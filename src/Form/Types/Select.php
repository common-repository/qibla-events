<?php
/**
 * Form Select Type
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

namespace QiblaEvents\Form\Types;

use QiblaEvents\Form\Abstracts\Type;

/**
 * Class Select
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Select extends Type
{
    /**
     * Outputs the html selected attribute.
     *
     * Compares the first two arguments and if identical marks as selected.
     * This function is a wrap of WordPress select that work with arrays.
     *
     * @since  1.0.0
     *
     * @param mixed $selected One of the values to compare.
     * @param mixed $current  (true) The other value to compare if not just true.
     *
     * @return string The html attribute or empty string.
     */
    protected function selected($selected, $current)
    {
        if (is_array($selected)) {
            $index = array_search($current, $selected, true);
            if (false === $index) {
                return '';
            }

            $selected = $selected[$index];
        }

        $selected = selected($selected, $current, false);

        return $selected;
    }

    /**
     * Build Options
     *
     * @since  1.0.0
     *
     * @param array $options The options list.
     *
     * @return string The options markup
     */
    protected function buildOptions(array $options)
    {
        // Initialize output.
        $output = '';

        /**
         * Filter Options Before Build the option tags
         *
         * @since 1.0.0
         *
         * @param array                             $options The select option items.
         * @param \QiblaEvents\Form\Types\Select $this    The class instance
         */
        $options = apply_filters('qibla_events_forms_type_select_options_before_build', $options, $this);

        if (! $options) {
            return $output;
        }

        // 3 depth max.
        static $depth = 2;

        if ($depth) :
            foreach ($options as $value => $label) :
                // Nested array means group.
                if (is_array($label) && ! isset($label['attrs'])) :
                    $output .= '<optgroup label="' . sanitize_key($value) . '">' .
                               $this->buildOptions($label) .
                               '</optgroup>';
                    --$depth;
                else :
                    $attrs = array(
                        $this->selected(strtolower($this->escape()), strtolower($this->escape($value)), false),
                    );

                    if (is_array($label) && isset($label['attrs'])) {
                        $label['attrs'] = $this->getAttrs($label['attrs']);
                        $attrs[]        = $label['attrs'];
                        $label          = $label['label'];
                    }

                    // Append the option.
                    $output .= sprintf(
                        '<option value="%s"%s>%s</option>',
                        $this->escape($value),
                        implode(' ', $attrs),
                        esc_html(sanitize_text_field($label))
                    );
                endif;
            endforeach;
        endif;

        // Reset the depth since it is static.
        $depth = 2;

        return $output;
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
            'value'          => '',
            'options'        => array(),
            'filter'         => FILTER_SANITIZE_STRING,
            'filter_options' => array(
                'flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP,
            ),
            'attrs'          => array(),
            'select2'        => false,
            'select2_theme'  => 'default',
        ));

        // Multiple Modifier.
        if (isset($args['attrs']['multiple'])) {
            $args['value']          = is_array($args['value']) ? $args['value'] : explode(',', $args['value']);
            $args['filter_options'] = array(
                'flags' => FILTER_REQUIRE_ARRAY | FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP,
            );
        }

        // Force Type.
        $args['type'] = 'select';

        if (! isset($args['attrs']['class'])) {
            $args['attrs']['class'] = array();
        }

        if (is_string($args['attrs']['class'])) {
            $args['attrs']['class'] = (array)$args['attrs']['class'];
        }

        // Additional property for select2.
        if ($args['select2']) {
            $args['attrs']['class'][]          = 'dlselect2';
            $args['attrs']['data-selecttheme'] = $args['select2_theme'];
            $args['attrs']['data-placeholder'] = isset($args['attrs']['data-placeholder']) ?
                $args['attrs']['data-placeholder'] :
                '';
        }

        // Include the none option.
        if (! isset($args['options']['none']) && empty($args['exclude_none'])) {
            $args['options'] = array_merge(
                array('none' => esc_html__('Select an option', 'qibla-events')),
                $args['options']
            );
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
     * @return string|array The sanitized value/s of this type. Empty string if the value is not correct.
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
        return is_array($value) ? array_map('esc_html', $value) : esc_html($value);
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

        // Get the arguments attributes for this type.
        $argsAttrs = $this->getArg('attrs');
        // Retrieve the name attribute and append the array symbol in case of multiple attribute.
        $nameAttr = esc_attr($this->getArg('name'));

        if (isset($argsAttrs['multiple'])) {
            $nameAttr .= '[]';
        }

        // Get options.
        $options = $this->buildOptions($options);
        // Options with empty one for select2 placeholder.
        if ($this->getArg('select2') && '' !== $argsAttrs['data-placeholder']) {
            $options = '<option></option>' . $options;
        }

        $output = sprintf(
            '<select name="%s" id="%s"%s>%s</select>',
            $nameAttr,
            esc_attr($this->getArg('id')),
            $this->getAttrs(),
            $options
        );

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_select_output', $output, $this);

        return $output;
    }
}
