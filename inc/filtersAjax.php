<?php

use QiblaEvents\Autocomplete;
use QiblaEvents\LoginRegister\Login\RequestFormLoginAjax;
use QiblaEvents\LoginRegister\Login\LoginFormFacade;
use QiblaEvents\LoginRegister\Register\RegisterFormFacade;
use QiblaEvents\LoginRegister\Register\RequestFormRegisterAjax;
use QiblaEvents\LoginRegister\LostPassword\LostPasswordFormFacade;
use QiblaEvents\LoginRegister\LostPassword\RequestFormLostPasswordAjax;

/**
 * Ajax Filters
 *
 * @since     1.0.0
 * @author    Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright Copyright (c) 2018, Alfio Piccione
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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
 * Filter Ajax Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('qibla_filters_ajax', array(
    'ajax' => array(
        'action' => array(
            /**
             * Autocomplete
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaEvents\\Autocomplete\\AjaxRequest::handleRequestFilter',
                'priority' => 20,
            ),

            /**
             * Contact Form & Modal
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaEvents\\Front\\ContactForm\\RequestFormContactFormAjax::handleFilter',
                'priority' => 0,
            ),
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaEvents\\Front\\ContactForm\\Modal\\RequestModalContactFormAjax::handleFilter',
                'priority' => PHP_INT_MAX,
            ),

            /**
             * Listings Filter
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'wp_loaded',
                'callback' => 'QiblaEvents\\Filter\\Request\\RequestFilterAjax::handleFilter',
                'priority' => 20,
            ),
        ),
    ),
));
