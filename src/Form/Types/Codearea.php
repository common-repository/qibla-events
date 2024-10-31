<?php
namespace QiblaEvents\Form\Types;

/**
 * Form Code-Area Type
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
 * Class CodeArea
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class CodeArea extends Textarea
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
        $args = wp_parse_args(array(
            'type' => 'textarea',
        ), $args);

        if (! isset($args['attrs'])) {
            $args['attrs'] = array();
        }

        // Additional Attributes.
        $args['attrs'] = array_merge($args['attrs'], array(
            // Add the data type to able to select the code area via javascript.
            'data-type' => 'codearea',
        ));

        parent::__construct($args);
    }

    /**
     * Sanitize
     *
     * @since  1.0.0
     *
     * @todo   Need sanitization
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
     * @todo   Need Excape
     *
     * @return string The escaped value of this type
     */
    public function escape()
    {
        return $this->getValue();
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return wp_unslash(parent::getValue());
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
        if (function_exists('wp_enqueue_code_editor')) {
            $settings = wp_enqueue_code_editor($this->getArg('args'));

            wp_add_inline_script(
                'code-editor',
                sprintf(
                    'jQuery( function() { wp.codeEditor.initialize( "' . $this->getArg('id') . '", %s ); } );',
                    wp_json_encode($settings)
                )
            );
        }

        $output = parent::getHtml();

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_codearea_output', $output, $this);

        return $output;
    }
}
