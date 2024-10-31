<?php
/**
 * Common Filters
 *
 * @since     1.0.0
 * @author    Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright Copyright (c) 2018, Alfio Piccione
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione <alfio.piccione@gmail.com>
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

use QiblaEvents\PostType;
use QiblaEvents\Taxonomy;
use QiblaEvents\Shortcode;

/**
 * Filter Inc Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('qibla_filters_inc', array(
    'inc' => array(
        'action' => array(
            /**
             * Add menu in admin bar
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_bar_menu',
                'callback' => function ($adminBar) {
                    if (! current_user_can('administrator')) {
                        return $adminBar;
                    }
                    $qiblaPage       = new \QiblaEvents\Admin\Page\Settings();
                    $usefulLinksPage = new \QiblaEvents\Admin\Page\UsefulLinks();
                    $qiblaPage->adminToolbar($adminBar);
                    $usefulLinksPage->adminToolbar($adminBar);
                },
                'priority' => 40,
            ),

            /**
             * Sidebar
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'widgets_init',
                'callback' => array(
                    new \QiblaEvents\Sidebars\Register(
                        include QiblaEvents\Plugin::getPluginDirPath('/inc/sidebarsList.php')
                    ),
                    'register',
                ),
                // After the one in theme.
                'priority' => 30,
            ),

            /**
             * After Setup Theme
             *
             * - Add Image Sizes      @since 1.0.0
             * - Register Shortcodes  @since 1.0.0
             */
            array(
                'filter'   => 'after_setup_theme',
                'callback' => 'QiblaEvents\\Functions\\addImageSizes',
                'priority' => 30,
            ),
            array(
                'filter'   => 'after_setup_theme',
                'callback' => array(
                    new Shortcode\Register(array(
                        new Shortcode\Events(),
                        new Shortcode\Search(),
                        new Shortcode\Button(),
                        new Shortcode\Alert(),
                        new Shortcode\Maps()
                    )),
                    'register',
                ),
                'priority' => 40,
            ),

            /*
             * Register Post Type
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'init',
                'callback' => array(
                    new PostType\Register(array(
                        new PostType\Listings(),
                    )),
                    'register',
                ),
                'priority' => 0,
            ),

            /*
             * Register Taxonomy
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'init',
                'callback' => array(
                    new Taxonomy\Register(array(
                        new Taxonomy\Locations(),
                        new Taxonomy\Tags(),
                        new Taxonomy\ListingCategories(),
                        new Taxonomy\ListingsAddress(),
                        new Taxonomy\EventDates(),
                    )),
                    'register',
                ),
                'priority' => 0,
            ),

            /**
             * Rating Average
             *
             * - delete and restore Average @since 1.0.0
             */
            array(
                'filter'   => 'wp_update_comment_count',
                'callback' => function ($postID) {
                    $average = new \QiblaEvents\Review\AverageCrud(get_post($postID));
                    $average->resetAverage();
                },
                'priority' => 20,
            ),

            /**
             * Svg Loader
             *
             * @todo Move all templats within the same file. One hook one file. Stop.
             *
             * - Front @since 1.0.0
             * - Admin @since 1.0.0
             */
            array(
                'filter'   => array(
                    'wp_footer',
                    'admin_footer',
                ),
                'callback' => 'QiblaEvents\\Functions\\svgLoaderTmpl',
                'priority' => 40,
            ),

            /**
             * Update User Roles
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'upgrader_process_complete',
                'callback'      => function ($instance, $args) {
                    if (! empty($args['plugin']) &&
                        in_array('qibla-events/index.php', (array)$args['plugin'], true) &&
                        'plugin' === $args['type'] &&
                        'update' === $args['action']
                    ) {
                        \QiblaEvents\Activate::activate();
                    }
                },
                'priority'      => 20,
                'accepted_args' => 2,
            ),
        ),
        'filter' => array(),
    ),
));
