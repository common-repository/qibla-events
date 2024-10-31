/**
 * utils
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

(
    function (_, $, DL, ClassList)
    {
        "use strict";

        DL.Utils = {
            /**
             * Functions
             *
             * @since 1.0.0
             *
             * @type {object}
             */
            Functions: {
                /**
                 * Deparam
                 *
                 * From query string to object.
                 * The inverse of the $.param() function.
                 *
                 * @since 1.0.0
                 *
                 * @link https://coderwall.com/p/quv2zq/deparam-function-in-javascript-opposite-of-param
                 *
                 * @param {string} querystring The query string to convert to object
                 *
                 * @returns {{}} The object create by the query string
                 */
                deparam: function (querystring)
                {
                    // Remove any preceding url and split.
                    querystring = querystring.substring(querystring.indexOf('?') + 1).split('&');
                    var params  = {}, pair, d = decodeURIComponent, i;

                    // March and parse.
                    for (i = querystring.length; i > 0;) {
                        pair               = querystring[--i].split('=');
                        params[d(pair[0])] = d(pair[1]);
                    }

                    return params;
                },

                /**
                 * Class List Utils
                 *
                 * Used to BC with IE
                 *
                 * @since 1.0.0
                 *
                 * @param {HTMLElement} el The element for which create the classList object.
                 * @returns {ClassList} The classList object
                 */
                classList: function (el)
                {
                    return new ClassList(el);
                },
            },

            /**
             * String
             *
             * @since 1.0.0
             *
             * @type {object}
             */
            String: {
                /**
                 * Capitalize
                 *
                 * @since 1.0.0
                 * @link http://alvinalexander.com/javascript/how-to-capitalize-each-word-javascript-string
                 *
                 * @param str The string to capitalize.
                 *
                 * @return string The capitalized string
                 */
                capitalize: function (str)
                {
                    return str.replace(/\w\S*/g, function (txt)
                    {
                        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                    });
                },

                /**
                 * Variablize String
                 *
                 * Create a camelized variable representation by a string
                 *
                 * @since 1.0.0
                 *
                 * @param string string The string to make as variable
                 *
                 * @returns {string} The variable representation of the string
                 */
                variablizeString: function (string)
                {
                    return (this.capitalize(string.replace(/[-_]+/g, ' '))).replace(/(\s)+/g, '');
                },

                /**
                 * Slugify
                 *
                 * @link https://gist.github.com/mathewbyrne/1280286
                 *
                 * @since 1.0.0
                 *
                 * @param {string} string The string to slugify.
                 *
                 * @returns {string} The slugified string
                 */
                toSlug: function (string)
                {
                    return string.toString().toLowerCase()
                                 .replace(/\s+/g, '-')
                                 .replace(/[^\w\-]+/g, '')
                                 .replace(/\-\-+/g, '-')
                                 .replace(/^-+/, '')
                                 .replace(/-+$/, '');
                },

                /**
                 * From Slug
                 *
                 * @since 1.0.0
                 *
                 * @param string The string to transform to slug.
                 *
                 * @returns {string} The slugified string
                 */
                fromSlug: function (string)
                {
                    return string.toString().replace('/\-\_/g', ' ');
                }
            },

            /**
             * Events
             *
             * @since 1.0.0
             *
             * @type {object}
             */
            Events: {
                /**
                 * Add Listener
                 *
                 * @since 1.0.0
                 *
                 * @throws Error if object is not valid or callback is not a function.
                 *
                 * @param obj
                 * @param event
                 * @param callback
                 * @param options
                 * @param extra
                 *
                 * @returns this for chaining
                 */
                addListener: function (obj, event, callback, options, extra)
                {
                    if (!obj || _.isArray(obj)) {
                        throw 'Invalid Object on addListener.';
                    }

                    if (!_.isFunction(callback)) {
                        throw 'Invalid callback on addListener.';
                    }

                    // Make it as an array, allow us to use multiple event but define the callback once.
                    event = _.isArray(event) ? event : [event];

                    // Through the event list.
                    _.forEach(event, function (evt)
                    {
                        // Set the event listener.
                        obj.addEventListener(evt, function (e)
                        {
                            callback.call(this, e, extra);
                        }.bind(this), options);
                    });

                    return this;
                },

                /**
                 * Dispatch Event
                 *
                 * Cross-browser version of the Event class with callback feature.
                 *
                 * Note:
                 * The event callback function need to call the callback by it self.
                 *
                 * @since 1.0.0
                 *
                 * @throws Error if object is not valid or callback is not a function.
                 *
                 * @param {string} event The event name.
                 * @param {*} obj The object on which the event must be dispatched.
                 * @param {Function} callback The callback to execute on event callback function. Optional.
                 * @param {*} detail Custom details. Optional.
                 */
                dispatchEvent: function (event, obj, callback, detail)
                {
                    if (!obj || _.isArray(obj)) {
                        throw 'Invalid Object on addListener.';
                    }

                    // Throw error only if callback is passed.
                    if (callback && !_.isFunction(callback)) {
                        throw 'Invalid callback on addListener.';
                    }

                    // Add custom details if passed.
                    detail = _.extend({
                        callback: callback
                    }, detail);

                    var theEvent = new CustomEvent(event, {
                        detail: detail
                    });

                    obj.dispatchEvent(theEvent);
                }
            },

            /**
             * UI
             *
             * @since 1.0.0
             *
             * @type {object}
             */
            UI: {
                /**
                 * Toggle Loader
                 *
                 * @since 1.0.0
                 *
                 * @param {object} element The element in which append the loader
                 * @param {Function} inCallback The callback to call after the loader become visible.
                 * @param {Function} outCallback The callback to call after the loader become hidden.
                 */
                toggleLoader: function (element, inCallback, outCallback)
                {
                    // Get the loader.
                    var loader = document.querySelector('.ajax-loader');
                    if (!loader) {
                        return;
                    }

                    if ('block' === loader.style.display) {
                        $(loader).stop(true, true).fadeOut(function ()
                        {
                            $(element).stop(true, true).fadeIn();
                            // Prevent to lost the loader when the listing Element is removed.
                            document.body.appendChild(loader);

                            // Callback to call when loader showing.
                            if (_.isFunction(inCallback)) {
                                inCallback();
                            }
                        }.bind(this));
                    } else {
                        if (!element.parentNode.lastElementChild.classList.contains('ajax-loader')) {
                            element.parentNode.appendChild(loader);
                        }

                        $(element).stop(true, true).fadeOut(function ()
                        {
                            loader.style.display = 'block';

                            // Callback to call when loader will be hidden.
                            if (_.isFunction(outCallback)) {
                                outCallback();
                            }
                        });
                    }
                },
            }
        };
    }(_, window.jQuery, window.DL, window.ClassList)
);
