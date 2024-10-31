<?php
/**
 * Listing Form Gallery Fields List
 *
 * The list of the fields for the form used to create the post listing.
 *
 * The Media types that use dropzone may have some additional data key like:
 * dzrefFilesData and dzPrefilledDataRef needed by the submit script to able to update and remove the medias from the
 * server.
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

// Set the number of images allowed for gallery.
$imageNum = intval(\QiblaEvents\Functions\getPostMeta(
    '_qibla_listings_mb_restriction_max_gallery_images_number',
    1
));

// Initialize the container for the thumbnail data.
$galleryData = array();
// Get and set the Gallery Thumbnails post data to able to work with dropzone.
// @todo Need further checks for non ajax.
$ids    = array_map('intval', explode(',', Fw\getPostMeta('_qibla_mb_images', '', $post)));
$images = new \WP_Query(array(
    'post_type'        => 'attachment',
    'post__in'         => $ids,
    'suppress_filters' => true,
    'no_found_rows'    => true,
    'post_status'      => 'inherit',
));

if ($images->posts) {
    // Keep only the allowed number of posts.
    $images->posts = array_splice($images->posts, 0, $imageNum);
    // Loop.
    foreach ($images->posts as $image) {
        $thumb           = wp_get_attachment_image_src($image->ID, 'thumbnail');
        $galleryData[] = array(
            'id'       => $image->ID,
            'url'      => $thumb[0],
            'name'     => basename($image->guid),
            'title'    => $image->post_title,
            'size'     => filesize(get_attached_file($image->ID)),
            'type'     => $image->post_mime_type,
            'accepted' => true,
        );
    }
}
unset($ids, $images, $image);

// Get the fields Values.
$fieldsValues = array(
    'gallery_exists_ids' => $post ? Fw\getPostMeta('_qibla_mb_images', array(), $post) : array(),
);

return apply_filters('qibla_listings_form_gallery_fields', array(
    /**
     * Gallery
     *
     * @since 1.0.0
     */
    'qibla_listing_form-gallery:file'              => $fieldFactory->base(array(
        'type'                => 'file',
        'name'                => 'qibla_listing_form-gallery',
        'label'               => esc_html__('Upload images for gallery', 'qibla-events'),
        'accept'              => 'image/jpeg,image/png',
        'use_dropzone'        => true,
        'skip_upload'         => true,
        'restriction'         => 'allow_gallery',
        'invalid_description' => esc_html__('Please enter valid images format.', 'qibla-events'),
        'dropzone'            => array(
            'dictDefaultMessage' => esc_html__('Gallery images', 'qibla-events'),
            'parallelUploads'    => $imageNum,
            'maxFiles'           => $imageNum,
            'uploadMultiple'     => true,
            'dzrefFilesData'     => $galleryData,
            'dzPrefilledDataRef' => 'qibla_listing_form-gallery_exists_ids',
        ),
    )),

    /**
     * Gallery Images Dropzone Media IDs
     *
     * Used to know the list of the files to remove after a request.
     *
     * @since 1.0.0
     */
    'qibla_listing_form-gallery_exists_ids:hidden' => $fieldFactory->base(array(
        'type'  => 'hidden',
        'name'  => 'qibla_listing_form-gallery_exists_ids',
        'attrs' => array(
            'value' => $fieldsValues['gallery_exists_ids'],
        ),
    )),
), $fieldsValues, $post);
