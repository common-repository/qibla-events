<?php
/**
 * Plugin Option Import
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Guido Scialfa <dev@guidoscialfa.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace QiblaEvents\Admin\Settings;

use QiblaEvents\Functions as F;
use QiblaEvents\Plugin;

/**
 * Class Import
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Import
{
    /**
     * FileSystem
     *
     * @since  1.0.0
     *
     * @var \WP_Filesystem_Direct The instance of the filesystem direct
     */
    protected $fs;

    /**
     * Get File Content
     *
     * @since  1.0.0
     *
     * @throws \Exception In case the file doesn't exists or is not a valid file.
     *
     * @param array $data A $_FILES array like from which retrieve the data to get the content.
     *
     * @return bool|string
     */
    protected function getFileContent($data)
    {
        if ('application/json' !== $data['type']) {
            throw new \Exception('Cannot import the file. Wrong file format.');
        }

        if (! $data['size'] || $data['error']) {
            throw new \Exception(
                'Cannot process the file, May be an error during upload the data or the file is empty'
            );
        }

        // Get the file path.
        $filePath = $data['tmp_name'];
        if (! $this->fs->is_file($filePath) || ! $this->fs->exists($filePath)) {
            throw new \Exception('Cannot retrieve file content. File is not valid or doesn\'t exists.');
        }

        // Try to retrieve the content.
        return $this->fs->get_contents($filePath);
    }

    /**
     * Extract data
     *
     * Extract data from the file content.
     *
     * @since  1.0.0
     *
     * @throws \Exception If for some reason the content cannot be extract.
     *
     * @param array $data A $_FILES array like from which retrieve the content to extract
     *
     * @return array Extracted data
     */
    protected function extract($data)
    {
        $content = $this->getFileContent($data);

        if (! $content) {
            throw new \Exception('Error on extract options. Content is empty.');
        }

        // Try to retrieve the data.
        $content = json_decode($content);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception('Error during extract options. Wrong data format.');
        }

        return (array)$content;
    }

    /**
     * Insert Attachment
     *
     * @since  1.0.0
     *
     * @throws \Exception If for some reason the file attachment cannot be inserted.
     *
     * @param array $option An array containing metadata and file content as base64 string.
     *
     * @return int The id of the attachment created. Zero if file exists.
     */
    protected function insertAttachment($option)
    {
        if (! isset($option['metadata']->file)) {
            return 0;
        }

        // Get the upload dir.
        $uploadDir = wp_upload_dir();

        // Check if file exists, if so, don't upload it again.
        $filePath = untrailingslashit($uploadDir['basedir']) . '/' . ltrim($option['metadata']->file, '/');

        // Try to retrieve the post.
        // First of all we need to get the postID if possible, by the file url
        // because we cannot trusth in $option['ID']. If we cannot retrieve the post by url try the ID of the option.
        // The option id may not exists within the current db since we are importing an external file,
        // this is why is not a trusted data.
        if ($this->fs->exists($filePath)) :
            // Try to retrieve the post from url, fallback to option ID.
            $postID = attachment_url_to_postid(F\switchUploadDirPathUrl($filePath, 'dir>url')) ?:
                (isset($option['ID']) ? $option['ID'] : 0);

            if ($postID) {
                $post = get_post($postID);
                if ($post && 'attachment' === $post->post_type) {
                    return $post->ID;
                }
            }

            // If otherwise the post doesn't exists but the file is within the directory,
            // there is probably an issue, so avoid to upload again the file.
            return 0;
        else :
            // If the file doesn't exists may be the directory where to upload.
            // Try to create all of the directories where the file must be stored.
            $dirs = dirname($filePath);
            // Remove the upload dir part from the dirs.
            $dirs = trim(str_replace($uploadDir['basedir'], '', $dirs), '/');

            // Have we something to create?
            if (false !== strpos($dirs, '/')) {
                // Retrieve the single directories.
                $dirs = explode(DIRECTORY_SEPARATOR, $dirs);
                // Create the directories.
                // Every directory must be created by the previous one.
                // This means we need to concatenate the current and the previous directory value.
                $currDir = $uploadDir['basedir'];
                foreach ($dirs as $dir) {
                    $currDir = untrailingslashit($currDir) . '/' . $dir;

                    if (! $this->fs->exists($currDir)) {
                        $this->fs->mkdir($currDir);
                    }
                }
            }
        endif;

        // Ok, if we have content go head.
        if (! isset($option['content']) || ! $option['content']) {
            throw new \Exception('Error during import options. Attachment File content is empty.');
        }

        // Upload the Media.
        if (! $this->fs->put_contents($filePath, base64_decode($option['content']))) {
            throw new \Exception('Error during import options. Cannot write file content.');
        }

        // Check the type of file. We'll use this as the 'post_mime_type'.
        $fileType = wp_check_filetype(basename($filePath), null);
        $attachID = wp_insert_attachment(array(
            'guid'           => F\switchUploadDirPathUrl($filePath, 'dir>url'),
            'post_mime_type' => $fileType['type'],
            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filePath)),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ), $filePath);

        // Throw an error if something went wrong.
        if (is_wp_error($attachID)) {
            throw new \Exception($attachID->get_error_message());
        }

        // Update the meta data of the newly attachment.
        // Don't care about the issue with metadata right know. A logger will be introduced later in a far future XD.
        if ($attachID) {
            wp_update_attachment_metadata($attachID, wp_generate_attachment_metadata($attachID, $filePath));
        }

        return $attachID;
    }

    /**
     * Construct
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        global $wp_filesystem;
        if (! $wp_filesystem) {
            // Make sure the WP_Filesystem function exists.
            F\requireWPFileSystem();
            WP_Filesystem();
        }

        // Clone instead of get a reference.
        $this->fs = clone $wp_filesystem;
    }

    /**
     * Import
     *
     * @since  1.0.0
     *
     * @throws \Exception In case some option are not valid.
     *
     * @param array $data The file from which retrieve the data to import.
     *
     * @return void
     */
    public function import($data)
    {
        // Unpack options data.
        $data = $this->extract($data);
        // Retrieve the list of the options to import.
        $settings = include Plugin::getPluginDirPath('/inc/settingsMenuList.php');
        // Imported option list result. Contains stored and not stored data.
        $result = array(
            'invalid' => array(),
        );

        foreach ($settings as $setting) :
            // The list of the option to save within the database.
            $options = array();
            // Build the option Class name.
            $setting   = str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', $setting['menu_slug'])));
            $className = 'QiblaEvents\\Admin\\Settings\\' . $setting;

            if (! class_exists($className)) {
                continue;
            }

            // Create the setting class instance.
            $obj = new $className;
            // The option name is the same of the Form name argument.
            $optionName = $obj->getArg('name');

            // Export field by field.
            foreach ($obj->getFields() as $field) :
                // Retrieve the type object within the field.
                $type = $field->getType();
                // Clean the option name.
                $opt = str_replace('qibla_opt-' . $optionName . '-', '', $type->getArg('name'));

                // Discard non existing data.
                // Since we are processing the form fields, all of the fields inside a settings form may be not
                // saved within the database and of course within the backup file.
                // Also it is not convenient to switch to a default value bacause most options are optional.
                if (! isset($data[$optionName]->$opt)) {
                    continue;
                }

                // Retrieve the option value.
                $option = $data[$optionName]->$opt;
                // Convert to array instead of work with object.
                // The object exists only within the json saved and not within the options data.
                $option = ('object' === gettype($option)) ? (array)$option : $option;

                // It's a media attachment?
                if (is_array($option) && isset($option['metadata'])) {
                    // If so, upload the attachment.
                    $option = intval($this->insertAttachment($option));
                } else {
                    // Sanitize the option value.
                    $option = $type->sanitize($option);
                }

                // Store the option to able to save later.
                $options[$type->getArg('name')] = $option;
            endforeach;

            if (! empty($options)) {
                $store = new Store($obj->getArg('name'), 'qibla_opt-');
                $store->store($options);
            }
        endforeach;
    }
}
