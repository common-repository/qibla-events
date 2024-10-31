<?php
namespace QiblaEvents\Form\Interfaces;

/**
 * Forms Interface
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
 * Interface Forms
 *
 * @package QiblaEvents\Form\Interfaces
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
interface Forms
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
     * Add Fields
     *
     * @since  1.0.0
     *
     * @param array $fields A list of IFields instance to add to the form
     *
     * @return void
     */
    public function addFields(array $fields);

    /**
     * Add Field
     *
     * @since  1.0.0
     *
     * @param Fields $field The field to add to this form
     *
     * @return void
     */
    public function addField(Fields $field);

    /**
     * Get Field
     *
     * @since  1.0.0
     *
     * @param string $name The name of the field to retrieve.
     *
     * @return Fields|\stdClass The field object requested an stdClass object if the field doesn't exists
     */
    public function getField($name);

    /**
     * Get Fields
     *
     * @since  1.0.0
     *
     * @return array The fields associated to the current form
     */
    public function getFields();

    /**
     * Add Hidden Type
     *
     * @since  1.0.0
     *
     * @param Types $type The input type to add to this form
     */
    public function addHidden(Types $type);

    /**
     * Get Form Nonce
     *
     * @since  1.0.0
     *
     * @return string The input type hidden for nonce
     */
    public function getNonce();
}
