<?php
/**
 * Form
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

use QiblaEvents\Collection;
use QiblaEvents\Form\Types\Hidden;
use QiblaEvents\Functions as F;
use QiblaEvents\Form\Abstracts;
use QiblaEvents\ListingsContext\Context;
use QiblaEvents\ListingsContext\Types;
use QiblaEvents\Request\Nonce;

/**
 * Class Form
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Form extends Abstracts\Form
{
    /**
     * Filters
     *
     * @since 1.0.0
     *
     * @var \QiblaEvents\Collection A collection of filters
     */
    private $filters;

    /**
     * Context
     *
     * Which is the context to manage
     *
     * @var \QiblaEvents\Listings\Context The context in which the form works
     */
    private $context;

    /**
     * Geocode Filter
     *
     * @since 1.0.0
     *
     * @var \QiblaEvents\Filter\GeocodeFilter The geocode filter instance
     */
    private $geocodeFilter;

    /**
     * Form constructor
     *
     * The class doesn't perform any check on Collection item type, so you should pass the correct type.
     *
     * @since 1.0.0
     *
     * @param Collection    $filters       A collection of filters.
     * @param Context       $context       The context in which the form works.
     * @param GeocodeFilter $geocodeFilter The geocode filter instance.
     */
    public function __construct(Collection $filters, Context $context, GeocodeFilter $geocodeFilter)
    {
        /**
         * Listings Filter Filters
         *
         * Filter the listings Filters fields before
         *
         * @since 1.0.0
         *
         * @param array $filterFields The list of the filters to filter.
         */
        $this->filters = apply_filters('qibla_listings_filter_fields', $filters);

        $this->context       = $context;
        $this->geocodeFilter = $geocodeFilter;

        parent::__construct(array(
            'action' => get_post_type_archive_link($this->context->context()),
            'method' => 'post',
            'name'   => 'qibla_form_filter',
            'attrs'  => array(
                'id'    => 'dlform_filter',
                'class' => 'dlform-filter',
            ),
        ));

        // Set the fields.
        foreach ($this->filters as $filter) {
            parent::addField($filter->field());
        }

        // Set the context in which the filter form must works.
        $this->contextField();
        // Add geocode filter fields.
        $this->geocodeFilterFields();
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        if (empty($this->fields)) {
            return '';
        }

        // Get args Attrs for the current form.
        $argsAttrs = $this->getArg('attrs');

        $output = sprintf(
            '<form action="%s" method="%s" name="%s"%s>',
            esc_url($this->getArg('action')),
            esc_attr($this->getArg('method')),
            sanitize_key($this->getArg('name')),
            $this->getAttrs()
        );

        /**
         * Filter Form output after opened
         *
         * @since 1.0.0
         *
         * @param string                      $output  The form output without the closing tag.
         * @param \QiblaEvents\Filter\Form $this    The instance of this form.
         * @param string                      $context The context in which the form operate.
         */
        $output = apply_filters(
            'qibla_listings_filter_form_output_after_opened',
            $output,
            $this,
            $this->context->context()
        );

        // Class scope block.
        $scopeClass = sanitize_html_class($argsAttrs['class']);

        // Order filters.
        $filters = $this->groupFiltersByPosition();

        // Get Normal Fields.
        if (! empty($filters['normal'])) {
            // Show the fields based on their position.
            // Normal are visible by default, while hidden are wrapped within a div to be able to set them hidden.
            foreach ($filters['normal'] as $filter) {
                $output .= $filter->field();
            }
        }

        // Get Hidden fields.
        if (! empty($filters['hidden'])) {
            $button = sprintf(
                '<a id="dltoggler_filters" class="dltoggler dltoggler--filters" href="#"><span></span> %s</a>',
                esc_html__('Other filters', 'qibla-events')
            );

            /**
             * Filter hidden button trigger.
             *
             * @since 1.0.0
             */
            $output .= apply_filters(
                'qibla_hidden_filter_button_trigger',
                sprintf(
                    '<div id="dltogglers" class="dl-field dltogglers">%s</div>',
                    $button
                )
            );

            /**
             * Filter for disable hidden fields, in some cases it can be useful.
             *
             * @since 1.0.0
             */
            if ('no' === apply_filters('qibla_disable_form_filter_hidden_fields', 'no')) {
                // Class scope block is the first class of the form.
                $output .= '<div class="' . sanitize_html_class($argsAttrs['class']) . '__hidden-fields">';

                // Retrieve the filters output.
                foreach ($filters['hidden'] as $filter) {
                    $output .= $filter->field();
                }

                // Build Form Actions.
                $output .= '<div class="' . $scopeClass . '__actions">';
                // - Clean form button
                // Set as link because the reset button doesn't reset pre-filled forms.
                $output .= sprintf(
                    '<a href="%s#" class="%s" data-action="clear">%s</a>',
                    esc_url(get_post_type_archive_link($this->context->context())),
                    "dlbtn dlbtn--tiny dlbtn--ghost {$scopeClass}__action {$scopeClass}__action--clear",
                    esc_html__('Cancel', 'qibla-events')
                );
                // - Submit Button
                $output .= sprintf(
                    '<input type="submit" class="%s" value="%s" data-action="update" />',
                    "dlbtn dlbtn--tiny {$scopeClass}__action {$scopeClass}__action--update",
                    esc_html__('Update', 'qibla-events')
                );

                $output .= '</div>';
                // Close the hidden fields container.
                $output .= '</div>';
            }
        }

        // Add hidden types.
        foreach ($this->hiddenTypes as $name => $type) {
            $output .= $type;
        }

        // Add nonce.
        $output .= $this->getNonce();

        /**
         * Filter Form output before closing it.
         *
         * @since 1.0.0
         *
         * @param string                      $output  The form output without the closing tag.
         * @param \QiblaEvents\Filter\Form $this    The instance of this form.
         * @param string                      $context The context in which the form operate.
         */
        $output = apply_filters(
            'qibla_listings_filter_form_output_before_close',
            $output,
            $this,
            $this->context->context()
        );

        $output .= '</form>';

        return $output;
    }

    /**
     * Helper Form
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function formFilter()
    {
        $instance = new self(
            Repository::retrieveFilters(),
            new Context(F\getWpQuery(), new Types()),
            new GeocodeFilter(new Nonce('geocoded'))
        );

        // @codingStandardsIgnoreLine
        echo F\ksesPost($instance);
    }

    /**
     * Group Filters By Position
     *
     * @since 1.0.0
     *
     * @return array The grouped filters
     */
    private function groupFiltersByPosition()
    {
        $filters = array();

        foreach ($this->filters as $filter) {
            $filters[$filter->position()][$filter->name()] = $filter;
        }

        return $filters;
    }

    /**
     * Set the Context Field
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function contextField()
    {
        // Set the context.
        $this->addHidden(new Hidden(array(
            'name'  => 'post_type',
            'attrs' => array(
                'value' => $this->context->context(),
            ),
        )));
    }

    /**
     * Set the Geocode Filter Fields
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function geocodeFilterFields()
    {
        foreach ($this->geocodeFilter->inputs() as $input) {
            $this->addHidden($input);
        }
    }
}
