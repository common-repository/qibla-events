<?php

use QiblaEvents\Form\Factories\FieldFactory;

/**
 * Settings Reset Backup Fields
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

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Import/Export Settings Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the settings fields.
 */
return apply_filters('qibla_opt_inc_import_export_fields', array(
    /**
     * Export Options
     *
     * @since 1.0.0
     */
    'qibla_opt_export:submit' => $fieldFactory->table(array(
        'type'  => 'submit',
        'name'  => 'qibla_opt_export',
        'label' => esc_html_x('Export Plugin Options', 'settings', 'qibla-events'),
        'value' => esc_html_x('Export', 'settings', 'qibla-events'),
        'attrs' => array(
            'class' => array(
                'button',
                'button-primary',
            ),
        ),
    )),

    /**
     * Import Options
     *
     * @since 1.0.0
     */
    'qibla_opt_import:file'   => $fieldFactory->table(array(
        'type'              => 'file',
        'use_wp_media'      => true,
        'name'              => 'qibla_opt_import',
        'use_btn'           => true,
        'file_submit_label' => esc_html_x('Import', 'settins', 'qibla-events'),
        'label'             => esc_html_x('Import Plugin Options', 'settings', 'qibla-events'),
        'description'       => esc_html_x('Import data retrieved by a previous backup.', 'settings', 'qibla-events'),
        'value'             => esc_html_x('Import', 'settings', 'qibla-events'),
        'accept'            => 'application/json',
    )),

    /**
     * Import Options
     *
     * @since 1.0.0
     */
    'qibla_opt_reset:submit'  => $fieldFactory->table(array(
        'type'        => 'submit',
        'name'        => 'qibla_opt_reset',
        'id'          => 'qibla_opt_reset',
        'label'       => esc_html_x('Reset Plugin Options', 'settings', 'qibla-events'),
        'description' => esc_html_x(
            'This will reset all of the settings to their default values.',
            'settings',
            'qibla-events'
        ),
        'value'       => esc_html_x('Reset', 'settings', 'qibla-events'),
        'attrs'       => array(
            'class' => array(
                'button',
                'button-secondary',
            ),
        ),
    )),
));
