<?php
/**
 * Types
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

namespace QiblaEvents\ListingsContext;

/**
 * Class Types
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Types
{
    /**
     * Listings Types
     *
     * @since 1.0.0
     *
     * @var array The list of the listings post types
     */
    private $types;

    /**
     * Types constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->types = apply_filters('qibla_listings_types_list', array('events'));
    }

    /**
     * Types
     *
     * @since 1.0.0
     *
     * @return array The array of the exists post types
     */
    public function types()
    {
        return array_filter($this->types, function ($type) {
            return post_type_exists($type);
        });
    }

    /**
     * Is Listings Type
     *
     * Check if a post type is one of the listings post types.
     *
     * @since 1.0.0
     *
     * @param string $type The listing post type name to check
     *
     * @return bool True if $type is one of the post type listings. False if not or post type doesn't exists.
     */
    public function isListingsType($type)
    {
        if (! is_string($type) || ! post_type_exists($type)) {
            return false;
        }

        return in_array($type, $this->types(), true);
    }
}
