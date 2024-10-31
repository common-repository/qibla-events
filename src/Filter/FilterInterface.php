<?php

/**
 * Interface Filters
 *
 * @since   1.0.0
 * @package QiblaEvents\Front\Filter
 *
 * Copyright (C) 2018 Alfio Piccione
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
 * Interface Filter
 *
 * @since 1.0.0
 */
interface FilterInterface
{
    /**
     * Filter Name
     *
     * The filter name is generally used to referrer to the field and for relationship with filter fields.
     *
     * @since 1.0.0
     *
     * @return string The filter name
     */
    public function name();

    /**
     * The Field
     *
     * @since 1.0.0
     *
     * @return \QiblaEvents\Filter\FilterFieldInterface The instance of the field
     */
    public function field();

    /**
     * Filter Query
     *
     * @since 1.0.0
     *
     * @param \WP_Query $wpQuery The query instance.
     * @param mixed     $args    The arguments to set in query.
     *
     * @return mixed
     */
    public function queryFilter(\WP_Query &$wpQuery, $args);
}
