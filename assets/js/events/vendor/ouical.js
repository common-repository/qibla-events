;(function (exports) {
    var MS_IN_MINUTES = 60 * 1000;

    /**
     * formatTime
     *
     * @param date
     * @returns {string}
     */
    var formatTime = function (date) {
        return date.toISOString().replace(/-|:|\.\d+/g, '');
    };

    /**
     * calculateEndTime
     *
     * @param event
     * @returns {*}
     */
    var calculateEndTime = function (event) {
        return event.end ?
            formatTime(event.end) :
            formatTime(new Date(event.start.getTime() + (event.duration * MS_IN_MINUTES)));
    };

    /**
     * calendarGenerators
     *
     * @type {{google: google, yahoo: yahoo, ics: ics, ical: ical, outlook: outlook}}
     */
    var calendarGenerators = {
        /**
         * google
         * @param event
         * @returns {string}
         */
        google: function (event) {
            var startTime = formatTime(event.start);
            var endTime = calculateEndTime(event);

            var href = encodeURI([
                'https://www.google.com/calendar/render',
                '?action=TEMPLATE',
                '&text=' + (event.title || ''),
                '&dates=' + (startTime || ''),
                '/' + (endTime || ''),
                '&details=' + (event.description || ''),
                '&location=' + (event.address || ''),
                '&sprop=&sprop=name:'
            ].join(''));
            return '<a class="oui oui-google" target="_blank" href="' + href + '">Google Calendar <em>(online)</em></a>';
        },

        /**
         * yahoo
         *
         * @param event
         * @returns {string}
         */
        yahoo: function (event) {
            var eventDuration = event.end ?
                ((event.end.getTime() - event.start.getTime()) / MS_IN_MINUTES) :
                event.duration;

            // Yahoo dates are crazy, we need to convert the duration from minutes to hh:mm
            var yahooHourDuration = eventDuration < 600 ?
                '0' + Math.floor((eventDuration / 60)) :
                Math.floor((eventDuration / 60)) + '';

            var yahooMinuteDuration = eventDuration % 60 < 10 ?
                '0' + eventDuration % 60 :
                eventDuration % 60 + '';

            var yahooEventDuration = yahooHourDuration + yahooMinuteDuration;

            // Remove timezone from event time
            var st = formatTime(new Date(event.start - (event.start.getTimezoneOffset() *
                                                        MS_IN_MINUTES))) || '';

            var href = encodeURI([
                'http://calendar.yahoo.com/?v=60&view=d&type=20',
                '&title=' + (event.title || ''),
                '&st=' + st,
                '&dur=' + (yahooEventDuration || ''),
                '&desc=' + (event.description || ''),
                '&in_loc=' + (event.address || '')
            ].join(''));

            return '<a class="oui oui-yahoo" target="_blank" href="' + href + '">Yahoo! Calendar <em>(online)</em></a>';
        },

        /**
         * ics
         *
         * @param event
         * @param eClass
         * @param calendarName
         * @returns {string}
         */
        ics: function (event, eClass, calendarName) {
            var startTime = formatTime(event.start);
            var endTime = calculateEndTime(event);

            var href = encodeURI(
                'data:text/calendar;charset=utf8,' + [
                                                       'BEGIN:VCALENDAR',
                                                       'VERSION:2.0',
                                                       'BEGIN:VEVENT',
                                                       'URL:' + document.URL,
                                                       'DTSTART:' + (startTime || ''),
                                                       'DTEND:' + (endTime || ''),
                                                       'SUMMARY:' + (event.title || ''),
                                                       'DESCRIPTION:' + (event.description || ''),
                                                       'LOCATION:' + (event.address || ''),
                                                       'END:VEVENT',
                                                       'END:VCALENDAR'
                                                   ].join('\n'));

            return '<a class="' + eClass + '" target="_blank" href="' + href + '">' + calendarName + ' Calendar</a>';
        },

        /**
         * ical
         *
         * @param event
         * @returns {*}
         */
        ical: function (event) {
            return this.ics(event, 'oui oui-ical', 'iCal');
        },

        /**
         * outlook
         *
         * @param event
         * @returns {*}
         */
        outlook: function (event) {
            return this.ics(event, 'oui oui-outlook', 'Outlook');
        }
    };

    /**
     * generateCalendars
     *
     * @param event
     * @returns {{google: *, yahoo: *, ical: *, outlook: *}}
     */
    var generateCalendars = function (event) {
        return {
            google: calendarGenerators.google(event),
            yahoo: calendarGenerators.yahoo(event),
            ical: calendarGenerators.ical(event),
            outlook: calendarGenerators.outlook(event)
        };
    };

    /**
     * Create CSS
     */
    var addCSS = function () {
        if (!document.getElementById('ouical-css')) {
            document.getElementsByTagName('head')[0].appendChild(generateCSS());
        }
    };

    /**
     * Generate Style
     *
     * @returns {HTMLStyleElement}
     */
    var generateCSS = function () {
        var styles = document.createElement('style');
        styles.id = 'ouical-css';
        styles.innerHTML = "#add-to-calendar-checkbox-label{cursor:pointer}.add-to-calendar-checkbox~.add-to-calendar-lists{display:none;}.add-to-calendar-checkbox:checked~.add-to-calendar-lists{display:block;}input[type=checkbox].add-to-calendar-checkbox{position:absolute;top:-9999px;left:-9999px}";
        return styles;
    };

    /**
     * Make sure we have the necessary event data, such as start time and event duration
     * @param params
     * @returns {boolean}
     */
    var validParams = function (params) {
        return params.data !== undefined && params.data.start !== undefined &&
               (params.data.end !== undefined || params.data.duration !== undefined);
    };

    /**
     * Generate Markup
     *
     * @param data
     * @param calendars
     * @param cal_class
     * @param calendarId
     * @returns {HTMLDivElement}
     */
    var generateMarkup = function (data, calendars, cal_class, calendarId) {
        var result = document.createElement('div');

        var label = 'Add to Calendar';
        if (data.label) {
            label = data.label;
        }

        result.innerHTML = '<label for="checkbox-for-' +
                           calendarId + '" class="add-to-calendar-checkbox">' + label + '</label>';
        result.innerHTML += '<input name="add-to-calendar-checkbox" class="add-to-calendar-checkbox" id="checkbox-for-' + calendarId + '" type="checkbox">';

        var lists = '';
        Object.keys(calendars).forEach(function (services) {
            lists += '<li>' + calendars[services] + '</li>';
        });

        result.innerHTML += '<div class="add-to-calendar-lists"><ul>' + lists + '</ul></div>';

        result.className = 'add-to-calendar';
        if (cal_class !== undefined) {
            result.className += (' ' + cal_class);
        }

        addCSS();

        result.id = calendarId;
        return result;
    };

    /**
     * getClass
     *
     * @param params
     */
    var getClass = function (params) {
        if (params.options && params.options.class) {
            return params.options.class;
        }
    };

    /**
     * getOrGenerateCalendarId
     *
     * @param params
     * @returns {number}
     */
    var getOrGenerateCalendarId = function (params) {
        return params.options && params.options.id ?
            params.options.id :
            Math.floor(Math.random() * 1000000); // Generate a 6-digit random ID
    };

    /**
     * createCalendar
     *
     * @param params
     * @returns {*}
     */
    exports.createCalendar = function (params) {
        if (!validParams(params)) {
            console.log('Event details missing.');
            return;
        }

        return generateMarkup(params.data, generateCalendars(params.data),
            getClass(params),
            getOrGenerateCalendarId(params));
    };

    window.addEventListener('load', function (ev) {
        document.body.addEventListener('click', function (e) {
            var checkbox = document.getElementById('checkbox-for-dlevents-cal');
            if (checkbox.checked) {
                checkbox.checked = !checkbox.checked;
            }
        });
        var addCalTrigger = document.querySelector('.add-to-calendar-checkbox');
        if (!addCalTrigger) {
            return;
        }
        addCalTrigger.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var checkbox = document.getElementById('checkbox-for-dlevents-cal');
            checkbox.checked = !checkbox.checked;
        });
    });

})(this);
