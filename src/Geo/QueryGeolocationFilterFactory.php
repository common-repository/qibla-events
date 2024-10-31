<?php
/**
 * Query Geolocation Filter Builder
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace QiblaEvents\Geo;

use AnthonyMartin\GeoLocation\GeoLocation;
use QiblaEvents\Listings\ListingLocation;
use QiblaEvents\Query\MetaQueryArguments;

/**
 * Class QueryGeolocationFilterFactory
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class QueryGeolocationFilterFactory
{
    /**
     * Create
     *
     * @since 1.0.0
     *
     * @param \WP_Query $wpQuery The query to filter.
     * @param LatLng    $latLng  The latitude and longitude for which filter the posts.
     *
     * @return QueryGeolocationFilter The instance of the class.
     */
    public static function create(\WP_Query $wpQuery, LatLng $latLng)
    {
        $boundingCoordsMetaQueryArguments = new BoundingCoordsMetaQueryArguments(
            GeoLocation::fromDegrees($latLng->latitude(), $latLng->longitude()),
            ListingLocation::LISTING_LAT_META_KEY,
            ListingLocation::LISTING_LNG_META_KEY
        );

        return new QueryGeolocationFilter($wpQuery, new MetaQueryArguments(
            $boundingCoordsMetaQueryArguments->listingBoundingMetaQueryArguments()
        ));
    }
}
