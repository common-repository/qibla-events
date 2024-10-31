<?php
/**
 * ListingsFilterFields
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
 * Class ListingsFilterFields
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingsFiltersFields
{
    /**
     * List
     *
     * @since 1.0.0
     *
     * @var array The list of the data that define the filter and fields.
     */
    private $list;

    /**
     * ListingsFiltersFields constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->list = include Plugin::getPluginDirPath('/inc/listingsFiltersFields.php');
    }

    /**
     * Get filter data
     *
     * @since 1.0.0
     *
     * @param string $name The name of the filter data to retrieve.
     *
     * @return array The data needed to build the filter. Empty array if no field is found.
     */
    public function __get($name)
    {
        if (! isset($needle, $this->list[$name])) {
            return array();
        }

        return $this->list[$name];
    }

    /**
     * Retrieve a single element from the list
     *
     * @since 1.0.0
     *
     * @param string $needle The element to retrieve.
     * @param string $name   The filter slug from which retrieve the needle.
     *
     * @return mixed The needle value
     */
    public function __call($needle, $name)
    {
        // Function expect only one parameter.
        $name = $name[0];

        if (! isset($this->list[$name])) {
            return '';
        }

        if (! isset($this->list[$name][$needle])) {
            return '';
        }

        return $this->list[$name][$needle];
    }
}
