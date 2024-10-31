<?php
namespace QiblaEvents\Form\Interfaces;

/**
 * Type Interface
 *
 * @package QiblaEvents\Form\Interfaces
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
 * Interface TypeInterface
 *
 * @package QiblaEvents\Form\Interfaces
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
interface Types
{

    /**
     * Escape
     *
     * @since  1.0.0
     *
     * @return mixed The escaped value of this type.
     */
    public function escape();

    /**
     * Sanitize
     *
     * @since  1.0.0
     *
     * @param mixed $value The value to sanitize.
     *
     * @return mixed The sanitized value of this type
     */
    public function sanitize($value);

    /**
     * Get Html
     *
     * @since  1.0.0
     *
     * @return string The html format of this type
     */
    public function getHtml();

    /**
     * Get Value
     *
     * Since some inputs may not need to have a value attribute, we must check if the value argument exists
     * as second choice.
     *
     * @since  1.0.0
     *
     * @return mixed The value of the input type, empty string if there is no value to return.
     */
    public function getValue();
}
