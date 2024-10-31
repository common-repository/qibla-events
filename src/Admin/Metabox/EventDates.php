<?php
/**
 * EventDates.php
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Metabox
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

namespace QiblaEvents\Admin\Metabox;

use QiblaEvents\Functions as F;
use QiblaEvents\Plugin;

/**
 * Class EventDates
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventDates extends AbstractMetaboxForm
{
    /**
     * Taxonomy
     *
     * @since 1.0.0
     */
    const EVENTS_DATES_TAXONOMY = 'dates';

    /**
     * Events Constructor
     *
     * @inheritdoc
     */
    public function __construct(array $args = array())
    {
        parent::__construct(wp_parse_args($args, array(
            'id'       => 'side_events_option',
            'title'    => esc_html__('Event Dates', 'qibla-events'),
            'callback' => array($this, 'callBack'),
            'screen'   => array('events'),
            'context'  => 'side',
            'priority' => 'high',
        )));

        parent::setFields(include Plugin::getPluginDirPath('/inc/metaboxFields/eventsOptionFields.php'));
    }

    /**
     * Filter Metabox Store
     *
     * @since 1.0.0
     *
     * @param string $name     The name of the meta. Aka the meta key.
     * @param mixed  $value    The value of the meta.
     * @param mixed  $post     The current post.
     * @param bool   $update   If the post has been updated or created.
     * @param mixed  $oldValue The old meta value.
     */
    public static function filterEventsStoreFilters($name, $value, $post, $update, $oldValue)
    {
        // Initialized.
        $startDate = '';
        $endDates  = '';

        if ('qibla_mb_event_dates_multidatespicker' === $name) {
            // Get all dates.
            $multiDates = F\getPostMeta('_qibla_mb_event_dates_multidatespicker') ?: null;

            if ($multiDates) {
                $multiDates = explode(',', $multiDates);

                $dates = array();
                foreach ($multiDates as $date) {
                    $date    = new \DateTime($date);
                    $dates[] = (string)$date->format('Y-m-d');
                }

                // Set the dates term.
                // Save the post meta as a term to be used for filtering and have a url.
                wp_set_object_terms($post->ID, $dates, self::EVENTS_DATES_TAXONOMY, false);

                $startDate = reset($dates);
                $endDates  = end($dates);
            }

            // Date for order.
            $eventTimeStar = F\getPostMeta('_qibla_mb_event_start_time_timepicker', '');
            $date          = F\setDateTimeFromTimeAndDate(intval($eventTimeStar), $startDate);
            $sortDate      = $date instanceof \DateTime ? $date->format('YmdHi') : '';
            update_post_meta($post->ID, '_qibla_mb_event_dates_start_for_orderby', $sortDate);
            // Start date post meta.
            update_post_meta($post->ID, '_qibla_mb_event_dates_multidatespicker_start', $startDate);
            // End date post meta.
            update_post_meta($post->ID, '_qibla_mb_event_dates_multidatespicker_end', $endDates);
        }
    }
}