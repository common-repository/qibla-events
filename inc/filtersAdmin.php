<?php
/**
 * Admin Filters
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

use Unprefix\Scripts\ScriptsFacade;
use Unprefix\Scripts\LocalizeScripts;
use QiblaEvents\Plugin;
use QiblaEvents\Requirements;
use QiblaEvents\Admin;
use QiblaEvents\Autocomplete;

// Build the requirements.
$req = new Requirements(include Plugin::getPluginDirPath('/inc/requirements.php'));
// Scripts and Styles.
$scripts          = new ScriptsFacade(include Plugin::getPluginDirPath('/inc/scripts.php'));

/**
 * Filter Admin Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('qibla_filters_admin', array(
    'admin' => array(
        'action' => array(
            /**
             * TGMRegister
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'tgmpa_register',
                'callback' => array(new Admin\TGMRegister(), 'registerRequiredPlugins'),
                'priority' => 20,
            ),
            /**
             * Autocomplete Handler
             *
             * @since 1.0.0
             */
            array(
                'filter'   => array(
                    'save_post_listings',
                    'after_delete_post',
                    'delete_event_categories',
                    'edit_event_categories',
                    'update_option_rewrite_rules',
                ),
                'callback' => array(
                    new Autocomplete\CacheHandler(
                        new Autocomplete\CacheTransient(),
                        'events',
                        array(
                            'save_post_listings',
                            'after_delete_post',
                            'delete_event_categories',
                            'edit_event_categories',
                            'update_option_rewrite_rules',
                        ),
                        array(
                            'taxonomies' => 'event_categories',
                        ),
                        'event_categories'
                    ),
                    'updateCachedDataOnPostInsert',
                ),
                'priority' => 20,
            ),

            /**
             * Notices
             *
             * Requirements Notices  @since 1.0.0
             * Listings Meta Updater @since 1.0.0
             */
            array(
                'filter'   => 'admin_notices',
                'callback' => array(
                    new Admin\Notice\NoticeList(
                        '<strong>' . esc_html__('Qibla Events Warning', 'qibla-events') . '</strong>',
                        $req->check(),
                        'error'
                    ),
                    'notice',
                ),
                'priority' => 20,
            ),
            array(
                'filter'   => 'admin_notices',
                'callback' => 'QiblaEvents\\Notice\\NoticeUpdateMetaListings::noticeFilter',
                'priority' => 10,
            ),

            /**
             * Register/Enqueue/Localize Scripts
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_enqueue_scripts',
                'callback' => array($scripts, 'register'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'init',
                'callback' => function () {
                    LocalizeScripts::lazyLocalize('/inc/localizedScriptsList.php', 'admin_enqueue_scripts');
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'admin_enqueue_scripts',
                'callback' => array($scripts, 'enqueuer'),
                'priority' => 30,
            ),

            /**
             * Settings
             *
             * - Permalink Settings @since 1.0.0
             * - Plugin Option       @since 1.0.0
             */
            array(
                'filter'   => 'admin_init',
                'callback' => 'QiblaEvents\\Admin\\PermalinkSettings::permalinkSettingsFilter',
                // Set to 0 so we can update the permalinks before taxonomies and post type are registered.
                'priority' => 0,
            ),
            array(
                'filter'   => 'wp_loaded',
                'callback' => array(
                    'QiblaEvents\\Admin\\Settings\\Controller',
                    'initializeControllerFilterCallback',
                ),
                'priority' => 20,
            ),

            /**
             * Menu Pages
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_menu',
                'callback' => array(
                    new Admin\Page\Register(array(
                        new Admin\Page\Settings(),
                        new Admin\Page\ListingsPage(),
                        new Admin\Page\ListingsAddPage(),
                        new Admin\Page\ListingsLocationsPage(),
                        new Admin\Page\ListingsAmenitiesPage(),
                        new Admin\Page\ListingsCategoriesPage(),
                        new Admin\Page\PluginOptions(),
                        new Admin\Page\UsefulLinks(),
                    )),
                    'register',
                ),
                'priority' => 20,
            ),

            /**
             * Meta-boxes
             *
             * Add Standard Meta-boxes @since 1.0.0
             * Add Comments Meta-boxes @since 1.0.0
             * Store Meta-boxes        @since 1.0.0
             * Store Comments Meta     @since 1.0.0
             */
            array(
                'filter'   => 'add_meta_boxes',
                'callback' => function () {
                    $register = new  Admin\Metabox\Register(array(
                        new Admin\Metabox\Listings(),
                    ));
                    $register->register();
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'add_meta_boxes_comment',
                'callback' => function ($comment) {
                    $types = new \QiblaEvents\ListingsContext\Types();
                    $type  = $types->isListingsType(get_post_type(get_comment($commentID)->comment_post_ID));

                    if ($type) {
                        $register = new Admin\Metabox\Register(array(
                            new Admin\Metabox\Review($comment),
                        ));
                        $register->register();
                    }
                },
                'priority' => 20,
            ),
            array(
                'filter'        => 'save_post',
                'callback'      => function ($postID, $post, $update) {
                    Admin\Metabox\Store::storeMetaFilter(array(
                        new Admin\Metabox\Listings(),
                    ), $postID, $post, $update);
                },
                'priority'      => 20,
                'accepted_args' => 3,
            ),
            array(
                'filter'        => 'edit_comment',
                'callback'      => function ($commentID, $data) {
                    $types = new \QiblaEvents\ListingsContext\Types();
                    $type  = $types->isListingsType(get_post_type(get_comment($commentID)->comment_post_ID));

                    if ($type) {
                        Admin\Metabox\StoreComments::storeMetaFilter(array(
                            new Admin\Metabox\Review(get_comment($commentID)),
                        ), $commentID, $data);
                    }
                },
                'priority'      => 20,
                'accepted_args' => 2,
            ),

            /**
             * Add Taxonomy Custom Fields
             *
             * - Term Meta Color   @since 1.0.0
             */
            array(
                'filter'   => array(
                    'event_categories_add_form_fields',

                    'event_categories_edit_form_fields',
                ),
                'callback' => function () {
                    $register = new Admin\Termbox\Register(array(
                        new Admin\Termbox\Color(),
                    ));
                    $register->register();
                },
                'priority' => 10,
            ),

            /**
             * Add Taxonomy Custom Fields
             *
             * - Category           @since 1.0.0
             * - Post Tags          @since 1.0.0
             * - Locations          @since 1.0.0
             * - Listing Categories @since 1.0.0
             * - Amenities          @since 1.0.0
             * - Product Categories @since 1.0.0
             * - Product Tags       @since 1.0.0
             */
            array(
                'filter'   => array(
                    'locations_add_form_fields',
                    'event_categories_add_form_fields',
                    'tags_add_form_fields',

                    'locations_edit_form_fields',
                    'event_categories_edit_form_fields',
                    'tags_edit_form_fields',
                ),
                'callback' => function () {
                    $register = new Admin\Termbox\Register(array(
                        new Admin\Termbox\Icon(),
                        new Admin\Termbox\TaxonomyRelation(),
                    ));
                    $register->register();
                },
                'priority' => 20,
            ),

            /**
             * Term Boxes
             *
             * - Store Term-boxes   @since 1.0.0
             * - Created Term store behavior @since 1.0.0
             */
            array(
                'filter'        => array(
                    'created_term',
                    'edit_term',
                ),
                'callback'      => function ($term_id, $tt_id, $taxonomy) {
                    Admin\Termbox\Store::storeMetaFilter(array(
                        new Admin\Termbox\Color(),
                        new Admin\Termbox\Icon(),
                        new Admin\Termbox\TaxonomyRelation(),
                    ), $term_id, $tt_id, $taxonomy);
                },
                'priority'      => 20,
                'accepted_args' => 3,
            ),

            /**
             * Set parent term
             *
             * Flag the parent term when a child term is saved.
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'set_object_terms',
                'callback'      => 'QiblaEvents\\Functions\\setParentTerm',
                'priority'      => 20,
                'accepted_args' => 6,
            ),

            /**
             * Event Date Meta box
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'add_meta_boxes',
                'callback' => function () {
                    $register = new Admin\Metabox\Register(array(
                        new Admin\Metabox\EventDates(),
                    ));
                    $register->register();
                },
                'priority' => 30,
            ),
            /**
             * Store Start/End Event Dates
             *
             * @since 1.0.0
             */
            array(
                'filter'        => 'qibla_events_metabox_after_store_meta',
                'callback'      => 'QiblaEvents\\Admin\\Metabox\\EventDates::filterEventsStoreFilters',
                'priority'      => 20,
                'accepted_args' => 5,
            ),
            array(
                'filter'        => 'save_post',
                'callback'      => function ($postID, $post, $update) {
                    Admin\Metabox\Store::storeMetaFilter(array(
                        new Admin\Metabox\EventDates(),
                    ), $postID, $post, $update);
                },
                'priority'      => 30,
                'accepted_args' => 3,
            ),
        ),
        'filter' => array(
            /**
             * Admin Body Classes
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'admin_body_class',
                'callback' => 'QiblaEvents\\Functions\\adminBodyClass',
                'priority' => 20,
            ),
        ),
    ),
));
