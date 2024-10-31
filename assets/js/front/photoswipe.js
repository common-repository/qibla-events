/**
 * Dl PhotoSwipe
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

;(
    function (_, Modernizr, PhotoSwipe, PhotoSwipeUI_Default)
    {
        "use strict";

        window.addEventListener('load', function (e)
        {
            // Try to find the data list elements.
            var script = document.getElementById('dlgallery');
            if (!script) {
                return;
            }

            var list = JSON.parse(document.getElementById('dlgallery').innerText);
            if (_.isEmpty(list)) {
                return;
            }

            // Retrieve the jumbotron and wrap it within an anchor element.
            var jumbotron = document.getElementsByClassName('dljumbotron');
            if (!jumbotron.length) {
                return;
            }

            // Work only with one jumbotron element.
            // Generally there are not more tha one jumbotron per page, but just to be sure.
            jumbotron = jumbotron[0];

            var a = document.createElement('a');
            a.classList.add('dljumbotron-gallery-wrapper');
            a.setAttribute('href', '#');

            jumbotron.parentElement.insertBefore(a, jumbotron);
            a.appendChild(jumbotron);

            // Create and insert the gallery label.
            var dataLabel = jumbotron.getAttribute('data-gallerylabel'),
                container = document.querySelector('.dljumbotron');

            if (dataLabel && container) {
                var iconLabel = document.createElement('span');
                iconLabel.classList.add('dljumbotron-gallery-label');

                // First is the label, latter the list of the classes to assign for the icon.
                dataLabel = dataLabel.split(':');

                var iconClass = dataLabel[1].split(',');

                iconLabel.innerHTML = '<i class="' + iconClass.join(' ') + '" aria-hidden="true"></i>' + dataLabel[0];

                // Append to the anchor parent.
                container.insertBefore(iconLabel, container.firstElementChild);
            }

            // This is the template for the script.
            // Be sure that exists.
            var pswp = document.getElementsByClassName('pswp');
            if (!pswp.length) {
                return;
            }

            pswp = pswp[0];

            // Define options
            var options = {
                // optionName: 'option value'
                // start at first slide
                index: 0,
                shareButtons: false,
                shareEl: false,
            };

            a.addEventListener('click', function (evt)
            {
                evt.preventDefault();
                evt.stopImmediatePropagation();

                // Initializes and opens PhotoSwipe
                var gallery = new PhotoSwipe(pswp, PhotoSwipeUI_Default, list, options);
                gallery.init();

            });
        });
    }(_, Modernizr, PhotoSwipe, PhotoSwipeUI_Default)
);