<?php
/**
 * Location Form Fields
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

// Field Factory Instance.
$fieldFactory = new FieldFactory();
// Listing Location Instance.
// Remember this is used even for create and update.
$listingLocation = $post ? new \QiblaEvents\Listings\ListingLocation($post) : null;
// Fields Values.
$fieldsValues = array(
    'map_location' => $listingLocation ? $listingLocation->locationAsString() : '',
);

/**
 * Listing Form Fields
 *
 * @since 1.0.0
 *
 * @param array $args The list of the fields
 */
return apply_filters('qibla_listings_form_location_fields', array(
    /**
     * Location Map
     *
     * @since 1.0.0
     */
    'qibla_listing_form-map_location:map' => $fieldFactory->base(array(
        'type'                => 'map',
        'name'                => 'qibla_listing_form-map_location',
        'label'               => esc_html__('Address', 'qibla-events'),
        'attrs'               => array(
            'required'           => 'required',
            'data-append-map-to' => 'dl-field',
            'value'              => $fieldsValues['map_location'],
            'autocomplete'       => 'off',
        ),
        'invalid_description' => esc_html__('Please only valid address.', 'qibla-events'),
        'after_input'         => function ($obj) {
            return $obj->getType()->getArg('is_invalid') ? $obj->getInvalidDescription() : '';
        },
        'map_options'         => array(
            'scrollwheel' => false,
        ),
    )),
), $fieldsValues, $post);
