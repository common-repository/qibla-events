<?php

/**
 * WordPress Backward Functions
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

if (! function_exists('wp_doing_ajax')) {
    /**
     * WP Doing Ajax
     *
     * Determines whether the current request is a WordPress Ajax request.
     *
     * @since WP4.7.0
     *
     * @return bool True if it's a WordPress Ajax request, false otherwise.
     */
    function wp_doing_ajax()
    {
        /**
         * Filters whether the current request is a WordPress Ajax request.
         *
         * @since 4.7.0
         *
         * @param bool $wp_doing_ajax Whether the current request is a WordPress Ajax request.
         */
        return apply_filters('wp_doing_ajax', defined('DOING_AJAX') && DOING_AJAX);
    }
}

if (! function_exists('get_theme_file_uri')) {
    /**
     * Retrieves the URL of a file in the theme.
     *
     * Searches in the stylesheet directory before the template directory so themes
     * which inherit from a parent theme can just override one file.
     *
     * @since WP4.7.0
     *
     * @param string $file Optional. File to search for in the stylesheet directory.
     *
     * @return string The URL of the file.
     */
    function get_theme_file_uri($file = '')
    {
        $file = ltrim($file, '/');

        if (empty($file)) {
            $url = get_stylesheet_directory_uri();
        } elseif (file_exists(get_stylesheet_directory() . '/' . $file)) {
            $url = get_stylesheet_directory_uri() . '/' . $file;
        } else {
            $url = get_template_directory_uri() . '/' . $file;
        }

        /**
         * Filters the URL to a file in the theme.
         *
         * @since 4.7.0
         *
         * @param string $url  The file URL.
         * @param string $file The requested file to search for.
         */
        return apply_filters('theme_file_uri', $url, $file);
    }
}

if (! function_exists('get_theme_file_path')) {
    /**
     * Retrieves the path of a file in the theme.
     *
     * Searches in the stylesheet directory before the template directory so themes
     * which inherit from a parent theme can just override one file.
     *
     * @since WP4.7.0
     *
     * @param string $file Optional. File to search for in the stylesheet directory.
     *
     * @return string The path of the file.
     */
    function get_theme_file_path($file = '')
    {
        $file = ltrim($file, '/');

        if (empty($file)) {
            $path = get_stylesheet_directory();
        } elseif (file_exists(get_stylesheet_directory() . '/' . $file)) {
            $path = get_stylesheet_directory() . '/' . $file;
        } else {
            $path = get_template_directory() . '/' . $file;
        }

        /**
         * Filters the path to a file in the theme.
         *
         * @since 4.7.0
         *
         * @param string $path The file path.
         * @param string $file The requested file to search for.
         */
        return apply_filters('theme_file_path', $path, $file);
    }
}

if (! function_exists('DLshortcodeUnautop')) {
    /**
     * Fix unautop issue #34722
     *
     * @link  http://www.wpexplorer.com/clean-up-wordpress-shortcode-formatting/
     * @link  https://core.trac.wordpress.org/ticket/34722
     *
     * @since 1.0.0
     *
     * @param $content
     *
     * @return string
     */
    function DLshortcodeUnautop($content)
    {
        $array   = array(
            '<p>['    => '[',
            ']</p>'   => ']',
            ']<br />' => ']',
        );
        $content = strtr($content, $array);

        return $content;
    }
}
