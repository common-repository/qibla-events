<?php
/**
 * Archive Listings Template
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

get_header('archive'); ?>

<div <?php F\scopeID('content') ?> <?php F\scopeClass('wrapper') ?>>

    <?php
    /**
     * Before archive content
     *
     * @since 1.0.0
     */
    do_action('qibla_events_before_archive_listings_content'); ?>

    <div <?php F\scopeClass('container', '', 'flex') ?>>

        <?php
        /**
         * Before archive
         *
         * @since 1.0.0
         */
        do_action('qibla_events_before_archive_listings'); ?>

        <main <?php F\scopeID('main') ?> <?php F\scopeClass() ?>>

            <?php
            /**
             * Before Archive Listings List
             *
             * @since 1.0.0
             */
            do_action('qibla_events_before_archive_listings_list'); ?>

            <div <?php F\scopeClass('listings-list') ?>>

                <?php
                if (have_posts()) :
                    /**
                     * Before the archive loop
                     *
                     * @since 1.0.0
                     */
                    do_action('qibla_events_before_archive_listings_loop'); ?>

                    <div <?php F\scopeClass('grid') ?>>
                        <?php
                        while (have_posts()) :
                            the_post();
                            load_template(Plugin::getPluginDirPath('/views/loop/events.php'), false);
                        endwhile;
                        ?>
                    </div>

                    <?php
                    /**
                     * After the archive loop
                     *
                     * @since 1.0.0
                     */
                    do_action('qibla_events_after_archive_listings_loop');
                else :
                    load_template(Plugin::getPluginDirPath('/views/noContentListings.php'), false);
                endif;
                ?>

            </div>

            <?php
            /**
             * After Archive Listings List
             *
             * @since 1.0.0
             */
            do_action('qibla_events_after_archive_listings_list'); ?>

        </main>

        <?php
        /**
         * After archive
         *
         * @since 1.0.0
         */
        do_action('qibla_events_after_archive_listings'); ?>

    </div>

    <?php
    /**
     * After archive content
     *
     * @since 1.0.0
     */
    do_action('qibla_events_after_archive_listings_content'); ?>

</div>

<?php get_footer('archive') ?>
