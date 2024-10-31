<?php
/**
 * ListingAdditionalFields
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

namespace QiblaEvents\Admin\Metabox;

use QiblaEvents\Plugin;

/**
 * Class ListingAdditionalFields
 *
 * @since  1.0.1
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingAdditionalFields
{
    /**
     * Fields Path
     *
     * @since  1.0.1
     *
     * @var string The path where the fields are located
     */
    protected static $fieldsPath = '/inc/metaboxFields/listingAdditionalFields.php';

    /**
     * Fields
     *
     * @since  1.0.1
     *
     * @var array The list of the fields
     */
    protected $fields;

    /**
     * ListingAdditionalFields constructor
     *
     * @since 1.0.1
     *
     * @param array $fields The fields list
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Prepend Additional Fields
     *
     * Include the additional fields at the top of the list.
     *
     * @since  1.0.1
     *
     * @return array The newly fields list
     */
    public function prependAdditionalFields()
    {
        return array_merge(include Plugin::getPluginDirPath(static::$fieldsPath), $this->fields);
    }

    /**
     * Listing Additional Fields Filter
     *
     * @since  1.0.1
     *
     * @param array $fields The fields to filter
     *
     * @return mixed The filtered fields list
     */
    public static function listingAdditionalFieldsFilter(array $fields)
    {
        $instance = new static($fields);

        return $instance->prependAdditionalFields();
    }
}
