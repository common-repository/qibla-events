<?php
/**
 * Request Listings By Geocoded Address Controller
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

use QiblaEvents\Geo\QueryGeolocationFilterFactory;
use QiblaEvents\Geo\LatLng;
use QiblaEvents\Request\AbstractRequestController;

/**
 * Class RequestByGeocodedAddressController
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
final class RequestByGeocodedAddressController extends AbstractRequestController
{
    /**
     * @inheritDoc
     */
    public function handle()
    {
        // Check for the data.
        if (isset($this->data['latLng']) && $this->data['latLng'] instanceof LatLng) {
            add_action('pre_get_posts', array($this, 'filterQueryByLatLng'));
        }
    }

    /**
     * Filter Query By LatLng
     *
     * Filter Listings Query By Latitude and Longitude Meta Query
     *
     * @since  1.0.0
     *
     * @param \WP_Query $wpQuery The current query instance
     */
    public function filterQueryByLatLng(\WP_Query $wpQuery)
    {
        if ($this->data['latLng']->isValid()) {
            // Create the instance.
            $filterQueryGeolocationBuilder = QueryGeolocationFilterFactory::create($wpQuery, $this->data['latLng']);
            // Set the Query.
            $filterQueryGeolocationBuilder->setQuery();
        }

        // Remove the filter after done.
        remove_action('pre_get_posts', array($this, 'filterQueryByLatLng'));
    }
}
