<?php
/**
 * Default Options
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

use QiblaEvents\ListingsContext\Types;

// Get Types.
$types = new Types();

$defaultOptions = array(
    // Listings.
    'general'    => array(
        'disable_css' => 'off',
    ),
    // Listings.
    'events'   => array(
        'archive_show_map'       => 'on',
        'posts_per_page'         => 10,
        'order_by_featured'      => 'on',
        'disable_reviews'        => 'off',
        'disable_map'            => 'off',
    ),

    // Search.
    'search'     => array(
        'placeholder'          => esc_html_x('What are you looking for?', 'default-options', 'qibla-events'),
        'geocoded_placeholder' => esc_html_x('Where about?', 'default-options', 'qibla-events'),
        'submit_label'         => esc_html_x('Search', 'default-options', 'qibla-events'),
        'submit_icon'          => 'Lineawesome::la-search',
    ),

    // Google Map.
    'google_map' => array(
        'zoom' => 15,
    ),
);

// Default archive description.
foreach ($types->types() as $type) {
    $defaultOptions['events']["{$type}_archive_description"] = '';
}

return $defaultOptions;
