<?php

/**
 * Listing Form Content Fields List
 *
 * @author    Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright Copyright (c) 2018, Alfio Piccione
 * @license   GNU General Public License, version 2
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

use QiblaEvents\Form\Factories\FieldFactory;

$fieldFactory = new FieldFactory();

// Get the fields Values.
$fieldsValues = array(
    'post_content' => $post ? $post->post_content : '',
);

/**
 * Listing Form Content Fields
 *
 * @since 1.0.0
 *
 * @param array $args The list of the fields
 */
return apply_filters('qibla_listings_form_content_fields', array(
    /**
     * Content
     *
     * @since 1.0.0
     */
    'qibla_listing_form-post_content:wysiwyg' => $fieldFactory->base(array(
        'type'                => 'wysiwyg',
        'name'                => 'qibla_listing_form-post_content',
        'label'               => esc_html__('Listing content', 'qibla-events'),
        'editor_settings'     => array(
            'tinymce'       => true,
            'teeny'         => true,
            'media_buttons' => false,
            'quicktags'     => false,
            'textarea_rows' => 8,
            'paste_as_text' => true,
        ),
        'attrs'               => array(
            'required' => 'required',
        ),
        'invalid_description' => esc_html__('Please only valid characters.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
        // For tests only.
        'value'               => $fieldsValues['post_content'],
    )),
), $fieldsValues, $post);
