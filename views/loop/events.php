<?php
/**
 * Main loop events
 *
 * @since      1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

use QiblaEvents\Functions as F;

// Get the excerpt or post content.
$postContent = has_excerpt() ? get_the_excerpt() : get_the_content();
$postContent = strip_shortcodes(wp_trim_words($postContent, 15));
$button = F\getPostMeta('_qibla_mb_button_url');
$class = $button ? 'has-button' : '';

/**
 * Show post content in main loop
 *
 * @since 1.0.0
 *
 * @param string 'yes' to show the content. 'no' otherwise. Default 'yes'.
 */
$showPostContent = apply_filters('qibla_show_entry_listings_loop_content', 'yes');
// The post.
$post = get_post();

/**
 * Before Post
 *
 * @since 1.0.0
 */
do_action('qibla_before_listings_events_loop'); ?>

<article id="post-<?php the_ID() ?>"
    <?php post_class(F\getScopeClass('article', '', array('events', 'zoom', 'overlay', 'card', $class))) ?>
         data-marker="<?php echo esc_html($post->post_name) ?>">

    <div <?php F\scopeClass('article-card-box') ?>>
        <?php
        /**
         * Before Loop Header
         *
         * @since 1.0.0
         * @since 1.0.0 Introduce $post parameter
         *
         * @param \WP_Post The current post.
         */
        do_action('qibla_before_listings_events_loop_header', $post); ?>

        <header <?php F\scopeClass('article', 'header') ?>>

            <a <?php F\scopeClass('article', 'link') ?> href="<?php echo esc_url(get_permalink()) ?>">
                <?php
                /**
                 * Loop Header
                 *
                 * @since 1.0.0
                 * @since 1.0.0 Introduce $post parameter
                 *
                 * @param \WP_Post The current post.
                 */
                do_action('qibla_listings_events_loop_header', $post); ?>
            </a>

            <?php
            /**
             * Loop Header After
             *
             * @since 1.0.0
             * @since 1.0.0 Introduce $post parameter
             *
             * @param \WP_Post The current post.
             */
            do_action('qibla_listings_events_loop_header_after', $post); ?>

        </header>

        <a <?php F\scopeClass('article', 'link') ?> href="<?php echo esc_url(get_permalink()) ?>">
            <?php
            /**
             * Loop entry Content
             *
             * @since 1.0.0
             * @since 1.0.0 Introduce $post parameter
             *
             * @param \WP_Post The current post.
             */
            do_action('qibla_listings_events_loop_entry_content', $post); ?>
        </a>
    </div>
</article>

<?php
/**
 * After Post
 *
 * @since 1.0.0
 * @since 1.0.0 Introduce $post parameter
 *
 * @param \WP_Post The current post.
 */
do_action('qibla_after_listings_events__loop', $post); ?>
