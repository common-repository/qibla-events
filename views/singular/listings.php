<?php
/**
 * Single Listings Template
 *
 * @since   1.0.0
 *
 * @license GNU General Public License, version 2
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
use QiblaEvents\Plugin;

get_header('events'); ?>

<?php
if (! post_password_required()) : ?>
    <div <?php F\scopeID('content') ?> <?php F\scopeClass('wrapper') ?>>

        <?php
        /**
         * Before single content
         *
         * @since 1.0.0
         */
        do_action('qibla_events_single_listings_content'); ?>

        <div <?php F\scopeClass('container', '', 'flex') ?>>

            <?php
            /**
             * Before single
             *
             * @since 1.0.0
             */
            do_action('qibla_events_before_single_listings'); ?>

            <?php while (have_posts()) :
                the_post(); ?>
                <main <?php F\scopeID('main') ?> <?php F\scopeClass('post', '', 'events') ?>>
                    <?php load_template(
                        Plugin::getPluginDirPath('/views/singular/listingsArticle.php'),
                        false
                    ); ?>
                </main>
            <?php endwhile; ?>

            <?php
            // Listings Sidebar.
            $sidebar = 'qibla-sidebar-listings'; ?>
            <div <?php F\scopeID('sidebar-listings') ?> <?php F\sidebarClass('sidebar', '', 'events') ?>>
                <div class="dlsidebar-wrapper">
                    <?php
                    /**
                     * Before Listings Sidebar
                     *
                     * @since 1.0.0
                     */
                    do_action('qibla_events_listings_sidebar');

                    if (is_active_sidebar($sidebar)) :
                        dynamic_sidebar($sidebar);
                    else :
                        /**
                         * Default sidebar content
                         *
                         * This function is fired if there is no default sidebar active
                         *
                         * @since 1.0.0
                         *
                         * @param string $sidebar The name of the default sidebar
                         */
                        do_action('qibla_events_listings_sidebar_content', $sidebar);
                    endif; ?>
                </div>
            </div>

            <?php
            /**
             * After single
             *
             * @since 1.0.0
             */
            do_action('qibla_events_after_single_listings'); ?>

        </div>

        <?php
        /**
         * After single content
         *
         * @since 1.0.0
         */
        do_action('qibla_events_after_single_listings_content'); ?>

    </div>
<?php
else :
    echo get_the_password_form();
endif; ?>

<?php get_footer('events') ?>
