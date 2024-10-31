<?php
/**
 * Request By Geocoded Address
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

namespace QiblaEvents\Geo\Request;

use QiblaEvents\Debug;
use QiblaEvents\Geo\LatLngFactory;
use QiblaEvents\Request\Nonce;

/**
 * Class RequestByGeocodedAddressController
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
final class RequestByGeocodedAddress
{
    /**
     * Key
     *
     * @since 1.0.0
     *
     * @var string The key related to the request.
     */
    const KEY = 'geocoded';

    /**
     * @inheritDoc
     */
    public function handleRequest()
    {
        if (! $this->isValidRequest()) {
            return;
        }

        try {
            $latLng = LatLngFactory::createFromPostRequest(new Nonce('geocoded'));

            if (! $latLng->isValid()) {
                return;
            }

            $director = new DirectorRequestByGeocodedAddress(
                new RequestByGeocodedAddressController(),
                $latLng
            );

            // Direct the request.
            $director->director();
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            return;
        }
    }

    /**
     * @inheritDoc
     */
    public function isValidRequest()
    {
        $nonce = new Nonce('geocoded');

        return $nonce->verify();
    }

    /**
     * Handle Request Filter
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function handleRequestFilter()
    {
        $instance = new static;
        $instance->handleRequest();
    }
}
