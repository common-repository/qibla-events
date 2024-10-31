<?php
namespace QiblaEvents\IconsSet;

/**
 * Interface Iconsets
 *
 * @since      1.0.0
 * @package    QiblaEvents\IconsSet
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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
 * Interface IconsSetInterface
 *
 * @since   1.0.0
 * @package QiblaEvents\IconsSet
 */
interface IconsSetInterface extends \ArrayAccess, \Iterator
{

    /**
     * Get Version
     *
     * @since  1.0.0
     *
     * @return string The version of the Icon set
     */
    public function getVersion();

    /**
     * Get Prefix
     *
     * @since  1.0.0
     *
     * @return string The prefix of the icon set
     */
    public function getPrefix();

    /**
     * Compact Icon List
     *
     * Create the icons array to be used with select inputs.
     *
     * @since  1.0.0
     *
     * @return array A list of key value pairs
     */
    public function compact();
}
