<?php
/**
 * Form Radio Type
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
 * Class Radio
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Radio extends Type
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
            'options' => array(),
        ));

        // Force Type.
        $args['type'] = 'radio';

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

        return esc_html($value);
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

        $radios = array();
        $c      = 1;

        foreach ($this->getArg('options') as $value => $label) {
            array_push($radios, sprintf(
                '<li><input type="%1$s" name="%2$s" id="%3$s" value="%4$s"%5$s /><label for="%3$s">%6$s</label></li>',
                sanitize_key($this->getArg('type')),
                esc_attr($this->getArg('name')),
                esc_attr($this->getArg('id') . $c),
                $this->escape($value),
                $this->getAttrs() . ' ' . checked($this->escape(), $this->escape($value), false),
                esc_html(sanitize_text_field($label))
            ));

            ++$c;
        }

        $output = '<ul>' . implode("\n", $radios) . '</ul>';

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_radio_output', $output, $this);

        return $output;
    }
}
