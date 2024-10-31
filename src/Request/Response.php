<?php
/**
 * Response
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

namespace QiblaEvents\Request;

/**
 * Class Response
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Response
{
    /**
     * Response Code
     *
     * @since  1.0.0
     *
     * @var int The response code
     */
    protected $code;

    /**
     * Message
     *
     * @since  1.0.0
     *
     * @var string The response message
     */
    protected $message;

    /**
     * Data
     *
     * Additional data to add to the response.
     *
     * @since  1.0.0
     *
     * @var array A list of element to want to associate to the Response.
     */
    protected $data;

    /**
     * Response constructor
     *
     * @since 1.0.0
     *
     * @param int    $code    The response code.
     * @param string $message The response message.
     */
    public function __construct($code = 0, $message = '', array $data = array())
    {
        $this->code    = $code;
        $this->message = $message;
        $this->data    = $data;
    }

    /**
     * Is Valid Status
     *
     * @since  1.0.0
     *
     * @return bool True if valid status, false otherwise
     */
    public function isValidStatus()
    {
        return 400 > $this->code;
    }

    /**
     * Get Code
     *
     * @since  1.0.0
     *
     * @return int The response code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get Message
     *
     * @since  1.0.0
     *
     * @return string The response message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the data
     *
     * @since  1.0.0
     *
     * @param array $data   The data that replace the existing data or the data to append.
     * @param bool  $append True to append the passed data, false to replace the existing one.
     *
     * @return void
     */
    public function setData(array $data, $append = true)
    {
        if ($append) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data = $data;
        }
    }

    /**
     * Get Data
     *
     * @since  1.0.0
     *
     * @return array The data associated to the response
     */
    public function getData()
    {
        return $this->data;
    }
}
