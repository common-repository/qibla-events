<?php
namespace QiblaEvents\Form\Types;

use QiblaEvents\Form\Abstracts\Type;

/**
 * Form File Type
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Types
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

/**
 * Class File
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class File extends Type
{
    /**
     * Dropzone Dictionary
     *
     * Strings used during Dropzone actions.
     *
     * @since  1.0.0
     *
     * @var array
     */
    protected static $dropzoneDict = null;

    /**
     * Get Dropzone Dictionary
     *
     * @since  1.0.0
     *
     * @return array The dictionary to use in Dropzone context
     */
    protected static function getDropzoneDict()
    {
        if (! static::$dropzoneDict) :
            static::$dropzoneDict = array(
                'dictDefaultMessage'           => esc_html__(
                    'Drop files here to upload',
                    'qibla-events'
                ),
                'dictFallbackMessage'          => esc_html__(
                    'Your browser does not support drag\'n\'drop file uploads.',
                    'qibla-events'
                ),
                'dictFallbackText'             => esc_html__(
                    'Please use the fallback form below to upload your files like in the olden days.',
                    'qibla-events'
                ),
                'dictFileTooBig'               => esc_html__(
                    'File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.',
                    'qibla-events'
                ),
                'dictInvalidFileType'          => esc_html__(
                    'You can not upload files of this type.',
                    'qibla-events'
                ),
                'dictResponseError'            => esc_html__(
                    'Server responded with {{statusCode}} code.',
                    'qibla-events'
                ),
                'dictCancelUpload'             => esc_html__(
                    'Cancel upload',
                    'qibla-events'
                ),
                'dictCancelUploadConfirmation' => esc_html__(
                    'Are you sure you want to cancel this upload?',
                    'qibla-events'
                ),
                'dictRemoveFile'               => esc_html__(
                    'Remove file',
                    'qibla-events'
                ),
                'dictMaxFilesExceeded'         => esc_html__(
                    'You can not upload any more files.',
                    'qibla-events'
                ),
            );
        endif;

        return static::$dropzoneDict;
    }

    /**
     * Get the Dropzone Json
     *
     * The json that define the options for the dropzone
     *
     * @since  1.0.0
     *
     * @param array $filesInfo The additional files info data if provided. Used when we need to retrieve data form
     *                         server. For example from a post.
     *
     * @return string The json or empty string if cannot be convert to a json string.
     */
    protected function getJsonDropzoneOptions($filesInfo)
    {
        return wp_json_encode(array_merge($this->getArg('dropzone'), array('dzrefFilesData' => $filesInfo))) ?: '';
    }

    /**
     * Set Dropzone Reference Options
     *
     * The method set the wp_footer action callback to add the variable where the options
     * are stored.
     * Every variable is unique and built starting by the input file name attribute.
     *
     * @since  1.0.0
     *
     * @return void
     */
    protected function setDropzoneRefOptions()
    {
        // Add the options to the page by variable to use it within the script.
        $self = $this;
        add_action('wp_footer', function () use ($self) {
            // Get the ref variable from the attrs def list.
            $atts         = $self->getArg('attrs');
            $dropzoneArgs = $self->getArg('dropzone');
            $ref          = $atts['data-dzref'];
            $dzOptions    = $self->getJsonDropzoneOptions($dropzoneArgs['dzrefFilesData']);

            printf(
                '<script type="text/javascript">/* <![CDATA[ */var %1$s = %2$s/* ]]> */</script>',
                $ref,
                $dzOptions
            );
        });
    }

    /**
     * Make Camel Case Dropzone Ref
     *
     * The method will get the name attribute value and replace it with a
     * camel case string allowed to used as variable.
     *
     * @since  1.0.0
     *
     * @param string $name The attribute name value.
     *
     * @return string The camel case attribute value
     */
    protected function makeCamelCaseDzRef($name)
    {
        $ref = preg_replace('/[^a-z0-9]/', ' ', $name . '_dz_settings');
        $ref = preg_replace('/\s{2,}/', '\s', $ref);
        $ref = ucwords($ref);
        $ref = str_replace(' ', '', $ref);

        return lcfirst($ref);
    }

    /**
     * Constructor
     *
     * @since  1.0.0
     *
     * @param array $args The arguments for this type.
     *
     * $args {
     *      use_wp_media: bool To skip the upload. Just want to validate it.
     *      use_dropzone: bool To use the dropzone script instead of the default file input.
     *      dropzone: array The dropzone arguments.
     * }
     */
    public function __construct($args)
    {
        $args = wp_parse_args($args, array(
            'use_wp_media'      => false,
            'use_dropzone'      => false,
            'use_btn'           => false,
            'file_submit_label' => esc_html__('Choose File', 'qibla-events'),
            'dropzone'          => array(),
            'accept'            => '',
            'max_size'          => wp_max_upload_size(),
        ));

        // Dropzone Arguments.
        // @todo Need dict localized text. See http://www.dropzonejs.com/#configuration.
        $args['dropzone'] = wp_parse_args($args['dropzone'], array_merge(array(
            'parallelUploads'  => 1,
            'maxFilesize'      => $args['max_size'] / MB_IN_BYTES,
            'filesizeBase'     => 1024,
            'thumbnailWidth'   => 150,
            'thumbnailHeight'  => 150,
            'paramName'        => $args['name'],
            'uploadMultiple'   => true,
            // Set to false the removeLinks because we use a custom element.
            'addRemoveLinks'   => false,
            'clickable'        => true,
            'maxFiles'         => 1,
            'acceptedFiles'    => isset($args['accept']) ? $args['accept'] : '',
            'autoProcessQueue' => false,
            'dzrefFilesData'   => array(),
            'media_action'     => array(
                'name' => '',
            ),
        ), static::getDropzoneDict()));

        // Force Type.
        $args['type'] = 'file';

        if (empty($args['attrs']['class'])) {
            $args['attrs']['class'] = array();
        }

        // Set the own class.
        $args['attrs']['class'] = array_merge($args['attrs']['class'], array(
            'type-file',
            $args['use_dropzone'] ? 'use-dropzone' : '',
        ));

        // Set additional attrs.
        $args['attrs'] = array_merge($args['attrs'], array(
            // The dzref is the name of the variable used in javascript to get the settings for the dropzone.
            'data-dzref' => $this->makeCamelCaseDzRef($args['name']),
        ));

        parent::__construct($args);
    }

    /**
     * Sanitize
     *
     * @since  1.0.0
     *
     * @param mixed $value The value to sanitize.
     *
     * @return string The sanitized value of this type
     */
    public function sanitize($value)
    {
        return $value;
    }

    /**
     * Escape
     *
     * @since  1.0.0
     *
     * @return string The escaped value of this type
     */
    public function escape()
    {
        return $this->getValue();
    }

    /**
     * Get Html
     *
     * @since  1.0.0
     *
     * @return string The html version of this type
     */
    public function getHtml()
    {
        if ($this->getArg('use_dropzone') &&
            wp_script_is('file-type', 'registered') &&
            wp_style_is('dropzone', 'registered')
        ) {
            wp_enqueue_style('dropzone');
            wp_enqueue_script('file-type');

            // Set the options for the dropzone script.
            $this->setDropzoneRefOptions();
        }

        if ($this->getArg('use_btn')) {
            $inputPlaceholder = '<span class="type-file-wrapper button button-secondary"><span>' .
                                $this->getArg('file_submit_label') .
                                '</span><input type="%1$s" name="%2$s" id="%3$s"%4$s /></span>';
        } else {
            $inputPlaceholder = '<input type="%1$s" name="%2$s" id="%3$s"%4$s />';
        }

        $output = sprintf(
            $inputPlaceholder,
            sanitize_key($this->getArg('type')),
            esc_attr($this->getArg('name')),
            esc_attr($this->getArg('id')),
            $this->getAttrs()
        );

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_file_output', $output, $this);

        return $output;
    }
}
