/**
 * Backbone DlMap
 *
 * This is the wrapper for the map used by the Listings map MVC.
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

window.DlMap = window.DlMap || {};

(function (_, Backbone, google, dlgooglemap, DlMap, DlListings, dllocalized)
{
    "use strict";

    /**
     * Set default map center
     *
     * @since 1.0.0
     *
     * @returns {google.maps.LatLng} The lat lng data for the center of the map.
     */
    function getDefaultMapCenter()
    {
        var defaultCenter = new google.maps.LatLng(21.4010244, -157.9784046);

        if (!_.isUndefined(window.dlgooglemap.center.lat) && !_.isUndefined(window.dlgooglemap.center.lng)) {
            defaultCenter = new google.maps.LatLng(window.dlgooglemap.center.lat, window.dlgooglemap.center.lng);
        }

        return defaultCenter;
    }

    document.addEventListener('DOMContentLoaded', function ()
    {
        /**
         * Map Style
         *
         * @since 1.0.0
         *
         * @type {Object} The hash containing the data for the map style.
         */
        DlMap.STYLE = (function (style)
        {
            try {
                // Try to set the custom style for the map is a valid JSON.
                // More styles by: https://snazzymaps.com/
                // Just use the filter 'qibla_map_style_slug' defined within the scripts.php :)
                if (!_.isEmpty(window.dlgooglemap.style)) {
                    style = _.union(style, JSON.parse(window.dlgooglemap.style));
                }
            } catch (e) {
                console.warn('Qibla: Json Map Style cannot be set.');
            }

            return style;
        }([
            {
                featureType: 'poi',
                elementType: 'all',
                stylers: [{visibility: 'off'}]
            },
            {
                featureType: 'transit',
                elementType: 'all',
                stylers: [{visibility: 'off'}]
            }
        ]));

        /**
         * The Map Class
         *
         * @param {HTMLElement} el      The element where construct the map.
         * @param {Object}      options The options for the map.
         *
         * @return void
         */
        DlMap.Map = function (el, options)
        {
            // Set the defaults.
            _.defaults(options, {
                zoom: window.dlgooglemap.zoom,
                maxZoom: 17,
                // Default to Lost Film Location :P
                center: getDefaultMapCenter(),
                // Keep it to able to double click to the marker to zoom.
                disableDoubleClickZoom: true,
                disableDefaultUI: true,
                gestureHandling: 'greedy',
                scrollwheel: true,
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.TOP_LEFT
                },
                styles: DlMap.STYLE
            });

            google.maps.Map.apply(this, [el, options]);
        };

        /**
         * The Map Prototype
         *
         * Extend by google.maps.Map.prototype
         *
         * @type {Object} The prototype.
         */
        DlMap.Map.prototype = Object.create(google.maps.Map.prototype, {
            /**
             * Opened Info Windows
             *
             * A list of currently opened info windows
             *
             * @since 1.0.0
             */
            openedInfoWindows: {
                value: [],
                writable: true,
                enumerable: true
            },

            /**
             * Close All info windows
             *
             * Remove all info windows previously added in the map
             *
             * @since 1.0.0
             *
             * @param {String}
             */
            closeOpenedInfoWindows: {
                value: function ()
                {
                    if (!this.openedInfoWindows.length) {
                        return;
                    }

                    // Close the previous opened info windows within the same map.
                    for (var c = 0; c < this.openedInfoWindows.length; ++c) {
                        this.openedInfoWindows[c].remove();
                    }

                    // Clean the list.
                    this.openedInfoWindows = [];
                }
            },

            /**
             * Create Bounds By Markers
             *
             * @since 1.0.0
             *
             * @return *{LatLngBounds} The bounds of the map
             */
            getBoundsByMakers: {
                value: function (markers)
                {
                    if (!markers) {
                        // Default to the markers Collection.
                        // Generally used in conjunction with some events like window resize or map togglers.
                        markers = DlMap.markerCollection.getMarkerRefs() || [];
                    }

                    // Create new Bounds and extend the them by including the markers positions.
                    var bounds = new google.maps.LatLngBounds();
                    _.forEach(markers, function (marker)
                    {
                        bounds.extend(marker.getPosition());
                    });

                    return bounds;
                }
            },

            /**
             * Pan and Zoom
             *
             * @since 1.0.0
             *
             * @param {Object} latLng The latitude and longitude object.
             * @param {int} zoom The amount of zoom.
             *
             * @return {Object} this The instance of the map for chaining
             */
            panAndZoom: {
                value: function (latLng, zoom)
                {
                    this.panTo(latLng);
                    this.setZoom(zoom);

                    return this;
                }
            },

            /**
             * Re center the map
             *
             * Use an internal timeout to prevent unnecessary computation.
             *
             * @since 1.0.0
             *
             * @return {Object} this for chaining
             */
            reCenter: {
                value: function ()
                {
                    clearTimeout(reCenter);

                    var reCenter = setTimeout(function ()
                    {
                        // Retrieve the bounds that are within the marker collection.
                        // Work with the entire collection of markers instead the ones of the current viewport.
                        var bounds = this.getBoundsByMakers();

                        // Fit the bounds.
                        this.fitBounds(bounds);
                        // Recenter the map to the bounds center.
                        this.setCenter(bounds.getCenter());
                    }.bind(this), 300);

                    return this;
                }
            },

            /**
             * Event Dispatcher
             *
             * Used for external purpose for example togglers.
             *
             * @since 1.0.0
             *
             * @return {Object} this for chaining
             */
            dispatchEvent: {
                value: function (event)
                {
                    switch (event) {
                        case 'resize' :
                            google.maps.event.trigger(this, 'resize');
                            break;
                        default:
                            break;
                    }

                    return this;
                }
            },

            /**
             * Set the Map Events
             *
             * - Zoom Changed: Close the opened info windows.
             * - Click: Close the opened info windows.
             * - Resize: Re center the map to the bounds.
             *
             * @since 1.0.0
             *
             * @return void
             */
            setEvents: {
                value: function ()
                {
                    // Close opened info windows.
                    this.addListener('zoom_changed', this.closeOpenedInfoWindows.bind(this));
                    this.addListener('resize', this.reCenter.bind(this));

                    google.maps.event.addDomListener(window, 'resize', this.reCenter.bind(this));
                    // Don't add the event to window and not to click or some other elements with click event listener
                    // will not work correctly.
                    google.maps.event.addDomListener(this, 'mousedown', this.closeOpenedInfoWindows.bind(this));
                }
            },
        });

        /**
         * Backbone Namespace
         *
         * Containing the Models, Views and Collections
         *
         * @since 1.0.0
         */
        DlMap.Backbone = {};

        /**
         * Dl Map Models
         *
         * Containing:
         * - Marker
         * - Poi ( Point Of Interest ) Aka WordPress Listings post type posts.
         *
         * @type {Object}
         */
        DlMap.Backbone.Models = {
            /**
             * Marker Model
             *
             * @since 1.0.0
             */
            Marker: Backbone.Model.extend({
                /**
                 * Constructor
                 *
                 * @since 1.0.0
                 *
                 * @param {Object} item The item with the data needed by the maker and the info window.
                 */
                constructor: function (item)
                {
                    // This is the View for the marker, as of the marker itself is a property not an attribute,
                    // We consider at this implementation the infobox as view for this model.
                    this.infoWindow = new DlMap.Backbone.Views.InfoWindow({
                        model: this,
                    });

                    // The marker instance.
                    // The custom marker is an Overlay and need a graphic rappresentation, but we don't consider
                    // this as view, just like the marker with properties.
                    this.marker = new CustomMarker(
                        new google.maps.LatLng(item.location.latLng[0], item.location.latLng[1]),
                        {
                            // The marker slug used in conjunction to the listing element to able to perform
                            // actions on events. See the loop-listings template for dataset attribute.
                            dataMarkerSlug: item.slug,
                            templateData: item
                        },
                        _.template(document.querySelector('#dltmpl_map_marker').innerHTML)
                    );

                    // Finally, create the model.
                    Backbone.Model.call(this, item);
                },

                /**
                 * Initialize
                 *
                 * @since 1.0.0
                 */
                initialize: function ()
                {
                    // Set the Events.
                    // The Click event is triggered to able to show the infoWindow.
                    google.maps.event.addListener(this.marker, 'click', this.onClick.bind(this));
                },

                /**
                 * On Click
                 *
                 * Trigger the Click event.
                 *
                 * @since 1.0.0
                 *
                 * @return {Object} this for chaining
                 */
                onClick: function (e)
                {
                    e.preventDefault();
                    e.stopPropagation();

                    // This event doesn't exists in backbone model, we must trigger it.
                    this.trigger('click');

                    return this;
                }
            })
        };

        /**
         * Dl Map Views
         *
         * Containing
         * - Info Windows ( InfoBox )
         *
         * @type {Object}
         */
        DlMap.Backbone.Views = {
            /**
             * Info Window
             *
             * @since 1.0.0
             */
            InfoWindow: Backbone.View.extend({

                /**
                 * The Element
                 *
                 * We don't need an element, just get the template
                 * HTML and put it within the internal info-box content.
                 *
                 * The element is always removed by the DOM every time it is closed.
                 *
                 * @since 1.0.0
                 */
                el: null,

                /**
                 * The Template
                 *
                 * @since 1.0.0
                 *
                 * @return {*} Whatever the _.template returns
                 */
                template: (function ()
                {
                    // Get the template for the info-box view.
                    var template = document.querySelector('#dltmpl_map_info_window');
                    if (!template) {
                        template = '';
                    } else {
                        template = template.innerHTML;
                    }

                    return _.template(template);
                }()),

                /**
                 * The constructor
                 *
                 * @since 1.0.0
                 */
                constructor: function ()
                {
                    // The instance of the InfoBox
                    // @link https://github.com/googlemaps/v3-utility-library/tree/master/infobox/docs
                    this.infoBox = new InfoBox({
                        closeBoxURL: false,
                        // Bem Scope block class
                        boxClass: 'dlmap-info-window dlmap-info-window--default animated fadeIn',
                        // Distances between the map edges and the infobox on pan
                        infoBoxClearance: new google.maps.Size(64, 64),
                        // Offset relative to the marker
                        // Add 10 extra pixel for the after arrow element.
                        pixelOffset: new google.maps.Size(-117, -62),
                        // This change the behavior of the pixelOffset: start from bottom/left
                        // instead of top/left.
                        alignBottom: true
                    });

                    // Create the view.
                    Backbone.View.apply(this, arguments);
                },

                /**
                 * Initialize
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                initialize: function ()
                {
                    _.bindAll(this, 'render');

                    // Set the event for rendering.
                    // For more info about this, read the comments on Backbone.Model.
                    this.model.on('click', this.render);

                    // Remove the view from the DOM and clean the listening.
                    // This is not necessary because we work only with one info-box at a time and it is removed every time is closed.
                    // See DlMap.Map.prototype.closeOpenedInfoWindows for more info.
                    this.model.on('remove', this.remove);
                },

                /**
                 * Render
                 *
                 * @since 1.0.0
                 *
                 * @returns {Object} this for chaining
                 */
                render: function ()
                {
                    // Single listing doesn't use InfoWindows.
                    // This is a workaround until some logic is split and moved into a separate object.
                    if (!DlMap.map) {
                        return this;
                    }

                    if (DlMap.map.openedInfoWindows.length) {
                        // Prevent to create/open/close previous info window every time a click is triggered.
                        // This also prevent accidentally element glitches.
                        var iwName = DlMap.map.openedInfoWindows[0].model.get('slug');
                        if (iwName === this.model.get('slug')) {
                            return;
                        }
                    }

                    // Remove all previous info windows.
                    DlMap.map.closeOpenedInfoWindows();

                    // Set the innerHtml for the info-box.
                    this.infoBox.setContent(this.template(this.model.attributes));
                    // Set the position for the info box. Same of the marker.
                    this.infoBox.setPosition(this.model.marker.getPosition());
                    // Open the info window.
                    this.infoBox.open(DlMap.map);

                    // Add the newly opened info window to the opened list.
                    DlMap.map.openedInfoWindows.push(this);

                    return this;
                },

                /**
                 * Close Info Window
                 *
                 * @since 1.0.0
                 *
                 * @return {Object} this For chaining
                 */
                remove: function ()
                {
                    // Close the infoBox and remove the element from the Dom.
                    this.infoBox.close();

                    Backbone.View.prototype.remove.apply(this, arguments);
                },
            })
        };

        /**
         * The Map Collections
         *
         * Containing:
         * - Marker
         *
         * @type {Object}
         */
        DlMap.Backbone.Collections = {
            /**
             * Marker
             *
             * @since 1.0.0
             */
            Marker: Backbone.Collection.extend({

                /**
                 * Retrieve the markers references
                 *
                 * Retrieve the internal instances of marker from the collection.
                 *
                 * @since 1.0.0
                 *
                 * @returns {Array} The marker instances
                 */
                getMarkerRefs: function ()
                {
                    return _.pluck(this.models, 'marker');
                },

                /**
                 * Initialize
                 *
                 * @since 1.0.0
                 */
                initialize: function ()
                {
                    _.bindAll(this, 'cleanMarkers');

                    // Run a clean up of the layout every time the collection is reset.
                    // This remove the markers from the map and the listings articles.
                    this.on('reset', this.cleanMarkers);
                },

                /**
                 * Clean the Layout
                 *
                 * @return {Object} this for chaining
                 */
                cleanMarkers: function ()
                {
                    if (DlMap.map.markerCluster) {
                        // This remove all of the marker within the map but not the listener
                        // for the infoWindow. Them must be removed separately.
                        DlMap.map.markerCluster.clearMarkers();
                        // Clean the marker Cluster data, just to be sure.
                        DlMap.map.markerCluster = null;
                    }
                },
            })
        };

    });

}(_, Backbone, google, window.dlgooglemap, window.DlMap, window.DlListings, window.dllocalized));
