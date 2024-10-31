<?php
/**
 * Decoder
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package   QiblaEvents\Utils\Json
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

namespace QiblaEvents\Utils\Json;

use QiblaEvents\Functions as F;

/**
 * Class Decoder
 *
 * @since   1.0.0
 * @package QiblaEvents\Utils\Json
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Decoder
{
    /**
     * Json
     *
     * @since 1.0.0
     *
     * @var string The json string to decode
     */
    private $json;

    /**
     * Assoc
     *
     * @since 1.0.0
     *
     * @var bool When TRUE, returned objects will be converted into associative arrays.
     */
    private $assoc;

    /**
     * Depth
     *
     * @since 1.0.0
     *
     * @var int User specified recursion depth.
     */
    private $depth;

    /**
     * Decoder constructor
     *
     * @since 1.0.0
     *
     * @throw \InvalidArgumentException in case the json is not a valid string.
     *
     * @param string $json  The json string to decode
     * @param bool   $assoc When TRUE, returned objects will be converted into associative arrays.
     * @param int    $depth User specified recursion depth.
     */
    public function __construct($json, $assoc = false, $depth = 512)
    {
        if (! is_string($json)) {
            throw new \InvalidArgumentException('Invalid json value.');
        }

        $this->json  = $json;
        $this->assoc = $assoc;
        $this->depth = $depth;
    }

    /**
     * Decode Json
     *
     * @since 1.0.0
     *
     * @return array The decoded data or empty array if json cannot be decoded
     */
    public function decode()
    {
        if (! $this->json) {
            return array();
        }

        $json = $this->json;
        $data = array();

        if ($json) {
            // Clean the string before decode it.
            // May be the data come from a POST request.
            $json = html_entity_decode($json);
            $json = wp_unslash($json);

            if (F\isJSON($json)) {
                // Decode.
                $data = json_decode($json, $this->assoc, $this->depth);
            }
        }

        return $data;
    }
}
