<?php
/**
 * ListingsDataGenerator
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Autocomplete
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    GNU General Public License, version 2
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

namespace QiblaEvents\Autocomplete;

use QiblaEvents\Collection;
use \QiblaEvents\Functions as F;
use QiblaEvents\IconsSet\Icon;
use QiblaEvents\Listings\ListingsPost;

/**
 * Class ListingsDataGenerator
 *
 * Every element within the data is set as follow:
 *
 *  label : {
 *      // Used as label for the type of the item.
 *      // Does not have any meaning. Don't use it as value to identify the type of the item.
 *      // Use the 'type' for that.
 *      label: 'Label Type',
 *
 *      // The name of the item
 *      name: 'Name of the Item',
 *
 *      // The item slug
 *      slug: 'slug-of-the-item',
 *
 *      // The Item type
 *      type: post|term,
 *
 *      // Taxonomy if type is term
 *      taxonomy: taxonomy,
 *
 *      // Post Type if type is post
 *      post_type: post_type,
 *
 *      // Permalink
 *      permalink: url,
 *
 *      // Icon
 *      icon: array
 *  }
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Autocomplete
 */
final class Generator implements GeneratorInterface
{
    /**
     * Data
     *
     * @since 1.0.0
     *
     * @var Collection The data object
     */
    private $data;

    /**
     * Terms
     *
     * @since 1.0.0
     *
     * @var array The list of the terms
     */
    private $terms;

    /**
     * Posts
     *
     * @since 1.0.0
     *
     * @var array The list of the posts
     */
    private $posts;

    /**
     * Query
     *
     * @since 1.0.0
     *
     * @var \WP_Query A query from which retrieve the posts
     */
    private $query;

    /**
     * Containers
     *
     * @since 1.0.0
     *
     * @var array A list of the container from which get additional data, such as terms or post meta.
     */
    private $containers;

    /**
     * Initial Containers to use
     *
     * @since 1.0.0
     *
     * @var string The container to use before show the autocomplete suggestions
     */
    private $initialContainerToUse;

    /**
     * ListingsDataGenerator constructor
     *
     * @since 1.0.0
     *
     * @throws \InvalidArgumentException If type hint isn't match for parameters.
     *
     * @param \WP_Query $query                 The query from which extract the posts.
     * @param array     $containers            The containers from which extract additional data based on posts.
     * @param string    $initialContainerToUse The container to use as first suggestion list.
     */
    public function __construct($query, $containers = array(), $initialContainerToUse = '')
    {
        if (! is_string($initialContainerToUse)) {
            throw new \InvalidArgumentException('Initial container if passed must be a string.');
        }

        $this->query                 = $query;
        $this->containers            = $containers;
        $this->initialContainerToUse = $initialContainerToUse;
        $this->data                  = null;
        $this->posts                 = array();
        $this->terms                 = array();
    }

    /**
     * @inheritdoc
     */
    public function prepare()
    {
        // Through the posts to build the structured data.
        foreach ($this->query->posts as $post) :
            $listings = new ListingsPost($post);
            $icon     = $listings->icon();
            $slug     = sanitize_title($post->post_title);

            // Set the post data.
            empty($this->posts[$slug]) and $this->posts[$slug] = (object)array(
                'name'      => esc_html(sanitize_text_field($post->post_title)),
                'slug'      => $slug,
                'ID'        => (int)$post->ID,
                'type'      => 'post',
                'post_type' => $post->post_type,
                'permalink' => esc_url_raw(wp_make_link_relative(get_permalink($post->ID))),
                'icon'      => $icon,
            );
        endforeach;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $data = array();

        // Set the suggestions.
        $data['suggestions'] = $this->posts + $this->terms;

        // Include the initial suggestions to show.
        if ($this->initialContainerToUse) {
            foreach ($this->terms as $item) {
                if ($this->initialContainerToUse === $item->taxonomy) {
                    $data['initial'][sanitize_key($item->slug)] = $item;
                }
            }
        }

        $this->data = new Collection($data);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function data($raw = false)
    {
        if ($raw) {
            return $this->data;
        }

        $data = new DataFormat($this->data);
        $data = $data->format();

        return $data;
    }

    /**
     * Set Terms
     *
     * Set the terms for every listing.
     *
     * @since  1.0.0
     *
     * @return Generator $this For concatenation
     */
    public function includeTerms()
    {
        // If no taxonomies container, nothing to do.
        // Same if there are no posts to process.
        if (! isset($this->containers['taxonomies']) || empty($this->posts)) {
            return $this;
        }

        if (empty($this->containers['taxonomies'])) {
            return $this;
        }

        /**
         * Filter Listings Allowed Taxonomies Filter
         *
         * @since 1.0.0
         *
         * @param array|string $taxonomies An array of taxonomies or a single one.
         */
        $taxonomiesList = apply_filters('qibla_listings_allowed_taxonomies_filter', $this->containers['taxonomies']);

        // Nothing to do if empty.
        if (! $taxonomiesList) {
            return $this;
        }

        foreach ($this->query->posts as $post) {
            foreach ((array)$taxonomiesList as $tax) {
                // Retrieve the terms.
                $terms = wp_get_post_terms($post->ID, $tax, array(
                    'field'      => 'name',
                    'hide_empty' => true,
                ));

                if (is_wp_error($terms) || ! $terms) {
                    continue;
                }

                $this->setTermsProperty($terms);
            }
        }

        return $this;
    }

    /**
     * Set Terms Property
     *
     * @since 1.0.0
     *
     * @param array $terms A list of \WP_Term object from which extract the data.
     *
     * @return void
     */
    private function setTermsProperty(array $terms)
    {
        // Retrieve the following data.
        foreach ($terms as $term) :
            // Get the icon.
            $icon = new Icon(F\getTermMeta('_qibla_tb_icon', $term), 'Lineawesome::la-check');
            // Set the term data.
            $slug = esc_html(sanitize_title($term->name));

            // Set the term.
            empty($this->terms[$slug]) and $this->terms[$slug] = (object)array(
                'name'      => sanitize_text_field($term->name),
                'slug'      => $slug,
                'id'        => intval($term->term_id),
                'type'      => 'term',
                'permalink' => esc_url_raw(wp_make_link_relative(get_term_link($term->term_id))),
                'taxonomy'  => is_array($term->taxonomy) ? $term->taxonomy[0] : $term->taxonomy,
                'icon'      => $icon->getArrayVersion(),
            );
        endforeach;
    }
}
