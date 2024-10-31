<?php
/**
 * Filters JSON Builder
 *
 * @since      1.0.0
 * @package    QiblaEvents\Front\Filter
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

namespace QiblaEvents\Filter;

use QiblaEvents\Functions as F;
use QiblaEvents\Listings\ListingLocation;
use QiblaEvents\Listings\ListingsPost;
use QiblaEvents\Listings\PlainObject;
use QiblaEvents\Plugin;

/**
 * Class JsonBuilder
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class JsonBuilder
{
    /**
     * Json
     *
     * @since  1.0.0
     *
     * @var array An array with the minimum properties needed to build the json
     */
    protected $json;

    /**
     * Constructor
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->json = array();
    }

    /**
     * Prepare the Json
     *
     * Prepare the json object to include only the necessary data from the query object.
     *
     * @since  1.0.0
     *
     * @param \WP_Query $query The query from which retrieve the posts to build map data
     *
     * @return JsonBuilder For chaining
     */
    public function prepare(\WP_Query $query)
    {
        $this->json['foundPosts']['number'] = $query->found_posts;

        while ($query->have_posts()) :
            $query->the_post();
            // Get the Post.
            $_post    = new PlainObject(new ListingsPost(get_post()));
            $location = new ListingLocation(get_post());

            // Location must be valid, empty or malformed data cause issues within the frontend.
            // In case of a invalid location just skip the listings.
            if (! $location->isValidLocation()) {
                continue;
            }

            // Retrieve the plain object representation of the listings.
            $props = $_post->object();

            // Initialize the postHtml data.
            $props->postHtml = '';

            ob_start();
            load_template(Plugin::getPluginDirPath('/views/loop/events.php'), false);
            // Include the template for the current post.
            $props->postHtml = ob_get_clean();
            // Minify the string.
            $props->postHtml = str_replace(array("\n", "\t", "\n\r"), '', $props->postHtml);
            // Normalize spaces.
            $props->postHtml = preg_replace('/\s+/', ' ', $props->postHtml);

            // Store the properties into the json.
            $this->json['posts'][] = $props;
        endwhile;

        wp_reset_postdata();

        return $this;
    }

    /**
     * Include No Content Found Template
     *
     * @since 1.0.0
     *
     * @return JsonBuilder $this For concatenation
     */
    public function includeNoContentFoundTemplate()
    {
        // No posts founds, let's get the no content template.
        if (empty($this->json['posts'])) {
            ob_start();
            load_template(Plugin::getPluginDirPath('/views/noContentListings.php'), false);
            $this->json['noContentFoundTemplate'] = ob_get_clean();
        }

        return $this;
    }

    /**
     * Include Found Posts Data
     *
     * @since 1.0.0
     *
     * @return JsonBuilder $this For concatenation
     */
    public function includeFoundPostsData()
    {
        // Get the found posts info.
        $this->json['foundPosts'] = F\getFoundPosts();

        return $this;
    }

    /**
     * Include Archive Description Data
     *
     * @since 1.0.0
     *
     * @return JsonBuilder $this For concatenation
     */
    public function includeArchiveDescriptionData()
    {
        // Get the found posts info.
        $this->json['archiveDescription'] = F\getArchiveDescription();

        return $this;
    }

    /**
     * Include Pagination
     *
     * @since 1.0.0
     *
     * @return JsonBuilder $this For concatenation
     */
    public function includePagination()
    {
        // Get the Pagination.
        ob_start();
        F\archivePaginationTmpl();
        $this->json['pagination']['markup'] = ob_get_clean();

        return $this;
    }

    /**
     * Include Auto Update Filters
     *
     * @since 1.0.0
     *
     * @param array $filters The filters from which retrieve the markup.
     *
     * @return $this For concatenation
     */
    public function includeAutoupdateFilters(array $filters)
    {
        // Always initialize variables.
        $this->json['autoUpdateFilters'] = array();

        if (empty($filters)) {
            return $this;
        }

        foreach ($filters as $filter) {
            $key           = sanitize_key($filter);
            $filterFactory = new FilterFactory(new ListingsFiltersFields());
            $filter        = $filterFactory->create($key);

            if ($filter) {
                $this->json['autoUpdateFilters'][$key] = $filter->field()->getHtml();
            }
        }

        return $this;
    }

    /**
     * Get Json Data
     *
     * @since  1.0.0
     *
     * @return array The json data
     */
    public function json()
    {
        return $this->json;
    }
}
