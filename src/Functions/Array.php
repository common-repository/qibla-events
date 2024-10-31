<?php
/**
 * Array Functions
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
 * Is Associative Array
 *
 * @since  1.0.0
 *
 * @param  array $array The array to know if is associative or not.
 *
 * @return bool         True if array is associative, false otherwise.
 */
function isArrayAssoc(array $array)
{
    return array_keys($array) !== range(0, count($array) - 1);
}

/**
 * Implode Assoc
 *
 * Implode an associative array
 *
 * @since 1.0.0
 *
 * @param string $glue    The string to use for separate the elements value.
 * @param string $assGlue The string to use to separate key and value.
 * @param array  $arr     The array to implode.
 *
 * @return string|bool The array imploded. False if $arr is not an array. False if $arr is not associative.
 */
function implodeAssoc($glue, $assGlue, $arr)
{
    if (wp_is_numeric_array($arr)) {
        return false;
    }

    // The string.
    $string = '';

    foreach ($arr as $k => $v) {
        if (! $v) {
            continue;
        }

        $string .= $k . $assGlue . $v . $glue;
    }

    // Remove the latest glue string.
    $string = rtrim($string, $glue);

    return $string;
}

/**
 * Insert an element into array in a specific position
 *
 * @since  1.0.0
 *
 * @param  array $needle    The array to insert in.
 * @param  array $haystack  The array to insert on.
 * @param  mixed $pos       The key or index where the array should be inserted.
 * @param  bool  $preserve  If the original value and positions should be preserved.
 * @param  bool  $recursive To merge recursively or not the result array.
 *
 * @return array            The new merged array
 */
function arrayInsertInPos($needle, array &$haystack, $pos, $preserve = false, $recursive = false)
{
    $keys = array_filter(array_intersect(array_keys($needle), array_keys($haystack)), 'is_string');

    if (is_array($pos)) {
        list($key, $before) = $pos;
        $before = (isset($before) && true === $before) ? true : false;
        $pos    = array_search($key, array_keys($haystack), true);
        $pos    = $before ? $pos : $pos + 1;
    }

    if ($keys) {
        if ($preserve) {
            $arr =& $needle;
        } else {
            $arr =& $haystack;
        }

        foreach ($keys as $k => $v) {
            unset($arr[$v]);
        }
    }

    $start    = array_splice($haystack, 0, (int)$pos);
    $func     = $recursive ? 'array_merge_recursive' : 'array_merge';
    $haystack = call_user_func_array($func, array($start, (array)$needle, $haystack));

    return $haystack;
}
