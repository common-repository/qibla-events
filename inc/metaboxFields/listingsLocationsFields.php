<?php
/**
 * Listings Locations Meta-box Fields
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

use \QiblaEvents\Form\Factories\FieldFactory;
use \QiblaEvents\Listings\ListingLocation;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();
// Listing Location Instance.
$listingLocation = new ListingLocation($post);

/**
 * Filter Map Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the map meta-box fields.
 */
return apply_filters('qibla_mb_inc_map_fields', array(
    /**
     * Google Map Location
     *
     * @since 1.0.0
     */
    'qibla_mb_map_location:map' => $fieldFactory->base(array(
        'type'        => 'map',
        'name'        => 'qibla_mb_map_location',
        'attrs'       => array(
            'value'              => $listingLocation->locationAsString() ?:
                '40.7127837,-74.00594130000002:New York, NY, USA',
            'data-append-map-to' => 'dl-field',
            'class'              => array('widefat'),
        ),
        'label'       => esc_html__('Address', 'qibla-events'),
        'description' => esc_html__(
            'Type the address of the location. Coordinate if no js.',
            'qibla-events'
        ),
        'display'     => array($this, 'displayField'),
    )),
));