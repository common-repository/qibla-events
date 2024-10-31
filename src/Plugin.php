<?php
namespace QiblaEvents;

/**
 * Class Plugin
 *
 * @since      1.0.0
 * @package    QiblaEvents
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

/**
 * Class Plugin
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Plugin
{
    /**
     * Plugin Version
     *
     * @since  1.0.0
     *
     * @var string The plugin current version
     */
    const PLUGIN_VERSION = '1.0.0';

    /**
     * Sanitize path
     *
     * @since 1.0.0
     *
     * @param string $path The path to sanitize
     *
     * @return string The sanitized path.
     */
    private static function sanitizePath($path)
    {
        while (false !== strpos($path, '..')) {
            $path = str_replace('..', '', $path);
        }

        $path = ('/' !== $path) ? $path : '';

        return $path;
    }

    /**
     * Get Plugin Dir Path
     *
     * @since  1.0.0
     *
     * @param string $path The path to append to the plugin dir path. Optional. Default to '/'.
     *
     * @return string The plugin dir path
     */
    public static function getPluginDirPath($path = '/')
    {
        $path = untrailingslashit(plugin_dir_path(__DIR__)) . '/' . trim($path, '/');
        $path = realpath($path);

        return self::sanitizePath($path);
    }

    /**
     * Get Plugin Dir Url
     *
     * @since  1.0.0
     *
     * @param string $path The path to append to the plugin dir url. Optional. Default to '/'.
     *
     * @return string The plugin dir url
     */
    public static function getPluginDirUrl($path = '/')
    {
        $path = untrailingslashit(plugin_dir_url(__DIR__)) . '/' . trim($path, '/');

        return self::sanitizePath($path);
    }
}
