<?php
/**
 * Formatting Functions
 *
 * @package QiblaEvents\Functions
 * @author     Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaEvents\Functions;

/**
 * Sanitize Html Class
 *
 * A wrapper function that enable to pass an array or a string of classes separated by spaces.
 *
 * @since 1.0.0
 *
 * @uses  sanitize_html_class() To sanitize the html class string
 *
 * @param mixed  $class    The classes as string or array.
 * @param string $fallback The value to return if the sanitization ends up as an empty string. Optional.
 * @param string $glue     The glue to use to explode the string list of classes. Optional default to space.
 *
 * @return string The sanitize class or classes list
 */
function sanitizeHtmlClass($class, $fallback = '', $glue = '')
{
    // Default to space.
    $glue = $glue ?: ' ';

    // If is a list and is represented as a string.
    if (is_string($class) && false !== strpos($class, $glue)) {
        $class = explode($glue, $class);
    }

    if (is_array($class)) {
        $newClass = $class;
        $class    = '';
        foreach ($newClass as $c) {
            $class .= ' ' . sanitizeHtmlClass($c, $fallback);
        }
    } else {
        $class = sanitize_html_class($class, $fallback);
    }

    return trim($class, ' ');
}

/**
 * Convert String To Boolean
 *
 * This function is the same of wc_string_to_bool.
 *
 * @since 1.0.0
 *
 * @param string|int $value The string to convert to boolean. 'yes', 1, 'true', '1' are converted to true.
 *
 * @return bool True or false depending on the passed value.
 */
function stringToBool($value)
{
    return is_bool($value) ?
        $value :
        ('yes' === $value || 1 === $value || 'true' === $value || '1' === $value || 'on' === $value);
}

/**
 * Convert Boolean to String
 *
 * This function is the same of wc_bool_to_string
 *
 * @since 1.0.0
 *
 * @param bool $bool The bool value to convert
 *
 * @return string The converted value. 'yes' or 'no'.
 */
function boolToString($bool)
{
    if (! is_bool($bool)) {
        $bool = stringToBool($bool);
    }

    return true === $bool ? 'yes' : 'no';
}

/**
 * SanitizeMetaKey
 *
 * @since 1.0.0
 *
 * @throw \InvalidArgumentException If key is not a valid string.
 *
 * @param string $key The key to sanitize.
 *
 * @return string The sanitized string. Empty string if is not possible to sanitize it.
 */
function sanitizeMetaKey($key)
{
    if (! is_string($key)) {
        throw new \InvalidArgumentException('Key must be a string.');
    }

    // Return if not valid value.
    if (! $key) {
        return '';
    }

    // Put in lower case.
    $key = strtolower($key);

    // Clean the data.
    $key = preg_replace('/[^a-z0-9\_]/', '_', $key);

    // We don't allow this kind of meta key.
    if ('_' === $key) {
        return '';
    }

    // Normalize underscores.
    $key = preg_replace('/\_{2,}/', '_', $key);

    // Insert the '_' underscore to the begin of the string.
    $key = '_' . trim($key, '_');

    // Return empty string in case we got only underscores.
    if ('_' === $key) {
        return '';
    }

    return $key;
}

/**
 * Format Attributes to string version
 *
 * @since  1.0.0
 *
 * @param array $attrs The attributes key => value pair to convert to string.
 *
 * @return string The string key="value" pair extracted from the attributes array
 */
function attrs(array $attrs = array())
{
    $output = '';

    if (! empty($attrs) && is_array($attrs)) {
        foreach ($attrs as $key => $val) {
            // Implode if the value of the attribute is an array.
            $val = is_array($val) ? implode(' ', $val) : $val;
            // Build the attribute string.
            $output .= ' ' . sanitize_key($key) . '="' . esc_attr($val) . '"';
        }
    }

    return $output;
}
