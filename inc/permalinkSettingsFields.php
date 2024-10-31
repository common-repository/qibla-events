<?php
/**
 * Permalink Settings Fields
 *
 * @author    Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright Copyright (c) 2018, Alfio Piccione
 * @license   GNU General Public License, version 2
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

use QiblaEvents\Functions as F;

// Retrieve the permalink options.
$permalinkOptions = F\getOption(\QiblaEvents\Admin\PermalinkSettings::OPTION_NAME);

/**
 * Permalink Settings Fields
 *
 * @since 1.0.0
 *
 * @param array                                   $permalinks       The permalink fields.
 * @param \QiblaEvents\Admin\PermalinkSettings $this             The instance of the class.
 * @param array                                   $permalinkOptions The options for the permalinks.
 */
return apply_filters('qibla_events_permalinks_settings_fields', array(
    'permalink_listings_cpt'           => array(
        'listing_permalink',
        esc_html_x('Listings cpt base', 'settings-permalink', 'qibla-events'),
        array($this, 'fieldFactory'),
        'permalink',
        \QiblaEvents\Admin\PermalinkSettings::SECTION_NAME,
        array(
            // The key must match the array item key.
            'key'       => 'permalink_listings_cpt',
            'label_for' => 'permalink_listings_cpt',
        ),
        'sanitizeCb' => 'sanitize_key',
        'type'       => array(
            'type'  => 'text',
            'name'  => 'permalink_listings_cpt',
            'attrs' => array(
                'value'       => isset($permalinkOptions['permalink_listings_cpt']) ? $permalinkOptions['permalink_listings_cpt'] : '',
                'class'       => array('regular-text', 'code'),
                'placeholder' => 'events',
            ),
        ),
    ),
    'permalink_tags_tax'          => array(
        'tags_permalink',
        esc_html_x('Amenities tax base', 'settings-permalink', 'qibla-events'),
        array($this, 'fieldFactory'),
        'permalink',
        \QiblaEvents\Admin\PermalinkSettings::SECTION_NAME,
        array(
            // The key must match the array item key.
            'key'       => 'permalink_tags_tax',
            'label_for' => 'permalink_tags_tax',
        ),
        'sanitizeCb' => 'sanitize_key',
        'type'       => array(
            'type'  => 'text',
            'name'  => 'permalink_tags_tax',
            'attrs' => array(
                'value'       => isset($permalinkOptions['permalink_tags_tax']) ? $permalinkOptions['permalink_tags_tax'] : '',
                'class'       => array('regular-text', 'code'),
                'placeholder' => 'tags',
            ),
        ),
    ),
    'permalink_event_categories_tax' => array(
        'event_categories_permalink',
        esc_html_x('Listings Categories tax base', 'settings-permalink', 'qibla-events'),
        array($this, 'fieldFactory'),
        'permalink',
        \QiblaEvents\Admin\PermalinkSettings::SECTION_NAME,
        array(
            // The key must match the array item key.
            'key'       => 'permalink_event_categories_tax',
            'label_for' => 'permalink_event_categories_tax',
        ),
        'sanitizeCb' => 'sanitize_key',
        'type'       => array(
            'type'  => 'text',
            'name'  => 'permalink_event_categories_tax',
            'attrs' => array(
                'value'       => isset($permalinkOptions['permalink_event_categories_tax']) ? $permalinkOptions['permalink_event_categories_tax'] : '',
                'class'       => array('regular-text', 'code'),
                'placeholder' => 'event-categories',
            ),
        ),
    ),
    'permalink_locations_tax'          => array(
        'listing_locations_permalink',
        esc_html_x('Locations tax base', 'settings-permalink', 'qibla-events'),
        array($this, 'fieldFactory'),
        'permalink',
        \QiblaEvents\Admin\PermalinkSettings::SECTION_NAME,
        array(
            // The key must match the array item key.
            'key'       => 'permalink_locations_tax',
            'label_for' => 'permalink_locations_tax',
        ),
        'sanitizeCb' => 'sanitize_key',
        'type'       => array(
            'type'  => 'text',
            'name'  => 'permalink_locations_tax',
            'attrs' => array(
                'value'       => isset($permalinkOptions['permalink_locations_tax']) ? $permalinkOptions['permalink_locations_tax'] : '',
                'class'       => array('regular-text', 'code'),
                'placeholder' => 'locations',
            ),
        ),
    ),
    'permalink_dates_tax'          => array(
        'dates_permalink',
        esc_html_x('Dates tax base', 'settings-permalink', 'qibla-events'),
        array($this, 'fieldFactory'),
        'permalink',
        \QiblaEvents\Admin\PermalinkSettings::SECTION_NAME,
        array(
            // The key must match the array item key.
            'key'       => 'permalink_dates_tax',
            'label_for' => 'permalink_dates_tax',
        ),
        'sanitizeCb' => 'sanitize_key',
        'type'       => array(
            'type'  => 'text',
            'name'  => 'permalink_dates_tax',
            'attrs' => array(
                'value'       => isset($permalinkOptions['permalink_dates_tax']) ? $permalinkOptions['permalink_dates_tax'] : '',
                'class'       => array('regular-text', 'code'),
                'placeholder' => 'dates',
            ),
        ),
    ),
), $this, $permalinkOptions);
