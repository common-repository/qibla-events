<?php
/**
 * Request Filter Controller
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

use QiblaEvents\Request\AbstractRequestController;

/**
 * Class RequestFilterController
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestFilterController extends AbstractRequestController
{
    /**
     * Filter Query
     *
     * Perform the filtering of the query
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function filterQuery()
    {
        $query = $this->data['query'];

        foreach ($this->data['filters'] as $filter) {
            $filterName = $filter->name();
            // All data are optional.
            if (isset($this->data['args'][$filterName])) {
                $filter->queryFilter($query, $this->data['args'][$filterName]);
            }
        }
    }

    /**
     * Json
     *
     * Create the json data and send it.
     *
     * @since 1.0.0
     *
     * @uses  wp_send_json() To send the json data.
     *
     * @return void
     */
    private function json()
    {
        // @todo Passing directly create an unexpected fatal error - Need Further investigation.
        $dataFilter = $this->data['auto_update_filters'];

        $json = $this->data['builder']
            ->prepare($this->data['query'])
            ->includeNoContentFoundTemplate()
            ->includeFoundPostsData()
            ->includeArchiveDescriptionData()
            ->includeAutoupdateFilters($dataFilter)
            ->includePagination()
            ->json();

        // Send the newly json data :P.
        wp_send_json($json);
    }

    /**
     * @inheritDoc
     */
    public function handle()
    {
        $currFilter = current_filter();

        switch ($currFilter) {
            case 'pre_get_posts':
                $this->filterQuery();
                break;
            case 'wp':
                $this->json();
                break;
        }
    }
}
