<?php
namespace QiblaEvents\Shortcode;

/**
 * Interface Short-codes
 *
 * @since      1.0.0
 * @package    QiblaEvents\Shortcode
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
 * Interface Shortcodes
 *
 * @since   1.0.0
 * @package QiblaEvents\Shortcode
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
interface ShortcodeInterface
{
    /**
     * Get The tag name
     *
     * Retrieve the tag name for the short-code.
     *
     * @since  1.0.0
     *
     * @return string
     */
    public function getTag();

    /**
     * Get Defaults
     *
     * Retrieve the defaults value for the short-code.
     *
     * @since  1.0.0
     *
     * @return array The default attributes for the shortcode
     */
    public function getDefaults();

    /**
     * Parse Attributes Arguments
     *
     * @since  1.0.0
     *
     * @param array $atts The short-code's attributes
     *
     * @return array The parsed arguments
     */
    public function parseAttrsArgs($atts);

    /**
     * Build Model
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code attributes.
     * @param string $content The content within the short-code.
     *
     * @return \stdClass The data instance or null otherwise.
     */
    public function buildData(array $atts, $content = '');

    /**
     * Callback
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code's attributes.
     * @param string $content The content within the short-code.
     *
     * @return string The short-code markup
     */
    public function callback($atts = array(), $content = '');
}
