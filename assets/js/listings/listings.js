/**
 * Backbone DlListings
 *
 * Part of the map application.
 *
 * The Backbone MVC used to update the list of the listings within the archive listings page on ajax events.
 * This is managed by the map-listings script.
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
window.DlListings = window.DlListings || {};

;(function (_, Backbone, DlListings, DlMap, dllocalized)
{
    "use strict";

    document.addEventListener('DOMContentLoaded', function ()
    {
        /**
         * The Backbone namespace
         *
         * @since 1.0.0
         */
        DlListings.Backbone = {};

        /**
         * Backbone Models
         *
         * Containing:
         * - Listings
         *
         * @since 1.0.0
         *
         * @type {Object}
         */
        DlListings.Backbone.Models = {
            /**
             * Listings Model
             *
             * @since 1.0.0
             */
            Listings: Backbone.Model.extend({}),

            /**
             * Found posts
             *
             * @since 1.0.0
             */
            FoundPosts: Backbone.Model.extend({
                /**
                 * The default values
                 *
                 * @since 1.0.0
                 */
                defaults: {
                    number: null,
                    numberOf: null,
                    label: '',
                    currObjLabel: '',
                    numSeparator: '',
                },
            }),

            /**
             * Archive Description
             *
             * @since 1.0.0
             */
            ArchiveDescription: Backbone.Model.extend({}),

            /**
             * Pagination
             *
             * @since 1.0.0
             */
            Pagination: Backbone.Model.extend({
                defaults: {
                    markup: null
                }
            }),
        };

        /**
         * Backbone Views
         *
         * Containing:
         * - Listings
         *
         * @since 1.0.0
         */
        DlListings.Backbone.Views = {
            /**
             * Listings View
             *
             * @since 1.0.0
             */
            Listings: Backbone.View.extend({
                /**
                 * Events
                 *
                 * @since 1.0.0
                 */
                events: {
                    'mouseenter': 'highlightPin',
                    'mouseleave': 'highlightPin'
                },

                /**
                 * Initialize
                 *
                 * @since 1.0.0
                 */
                initialize: function ()
                {
                    this.model.on('delete', this.remove.bind(this));
                    this.model.on('update', this.render.bind(this));
                },

                /**
                 * Remove
                 *
                 * Remove the element from the Dom.
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                remove: function ()
                {
                    if (!this.el) {
                        return;
                    }

                    this.el.remove();
                },

                /**
                 * Render
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                render: function ()
                {
                    var container = document.querySelector('.dllistings-list');

                    if (!container) {
                        return;
                    }

                    // Try to retrieve the grid container.
                    // In some cases may be the element not exists, for example
                    // when there is no posts on page load.
                    var grid = container.querySelector('.dlgrid');
                    // If the element doesn't exists, create it and insert
                    // as first child of the container.
                    if (!grid) {
                        grid = document.createElement('div');
                        grid.classList.add('dlgrid');
                        container.insertBefore(grid, container.firstElementChild);
                    }
                    // Then set the container to the newly element.
                    container = grid;

                    // @todo Build a valid backbone view attributes. The postHtml came from old implementation.
                    var postHtml = this.model.get('postHtml');

                    if (postHtml) {
                        container.insertAdjacentHTML('beforeEnd', postHtml);

                        // Use setTimeout because of the time needed to insert the adjacent html.
                        setTimeout(function ()
                        {
                            // Set the element.
                            this.el = document.getElementById('post-' + this.model.get('ID'));
                            // Reassign the events.
                            this.el.addEventListener('mouseenter', this.highlightPin.bind(this));
                            this.el.addEventListener('mouseleave', this.highlightPin.bind(this));
                        }.bind(this), 0);
                    }
                },

                /**
                 * Highlight Pin
                 *
                 * Highlight the marker on listing element hover
                 *
                 * @since 1.0.0
                 *
                 * @param {Object} e The event
                 */
                highlightPin: function (e)
                {
                    // Retrieve the listing slug (the post name).
                    var slug = this.el.getAttribute('data-marker');
                    // Get the properly marker element based on the slug of this view.
                    // For more info about the relation see the custom-marker.js.
                    var markerEl = document.getElementsByClassName('data-marker-slug-' + slug)[0];

                    if (!markerEl) {
                        return;
                    }

                    switch (e.type) {
                        case 'mouseenter':
                            markerEl.classList.add('dlmap-marker--hover');
                            break;
                        case 'mouseleave':
                            markerEl.classList.remove('dlmap-marker--hover');
                            break;
                    }
                }
            }),

            /**
             * Found posts
             *
             * @since 1.0.0
             */
            FoundPosts: Backbone.View.extend({
                /**
                 * The Element
                 *
                 * We don't use the element property since there are more than one
                 * elements to update.
                 *
                 * @since 1.0.0
                 */
                el: null,

                /**
                 * Initialize
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                initialize: function ()
                {
                    this.model.on('change', this.render.bind(this));
                },

                /**
                 * Render
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                render: function ()
                {
                    var data = {
                        number: {
                            data: this.model.get('number'),
                            selector: '.dlposts-found__number'
                        },
                        numberOf: {
                            data: this.model.get('numberOf'),
                            selector: '.dlposts-found__of'
                        },
                        numSep: {
                            data: this.model.get('numSeparator'),
                            selector: '.dlposts-found__number-separator'
                        },
                        currObjLabel: {
                            data: this.model.get('currObjLabel'),
                            selector: '.dlposts-found__current-page-label'
                        }
                    };

                    _.forEach(data, function (el)
                    {
                        this.updateFoundPosts(el.data, el.selector);
                    }.bind(this));
                },

                /**
                 * Update Numbers
                 *
                 * @since 1.0.0
                 *
                 * @param data     The number or string to store
                 * @param selector The selector where store the data.
                 */
                updateFoundPosts: function (data, selector)
                {
                    var elems = document.querySelectorAll(selector);
                    if (!elems.length) {
                        return;
                    }

                    _.forEach(elems, function (elem)
                    {
                        elem.innerHTML = data;
                    });
                }
            }),

            /**
             * Archive Description
             *
             * @since $(SINCE)
             */
            ArchiveDescription: Backbone.View.extend({
                /**
                 * The Element
                 *
                 * We don't use the element property since there are more than one
                 * elements to update.
                 *
                 * @since 1.0.0
                 */
                el: null,

                /**
                 * Initialize
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                initialize: function () {
                    this.model.on('change', this.render.bind(this));
                },

                /**
                 * Render
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                render: function () {
                    var data = {
                        description: {
                            data: this.model.get('description'),
                            selector: '.dlarchive-description__content'
                        }
                    };

                    _.forEach(data, function (el) {
                        this.updateArchiveDescription(el.data, el.selector);

                    }.bind(this));
                },

                /**
                 * Update Description
                 *
                 * @since 1.0.0
                 *
                 * @param data     The description or string to store
                 * @param selector The selector where store the data.
                 */
                updateArchiveDescription: function (data, selector) {
                    var elem = document.querySelector(selector);
                    if (!elem) {
                        return;
                    }

                    elem.innerHTML = data;
                }
            }),

            /**
             * Pagination
             *
             * @since 1.0.0
             */
            Pagination: Backbone.View.extend({
                /**
                 * The Element
                 *
                 * @since 1.0.0
                 */
                el: '.dlpagination',

                /**
                 * Initialize
                 *
                 * @since 1.0.0
                 */
                initialize: function ()
                {
                    this.model.on('change', this.render.bind(this));
                },

                /**
                 * Render
                 *
                 * @since 1.0.0
                 */
                render: function ()
                {
                    try {
                        var dynamicEl = document.querySelector('.dlpagination'),
                            markup    = this.model.get('markup');

                        if (!this.el && !markup && dynamicEl) {
                            dynamicEl.remove();
                            return;
                        }

                        if (!markup && this.el) {
                            this.el.remove();
                            this.el = null;
                            return;
                        }

                        if (!markup) {
                            return;
                        }

                        if (!this.el) {
                            this.el = dynamicEl;
                            if (!this.el) {
                                var container = document.querySelector('.dlarchive-listings-footer__right');
                                if (!container) {
                                    return;
                                }

                                this.el           = document.createElement('div');
                                this.el.innerHTML = markup;
                                this.el           = this.el.firstElementChild;

                                container.appendChild(this.el);
                            }
                        }

                        this.el.innerHTML = '';
                        this.el.innerHTML = markup;
                    } catch (e) {
                        ('dev' === window.dllocalized.env) && console.warn(e);
                    }
                },
            }),
        };

        /**
         * Backbone Collections
         *
         * @since 1.0.0
         */
        DlListings.Backbone.Collections = {
            /**
             * Listings
             *
             * @since 1.0.0
             */
            Listings: Backbone.Collection.extend({
                /**
                 * Parse
                 *
                 * Parse the data from the server in order to retrieve the correct posts collection.
                 *
                 * @since 1.0.0
                 *
                 * @param {Object} response The server response data.
                 *
                 * @returns {array} A list of object to create models, empty array if no posts found.
                 */
                parse: function (response)
                {
                    if (typeof response.posts === 'undefined') {
                        return [];
                    }

                    return response.posts;
                },

                /**
                 * Initialize
                 *
                 * @since 1.0.0
                 */
                initialize: function ()
                {
                    _.bindAll(
                        this,
                        'removeCb',
                        'addCb'
                    );

                    this.on('reset', this.removeCb);
                    this.on('add', this.addCb);
                },

                /**
                 * Remove old models
                 *
                 * @since 1.0.0
                 *
                 * @param {Object} collection The current collection.
                 * @param {Object} options    The options.
                 *
                 * @return {Object} this for chaining
                 */
                removeCb: function (collection, options)
                {
                    _.forEach(options.previousModels, function (model)
                    {
                        model.trigger('delete');
                    });

                    return this;
                },

                /**
                 * Create View for Listing
                 *
                 * After the model is added to the collection let's create the view that has the newly model
                 * as model from which get updates.
                 *
                 * The view will be rendered later.
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                addCb: function (model)
                {
                    new DlListings.Backbone.Views.Listings({
                        model: model,
                        el: '#post-' + parseInt(model.get('ID'))
                    });
                }
            }),
        };
    });

}(_, Backbone, window.DlListings, window.DlMap, window.dllocalized));