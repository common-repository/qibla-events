/**
 * Multi Dates Picker
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
    function (_, $, dllocalized) {
        "use strict";
        document.addEventListener('DOMContentLoaded', function () {
            // Get the multi date Inputs.
            var inputs = document.querySelectorAll('[data-type="multidatespicker"]');
            if (!inputs.length) {
                return false;
            }

            _.forEach(inputs, function (input) {
                // Create Div for display calendars
                var inputWrapper = document.createElement('div');
                inputWrapper.setAttribute('id', 'multidates_' + input.getAttribute('id'));
                input.parentNode.insertBefore(inputWrapper, input);

                // Hide input
                input.style.opacity = '0';
                input.style.height = '0px';
                input.style.width = '0px';
                input.style.padding = '0px';
                input.style.position = 'absolute';

                // Selected dates to add in calendar
                var currDates = input.getAttribute('value') !== '' ? input.getAttribute('value').split(',') : [];

                // Disabled after today days
                var dateToday = new Date();
                if (null !== dateToday) {
                    $(inputWrapper).datepicker({
                        dateFormat: input.getAttribute('data-format'),
                        defaultDate: dllocalized.event_saved_default_date,
                        changeMonth: dllocalized.event_date_month_year,
                        changeYear: dllocalized.event_date_month_year,
                        numberOfMonths: 1,
                        altField: '#' + input.getAttribute('id')
                    });
                }

                // Format and Default date.
                $(inputWrapper).multiDatesPicker({
                    dateFormat: input.getAttribute('data-format')
                });

                // Remove Today if not selected
                $(inputWrapper).multiDatesPicker('removeDates', dateToday);

                // Add Selected dates
                if (currDates.length > 0) {
                    $(inputWrapper).multiDatesPicker({addDates: currDates});
                }
            });

            // Get Time Inputs.
            var inputsTime = document.querySelectorAll('[data-type="timepicker"]');
            if (!inputsTime.length) {
                return false;
            }

            _.forEach(inputsTime, function (input) {
                $(input).timepicker({
                    'timeFormat': 'H:i',
                    'step': '15'
                });
            });
        });
    }(window._, window.jQuery, window.dllocalized, window.dllocalized)
);