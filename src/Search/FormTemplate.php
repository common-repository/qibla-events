<?php
/**
 * Search Form
 *
 * @since      1.0.0
 * @package    QiblaEvents\Search
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione
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

namespace QiblaEvents\Search;

use QiblaEvents\Functions as F;
use QiblaEvents\IconsSet;
use QiblaEvents\Request\Nonce;
use QiblaEvents\TemplateEngine\Engine as TEngine;
use QiblaEvents\TemplateEngine\TemplateInterface;

/**
 * Class Front-end Searcher Form
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class FormTemplate implements TemplateInterface
{
    /**
     * The ID
     *
     * The unique id of the form
     *
     * @since 1.0.0
     *
     * @var string The id attribute value of the form
     */
    private $id;

    /**
     * Post Type
     *
     * @since  1.0.0
     *
     * @var array The post types for which search
     */
    private $postTypes;

    /**
     * Fields
     *
     * @since 1.0.0
     *
     * @var array List of fields to include into the form
     */
    private $fields;

    /**
     * Form Method
     *
     * @since 1.0.0
     *
     * @var string The form method. Post or Get.
     */
    private $method;

    /**
     * Search Type
     *
     * @since 1.0.0
     *
     * @var string The search type
     */
    private $type;

    /**
     * Construct
     *
     * @since 1.0.0
     *
     * @param string $id        The id of the search form element.
     * @param array  $postTypes The list of the post types to use within the search.
     * @param array  $fields    The fields to use within this form.
     * @param string $type      The type of the form.
     * @param string $method    The method used to send the data. POST or GET.
     */
    public function __construct($id, array $postTypes, $fields, $type, $method)
    {
        $this->id        = sanitize_key($id);
        $this->postTypes = array_filter($postTypes, 'sanitize_key');
        $this->fields    = $fields;
        $this->method    = $method;
        $this->type      = $type;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $postTypeInputNameKey = (1 === count($this->postTypes)) ? 'post_type' : 'post_type[]';
        $action               = (1 === count($this->postTypes)) ?
            get_post_type_archive_link($this->postTypes[0]) :
            home_url('/');

        // Initialize data instance.
        $data = array(
            // Container Class.
            'class'                 => array(
                'dlsearch',
                "dlsearch--{$this->type}",
            ),

            // Form Data.
            'id'                    => $this->id,
            'action'                => $action,
            'postTypes'             => $this->postTypes,
            'postTypesInputNameKey' => $postTypeInputNameKey,

            // Submit.
            'submitLabel'           => F\getPluginOption('search', 'submit_label', true),
            'submitIcon'            => new IconsSet\Icon(
                F\getPluginOption('search', 'submit_icon', false),
                F\getDefaultOptions('search', 'submit_icon')
            ),

            // Fields.
            'fields'                => $this->fields,

            // Form Method.
            'method'                => $this->method,
        );

        return (object)$data;
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine   = new TEngine('searcher_form', $data, '/views/search/form.php');
        $response = $engine->render();

        if ($response) {
            // Load Scripts needed by the fields diplayed in front-end.
            $scriptLoader = new ScriptLoader($this->fields);
            $scriptLoader->throughtFields();
        }
    }
}
