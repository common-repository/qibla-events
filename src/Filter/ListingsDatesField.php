<?php
/**
 * ListingsDatesField.php
 *
 * @since      ${SINCE}
 * @package    QiblaEvents\Filter
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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
 * Class ListingsDatesField
 *
 * @since  ${SINCE}
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class ListingsDatesField  implements FilterFieldInterface
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
     * Dates
     *
     * @since 1.0.0
     *
     * @var array The Dates array for select options
     */
    private $datesOption = array();

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
            $value = $qObj instanceof \WP_Term && is_tax('dates') ? $qObj->slug : 'all';
        }

        if (is_string($value)) {
            $value = strtolower($value);
        }

        return $value;
    }

    /**
     * Calendar Scripts.
     *
     * @since 1.0.0
     */
    public function calendarScripts()
    {
        // Script
        if (wp_script_is('appmap-ev-calendar-filter', 'registered')) {
            wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
            wp_enqueue_script('appmap-ev-calendar-filter', array('jquery-ui-datepicker'));
        }
        // Lang
        if (wp_script_is('datepicker-lang', 'registered')) {
            wp_enqueue_script('datepicker-lang', array('jquery', 'jquery-ui-datepicker'));
        }
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
        $obj   = get_queried_object();
        $value = isset($obj->taxonomy) && 'dates' === $obj->taxonomy ? $obj->slug : 'all';

        /**
         * All Dates Filter Label
         *
         * @since 1.0.0
         *
         * @param string $label The label of the filter to use as option to select all categories.
         */
        $label = apply_filters(
            'qibla_listings_filter_date_all_options_label',
            esc_html__('All Dates', 'qibla-events')
        );

        if ('all' !== $value) {
            $date  = new \DateTime($value);
            $label = date_i18n('Y-m-d', $date->getTimestamp());
        }

        // Override options
        $this->datesOption = array(
            'all' => array(
                'attrs' => array(),
                'label' => $label,
            ),
        );

        // Add dates in event_dates taxonomy.
        $this->datesOption = array_merge($this->datesOption,
            array(
                'days' => F\getTermsList(array(
                    'taxonomy'   => 'dates',
                    'hide_empty' => false,
                )),
            ));

        if ('all' !== $value) {
            $this->datesOption = array_merge(array(
                'all' => array(
                    'attrs' => array(),
                    'label' => esc_attr__('All Dates', 'qibla-events'),
                ),
            ), $this->datesOption);
        }
        /* Override option to activate only the calendar. -------------------------------------- */

        // Select2 theme
        $selectTheme = 'on' === F\getPluginOption('general', 'disable_css', true) ? 'default' : 'qibla-minimal';

        $factory     = new FieldFactory();
        $this->name  = $name;
        $this->field = $factory->base(array(
            'type'          => $type,
            'name'          => $this->name,
            'label'         => esc_html__('Dates', 'qibla-events'),
            'exclude_none'  => true,
            'value'         => $this->value(),
            'select2'       => true,
            'select2_theme' => $selectTheme,
            'options'       => $this->datesOption,
            'attrs'         => array(
                'class' => 'dllistings-ajax-filter-trigger',
            ),
        ));
    }

    /**
     * @return mixed
     */
    private function taxFromFilterName()
    {
        return str_replace(array('qibla_', '_filter'), '', $this->name);
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
        if ('dates' === $this->taxFromFilterName()) {
            $this->calendarScripts();
        }

        return $this->field;
    }
}
