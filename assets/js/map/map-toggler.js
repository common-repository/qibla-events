/**
 * Map Toggler
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
window.DL = window.DL || {};

;(
    function ($, _, dllocalized, google, DlMap, DL)
    {
        "use strict";

        window.addEventListener('load', function ()
        {
            // If the map object is not defined.
            // There is no map to toggle.
            if (_.isEmpty(DlMap) || typeof DlMap.map === 'undefined') {
                return false;
            }

            /**
             * The Map Toggler
             *
             * @since 1.0.0
             *
             * @type {Object}
             */
            var MapToggle = {
                /**
                 * Set Toggler
                 *
                 * @since 1.0.0
                 */
                setToggler: function (selector)
                {
                    this.instance = this.togglers.querySelector(selector);
                    this.instance.addEventListener('click', this.toggleMap.bind(this));
                },

                /**
                 * Update Toggler Label & Icon
                 *
                 * @since 1.0.0
                 */
                updateToggler: function ()
                {
                    var tmp          = '',
                        script       = '#dltoggler_map_tmpl',
                        selector     = '#dltoggler_map',
                        selectorToRm = '#dltoggler_map_open';

                    if (this.isOpen) {
                        tmp          = selector;
                        selector     = selectorToRm;
                        selectorToRm = tmp;
                        script       = '#dltoggler_map_open_tmpl';
                    }

                    var toggler = document.querySelector(script);
                    this.togglers.querySelector(selectorToRm).remove();
                    this.togglers.insertAdjacentHTML('beforeend', toggler.innerHTML);

                    this.setToggler(selector);
                },

                /**
                 * On Click Event
                 *
                 * The on click event show the map previously hidden
                 *
                 * @since 1.0.0
                 *
                 * @return {Object} this for chaining
                 */
                toggleMap: function ()
                {
                    // Retrieve the container element of the map.
                    var mapEl = DlMap.map.getDiv();

                    // Show the map after 257 milliseconds. The value is the same of the global value in css.
                    $(mapEl).slideToggle(450, function ()
                    {
                        // Toggle Open/Closed value.
                        this.isOpen = !this.isOpen;
                        // Update the map.
                        this.updateMapStatus();
                        this.mobileMapStatus();
                        // Set the properly height of the map, so we can positioning, zooming etc...
                        // without issue.
                        mapEl.style.height = window.innerHeight - mapEl.getBoundingClientRect().top + 'px';
                        // Dispatch the resize event to the map so we can execute all of the functions needed
                        // when the map resize.
                        // Note don't use DL.Utils.Events.dispatchEvent this have a separate implementation.
                        DlMap.map.dispatchEvent('resize');
                        // Update the label of the map toggler.
                        this.updateToggler();

                        // Map need additional style and classes.
                        // Do not use the toggle that is not supported in IE10
                        if (mapEl.classList.contains('dlgoogle-map--open')) {
                            mapEl.classList.remove('dlgoogle-map--open');
                        } else {
                            mapEl.classList.add('dlgoogle-map--open');
                        }

                        // Set or remove a body class for map, so we can now if the map is in fullscreen or not.
                        // Do not use the toggle that is not supported in IE10
                        if (this.isOpen) {
                            document.body.classList.add('dlgoogle-map-full-screen');
                        } else {
                            document.body.classList.remove('dlgoogle-map-full-screen');
                        }
                    }.bind(this));

                    return this;
                },

                /**
                 * Update map status
                 *
                 * @since 1.0.0
                 *
                 * @return {Object} this for chaining
                 */
                updateMapStatus: function ()
                {
                    var mapEl = DlMap.map.getDiv();

                    if (this.isOpen) {
                        mapEl.style.display = 'block';

                    } else {
                        mapEl.style.display = 'none';
                    }

                    return this;
                },

                mobileMapStatus: function () {
                    var mapEl = DlMap.map.getDiv();

                    if (1024 > window.innerWidth) {
                        mapEl.style.position = 'fixed';
                        mapEl.style.top      = '0';
                        mapEl.style.left     = '0';
                        mapEl.style.right    = '0';
                        mapEl.style.bottom   = '0';
                        mapEl.style.zIndex   = '50';
                    } else {
                        mapEl.style.position = 'relative';
                        mapEl.style.top      = 'initial';
                        mapEl.style.left     = 'initial';
                        mapEl.style.right    = 'initial';
                        mapEl.style.bottom   = 'initial';
                        mapEl.style.zIndex   = 'initial';
                    }
                },

                /**
                 * Show Button
                 *
                 * @since 1.0.0
                 *
                 * @return {Object} this for chaining
                 */
                showBtn: function ()
                {
                    this.instance.style.display = 'inline-block';

                    return this;
                },

                /**
                 * Hide Btn
                 *
                 * @since 1.0.0
                 *
                 * @return {Object} this for chaining
                 */
                hideBtn: function ()
                {
                    this.instance.style.display = 'none';

                    return this;
                },

                /**
                 * If use short code map or not
                 *
                 * @since 1.0.0
                 *
                 * @returns {boolean}
                 */
                useScMap: function ()
                {
                    var map = document.getElementById('dlgoogle-map');
                    if (map) {
                        return Boolean(DL.Utils.Functions.classList(map).contains('dlsc-map'));
                    }
                    return false;
                },

                /**
                 * Construct
                 *
                 * Create the element toggler in Dom, append to the correct position and set the event listeners.
                 *
                 * @since 1.0.0
                 *
                 * @throw Error in case the template doesn't exits.
                 *
                 * @return {Object} this for chaining
                 */
                construct: function ()
                {
                    if (!this.instance) {

                        // use shortcode map.
                        if (this.useScMap()) {
                            return;
                        }

                        // Create the Map Toggler Element.
                        var template = document.querySelector('#dltoggler_map_tmpl');

                        if (!template) {
                            throw "Invalid toggler template or missed element.";
                        }

                        // Append the map toggler.
                        this.togglers = document.querySelector('#dltogglers');
                        if (!this.togglers) {
                            return;
                        }

                        this.togglers.insertAdjacentHTML('beforeend', template.innerHTML);
                        // Set the instance and the Event Listener
                        this.setToggler('#dltoggler_map');

                        // Hide the map on construction if the width is less than 1024px.
                        if (1024 > window.innerWidth) {
                            DlMap.map.getDiv().style.display = 'none';
                        }

                        this.isOpen = false;
                        this.showBtn();
                    }

                    // Always run on construction.
                    document.body.style.paddingBottom = this.togglers.offsetHeight + 'px';

                    return this;
                },

                /**
                 * Destroy toggle
                 *
                 * @since 1.0.0
                 *
                 * @return {Object} this for chaining
                 */
                destroy: function ()
                {
                    // Hide the toggle and restore some data.
                    this.hideBtn();
                    // Show again the map if previously hidden.
                    DlMap.map.getDiv().style.display = 'block';
                    DlMap.map.getDiv().style.height  = '100vh';
                    // Reset the margin bottom for the html element previously added to not hide
                    // elements lower the togglers.
                    document.body.style.paddingBottom = '0';
                    // Remove the checker class.
                    document.body.classList.remove('dlgoogle-map-full-screen');

                    return this;
                }
            };

            try {
                // Create the toggle and update the map status if necessary.
                // The map may be visible or not depending on the viewport size.
                if (!MapToggle.construct()) {
                    return;
                }
            } catch (e) {
                ('dev' === window.dllocalized.env) && console.warn(e);

                return false;
            }

            // Only when the page is loaded.
            // We want to hide button and restore eventually changed Map toggle data.
            if (1024 <= window.innerWidth) {
                MapToggle.hideBtn();
                MapToggle.destroy();
            }

            // Set the resize event, to prevent unnecessary computation, simply set a timeout.
            // In this way the event will be performed on resize end.
            window.addEventListener('resize', function (e)
            {
                clearTimeout(mapResize);

                var mapResize = setTimeout(function ()
                {
                    // We don't know when the toggler will be hidden, so, update the map status in any case.
                    if (1024 > window.innerWidth) {
                        MapToggle.construct();
                        MapToggle.showBtn();
                        MapToggle.updateMapStatus();
                        MapToggle.mobileMapStatus();
                    } else {
                        // Restore the map to the original status.
                        // Corresponding to the Desktop status.
                        MapToggle.destroy();
                        MapToggle.mobileMapStatus();
                    }
                }, 100);
            });
        });

    }(window.jQuery, _, window.dllocalized, window.google, window.DlMap, window.DL)
);
