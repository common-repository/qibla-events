/**
 * Color Picker
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
    function (_, $)
    {
        'use strict';

        // Initialize the Color Picker.
        var picker = document.querySelectorAll('[data-type="color-picker"]');

        if (!picker.length) {
            return false;
        }

        _.forEach(picker, function (item)
        {
            // Color Picker Options
            var options = {
                mode: 'rgba',
                palettes: false,
                default: item.getAttribute('data-default-color'),
                clear: function (e)
                {
                    var color = item.getAttribute('data-default-color') || '';
                    $(this).iris('color', '#' + color.replace('#', ''));
                }.bind(item)
            };

            $(item).wpColorPicker(options);
        });

    }(window._, window.jQuery)
);