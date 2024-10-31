<?php
namespace QiblaEvents\Form\Interfaces;

/**
 * Validators
 *
 * @package QiblaEvents\Form
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
 * Interface Validators
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
interface Validators
{
    /**
     * Validate
     *
     * @since 1.0.0
     *
     * @param array $fields The fields to validate.
     * @param array $args   Additional arguments to consume within the validation.
     *
     * @return array All of the valid submitted data. Empty array if the form has no fields.
     */
    public function validate(array $fields, array $args = array());
}
