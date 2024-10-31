<?php
/**
 * DateTime
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

namespace QiblaEvents\Functions;

/**
 * Set time stamp
 *
 * @since 1.0.0
 *
 * @param $unixtimestamp string The unix timestamp.
 *
 * @return $this
 */
function setTimestap($unixtimestamp = '')
{
    // Timezone_string is empty when the option is set to Manual Offset. So we use gmt_offset.
    $option = get_option('timezone_string') ? get_option('timezone_string') : get_option('gmt_offset');
    // Set to UTC in order to prevent issue if used with DateTimeZone constructor.
    $option = (in_array($option, array('', '0'), true) ? 'UTC' : $option);
    // And remember to add the symbol.
    if (is_numeric($option) && 0 < $option) {
        $option = '+' . $option;
    }

    $timeZone = new \DateTimeZone($option);
    $date     = new \DateTime();
    $date->setTimezone($timeZone);

    if ('' === $unixtimestamp) {
        $unixtimestamp = $date->getTimestamp();
    }

    return $date->setTimestamp($unixtimestamp);
}

/**
 * Set timestamp from time and date event
 *
 * @since 1.0.0
 *
 * @param $time     int     The time in timestamp format
 * @param $datetime string  The date in Y-m-d format
 *
 * @return $this
 */
function setDateTimeFromTimeAndDate($time, $datetime) {

    // Set timestamp for start time if isset or data start
    $date      = '' !== $time ? $time : strtotime($datetime);
    $timestamp = setTimestap(intval($date));

    // Explode data start and create array contains Y,m,d
    $data_list = '' !== $datetime ? explode('-', $datetime) : array();

    // Set date in $timestamp
    if (! empty($data_list) && '' !== $time) {
        list($y, $m, $d) = $data_list;
        $timestamp->setDate($y, $m, $d);
    } else {
        $timestamp->setTime(0, 0, 0);
    }

    return $timestamp;
}
