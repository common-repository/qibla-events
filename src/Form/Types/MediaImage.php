<?php
namespace QiblaEvents\Form\Types;

use QiblaEvents\Form\Abstracts\MediaType;

/**
 * Form MediaImage Type
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
 * Class MediaImage
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class MediaImage extends MediaType
{
    /**
     * Constructor
     *
     * @since  1.0.0
     *
     * @param array $args The arguments for this type.
     */
    public function __construct($args)
    {
        $args = wp_parse_args($args, array(
            'btn' => array(
                'label'            => esc_html__('Add Image', 'qibla-events'),
                'data_media_title' => esc_html__('Upload Image', 'qibla-events'),
            ),
        ));

        // Force the mime type.
        $args['mime'] = 'image';

        parent::__construct($args);
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
        if (! did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }

        if (wp_script_is('admin-mediauploader-type', 'registered') &&
            wp_style_is('qibla-form-types', 'registered')
        ) {
            wp_enqueue_style('qibla-form-types');
            wp_enqueue_script('admin-mediauploader-type');
        }

        // Get the parent html.
        $output = parent::getHtml();

        $values = explode(',', $this->escape());
        $img    = '';
        foreach ($values as $value) {
            // Get the media.
            // Check the media not the value of the input because it may be set to '0' that is not considered as false.
            $media = wp_get_attachment_image_url($value, 'thumbnail');
            // Append the media image.
            $img .= $media ? sprintf(
                '<div class="dl-media-img-wrapper"><img data-id="%d" class="%s" src="%s" alt="" /></div>',
                intval($value),
                'dl-media-attachment',
                $media
            ) : '';
        }

        if (1 < count($values)) {
            $img = sprintf('<div class="dl-media-gallery">%s</div>', $img);
        }

        $output .= $img;

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_mediaimage_output', $output, $this);

        return $output;
    }
}
