<?php
/**
 * ListingsPostPlainObject
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

namespace QiblaEvents\Listings;

use QiblaEvents\ValueObject\QiblaString;

/**
 * Class ListingsPostPlainObject
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class PlainObject
{
    /**
     * Entity
     *
     * @since 1.0.0
     *
     * @var EntityInterface
     */
    private $entity;

    /**
     * PlainObject constructor
     *
     * @since 1.0.0
     *
     * @param EntityInterface $entity The entity instance to convert to a plain object.
     */
    public function __construct(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Create the object
     *
     * @since 1.0.0
     *
     * @return object The object
     */
    public function object()
    {
        $obj = array();

        // Get the interfaces that implements the entity object.
        $interfaces = class_implements($this->entity);

        // If no interfaces, return empty object.
        if (! $interfaces) {
            return new \stdClass();
        }

        // Through the interfaces.
        foreach ($interfaces as $interface) :
            // Retrieve the methods to call to retrieve the output.
            $methods = get_class_methods($interface);

            // For Safety.
            if (! $methods) {
                return new \stdClass();
            }

            // Call each method found in the current interface.
            foreach ($methods as $method) {
                $method = new QiblaString($method);
                $key    = $method->camelToSnakeCase();

                $obj[$key->val()] = call_user_func(array($this->entity, $method->val()));
            }

            $obj = array_filter($obj);
        endforeach;

        return (object)array_filter($obj);
    }
}
