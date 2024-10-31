<?php
/**
 * ListingLocationStore
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

/**
 * Class ListingLocationStore
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingLocationStore
{
    /**
     * The Listing Post
     *
     * @since  1.0.0
     *
     * @var \WP_Post The post associated to the data
     */
    private $post;

    /**
     * Latitude
     *
     * @since  1.0.0
     *
     * @var float The latitude value
     */
    private $latitude;

    /**
     * Longitude
     *
     * @since  1.0.0
     *
     * @var float The longitude value
     */
    private $longitude;

    /**
     * Address
     *
     * @since  1.0.0
     *
     * @var string The address value
     */
    private $address;

    /**
     * ListingLocationStore constructor
     *
     * @since  1.0.0
     *
     * @param \WP_Post $post The post associated to the data.
     * @param array    $args The data arguments, containing LAT, LNG and ADDRESS.
     */
    public function __construct(\WP_Post $post, array $args = array())
    {
        $this->post      = $post;
        $this->latitude  = $args['latitude'];
        $this->longitude = $args['longitude'];
        $this->address   = $args['address'];
    }

    /**
     * Store Data
     *
     * @since  1.0.0
     *
     * @param bool $update If the post is a newly post or an existing one that need updates.
     *
     * @return void
     */
    public function store($update = false)
    {
        // Save the post meta.
        if (! $this->latitude || ! $this->longitude) {
            throw new \LogicException('Cannot store latitude or longitude for listing. Invalid data.');
        }

        // Create the list for the LAT/LNG meta key=>value.
        $list = array_combine(
            array(ListingLocation::LISTING_LAT_META_KEY, ListingLocation::LISTING_LNG_META_KEY),
            array($this->latitude, $this->longitude)
        );

        foreach ($list as $metaKey => $value) {
            if ($update) {
                update_post_meta($this->post->ID, $metaKey, floatval($value));
            } else {
                add_post_meta($this->post->ID, $metaKey, floatval($value), true);
            }
        }

        // Set the address term.
        wp_set_object_terms($this->post->ID, array($this->address), ListingLocation::LISTING_ADDRESS_TAXONOMY, false);
    }

    /**
     * Create Instance From String
     *
     * @since  1.0.0
     *
     * @throws \InvalidArgumentException If location is not in the correct format or lat lng are not numeric values.
     * @throws \LengthException In case one of lat lng or address is missed.
     *
     * @param \WP_Post $post     The post instance.
     * @param string   $location The location string.
     *
     * @return ListingLocationStore The instance of the class
     */
    public static function createFromString(\WP_Post $post, $location)
    {
        if (false === strpos($location, ListingLocation::LATLNG_SEPARATOR) ||
            false === strpos($location, ListingLocation::COORDS_ADDRESS_SEPARATOR)
        ) {
            throw new \InvalidArgumentException('Invalid argument for ' . __METHOD__);
        }

        // Split LAT/LNG and ADDRESS.
        $parts = explode(ListingLocation::COORDS_ADDRESS_SEPARATOR, $location);
        if (2 !== count($parts)) {
            throw new \LengthException(__METHOD__ . ' Cannot continue, cannot build arguments.');
        }

        // Explode to retrieve the LAT/LNG values.
        $parts[0] = explode(ListingLocation::LATLNG_SEPARATOR, $parts[0]);
        // Check against the array item num and values type.
        if (2 !== count($parts)) {
            throw new \LengthException(__METHOD__ . ' Cannot continue, cannot build arguments.');
        }
        if (! is_numeric($parts[0][0]) || ! is_numeric($parts[0][1])) {
            throw new \InvalidArgumentException('Invalid argument for ' . __METHOD__);
        }

        return new static($post, array(
            'latitude'  => $parts[0][0],
            'longitude' => $parts[0][1],
            'address'   => $parts[1],
        ));
    }
}
