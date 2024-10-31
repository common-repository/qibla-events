<?php
namespace QiblaEvents\Form\Types;

use QiblaEvents\Form\Abstracts\Type;

/**
 * Form Map Type
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
 * Class Map
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Map extends Type
{
    /**
     * Create Options Reference
     *
     * Create a js reference for the google map related to this field.
     *
     * @since 1.0.0
     *
     * @return void
     */
    protected function setRefOptions()
    {
        $self = $this;
        add_action((is_admin() ? 'admin_footer' : 'wp_footer'), function () use ($self) {
            $mapArgs   = $self->getArg('map_options');
            $optionRef = str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', $self->getArg('id'))));

            printf(
                '<script type="text/javascript">/* <![CDATA[ */var %1$s = %2$s/* ]]> */</script>',
                $optionRef,
                wp_json_encode($mapArgs)
            );
        });
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
            // Must be a class value without the dot.
            'append_map_to'  => '',
        ));

        // Force the type to be the correct 'text'.
        $args['type'] = 'text';

        if (! isset($args['attrs']['class'])) {
            $args['attrs']['class'] = array();
        }

        // Add the class to able to customize the input via css.
        $args['attrs']['class'] = array_merge($args['attrs']['class'], array('dl-gmap-input-searcher', 'controls'));

        if (! isset($args['map_options'])) {
            $args['map_options'] = array();
        }

        // Add default map options not defined.
        $args['map_options'] = wp_parse_args($args['map_options'], array(
            'zoom'                  => 18,
            'mapTypeControl'        => true,
            'fullscreenControl'     => true,
            'mapTypeControlOptions' => array(
                'style' => 2,
            ),
            'mapTypeId'             => 'roadmap',
        ));

        // Add the data-type attribute. It's a custom type.
        $args['attrs']['data-type'] = 'map';

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
     * @return string The escaped value of this type
     */
    public function escape()
    {
        return esc_html($this->getValue());
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
        if (wp_script_is('map-type', 'registered') &&
            wp_style_is('qibla-form-types', 'registered')
        ) {
            wp_enqueue_style('qibla-form-types');
            wp_enqueue_script('map-type');

            $this->setRefOptions();
        }

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
        $output = apply_filters('qibla_events_type_map_output', $output, $this);

        return $output;
    }
}
