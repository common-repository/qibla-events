<?php
/**
 * Latitude Longitude
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

/**
 * Class LatLng
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class LatLng
{
    /**
     * Latitude Longitude Separator
     *
     * @since  1.0.0
     *
     * @var string The latitude longitude character separator
     */
    const LATLNG_SEPARATOR = ',';

    /**
     * Latitude
     *
     * @since  1.0.0
     *
     * @var float The float value that represent the latitude
     */
    private $latitude;

    /**
     * Longitude
     *
     * @since  1.0.0
     *
     * @var float The float value that represent the longitude
     */
    private $longitude;

    /**
     * LatLng constructor
     *
     * @since 1.0.0
     *
     * @param float $latitude  The float value that represent the latitude.
     * @param float $longitude The float value that represent the longitude.
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude  = floatval($latitude);
        $this->longitude = floatval($longitude);
    }

    /**
     * Latitude
     *
     * @since  1.0.0
     *
     * @return float The latitude value
     */
    public function latitude()
    {
        return $this->latitude;
    }

    /**
     * Longitude
     *
     * @since  1.0.0
     *
     * @return float The longitude value
     */
    public function longitude()
    {
        return $this->longitude;
    }

    /**
     * Is Valid
     *
     * @since 1.0.0
     *
     * @return bool True if both lat and lng return a value. False otherwise.
     */
    public function isValid()
    {
        return $this->latitude() && $this->longitude();
    }

    /**
     * As Associative
     *
     * @since 1.0.0
     *
     * @return array An associative array with latitude and longitude values
     */
    public function asAssoc()
    {
        return array(
            'lat' => $this->latitude(),
            'lng' => $this->longitude(),
        );
    }

    /**
     * To String
     *
     * @since  1.0.0
     *
     * @return string The string version of the lat lng
     */
    public function __toString()
    {
        return $this->latitude() . self::LATLNG_SEPARATOR . $this->longitude();
    }
}
