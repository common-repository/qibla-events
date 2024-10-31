/**
 * Search Dates
 *
 * @since 1.0.0
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
    function (window, _, $, dllocalized) {

        'use strict';

        /**
         * Create Calendar
         *
         * @since 1.0.0
         *
         * @param elem
         */
        function createCalendar(elem)
        {
            if (!elem) {
                return;
            }

            // Set form action.
            elem.form.setAttribute('action', dllocalized.site_url + '/' + dllocalized.events_permalink + '/');

            $(elem).datepicker({
                dateFormat: 'yy-mm-dd',
                defaultDate: dllocalized.first_date_events,
                beforeShowDay: function (date) {
                    var string = $.datepicker.formatDate('yy-mm-dd', date);
                    // Only dates that have at least one associated post
                    return [dllocalized.dates_saved_events.indexOf(string) !== -1];
                },
                beforeShow: function () {
                    $(this).datepicker("widget").addClass('ev-calendar-search');
                },
                onSelect: function () {
                    $(this).change();
                }
            }).change(function () {
                if (this.value) {
                    elem.form.setAttribute('action', dllocalized.site_url + '/' + dllocalized.dates_permalink + '/' + this.value + '/');
                } else {
                    elem.form.setAttribute('action', dllocalized.site_url + '/' + dllocalized.events_permalink + '/');
                }
            });

            // Slide Down Calendar.
            $(elem).datepicker('option', 'showAnim', 'slideDown');
        }

        window.addEventListener('load', function (e) {
            var calendar = document.querySelectorAll('.dlsearch--dates .dlsearch__input');
            _.forEach(calendar, function (cal) {
                if (cal) {
                    // Create calendar.
                    createCalendar(cal);
                }
            })

        });
    }(window, _, jQuery, window.dllocalized)
);
