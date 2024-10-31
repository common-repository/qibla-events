/**
 * single-listing-contactform.js
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
    function (_, $, dllocalized, DL)
    {
        "use strict";

        var ContactForm = {
            /**
             * Get The Modal
             *
             * @since 1.0.0
             *
             * @param {object} evt The event object
             *
             * @return void
             */
            requestModal: function (evt)
            {
                evt.preventDefault();
                evt.stopImmediatePropagation();

                DL.Modal.construct('script', {
                    url: window.dllocalized.site_url + '/index.php',
                    data: {
                        dlajax_action: 'modal_contact_form_request'
                    },
                    beforeSend: null,
                    complete: null
                }, evt);
            },

            /**
             * Build The form
             *
             * @since 1.0.0
             *
             * @return void
             */
            buildForm: function ()
            {
                if (!DL.Modal.LoginRegisterForm.isInUse) {
                    // Build the form instance and set the property of the obj.
                    this.form = DL.Form.getForm(
                        DL.Modal.modal.querySelector('#qibla_contact_form'),
                        {
                            scrollOnTopAfterSubmit: false,
                            ajaxAction: 'contact_form_request',
                            extraFields: {
                                mailto: {
                                    type: 'hidden',
                                    name_suffix: 'mailto',
                                    sanitize: 'sanitize_email',
                                    filter_id: 513,
                                    value: this.mailTo,
                                }
                            },
                        }
                    );

                    // Then initialize the form instance, so we can take advance of ajax.
                    this.form.init();
                }
            },

            /**
             * Add Listeners
             *
             * @since 1.0.0
             *
             * @return void
             */
            addListeners: function ()
            {
                var addListener = DL.Utils.Events.addListener.bind(this);

                // Event to get and show the modal form.
                addListener(this.modalElement, 'click', this.requestModal);
                addListener(this.modalElement, 'preload-modal', this.requestModal, {once: true});
                addListener(window, 'dl_modal_opened', this.buildForm);
                addListener(window, 'dl_modal_closed', function ()
                {
                    // Remember to remove the listener or every time the modal is inserted into the document
                    // a new event handler will be registered.
                    window.removeEventListener('dl_modal_opened', this.buildForm, true);
                }.bind(this));
            },

            /**
             * Initialize
             *
             * @since 1.0.0
             *
             * @return void
             */
            init: function ()
            {
                this.addListeners();
            },

            /**
             * Construct
             *
             * @since 1.0.0
             */
            construct: function ()
            {
                _.bindAll(
                    this,
                    'requestModal',
                    'addListeners',
                    'buildForm'
                );

                this.modalElement = document.querySelector('.dlsocials-links__link--email');
                // Don't build if the main selector is missed.
                if (!this.modalElement) {
                    return;
                }

                // Build the mailTo, so we can send to the server to what address we want to send the email.
                this.mailTo = this.modalElement.getAttribute('href').replace('mailto:', '');
                // The form will be build after the modal has been retrieved.
                this.form   = null;

                return this;
            }
        };

        window.addEventListener('load', function ()
        {
            setTimeout(function ()
            {
                if (ContactForm.construct()) {
                    ContactForm.init();
                    // Lazy load, so we can cache the modal for a much speed opening.
                    DL.Utils.Events.dispatchEvent('preload-modal', ContactForm.modalElement);
                }
            }, 0);
        });

    }(_, window.jQuery, window.dllocalized, window.DL)
);
