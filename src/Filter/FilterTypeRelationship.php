<?php
/**
 * FilterTypeRelationship
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

namespace QiblaEvents\Filter;

use QiblaEvents\Plugin;

/**
 * Class FilterTypeRelationship
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class FilterTypeRelationship
{
    /**
     * List
     *
     * @since 1.0.0
     *
     * @var array The list that contain the listings filters relationships
     */
    private $list;

    /**
     * Type exists in list
     *
     * @since 1.0.0
     *
     * @param string $type The key to check against.
     *
     * @return bool True if exists, false otherwise
     */
    private function exists($type)
    {
        return array_key_exists($type, $this->list);
    }

    /**
     * FilterTypeRelationship constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->list = include Plugin::getPluginDirPath('/inc/listingsFiltersRelationship.php');
    }

    /**
     * Filters
     *
     * @since 1.0.0
     *
     * @param string $type The type of the listings for which retrieve the relationships.
     *
     * @return array The relationship items or empty array if type doesn't exists in the list
     */
    public function filters($type)
    {
        if (! $this->exists($type)) {
            return array();
        }

        return $this->list[$type];
    }
}
