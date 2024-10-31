<?php
namespace QiblaEvents\Form\Types;

/**
 * Form Date Time Type
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

use QiblaEvents\Utils\TimeZone;

/**
 * Class DateTime
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class DateTime extends Text
{
    /**
     * Normalize Time Format
     *
     * Javascript doesn't like the Php format g:i A, g:i a, so we convert those to H:i.
     *
     * For more info have a look at assets/js/types/dateTimePicker.js
     *
     * @return string The time normalized time format.
     */
    private function normalizeTimeFormat()
    {
        $time = get_option('time_format');

        if ('g:i A' === $time || 'g:i a' === $time) {
            $time = 'H:i';
        }

        return $time;
    }

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
            'filter'         => FILTER_SANITIZE_STRING,
            'filter_options' => array(
                'flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP,
            ),
            'attrs'          => array(),
        ));

        // Force input type.
        // @todo use datetime-local when will be supported.
        $args['type'] = 'text';

        // Able to use the jQuery datepicker widget.
        $args['attrs'] = array_merge($args['attrs'], array(
            'data-type' => 'datetimepicker',
        ));

        parent::__construct($args);
    }

    /**
     * Sanitize
     *
     * @since  1.0.0
     *
     * @param string $value The value to sanitize.
     *
     * @return string The sanitized value of this type. Empty string if the value is not correct.
     */
    public function sanitize($value)
    {
        $timeZone = new TimeZone();

        if (! is_int($value) && $value) {
            $date = \DateTime::createFromFormat(
                get_option('date_format') . ' ' . $this->normalizeTimeFormat(),
                $value,
                $timeZone->getTimeZone()
            );

            $value = $date->getTimestamp();
        }

        return parent::sanitize($value);
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
        wp_enqueue_style('qibla-form-types');
        wp_enqueue_script('datetimepicker-type');

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
        $output = apply_filters('qibla_events_type_datetime_output', $output, $this);

        return $output;
    }
}
