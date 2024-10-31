<?php
/**
 * String
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

namespace QiblaEvents\ValueObject;

/**
 * Class String
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class QiblaString implements ValueObjectInterface
{
    /**
     * Value
     *
     * @since 1.0.0
     *
     * @var string The value
     */
    private $value;

    /**
     * QiblaString constructor
     *
     * @since 1.0.0
     *
     * @param string $string The string to create
     */
    public function __construct($string)
    {
        if (! is_string($string)) {
            throw new \InvalidArgumentException('Invalid value when build string Object.');
        }

        $this->value = $string;
    }

    /**
     * From Slug To Label
     *
     * @since 1.0.0
     *
     * @return QiblaString
     */
    public function fromSlugToLabel()
    {
        return new self(str_replace(array('-', '_'), ' ', $this->value));
    }

    /**
     * From Label To Slug
     *
     * @since 1.0.0
     *
     * @return QiblaString A new instance of the class with the new string format.
     */
    public function fromLabelToSlug()
    {
        return new self(sanitize_title($this->value));
    }

    /**
     * Capitalize
     *
     * @since 1.0.0
     *
     * @return QiblaString The new instance.
     */
    public function capitalize()
    {
        return new self(ucwords($this->value));
    }

    /**
     * Camel Case to Snake Case
     *
     * @since 1.0.0
     *
     * @return QiblaString A new instance of the class with new string format
     */
    public function camelToSnakeCase()
    {
        $val = preg_replace(
            array('/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'),
            '$1_$2',
            $this->value
        );

        if ($val !== $this->value) {
            $val = strtolower($val);
        }

        return new self($val);
    }

    /**
     * Snake Case to Camel Case
     *
     * @since 1.0.0
     *
     * @return QiblaString A new instance of the class with new string format
     */
    public function snakeCaseToCamel()
    {
        $value = str_replace('-', '_', $this->value);
        // From snake_case to camelCase.
        $value = str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
        $value = strtolower($value[0]) . substr($value, 1);

        return new self($value);
    }

    /**
     * Replace
     *
     * @since 1.0.0
     *
     * @param string $needle string The value to replace.
     * @param string $with   The value to replace with.
     *
     * @return QiblaString A new instance of the class with new string format
     */
    public function replace($needle, $with)
    {
        $val = str_replace($needle, $with, $this->value);

        return new self($val);
    }

    /**
     * Lower Case
     *
     * @since 1.0.0
     *
     * @return QiblaString A new instance of the class with new string format
     */
    public function lower()
    {
        return new self(strtolower($this->value));
    }

    /**
     * Val
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function val()
    {
        return $this->value;
    }
}
