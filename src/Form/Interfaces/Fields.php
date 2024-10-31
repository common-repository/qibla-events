<?php
namespace QiblaEvents\Form\Interfaces;

/**
 * Fields Interface
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
interface Fields
{
    /**
     * Get Html
     *
     * @since  1.0.0
     *
     * @return string The html format of this type
     */
    public function getHtml();

    /**
     * Get Type
     *
     * @since  1.0.0
     *
     * @return Type The type associated to the current field
     */
    public function getType();

    /**
     * Get Invalid Description
     *
     * Used to display the invalid description associated to the input type when the validate fails.
     *
     * @since  1.0.0
     *
     * @return string Return the invalid description of the field
     */
    public function getInvalidDescription();
}
