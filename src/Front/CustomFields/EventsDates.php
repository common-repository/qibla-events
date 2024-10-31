<?php
/**
 * EventsDates.php
 *
 * @since      1.0.0
 * @package    QiblaEvents\Front\CustomFields
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

namespace QiblaEvents\Front\CustomFields;

use QiblaEvents\Functions as F;
use QiblaEvents\TemplateEngine\TemplateInterface;
use QiblaEvents\Utils\TimeZone;

/**
 * Class EventsDates
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventsDates extends AbstractMeta implements TemplateInterface
{

    /**
     * Initialize Object
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        // Build the meta-keys array.
        $this->meta = array(
            'dates'      => "_qibla_{$this->mbKey}_event_dates_multidatespicker",
            'data_start' => "_qibla_{$this->mbKey}_event_dates_multidatespicker_start",
            'date_end'   => "_qibla_{$this->mbKey}_event_dates_multidatespicker_end",
            'time_start' => "_qibla_{$this->mbKey}_event_start_time_timepicker",
            'time_end'   => "_qibla_{$this->mbKey}_event_end_time_timepicker",
        );
    }

    /**
     * Get Data
     *
     * @inheritDoc
     */
    public function getData()
    {
        // Set Time Zone
        $timeZone = new TimeZone();
        $timeZone = new \DateTimeZone($timeZone->getTimeZone()->getName());
        $time     = new \DateTime();
        $time->setTimezone($timeZone);

        // Initialize data class.
        $data = new \stdClass();

        // Do nothing if the context is not a singular post type.
        if (! is_singular('events')) {
            return $data;
        }

        $multiDates = $this->getMeta('dates', '');
        $dateStart  = $this->getMeta('data_start', '');
        $dateEnd    = $this->getMeta('date_end', '');
        $timeStart  = $this->getMeta('time_start', '');
        $timeEnd    = $this->getMeta('time_end', '');

        if ($multiDates) {
            $multiDates = explode(',', $multiDates);

            // Initialized one date.
            $data->oneDate = false;
            $data->eventsDateTimeIn  = '';
            $data->eventsDateTimeOut = '';

            // First Dates.
            $firstDate = new \DateTime(reset($multiDates));
            // Set Date in.
            $data->eventsDateIn = date_i18n('j F', intval($firstDate->getTimestamp())) ?: '';

            if (0 < count($multiDates)) {
                // Last Dates.
                $lastDate = new \DateTime(end($multiDates));
                // Set Date Out.
                $data->eventsDateOut = date_i18n('j F', intval($lastDate->getTimestamp()));
            } else {
                $lastDate = $firstDate;
            }

            // Default time start/end.
            $data->eventsDateTimeIn  = $firstDate->format('c');
            $data->eventsDateTimeOut = $lastDate->format('c');

            if ($timeStart) {
                $dateTime               = F\setDateTimeFromTimeAndDate($timeStart, $dateStart);
                $data->eventsDateTimeIn = $dateTime->format('c');
                $time->setTimestamp($timeStart);
                $data->timeStart = $time->format('H:i');
            }

            if ($timeEnd) {
                $dateTime                = F\setDateTimeFromTimeAndDate($timeEnd, $dateEnd);
                $data->eventsDateTimeOut = $dateTime->format('c');
                $time->setTimestamp($timeEnd);
                $data->timeEnd = $time->format('H:i');
            }

            if (intval($firstDate->getTimestamp()) === intval($lastDate->getTimestamp())) {
                $data->oneDate = true;
            }
        }

        return $data;
    }

    /**
     * Template
     *
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $this->loadTemplate('qibla_mb_events_dates', $data, '/views/events/eventsDate.php');
    }

    /**
     * Related Posts Filter
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function eventsDatesFilter()
    {
        $instance = new self;

        $instance->init();
        $instance->tmpl($instance->getData());
    }

}