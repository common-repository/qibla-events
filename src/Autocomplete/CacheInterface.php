<?php
namespace QiblaEvents\Autocomplete;

/**
 * Data Cache Interface
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Autocomplete
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    GNU General Public License, version 2
 *
 * Copyright (C) 2018 Guido Scialfa <dev@guidoscialfa.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.s
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

/**
 * DataCacheInterface
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Autocomplete
 */
interface CacheInterface
{
    /**
     * Set Data Cache
     *
     * @since  1.0.0
     *
     * @param string $data The json data to store.
     * @param string $name The name for the cache type.
     *
     * @return void
     */
    public function set($data, $name);

    /**
     * Get transient
     *
     * @since  1.0.0
     *
     * @uses   get_transient() To retrieve the cache content.
     *
     * @param string $name The name of the specific cache to retrieve
     *
     * @return mixed Whatever the get_transient returns
     */
    public function get($name);
}
