<?php
namespace Unprefix\Scripts;

/**
 * Script Abstract
 *
 * @package Unprefix\Scripts
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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
 * Class ScriptAbstract
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Unprefix\Scripts
 */
abstract class ScriptAbstract
{
    /**
     * List
     *
     * @since  1.0.0
     * @access private
     *
     * @var array A list of scripts
     */
    protected $list;

    /**
     * Must Registered / Enqueued
     *
     * If the script / style must be enqueued or not
     *
     * @since  1.0.0
     * @access public
     *
     * @param array $item  The current script / style.
     * @param int   $index The index of the callback.
     *
     * @return bool True if must enqueue, false otherwise.
     */
    public function mustPerformed(array $item, $index)
    {
        $mustPerformed = true;
        if (isset($item[$index]) && is_callable($item[$index])) {
            $mustPerformed = $item[$index]();
        }

        return $mustPerformed;
    }

    /**
     * ScriptTrait constructor
     *
     * @since 1.0.0
     *
     * @param array $list The list of the scripts
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * Get List
     *
     * @since  1.0.0
     * @access public
     *
     * @throws \Exception In case the list to retrieve doesn't exists.
     *
     * @param string $list Which list to retrieve.
     *
     * @return array The list of the styles
     */
    public function getList($list = '')
    {
        if ('' === $list) {
            return $this->list;
        }

        if (! isset($this->list[$list])) {
            throw new \Exception(
                sprintf('The list %s does not exists.', $list)
            );
        }

        return $this->list[$list];
    }
}
