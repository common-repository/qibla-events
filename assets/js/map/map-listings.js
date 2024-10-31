/**
 * Google Map Listings
 *
 * This may could be seen as a Controller for the Map Listings MVC.
 * The class use the DlListings MVC to set triggers, make the ajax call and send data to the relative mvc parts.
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
window.DlMap      = window.DlMap || {};
window.DlListings = window.DlListings || {};

(
    function (
        $,
        _,
        dllocalized,
        Backbone,
        google,
        CustomMarkerClusterer,
        DlMap,
        DlListings,
        dllistings,
        dlgooglemap,
        jsonListings,
        DL
    )
    {
        "use strict";

        /**
         * Remove Geocode Inputs
         *
         * Temporary function to allow us to remove the geocode inputs when the element select change.
         *
         * @since 1.0.0
         *
         * @param {HTMLElement} el    The element that trigger the remove.
         * @param {String}      event The event to set for listening.
         */
        function setGeocodeInputsRemoveAction(el, event)
        {
            el.addEventListener(event, function ()
            {
                var geocodedInputs = this.form.querySelectorAll('.geocode-input');
                geocodedInputs.length && _.forEach(geocodedInputs, function (geoInput)
                {
                    geoInput.remove();
                });

                // Then remove the nonce.
                var geocodeNonce = this.form.querySelector('#geocode_nonce');
                geocodeNonce && geocodeNonce.remove();
            }.bind(this));
        }

        DL.Listings = DL.Listings || {};

        /**
         * Listings Filter
         *
         * @since 1.0.0
         */
        DL.Listings.Filter = {
            /**
             * Retrieve Listings List Element
             *
             * @since 1.0.0
             *
             * @returns {Element} The element instance
             */
            listingsListEl: function ()
            {
                return document.querySelector('.dllistings-list');
            },

            /**
             * If listings filter use map or not
             *
             * @since 1.0.0
             *
             * @returns {boolean}
             */
            useMap: function ()
            {
                return Boolean(DL.Utils.Functions.classList(document.body).contains('dl-is-listings--with-map'));
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
             * If listings filter use map or not
             *
             * @since 1.0.0
             *
             * @returns {boolean}
             */
            visibleMap: function ()
            {
                return window.dllistings.mapVisible || this.useScMap();
            },

            /**
             * Get Form Elements
             *
             * @since 1.0.0
             */
            getFormTriggers: function ()
            {
                return this.form.querySelectorAll('.dllistings-ajax-filter-trigger');
            },

            /**
             * Center and set Events
             *
             * @since 1.0.0
             */
            updateMap: function ()
            {
                DlMap.map.reCenter();
                DlMap.map.setEvents();
            },

            /**
             * Create the Auto Update Filters List
             *
             * @since 1.0.0
             *
             * @return void
             */
            buildAutoupdateList: function ()
            {
                if (!_.isEmpty(this.autoUpdate)) {
                    return;
                }

                var list = this.form.querySelectorAll('.is-autoupdate');
                if (!list) {
                    return;
                }

                _.forEach(list, function (item)
                {
                    var slug = DL.Utils.String.toSlug(item.getAttribute('data-autoupdate'));

                    if (!slug) {
                        return;
                    }

                    this.autoUpdate[slug] = {
                        'instance': item,
                        'filter': item.getAttribute('data-autoupdate')
                    };
                }.bind(this));
            },

            /**
             * Update Autoupdate Filters Markup
             *
             * @since 1.0.0
             *
             * @param items The items to update
             *
             * @return void
             */
            updateAutoupdateFiltersMarkup: function (items)
            {
                if (_.isEmpty(items)) {
                    return;
                }

                _.forEach(items, function (item, key)
                {
                    if (!_.isUndefined(this.autoUpdate[key])) {
                        // Insert the newly elements within the container.
                        this.autoUpdate[key].instance.innerHTML = item;
                    }
                }.bind(this));

                DL.Listings.FilterToggler.counterChecked();
                DL.Listings.FilterToggler.updateCheckboxCounter();
            },

            /**
             * Get the post Data
             *
             * @since 1.0.0
             *
             * @returns {string} The data for the ajax post call
             */
            postData: function (callback)
            {
                var data = 'dlajax_action=listings_filter&' + $(this.form).serialize();

                if (_.isEmpty(this.autoUpdate)) {
                    this.buildAutoupdateList();
                }

                // Create and extract the list of the filters that need to be updated.
                _.forEach(this.autoUpdate, function (item, key)
                {
                    // Set the autoupdate slug to allow the server to build the right list of elements.
                    // Add '_autocomplete' suffix to prevent name collisions with the fields names.
                    data += '&auto_update_filters[' + item.filter + ']=1';
                });

                _.isFunction(callback) && callback(data);
            },

            /**
             * Init
             *
             * Initialize the map and the marker collection.
             *
             * @since 1.0.0
             */
            init: function ()
            {
                if (this.visibleMap()) {
                    // Create the map Instance.
                    // Get the instance of the map before retrieve the point to permit other scripts to work
                    // with the newly html element. The one that contain the map.
                    DlMap.map = new DlMap.Map(document.querySelector('#dlgoogle-map'), window.dlgooglemap);

                    // Create the instance for the marker Collection.
                    DlMap.markerCollection = new DlMap.Backbone.Collections.Marker(null, {
                        model: DlMap.Backbone.Models.Marker
                    });
                }
                // Setting Up the Model and View for Archive description element.
                this.archiveDescriptionModel = new DlListings.Backbone.Models.ArchiveDescription();
                this.archiveDescriptionView  = new DlListings.Backbone.Views.ArchiveDescription({
                    model: this.archiveDescriptionModel,
                });

                // Setting Up the Model and View for Found posts element.
                this.foundPostsModel = new DlListings.Backbone.Models.FoundPosts();
                this.foundPostsView  = new DlListings.Backbone.Views.FoundPosts({
                    model: this.foundPostsModel,
                });

                this.paginationModel = new DlListings.Backbone.Models.Pagination();
                this.paginationView  = new DlListings.Backbone.Views.Pagination({
                    model: this.paginationModel
                });

                // Create the instance of the listings collection.
                this.listingsCollection = new DlListings.Backbone.Collections.Listings(null, {
                    model: DlListings.Backbone.Models.Listings,
                });

                // Retrieve the static data if exists.
                // Generally the static data are created after a search query
                // After retrieved the data, remove the posts property, so we can know that the posts
                // are no longer valid.
                if (!_.isEmpty(jsonListings) && !_.isEmpty(jsonListings.posts)) {
                    // Add the listings to the collection.
                    this.listingsCollection.add(jsonListings.posts);

                    if (this.visibleMap()) {
                        // Set the POI.
                        DlMap.markerCollection.add(jsonListings.posts);

                        // Add the markers on map.
                        DlMap.map.markerCluster = new CustomMarkerClusterer(
                            DlMap.map,
                            DlMap.markerCollection.getMarkerRefs(),
                            {
                                gridSize: 50,
                                maxZoom: 15,
                                minimumClusterSize: 2,
                                ignoreHidden: true,
                            },
                            _.template(document.querySelector('#dltmpl_map_markerclusterer').innerHTML)
                        );

                        this.updateMap();
                    }

                    this.setPagionationAjaxTrigger();

                    // Remove the unnecessary data.
                    // The jsonListings is used only on page load.
                    window.jsonListings = undefined;
                }

                this.setTriggers();
            },

            /**
             * Pagination Ajax Trigger
             *
             * @since 1.0.0
             */
            setPagionationAjaxTrigger: function ()
            {
                var self       = this,
                    pagination = document.querySelector('.dlpagination'),
                    links;

                if (!pagination) {
                    return;
                }

                links = pagination.querySelectorAll('a.page-numbers');

                if (!links.length) {
                    return;
                }

                _.forEach(links, function (link)
                {
                    link.addEventListener('click', function (e)
                    {
                        e.preventDefault();

                        clearTimeout(padd);

                        var padd = setTimeout(function ()
                        {
                            self.filter(e);
                        }, 0);
                    });
                });
            },

            /**
             * Set Filter Triggers Events
             *
             * @since 1.0.0
             */
            setTriggers: function ()
            {
                // use shortcode map
                if (!this.useMap() && this.useScMap()) {
                    return;
                }

                // No form no action to take.
                if (typeof this.form === 'undefined' || null === this.form) {
                    throw 'Invalid form element when set triggers.';
                }

                // Edit or remove this can cause issues with map-filters-toggler.
                this.form.addEventListener('submit', this.filter.bind(this));
                // The form must trigger the ajax call to able to update the list of the posts.
                // If remove this, remove event the conditional on callback.
                this.form.addEventListener('reset', this.filter.bind(this));

                _.forEach(this.getFormTriggers(), function (el)
                {
                    var event;

                    switch (el.nodeName) {
                        case 'SELECT':
                            event = 'select2change';
                            if ('qibla_' + el.getAttribute('data-taxonomy') + '_filter' === el.getAttribute('id')) {
                                setGeocodeInputsRemoveAction.call(this, el, 'select2change');
                            }

                            break;

                        case 'INPUT':
                            event = 'change';
                            break;
                        default:
                            event = 'click';
                            break;
                    }

                    el.addEventListener(event, this.filter.bind(this));

                }.bind(this));
            },

            /**
             * No Posts Found
             *
             * @since 1.0.0
             *
             * @param {html} html The html string.
             */
            noPostsFound: function (html)
            {
                // Retrieve the container.
                var container = this.listingsListEl().firstElementChild;
                if (!container) {
                    return;
                }

                container.innerHTML = html;
            },

            /**
             * Toggle Elements When Fetching
             *
             * @since 1.0.0
             *
             * @return void
             */
            toggleElementsWhenFetching: function ()
            {
                var archiveDescription = document.querySelector('.dlarchive-description'),
                    archiveFooter      = document.querySelector('.dlarchive-listings-footer');

                archiveFooter && DL.Utils.Functions.classList(archiveFooter).toggle('is-hidden');
                archiveDescription && DL.Utils.Functions.classList(archiveDescription).toggle('is-hidden');
            },

            /**
             * Update Listings Container Box Model
             *
             * @since 1.0.0
             *
             * @param {bool} immediate True if the update must be immediate, false if we need to wait for images.
             *
             * @return void
             */
            updateListingsContainerBoxModel: function (immediate)
            {
                /**
                 * Update Height
                 *
                 * Update the height of the listings container.
                 *
                 * @since 1.0.0
                 *
                 * @param {mixed} value The value to set.
                 *
                 * @return void
                 */
                var updateHeight = function (value)
                {
                    this.listingsListEl().style.height = value;
                }.bind(this);

                /**
                 * Perform Update Height based on images counter value
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                var performUpdateHeightByCounter = function ()
                {
                    // Decrease the counter of the images.
                    --counter;

                    // If we have finished to load the images, update the container height.
                    if (0 === counter) {
                        updateHeight('auto');
                    }
                };

                // Don't do anything if the map is visible.
                if (this.visibleMap()) {
                    return;
                }

                // Immediate for when we don't need to wait for images.
                // Used on before ajax call.
                if (immediate) {
                    updateHeight(this.listingsListEl().firstElementChild.clientHeight + 'px');
                    return;
                }

                var imgs    = this.listingsListEl().querySelectorAll('img'),
                    counter = imgs.length;

                _.forEach(imgs, function (img)
                {
                    if (img.complete) {
                        performUpdateHeightByCounter();
                    } else {
                        $(img).load(performUpdateHeightByCounter);
                    }

                }.bind(this));
            },

            /**
             * Filter the listings
             *
             * @since 1.0.0
             *
             * @param {Event} e The event.
             *
             * @return void
             */
            filter: function (e)
            {
                var collectionUrl = this.ajaxUrl;
                if (e instanceof Event) {
                    // Form reset need to make the ajax call to retrieve the newly posts.
                    // Remove if remove the listener to the form:reset.
                    if (e.type !== 'reset') {
                        // Edit or remove this can cause issues with map-filters-toggler.
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    // Dynamically change the url for the ajax call if the event is set by
                    // a pagination item link.
                    if (e.target.classList.contains('page-numbers')) {
                        collectionUrl = e.target.getAttribute('href');
                    } else if ('I' === e.target.tagName && e.target.parentNode.classList.contains('page-numbers')) {
                        // This is a workaround for IE10 because not support pointer-events to the elements.
                        // Dropping support to IE10 this may be removed.
                        collectionUrl = e.target.parentNode.getAttribute('href');
                    }

                    // Don't trigger the ajax call if the filters are visibile, as of this make the call before
                    // click to the "update" btn and may confuse the user about the behavior of the btn.
                    // Especially in small devices where the map and listings are not visible at the same time.
                    if (e.type === 'select2change' && this.form.classList.contains('dlform-filter--open')) {
                        return;
                    }
                }

                // Set the url from which fetch the listings data.
                this.listingsCollection.url = collectionUrl;

                // Animate and fetch the newly collection.
                // Because of the IE Edge issue that doesn't scroll on html element.
                // Also, mobile devices scroll to 'body'.
                var elementToScrollTo = document.body.classList.contains('is-safari') ||
                                        document.body.classList.contains('is-edge') ||
                                        document.body.classList.contains('is-mobile') ? 'body' : 'html';

                $(elementToScrollTo).animate({
                    scrollTop: 0,
                }, function ()
                {
                    // Show the loader.
                    if (this.listingsListEl()){
                        DL.Utils.UI.toggleLoader(this.listingsListEl().firstElementChild);
                    }

                    // Fetch the data from the server.
                    this.postData(function (data)
                    {
                        this.listingsCollection.fetch({
                            type: 'post',
                            reset: true,
                            silent: false,
                            data: data,

                            /*
                             * Before Sending data
                             */
                            beforeSend: function ()
                            {
                                /*
                                 * Dispatch Listings Collection Fetching
                                 *
                                 * @since 1.0.0
                                 *
                                 * @param obj this Instance of this object.
                                 */
                                DL.Utils.Events.dispatchEvent('listings-collection-fetching', this.form, null, {
                                    obj: this,
                                    eventParent: e,
                                });

                                this.toggleElementsWhenFetching();
                                this.updateListingsContainerBoxModel(true);
                            }.bind(this),

                            /*
                             * On call complete
                             */
                            complete: function ()
                            {
                                /**
                                 * Dispatch Listings Collection Fetched
                                 *
                                 * @since 1.0.0
                                 *
                                 * @param obj this Instance of this object.
                                 */
                                DL.Utils.Events.dispatchEvent('listings-collection-fetched', this.form, null, {
                                    obj: this,
                                    eventParent: e,
                                });

                                if (this.listingsListEl().lastElementChild.classList.contains('ajax-loader')) {
                                    DL.Utils.UI.toggleLoader(
                                        this.listingsListEl().firstElementChild,
                                        function ()
                                        {
                                            this.updateListingsContainerBoxModel();
                                            this.toggleElementsWhenFetching();

                                        }.bind(this)
                                    );
                                }
                            }.bind(this),

                            /*
                             * On success
                             */
                            success: function (collection, data, options)
                            {
                                if (collection.models) {
                                    // Reset the marker collection to allow us to fill it with new data.
                                    DL.Listings.Filter.visibleMap() && DlMap.markerCollection.reset();

                                    _.forEach(collection.models, function (model)
                                    {
                                        // Trigger the update event on modal so the listings view can render.
                                        model.trigger('update');
                                        // Insert the current model within the marker collection.
                                        DL.Listings.Filter.visibleMap() && DlMap.markerCollection.add(model.attributes);
                                    });
                                }

                                /**
                                 * Dispatch Listings Collection Fetched Success
                                 *
                                 * @since 1.0.0
                                 *
                                 * @param obj this Instance of this object.
                                 */
                                DL.Utils.Events.dispatchEvent('listings-collection-success-fetched', this.form, null, {
                                    obj: this,
                                    eventParent: e,
                                });

                                // If no posts has been found, lets' show the no-content-found template.
                                if (!data.foundPosts.number) {
                                    this.noPostsFound(data.noContentFoundTemplate);
                                } else {
                                    // May be the query filter is made after a no content found event,
                                    // so check if the no content found element is within the container, and if so, remove it.
                                    var ncf = document.querySelector('.dlnocontent-found-listings');
                                    ncf && ncf.remove();

                                    if (DL.Listings.Filter.visibleMap()) {
                                        // Then create the markers within the map and recenter it.
                                        DlMap.map.markerCluster = new CustomMarkerClusterer(
                                            DlMap.map,
                                            DlMap.markerCollection.getMarkerRefs(),
                                            {
                                                gridSize: 50,
                                                maxZoom: 15,
                                                minimumClusterSize: 2,
                                                ignoreHidden: true,
                                            },
                                            _.template(document.querySelector('#dltmpl_map_markerclusterer').innerHTML)
                                        );

                                        this.updateMap();
                                    }
                                }

                                // Update the number of posts found.
                                this.foundPostsModel.set(data.foundPosts);
                                this.archiveDescriptionModel.set(data.archiveDescription);
                                this.paginationModel.set(data.pagination);

                                this.setPagionationAjaxTrigger();

                                // Update the autoupdate markup filters if send back.
                                if ('autoUpdateFilters' in data) {
                                    this.updateAutoupdateFiltersMarkup(data.autoUpdateFilters);
                                }
                            }.bind(this)
                        });
                    }.bind(this));
                }.bind(this));
            },

            /**
             * Construct
             *
             * @since 1.0.0
             *
             * @param {DL.Geo.userPosition} userPosition The position of the user.
             *
             * @returns {Listings}
             */
            construct: function (postUrl, userPosition, formFilter)
            {
                _.bindAll(
                    this,
                    'getFormTriggers',
                    'updateMap',
                    'postData',
                    'init',
                    'setPagionationAjaxTrigger',
                    'setTriggers',
                    'noPostsFound',
                    'toggleElementsWhenFetching',
                    'filter',
                    'updateListingsContainerBoxModel',
                    'updateAutoupdateFiltersMarkup',
                    'buildAutoupdateList'
                );

                // no filters no map and not use shortcode.
                if (!formFilter && !this.useMap() && !this.useScMap()) {
                    return;
                }

                if (formFilter) {
                    this.ajaxUrl      = postUrl;
                    this.form         = formFilter;
                    this.userPosition = userPosition;
                    this.data         = null;
                    this.autoUpdate   = {};
                }

                return this;
            }
        };

        /**
         * Listings Filter Factory
         *
         * @since 1.0.0
         *
         * @param {DL.Geo.userPosition} userPosition The position object
         *
         * @return void
         */
        DL.Listings.FilterFactory = function (postUrl, userPosition, formFilter)
        {
            return Object.create(DL.Listings.Filter).construct(postUrl, userPosition, formFilter);
        };

        /**
         * Filter Toggler
         *
         * @since 1.0.0
         */
        DL.Listings.FilterToggler = {

            /**
             * If listings filter use map or not
             *
             * @since 1.0.0
             *
             * @returns {boolean}
             */
            useMap: function ()
            {
                return Boolean(DL.Utils.Functions.classList(document.body).contains('dl-is-listings--with-map'));
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
             * If listings filter use map or not
             *
             * @since 1.0.0
             *
             * @returns {boolean}
             */
            visibleMap: function ()
            {
                return window.dllistings.mapVisible || this.useScMap();
            },

            /**
             * Toggler
             *
             * @since 1.0.0
             *
             * @return void
             */
            toggle: function (ev)
            {
                var self = this;

                if ('A' === ev.target.tagName) {
                    ev.preventDefault();
                    ev.stopImmediatePropagation();
                }

                this.actions.style.display = 'none';

                // Toggle the fields.
                $(this.filters).slideToggle(375, function ()
                {
                    self.isOpen = !self.isOpen;

                    // Show or hide the filters.
                    // Don't use toggle that is not supported in IE10
                    if (self.isOpen) {
                        self.form.classList.add('dlform-filter--open');

                        self.actions.style.display = 'block';
                    } else {
                        self.form.classList.remove('dlform-filter--open');
                    }

                    //DL.Utils.Functions.classList(document.body).add('dldocument-blocked');
                    this.style.overflowY = 'scroll';

                    if (self.useMap()) {
                        self.form.parentElement.style.overflowY = 'hidden';
                    }

                    //this.style.paddingBottom = self.actions.offsetHeight + 'px';

                    if (self.isOpen) {
                        // Retrieve toggler height to remove the height from the filters container.
                        // This prevent scrolling bar partially hidden by the actions.
                        var togglerHeight = self.container.querySelector('#dltogglers');
                        if (togglerHeight) {
                            togglerHeight = self.container.querySelector('#dltogglers').clientHeight;
                        }

                        //this.style.height = (window.innerHeight - this.getBoundingClientRect().top - togglerHeight) + 'px';
                    } else {
                        this.style.overflowY = 'auto';

                        if (self.useMap()) {
                            self.form.parentElement.style.overflowY = 'auto';
                        }

                        //DL.Utils.Functions.classList(document.body).remove('dldocument-blocked');
                    }
                });
            },

            /**
             * Update counter checked
             *
             * Update the counter of checked checkbox.
             *
             * @since 1.0.0
             */
            updateCheckboxCounter: function () {
                var checkedCount = document.querySelectorAll('[type=checkbox]:checked');
                var counter = document.querySelector('#dltoggler_filters > span');

                if (checkedCount.length > 0) {
                    counter.innerText = checkedCount.length;
                } else {
                    counter.innerText = '';
                }
            },

            /**
             * Count checked checkbox
             *
             * @since 1.0.0
             */
            counterChecked: function () {
                _.forEach(document.querySelectorAll('[type=checkbox]'), function (el) {
                    el.addEventListener('change', function () {
                        DL.Listings.FilterToggler.updateCheckboxCounter();
                    });

                });
            },

            /**
             * Clear Filters
             *
             * @since 1.0.0
             *
             * @param evt
             *
             * @return void
             */
            clearFilters: function (evt)
            {
                if (evt) {
                    evt.preventDefault();
                    evt.stopPropagation();
                }

                // Clean the form.
                this.form.reset();
                // Also, as of the select2 script is used for select inputs, we must clean that elements too.
                // So, first of all try to find the select2 element.
                var select = this.form.querySelectorAll('select');
                if (select.length) {
                    // Than for every element found, select the first option and dispatch the event
                    // so, the select2 script can update their components.
                    _.forEach(select, function (el)
                    {
                        el.setAttribute('selected', el.firstElementChild.getAttribute('value'));
                        DL.Utils.Events.dispatchEvent('change', el);
                    });
                }
            },

            /**
             * Init
             *
             * @since 1.0.0
             *
             * @return {DlListings} this for chaining
             */
            init: function ()
            {
                // Toggler element in form.
                var filterTogglerEl = this.form.querySelector('#dltoggler_filters');

                if (filterTogglerEl) {
                    filterTogglerEl.addEventListener('click', this.toggle);
                }

                this.updateBtn.addEventListener('click', this.toggle);
                this.clearBtn.addEventListener('click', this.toggle);

                this.counterChecked();
                this.updateCheckboxCounter();

                return this;
            },

            /**
             * Construct
             *
             * @since 1.0.0
             *
             * @returns {DlListings} this for chaining
             */
            construct: function ()
            {
                _.bindAll(
                    this,
                    'init',
                    'useMap',
                    'useScMap',
                    'visibleMap',
                    'toggle',
                    'clearFilters',
                    'updateCheckboxCounter',
                    'counterChecked'
                );

                this.isOpen = false;

                // use shortcode.
                if (this.useScMap()) {
                    return;
                }

                // The form filter.
                this.form = document.querySelector('.dlform-filter');
                if (!this.form) {
                    return;
                }

                // The Hidden filters for which build the toggler.
                this.filters = this.form.querySelector('.dlform-filter__hidden-fields');
                if (!this.filters) {
                    return;
                }

                this.container = document.querySelector('#dllistings_toolbar');
                this.actions   = this.filters.querySelector('.dlform-filter__actions');
                this.updateBtn = this.form.querySelector('.dlform-filter__action--update');
                this.clearBtn  = this.form.querySelector('.dlform-filter__action--clear');

                return this;
            }
        };

        /**
         * Filter Toggler Factory
         *
         * @since 1.0.0
         *
         * @constructor
         */
        DL.Listings.FilterTogglerFactory = function ()
        {
            return Object.create(DL.Listings.FilterToggler).construct();
        };

        window.addEventListener('load', function ()
        {
            // Initialize Filter.
            var filter = DL.Listings.FilterFactory(
                window.dllistings.listingsAjaxUrl,
                DL.Geo.UserPositionFactory(),
                document.getElementById('dlform_filter')
            );
            filter && filter.init();

            // Initialize Filter Toggler
            var filterToggler = DL.Listings.FilterTogglerFactory();
            filterToggler && filterToggler.init();
        });
    }(
        jQuery,
        _,
        window.dllocalized,
        Backbone,
        window.google,
        window.CustomMarkerClusterer,
        window.DlMap,
        window.DlListings,
        window.dllistings,
        window.dlgooglemap,
        window.jsonListings,
        window.DL
    )
);