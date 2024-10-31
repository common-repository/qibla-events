/**
 * user-geolocation.js
 *
 * @since
 * @author     Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, Alfio Piccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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
    function (_, DL)
    {
        "use strict";

        DL.Geo = DL.Geo || {};

        /**
         * User Geolocation Position
         *
         * @todo use factory
         *
         * @type {object}
         */
        DL.Geo.UserPosition = {
            /**
             * Position Options
             *
             * Enable High Accuracy
             *
             *      Is a Boolean that indicates the application would like to receive the best possible results.
             *      If true and if the device is able to provide a more accurate position, it will do so.
             *
             *      Note that this can result in slower response times or increased power consumption
             *      (with a GPS chip on a mobile device for example). On the other hand, if false,
             *      the device can take the liberty to save resources by responding more quickly and/or using less power.
             *
             *      Default: true.
             *
             * TimeOut
             *
             *      Is a positive long value representing the maximum length of time (in milliseconds)
             *      the device is allowed to take in order to return a position.
             *
             *      The default value is Infinity, meaning that getCurrentPosition()
             *      won't return until the position is available.
             *
             *      Set to 3 seconds by default.
             *
             * Maximum Age
             *
             *      Is a positive long value indicating the maximum age in milliseconds
             *      of a possible cached position that is acceptable to return.
             *
             *      If set to 0, it means that the device cannot use a cached position and
             *      must attempt to retrieve the real current position.
             *      If set to Infinity the device must return a cached position regardless of its age. Default: 0.
             *
             *      Set to 0 by default.
             *
             * @since 1.0.0
             *
             * @type {Object}
             */
            options: {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0,
            },

            /**
             * Get Latitude
             *
             * @since 1.0.0
             *
             * @returns {number} The latitude value
             */
            lat: function ()
            {
                return this.latitude ? this.latitude : 0;
            },

            /**
             * Get Longitude
             *
             * @since 1.0.0
             *
             * @returns {number} The longitude value
             */
            lng: function ()
            {
                return this.longitude ? this.longitude : 0;
            },

            /**
             * Get Current User Position
             *
             * @param {Function} successCB The callback to call on success.
             * @param {Function} errorCB   The callback to call on error.
             */
            currentPosition: function (successCB, errorCB)
            {
                if (!this.allowed()) {
                    throw 'Qibla cannot geolocated user. User agent doesn\'t support navigator.geolocation';
                }

                navigator.geolocation.getCurrentPosition(function (position)
                {
                    this.latitude  = position.coords.latitude;
                    this.longitude = position.coords.longitude;

                    if (_.isFunction(successCB)) {
                        successCB.call(this);
                    }
                }.bind(this), function (error)
                {
                    console.warn('Qibla User Geolocation failed. Code:' + error.code + 'Message: ' + error.message);

                    if (_.isFunction(errorCB)) {
                        errorCB.call(this);
                    }
                }.bind(this), this.options);

                return this;
            },

            /**
             * Initialize
             *
             * @since 1.0.0
             *
             * @returns void
             */
            allowed: function ()
            {
                // Check if the protocol is https or some feature will not work.
                // For example: getCurrentPosition, watchPosition, PositionError.
//                if ('https' !== window.location.protocol) {
//                    console.warn('Geolocation works only in secure context.');
//                    return false;
//                }

                // Check if geolocation is in the navigator object.
                // aka, we can use the geolocation object.
                if (false === 'geolocation' in navigator) {
                    console.warn('Geolocation not found.');
                    return false;
                }

                return true;
            },

            /**
             * Construct
             *
             * @since 1.0.0
             *
             * @return void
             */
            construct: function ()
            {
                if (!this.allowed()) {
                    return;
                }

                _.bindAll(
                    this,
                    'lng',
                    'lat',
                    'allowed',
                    'currentPosition'
                );

                this.latitude  = 0;
                this.longitude = 0;

                return this;
            }
        };

        /**
         * User Position Factory
         *
         * @since 1.0.0
         */
        DL.Geo.UserPositionFactory = function ()
        {
            return Object.create(DL.Geo.UserPosition).construct();
        };
    }(_, window.DL)
);
