<?php

namespace QiblaEvents\Form\Abstracts;

use QiblaEvents\Form\Interfaces\Types as ITypes;
use QiblaEvents\Form\Interfaces\Fields as IFields;
use QiblaEvents\Form\Traits;

/**
 * Abstract Field
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

/**
 * Class Abstract Field
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class Field extends Traits\FieldsTrait implements IFields
{
    /**
     * Input Type
     *
     * @since  1.0.0
     *
     * @var Type The object type
     */
    protected $type;

    /**
     * Get Label
     *
     * @since  1.0.0
     *
     * @return string The label for the current field type
     */
    protected function getLabel()
    {
        if (! $this->getArg('label')) {
            return '';
        }

        // The label.
        return sprintf(
            '<label for="%s">%s</label>',
            sanitize_key($this->getType()->getArg('id')),
            wp_kses($this->getArg('label'), array(
                'span' => array(
                    'id'    => true,
                    'class' => true,
                ),
            ))
        );
    }

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param ITypes $type The input type related to this field.
     * @param array  $args The arguments to build the field.
     *
     * $args {
     *  label_position: string - before|after The position where the label and description will appear.
     * }
     */
    public function __construct(ITypes $type, array $args = array())
    {
        // Set the input type.
        $this->type = $type;

        // Set the arguments for the current field.
        $this->args = wp_parse_args($args, array(
            'display'             => null,
            'container'           => 'div',
            'container_class'     => array(
                'dl-field',
                'dl-field--' . sanitize_key($this->type->getArg('type')),
            ),
            'label'               => '',
            'label_position'      => 'before',
            'before_label'        => '',
            'before_input'        => '',
            'after_input'         => '',
            'desc_container'      => 'p',
            'description'         => '',
            'invalid_description' => '',
            'attrs'               => array(),
        ));
    }

    /**
     * To String
     *
     * Return the html version of the current field
     *
     * @since  1.0.0
     *
     * @return string The current field in html format
     */
    public function __toString()
    {
        return $this->getHtml();
    }

    /**
     * Get Type
     *
     * @since  1.0.0
     *
     * @return Type The type associated to the current field
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get Invalid Description
     *
     * Used to display the invalid description associated to the input type when the validate fails.
     *
     * @since  1.0.0
     *
     * @return string Return the invalid description of the field
     */
    public function getInvalidDescription()
    {
        if (! $this->getArg('invalid_description')) {
            return '';
        }

        // The block class value.
        $containerClassArg = $this->getArg('container_class');

        return sprintf(
            '<p class="%s__invalid-description">%s</p>',
            sanitize_html_class($containerClassArg[0]),
            $this->getArg('invalid_description')
        );
    }
}
