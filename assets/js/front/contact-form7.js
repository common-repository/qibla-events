/**
 * Contact Form 7
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
;(function ()
{
    "use strict";

    document.addEventListener('DOMContentLoaded', function ()
    {
        // Get the contact form instance.
        var cf7s = document.querySelectorAll('.wpcf7');
        if (!cf7s.length) {
            return;
        }

        _.forEach(cf7s, function (cf7)
        {
            // Remove the ajax loader element and replace with our own.
            var ajaxLoader = cf7.querySelector('.ajax-loader');
            if (ajaxLoader) {
                ajaxLoader.remove();

                // Add the newly loader.
                var ajaxLoader = document.querySelector('.svgloader');
                if (ajaxLoader) {
                    // Append the ajax loader after the submit element.
                    var submit = cf7.querySelector('input[type="submit"]');
                    if (submit) {
                        submit.parentElement.insertBefore(ajaxLoader, submit.nextElementSibling);
                    }
                }
            }
        });
    });
}());