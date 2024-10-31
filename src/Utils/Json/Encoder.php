<?php
/**
 * Json Encoder
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Utils\Json
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    GNU General Public License, version 2
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
 * Class Encoder
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Utils\Json
 */
final class Encoder implements EncoderInterface
{
    /**
     * Json
     *
     * @since  1.0.0
     *
     * @var string The json string
     */
    private $json;

    /**
     * Json Options
     *
     * @since  1.0.0
     *
     * @link   http://php.net/manual/it/json.constants.php
     *
     * @var int The bitmask option for json_encode
     */
    private $options;

    /**
     * Data
     *
     * @since 1.0.0
     *
     * @var mixed The data to convert to json
     */
    private $data;

    /**
     * ListingsJsonBuilder constructor
     *
     * @since 1.0.0
     *
     * @param int   $options The options to use during json_encode.
     * @param mixed $data    The data to encode.
     */
    public function __construct($data, $options = 0)
    {
        $this->data    = $data;
        $this->options = $options;
        $this->json    = '';
    }

    /**
     * @inheritDoc
     */
    public function prepare()
    {
        if ($this->data) {
            $this->json = (string)wp_json_encode($this->data, $this->options);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function json()
    {
        if (! F\isJSON($this->json)) {
            return '';
        }

        return $this->json;
    }
}
