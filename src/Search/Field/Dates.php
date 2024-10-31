<?php
/**
 * Dates
 *
 * @since 1.0.0
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

namespace QiblaEvents\Search\Field;

use QiblaEvents\Request\Nonce;
use QiblaEvents\Form\Factories\FieldFactory;
use QiblaEvents\TemplateEngine\Engine as TEngine;
use QiblaEvents\TemplateEngine\TemplateInterface;

/**
 * Class Dates
 *
 * @since 1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Dates implements SearchFieldInterface, TemplateInterface
{
    /**
     * Field Slug
     *
     * @since 1.0.0
     *
     * @var string The field slug
     */
    const SLUG = 'dates';

    /**
     * Fields
     *
     * @since 1.0.0
     *
     * @var SearchFieldInterface The field instance
     */
    private $field;

    /**
     * Taxonomy
     *
     * @since 1.0.0
     *
     * @var string The taxonomy name for set hidden field
     */
    private $taxonomyName;

    /**
     * Build Attributes for Form
     *
     * @since 1.0.0
     *
     * @return array The list of the attributes for the form markup.
     */
    private function attrs()
    {
        // Set default attributes.
        $attrs = array(
            'class'       => array(
                'dlsearch__input',
            ),
            'placeholder' => esc_html__('All Dates', 'qibla-events'),
        );

        return $attrs;
    }

    /**
     * Dates constructor.
     *
     * @since 1.0.0
     *
     * @param FieldFactory $fieldFactory The instance of the field factory to use to build the field.
     * @param  string      $taxonomyName The name of the taxonomy for set hidden field.
     * @param array        $fieldArgs    Additional arguments for the field.
     * @param array        $args         Additional args to set for field.
     */
    public function __construct(
        FieldFactory $fieldFactory,
        $taxonomyName,
        array $fieldArgs = array(),
        array $args = array()
    ) {
        $this->taxonomyName = $taxonomyName;
        $this->field        = $fieldFactory->base(wp_parse_args($fieldArgs, array(
            'type'      => 'search',
            'name'      => 'qibla_dates_filter',
            'id'        => 'dlsearch_input_dates',
            'container' => '',
            'attrs'     => array_merge($this->attrs(), $args),
        )));
    }

    /**
     * @inheritDoc
     */
    public function slug()
    {
        return self::SLUG;
    }

    /**
     * @inheritDoc
     */
    public function field()
    {
        return $this->field;
    }

    /**
     * @inheritDoc
     */
    public function doField()
    {
        $this->tmpl($this->getData());
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $nonce = new Nonce('dlsearch_input');

        return (object)array(
            // The field.
            'field' => $this->field,
            // Taxonomy.
            'taxonomy' => $this->taxonomyName,
            // Nonce Field.
            'nonce' => $nonce->field(),
        );
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new TEngine('search_events_dates_input_field', $data, '/views/search/field/dates.php');
        $engine->render();
    }
}
