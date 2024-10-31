<?php
namespace QiblaEvents\Form\Traits;

use QiblaEvents\Functions as F;

/**
 * Trait Arguments
 *
 * @package QiblaEvents\Traits
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
 * Trait Fields
 *
 * @since   1.0.0
 * @package QiblaEvents\Form\Traits
 */
abstract class FieldsTrait extends ArgumentsTrait
{
    /**
     * Execute the Before or After callback
     *
     * Don't trust to FALSE value when add callback to 'display' fields, because if the callback is not added
     * to the field the value of 'display' will be NULL and this method will return an empty string.
     * So by default every field must be showed unless the callback return FALSE explicitly.
     *
     * @since  1.0.0
     *
     * @param callable $func A callback function to call.
     *
     * @return mixed The returned value of the callback or an empty string if the callback is not callable.
     */
    protected function argCb($func)
    {
        if (! is_callable($this->getArg($func))) {
            return '';
        }

        return call_user_func($this->getArg($func), $this);
    }

    /**
     * Get Description
     *
     * @since  1.0.0
     *
     * @return string The field description markup
     */
    protected function getDescription()
    {
        if (! $this->getArg('description')) {
            return '';
        }

        // Scope Class.
        $scope = $this->getArg('container_class');
        $scope = ! empty($scope) ? $scope[0] : '';

        // The field description.
        return sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            tag_escape($this->getArg('desc_container')),
            sanitize_html_class($scope) . '__description',
            F\ksesPost($this->getArg('description'))
        );
    }
}
