<?php
/**
 * Class Settings Store
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

namespace QiblaEvents\Admin\Settings;

use QiblaEvents\Functions as F;

/**
 * Class Store
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Store
{
    /**
     * Options Prefix
     *
     * @since  1.0.0
     *
     * @var string The prefix of the options name
     */
    private $optionsPrefix;

    /**
     * Options Name
     *
     * @since  1.0.0
     *
     * @var string The name of the options group to store
     */
    private $optionsName;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param string $optionsName   The name of the options.
     * @param string $optionsPrefix The prefix for the options.
     */
    public function __construct($optionsName, $optionsPrefix)
    {
        $this->optionsPrefix = $optionsPrefix;
        $this->optionsName   = strtolower($optionsName);
    }

    /**
     * Clean the Options Keys
     *
     * Clean the option key string to don't add the option name as keys for value, instead keep only the
     * single option name.
     *
     * @param array $options The array of the options to clean.
     *
     * @return array The cleaned array
     */
    private function cleanOptionsKeys(array $options)
    {
        $opt = array();
        foreach ($options as $key => $value) {
            $key       = trim(str_replace("{$this->optionsPrefix}{$this->optionsName}", '', $key), '-');
            $opt[$key] = $value;
        }

        return $opt;
    }

    /**
     * Store Options
     *
     * @since  1.0.0
     *
     * @param array $data The data to store in database.
     *
     * @return bool True on success false on failure.
     */
    public function store(array $data)
    {
        if (! current_user_can('edit_theme_options')) {
            wp_die('Cheatin&#8217; Uh?');
        }

        if (! $this->optionsName) {
            return false;
        }

        // Clean the valid keys.
        $optCleaned = $this->cleanOptionsKeys($data);

        // Store the options.
        $updated = update_option($this->optionsPrefix . $this->optionsName, $optCleaned, true);

        return $updated;
    }
}
