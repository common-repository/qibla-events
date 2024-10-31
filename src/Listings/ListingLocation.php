<?php
/**
 * ListingLocation
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

namespace QiblaEvents\Listings;

use QiblaEvents\Functions as F;

/**
 * Class ListingLocation
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingLocation
{
    /**
     * Listing Address Taxonomy Slug
     *
     * @since  1.0.0
     *
     * @var string The listing address taxonomy slug
     */
    const LISTING_ADDRESS_TAXONOMY = 'listings_address';

    /**
     * Listing Latitude Post Meta Key
     *
     * @since  1.0.0
     *
     * @var string The listing latitude post meta key
     */
    const LISTING_LAT_META_KEY = '_qibla_mb_map_location_lat';

    /**
     * Listing Longitude Meta Key
     *
     * @since  1.0.0
     *
     * @var string The listing longitude meta key
     */
    const LISTING_LNG_META_KEY = '_qibla_mb_map_location_lng';

    /**
     * Latitude Longitude Separator
     *
     * @since  1.0.0
     *
     * @var string The latitude longitude character separator
     */
    const LATLNG_SEPARATOR = ',';

    /**
     * The LAT LNG and Address separator
     *
     * @since  1.0.0
     *
     * @var string The Separator for LATLNG and Address
     */
    const COORDS_ADDRESS_SEPARATOR = ':';

    /**
     * The Listing Post
     *
     * @since  1.0.0
     *
     * @var \WP_Post The post from which retrieve the data
     */
    private $post;

    /**
     * Data
     *
     * @since  1.0.0
     *
     * @var object The data object
     */
    private $data;

    /**
     * Set Data
     *
     * @since  1.0.0
     *
     * @param \WP_Post $post The post from which retrieve the data.
     *
     * @return object The data as object
     */
    private function setData(\WP_Post $post)
    {
        // Stub.
        $data = array(
            'address' => '',
            'latLng'  => array(),
        );

        // Get the address associated to the post.
        $terms = wp_get_object_terms($post->ID, self::LISTING_ADDRESS_TAXONOMY);
        // Be sure we have a valid value.
        if (is_array($terms) && $terms) {
            $data = array(
                'address' => $terms[0]->name,
                'latLng'  => array(
                    floatval(F\getPostMeta(self::LISTING_LAT_META_KEY, 0, $post)),
                    floatval(F\getPostMeta(self::LISTING_LNG_META_KEY, 0, $post)),
                ),
            );
        }

        return (object)$data;
    }

    /**
     * ListingLocation constructor
     *
     * @since  1.0.0
     *
     * @param \WP_Post $post The post from which retrieve the data
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
        $this->data = $this->setData($post);
    }

    /**
     * To String
     *
     * @since  1.0.0
     *
     * @return string The string format of the location LAT,LNG:ADDRESS
     */
    public function __toString()
    {
        return $this->locationAsString();
    }

    /**
     * Clone
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function __clone()
    {
        trigger_error('Cloning for this object is not allowed', E_USER_ERROR);
    }

    /**
     * Location
     *
     * @since  1.0.0
     *
     * @see    ListingLocation::setData()
     *
     * @return object The location data
     */
    public function location()
    {
        return $this->data;
    }

    /**
     * Location as String
     *
     * Retrieve the location data in string format
     *
     * @since  1.0.0
     *
     * @return string The location data as a string
     */
    public function locationAsString()
    {
        // Be sure we have valid data. Avoid return strings like ',:'.
        if (! $this->isValidLocation()) {
            return '';
        }

        return $this->latitude() .
               self::LATLNG_SEPARATOR .
               $this->longitude() .
               self::COORDS_ADDRESS_SEPARATOR .
               $this->address();
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
        return $this->data->latLng[0];
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
        return $this->data->latLng[1];
    }

    /**
     * Address
     *
     * @since  1.0.0
     *
     * @return string The address of the listing
     */
    public function address()
    {
        return $this->data->address;
    }

    /**
     * Check if a valid location
     *
     * @since  1.0.0
     *
     * @return bool True if valid, false if some data is empty
     */
    public function isValidLocation()
    {
        return (bool)(
            $this->address() && $this->latitude() && $this->longitude()
        );
    }

    /**
     * Prevent to deserialize object
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function __wakeup()
    {
        trigger_error('You cannot deserialize this object.', E_USER_ERROR);
    }
}
