<?php
/**
 * AddressFactory
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

namespace QiblaEvents\Geo;

use QiblaEvents\Functions as F;
use QiblaEvents\Geo\Request\RequestByGeocodedAddress;
use QiblaEvents\Request\Nonce;

/**
 * Class AddressFactory
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class AddressFactory
{
    /**
     * Create Address from POST Request
     *
     * @since 1.0.0
     *
     * @throws \OutOfBoundsException In case the nonce verification doesn't pass
     *
     * @return Address The instance of the Address class
     */
    public static function createFromPostRequest(Nonce $nonce)
    {
        if (! $nonce->verify()) {
            throw new \OutOfBoundsException('Cannot retrieve geocode address from Post Request.');
        }

        // Retrieve Geocoded data from the request.
        // @codingStandardsIgnoreLine
        $address = F\filterInput($_POST, RequestByGeocodedAddress::KEY, FILTER_DEFAULT, array(
            'flags'   => FILTER_REQUIRE_ARRAY,
            'options' => array(
                'address' => FILTER_SANITIZE_STRING,
            ),
        ));

        if (! isset($address['address'])) {
            throw new \RangeException('No valid geocoded values found on request.');
        }

        // Create the instance of the variable.
        return new Address($address['address']);
    }
}
