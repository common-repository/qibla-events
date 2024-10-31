<?php
/**
 * Director Filter Request
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

namespace QiblaEvents\Filter\Request;

use QiblaEvents\Functions as F;
use QiblaEvents\Filter\JsonBuilder;
use QiblaEvents\Filter\Repository;
use QiblaEvents\Form\Interfaces\Validators;
use QiblaEvents\Request\AbstractDirectorRequest;
use QiblaEvents\Request\RequestControllerInterface;

/**
 * Class DirectorFilterRequest
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class DirectorRequestFilter extends AbstractDirectorRequest
{
    /**
     * Validator
     *
     * @since 1.0.0
     *
     * @var Validators The validator to use to validate fields
     */
    private $validator;

    /**
     * Json Builder
     *
     * @since 1.0.0
     *
     * @var JsonBuilder The builder to use to build the json data
     */
    private $jsonBuilder;

    /**
     * Auto update filters
     *
     * @since 1.0.0
     *
     * @var array Containing the filters that must update their markup
     */
    private $autoUpdateFilters;

    /**
     * Filter
     *
     * Created to allow the object to remember the data to inject into the Controller due to the fact that
     * the hooks for parse args the query must be executed once.
     *
     * @since 1.0.0
     *
     * @var array The list of the filters inject into Controller
     */
    private $filters;

    /**
     * Arguments
     *
     * Created to allow the object to remember the data to inject into the Controller due to the fact that
     * the hooks for parse args the query must be executed once.
     *
     * @since 1.0.0
     *
     * @var array The arguments to inject into the Controller.
     */
    private $args;

    /**
     * Validate
     *
     * @since 1.0.0
     *
     * @return array The response of the validation
     */
    private function validate()
    {
        $filters = Repository::retrieveFilters()->asArray();

        // Process and validate the filters form.
        $response = $this->validator->validate(array_map(function ($filter) {
            return $filter->field();
        }, $filters));

        return $response;
    }

    /**
     * DirectorRequestFilter constructor
     *
     * @since 1.0.0
     *
     * @param RequestControllerInterface $controller  The controller to which dispatch the request.
     * @param Validators                 $validator   The validator to use to validate the fields.
     * @param JsonBuilder                $jsonBuilder The builder to use to build the json data.
     */
    public function __construct(RequestControllerInterface $controller, Validators $validator, JsonBuilder $jsonBuilder)
    {
        parent::__construct($controller);

        $this->jsonBuilder       = $jsonBuilder;
        $this->validator         = $validator;
        $this->autoUpdateFilters = array();

        $this->filters = array();
        $this->args    = array();
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function director()
    {
        $response = $this->validate();

        // Nothing to process.
        if (! $response) {
            return;
        }

        // If there are invalid fields, don't do anythings, this means some one had
        // compromised the form data.
        if (! empty($response['invalid']) || empty($response['valid'])) {
            return;
        }

        $this->setAutoUpdateFilters();

        $this->delegateQueryFilter($response);
        $this->delegateJsonBuilding();
    }

    /**
     * Delegate the Query
     *
     * Hook 'pre_get_posts' To filter the query.
     *
     * @since 1.0.0
     *
     * @param array $data The data from which extract the valid fields values
     */
    public function delegateQueryFilter(array $data)
    {
        $filters = Repository::retrieveFilters()->asArray();
        $args    = array();

        // Build the arguments for the query.
        foreach ($filters as &$filter) {
            $field = $filter->field();

            // Some input type may not be sent nor have values like checkboxes.
            if (! isset($data['valid'][$field->getArg('name')])) {
                continue;
            }

            $args[$filter->name()] = (array)$data['valid'][$field->getArg('name')];
        }
        unset($filter, $field);

        $this->filters = $filters;
        $this->args    = $args;

        add_action('pre_get_posts', array($this, 'delegateQueryFilterCallback'), 20);
    }

    /**
     * Callback for delegateQueryFilter
     *
     * It's a separate method because we need to execute the action once.
     * Please I know, but don't call this directly.
     *
     * @since 1.0.0
     *
     * @param \WP_Query $query
     */
    public function delegateQueryFilterCallback(\WP_Query $query)
    {
        $this->injectDataIntoController(array(
            'query'   => $query,
            'filters' => $this->filters,
            'args'    => $this->args,
        ));
        $this->dispatchToController();

        /**
         * After query has been filtered
         *
         * @since 1.0.0
         *
         * @param \WP_Query $wpQuery The current query instance.
         */
        do_action_ref_array('qibla_listings_after_filter_query_arguments', array($query));

        // And remember that we want only the published posts.
        $query->set('post_status', 'publish');
        // Add additional query vars to remember that this query is a filtered one.
        $query->set('dl_filtered', true);
        // Parse them and done.
        $query->parse_query_vars();

        remove_action('pre_get_posts', array($this, 'preGetPostsCb'), 20);
    }

    /**
     * Delegate the Build Json
     *
     * Hook 'wp' to retrieve the query posts.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function delegateJsonBuilding()
    {
        add_action('wp', array($this, 'delegateJsonBuildingCallback'), 20);
    }

    /**
     * Callback for delegateJsonBuilding
     *
     * It's a separate method because we need to execute the action once.
     * Please I know, but don't call this directly.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function delegateJsonBuildingCallback()
    {
        $query = F\getWpQuery();

        if (! $query->get('dl_filtered')) {
            return;
        }

        $this->injectDataIntoController(array(
            'builder'             => $this->jsonBuilder,
            'auto_update_filters' => $this->autoUpdateFilters,
            'query'               => $query,
        ));
        $this->dispatchToController();
    }

    /**
     * Set Auto Update Filters
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function setAutoupdateFilters()
    {
        // @codingStandardsIgnoreStart
        $this->autoUpdateFilters = (array)F\filterInput(
            $_POST,
            'auto_update_filters',
            FILTER_SANITIZE_STRING,
            FILTER_REQUIRE_ARRAY
        ) ?: array();
        // @codingStandardsIgnoreEnd

        // Keep only the keys.
        $this->autoUpdateFilters = array_keys($this->autoUpdateFilters);
    }
}
