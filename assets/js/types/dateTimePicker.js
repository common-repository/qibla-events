/**
 * Date Picker
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
    function ($, dllocalized)
    {
        'use strict';

        /**
         * Normalize Time Format
         *
         * Javascript doesn't like the Php time formats: g:i A, g:i a, so we convert it to H:i
         * within every datetimepicker field.
         *
         * For more info have a look at the QiblaEvents\Form\Types\DateTime.php
         *
         * @param time The time string to normalize.
         * @returns string The normalized time string
         */
        function normalizeTimeFormat(time)
        {
            var tf;

            switch (time) {
                case 'g:i a':
                case 'g:i A':
                    tf = 'H:i';
                    break;
                default:
                    tf = time;
                    break;
            }

            return tf;
        }

        window.addEventListener('load', function ()
        {
            // Get the Inputs.
            var $inputs = $('[data-type="datetimepicker"]');

            if (!$inputs.length || typeof window.dllocalized === 'undefined') {
                return false;
            }

            // Retrieve the time format.
            var timeFormat = normalizeTimeFormat(window.dllocalized.time_format);

            // Set the locale based on wordpress language.
            $.datetimepicker.setLocale(window.dllocalized.lang.substr(0, 2));
            // Settings http://xdsoft.net/jqplugins/datetimepicker/
            $($inputs).datetimepicker({
                step: 15,
                weeks: false,
                minDate: 0,
                todayButton: false,
                format: window.dllocalized.date_format + ' ' + timeFormat,
                formatDate: window.dllocalized.date_format,
                formatTime: window.dllocalized.time_format
            });
        });

    }(window.jQuery, window.dllocalized)
);
