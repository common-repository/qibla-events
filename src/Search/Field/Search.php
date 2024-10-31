<?php
/**
 * Search
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

namespace QiblaEvents\Search\Field;

use QiblaEvents\Functions as F;
use QiblaEvents\Front\Settings;
use QiblaEvents\Request\Nonce;
use QiblaEvents\Form\Factories\FieldFactory;
use QiblaEvents\TemplateEngine\Engine as TEngine;
use QiblaEvents\TemplateEngine\TemplateInterface;
use QiblaEvents\Utils\Json\Encoder;

/**
 * Class Search
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
final class Search implements SearchFieldInterface, TemplateInterface
{
    /**
     * Field Slug
     *
     * @since 1.0.0
     *
     * @var string The field slug
     */
    const SLUG = 'search';

    /**
     * Fields
     *
     * @since 1.0.0
     *
     * @var SearchFieldInterface The field instance
     */
    private $field;

    /**
     * Autocomplete
     *
     * If the search field must use autocomplete or not.
     *
     * @since 1.0.0
     *
     * @var bool True to use autocomplete feature, false otherwise
     */
    private $autocomplete;

    /**
     * Json Encoder Factory
     *
     * @since 1.0.0
     *
     * @return Encoder Instance of the class
     */
    private function jsonEncoderFactory()
    {
        /**
         * Search Json encoder factory
         *
         * To filter the arguments to be passed to the encoder.
         *
         * @since 1.0.0
         */
        $args = apply_filters('qibla_events_search_json_encoder_factory', array(
            'type'             => 'events',
            'containers'       => array(
                'taxonomies' => 'event_categories',
            ),
            'initialContainer' => 'event_categories'
        ));

        return new Encoder($args);
    }

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
            'class'        => array(
                'dlsearch__input',
            ),
            'placeholder'  => F\getPluginOption('search', 'placeholder'),
            'autocomplete' => 'off',
        );

        // Set if the search must use autocomplete.
        if ($this->autocomplete) {
            $attrs['class'][]           = 'use-autocomplete';
            $attrs['data-autocomplete'] = $this->jsonEncoderFactory()->prepare()->json();
        }

        return $attrs;
    }

    /**
     * Search constructor
     *
     * @since 1.0.0
     *
     * @param FieldFactory $fieldFactory The instance of the field factory to use to build the field.
     * @param array        $fieldArgs    Additional arguments for the field.
     * @param bool         $autocomplete If the search must use autocomplete feature. Default to false.
     * @param array        $args         Additional args to set for field.
     */
    public function __construct(
        FieldFactory $fieldFactory,
        array $fieldArgs = array(),
        $autocomplete = false,
        array $args = array()
    ) {
        $this->autocomplete = $autocomplete;
        $this->field        = $fieldFactory->base(wp_parse_args($fieldArgs, array(
            'type'         => 'search',
            'name'         => 's',
            'id'           => 'dlsearch_input',
            'container'    => '',
            'attrs'        => array_merge($this->attrs(), $args),
            'autocomplete' => 'off',
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
     * If use autocomplete
     *
     * @since 1.0.0
     *
     * @return bool True if search field use autocomplete or false otherwise
     */
    public function useAutocomplete()
    {
        return $this->autocomplete;
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

            // Nonce Field.
            'nonce' => $nonce->field(),
        );
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new TEngine('search_input_field', $data, '/views/search/field/search.php');
        $engine->render();
    }
}
