<?php
/**
 * RequestSearchController
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

namespace QiblaEvents\Search\Request;

use QiblaEvents\Filter\Repository;
use QiblaEvents\Request\AbstractRequestController;
use QiblaEvents\Request\Redirect;

/**
 * Class RequestSearchController
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestSearchController extends AbstractRequestController
{
    /**
     * @inheritDoc
     */
    public function handle()
    {
        if (isset($this->data['context']->type)) :
            // Retrieve the type of the request.
            $type = filter_var($this->data['context']->type, FILTER_SANITIZE_STRING);

            switch ($type) :
                case 'post':
                    $this->handlePost();

                    // In case the redirect is not performed, return, consider it as simple search.
                    return;
                    break;

                case 'term':
                    add_action('pre_get_posts', array($this, 'resetQuerySearch'));
                    break;

                default:
                    // Nothing to do by default, Wp will handle the request based on POST parameters.
                    return;
                    break;
            endswitch;
        endif;

        // Filter the query based on term if in REQUEST.
        if ('' !== $this->data['term']) {
            add_action('pre_get_posts', array($this, 'filterTaxonomyTerm'));
        }
    }

    /**
     * Filter Taxonomy Term
     *
     * @since 1.0.0
     *
     * @param \WP_Query $query The current query to filter.
     */
    public function filterTaxonomyTerm(\WP_Query $query)
    {
        // Get the Listings Filters.
        $filters = Repository::retrieveFilters();

        // Nothing to do if the taxonomy filter isn't in the repository.
        if (! isset($filters[$this->data['filter_key']])) {
            return;
        }

        $filters[$this->data['filter_key']]->queryFilter($query, (array)$this->data['term']);

        // Remove after done.
        remove_action('pre_get_posts', array($this, 'filterTaxonomyTerm'));
    }

    /**
     * Reset Search Query Var
     *
     * @since 1.0.0
     *
     * @param \WP_Query $wpQuery The query var instance
     *
     * @return void
     */
    public function resetQuerySearch(\WP_Query $wpQuery)
    {
        // The query search must be explicitly reset.
        // If the search query is set, the search will override our query data.
        unset($wpQuery->query_vars['s']);
        $wpQuery->is_search = false;

        // Remove after done.
        remove_action('pre_get_posts', array($this, 'resetQuerySearch'));
    }

    /**
     * Handle Post Search
     *
     * @uses  Redirect::redirect() To redirect to the single post.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function handlePost()
    {
        $redirect = '';

        if (isset($this->data['context']->permalink)) {
            $redirect = filter_var($this->data['context']->permalink, FILTER_SANITIZE_URL);
            $redirect = urldecode($redirect) ?: '';
        }

        // Check if post exists.
        if (url_to_postid($redirect)) {
            // Redirect::redirect will exit.
            $redirect and Redirect::redirect($redirect);
        }
    }
}
