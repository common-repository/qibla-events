<?php
/**
 * eventsOptionFields
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

use QiblaEvents\Functions as F;
use QiblaEvents\Utils\TimeZone;
use QiblaEvents\Form\Factories\FieldFactory;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();
$timeZone     = new TimeZone();
$date         = new DateTime();
$timeZone     = new DateTimeZone($timeZone->getTimeZone()->getName());
$date->setTimezone($timeZone);

/**
 * Filter Event Option Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the extra wysiwyg meta-box fields.
 */
return apply_filters('qibla_mb_inc_event_option_fields', array(
    /**
     * Event Date In
     *
     * @since 1.0.0
     */
    'qibla_mb_event_option_dates:multidates'    => $fieldFactory->table(array(
        'type'        => 'multi_dates',
        'name'        => 'qibla_mb_event_dates_multidatespicker',
        'label'       => '',
        'description' => '',
        'attrs'       => array(
            'required'    => 'required',
            'value'       => '' !== F\getPostMeta('_qibla_mb_event_dates_multidatespicker', '') ?
                sanitize_text_field(F\getPostMeta('_qibla_mb_event_dates_multidatespicker', '')) : array(),
            'data-format' => 'yy-mm-dd',
        ),
    )),

    /**
     * Event Start Time
     *
     * @since 1.0.0
     */
    'qibla_mb_event_option_start_time:time' => $fieldFactory->table(array(
        'type'        => 'time',
        'name'        => 'qibla_mb_event_start_time_timepicker',
        'label'       => esc_html__('Start Time', 'qibla-events'),
        'description' => '',
        'attrs'       => array(
            'value'       => '' !== F\getPostMeta('_qibla_mb_event_start_time_timepicker', '') ?
                $date->setTimestamp(F\getPostMeta('_qibla_mb_event_start_time_timepicker'))->format('H:i') : '',
        ),
    )),

    /**
     * Event End Time
     *
     * @since 1.0.0
     */
    'qibla_mb_event_option_end_time:time'   => $fieldFactory->table(array(
        'type'        => 'time',
        'name'        => 'qibla_mb_event_end_time_timepicker',
        'label'       => esc_html__('End Time', 'qibla-events'),
        'description' => '',
        'attrs'       => array(
            'value'       => '' !== F\getPostMeta('_qibla_mb_event_end_time_timepicker', '') ?
                $date->setTimestamp(F\getPostMeta('_qibla_mb_event_end_time_timepicker'))->format('H:i') : '',
        ),
    )),
));