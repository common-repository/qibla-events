<?php
/**
 * FrontEnd Filters
 *
 * @todo      Improve the number of object created. May be put all filters within a function and create there the
 *            object?
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
use QiblaEvents\Front;
use QiblaEvents\LoginRegister\Register\RegisterFormFacade;
use QiblaEvents\LoginRegister\Login\LoginFormFacade;
use QiblaEvents\LoginRegister\LostPassword\LostPasswordFormFacade;
use QiblaEvents\VisualComposer;
use QiblaEvents\Review\AverageRating;
use QiblaEvents\ListingsContext\Context;
use QiblaEvents\ListingsContext\Types;
use QiblaEvents\Post;

// Custom Fields Classes.
$singularHeaderMeta = new Front\CustomFields\Header();

// Scripts and Styles.
$scripts           = new ScriptsFacade(include Plugin::getPluginDirPath('/inc/scripts.php'));
$deregisterScripts = new ScriptsFacade(include Plugin::getPluginDirPath('/inc/deScriptsList.php'));

$galleryMeta      = new Front\CustomFields\Gallery();
/**
 * Filter Front Filters
 *
 * @since 1.0.0
 *
 * @param array $array The list of the filters list
 */
return apply_filters('qibla_filters_front', array(
    'front' => array(
        'action' => array(
            /**
             * Template Redirect
             *
             *  Set Last Viewed Cookie @since 1.0.0
             */
            array(
                'filter'   => 'template_redirect',
                'callback' => 'QiblaEvents\\Functions\\setViewedCookie',
                'priority' => 20,
            ),

            /**
             * Requests
             *
             * - Search                     @since 1.0.0
             * -  Filter Request By Geocode @since 1.0.0
             */
            array(
                'filter'   => 'init',
                'callback' => 'QiblaEvents\\Search\\Request\\RequestSearch::handleRequestFilter',
                'priority' => 10,
            ),
            array(
                'filter'   => 'init',
                'callback' => 'QiblaEvents\\Geo\\Request\\RequestByGeocodedAddress::handleRequestFilter',
                'priority' => 20,
            ),

            /**
             * Store
             *
             * - Review @since 1.0.0
             */
            array(
                'filter'        => 'comment_post',
                'callback'      => array(
                    'QiblaEvents\\Review\\ReviewFieldsStore',
                    'reviewFieldsStoreFilter',
                ),
                'priority'      => 20,
                'accepted_args' => 3,
            ),

            /**
             * Enqueue Scripts
             *
             * - Deregister Scripts / Style @since 1.0.0
             * - Register Scripts / Style   @since 1.0.0
             * - Lazy Localized             @since 1.0.0
             * - Enqueue Scripts / Style    @since 1.0.0
             */
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($deregisterScripts, 'deregister'),
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scripts, 'register'),
                // Leave it to 20 or horrible things will happens.
                'priority' => 20,
            ),
            array(
                'filter'   => 'init',
                'callback' => function () {
                    LocalizeScripts::lazyLocalize('/inc/lazyLocalizedScriptsList.php', 'wp_enqueue_scripts');
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'init',
                'callback' => function () {
                    LocalizeScripts::lazyLocalize('/inc/localizedScriptsList.php', 'wp_enqueue_scripts');
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'wp_enqueue_scripts',
                'callback' => array($scripts, 'enqueuer'),
                'priority' => 30,
            ),

            /**
             * Pre Get Posts
             *
             * - posts per page    @since 1.0.0
             * - order by featured @since 1.0.0
             */
            array(
                'filter'   => 'pre_get_posts',
                'callback' => 'QiblaEvents\\Front\\Settings\\Listings::postsPerPage',
                'priority' => 20,
            ),
            array(
                'filter'        => 'the_posts',
                'callback'      => 'QiblaEvents\\Front\\Settings\\Listings::orderByFeatured',
                'priority'      => 20,
                'accepted_args' => 2,
            ),

            /**
             * Archive
             *
             * - Listings Form Filters                      @since 1.0.0
             * - Listings Toolbar                           @since 1.0.0
             * - Listings Found Posts                       @since 1.0.0
             * - Listings Archive Description               @since 1.0.0
             * - Listings Google Map                        @since 1.0.0
             */
            array(
                'filter'   => 'qibla_events_before_archive_listings_list',
                'callback' => 'QiblaEvents\\Filter\\Form::formFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_before_archive_listings_list',
                'callback' => 'QiblaEvents\\Functions\\listingsToolbarTmpl',
                'priority' => 30,
            ),
            array(
                'filter'   => 'qibla_events_listings_toolbar',
                'callback' => 'QiblaEvents\\Functions\\foundPostsTmpl',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_after_archive_listings_list',
                'callback' => 'QiblaEvents\\Template\\ListingsArchiveFooter::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_after_archive_listings_list',
                'callback' => 'QiblaEvents\\Functions\\theArchiveDescription',
                'priority' => 30,
            ),
            array(
                'filter'   => 'qibla_events_before_archive_listings',
                'callback' => 'QiblaEvents\\Template\\GoogleMap::template',
                'priority' => 20,
            ),

            /**
             * Loop
             *
             * - Listings Post Thumbnail Size     @since 1.0.0
             * - Listings Post Thubmnail Template @since 1.0.0
             * - Listings Post Icon               @since 1.0.0
             * - Listings Average Rating          @since 1.0.0
             * - Listings Footer Loop Location    @since 1.0.0
             */
            array(
                'filter'   => 'post_thumbnail_size',
                'callback' => 'QiblaEvents\\Functions\\postThumbnailSize',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_events_loop_header',
                'callback' => 'QiblaEvents\\Template\\Thumbnail::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_template_engine_data_the_post_title',
                'callback' => 'QiblaEvents\\Functions\\listingsPostTitleIcon',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_events_loop_entry_content',
                'callback' => 'QiblaEvents\\Template\\Subtitle::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_before_post_title',
                'callback' => function (\stdClass $data) {
                    $post  = get_post($data->ID);
                    $types = new Types();

                    if ($types->isListingsType($post->post_type) && ! Context::isSingleListings()) {
                        \QiblaEvents\Review\AverageRating::averageRatingFilter();
                    }
                },
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_listings_events_loop_header_after',
                'callback' => 'QiblaEvents\\Front\\CustomFields\\Button::buttonFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_template_engine_data_post_loop_footer',
                'callback' => 'QiblaEvents\\Functions\\listingsLoopFooterLocation',
                'priority' => 20,
            ),

            /**
             * Loop Header
             *
             * - Listings postTitle        @since 1.0.0
             */
            array(
                'filter'   => 'qibla_listings_events_loop_header',
                'callback' => array(new Post\Title(), 'postTitleTmpl'),
                'priority' => 20,
            ),

            /**
             * Loop Entry Content
             *
             * - Loop Listings Footer  @since 1.0.0
             */
            array(
                'filter'   => 'qibla_listings_events_loop_entry_content',
                'callback' => 'QiblaEvents\\Functions\\loopFooter',
                'priority' => 20,
            ),

            /**
             * Single
             *
             * - Listings Gallery
             * - Listings Title                    @since 1.0.0
             * - Listings Terms list               @since 1.0.0
             * - Listings Sub Title                @since 1.0.0
             * - Listings Average Rating           @since 1.0.0
             * - Listings Header Subtitle          @since 1.0.0
             * - Listings Section Single Listing   @since 1.0.0
             * - Listings Review                   @since 1.0.0
             * - Listings Related Posts            @since 1.0.0
             */
            array(
                'filter'   => 'qibla_events_single_listings_header',
                'callback' => array($galleryMeta, 'template'),
                'priority' => 10,
            ),
            array(
                'filter'   => 'qibla_events_single_listings_header',
                'callback' => 'QiblaEvents\\Functions\\listingsTermsListTmpl',
                // Before the single listings title.
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_single_listings_header',
                'callback' => array(new Post\Title(), 'postTitleTmpl'),
                'priority' => 30,
            ),
            array(
                'filter'   => 'qibla_events_single_listings_header',
                'callback' => array($singularHeaderMeta, 'subtitle'),
                // After the title.
                'priority' => 40,
            ),
            array(
                'filter'   => 'qibla_events_after_post_title',
                'callback' => function () {
                    if (Context::isSingleListings()) {
                        AverageRating::averageRatingFilter();
                    }
                },
                // After the title in theme.
                'priority' => 40,
            ),
            array(
                'filter'   => 'qibla_events_single_listings_header',
                'callback' => 'QiblaEvents\\Front\\CustomFields\\EventsDates::eventsDatesFilter',
                // After single events sub title.
                'priority' => 45,
            ),
            array(
                'filter'   => 'qibla_events_after_single_listings',
                'callback' => 'QiblaEvents\\Front\\CustomFields\\EventsLocation::eventsLocationFilter',
                'priority' => 40,
            ),
            array(
                'filter'   => 'qibla_events_listings_sidebar',
                'callback' => 'QiblaEvents\\Front\\CustomFields\\EventsSidebarCard::eventsSidebarCardFilter',
                'priority' => 10,
            ),
            array(
                'filter'   => 'qibla_events_events_after_time_sidebar_card',
                'callback' => 'QiblaEvents\\Functions\\addEventCalendar',
                'priority' => 10,
            ),
            array(
                'filter'   => 'qibla_events_events_after_time_sidebar_card',
                'callback' => 'QiblaEvents\\Functions\\addEventLocation',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_events_after_time_sidebar_card',
                'callback' => 'QiblaEvents\\Functions\\addEventSiteAndTel',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_listings_sidebar',
                'callback' => 'QiblaEvents\\Front\\CustomFields\\Button::buttonFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_listings_sidebar',
                'callback' => 'QiblaEvents\\Template\\ShareAndWish::template',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_listings_sidebar',
                'callback' => 'QiblaEvents\\Front\\CustomFields\\ListingsSocials::socialLinksFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_after_single_listings_loop_entry_content',
                'callback' => 'QiblaEvents\\Template\\AmenitiesTemplate::tagsSectionFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_after_single_listings_content',
                'callback' => 'QiblaEvents\\Front\\CustomFields\\RelatedPosts::relatedPostsFilter',
                'priority' => 40,
            ),
            array(
                'filter'   => 'qibla_events_after_single_listings_loop',
                'callback' => 'QiblaEvents\\Review\\ReviewList::reviewListFilter',
                'priority' => 20,
            ),

            /**
             * Single Listings
             *
             * Disable Jumbotron within the single listing package post @since 1.0.0
             */
            array(
                'filter'   => 'qibla_events_is_jumbotron_allowed',
                'callback' => 'QiblaEvents\\Front\\ListingPackage\\SingleListingPackage::disableJumbotronWithinSingularListingPackageFilter',
                'priority' => 20,
            ),

            /**
             * Sidebar
             *
             * Single Package Listing @since 1.0.0
             */
            array(
                'filter'   => array(
                    'qibla_events_has_sidebar',
                    'qibla_events_show_sidebar',
                ),
                'callback' => 'QiblaEvents\\Sidebar::removeSidebarFromSinglePackageListingFilter',
                // After the theme and framework.
                'priority' => 40,
            ),

            /**
             * Head
             *
             * - Jumbotron customCss @since 1.0.0
             * - Jumbotron Parallax  @since 1.0.0
             * - 404 background page @since 1.0.0
             */
            array(
                'filter'   => 'wp_head',
                'callback' => array($galleryMeta, 'customCss'),
                'priority' => 20,
            ),

            /**
             * Footer
             *
             * - Listings Map Templates            @since 1.0.0
             * - Listings Togglers Templates       @since 1.0.0
             * - Listings Json Collection          @since 1.0.0
             * - Listings Form Togglers Templates  @since 1.0.0
             * - Dropzone Template                 @since 1.0.0
             * - Copyright                         @since 1.0.0
             */
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaEvents\\Functions\\mapTmpls',
                // Before the scripts are loaded.
                'priority' => 10,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaEvents\\Functions\\togglersTmpls',
                // Before the scripts are loaded.
                'priority' => 10,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaEvents\\Listings\\ListingsLocalizedScript::printScriptFilter',
                'priority' => 0,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaEvents\\Shortcode\\Alert::alertAjaxTmpl',
                'priority' => 40,
            ),
            array(
                'filter'   => 'wp_footer',
                'callback' => 'QiblaEvents\\Functions\\searchNavigationTmpl',
                'priority' => 40,
            ),

            // Calendar scripts
            array(
                'filter'   => 'wp_print_footer_scripts',
                'callback' => 'QiblaEvents\\Functions\\calendarScripts',
                'priority' => PHP_INT_MAX,
            ),
        ),
        'filter' => array(
            /**
             * Filter Wp Template for Listings Context
             *
             * This is the main hook used to load the correct template when the main context is for listings
             * post type.
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'template_include',
                'callback' => 'QiblaEvents\\ListingsContext\\TemplateIncludeFilter::templateIncludeFilterFilter',
                'priority' => 20,
            ),

            /**
             * Fix issue with shortcode unautop
             *
             * This function must be removed after https://core.trac.wordpress.org/ticket/34722 as been fixed.
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'the_content',
                'callback' => 'DLshortcodeUnautop',
                // Must be set to 10 or will not work.
                'priority' => 10,
            ),

            /**
             * Html Scope Attributes
             *
             * - Text Only Post            @since 1.0.0
             * - Featured Listings         @since 1.0.0
             */
            array(
                'filter'        => 'qibla_scope_attribute',
                'callback'      => 'QiblaEvents\\Functions\\getPostTextOnlyModifier',
                // Before the theme filter to able to remove it.
                'priority'      => 19,
                'accepted_args' => 5,
            ),
            array(
                'filter'        => array(
                    'qibla_scope_attribute',
                    'qibla_events_scope_attribute',
                ),
                'callback'      => 'QiblaEvents\\Functions\\listingsFeaturedScopeModifier',
                'priority'      => 30,
                'accepted_args' => 5,
            ),

            /**
             * Listings Data
             *
             * - Filter the listing thumbnail @since 1.0.0
             */
            array(
                'filter'   => 'qibla_events_template_engine_data_the_post_thumbnail',
                'callback' => 'QiblaEvents\\Functions\\postThumbToJumbotronData',
                'priority' => 20,
            ),

            /**
             * Single Comments
             *
             * - Disable Reviews Listings                @since 1.0.0
             * - Prevent Reply on Listings If not author @since 1.0.0
             * - Show Review Form                        @since 1.0.0
             */
            array(
                'filter'   => 'qibla_events_disable_reviews',
                'callback' => 'QiblaEvents\\Front\\Settings\\Listings::forceDisableReviews',
                'priority' => 20,
            ),
            array(
                'filter'   => 'preprocess_comment',
                'callback' => 'QiblaEvents\\Review\\ReviewReplyCommenterCheck::checkAllowedReplyFilter',
                'priority' => 20,
            ),
            array(
                'filter'   => 'qibla_events_after_comments',
                'callback' => 'QiblaEvents\\Review\\ReviewForm::reviewFormFilter',
                'priority' => 20,
            ),

            /**
             * Extras
             *
             * - Body Class @since 1.0.0
             */
            array(
                'filter'   => 'body_class',
                'callback' => 'QiblaEvents\\Functions\\bodyClass',
                'priority' => 20,
            ),

            array(
                'filter'   => 'show_admin_bar',
                'callback' => 'QiblaEvents\\User\\DisableAdminBar::hideAdminBarFilter',
                'priority' => 20,
            ),

            /**
             * Set current lang in json search factory
             *
             * @since 1.0.0
             */
            array(
                'filter'   => 'qibla_events_search_json_encoder_factory',
                'callback' => function ($args) {
                    $lang = \QiblaEvents\Functions\setCurrentLang();
                    if (\QiblaEvents\Functions\isWpMlActive() && $lang) {
                        $args['lang'] = $lang;
                    }

                    return $args;
                },
                'priority' => 10,
            ),

            array(
                'filter'        => 'qibla_extra_data_in_listings_post',
                'callback'      => function ($data, $post) {

                    if ('events' === $post->post_type) {
                        // Get Date start.
                        $dateStart = \QiblaEvents\Functions\getPostMeta(
                            '_qibla_mb_event_dates_multidatespicker_start',
                            ''
                        );
                        if ($dateStart) {
                            $date            = new \DateTime($dateStart);
                            $data->date = date_i18n('l d F', intval($date->getTimestamp())) ?: '';
                        }
                    }

                    return $data;
                },
                'priority'      => 30,
                'accepted_args' => 2,
            ),
        ),
    ),
));
