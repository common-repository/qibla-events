/**
 * Geocoding
 *
 * @since     1.0.0
 * @author    Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright Copyright (c) 2018, Alfio Piccione
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione <alfio.piccione@gmail.com>
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

window.DL = window.DL || {};

;(
    function (_, dllocalized, DL, googleMaps)
    {
        "use strict";

        // Initialize the object.
        DL.Geo = DL.Geo || {};

        // Create the geocoding object.
        DL.Geo.Geocode = {
            /**
             * Geocode
             *
             * @throws Error in case the geocode data is empty.
             *
             * @since 1.0.0
             *
             * @param {Function} success Callback to call on success.
             * @param {Function} error Callback to call on errors.
             *
             * @return {*} this for chaining
             */
            geocode: function (success, error)
            {
                // Initialize the data to return.
                var data = {};

                if (_.isEmpty(this.geocoderRequest)) {
                    throw 'Cannot geocode empty data.';
                }

                // Retrieve the geocode and call the callback.
                this.geocoder.geocode(this.geocoderRequest, function (geocoderResult, statusCode)
                {
                    // Check for the status.
                    switch (statusCode) {
                        case googleMaps.GeocoderStatus.OK:
                            // Always take the first result.
                            var result = geocoderResult[0];
                            // Build the data.
                            data       = {
                                lat: result.geometry.location.lat(),
                                lng: result.geometry.location.lng(),
                                address: result.formatted_address,
                            };

                            // Handle Success.
                            if (_.isFunction(success)) {
                                success(data);
                            }
                            break;

                        case googleMaps.GeocoderStatus.ZERO_RESULTS:
                            break;

                        default:
                            // All other status that are generally error during geocode.
                            console.warn('Qibla Geocoding Issue: ' + geocoderResult[1]);

                            // Handle error
                            if (_.isFunction(error)) {
                                error(data);
                            }
                            break;
                    }
                });

                return this;
            },

            /**
             * Construct
             *
             * @since 1.0.0
             *
             * @param {*}       address The address or the lat/lng object in case of reverse is true.
             * @param {boolean} reverse If asking for reverse Geocoding or not.
             *
             * @returns {(*|null)} this for chaining
             */
            construct: function (address, reverse)
            {
                if (!_.isFunction(googleMaps.Geocoder)) {
                    return;
                }

                // Set the context for the request.
                var context = (reverse ? 'location' : 'address');

                this.geocoder        = new googleMaps.Geocoder();
                this.geocoderRequest = {};

                if (address) {
                    this.geocoderRequest[context] = address;
                }

                return this;
            }
        };

        /**
         * Geocode Factory
         *
         * @since 1.0.0
         *
         * @param {*}       address The address or the lat/lng object in case of reverse is true.
         * @param {boolean} reverse If asking for reverse Geocoding or not.
         *
         * @return {DL.Geo.GeocodeFactory} The instance of the new object.
         */
        DL.Geo.GeocodeFactory = function (address, reverse)
        {
            return Object.create(DL.Geo.Geocode).construct(address, reverse);
        };
    }(_, window.dllocalized, window.DL, window.google.maps)
);
