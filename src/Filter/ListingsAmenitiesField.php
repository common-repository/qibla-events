<?php
/**
 * Listings Amenties Field
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace QiblaEvents\Filter;

use QiblaEvents\Form\Factories\FieldFactory;
use QiblaEvents\Functions as F;

/**
 * Class Listings Amenities Field
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Filter
 */
class ListingsAmenitiesField implements FilterFieldInterface
{
    /**
     * Field Name
     *
     * @since 1.0.0
     *
     * @var string The field name
     */
    private $name;

    /**
     * Field
     *
     * @since 1.0.0
     *
     * @var \QiblaEvents\Form\Interfaces\Fields The field for internal use
     */
    private $field;

    /**
     * ListingsAmenitiesField constructor
     *
     * @since 1.0.0
     *
     * @param string $name Name attribute for the input field.
     * @param string $type The type to use with the field.
     */
    public function __construct($name, $type)
    {
        $factory     = new FieldFactory();
        $this->name  = $name;
        $this->field = $factory->base(array(
            'type'            => $type,
            'name'            => $this->name,
            'exclude_all'     => true,
            'value'           => $this->value(),
            'options'         => $this->filterOptionsBasedOnTaxonomy(),
            'container_class' => array('dl-field', "dl-field--{$type}", 'is-autoupdate'),
            'attrs'           => array(
                'data-autoupdate' => $this->name,
            ),
        ));
    }

    /**
     * @inheritdoc
     */
    public function type()
    {
        return $this->field()->getType();
    }

    /**
     * @inheritdoc
     */
    public function field()
    {
        return $this->field;
    }

    /**
     * Retrieve value
     *
     * @since 1.0.0
     *
     * @return array|string Depending on the content may be a string or an array.
     */
    private function value()
    {
        // @codingStandardsIgnoreLine
        $value = F\filterInput($_POST, $this->name, FILTER_SANITIZE_STRING) ?: '';

        if (! $value) {
            // @codingStandardsIgnoreLine
            $value = F\filterInput($_POST, $this->name, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?:
                array();
        }

        if (! $value) {
            $qObj  = get_queried_object();
            $value = $qObj instanceof \WP_Term && is_tax('tags') ? $qObj->slug : '';
        }

        if (is_string($value)) {
            $value = strtolower($value);
        }

        return $value;
    }

    /**
     * Filter Options Based On Taxonomy
     *
     * @since 1.0.0
     *
     * @return array The options list
     */
    private function filterOptionsBasedOnTaxonomy()
    {
        // Get the current queried object to use for comparison.
        $currObj = get_queried_object();

        if (! $currObj instanceof \WP_Term ||
            (isset($currObj->taxonomy) && 'event_categories' !== $currObj->taxonomy)
        ) {
            return F\getTermsList(array(
                'taxonomy'   => 'tags',
                'hide_empty' => false,
            ));
        }

        $list = F\getTermsByTermBasedOnContext(get_terms('tags'), 'event_categories');

        return $list;
    }
}
