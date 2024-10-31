/**
 * Sharing Popup
 *
 * @since      1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

;(
    function (_, $, DL) {

        "use strict";

        /**
         * Popup
         *
         * @since 1.0.0
         *
         * @param obj      object The object to listener
         * @param selector string The selector for get element
         * @param event    string The event on the element
         */
        function popup(obj, selector, event)
        {
            DL.Utils.Events.addListener(obj, 'click', function () {
                var elem = document.querySelector(selector);

                if ('fadeIn' === event) {
                    $(elem).fadeIn();
                } else if ('fadeOut' === event) {
                    $(elem).fadeOut();
                }

            });
        }

        /**
         * Build Popup
         *
         * @since 1.0.0
         */
        function buildSharePopup()
        {
            var shareContainer = document.querySelector('.dlshare-popup');

            if (shareContainer) {
                var clone = shareContainer.cloneNode(true);
                // The Share Fragment.
                var shareWrapper = document.createElement('div');
                var close = document.createElement('a');

                shareWrapper.classList.add('dlshare-wrapper-popup');
                close.classList.add('dlshare-close');

                // close element.
                close.innerHTML = '<i class="la la-times" aria-hidden="true"></i>';

                // Append the elements.
                shareWrapper.appendChild(close);
                shareWrapper.appendChild(clone);

                // Append to body
                document.body.insertAdjacentHTML('beforeend', shareWrapper.outerHTML);
            }
        }

        DL.Utils.Events.addListener(window, 'load', function () {
            // Build popup.
            buildSharePopup();
            // Open popup.
            popup(
                document.getElementById('share_popup_trigger'),
                '.dlshare-wrapper-popup',
                'fadeIn'
            );
            // Close popup.
            popup(
                document.querySelector('.dlshare-close'),
                '.dlshare-wrapper-popup',
                'fadeOut'
            );
            popup(
                document.querySelector('.dlshare-wrapper-popup'),
                '.dlshare-wrapper-popup',
                'fadeOut'
            );
        });

    }(window._, window.jQuery, window.DL)
);
