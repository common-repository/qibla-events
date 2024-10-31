/**
 * Form
 *
 * @since 1.0.0
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
window.DlTypes = window.DlTypes || {};

;(
    function (_, ClassList, $, dllocalized, dlformlocalized, DL, DlTypes)
    {
        "use strict";

        // Initialize the object.
        DL.Form = {
            /**
             * Get an new instance of the form
             *
             * @since 1.0.0
             *
             * @param {Element} form The form DOM Element
             * @param {object} options The options to build the form instance.
             *
             * @returns {*} A new instance of the form object.
             */
            getForm: function (form, options)
            {
                /**
                 * Form Handler
                 *
                 * @since 1.0.0
                 *
                 * @type {Object}
                 */
                var FormHandler = {
                    /**
                     * Add Listener
                     *
                     * @todo switch to DL.Utils.Event.addListener.
                     *
                     * @since 1.0.0
                     *
                     * @param obj
                     * @param event
                     * @param callback
                     * @param options
                     * @param extra
                     * @returns {FormHandler}
                     */
                    addListener: function (obj, event, callback, options, extra)
                    {
                        if (!obj || _.isArray(obj)) {
                            throw 'Invalid Object on addListener.';
                        }

                        if (!_.isFunction(callback)) {
                            throw 'Invalid callback on addListener.';
                        }

                        // Set the event listener.
                        obj.addEventListener(event, function (e)
                        {
                            callback.call(this, e, extra);
                        }.bind(this), options);

                        return this;
                    },

                    /**
                     * Dispatch Event
                     *
                     * Create an event by string and pass the data to the event listener.
                     *
                     * @since 1.0.0
                     *
                     * @param eventName
                     * @param data
                     */
                    dispatch: function (eventName, data)
                    {
                        // @todo Need to set custom options.
                        var customEvent = new CustomEvent(eventName, {
                            detail: data
                        });

                        // Dispatch the event.
                        this.form.dispatchEvent(customEvent);
                    },

                    /**
                     * Append Hidden Inputs to Event details
                     *
                     * Append the hidden inputs of the form to allow the dispatcher for files upload
                     * to allow validate it self on server.
                     *
                     * Files send via Dropzone not send nonce and extra input needed by the server to validate the action.
                     *
                     * @since 1.0.0
                     *
                     * @returns {{}}
                     */
                    getFormHiddenFields: function ()
                    {
                        // Get the hidden fields and add them to the data to send to the event.
                        var fields       = this.form.querySelectorAll('input[type="hidden"]'),
                            hiddenInputs = {};

                        if (fields.length) {
                            _.forEach(fields, function (field)
                            {
                                hiddenInputs[field.getAttribute('name')] = field.value;
                            });
                        }

                        return hiddenInputs;
                    },

                    /**
                     * Set Invalid Description for Field
                     *
                     * @since 1.0.0
                     *
                     * @param {Element} element The invalid element
                     * @param {*} itemData The object from which retrieve the data needed to build the invalid description.
                     */
                    setInvalidDescriptionForField: function (element, itemData)
                    {
                        var invalidDesc = element.parentNode.querySelector(
                            '.' + itemData.containerClass[0] + '__invalid-description'
                        );

                        if (invalidDesc) {
                            invalidDesc.remove();
                        }

                        // Create the invalid description element.
                        invalidDesc = document.createElement('p');
                        invalidDesc.classList.add(itemData.containerClass[0] + '__invalid-description');
                        invalidDesc.innerText = itemData.invalidDescription.replace(/<[^>]+>/ig, '');

                        element.parentNode.setAttribute('class', itemData.containerClass.join(' '));
                        element.parentNode.appendChild(invalidDesc);
                    },

                    /**
                     * Handle Error Response
                     *
                     * @since 1.0.0
                     *
                     * @param error
                     */
                    errorResponseHandler: function (error)
                    {
                        try {
                            var responseJson = error.responseJSON.data;

                            if (!_.isEmpty(responseJson)) {
                                _.forEach(responseJson.data, function (item)
                                {
                                    var element = document.querySelector(item.selector);
                                    if (element) {
                                        this.setInvalidDescriptionForField(element, item);
                                    }
                                }.bind(this));
                            }

                            // Show the alert element.
                            this.showAlert(responseJson.message, 'error', ['la', 'la-times'], true);
                        } catch (e) {
                            // Show an alert describing the issue.
                            this.showAlert(window.dlformlocalized.unknownError, 'error', ['la', 'la-times'], true);
                        }

                        // Toggle Loader
                        this.toggleLoader();
                    },

                    /**
                     * Handle Success Response
                     *
                     * @since 1.0.0
                     *
                     * @param success
                     */
                    successResponseHandler: function (success)
                    {
                        var response = success.data;

                        // Upload the files if needed.
                        // Also append extra data for form that are allowed to insert posts.
                        // Hidden fields are necessary to pass server checks.
                        if (this.dropZone.inputs.length) {
                            var eventDetail = _.extend({
                                dlajax_action: 'store_media_file',
                                dropzoneCb: [
                                    {
                                        name: 'sending',
                                        cb: function (file, xhr, formData)
                                        {
                                            _.forEach(this.getFormHiddenFields(), function (value, key)
                                            {
                                                // Append any data passed by the event a part of the request.
                                                // Used for example to pass nonce fields and extra hidden data.
                                                formData.append(key, value);
                                            });
                                        }.bind(this)
                                    }
                                ]
                            }, this.getFormHiddenFields());

                            // Dispatch file upload.
                            this.dispatch('ajax-form-submit-files', eventDetail);
                        } else {
                            // Toggle Loader
                            this.toggleLoader();
                            // Show the alert element.
                            this.showAlert(response.message, 'success', ['la', 'la-check'], true);
                        }
                    },

                    /**
                     * Show Alert
                     *
                     * @todo Make it as an indipendent object.
                     *
                     * @since 1.0.0
                     *
                     * @param {string} message
                     * @param {string} type
                     * @param {*} iconClass
                     * @param {boolean} dismissable
                     */
                    showAlert: function (message, type, iconClass, dismissable)
                    {
                        // Get the template.
                        var alertTmpl = document.querySelector('#dlalert-tmpl');

                        // Build The alert if alert template is found.
                        if (alertTmpl) {
                            // Remove the previously alert.
                            var alert = this.form.parentNode.querySelector('.dlalert');
                            if (alert) {
                                alert.remove();
                            }

                            // Compile it.
                            var tmpl         = _.template(alertTmpl.innerHTML);
                            var templateObj  = {
                                type: type,
                                message: message,
                                icon: {
                                    classList: iconClass.join(' '),
                                }
                            };
                            var tmplCompiled = tmpl(templateObj);

                            // Then append the newly markup before the element.
                            this.form.insertAdjacentHTML('beforebegin', tmplCompiled);

                            if (dismissable) {
                                // Then give the time to read the alert and hide it.
                                setTimeout(function ()
                                {
                                    $(this.form.parentNode.querySelector('.dlalert')).slideUp();
                                }.bind(this), 4000);
                            }
                        }
                    },

                    /**
                     * Toggle the loader
                     *
                     * @since 1.0.0
                     */
                    toggleLoader: function ()
                    {
                        var submit = this.form.querySelector('input[type="submit"]');

                        DL.Utils.UI.toggleLoader(
                            submit,
                            function ()
                            {
                                if (this.options.scrollOnTopAfterSubmit) {
                                    this.scrollOnTopOfForm();
                                }
                            }.bind(this),
                            function ()
                            {
                                $(submit).stop(true, true).fadeOut();
                            }
                        );
                    },

                    /**
                     * Scroll on top of Form
                     *
                     * @since 1.0.0
                     *
                     * @return void
                     */
                    scrollOnTopOfForm: function ()
                    {
                        var _self = this;

                        $('html,body').animate({
                            // Scroll on parent to not stick the form on top of the window.
                            scrollTop: _self.form.parentNode.offsetTop
                        }, 1600);
                    },

                    /**
                     * Validate Dropzone Files
                     *
                     * @since 1.0.0
                     *
                     * @returns {boolean} True if the field is required and there are files, false is required but empty.
                     */
                    validateDropzoneFiles: function ()
                    {
                        var isValid     = true,
                            currentFile = null,
                            input       = null;

                        // Check if there are files to upload.
                        for (var c = 0; c < this.dropZone.dropzoneCollection.length; ++c) {
                            currentFile = this.dropZone.dropzoneCollection[c];
                            // File is required but no files found?
                            // Get the input type and check if is set as required.
                            // If set as required the form must not be submitted.
                            input = currentFile.element.firstElementChild;

                            // If there are files that need to be uploaded but are rejected,
                            // Inform the script about this, so we can prevent to submit the form.
                            if (currentFile.getRejectedFiles().length) {
                                this.showAlert(window.dlformlocalized.rejectedFiles, 'error', ['la', 'la-times'], true);
                                // Prevent to make the ajax call.
                                isValid = false;
                                break;

                                // Check if the input file is required and the are no files stored.
                                // If not, show the alert, go to the top of the form and set the input field as invalid.
                            } else if (input.required && !currentFile.files.length) {
                                this.showAlert(window.dlformlocalized.missedFile, 'error', ['la', 'la-times'], true);
                                input.parentNode.parentNode.classList.add('is-invalid');
                                // Prevent to make the ajax call.
                                isValid = false;
                                break;
                            }
                        }

                        if (!isValid) {
                            this.scrollOnTopOfForm();
                        }

                        return isValid;
                    },

                    /**
                     * Submit Request
                     *
                     * @since 1.0.0
                     *
                     * @param evt An event instance
                     */
                    submit: function (evt)
                    {
                        evt.preventDefault();
                        evt.stopPropagation();

                        if (!this.validateDropzoneFiles()) {
                            return;
                        }

                        // Throw error if no ajax action has been set.
                        if (!this.options.ajaxAction) {
                            throw "Ajax action not set. You must set an action to send data via ajax.";
                        }

                        // Set the data to send to the server.
                        var data = {
                            dlajax_action: this.options.ajaxAction,
                            enc_data: $(this.form).serialize(),
                            extra_data: {
                                fields: this.options.extraFields
                            }
                        };

                        // Perform the request.
                        // @todo Add custom url as parameter for the construct.
                        $.ajax(window.dllocalized.site_url + '/index.php', _.extend({
                            method: 'post',
                            data: data
                        }, {
                            beforeSend: this.options.ajaxBeforeSendCb.bind(this),
                            error: this.options.ajaxErrorCb.bind(this),
                            success: this.options.ajaxSuccessCb.bind(this),
                        }));
                    },

                    /**
                     * Initialize Handler
                     *
                     * @since 1.0.0
                     */
                    init: function ()
                    {
                        // Set events.
                        this.addListener(this.form, 'submit', this.submit);

                        // Initialize Dropzone.
                        this.dropZone.init();

                        // Callback on INIT.
                        if (!_.isUndefined(this.options.onInit) && _.isFunction(this.options.onInit)) {
                            this.options.onInit.call(this);
                        }
                    },

                    /**
                     * Construct
                     *
                     * @since 1.0.0
                     *
                     * @param {Element} form The form to handle.
                     * @param {*} options The options to use for form.
                     *
                     * @returns {FormHandler} This for chaining
                     */
                    construct: function (form, options)
                    {
                        // Binding.
                        _.bindAll(
                            this,
                            'submit',
                            'showAlert',
                            'errorResponseHandler',
                            'successResponseHandler',
                            'toggleLoader',
                            'scrollOnTopOfForm',
                            'dispatch',
                            'setInvalidDescriptionForField'
                        );

                        // Set the form instance.
                        // The form that will be handled for submission.
                        this.form = form;

                        // Set the dropzone.
                        this.dropZone = DlTypes.getDropzone(this.form);

                        // Set options.
                        this.options = _.extend({
                            scrollOnTopAfterSubmit: true,
                            ajaxBeforeSendCb: this.toggleLoader,
                            ajaxErrorCb: this.errorResponseHandler,
                            ajaxSuccessCb: this.successResponseHandler,
                            extraFields: {},
                            onInit: null
                        }, options);

                        return this;
                    },
                };

                // Create and return a new instance.
                return FormHandler.construct(form, options);
            }
        };
    }(_, window.ClassList, window.jQuery, window.dllocalized, window.dlformlocalized, window.DL, window.DlTypes)
);
