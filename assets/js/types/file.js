/**
 * File Type
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

window.DlTypes = window.DlTypes || {};

;(
    function (_, $, DlTypes, Dropzone, dllocalized)
    {
        "use strict";

        DlTypes.getDropzone = function (form)
        {
            var DropzoneInputs = {
                /**
                 * Form
                 *
                 * The form where the inputs are in
                 *
                 * @since 1.0.0
                 *
                 * @type {Element} The form element
                 */
                form: null,

                /**
                 * Dropzone Collection
                 *
                 * Where store all of the Dropzone Instances.
                 *
                 * @since 1.0.0
                 *
                 * @type {*} An array of elements.
                 */
                dropzoneCollection: [],

                /**
                 * Create the container for the input
                 *
                 * @since 1.0.0
                 *
                 * @param {Element} wrapper The current container
                 * @param {Element} input   The input field
                 *
                 * @returns {Element} The new container
                 */
                createInputContainer: function (wrapper, input)
                {
                    var container = document.createElement('div');

                    container.appendChild(input);
                    // Set the dropzone class to the container field for the input.
                    container.classList.add('dropzone');
                    // Append the container.
                    wrapper.appendChild(container);

                    return container;
                },

                /**
                 * Get Dropzone Options
                 *
                 * @since 1.0.0
                 *
                 * @param {Element} input The element from which retrieve the name of the variable where the options are stored.
                 *
                 * @returns {Object} The options for the dropzone instance
                 */
                getDropzoneOptions: function (input)
                {
                    return _.extend({
                        // Where the images will be processed.
                        url: dllocalized.site_url + '/index.php',
                        // The template for the preview of the images.
                        previewTemplate: document.querySelector('#dropzone_template').innerHTML,
                    }, window[input.dataset.dzref]);
                },

                /**
                 * Process the dropzone queue
                 *
                 * @since 1.0.0
                 *
                 * @param {*} evt The event object.
                 */
                processDropzoneFiles: function (evt)
                {
                    _.forEach(this.dropzoneCollection, function (dropzone)
                    {
                        /**
                         * Set the callback for the dropzone object.
                         *
                         * These callbacks must be stored within the event.detail property.
                         * dropzoneCb: [
                         *  {
                         *      name: 'queuecomplete',
                         *      cb: function ()
                         *      {
                         *           // function body
                         *      }
                         *  }, {
                         *      name: 'sending',
                         *      cb: function ()
                         *      {
                         *          // function body
                         *      }
                         *  },
                         *  // etc....
                         * ]
                         *
                         */
                        if (!_.isUndefined(evt.detail.dropzoneCb) && !_.isEmpty(evt.detail.dropzoneCb)) {
                            _.forEach(evt.detail.dropzoneCb, function (item)
                            {
                                dropzone.on(item.name, function ()
                                {
                                    return item.cb.apply(dropzone, arguments);
                                });
                            });
                        }

                        dropzone.processQueue();
                    });
                },

                /**
                 * Initialize Dropzone
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                initializeDropzone: function ()
                {
                    // Get the inputs allowed for Dropzone.
                    this.inputs = _.filter(this.form.querySelectorAll('input[type="file"]'), function (input)
                    {
                        return input.classList.contains('use-dropzone');
                    });

                    // Empty by default.
                    // Don't return like other conditionals, we need to take the property to work properly
                    // within other objects.
                    if (!this.inputs.length) {
                        this.inputs = [];
                    }

                    // Initialize dropzone.
                    _.forEach(this.inputs, function (input)
                    {
                        // Create the container for the input file.
                        var container       = this.createInputContainer(input.parentNode, input);
                        // Then hide the input, we don't want to see it.
                        input.style.display = 'none';

                        // Set the existing files if there are ones.
                        var dzInstance     = new Dropzone(container, this.getDropzoneOptions(input)),
                            dzrefFilesData = dzInstance.options.dzrefFilesData;

                        if (dzrefFilesData.length) {
                            _.forEach(dzrefFilesData, function (mockFile)
                            {
                                dzInstance.options.addedfile.call(dzInstance, mockFile);
                                dzInstance.files.push(mockFile);
                                dzInstance.options.thumbnail.call(dzInstance, mockFile, mockFile.url);
                            }.bind(this));
                        }
                        // Push the element into the collection.
                        this.dropzoneCollection.push(dzInstance);

                        // Process the queue for the files that uses dropzone.
                        // Always perform this task at the 'ajax-form-submit-files' event.
                        // Otherwise the php handler will not know about the request.
                        this.form.addEventListener('ajax-form-submit-files', this.processDropzoneFiles);
                    }.bind(this));
                },

                /**
                 * Init
                 *
                 * @since 1.0.0
                 *
                 * @return void
                 */
                init: function ()
                {
                    // Initialize the dropzone.
                    this.initializeDropzone();
                },

                /**
                 * Constructor
                 *
                 * @since 1.0.0
                 *
                 * @param {Element} form The form from which retrieve the file inputs
                 *
                 * @returns {DlTypes} The object
                 */
                construct: function (form)
                {
                    this.form = form;

                    // Disable auto discover for all elements:
                    Dropzone.autoDiscover = false;

                    _.bindAll(
                        this,
                        'createInputContainer',
                        'initializeDropzone',
                        'processDropzoneFiles'
                    );

                    return this;
                }
            };

            return DropzoneInputs.construct(form);
        };

    }(_, window.jQuery, window.DlTypes, window.Dropzone, window.dllocalized)
);
