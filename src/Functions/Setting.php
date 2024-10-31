<?php
/**
 * Settings Functions
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

use QiblaEvents\Debug;
use QiblaEvents\Plugin;
use QiblaEvents\Utils\DateTime;

/**
 * Get Default Options
 *
 * Retrieve the default theme options. If single is passed but option doesn't exists, an emptry string will
 * returned.
 *
 * @param string $group  The group name of the options.
 * @param string $single The single name of the option to retrieve.
 *
 * @return mixed|string The option
 */
function getDefaultOptions($group, $single = '')
{
    $options = wp_cache_get('default_options', 'options');

    if (! $options) {
        $options = include Plugin::getPluginDirPath('/inc/defaultOptions.php');

        wp_cache_add('default_options', $options, 'options');
    }

    $group  = sanitize_key($group);
    $single = sanitize_key($single);

    // Return the single if requested.
    if ($single) {
        if (isset($options[$group][$single])) {
            return $options[$group][$single];
        } else {
            return '';
        }
    }

    return $options[$group];
}

/**
 * Get Theme Option
 *
 * Retrieve the option of the theme and in case is requested the default value.
 * This able us to get the default value within the fw and theme with less code.
 *
 * @uses getDefaultOptions()
 * @uses getOption()
 *
 * @param string $option  The option name for which retrieve the value.
 * @param string $single  The key of the single option to retrieve. Optional.
 * @param bool   $default If the default value of the option should be returned if the option doesn't exists.
 *
 * @return mixed Whatever the getOption return
 */
function getPluginOption($option, $single = '', $default = false)
{
    if ($default) {
        $default = getDefaultOptions($option, $single);
    }

    $opt = getOption($option, $single, $default, 'qibla_opt-');

    /**
     * Filter Theme Options
     *
     * @since 1.0.0
     *
     * @param mixed  $opt     The option.
     * @param string $option  The option name for which retrieve the value.
     * @param string $single  The key of the single option to retrieve. Optional.
     * @param bool   $default If the default value of the option should be returned if the option doesn't exists.
     */
    $opt = apply_filters('qibla_theme_option', $opt, $option, $single, $default);

    return $opt;
}

/**
 * Get Option
 *
 * Example of a option key.
 *
 *      the_prefix-option_name-single_option
 *
 * The components are separated by '-' score and key names by '_' underscore.
 *
 * @uses get_option()
 * @uses sanitize_key()
 *
 * @param string $option  The name of the option to retrieve.
 * @param string $single  The key of the single option to retrieve. Optional.
 * @param string $default The default value to return in case the option doesn't exists.
 * @param string $prefix  The prefix of the $option key.
 *
 * @return mixed The value of the option
 */
function getOption($option, $single = '', $default = '', $prefix = '')
{
    $optionName = $prefix . $option;
    $option     = get_option(sanitize_key($optionName), $default);

    if ('' !== $single) {
        // Use the default value only if the options doesn't exists.
        // In some circumstances we may want to store an empty value that can be evaluated as false.
        $option = (is_array($option) && isset($option[$single]) ? $option[$single] : $default);
    }

    return wp_unslash($option);
}

/**
 * Get Date Time Format
 *
 * Retrieve the date time format from WordPress options
 *
 * @since 1.0.0
 *
 * @param string $sep The separator character between date format and time. Optional. Default to space.
 *
 * @return string The date and time format
 */
function getDateTimeFormat($sep = '')
{
    // Default separator to space.
    $sep = $sep ?: ' ';

    return get_option('date_format') . $sep . get_option('time_format');
}

/**
 * Safety retrieve the post meta Date instance
 *
 * @since  1.0.0
 *
 * @param mixed $meta The timestamp value from which generate the DateTime instance.
 *
 * @return \DateTime|string on success, or empty string in case the meta is evaluable as false or the
 *                          timeStampToDateTime thrown an Exception.
 */
function getDateByMeta($meta)
{
    if (! $meta) {
        return '';
    }

    try {
        return DateTime::timeStampToDateTime($meta)->format(getDateTimeFormat());
    } catch (\Exception $e) {
        $debugInstance = new Debug\Exception($e);
        'dev' === QB_ENV && $debugInstance->display();

        return '';
    }
}
