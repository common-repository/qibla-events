<?php
/**
 * Front Functions Sidebar
 *
 * @todo    Move into a class.
 *
 * @since   1.0.0
 * @package QiblaEvents\Front
 *
 * Copyright (C) 2018 Alfio Piccione
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
 * Get Sidebar Class
 *
 * @since 1.0.0
 *
 * @uses  getScopeClass()
 *
 * @param string $block    The custom block scope.
 * @param string $modifier The block modifier key.
 * @param string $name     The name of the sidebar.
 *
 * @return string          The sidebar class value
 */
function getSidebarClass($block, $modifier = '', $name = '')
{
    // Current sidebar name.
    $block = $scope = getScopeClass($block);

    /**
     * Filter Modifier
     *
     * @since 1.0.0
     *
     * @param string $modifier The block modifier key.
     * @param string $block    The custom block scope.
     * @param string $name     The name of the sidebar.
     */
    $modifier = apply_filters('qibla_sidebar_class_modifier', $modifier, $block, $name);

    if ($name) {
        /**
         * Filter Modifier By Name
         *
         * @since 1.0.0
         *
         * @param string $modifier The block modifier key.
         * @param string $block    The custom block scope.
         */
        $modifier = apply_filters("qibla_sidebar_{$name}_class_modifier", $modifier, $block);
    }

    if ($modifier) {
        // Current scope modifier.
        $scope = $block . ' ' . $block . '--' . $modifier;
    }

    /**
     * Filter Sidebar Class Scope
     *
     * @since 1.0.0
     *
     * @param string $scope The scope class.
     * @param string $block The block class name.
     * @param string $name  The name of the sidebar.
     */
    $scope = apply_filters('qibla_sidebar_scope_class', $scope, $block, $name);

    if ($name) {
        /**
         * Filter Sidebar Class Scope By Name
         *
         * @since 1.0.0
         *
         * @param string $scope The scope class.
         * @param string $block The block class name.
         */
        $scope = apply_filters("qibla_sidebar_{$name}_scope_class", $scope, $block);
    }

    return trim($scope, ' ');
}

/**
 * Sidebar Class
 *
 * @since 1.0.0
 *
 * @uses  getSidebarClass()
 *
 * @param string $block    The custom block scope.
 * @param string $modifier The block modifier key.
 * @param string $name     The name of the sidebar.
 *
 * @return void
 */
function sidebarClass($block, $modifier = '', $name = '')
{
    echo 'class="' . getSidebarClass($block, $modifier, $name) . '"';
}

/**
 * Sidebar Scope ID
 *
 * @since 1.0.0
 *
 * @uses  getSidebarClass()
 *
 * @param string $block    The custom block scope.
 * @param string $element  The element within the scope.
 * @param string $modifier The block modifier key.
 *
 * @return void
 */
function sidebarID($block = '', $element = '', $modifier = '')
{
    echo 'id="' . getSidebarClass($block, $element, $modifier) . '"';
}

