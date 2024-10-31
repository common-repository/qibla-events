<?php
/**
 * Scripts List to de-register
 *
 * @package Qibla
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

/**
 * De-register Scripts List
 *
 * @since 1.0.0
 *
 * @param array $array The list of the scripts and styles to de-register.
 */
return apply_filters('qibla_deregister_scripts_list', array(
    'scripts' => array(
        array(
            'context' => 'front',
            // De-register the select2. May other versions cannot work well with the one we are using.
            'handle'  => 'select2',
        ),
    ),
    'styles'  => array(
        array(
            'context' => 'front',
            'handle'  => 'contact-form-7',
        ),
        array(
            'context' => 'front',
            'handle'  => 'contact-form-7-rtl',
        ),
        array(
            'context' => 'front',
            'handle'  => 'jquery-ui-style',
        ),
        array(
            'context' => 'front',
            'handle'  => 'animate-css',
        ),
        array(
            'context' => 'front',
            'handle'  => 'select2',
        ),
    ),
));
