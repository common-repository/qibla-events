/**
 * modal
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
    function (_, $, ClassList, DL, dllocalized, dlmodallocalized)
    {
        "use strict";

        /**
         * Modal
         *
         * @since 1.0.0
         *
         * @type {{id: string, showCloseBtn: boolean, pageWrapperEl: Element, addListener: addListener, addListeners: addListeners, isInUse: isInUse, addCloseBtn: addCloseBtn, createOverlay: createOverlay, open: open, close: close, autoOpen: autoOpen, init: init}}
         */
        DL.Modal = {

            /**
             * Modal ID
             *
             * @since 1.0.0
             *
             * @var {String} The value of the ID attribute that refer to the modal element
             */
            id: 'dlmodal',

            /**
             * Show the close btn
             *
             * @since 1.0.0
             *
             * @var {Boolean} True to show the close button, false otherwise
             */
            showCloseBtn: true,

            /**
             * The pageWrapper Element
             *
             * @since 1.0.0
             *
             * @var {Element} The Page Wrapper DOM Element
             */
            pageWrapperEl: document.querySelector('#dlpage-wrapper'),

            /**
             * Modal Cache
             *
             * Use this object to store the opened modal that are build in 'script' context.
             *
             * @since 1.0.0
             *
             * @type {object}
             */
            cache: {},

            /**
             * Add the listeners
             *
             * @since 1.0.0
             *
             * @return void
             */
            addListeners: function ()
            {
                DL.Utils.Events.addListener(window, 'load', this.autoOpen);

                if (this.showCloseBtn) {
                    DL.Utils.Events.addListener(this.btnClose, 'click', this.close);
                    DL.Utils.Events.addListener(document.body, 'click', function (evt)
                    {
                        if (DL.Utils.Functions.classList(evt.target).contains(this.id + '-overlay')) {
                            this.close();
                        }
                    }.bind(this));
                }
            },

            /**
             * Check if the modal is in use or not.
             *
             * @since 1.0.0
             *
             * @returns {boolean} True if is in use, false otherwise
             */
            isInUse: function ()
            {
                return this.inUse;
            },

            /**
             * Add the Close Btn
             *
             * @since 1.0.0
             *
             * @returns {Element} The newly button element
             */
            addCloseBtn: function ()
            {
                var fragment  = document.createDocumentFragment(),
                    container = document.createElement('a'),
                    text      = document.createElement('span'),
                    icon      = document.createElement('i');

                // The container.
                DL.Utils.Functions.classList(container).add(this.id + '-close');
                // The text.
                DL.Utils.Functions.classList(text).add(this.id + '-close__text', 'screen-reader-text');
                text.classList.add(this.id + '-close__text');
                text.innerText = window.dlmodallocalized.closeBtn;

                // The Icon.
                DL.Utils.Functions.classList(icon).add('la', 'la-times');

                // Build by Fragment.
                fragment.appendChild(container);
                container.appendChild(icon);
                container.appendChild(text);

                // Append the element.
                this.modal.insertBefore(fragment, this.modal.firstElementChild);

                return this.modal.querySelector('.' + this.id + '-close');
            },

            /**
             * Create the Overlay Element
             *
             * @since 1.0.0
             *
             * @returns {Element} The newly overlay element.
             */
            createOverlay: function ()
            {
                // Do not create the overlay if all-ready exists.
                if (!_.isUndefined(this.overaly) && !_.isEmpty(this.overaly)) {
                    return this.overlay;
                }

                var overlay = document.createElement('div');
                overlay.classList.add(this.id + '-overlay');
                // Will be displayed later.
                overlay.style.display = 'none';
                // Append the overlay to the modal template.
                this.template.appendChild(overlay);

                // Set the overlay property.
                this.overlay = this.template.querySelector('.' + this.id + '-overlay');
            },

            /**
             * Open Modal
             *
             * @since 1.0.0
             *
             * @return void
             */
            open: function ()
            {
                if (this.inUse) {
                    return;
                }

                // Then show the overlay.
                // Showing overlay need some little task such as blur the page content and disable the page scrolling.
                this.modal.style.opacity = 0;
                $(this.overlay).stop(true, true).fadeIn(275, function ()
                {
                    this.modal.classList.add('animated', 'slideInDown');
                    setTimeout(function ()
                    {
                        this.modal.style.opacity = 1;
                    }.bind(this), 0);

                    // Prevent the body and html elements to scroll while the modal is open.
                    document.querySelector('body').style.overflow = 'hidden';

                    // Set the inUse so, other scripts and events can know the the modal is taken.
                    this.inUse = true;

                    // Dispatch an event on modal opening.
                    // So other scripts may do additional his own stuffs.
                    DL.Utils.Events.dispatchEvent('dl_modal_opened', window);
                }.bind(this));
            },

            /**
             * Can be closed
             *
             * Check if modal can be closed
             *
             * @since 1.0.0
             *
             * @returns {boolean} True if can be closed, false otherwise
             */
            canBeClosed: function ()
            {
                return !_.isNull(this.modal.querySelector('.' + this.id + '-close'));
            },

            /**
             * Close the modal
             *
             * @since 1.0.0
             *
             * @return void
             */
            close: function ()
            {
                if (!this.inUse) {
                    return;
                }

                // Close only if the close btn exists.
                // Modals without the closing btn cannot be removed from the DOM.
                if (!this.canBeClosed()) {
                    return;
                }

                this.modal.classList.add('animated', 'slideOutUp');
                setTimeout(function ()
                {
                    this.modal.style.opacity = 0;
                    setTimeout(function ()
                    {
                        $(this.overlay).fadeOut(275, function ()
                        {
                            // Restore the page scrolling.
                            document.querySelector('body').style.overflow = 'auto';

                            // Release the modal.
                            this.inUse = false;

                            // Remove the element from the DOM if script context.
                            if ('script' === this.context) {
                                this.template.remove();
                            }

                            // Dispatch an event on modal closing.
                            // So other scripts may do additional his own stuffs.
                            DL.Utils.Events.dispatchEvent('dl_modal_closed', window);
                        }.bind(this));
                    }.bind(this), 0);
                }.bind(this), 0);
            },

            /**
             * Auto Open
             *
             * When we use the html context, the element of the modal and his content is rendered
             * as a DOM content not loaded via ajax.
             *
             * @since 1.0.0
             *
             * @return void
             */
            autoOpen: function ()
            {
                // Auto open must work only if the context of the modal is set to html.
                if ('html' === this.context) {
                    this.open();
                }
            },

            /**
             * Initialize Modal
             *
             * @since 1.0.0
             *
             * @return void
             */
            init: function ()
            {
                // Get the element and set the property.
                // Return if no template exists.
                var template = document.getElementById(this.id);
                if (!template) {
                    return;
                }

                this.template     = template;
                this.context      = this.template.getAttribute('data-context');
                this.showCloseBtn = this.template.getAttribute('data-showclosebtn');
                this.inUse        = false;
                this.modal        = this.template.querySelector('.' + this.id);

                // Create the overlay and then append the modal within it, so we can hide and show it
                // when needed.
                this.createOverlay();
                this.overlay.appendChild(this.modal);

                // Add the close element action to the modal.
                // We add it via js because html context generally need an action by the user and cannot be closed
                // if js is not active.
                if (this.showCloseBtn) {
                    this.btnClose = this.addCloseBtn();
                }

                // Set the listeners.
                this.addListeners();
            },

            /**
             * Construct
             *
             * @since 1.0.0
             *
             * @param {string} context The context of the modal. Can be 'html' or 'script'.
             * @param {object} data The data for the ajax call to pass if 'script' context.
             * @param {object} evt The event object in case of a 'script' context.
             */
            construct: function (context, data, evt)
            {
                // Bind the methods.
                _.bindAll(this,
                    'addListeners',
                    'isInUse',
                    'addCloseBtn',
                    'createOverlay',
                    'open',
                    'canBeClosed',
                    'close',
                    'autoOpen',
                    'init'
                );

                switch (context) {
                    // Html Context
                    case 'html':
                        this.init();
                        break;
                    // Script Context
                    case 'script':
                        // Create the Modal via ajax call.
                        this.constructViaAjax(data, evt);
                        break;
                }
            },

            /**
             * Construct Modal Via Ajax
             *
             * @since 1.0.0
             *
             * @param {object} data The data for the ajax call to pass if 'script' context.
             * @param {object} evt The event object in case of a 'script' context.
             */
            constructViaAjax: function (data, evt)
            {
                /**
                 * Insert the modal markup in DOM
                 *
                 * @since 1.0.0
                 *
                 * @param data The data mock containing the html markup
                 */
                function insertModalMarkup(data)
                {
                    if (_.isUndefined(data.html) ||
                        '' === data.html ||
                        ('openByDefault' in data && !data.openByDefault)
                    ) {
                        return;
                    }

                    if ('openByDefault' in data && !data.openByDefault) {
                        return;
                    }

                    // Append the modal to the document.
                    document.body.insertAdjacentHTML('beforeend', data.html);

                    // Initialize and open the modal after the element is added on DOM.
                    setTimeout(function ()
                    {
                        this.init();
                        this.open();
                    }.bind(this), 0);
                }

                // Get the key to use as cache key.
                var key = data.data.dlajax_action;

                // We all ready have that modal?
                if (!_.isUndefined(this.cache[key]) && !_.isEmpty(this.cache[key])) {
                    // If so, just simple get the markup and re-insert into the DOM.
                    insertModalMarkup.call(this, this.cache[key]);
                } else {
                    // Self instance.
                    var self = this;

                    // Build the Data for the ajax call.
                    data = _.extend({
                        method: 'POST',
                        /**
                         * Before Send
                         *
                         * @since 1.0.0
                         *
                         * @param jqXHR
                         * @param settings
                         */
                        beforeSend: function (jqXHR, settings)
                        {
                            DL.Utils.UI.toggleLoader(evt.target);
                        },

                        /**
                         * Success
                         *
                         * @since 1.0.0
                         *
                         * @param response
                         * @param textStatus
                         * @param jqXHR
                         */
                        success: function (response, textStatus, jqXHR)
                        {
                            insertModalMarkup.call(self, response.data.data);
                            // Add the newly modal to the cache.
                            self.cache[key] = {html: response.data.data.html};

                            setTimeout(function ()
                            {
                                // Execute the callback if a valid function.
                                if (typeof data.onSuccessCallback === 'function') {
                                    data.onSuccessCallback(response, jqXHR);
                                }
                            }, 0);
                        },

                        /**
                         * Error
                         *
                         * @since 1.0.0
                         *
                         * @param jqXHR
                         * @param textStatus
                         * @param errorThrown
                         */
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            console.error('DL Modal cannot be created: ' + errorThrown);
                        },

                        /**
                         * Complete
                         *
                         * @since 1.0.0
                         */
                        complete: function ()
                        {
                            DL.Utils.UI.toggleLoader(evt.target);
                        }
                    }, data);
                    // Get the call to retrieve the modal.
                    // The modal retrieved is determined by the dlajax_action value.
                    $.ajax(data.url, data);
                }
            }
        };

        /**
         * Login Register Modal
         *
         * @since 1.0.0
         *
         * @type {{getForms: getForms, createTogglers: createTogglers, toggleForms: toggleForms, init: init, addListeners: addListeners}}
         */
        DL.Modal.LoginRegisterForm = {
            /**
             * Is in use
             *
             * Since we use only one instance of the modal login register form, we need to know when
             * the modal with the form has been created in order to prevent other login/register modals
             * to perform again the same events.
             *
             * Why? Because some pages may need a logged in user and the modal is created by the server and
             * we may have in the same page the login/register modal that will be created by an event but the
             * object is initialized on window load.
             *
             * This may create duplicated content within the modal login/register.
             *
             * @since 1.0.0
             */
            isInUse: false,

            /**
             * Login Register Modal
             *
             * @since 1.0.0
             *
             * @returns void
             */
            addListeners: function ()
            {
                var addListener = DL.Utils.Events.addListener.bind(this);

                // At the last, for every form container, let's, toggle che classes on signup/in click event.
                // The internal callback, hide or show the correct form based on the click on the element.
                _.forEach(this.loginRegisterForm.querySelectorAll('a.dlmodal-form-toggler'), function (item)
                {
                    // Set the click event.
                    addListener(item, 'click', this.toggleForms);
                }.bind(this));

                var lostPasswordForm = DL.Modal.modal.querySelector('.dllogin-register-form__lostpassword-link');
                if (lostPasswordForm) {
                    addListener(
                        DL.Modal.modal.querySelector('.dllogin-register-form__lostpassword-link'),
                        'click',
                        this.toggleLostPassword
                    );
                }

                // Set the Ajax Form Submit Listeners.
                _.forEach(this.forms, function (form)
                {
                    // Get the for that need to be submitted.
                    form = form.querySelector('form');
                    // Set the listener.
                    addListener(form, 'submit', this.submitFormViaAjax);
                }.bind(this));

                // Release the form when the modal is closed.
                addListener(window, 'dl_modal_closed', function ()
                {
                    DL.Modal.LoginRegisterForm.isInUse = false;
                });
            },

            /**
             * Submit Form Via Ajax
             *
             * @since 1.0.0
             *
             * @param {object} evt The event object
             */
            submitFormViaAjax: function (evt)
            {
                evt.preventDefault();
                evt.stopImmediatePropagation();
                var form = DL.Form.getForm(evt.target, {
                    ajaxAction: evt.target.querySelector('#dlaction').value,
                    scrollOnTopAfterSubmit: false,
                    ajaxSuccessCb: function (success)
                    {
                        // Call the Handler of the form
                        form.successResponseHandler(success);

                        if (!_.isUndefined(success.data.data.redirect_to) && success.data.data.redirect_to) {
                            setTimeout(function ()
                            {
                                window.location = window.location.origin + success.data.data.redirect_to;
                            }, 2500);
                        }
                    }
                });

                // Initialize the form.
                form.init();
                form.submit(evt);
            },

            /**
             * Get Forms
             *
             * @since 1.0.0
             *
             * @returns {Array} The login, register and lost password forms
             */
            getForms: function ()
            {
                // After get the forms, retrieve the parent elements.
                // We need to work with the container not the forms itself.
                // Since the classes are not the same for the container we must map the forms parentNode.
                return _.map(this.loginRegisterForm.querySelectorAll('form'), function (form)
                {
                    return form.parentNode;
                });
            },

            /**
             * Create Togglers
             *
             * Create the elements for the togglers that are links used to fadeIn/Out the forms
             *
             * @since 1.0.0
             *
             * @returns void
             */
            createTogglers: function ()
            {
                // Then create the signup, signin and lost passoword elements, so we can toggle the forms.
                var signupLink             = document.createElement('a'),
                    signinLink             = document.createElement('a'),
                    lostpasswordLink       = document.createElement('a'),
                    goBack                 = document.createElement('a'),
                    loginFormLabelsWrapper = document.createElement('p');

                // Labels Wrapper
                loginFormLabelsWrapper.classList.add('dllogin-register-form__labels-wrapper');
                // Sign Up
                DL.Utils.Functions.classList(signupLink).add('dllogin-register-form__signup-link', 'dlmodal-form-toggler');
                signupLink.innerHTML = window.dlmodallocalized.signupLabel;

                // Sign Up Form.
                if (typeof this.forms[1] !== 'undefined' && 0 < parseInt(window.dllocalized.usersCanRegister)) {
                    // Sign In Toggler
                    // This is the toggler to show the sign up form in sign in.
                    var cloneLFlW = loginFormLabelsWrapper.cloneNode();
                    cloneLFlW.appendChild(signupLink);
                    this.forms[0].appendChild(cloneLFlW);

                    // Sign In
                    DL.Utils.Functions.classList(signinLink).add(
                        'dllogin-register-form__signin-link',
                        'dlmodal-form-toggler'
                    );
                    signinLink.innerHTML = window.dlmodallocalized.signinLabel;

                    cloneLFlW = loginFormLabelsWrapper.cloneNode();
                    cloneLFlW.appendChild(signinLink);
                    this.forms[1].appendChild(cloneLFlW);
                }

                // Lost Password Form.
                if (typeof this.forms[2] !== 'undefined' && 0 < parseInt(window.dllocalized.usersCanRegister)) {
                    // Lost Password
                    DL.Utils.Functions.classList(lostpasswordLink).add(
                        'dllogin-register-form__lostpassword-link',
                        'dlmodal-form-toggler'
                    );
                    lostpasswordLink.innerHTML = window.dlmodallocalized.lostPasswordLabel;
                    // Get Back
                    DL.Utils.Functions.classList(goBack).add(
                        'dllogin-register-form__go-back',
                        'dlmodal-form-toggler'
                    );
                    goBack.innerText = window.dlmodallocalized.goBackLabel;

                    cloneLFlW = loginFormLabelsWrapper.cloneNode();
                    cloneLFlW.appendChild(goBack);

                    if (typeof this.forms[2] !== 'undefined') {
                        this.forms[2].appendChild(cloneLFlW);
                    }

                    // Append the lost password element directly as Child of the modal Element.
                    var lostPasswordWrapper = document.createElement('p');
                    lostPasswordWrapper.classList.add('dllogin-register-form__lost-password-wrapper');
                    lostPasswordWrapper.appendChild(lostpasswordLink);

                    var lostPasswordTarget = this.loginRegisterForm.querySelector('#qibla_events_login_form-remember');

                    if (lostPasswordTarget) {
                        lostPasswordTarget.parentNode.appendChild(lostPasswordWrapper);
                    }
                }
            },

            /**
             * Toggle Forms
             *
             * @since 1.0.0
             *
             * @param {Object} evt The event object
             */
            toggleForms: function (evt)
            {
                evt.preventDefault();
                evt.stopPropagation();

                var from = 0,
                    to   = 2;

                // The Go back event.
                if (evt.target.classList.contains('dllogin-register-form__go-back')) {
                    from = 2;
                    to   = 0;
                } else if (evt.target.classList.contains('dllogin-register-form__signin-link') ||
                           evt.target.parentNode.classList.contains('dllogin-register-form__signin-link')
                ) {
                    from = 1;
                    to   = 0;
                } else if (evt.target.classList.contains('dllogin-register-form__signup-link') ||
                           evt.target.parentNode.classList.contains('dllogin-register-form__signup-link')
                ) {
                    from = 0;
                    to   = 1;
                }

                $(this.forms[from]).stop(true, true).fadeOut(function ()
                {
                    $(this.forms[to]).stop(true, true).fadeIn();

                    // Show the lost password element.
                    var lostPasswordWrapper = DL.Modal.modal.querySelector(
                        '.dllogin-register-form__lost-password-wrapper'
                    );

                    if (!$(lostPasswordWrapper).is('visible')) {
                        $(lostPasswordWrapper).stop(true, true).fadeIn();
                    }
                }.bind(this));
            },

            /**
             * Toggle Lost Password Form
             *
             * @since 1.0.0
             *
             * @param {Object} evt The event object
             */
            toggleLostPassword: function (evt)
            {
                evt.preventDefault();
                evt.stopPropagation();

                // Exclude Lost Password Form.
                for (var counter = 0; counter < 2; ++counter) {
                    // Hide all other forms and show only the lost password one.
                    $(this.forms[counter]).fadeOut(function ()
                    {
                        if (2 === counter) {
                            $(this.forms[2]).fadeIn();
                        }
                    }.bind(this));
                }
            },

            /**
             * Initialize
             *
             * @since 1.0.0
             *
             * @returns void
             */
            init: function ()
            {
                _.bindAll(this,
                    'addListeners',
                    'createTogglers',
                    'getForms',
                    'toggleForms',
                    'toggleLostPassword'
                );

                // Do nothing if there are no elements that need to use the modal.
                if (_.isUndefined(DL.Modal.modal)) {
                    return;
                }

                // Try to retrieve the login register form.
                this.loginRegisterForm = DL.Modal.modal.querySelector('.dllogin-register-form');
                if (!this.loginRegisterForm) {
                    return;
                }

                // Set the forms
                this.forms = this.getForms();
                if (_.isEmpty(this.forms)) {
                    return;
                }

                // 0 - Sign In Form
                // 1 - Sign Up Form
                // 2 - Lost Password Form
                // Set the properly classes to show and hide the forms.
                // @todo Some installation may not allow to register and/or reset the password. So make 1 and 2 optional.
//                this.forms[0].classList.add('is-visible');

                if (typeof this.forms[1] !== 'undefined') {
                    $(this.forms[1]).hide();
                }

                if (typeof this.forms[2] !== 'undefined') {
                    $(this.forms[2]).hide();
                }

                // Set the form togglers.
                this.createTogglers();
                // Set the listeners.
                this.addListeners();

                // Flag the form as in use.
                this.isInUse = true;
            },
        };

        // Initialize the Modal.
        DL.Modal.construct('html');
        DL.Modal.LoginRegisterForm.init();

    }(_, window.jQuery, window.ClassList, window.DL, window.dllocalized, window.dlmodallocalized)
);
