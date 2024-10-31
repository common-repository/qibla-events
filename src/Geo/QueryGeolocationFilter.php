<?php
/**
 * Listings Query Filter By Geolocation Builder
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

use QiblaEvents\Query\MetaQueryArguments;

/**
 * Class QueryGeolocationFilter
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class QueryGeolocationFilter
{
    /**
     * Query
     *
     * @since  1.0.0
     *
     * @var \WP_Query The query instance passed by ref
     */
    private $query;

    /**
     * Meta Query Arguments
     *
     * @since  1.0.0
     *
     * @var MetaQueryArguments The query arguments instance
     */
    private $metaQueryArguments;

    /**
     * FilterListingsQueryByGeolocationFacade constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Query          $query
     * @param MetaQueryArguments $metaQuery
     */
    public function __construct(\WP_Query $query, MetaQueryArguments $metaQuery)
    {
        $this->query              = $query;
        $this->metaQueryArguments = $metaQuery;
    }

    /**
     * Set Query
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function setQuery()
    {
        $this->metaQueryArguments->buildQueryArgs($this->query);
    }
}
