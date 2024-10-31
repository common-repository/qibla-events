/**
 * Single Listings Gmap.
 *
 * @since      1.0.0
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

;(
    function (_, google, DlMap, dllocalized)
    {
        "use strict";

        window.addEventListener('load', function ()
        {
            if (!document.body.classList.contains('dl-is-singular-listings')) {
                return;
            }

            /**
             * Create the Map Element
             *
             * @since 1.0.0
             *
             * @returns {Element} The new div element to append to the listing location.
             */
            function createMapElement()
            {
                var map = document.querySelector('.dllisting-location__map');

                // Map may exists.
                if (!map) {
                    map = document.createElement('div');
                    // Assign attributes.
                    map.classList.add('dllisting-location__map');
                    map.style.width     = '100%';
                    map.style.minHeight = '285px';
                }

                return map;
            }

            // Get the listing location container.
            var container = document.querySelector('.dllisting-location');
            if (!container) {
                return;
            }

            try {
                // Map Options.
                var options = JSON.parse(container.getAttribute('data-map-options'));
                if (!options) {
                    return;
                }

                // Edit default controls.
                options = _.extend(options, {
                    disableDefaultUI: true,
                    fullscreenControl: false,
                    disableDoubleClickZoom: true,
                    scrollwheel: false,
                    zoomControl: false,
                    styles: DlMap.STYLE
                });

                // Append the google map to the container.
                container.insertBefore(
                    createMapElement(),
                    document.querySelector('.dllisting-address')
                );

                // Create the Map.
                var map = new google.maps.Map(container.querySelector('.dllisting-location__map'), options);

                // Build the Maker.
                var marker = new DlMap.Backbone.Models.Marker(options.item);
                marker.marker.setMap(map);
            } catch (e) {
                ('dev' === window.dllocalized.env) && console.warn(e);
            }
        });
    }(_, google, window.DlMap, window.dllocalized)
);