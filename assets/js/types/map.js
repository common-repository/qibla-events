/**
 * Google Map
 *
 * @todo Use Require / Promise / Browserify to load the Gmap Script.
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

window.DL = window.DL || {};

(
    function (DL)
    {
        'use strict';

        /**
         * Re-initialize the Google Map
         *
         * @since 1.0.0
         *
         * @param map        The map instance.
         * @param mapOptions The option for the map instance.
         */
        function gmapReInit(map, mapOptions)
        {
            // Resize the map.
            google.maps.event.trigger(map, 'resize');
            // Recenter the markers.
            map.setCenter(mapOptions.center);
        }

        /**
         * Set the drag event on marker
         *
         * The function update the values for gmapEl and searcher after the drag ends.
         *
         * @since 1.0.0
         *
         * @param marker   The marker for which set the listener.
         * @param gmapEl   The real input element.
         * @param searcher The searcher input element.
         */
        function setDragEventOnMarker(marker, gmapEl, searcher)
        {
            marker.addListener('dragend', function (coords)
            {
                var geocoder = new google.maps.Geocoder();

                geocoder.geocode({
                    location: coords.latLng
                }, function (results, status)
                {
                    if ('OK' !== status) {
                        return;
                    }

                    var place                = results[0];
                    var locationStringFormat = place.geometry.location.lat() + ',' + place.geometry.location.lng() + ':' + place.formatted_address;

                    // Set the value of the lat;lng:address input.
                    gmapEl.value   = locationStringFormat;
                    // Also Update the searcher value to give the user a feedback.
                    searcher.value = place.formatted_address;
                });
            });
        }

        window.addEventListener('load', function ()
        {
            // Retrieve the map data.
            var gmapDataRef = document.querySelectorAll('.dl-gmap-input-searcher');

            if (!gmapDataRef.length) {
                return false;
            }

            [].forEach.call(gmapDataRef, function (gmapEl)
            {
                var latlng, address = '';
                if (gmapEl.getAttribute('value')) {
                    // Clean the coordinate value. We have the formatted address in it.
                    var gmapElVal = gmapEl.getAttribute('value').split(':');
                    address       = gmapElVal[1];
                    // Retrieve the latitude and longitude.
                    latlng        = gmapElVal[0].split(',').map(function (el)
                    {
                        return parseFloat(el);
                    });
                }

                var searcher = document.createElement('input');
                searcher.setAttribute('type', 'text');
                searcher.setAttribute('name', gmapEl.getAttribute('name') + '_searcher');
                searcher.setAttribute('id', gmapEl.getAttribute('id') + '_searcher');
                searcher.setAttribute('value', address);
                searcher.setAttribute('placeholder', 'Start typing the address to see suggestions');
                // Keep the gmalEl sync with the searcher.
                searcher.addEventListener('change', function ()
                {
                    if (!this.value) {
                        // Reset the value of the gmapEl only if the searcher become empty.
                        gmapEl.setAttribute('value', '');
                    }
                });
                // Set the class to the new element searcher.
                _.forEach(gmapEl.classList, function (elem)
                {
                    searcher.classList.add(elem);
                });

                // Insert the searcher before the gmap Element.
                gmapEl.parentNode.insertBefore(searcher, gmapEl);
                // Set the gmap element as hidden, able to store the coordinate.
                gmapEl.classList.add('screen-reader-text');

                // Get the ID attribute value.
                var searchInputID = searcher.getAttribute('id');

                // Create the map Wrapper and set styles.
                var gmapWrapper = document.createElement('div'),
                    // Retrieve the desired position for the new map container if set.
                    gmapPos     = gmapEl.getAttribute('data-append-map-to'),
                    // Otherwise set the parent element to the current input by default.
                    gmapPosEl   = gmapEl;

                if (gmapPos) {
                    // We try to find the parent of the current input element that has the same class of appendTo.
                    var cPl = 0;
                    while (!gmapPosEl.parentElement.classList.contains(gmapPos)) {
                        gmapPosEl = gmapPosEl.parentElement;
                        // Prevent an infinite loop in case the gmapPos is not found up to 10 parents.
                        if (10 <= ++cPl) {
                            break;
                        }
                    }
                }

                // Set the properly class to the wrapper.
                gmapWrapper.classList.add('gmap-wrapper');
                // Clear fix to prevent to hide the field.
                gmapWrapper.classList.add('u-cf');
                gmapWrapper.style.clear = 'both';
                // Append the Map to the specified element.
                gmapPosEl.parentElement.appendChild(gmapWrapper);

                // Build the related map option by the id attribute of the
                var gmapOptionsByID = DL.Utils.String.variablizeString(gmapEl.getAttribute('id')),
                    // Be sure we have a valid data type.
                    gmapOptions     = window[gmapOptionsByID] || {};

                // Create the Options.
                // Set the center only if the location is provided.
                if (latlng) {
                    gmapOptions.center = {lat: latlng[0], lng: latlng[1]};
                }

                // Create the map.
                var map = new google.maps.Map(gmapWrapper, gmapOptions);
                // Hide the map wrapper if no lat lng have been provided.
                if (!latlng) {
                    gmapWrapper.style.display = 'none';
                }

                // Create the marker.
                var marker = new google.maps.Marker({
                    position: gmapOptions.center,
                    draggable: true,
                    map: map,
                });

                // Set the callback for the drag event.
                setDragEventOnMarker(marker, gmapEl, searcher);

                // Resize and center the map when the meta-box tab content become visible.
                document.addEventListener('dl-tab-opened', function ()
                {
                    gmapReInit(map, gmapOptions);
                });

                // Create the search box and link it to the UI element.
                var searchBox = new google.maps.places.SearchBox(searcher);
                /**
                 * Search Box
                 *
                 * @since 1.0.0
                 */
                searchBox.addListener('places_changed', function ()
                {
                    // Retrieve the place.
                    var place = searchBox.getPlaces()[0];

                    if (!place) {
                        return;
                    }

                    // Show the map wrapper if hidden.
                    gmapWrapper.style.display = 'block';
                    // Recenter the map.
                    gmapReInit(map, gmapOptions);
                    // Clear out the old marker.
                    marker.setMap(null);

                    // Set the value of the lat;lng:address input.
                    gmapEl.setAttribute(
                        'value',
                        place.geometry.location.lat() + ',' + place.geometry.location.lng() + ':' + place.formatted_address
                    );

                    // Recenter the map.
                    map.setCenter(place.geometry.location);
                    // Create a marker and assign it to the map.
                    marker = new google.maps.Marker({
                        map: map,
                        draggable: true,
                        title: place.name,
                        position: place.geometry.location
                    });

                    // Set the callback for the drag event.
                    setDragEventOnMarker(marker, gmapEl, searcher);
                });

                /**
                 * Events
                 */
                // Remove the enter event handler to prevent to save the post.
                document.addEventListener('keypress', function (e)
                {
                    if (13 === e.keyCode && searchInputID === e.target.getAttribute('id')) {
                        e.preventDefault();
                    }
                });
            });
        });
    }(window.DL)
);