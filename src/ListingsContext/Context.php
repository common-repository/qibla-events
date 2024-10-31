<?php
/**
 * RequestContext
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

namespace QiblaEvents\ListingsContext;

use QiblaEvents\Functions as F;

/**
 * Class RequestContext
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Context
{
    /**
     * WP_Query
     *
     * @since 1.0.0
     *
     * @var \WP_Query The query from which retrieve the context
     */
    private $query;

    /**
     * Listings Types
     *
     * @since 1.0.0
     *
     * @var \QiblaEvents\ListingsContext\Types The listings custom post types
     */
    private $types;

    /**
     * Context constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Query                             $query The query from which retrieve the context.
     * @param \QiblaEvents\ListingsContext\Types $types The listings custom post types.
     */
    public function __construct(\WP_Query $query, Types $types)
    {
        $this->query = $query;
        $this->types = $types;
    }

    /**
     * Is Listings Tax Archive
     *
     * The method try to know if the current query is for a taxonomy that is associated to a custom post type listings.
     *
     * @since 1.0.0
     *
     * @return bool True if the current query is for a taxonomy. False otherwise.
     */
    public function isListingsTaxArchive()
    {
        // Initialize return value.
        $bool = false;

        // Is a tax archive?
        if ($this->query->is_tax()) {
            // Get the list of the taxonomies associated to the listings post type.
            $list = $this->listingsTaxonomies();

            // Try to find if one of the taxonomies retrieve is in the query.
            foreach ($list as $item) {
                if ($this->query->is_tax($item)) {
                    // Stop on match.
                    $bool = true;
                    break;
                }
            }
        }

        return $bool;
    }

    /**
     * Is Listings Archive
     *
     * @since 1.0.0
     *
     * @return bool True if listings archive false otherwise
     */
    public function isListingsArchive()
    {
        $bool = $this->isListingsTypeArchive() ||
                $this->isListingsTaxArchive() ||
                $this->isListingsSearch();

        /**
         * Filter Is Listings Archive
         *
         * @since 1.0.0
         *
         * @param bool $bool True if is listings archive, false otherwise.
         */
        $bool = apply_filters('qibla_listings_is_archive_listings', $bool);

        return $bool;
    }

    /**
     * Is Listings Type Archive
     *
     * @since 1.0.0
     *
     * @return bool True if the current query is for an archive post type listings, false otherwise.
     */
    public function isListingsTypeArchive()
    {
        // Initialize the return value.
        $bool = false;

        // Is a post type archive?
        if ($this->query->is_post_type_archive()) {
            // Try to know if it's the post type archive of a listings post type.
            foreach ($this->types->types() as $item) {
                if ($this->query->is_post_type_archive($item)) {
                    // Stop on match.
                    $bool = true;
                    break;
                }
            }
        }

        return $bool;
    }

    /**
     * Is Listings Search
     *
     * @since 1.0.0
     *
     * @return bool True if the current query is for search and the context is set for post type listings. False
     *              otherwise.
     */
    public function isListingsSearch()
    {
        $query = F\getWpQuery();

        return $query->is_search() && $this->context();
    }

    /**
     * Listing Type From Taxonomy
     *
     * The method try to retrieve the first object associated to the taxonomy.
     * Right now the relation is one to one. One post type associated to one Taxonomy.
     *
     * @since 1.0.0
     *
     * @param $tax
     *
     * @return string The listing post type associated to the taxonomy or empty string if the taxonomy doesn't exists.
     */
    public function listingTypeFromTax($tax)
    {
        // Get the taxonomy object in order to retrieve the object_type.
        $tax = get_taxonomy($tax);

        // Means the taxonomy doesn't exists.
        if (! $tax) {
            return '';
        }

        return reset($tax->object_type);
    }

    /**
     * Listings Taxonomies
     *
     * This method return not just taxonomies list but the list of the taxonomies associated to a specific
     * listings post type. We pass the tax in order to retrieve the object connected to that taxonomy that allow us
     * to retrieved all other taxonomies associated to the post type.
     *
     * @since 1.0.0
     *
     * @param string $tax The taxonomy from which retrieve the taxonomies connected to a listings post type.
     *
     * @return array A list of taxonomies associated to a specific post type listings
     */
    public function listingsTaxonomies($tax = '')
    {
        // Initialize return value.
        $list = array();
        // Retrieve the taxonomy if set or from the current query.
        $tax = $tax ?: $this->query->get('taxonomy');

        if (! $tax) {
            return array();
        }

        // Get the post type associated to the taxonomy.
        $postType = $this->listingTypeFromTax($tax);

        // Before trying to get the taxonomies be sure the post type is one of the listings types.
        // Otherwise wrong taxonomies may returned.
        if ($postType && $this->types->isListingsType($postType)) {
            $list = get_object_taxonomies($postType);
        }

        return $list;
    }

    /**
     * Retrieve the context
     *
     * @since 1.0.0
     *
     * @return string The post type for the request to handle
     */
    public function context()
    {
        // Context come from the Request.
        // @codingStandardsIgnoreStart
        if (isset($_REQUEST['post_type'])) {
            // Set the post type for which retrieve the posts.
            return F\filterInput($_REQUEST, 'post_type', FILTER_SANITIZE_STRING);
            // @codingStandardsIgnoreEnd
        }

        // If there is a post type listings in current query.
        if ($this->types->isListingsType($this->query->get('post_type'))) {
            return $this->query->get('post_type');
        }

        // Is Post Type Archive or Is Singular Post Type.
        if ($this->isListingsTypeArchive()) {
            return $this->query->get('post_type');
        }

        // Is Taxonomy Archive related to the listings post type.
        if ($this->isListingsTaxArchive()) {
            return $this->listingTypeFromTax($this->query->get('taxonomy'));
        }
    }

    /**
     * Is Single Listings
     *
     * @since 1.0.0
     *
     * @return bool True if singular listings false otherwise.
     */
    public static function isSingleListings()
    {
        $instance = new self(F\getWpQuery(), new Types());

        return is_singular($instance->types->types());
    }
}
