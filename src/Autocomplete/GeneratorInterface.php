<?php

namespace QiblaEvents\Autocomplete;

/**
 * AutocompleteDataGeneratorInstance
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

/**
 * Interface AutocompleteDataGeneratorInstance
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Autocomplete
 */
interface GeneratorInterface
{
    /**
     * Prepare
     *
     * @since  1.0.0
     *
     * @return GeneratorInterface The instance for chaining
     */
    public function prepare();

    /**
     * Generate Data
     *
     * @since  1.0.0
     *
     * @return GeneratorInterface The instance for chaining
     */
    public function generate();

    /**
     * Get Data
     *
     * @since  1.0.0
     *
     * @param bool $raw True to retrieve the raw data, false to retrieve the formatted one.
     *
     * @return array The generated data. In a form useful for json parsing.
     */
    public function data($raw = false);
}
