<?php
namespace QiblaEvents\Form\Types;

use QiblaEvents\Functions as F;
use QiblaEvents\Form\Abstracts\Type;

/**
 * Form Text-area Type
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
 * Class Text-area
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Textarea extends Type
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
            'type'         => 'textarea',
            'allowed_html' => true,
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
        $value = F\ksesPost($value);

        // Strip all tags if html isn't allowed.
        if (false === $this->getArg('allowed_html')) {
            $value = wp_strip_all_tags($value);
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
        return $this->sanitize($this->getValue());
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
            '<textarea name="%s" id="%s"%s>%s</textarea>',
            esc_attr($this->getArg('name')),
            esc_attr($this->getArg('id')),
            $this->getAttrs(),
            $this->escape()
        );

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_textarea_output', $output, $this);

        return $output;
    }
}
