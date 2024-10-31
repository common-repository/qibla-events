<?php
/**
 * Settings General Fields
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

use QiblaEvents\Functions as F;
use QiblaEvents\Form\Factories\FieldFactory;
use QiblaEvents\ListingsContext\Types;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

// Get Types.
$types = new Types();

/**
 * Filter Listings Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
$fields = array(
    /**
     * Disable Css
     *
     * @since 1.0.0
     */
    'qibla_opt-general-disable_css:checkbox'   => $fieldFactory->table(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_opt-general-disable_css',
        'value'       => F\getPluginOption('general', 'disable_css', true),
        'label'       => esc_html_x('Naked html', 'settings', 'qibla-events'),
        'description' => esc_html_x(
            'Use the plugin without any additional css',
            'settings',
            'qibla-events'
        ),
    )),

);

return apply_filters('qibla_opt_inc_general_fields', $fields);

