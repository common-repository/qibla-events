<?php
/**
 * ScriptLoader
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2018 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaEvents\Search;

use QiblaEvents\Search\Field\SearchFieldInterface;

/**
 * Class ScriptLoader
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ScriptLoader
{
    /**
     * Fields Collection
     *
     * @since 1.0.0
     *
     * @var array The list of the fields for which enqueue the scripts
     */
    private $fields;

    /**
     * ScriptLoader constructor
     *
     * @since 1.0.0
     *
     * @param array $fields The list of the fields for which enqueue the scripts
     */
    public function __construct(array $fields)
    {
        $this->fields = array_filter($fields, function ($field) {
            return $field instanceof SearchFieldInterface;
        });
    }

    /**
     * Throught The Fields
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function throughtFields()
    {
        foreach ((array)$this->fields as $field) {
            $this->enqueue($field);
        }
    }

    /**
     * Enqueue
     *
     * @since 1.0.0
     *
     * @param SearchFieldInterface $field The fields for which enqueue the scripts
     *
     * @return void
     */
    private function enqueue($field)
    {
        switch ($field->slug()) :
            case 'geocoded':
                // Enqueue the geocoded script.
                if (wp_script_is('dlsearch-geocoded', 'registered')) {
                    wp_enqueue_script('dlsearch-geocoded');
                }
                break;
            case 'search':
                if ($field->useAutocomplete()) {
                    // Enqueue the autocomplete.
                    if (wp_script_is('dlautocomplete', 'registered')) {
                        wp_enqueue_script('dlautocomplete');
                    }
                }
                break;
            case 'dates':
                // Script
                if (wp_script_is('appmap-ev-search-dates', 'registered')) {
                    wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
                    wp_enqueue_script('appmap-ev-search-dates', array('jquery-ui-datepicker'));
                }
                // Lang
                if (wp_script_is('datepicker-lang', 'registered')) {
                    wp_enqueue_script('datepicker-lang', array('jquery', 'jquery-ui-datepicker'));
                }
                break;
        endswitch;

        if (wp_script_is('dlsearch-utils', 'registered')) {
            wp_enqueue_script('dlsearch-utils');
        }
    }
}
