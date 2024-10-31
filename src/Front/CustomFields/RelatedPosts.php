<?php
/**
 * Class Front-end Related Posts
 *
 * @since      1.0.0
 * @package    QiblaEvents\Front\CustomFields
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa
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

namespace QiblaEvents\Front\CustomFields;

use QiblaEvents\TemplateEngine\TemplateInterface;
use QiblaEvents\Functions as F;
use QiblaEvents\ListingsContext\Context;

/**
 * Class RelatedPosts
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class RelatedPosts extends AbstractMeta implements TemplateInterface
{
    /**
     * Initialize Object
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        // Build the meta-keys array.
        $this->meta = array(
            'show_related' => "_qibla_{$this->mbKey}_post_show_related",
            'cta_label'    => "_qibla_{$this->mbKey}_post_related_cta_label",
        );
    }

    /**
     * Get Data
     *
     * @inheritDoc
     */
    public function getData()
    {
        // Initialize data class.
        $data = new \stdClass();

        // Do nothing if the context is not a singular post type.
        if (! is_singular()) {
            return $data;
        }

        // Retrieve the meta.
        $isrelatedActive = $this->getMeta('show_related', 'on');

        if ('off' === $isrelatedActive) {
            return $data;
        }

        // Get the taxonomy term from which retrieve the posts.
        $taxName = 'post' === get_post_type() ? 'category' : 'event_categories';
        $terms   = get_the_terms(null, $taxName);

        // The getData() methods doesn't convert \WP_Error into Exceptions.
        if (! $terms || is_wp_error($terms)) {
            return $data;
        }

        $cta = array(
            'title' => '',
            'url'   => '',
            'label' => '',
        );

        // Cta is allowed only within the singular listings.
        if (Context::isSingleListings()) {
            $cta['title'] = $terms[0]->name;
            $cta['url']   = get_term_link($terms[0], $taxName);
            $cta['label'] = esc_html__('Discover all the events', 'qibla-events');

            // Retrieve the Cta Bg.
            $ctaBg = wp_get_attachment_image_url(F\getTermMeta('_qibla_tb_thumbnail', $terms[0]->term_id), 'qibla-cta');
            // Set the styles for the container.
            $cta['background-image'] = $ctaBg;
        }

        $data->cta = $cta;

        // Set the arguments for the query.
        $data->postsArgs = array(
            'post_type'             => get_post_type(),
            'posts_status'          => 'publish',
            'posts_per_page'        => 3,
            'additional_query_args' => array(
                // Exclude the current post.
                'post__not_in'        => array(get_the_ID()),
                'ignore_sticky_posts' => true,
                'orderby'             => 'meta_value_num',
                'meta_key'            => '_qibla_mb_event_dates_start_for_orderby',
                'order'               => 'ASC',
                'tax_query'           => array(
                    array(
                        'taxonomy'         => $taxName,
                        'terms'            => wp_list_pluck($terms, 'term_id'),
                        'include_children' => false,
                    ),
                ),
            ),
        );

        return $data;
    }

    /**
     * Template
     *
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        // Build the shortcode class based on post type.
        $shortcodeClass     = 'QiblaEvents\\Shortcode\\' . ucfirst(get_post_type());
        $data->relatedPosts = null;

        if (class_exists($shortcodeClass) && property_exists($data, 'postsArgs')) {
            $shortcodeClassInstance = new $shortcodeClass;
            $data->relatedPosts     = $shortcodeClassInstance->callback($data->postsArgs);
        }

        $this->loadTemplate('qibla_mb_show_events_related', $data, '/views/relatedPosts.php');
    }

    /**
     * Related Posts Filter
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function relatedPostsFilter()
    {
        $instance = new self;

        $instance->init();
        $instance->tmpl($instance->getData());
    }
}
