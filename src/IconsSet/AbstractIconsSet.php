<?php
namespace QiblaEvents\IconsSet;

/**
 * Class IconSet
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
 * Class IconSet
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractIconsSet implements IconsSetInterface
{
    /**
     * Version
     *
     * @since  1.0.0
     *
     * @var string The version of the Icon Set
     */
    protected $version;

    /**
     * Icons List
     *
     * @since  1.0.0
     *
     * @var array The Icons List
     */
    protected $list;

    /**
     * Icons Prefix
     *
     * @since  1.0.0
     *
     * @var string The prefix of the icons
     */
    protected $prefix;

    /**
     * Position
     *
     * @since  1.0.0
     *
     * @var int The position of the current pointer
     */
    protected $position;

    /**
     * Keys
     *
     * @since  1.0.0
     *
     * @var array The list of the keys for this icon set
     */
    protected $keys;

    /**
     * Construct
     *
     * @since  1.0.0
     */
    protected function __construct()
    {
        $this->position = 0;
        $this->keys     = array_keys($this->list);
    }

    /**
     * Get Version
     *
     * @since  1.0.0
     *
     * @return string The version of the Icon set
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get Prefix
     *
     * @since  1.0.0
     *
     * @return string The prefix of the icon set
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Compact Icon List
     *
     * Create the icons array to be used with select inputs.
     *
     * @since  1.0.0
     *
     * @return array A list of key value pairs
     */
    public function compact()
    {
        // Clean the class name removing the namespace.
        $className = explode('\\', get_class($this));
        $className = end($className);

        $compact = array($className => array());
        foreach ($this as $name => $i) {
            // Build the icon Name.
            $icoName = str_replace($this->getPrefix() . '-', '', $name);
            // Append the element.
            $compact[$className] = array_merge(
                $compact[$className],
                array(strtolower($className) . '::' . $name => ucwords($icoName))
            );
        }

        return $compact;
    }

    /**
     * Offset Exists
     *
     * @param string $key The key of the icon
     *
     * @return bool True if offset exists, false otherwise.
     */
    public function offsetExists($key)
    {
        return isset($this->list[sanitize_key($key)]);
    }

    /**
     * Get Offset
     *
     * @param string $key The key of the icon.
     *
     * @return string The unicode value associated to the key. Empty string if not.
     */
    public function offsetGet($key)
    {
        return ($this->offsetExists($key) ? $this->list[sanitize_key($key)] : '');
    }

    /**
     * Offset Set
     *
     * This does not set anything.
     *
     * @param string $key The key of the icon.
     * @param string $val The unicode value.
     *
     * @return void
     */
    public function offsetSet($key, $val)
    {
        return null;
    }

    /**
     * Offset Unset
     *
     * This does not do anything.
     *
     * @param mixed $key The key of the icon
     *
     * @return bool Always false
     */
    public function offsetUnset($key)
    {
        return false;
    }

    /**
     * Rewind
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Get Current Element
     *
     * @since  1.0.0
     *
     * @return string The current value based on position
     */
    public function current()
    {
        return $this->list[$this->keys[$this->position]];
    }

    /**
     * Get Current Position
     *
     * @since  1.0.0
     *
     * @return int The current position
     */
    public function key()
    {
        return $this->keys[$this->position];
    }

    /**
     * Next
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Is Valid
     *
     * Check if the current element exists.
     *
     * @since  1.0.0
     *
     * @return bool True if isset, false otherwise
     */
    public function valid()
    {
        return isset($this->keys[$this->position]);
    }
}
