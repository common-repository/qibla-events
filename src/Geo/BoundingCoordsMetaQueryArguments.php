<?php
/**
 * Bounding Box Coordinates Meta Query Arguments
 *
 * The class will additional build a query considering a bound box where the listing must be located in
 * in order to be retrieved by the database.
 *
 * Use The AnthonyMartin\GeoLocation\GeoLocation class to create the bounding and to pass the data as value
 * for the meta query.
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

/**
 * Class BoundingCoordsMetaQueryArguments
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class BoundingCoordsMetaQueryArguments
{
    /**
     * Geolocation
     *
     * @since  1.0.0
     *
     * @var GeoLocation The instance of the geolocation
     */
    private $geolocation;

    /**
     * Latitude Meta Key
     *
     * @since  1.0.0
     *
     * @var string The latitude meta key to use to retrieve the data
     */
    private $latMetaKey;

    /**
     * Longitude Meta Key
     *
     * @since  1.0.0
     *
     * @var string The longitude meta key to use to retrieve the data
     */
    private $lngMetaKey;

    /**
     * Distance
     *
     * The distance is treated as km by default.
     *
     * @since  1.0.0
     *
     * @var float The number that identify the distance in km by the center lat/lng to calculate the bounding box
     */
    private $distance;

    /**
     * BoundingCoordsMetaQueryArguments constructor
     *
     * @since 1.0.0
     *
     * @param GeoLocation $geolocation The instance of the geolocation.
     * @param string      $latMetaKey  The latitude meta key to use to retrieve the data.
     * @param string      $lngMetaKey  The longitude meta key to use to retrieve the data.
     * @param float       $distance    The number that identify the distance in km by the center lat/lng to calculate
     *                                 the bounding box.
     */
    public function __construct(GeoLocation $geolocation, $latMetaKey, $lngMetaKey, $distance = 5.0)
    {
        $this->geolocation = $geolocation;
        $this->latMetaKey  = $latMetaKey;
        $this->lngMetaKey  = $lngMetaKey;
        $this->distance    = $distance;
    }

    /**
     * Get Bounding Box in Degrees
     *
     * @since  1.0.0
     *
     *  Return a list like:
     * [
     *    [ MinLat, MinLng ]
     *    [ MaxLat, MaxLng ]
     * ]
     *
     * @return array The list of the min/max latitude and longitude.
     */
    private function boundingBoxInDegrees()
    {
        $bounding = $this->geolocation->boundingCoordinates($this->distance, 'km');

        return array(
            array(
                // Min Lat.
                $bounding[0]->getLatitudeInDegrees(),
                // Min Lng.
                $bounding[0]->getLongitudeInDegrees(),
            ),
            array(
                // Max Lat.
                $bounding[1]->getLatitudeInDegrees(),
                // Max Lng.
                $bounding[1]->getLongitudeInDegrees(),
            ),
        );
    }

    /**
     * Build the Meta Query Arguments
     *
     * @since  1.0.0
     *
     * @link   https://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
     *
     * @return array The array that form the meta query arguments.
     */
    public function listingBoundingMetaQueryArguments()
    {
        // Get bounding box.
        $bounding = $this->boundingBoxInDegrees();

        return array(
            'relation' => 'AND',
            array(
                'key'     => $this->latMetaKey,
                // Min - max latitude.
                'value'   => array($bounding[0][0], $bounding[1][0]),
                'type'    => 'DECIMAL(16,14)',
                'compare' => 'BETWEEN',
            ),
            array(
                'key'     => $this->lngMetaKey,
                // Min - max Longitude.
                'value'   => array($bounding[0][1], $bounding[1][1]),
                'type'    => 'DECIMAL(16,14)',
                'compare' => 'BETWEEN',
            ),
        );
    }
}
