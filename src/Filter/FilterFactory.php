<?php
/**
 * Filter Factory
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

/**
 * Class FilterFactory
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class FilterFactory
{
    /**
     * Filter Fields
     *
     * @since 1.0.0
     *
     * @var ListingsFiltersFields The instance of the Filters Fields from which extract the data to create the filter.
     */
    private $filtersFields;

    /**
     * FilterFactory constructor
     *
     * @since 1.0.0
     *
     * @param ListingsFiltersFields $filtersFields The instance of the Filters Fields from which extract the data to
     *                                             create the filter.
     */
    public function __construct(ListingsFiltersFields $filtersFields)
    {
        $this->filtersFields = $filtersFields;
    }

    /**
     * Create
     *
     * @since 1.0.0
     *
     * @param string $name The name of the filter to create.
     *
     * @return null
     */
    public function create($name)
    {
        // Initialize the instance.
        $instance = null;

        // Retrieve filter and field.
        $filter = $this->filtersFields->filter($name);
        $field  = $this->filtersFields->field($name);
        $type   = $this->filtersFields->type($name);

        // Before doing anything be sure both classes exists.
        if (! class_exists($filter) || ! class_exists($field) || empty($type)) {
            return null;
        }

        // Create the instance.
        $instance = new $filter(new $field($name, $type));

        return $instance;
    }
}
