<?php
/**
 * Listing Form Base Fields List
 *
 * The list of the fields for the form used to create the post listing.
 *
 * The Media types that use dropzone may have some additional data key like:
 * dzrefFilesData and dzPrefilledDataRef needed by the submit script to able to update and remove the medias from the
 * server.
 *
 * Some additional keys are not allowed within the original File type. Use with caution.
 *
 * Some notes:
 * 1. use -tax- to indicate that field is a taxonomy terms container.
 * 2. use -meta- to indicate that field is a post meta.
 *
 * For more info see the names attributes and the keys of the items list.
 *
 * @author     Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, Alfio Piccione
 * @license    GNU General Public License, version 2
 *
 * Copyright (C) 2018 Alfio Piccione <alfio.piccione@gmail.com>
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

use QiblaEvents\Functions as Fw;
use QiblaEvents\Form\Factories\FieldFactory;

$fieldFactory = new FieldFactory();
// Get the fields Values.
$fieldsValues = array(
    'post_title' => $post ? $post->post_title : '',
    'sub_title'  => Fw\getPostMeta('_qibla_mb_sub_title', '', $post),
);

// Clean the data to be coherent on what is expected.
$thumbnailData = array();

// Check for the ID or WordPress will return the first attachment post.
$thumbnailID = intval(Fw\getPostMeta('_qibla_mb_jumbotron_background_image', 0, $post));
if ($thumbnailID) {
    // Get and set the Thumbnail post data to able to work with dropzone.
    // @todo Need further checks for non ajax.
    $image = new \WP_Query(array(
        'post_type'        => 'attachment',
        'post_status'      => 'inherit',
        'suppress_filters' => true,
        'no_found_rows'    => true,
        'post__in'         => array($thumbnailID),
    ));

    if ($image->posts) {
        $image         = $image->posts[0];
        $thumbnailData = array(
            array(
                'id'       => $image->ID,
                'url'      => $image->guid,
                'name'     => basename($image->guid),
                'title'    => $image->post_title,
                'size'     => filesize(get_attached_file($image->ID)),
                'type'     => $image->post_mime_type,
                'accepted' => true,
            ),
        );
    }
    unset($image);
}
unset($thumbnailID);

/**
 * Listing Form Fields
 *
 * @since 1.0.0
 *
 * @param array $args The list of the fields
 */
return apply_filters('qibla_listings_form_base_fields', array(
    /**
     * Post Title
     *
     * @since 1.0.0
     */
    'qibla_listing_form-post_title:text'             => $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_listing_form-post_title',
        'label'               => esc_html__('Title', 'qibla-events'),
        'container_class'     => array('dl-field', 'dl-field--text', 'dl-field--in-column'),
        'attrs'               => array(
            'required'    => 'required',
            'placeholder' => esc_html_x('My awesome hotel', 'placeholder', 'qibla-events'),
            'value'       => $fieldsValues['post_title'],
        ),
        'invalid_description' => esc_html__('Please only valid characters.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
    )),

    /**
     * Post Title
     *
     * @since 1.0.0
     */
    'qibla_listing_form-meta-sub_title:text'         => $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_listing_form-meta-sub_title',
        'label'               => esc_html__('Tag line', 'qibla-events'),
        'container_class'     => array('dl-field', 'dl-field--text', 'dl-field--in-column'),
        'restriction'         => 'allow_sub_title',
        'invalid_description' => esc_html__('Please only valid characters.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
        'attrs'               => array(
            'placeholder' => esc_html_x('Best place in the whole world.', 'placeholder', 'qibla-events'),
            // For tests only.
            'value'       => $fieldsValues['sub_title'],
        ),
    )),

    /**
     * Featured Image
     *
     * @since 1.0.0
     */
    'qibla_listing_form-thumbnail:file'              => $fieldFactory->base(array(
        'type'                => 'file',
        'name'                => 'qibla_listing_form-thumbnail',
        'label'               => esc_html__('Upload the Main image', 'qibla-events'),
        'container_class'     => array('dl-field', 'dl-field--file', 'dl-field--clear-in-column'),
        'use_dropzone'        => true,
        'accept'              => 'image/jpeg,image/png',
        'invalid_description' => esc_html__('Please enter a valid image format.', 'qibla-events'),
        'dropzone'            => array(
            'thumbnailWidth'     => 1080,
            'thumbnailHeight'    => 607,
            'dzrefFilesData'     => $thumbnailData,
            'dzPrefilledDataRef' => 'qibla_listing_form-thumbnail_exists_ids',
            'dictDefaultMessage' => esc_html_x('Drop your listing main image here', 'placeholder', 'qibla-events'),
        ),
        'attrs'               => array(
            'required' => 'required',
        ),
    )),

    /**
     * Featured Image Dropzone Media ID
     *
     * Used to know the list of the files to remove after a request.
     *
     * @since 1.0.0
     */
    'qibla_listing_form-thumbnail_exists_ids:hidden' => $fieldFactory->base(array(
        'type'  => 'hidden',
        'name'  => 'qibla_listing_form-thumbnail_exists_ids',
        'attrs' => array(
            'value' => Fw\getPostMeta('_qibla_mb_jumbotron_background_image', array(), $post),
        ),
    )),
), $fieldsValues, $post);
