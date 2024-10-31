<?php
namespace QiblaEvents\Form\Types;

/**
 * Form Number Type
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Types
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
 * Class Number
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Form\Types
 */
class Number extends Text
{
    /**
     * Constructor
     *
     * @since  1.0.0
     *
     * @param array $args The arguments for this type.
     */
    public function __construct($args)
    {
        $args = wp_parse_args($args, array(
            'filter' => FILTER_SANITIZE_NUMBER_INT,
        ));

        // Make sure that if the filter is FILTER_SANITIZE_NUMBER_FLOAT the filter_options
        // contain the correct value only if empty. Avoid to overwrite existing args.
        if (FILTER_SANITIZE_NUMBER_FLOAT === $args['filter'] && ! isset($args['filter_options'])) {
            $args['filter_options'] = FILTER_FLAG_ALLOW_FRACTION;
        }

        $args['type'] = 'number';

        parent::__construct($args);
    }

    /**
     * Sanitize
     *
     * @since  1.0.0
     *
     * @param string $value The value to sanitize.
     *
     * @return int|float The sanitized value of this type. Empty string if the value is not correct.
     */
    public function sanitize($value)
    {
        if (! is_numeric($value)) {
            return 0;
        }

        switch ($this->getArg('filter')) {
            case 520:
                $value = floatval($value);
                break;

            default:
                $value = intval($value);
                break;
        }

        return $this->applyPattern($value);
    }

    /**
     * Escape
     *
     * @since  1.0.0
     *
     * @return string The escaped value of this type
     */
    public function escape()
    {
        return esc_attr(wp_strip_all_tags($this->getValue()));
    }

    /**
     * Get Html
     *
     * @since  1.0.0
     *
     * @return string The html version of this type
     */
    public function getHtml()
    {
        $output = sprintf(
            '<input type="%s" name="%s" id="%s"%s />',
            sanitize_key($this->getArg('type')),
            esc_attr($this->getArg('name')),
            esc_attr($this->getArg('id')),
            $this->getAttrs()
        );

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_number_output', $output, $this);

        return $output;
    }
}
