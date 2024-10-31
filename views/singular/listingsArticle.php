<?php
/**
 * Content Listings
 *
 * This template part should be used only for single post type pages.
 * If you need a template part for the main content, consider to include template-parts/loop.php
 *
 * @since   1.0.0
 * @package Qibla\Template
 *
 * @license GNU General Public License, version 2
 *
 *    This program is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU General Public License
 *    as published by the Free Software Foundation; either version 2
 *    of the License, or (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

use QiblaEvents\Functions as F;

/**
 * Before Post
 *
 * @since 1.0.0
 */
do_action('qibla_events_before_single_listings_loop'); ?>

<article id="post-<?php the_ID() ?>" <?php post_class(F\getScopeClass('article', '', 'events')) ?>>
    <?php
    /**
     * Before Single Loop Header
     *
     * @since 1.0.0
     */
    do_action('qibla_events_before_single_listings_header'); ?>

    <?php if ('yes' === apply_filters('qibla_show_single_listings_header', 'yes')) : ?>

        <header <?php F\scopeClass('article', 'header') ?>>

            <?php
            /**
             * Single Header
             *
             * @since 1.0.0
             */
            do_action('qibla_events_single_listings_header'); ?>

        </header>

    <?php endif; ?>

    <?php
    /**
     * Before the single loop entry
     *
     * @since 1.0.0
     */
    do_action('qibla_events_before_single_listing_loop_entry_content'); ?>

    <?php if (get_the_content()) : ?>
        <div class="<?php echo esc_attr(F\sanitizeHtmlClass(F\getScopeClass('article', 'content'))); ?> entry-content">
            <?php the_content(); ?>
        </div>
    <?php endif; ?>

    <?php
    /**
     * After the single loop entry
     *
     * @since 1.0.0
     */
    do_action('qibla_events_after_single_listings_loop_entry_content'); ?>
</article>

<?php
/**
 * After Post
 *
 * @since 1.0.0
 */
do_action('qibla_events_after_single_listings_loop'); ?>
