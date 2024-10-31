<?php
/**
 * ListingsCategoriesField
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
 * Class ListingsCategoriesField
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingsCategoriesField implements FilterFieldInterface
{
    /**
     * Field Name
     *
     * @var string The name to use with this field
     */
    private $name;

    /**
     * Field
     *
     * @since 1.0.0
     *
     * @var \QiblaEvents\Form\Interfaces\Fields A field instance
     */
    private $field;

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
            $value = $qObj instanceof \WP_Term && is_tax('event_categories') ? $qObj->slug : 'all';
        }

        if (is_string($value)) {
            $value = strtolower($value);
        }

        return $value;
    }

    /**
     * ListingsCategoriesField constructor
     *
     * @since 1.0.0
     *
     * @throws \Exception If terms cannot be retrieved
     *
     * @param string $name Name attribute for the input field.
     * @param string $type The type to use with the field.
     */
    public function __construct($name, $type)
    {
        /**
         * All Categories Filter Label
         *
         * @since 1.0.0
         *
         * @param string $label The label of the filter to use as option to select all categories.
         */
        $allOptionsLabel = apply_filters(
            'qibla_listings_filter_category_all_options_label',
            esc_html__('All Categories', 'qibla-events')
        );

        // Select2 theme
        $selectTheme = 'on' === F\getPluginOption('general', 'disable_css', true) ? 'default' : 'qibla-minimal';

        $factory     = new FieldFactory();
        $this->name  = $name;
        $this->field = $factory->base(array(
            'type'          => $type,
            'name'          => $this->name,
            'label'         => esc_html__('Categories', 'qibla-events'),
            'exclude_none'  => true,
            'value'         => $this->value(),
            'select2'       => true,
            'select2_theme' => $selectTheme,
            'options'       => array('all' => $allOptionsLabel) + F\getTermsList(array(
                    'taxonomy'   => 'event_categories',
                    'hide_empty' => false,
                )),
            'attrs'         => array(
                'class' => 'dllistings-ajax-filter-trigger',
            ),
        ));
    }

    /**
     * @inheritDoc
     */
    public function type()
    {
        return $this->field()->getType();
    }

    /**
     * @inheritDoc
     */
    public function field()
    {
        return $this->field;
    }
}
