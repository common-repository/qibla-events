<?php
/**
 * Archive Functions
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package   QiblaEvents\Functions
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

namespace QiblaEvents\Functions;

use QiblaEvents\ListingsContext\Context;
use QiblaEvents\ListingsContext\Types;
use QiblaEvents\TemplateEngine\Engine as TEngine;
use QiblaEvents\Front\Settings;

/**
 * Found Posts
 *
 * @since  1.0.0
 *
 * @return \stdClass $data The data object.
 */
function getFoundPosts()
{
    $mainQuery = getWpQuery();

    // Initialize Data.
    $data = new \stdClass();

    // Set the found posts.
    $data->number = intval($mainQuery->found_posts);
    $data->label  = esc_html__('results', 'qibla-events');

    $postsPerPage = intval($mainQuery->get('posts_per_page'));
    $paged        = intval($mainQuery->get('paged')) ?: 1;

    // The Number Of value is the number of post that we are showing into the current page.
    $data->numberOf = 0;

    if (-1 === $postsPerPage) {
        $data->numberOf = $data->number;
    } elseif (is_paged() || $data->number > $postsPerPage) {
        $data->numberOf = $postsPerPage * $paged;
        // Last page.
        if (intval($mainQuery->max_num_pages) === $paged) {
            $data->numberOf = $data->number;
        }
    } elseif ($data->number) {
        $data->numberOf = $data->number;
    }

    // Set the numbers separator.
    $data->numSeparator = esc_html_x('of', 'found-posts-separator', 'qibla-events');

    // The current page label.
    // Based on queried Object.
    $currLabel = '';

    $currObj = get_queried_object();
    if ($currObj instanceof \WP_Term) {
        $currLabel = is_array($currObj) ? $currObj[0]->name : $currObj->name;
    }

    // All depend by the ajax url for filtering.
    // @codingStandardsIgnoreLine
    $inputValue = filterInput($_POST, 'qibla_event_categories_filter', FILTER_SANITIZE_STRING) ?: false;
    if (! $currLabel && $inputValue) {
        $currLabel = ' ' . esc_html(ucfirst($inputValue));
    }

    $data->currObjLabel = $currLabel ? '<i>&#183;</i>' . $currLabel : '';

    return $data;
}

/**
 * Get Archive Description
 *
 * @since 1.0.0
 *
 * @return \stdClass $data The data object.
 */
function getArchiveDescription()
{
    $currObj = get_queried_object();
    // Initialize Data.
    $data = new \stdClass();
    // Initialized description.
    $data->description = '';

    if (isset($currObj->taxonomy)) {
        $taxonomy          = $currObj->taxonomy;
        $term              = $currObj->term_id;
        $data->description = get_term_field('description', $term, $taxonomy, 'raw');
    } else {
        $data->description = $currObj->description;
    }

    return $data;
}

/**
 * Found Posts View
 *
 * @since 1.0.0
 *
 * @return void
 */
function foundPostsTmpl()
{
    $engine = new TEngine('listings_found_posts', getFoundPosts(), 'views/foundPosts.php');
    $engine->render();
}

/**
 * Toolbar
 *
 * @since  1.0.0
 *
 * @return void
 */
function listingsToolbarTmpl()
{
    $engine = new TEngine('listings_toolbar', new \stdClass(), '/views/archive/listingsToolbar.php');
    $engine->render();
}

/**
 * Post Thumbnail Size
 *
 * @since  1.0.0
 *
 * @hooked to post_thumbnail_size
 *
 * @param string|array $size The post thumbnail size. Image size or array of width and height values
 *                           (in that order).
 *
 * @return string
 */
function postThumbnailSize($size)
{
    $types = new Types();

    // Get the post type and if not set return.
    if (! is_singular() && $types->isListingsType(get_post_type())) {
        $size = 'qibla-post-thumbnail-loop';
    }

    return $size;
}

/**
 * Get the archive Page
 *
 * Archive page are the ones like page for posts and shop.
 * All of the pages that works like archives.
 *
 * @since 1.0.0
 * @uses  get_post() To retrieve the post
 *
 * @return mixed Whatever the get_post return
 */
function getArchivePage()
{
    $post = null;

    if (is_home() && get_option('page_for_posts')) {
        $post = intval(get_option('page_for_posts'));
    } elseif (isWooCommerceActive() && isShop()) {
        $post = intval(get_option('woocommerce_shop_page_id'));
    }

    return get_post($post);
}

/**
 * Get the term archive description
 *
 * This function override the default WordPress get_the_archive_description able to get raw content.
 *
 * @see   term_description()
 *
 * @since 1.0.0
 *
 * @param \stdClass $queriedObject The object needed to get the correct archive description
 *
 * @return string The term archive description.
 */
function getTheArchiveDescription($queriedObject = null)
{
    global $wp_query;

    $page_for_posts = intval(get_option('page_for_posts'));
    $description    = '';
    $queriedObject  = $queriedObject ?: get_queried_object();

    if (is_tax() || is_tag() || is_category()) {
        $taxonomy    = $queriedObject->taxonomy;
        $term        = $queriedObject->term_id;
        $description = get_term_field('description', $term, $taxonomy, 'raw');

        // Show or not the taxonomy description if the term description is empty.
        if ('no' === apply_filters('qibla_hide_taxonomy_description', 'no')) {
            $description = empty($description) ? get_taxonomy($taxonomy)->description : $description;
        }
    } elseif (is_post_type_archive()) {
        /**
         * Filters the description for a post type archive.
         *
         * @since 1.0.1
         * @since Wp4.9.0
         *
         * @param string        $description   The post type description.
         * @param \WP_Post_Type $queriedObject The post type object.
         */
        $description = apply_filters('get_the_post_type_description', $queriedObject->description, $queriedObject);
    } elseif (is_search()) {
        $description = sprintf(
        // Translators: The %s is the i18n numbers of the posts found.
            esc_html__('We had found %s posts based on your search', 'qibla-events'),
            '<strong class="' . getScopeClass('', 'postsfound-number') . '">' .
            number_format_i18n($wp_query->found_posts) . '</strong>'
        );
    } elseif (is_home() && get_option('page_for_posts')) {
        $description = strip_shortcodes(get_post($page_for_posts)->post_content);
        $description = wp_strip_all_tags($description);
    }

    if (is_wp_error($description)) {
        $description = '';
    }

    if (! empty($description)) {
        // Apply the default WordPress filters but not 'wpautop'.
        foreach (array('wptexturize', 'convert_chars', 'shortcode_unautop') as $filter) {
            apply_filters($filter, $description);
        }
    }

    /**
     * Filter the archive description.
     *
     * @since WordPress 4.1.0
     * @since 1.0.0
     *
     * @see   get_the_archive_description()
     *
     * @param string $description Archive description to be displayed.
     */
    $description = apply_filters('get_the_archive_description', $description);

    return $description;
}

/**
 * Archive Description
 *
 * @since 1.0.0
 *
 * @return void
 */
function theArchiveDescription()
{
    // Initialize data instance.
    $data = new \stdClass();

    // Set the archive description.
    $data->description = getTheArchiveDescription();

    $engine = new TEngine('archive_description', $data, '/views/archive/description.php');
    $engine->render();
}

/**
 * Add Pagination to archive page
 *
 * @since 1.0.0
 *
 * @return \stdClass The object data.
 */
function getArchivePagination()
{
    global $wp_query;

    // Data for template.
    $data = new \stdClass();
    // Need an unlikely integer.
    $big = 999999999;

    $data->paginateArgs = array(
        'base'               => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'type'               => 'array',
        'format'             => '?paged=%#%',
        'current'            => max(1, get_query_var('paged')),
        'total'              => $wp_query->max_num_pages,
        'before_page_number' => '<span class="screen-reader-text">' . esc_html__('Page', 'qibla-events') . '</span>',
        'prev_text'          => sprintf(
            '<i class="fa fa-chevron-left" aria-hidden="true"></i>%s',
            '<span class="screen-reader-text">' . esc_html__('Previous Page', 'qibla-events') . '</span>'
        ),
        'next_text'          => sprintf(
            '%s<i class="fa fa-chevron-right" aria-hidden="true"></i>',
            '<span class="screen-reader-text">' . esc_html__('Next Page', 'qibla-events') . '</span>'
        ),
    );

    // Get the pagination markup.
    $data->list = (array)paginate_links($data->paginateArgs);

    return $data;
}

/**
 * Archive Pagination Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function archivePaginationTmpl()
{
    $data = call_user_func_array('QiblaEvents\\Functions\\getArchivePagination', func_get_args());

    if (! $data->list) {
        return;
    }

    $engine = new TEngine('archive_pagination', $data, '/views/pagination/archivePagination.php');
    $engine->render();
}

