/**
 * Calendar Filter
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

            // Create Calendar.
            var cal = document.createElement('div');
            cal.setAttribute('id', 'qibla_calendar_filter');
            elem.parentNode.insertAdjacentElement('beforebegin', cal);

            // Remove search box.
            var searchBox = document.getElementById('select2-qibla_dates_filter-results').parentElement.parentElement;
            searchBox.querySelector('.select2-search--dropdown').remove();

            var calendar = document.getElementById('qibla_calendar_filter');
            if (!calendar) {
                return;
            }

            $(calendar).datepicker({
                dateFormat: 'yy-mm-dd',
                defaultDate: dllocalized.first_date_events,
                altField: '#qibla_calendar_filter',
                beforeShowDay: function (date) {
                    var string = $.datepicker.formatDate('yy-mm-dd', date);
                    // Only dates that have at least one associated post
                    return [dllocalized.dates_saved_events.indexOf(string) !== -1];
                },
                onSelect: function () {
                    $(this).change();
                }
            }).change(function () {
                // Select option group days.
                var lists = document.querySelectorAll('#select2-qibla_dates_filter-results li[aria-label="days"] ul.select2-results__options li');
                var i;
                for (i = 0; i < lists.length; i++) {
                    checkValue(this.value, lists[i]);
                }
                // target new linck.
                //window.location.href = dllocalized.site_url + '/' + dllocalized.dates_permalink + '/' + this.value + '/';
            });
        }

        /**
         * Check value in item ID.
         *
         * @since 1.0.0
         *
         * @param value The current value to selected calendar date
         * @param item  The select2 dropdown lists
         */
        function checkValue(value, item)
        {
            if(item.getAttribute('id').includes(value)){
                $(item).trigger({type:'mouseup'});
            }
        }

        /**
         * Icon Calendar
         *
         * @since 1.0.0
         *
         * @param elem
         */
        function createTrigger(elem)
        {
            if (!elem) {
                return;
            }

            var wrapper = document.getElementById('trigger_calendar');
            if (wrapper) {
                wrapper.remove();
            }

            // Create element for calendar trigger.
            var text = document.createTextNode(dllocalized.label_calendar);
            var trigger = document.createElement('div');
            trigger.setAttribute('id', 'trigger_calendar');
            trigger.setAttribute('class', 'ev-trigger-calendar');
            trigger.appendChild(text);

            if (elem) {
                elem.insertAdjacentElement('beforebegin', trigger);
            }
        }

        /**
         * Toggle focus.
         *
         * @since 1.0.0
         *
         * @param trigger  The trigger elem jquery object
         * @param calendar The input elem jquery object
         */
        function openCalendar(trigger)
        {
            trigger.on('click', function () {
                var input = document.getElementById('qibla_calendar_filter');
                input.style.opacity = 1;
                input.style.height = '100%';
                input.style.width = '100%';

                input.classList.remove('screen-reader-text');
            });
        }

        window.addEventListener('load', function (e) {
            var dates = document.getElementById('qibla_dates_filter');
            if (!dates) {
                return;
            }

            // Select Open.
            $(dates).on('select2:open', function () {
                var calendar = document.getElementById('qibla_calendar_filter');
                // Init cal.
                createTrigger(
                    document.getElementById('select2-qibla_dates_filter-results')
                );
                if (!calendar) {
                    // Create calendar.
                    createCalendar(document.getElementById('trigger_calendar'));
                }
                // Open Calendar
                openCalendar($(document.getElementById('trigger_calendar')));

                // Open the calendar immediately. to be deleted when the select options are activated.
                $(document.getElementById('trigger_calendar')).click();
                //document.getElementById('select2-qibla_event_dates_filter-results').style.display = 'none';
                var lists = document.querySelectorAll('#select2-qibla_dates_filter-results li');
                _.forEach(lists,  function (item) {
                    item.style.display = 'none';
                });

                document.getElementById('trigger_calendar').style.display = 'none';

            });
        });
    }(window._, window.jQuery, window.dllocalized)
);
