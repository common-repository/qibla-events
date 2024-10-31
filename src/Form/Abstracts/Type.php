<?php
/**
 * Abstract Type
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Abstracts
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

namespace QiblaEvents\Form\Abstracts;

use QiblaEvents\Form\Traits;
use QiblaEvents\Form\Interfaces\Types;

/**
 * Class Abstract Type
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class Type extends Traits\ArgumentsTrait implements Types
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
            'name'           => '',
            'id'             => (isset($args['id']) ? $args['id'] : $args['name']),
            'attrs'          => array(),
            'sanitize_cb'    => array($this, 'sanitize'),
            'escape_cb'      => array($this, 'escape'),
            'filter'         => '', // Needed, some input may not need a filter value.
            'filter_options' => array(),
            'is_invalid'     => false,
            'allow_html'     => false,
        ));

        $this->args = apply_filters('qibla_form_filter_type_args', $args);
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
        return $value;
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
        return esc_attr($this->getValue());
    }

    /**
     * To String
     *
     * Return the html version of the current type
     *
     * @since  1.0.0
     *
     * @return string The current type in html format
     */
    public function __toString()
    {
        return $this->getHtml();
    }

    /**
     * Get Value
     *
     * Since some inputs may not need to have a value attribute, we must check if the value argument exists
     * as second choice.
     *
     * @since  1.0.0
     *
     * @return mixed The value of the input type, empty string if there is no value to return.
     */
    public function getValue()
    {
        $attrs = (array)$this->getArg('attrs');

        // Check for value attribute ( value="" ) and if not exists, try to retrieve the value argument of the type.
        return isset($attrs['value']) ? $attrs['value'] : ($this->hasArg('value') ? $this->getArg('value') : '');
    }

    /**
     * Set Value
     *
     * @since  1.0.0
     *
     * @param mixed $value The value to set
     */
    protected function setValue($value)
    {
        $attrs = (array)$this->getArg('attrs');
        // Always sanitize.
        $value = $this->sanitize($value);

        if ($this->hasArg('value')) {
            $this->setArg('value', $value);
        } elseif (isset($attrs['value'])) {
            // Remove the previous attribute value.
            unset($attrs['value']);
            // Set the new value.
            $attrs['value'] = $value;
            // Restore the attrs within the arguments.
            $this->setArg('attrs', $attrs);
        }
    }

    /**
     * Get Extra Attributes
     *
     * This method is deprecated, is still here to BC.
     *
     * @since 1.0.0
     *
     * @param array $attrs The attributes key => value pair to convert to string.
     *
     * @return string The string key="value" pair extracted from the attributes array
     */
    protected function getAttrs(array $attrs = array())
    {
        $attrs = $attrs ? $attrs : $this->getArg('attrs');

        return \QiblaEvents\Functions\attrs($attrs);
    }

    /**
     * Apply Pattern
     *
     * Apply the pattern defined by the type if exists.
     *
     * @since  1.0.0
     *
     * @param string $value The value to pass to the pattern.
     *
     * @return string The result of the applied pattern. Empty string if the pattern doesn't matched anything.
     */
    protected function applyPattern($value)
    {
        if (! $this->hasArg('attrs')) {
            return $value;
        }

        $attrs   = $this->getArg('attrs');
        $pattern = isset($attrs['pattern']) ? $attrs['pattern'] : '';

        if ($pattern) {
            if (! preg_match("/{$pattern}/", $value)) {
                return '';
            }
        }

        return $value;
    }
}
