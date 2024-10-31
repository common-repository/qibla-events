<?php
/**
 * Theme Option Export
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

use QiblaEvents\Form\Interfaces\Types;
use QiblaEvents\Functions as F;
use QiblaEvents\Plugin;

/**
 * Class Export
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Export
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
     * Construct
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        global $wp_filesystem;

        if (! $wp_filesystem) {
            WP_Filesystem();
        }

        // Clone instead of get a reference.
        $this->fs = clone $wp_filesystem;
    }

    /**
     * Retrieve the option
     *
     * @since  1.0.0
     *
     * @param Types $type   The type of the option.
     * @param mixed $option The option value.
     *
     * @return mixed The option value.
     */
    protected function getOption($type, $option)
    {
        $data = null;

        switch ($type->getArg('type')) {
            /*
             * Media
             *
             * Retrieve the Media based on the ID value.
             * The media must be converted to a base64 to able to export.
             */
            case 'media_image':
                $attachID = intval($option);
                // Try to retrieve the media post data.
                $mediaData = wp_get_attachment_metadata($attachID);

                if (! $mediaData) {
                    break;
                }

                // Retrieve the path of the file.
                $wpUploadDir = wp_upload_dir();
                $filePath    = untrailingslashit($wpUploadDir['basedir']) . '/' . $mediaData['file'];

                if (! $this->fs->exists($filePath)) {
                    break;
                }

                // Remove not needed meta data.
                unset($mediaData['sizes']);

                $data = array(
                    'metadata' => $mediaData,
                    'ID'       => $attachID,
                    'content'  => base64_encode($this->fs->get_contents($filePath)),
                );
                break;
            default:
                $data = $option;
                break;
        }

        return $data;
    }

    /**
     * Export
     *
     * @since  1.0.0
     *
     * @throws \Exception If data cannot be encoded
     *
     * @return void
     */
    public function export()
    {
        $data = array();
        // Retrieve the list of the options to import.
        $settings = include Plugin::getPluginDirPath('/inc/settingsMenuList.php');

        foreach ($settings as $setting) :
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
            foreach ($obj->getFields() as $field) {
                // Retrieve the type object within the field.
                $type = $field->getType();
                // Clean the option name.
                $opt = str_replace('qibla_opt-' . $optionName . '-', '', $type->getArg('name'));
                // Retrieve the default just in case.
                $option = F\getPluginOption($optionName, $opt, true);
                // Retrieve the option based on type.
                $option = $this->getOption($type, $option);

                if (! $option) {
                    continue;
                }

                // Include the option.
                $data[$optionName][sanitize_key($opt)] = $option;
            }
        endforeach;

        // Encode to json so, we can store the data within a file.
        $data = wp_json_encode($data);

        if (! $data) {
            throw new \Exception(
                'Error during processing data. Sorry, we are not able to perform an export of the theme option.'
            );
        }
        // Get plugin data.
        $pluginData = get_plugin_data(Plugin::getPluginDirPath('/index.php'), false);
        // Retrieve the theme name to use as file name.
        $fileName = sanitize_key($pluginData['Name']) . '-options_backup.json';

        // Clean the output to prevent page content to be inserted into the option data file.
        ob_clean();
        header('Content-type: application/json');
        header('X-Cache-Enabled: False');
        header('Content-Disposition: attachment; filename=' . $fileName);

        // Send the Data.
        echo $data;
        die();
    }
}
