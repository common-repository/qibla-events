<?php
use \QiblaEvents\Functions as F;
use \QiblaEvents\Form\Factories\FieldFactory;

/**
 * Additional Listings Info Meta-box Fields
 *
 * @author  Alfio Piccione <alfio.piccione@gmail.com>
 *
 * @license GPL 2
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

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Gallery Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the gallery meta-box fields.
 */
return apply_filters('qibla_mb_inc_gallery_fields', array(
    /**
     * Gallery Background Color
     *
     * @since 1.0.0
     */
    'qibla_mb_jumbotron_background_color:color_picker' => $fieldFactory->base(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_mb_jumbotron_background_color',
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_jumbotron_background_color', 'rgba(255, 255, 255, .3)'),
        ),
        'label'       => esc_html__('Gallery Background Color', 'qibla-events'),
        'description' => esc_html__('Select the background color.', 'qibla-events'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Gallery Background Image
     *
     * @since 1.0.0
     */
//    'qibla_mb_jumbotron_background_image:media_image'  => $fieldFactory->base(array(
//        'type'        => 'media_image',
//        'name'        => 'qibla_mb_jumbotron_background_image',
//        'attrs'       => array(
//            'value' => F\getPostMeta('_qibla_mb_jumbotron_background_image'),
//        ),
//        'label'       => esc_html__('Gallery Background Image', 'qibla-events'),
//        'description' => esc_html__('Upload a background image', 'qibla-events'),
//        'display'     => array($this, 'displayField'),
//    )),

    /**
     * Images
     *
     * @since 1.0.0
     */
    'qibla_mb_images:media_image' => $fieldFactory->base(array(
        'type'        => 'media_image',
        'name'        => 'qibla_mb_images',
        'label'       => esc_html__('Insert Gallery', 'qibla-events'),
        'description' => esc_html__('Upload files to create a gallery.', 'qibla-events'),
        'display'     => array($this, 'displayField'),
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_images'),
        ),
        'btn'         => array(
            'data_multiple' => 'yes',
        ),
    )),
));
