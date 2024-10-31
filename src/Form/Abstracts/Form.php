<?php
namespace QiblaEvents\Form\Abstracts;

use QiblaEvents\Form\Interfaces\Fields as IFields;
use QiblaEvents\Form\Interfaces\Fieldsets as IFieldsets;
use QiblaEvents\Form\Interfaces\Types;
use QiblaEvents\Form\Traits;
use QiblaEvents\Form\Interfaces\Forms;
use QiblaEvents\Form\Traits\ArgumentsTrait;

/**
 * Form
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
 * Class Abstract Form
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class Form extends Traits\ArgumentsTrait implements Forms
{
    /**
     * Fields
     *
     * @since  1.0.0
     *
     * @var array A list of object fields for this form
     */
    protected $fields;

    /**
     * Hidden Types
     *
     * @since  1.0.0
     *
     * @var array A list of hidden types for this form
     */
    protected $hiddenTypes;

    /**
     * Get Extra Attributes
     *
     * @since  1.0.0
     *
     * @return string The string key="value" pair extracted from the attributes array
     */
    protected function getAttrs()
    {
        $attrs  = '';
        $_attrs = $this->getArg('attrs');

        if (! empty($_attrs) && is_array($_attrs)) {
            foreach ($_attrs as $key => $val) {
                $attrs .= ' ' . sanitize_key($key) . '="' . esc_attr($val) . '"';
            }
        }

        return $attrs;
    }

    /**
     * Constructor
     *
     * @since  1.0.0
     *
     * @param array $args The arguments for the current form.
     */
    public function __construct(array $args = array())
    {
        $this->fields      = array();
        $this->hiddenTypes = array();
        $this->args        = wp_parse_args($args, array(
            'action' => '#',
            'method' => 'get',
            'name'   => 'qibla_form',
            'attrs'  => array(),
        ));
    }

    /**
     * To String
     *
     * Return the form in html format
     *
     * @since  1.0.0
     *
     * @return string The current form in html format
     */
    public function __toString()
    {
        return $this->getHtml();
    }

    /**
     * Add Fields
     *
     * @since  1.0.0
     *
     * @param array $fields A list of IFields instance to add to the form
     *
     * @return void
     */
    public function addFields(array $fields)
    {
        foreach ($fields as $field) {
            if ($field instanceof IFieldsets) {
                $this->addFieldset($field);
            } else {
                $this->addField($field);
            }
        }
    }

    /**
     * Add Field-set Fields
     *
     * Field-sets are internally manages as fields, this due the fact that field-sets doesn't exists
     * within a form, they are just fields, we define field-sets on front to manage group of fields but are stored
     * like any other field.
     *
     * This help us with processers, validates and other logics to manage only one kind of data.
     *
     * @since  1.0.0
     *
     * @param IFieldsets $fieldset The field-set to add to this form.
     *
     * @return void
     */
    public function addFieldset(IFieldsets $fieldset)
    {
        foreach ($fieldset->getFields() as $field) {
            $this->addField($field);
        }
    }

    /**
     * Add Field
     *
     * @since  1.0.0
     *
     * @param IFields $field The field to add to this form
     *
     * @return void
     */
    public function addField(IFields $field)
    {
        // Get the attribute id value of the input type associated to the field.
        // Since it is unique, we can use it to reference the field within the form.
        $key = $field->getType()->getArg('id');

        // Set the field.
        $this->fields[sanitize_key($key)] = $field;
    }

    /**
     * @inheritdoc
     */
    public function addHidden(Types $type)
    {
        if ('hidden' !== $type->getArg('type')) {
            return;
        }

        // Get the attribute id of the input type to use as key.
        $key = $type->getArg('id');

        $this->hiddenTypes[sanitize_key($key)] = $type;
    }

    /**
     * Get Field
     *
     * @since  1.0.0
     *
     * @param string $name The name of the field to retrieve.
     *
     * @return IFields|\stdClass The field object requested an stdClass object if the field doesn't exists
     */
    public function getField($name)
    {
        if (! isset($this->fields[$name])) {
            return new \stdClass();
        }

        return $this->fields[$name];
    }

    /**
     * Get Fields
     *
     * @since  1.0.0
     *
     * @return array The fields associated to the current form
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get Form Nonce
     *
     * @since  1.0.0
     *
     * @return string The input type hidden for nonce
     */
    public function getNonce()
    {
        return wp_nonce_field(
            $this->getArg('name'),
            $this->getArg('name') . '_nonce',
            true,
            false
        );
    }
}
