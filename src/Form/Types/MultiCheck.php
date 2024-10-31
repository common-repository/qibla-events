<?php
namespace QiblaEvents\Form\Types;

/**
 * Form MultiCheck Type
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
 * Class MultiCheck
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class MultiCheck extends Checkbox
{
    /**
     * Make Input
     *
     * Create a single checkbox input
     *
     * @param mixed  $value The value of the checkbox.
     * @param string $label The label associated to the checkbox.
     *
     * @return string The input markup
     */
    protected function makeInput($value, $label)
    {
        static $counter = 0;

        $checked = in_array($this->escape($value), (array)$this->getValue(), true) ? 'checked="checked"' : '';

        $input = sprintf(
            '<input type="%1$s" name="%2$s" id="%3$s" value="%4$s"%5$s/><label for="%3$s">%6$s</label>',
            sanitize_key($this->getArg('type')),
            esc_attr($this->getArg('name')) . '[]',
            esc_attr($this->getArg('id')) . '_' . ++$counter,
            $this->escape($value),
            $this->getAttrs() . ' ' . $checked,
            sanitize_text_field($label)
        );

        // Apply the style for the checkbox.
        if ('default' !== $this->getArg('style')) {
            $input = sprintf(
                '<span class="%s">%s<span class="toggler"></span></span>',
                'type-checkbox type-checkbox--' . sanitize_key($this->getArg('style')),
                $input
            );
        }

        return $input;
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
            'options'        => array(),
            'filter'         => FILTER_SANITIZE_STRING,
            'filter_options' => array(
                'flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP,
            ),
        ));

        // It is a list of elements, so we require an array on input.
        $args['filter_options'] = FILTER_REQUIRE_ARRAY;

        // Include the none option.
        if (! isset($args['options']['all']) && empty($args['exclude_all'])) {
            $args['options'] = array_merge(array('all' => esc_html__('All', 'qibla-events')), $args['options']);
        }

        parent::__construct($args);
    }

    /**
     * Sanitize
     *
     * In case of select multiple, the sanitize value become a recursive function, to sanitize every element of the
     * array.
     *
     * @since  1.0.0
     *
     * @param string|array $value The value to sanitize or the array of values in case of select multiple.
     *
     * @return string The sanitized value of this type. Empty string if the value is not correct.
     */
    public function sanitize($value)
    {
        // If select multiple, sanitize every value inside the array.
        if (is_array($value)) {
            // The new sanitized values array.
            $values = array();
            foreach ($value as $key => $item) {
                // Sanitize the current value.
                $values[$key] = $this->sanitize($item);
            }

            return $values;
        }

        $value = sanitize_text_field($value);

        return $this->applyPattern($value);
    }

    /**
     * Escape
     *
     * @since  1.0.0
     *
     * @param mixed $value The value to sanitize.
     *
     * @return mixed The escaped value of this type
     */
    public function escape($value = null)
    {
        $value = $value ?: $this->getValue();

        // If select multiple, execute the call for every value.
        return is_array($value) ? array_map('esc_attr', $value) : esc_html($value);
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
        $options = $this->getArg('options');
        if (empty($options)) {
            return '';
        }

        // The markup output.
        $output = '<ul class="multicheck-list">';

        foreach ($options as $value => $label) {
            $output .= '<li class="multicheck-list__item">' . $this->makeInput($value, $label) . '</li>';
        }

        $output .= '</ul>';

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_multicheck_output', $output, $this);

        return $output;
    }
}
