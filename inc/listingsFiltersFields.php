<?php
/**
 * Listings Filter Fields
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

return apply_filters('qibla_listings_filters_fields', array(
    'qibla_tags_filter'   => array(
        'filter' => 'QiblaEvents\\Filter\\ListingsAmenitiesFilter',
        'field'  => 'QiblaEvents\\Filter\\ListingsAmenitiesField',
        'type'   => 'multi-check',
    ),
    'qibla_dates_filter'   => array(
        'filter' => 'QiblaEvents\\Filter\\ListingsDatesFilter',
        'field'  => 'QiblaEvents\\Filter\\ListingsDatesField',
        'type'   => 'select',
    ),
    'qibla_event_categories_filter' => array(
        'filter' => 'QiblaEvents\\Filter\\ListingsCategoriesFilter',
        'field'  => 'QiblaEvents\\Filter\\ListingsCategoriesField',
        'type'   => 'select',
    ),
    'qibla_locations_filter'   => array(
        'filter' => 'QiblaEvents\\Filter\\ListingsLocationsFilter',
        'field'  => 'QiblaEvents\\Filter\\ListingsLocationsField',
        'type'   => 'select',
    ),
));
