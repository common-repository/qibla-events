<?php
/**
 * Collection
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

namespace QiblaEvents;

/**
 * Class Collection
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Collection implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * List
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $list;

    /**
     * FilterContainer constructor
     *
     * @since 1.0.0
     *
     * @param array $list
     */
    public function __construct(array $list = array())
    {
        $this->list = $list;
    }

    /**
     * As Array
     *
     * @since {SINCE}
     *
     * @return array The entire data list
     */
    public function asArray()
    {
        return $this->list;
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->list[$offset]);
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->list[$offset];
        }
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->list[$offset] = $value;
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->list[$offset]);
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function current()
    {
        return current($this->list);
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function next()
    {
        return next($this->list);
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function key()
    {
        return key($this->list);
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function valid()
    {
        return isset($this->list[$this->key()]);
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function rewind()
    {
        reset($this->list);
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function count()
    {
        return count($this->list);
    }
}
