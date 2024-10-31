<?php
namespace QiblaEvents\Form\Types;

/**
 * Form Tel Type
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
 * Class Tel
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Tel extends Text
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
            'type'           => 'tel',
            'filter'         => FILTER_SANITIZE_STRING,
            'filter_options' => array(
                'flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP,
            ),
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
        // @todo Create function to sanitize tel number. May include +
        $value = sanitize_text_field($value);

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
        return $this->sanitize($this->getValue());
    }

    /**
     * @inheritDoc
     */
    public function getHtml()
    {
        $output = parent::getHtml();

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_tel_output', $output, $this);

        return $output;
    }
}
