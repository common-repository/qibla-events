<?php
namespace QiblaEvents\Form\Abstracts;

use QiblaEvents\Form\Traits;
use QiblaEvents\Form\Interfaces\Fieldsets;

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
 * Class Abstract Fieldset
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class Fieldset extends Traits\FieldsTrait implements Fieldsets
{
    /**
     * Fields
     *
     * @since  1.0.0
     *
     * @var array A list of Fields object
     */
    protected $fields;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param array $fields The fields to add to this set.
     * @param array $args   The arguments to build the fieldset.
     */
    public function __construct(array $fields, array $args = array())
    {
        // Set the fields.
        $this->fields = $fields;

        // Set the arguments for the current field.
        $this->args = wp_parse_args($args, array(
            'display'         => null,
            'form'            => '',
            'id'              => '',
            'name'            => '',
            'legend'          => '',
            'before_fieldset' => '',
            'after_fieldset'  => '',
            'container_class' => array('dl-fieldset'),
            'desc_container'  => 'p',
            'description'     => '',
            'icon_class'      => '',
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
     * Get Fields
     *
     * @since  1.0.0
     *
     * @return array The list of fields
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get Legend
     *
     * @since  1.0.0
     *
     * @return string The legend markup
     */
    protected function getLegend()
    {
        return sprintf(
            '<legend data-icon="%s">%s</legend>',
            preg_replace('/[^a-z0-9\s\-\_]/', '', $this->getArg('icon_class')),
            esc_html(wp_strip_all_tags($this->getArg('legend')))
        );
    }
}
