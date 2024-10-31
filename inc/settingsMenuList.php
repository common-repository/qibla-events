<?php
/**
 * Settings Menu List
 *
 * @author  Alfio Piccione <alfio.piccione@gmail.com>
 *
 * @license GPL 2
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

/**
 * Filter Menu Settings list
 *
 * @since 1.0.0
 *
 * @param array $array The list of the menu settings
 */
return apply_filters('qibla_settings_menu_list', array(
    array(
        'page_title' => esc_html__('General', 'qibla-events'),
        'menu_title' => esc_html__('General', 'qibla-events'),
        'menu_slug'  => 'general',
        'icon_class' => array('la', 'la-cog'),
    ),
    array(
        'page_title' => esc_html__('Events', 'qibla-events'),
        'menu_title' => esc_html__('Events', 'qibla-events'),
        'menu_slug'  => 'events',
        'icon_class' => array('la', 'la-map-marker'),
    ),
    array(
        'page_title' => esc_html__('Google Map', 'qibla-events'),
        'menu_title' => esc_html__('Google Map', 'qibla-events'),
        'menu_slug'  => 'google-map',
        'icon_class' => array('la', 'la-map'),
    ),
    array(
        'page_title' => esc_html__('Search', 'qibla-events'),
        'menu_title' => esc_html__('Search', 'qibla-events'),
        'menu_slug'  => 'search',
        'icon_class' => array('la', 'la-search'),
    ),
    array(
        'page_title' => esc_html__('Import / Export', 'qibla-events'),
        'menu_title' => esc_html__('Import / Export', 'qibla-events'),
        'menu_slug'  => 'import-export',
        'icon_class' => array('la', 'la-wrench'),
    ),
));
