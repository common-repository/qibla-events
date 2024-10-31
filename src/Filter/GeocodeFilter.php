<?php
/**
 * GeocodeFilter
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

namespace QiblaEvents\Filter;

use QiblaEvents\Form\Types\Hidden;
use QiblaEvents\Geo\LatLng;
use QiblaEvents\Geo\LatLngFactory;
use QiblaEvents\Debug;
use QiblaEvents\Request\Nonce;

/**
 * Class GeocodeFilter
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
final class GeocodeFilter
{
    /**
     * Nonce
     *
     * @since 1.0.0
     *
     * @var Nonce The nonce to use to retrieve the geocode value from request.
     */
    private $nonce;

    /**
     * GeocodeFilter constructor
     *
     * @since 1.0.0
     *
     * @param Nonce $nonce The nonce to use to retrieve the geocode value from request.
     */
    public function __construct(Nonce $nonce)
    {
        $this->nonce = $nonce;
    }

    /**
     * Geocode Data From POST request
     *
     * @since 1.0.0
     *
     * @return LatLng The LatLng instance or null if geocode value cannot be retrieved
     */
    private function geocodeDataFromPOST()
    {
        $geocode = null;

        try {
            $geocode = LatLngFactory::createFromPostRequest($this->nonce);
        } catch (\Exception $e) {
            $geocode = null;
        }

        return $geocode;
    }

    /**
     * Inputs
     *
     * @since 1.0.0
     *
     * Create the inputs for the geocoded data.
     * Since this is used to filter the Filter\Form output string we build internally the input.
     *
     * @return array The fields for the geocode input.
     */
    public function inputs()
    {
        $fields = array();
        $data   = $this->geocodeDataFromPOST();

        if (! $data instanceof LatLng || ! $data->isValid()) {
            return array();
        }

        $fields['lat'] = new Hidden(array(
            'name'  => 'geocoded[lat]',
            'attrs' => array(
                'value' => $data->latitude(),
                'class' => 'geocode-input',
            ),
        ));

        $fields['lng'] = new Hidden(array(
            'name'  => 'geocoded[lng]',
            'attrs' => array(
                'value' => $data->longitude(),
                'class' => 'geocode-input',
            ),
        ));

        $fields['nonce'] = new Hidden(array(
            'name'  => $this->nonce->name(),
            'attrs' => array(
                'value' => $this->nonce->nonce(),
                // Here to have a ref in js.
                'class' => 'geocode-input',
            ),
        ));

        return $fields;
    }
}
