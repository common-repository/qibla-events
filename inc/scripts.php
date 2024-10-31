<?php
/**
 * Scripts
 *
 * @since 1.0.0
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

use QiblaEvents\Plugin;
use QiblaEvents\Functions as F;
use QiblaEvents\Front\Settings;
use QiblaEvents\ListingsContext\Context;

// Get the Environment.
$dev = ! ! ('dev' === QB_ENV);

// Get current lang.
$lang = substr(get_bloginfo('language'), 0, 2);

// Retrieve the Google Map ApiKey option.
$gmapApiKey = F\getPluginOption('google_map', 'apikey', true);

$scripts = array(
    'scripts' => array(
        'force-js' => array(
            'force-js',
            Plugin::getPluginDirUrl('/assets/js/vendor/force.min.js'),
            array(),
            $dev ? time() : '',
            true,
        ),

        /*
         * Fw BC
         */
        'dl-bw-oldbrowsers' => array(
            'dl-bw-oldbrowsers',
            Plugin::getPluginDirUrl('/assets/js/vendor/backward-oldbrowsers.min.js'),
            array('modernizr'),
            $dev ? time() : '',
            true,
        ),

        /*
         * Vendors
         *
         * - Modernizr
         * - Utils
         * - Select2
         * - Url
         */
        'modernizr'         => array(
            'modernizr',
            Plugin::getPluginDirUrl('/assets/js/vendor/modernizr.min.js'),
            array(),
            '3.3.1',
            true,
        ),
        'dl-utils'          => array(
            'dl-utils',
            Plugin::getPluginDirUrl('/assets/js/utils.js'),
            array('dl-bw-oldbrowsers', 'underscore', 'jquery'),
            '1.0.0',
            true,
        ),
        'select2'           => array(
            'select2',
            Plugin::getPluginDirUrl('/assets/js/vendor/select2.full.min.js'),
            array('jquery'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),
        'url-parser'        => array(
            'url-parser',
            Plugin::getPluginDirUrl('/assets/js/vendor/url.min.js'),
            array(),
            $dev ? time() : '',
            true,
        ),

        /*
         * Map
         *
         * - Google Map
         * - Map Type
         */
        'google-map'        => array(
            'google-map',
            // @codingStandardsIgnoreLine
            '//maps.googleapis.com/maps/api/js?v=3.29&amp;key=' . sanitize_text_field($gmapApiKey) . '&amp;libraries=places&geocoding',
            array(),
            '3.28',
            true,
            '__return_true',
            '__return_false',
        ),
        'map-type'          => array(
            'map-type',
            Plugin::getPluginDirUrl('/assets/js/types/map.js'),
            array('google-map'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),

        /*
         * Form & Form Types
         *
         * - Dropzone
         * - file Type
         * - DL Form
         */
        'dropzone'          => array(
            'dropzone',
            Plugin::getPluginDirUrl('/assets/js/vendor/dropzone.min.js'),
            array(),
            '',
            true,
            '__return_true',
            '__return_false',
        ),
        'file-type'         => array(
            'file-type',
            Plugin::getPluginDirUrl('/assets/js/types/file.js'),
            array('underscore', 'jquery', 'dropzone'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),
        'dl-form'           => array(
            'dl-form',
            Plugin::getPluginDirUrl('/assets/js/form.js'),
            array('underscore', 'jquery', 'dropzone', 'file-type'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),

        // Date Picker Scripts.
        'multidatespicker'      => array(
            'multidatespicker',
            Plugin::getPluginDirUrl('/assets/js/events/vendor/jquery-ui.multidatespicker.js'),
            array('jquery'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),

        // Multi Date Picker Scripts.
        'multidatespicker-type' => array(
            'multidatespicker-type',
            Plugin::getPluginDirUrl('/assets/js/events/types/multiDatesPicker.js'),
            array('multidatespicker'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
            array(
                'async' => 'async',
            ),
        ),

        // Date Time Picker Scripts.
        'timepicker'            => array(
            'timepicker',
            Plugin::getPluginDirUrl('/assets/js/events/vendor/jquery.timepicker.js'),
            array('jquery'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
    ),
    'styles'  => array(
        // Admin Css.
        'qibla-form-types' => array(
            'qibla-form-types',
            Plugin::getPluginDirUrl('/assets/css/form.min.css'),
            array(),
            $dev ? time() : '',
            'screen',
            '__return_true',
            '__return_false',
        ),

        // Date Picker Style.
        'multidatespicker-style' => array(
            'multidatespicker-style',
            Plugin::getPluginDirUrl('/assets/css/vendor/jquery-ui.multidatespicker.css'),
            array(),
            $dev ? time() : '',
            'screen',
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),

        // Date Time Picker Style.
        'timepicker-style'       => array(
            'timepicker-style',
            Plugin::getPluginDirUrl('/assets/css/vendor/jquery.timepicker.css'),
            array('jquery-ui'),
            $dev ? time() : '',
            'screen',
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
    ),
);

// DatePicker Language.
if (file_exists(Plugin::getPluginDirPath("/assets/js/events/datepicker-lang/datepicker-{$lang}.js"))) {

    $scripts['scripts'] = array_merge($scripts['scripts'], array(
        'datepicker-lang' => array(
            'datepicker-lang',
            Plugin::getPluginDirUrl("/assets/js/events/datepicker-lang/datepicker-{$lang}.js"),
            array('jquery', 'jquery-ui-datepicker'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
    ));
}

if (is_admin()) {
    // Push scripts.
    $scripts['scripts'] = array_merge($scripts['scripts'], array(
        // Media.
        'admin-media-type' => array(
            'admin-media-type',
            Plugin::getPluginDirUrl('/assets/js/types/media.js'),
            array(),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),

        // Date Time Picker.
        'datetimepicker'             => array(
            'datetimepicker',
            Plugin::getPluginDirUrl('/assets/js/vendor/jquery.datetimepicker.full.js'),
            array('jquery'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),

        // Meta-box Fields.
        'admin-metabox-fieldsets'    => array(
            'admin-metabox-fieldsets',
            Plugin::getPluginDirUrl('/assets/js/metabox-fieldsets.js'),
            array('underscore', 'jquery', 'select2'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_true',
        ),

        // Type Select.
        'admin-select-type'          => array(
            'admin-select-type',
            Plugin::getPluginDirUrl('/assets/js/types/select.js'),
            array('underscore', 'modernizr', 'select2'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_true',
            array(
                'async' => 'async',
            ),
        ),

        // Type Typography.
        'admin-typography-type'      => array(
            'admin-typography-type',
            Plugin::getPluginDirUrl('/assets/js/types/typography.js'),
            array(),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_true',
            array(
                'async' => 'async',
            ),
        ),

        // Type Date Time Picker.
        'datetimepicker-type'        => array(
            'datetimepicker-type',
            Plugin::getPluginDirUrl('/assets/js/types/dateTimePicker.js'),
            array('datetimepicker'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),

        // Type Media Uploader.
        'admin-mediauploader-type'   => array(
            'admin-mediauploader-type',
            Plugin::getPluginDirUrl('/assets/js/types/mediaUploader.js'),
            array('admin-media-type', 'media-gallery'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),

        // Type Color Picker.
        'admin-colorpicker-type'     => array(
            'admin-colorpicker-type',
            Plugin::getPluginDirUrl('/assets/js/types/colorPicker.js'),
            array('underscore', 'jquery', 'wp-color-picker'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),

        // Type Icon List.
        'admin-iconlist-type'        => array(
            'admin-iconlist-type',
            Plugin::getPluginDirUrl('/assets/js/types/iconList.js'),
            array('underscore'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),
    ));

    $scripts['styles'] = array_merge($scripts['styles'], array(
        // Premium.
        'qibla-events-premium'   => array(
            'qibla-events-premium',
            Plugin::getPluginDirUrl('/assets/css/premium.min.css'),
            array(),
            $dev ? time() : '',
            'all',
        ),
        // Vendor.
        'vendor'   => array(
            'vendor',
            Plugin::getPluginDirUrl('/assets/css/vendor/vendor.min.css'),
            array(),
            $dev ? time() : '',
            'screen',
        ),

        // Dropzone.
        'dropzone' => array(
            'dropzone',
            Plugin::getPluginDirUrl('/assets/css/vendor/dropzone.min.css'),
            array(),
            $dev ? time() : '',
            'screen',
            '__return_true',
            '__return_false',
        ),

        // Admin Css.
        'qibla'    => array(
            'qibla',
            Plugin::getPluginDirUrl('/assets/css/admin.min.css'),
            array('vendor', 'qibla-form-types'),
            $dev ? time() : '',
            'screen',
        ),
    ));

    /**
     * Filter Scripts
     *
     * @since 1.0.0
     *
     * @param array $scripts The array of the scripts
     */
    $scripts = apply_filters('qibla_events_admin_scripts_list', $scripts);
} else {
    // Front Scripts.
    $scripts['scripts'] = array_merge($scripts['scripts'], array(
        /*
         * UI
         *
         * - Select
         */
        'dl-select2'                         => array(
            'dl-select2',
            Plugin::getPluginDirUrl('/assets/js/front/select2.js'),
            array('modernizr', 'select2'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_true',
            array(
                'async' => 'async',
            ),
        ),

        /*
         * Listings
         *
         * - Backbone Interface
         * - Listings Location
         * - Listings Contact Form
         * - Listings Google Map
         */
        'dllistings'                         => array(
            'dllistings',
            Plugin::getPluginDirUrl('/assets/js/listings/listings.js'),
            array(
                'underscore',
                'backbone',
            ),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),
        'dllistings-contact-form'            => array(
            'dllistings-contact-form',
            Plugin::getPluginDirUrl('/assets/js/listings/listings-contactform.js'),
            array('underscore', 'dl-modal', 'dl-form'),
            $dev ? time() : '',
            true,
            function () {
                return Context::isSingleListings();
            },
            '__return_false',
        ),
        'dllistings-share-popup'            => array(
            'dllistings-share-popup',
            Plugin::getPluginDirUrl('/assets/js/front/share-popup.js'),
            array('underscore', 'jquery'),
            $dev ? time() : '',
            function () {
                return Context::isSingleListings();
            },
            function () {
                return Context::isSingleListings();
            },
        ),
        'dllistings-google-map'              => array(
            'dllistings-google-map',
            Plugin::getPluginDirUrl('/assets/js/listings/listings-gmap.js'),
            array('google-map', 'dlmap-app'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),

        /*
         * Photoswipe
         *
         * - Photoswipe
         * - UI
         * - Photoswite Wrapper
         */
        'photoswipe'                         => array(
            'photoswipe',
            Plugin::getPluginDirUrl('/assets/js/vendor/photoswipe.min.js'),
            array(),
            $dev ? time() : '',
            true,
            function () {
                return Context::isSingleListings();
            },
            '__return_false',
        ),
        'photoswipe-ui'                      => array(
            'photoswipe-ui',
            Plugin::getPluginDirUrl('/assets/js/vendor/photoswipe-ui-default.min.js'),
            array('photoswipe'),
            $dev ? time() : '',
            true,
            function () {
                return Context::isSingleListings();
            },
            '__return_false',
        ),
        'dlphotoswipe'                       => array(
            'dlphotoswipe',
            Plugin::getPluginDirUrl('/assets/js/front/photoswipe.js'),
            array('underscore', 'photoswipe-ui'),
            $dev ? time() : '',
            true,
            function () {
                return Context::isSingleListings();
            },
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),

        /*
         * Search
         * - Search Listings
         * - Autocomplete
         * - Search Autocomplete
         */
        'dlsearch-geocoded'                  => array(
            'dlsearch-geocoded',
            Plugin::getPluginDirUrl('/assets/js/search/search-geocoded.js'),
            array('underscore', 'jquery', 'dlgeo-geocoding', 'dlgeo-user-geolocation'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),
        'autocomplete'                       => array(
            'autocomplete',
            Plugin::getPluginDirUrl('/assets/js/vendor/jquery.autocomplete.min.js'),
            array('underscore', 'jquery', 'modernizr'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),
        'dlautocomplete'                     => array(
            'dlautocomplete',
            Plugin::getPluginDirUrl('/assets/js/autocomplete.js'),
            array('underscore', 'jquery', 'autocomplete'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),

        /*
         * Contact Form
         */
        'dlcf7'                              => array(
            'dlcf7',
            Plugin::getPluginDirUrl('/assets/js/front/contact-form7.js'),
            array('jquery', 'contact-form-7'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),

        /*
         * Modal
         *
         * - Modal
         * - Login Register Modal
         */
        'dl-modal'                           => array(
            'dl-modal',
            Plugin::getPluginDirUrl('/assets/js/modal.js'),
            array('underscore', 'jquery', 'dl-form'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),

        /*
         * Review
         */
        'dlreview'                           => array(
            'dlreview',
            Plugin::getPluginDirUrl('/assets/js/front/review.js'),
            array('underscore', 'jquery', 'dl-utils'),
            $dev ? time() : '',
            true,
            '__return_true',
            function () {
                return Context::isSingleListings() and
                       ('off' === F\getPluginOption('events', 'disable_reviews', true));
            },
            array(
                'async' => 'async',
            ),
        ),

        /*
         *  Map & Geo
         *
         *  - Google Map Info-box
         *  - Marker Cluster
         *  - Custom Marker
         *  - Custom Marker Cluster
         *  - Geocoding
         *  - User Geolocation
         *  - Map App
         *  - Listings Map
         *  - Filter Togglers
         *  - Map Toggler
         */
        'google-map-infobox'                 => array(
            'google-map-infobox',
            Plugin::getPluginDirUrl('/assets/js/vendor/gmap-v3-utils/infobox.js'),
            array('google-map'),
            '1.1.13',
            true,
            '__return_true',
            '__return_false',
        ),
        'google-map-marker-clusterer'        => array(
            'google-map-marker-clusterer',
            Plugin::getPluginDirUrl('/assets/js/vendor/gmap-v3-utils/markerclusterer.js'),
            array('google-map'),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),
        'google-map-custom-marker'           => array(
            'google-map-custom-marker',
            Plugin::getPluginDirUrl('/assets/js/gmap-v3-utils/custom-marker.js'),
            array(
                'google-map',
                'google-map-infobox',
            ),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),
        'google-map-custom-marker-clusterer' => array(
            'google-map-custom-marker-clusterer',
            Plugin::getPluginDirUrl('/assets/js/gmap-v3-utils/custom-marker-clusterer.js'),
            array(
                'google-map',
                'google-map-marker-clusterer',
            ),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),
        'dlgeo-geocoding'                    => array(
            'dlgeo-geocoding',
            Plugin::getPluginDirUrl('/assets/js/geo/geocoding.js'),
            array(
                'underscore',
                'google-map',
            ),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),
        'dlgeo-user-geolocation'             => array(
            'dlgeo-user-geolocation',
            Plugin::getPluginDirUrl('/assets/js/geo/user-geolocation.js'),
            array(
                'underscore',
                'dlgeo-geocoding',
            ),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
            array(
                'async' => 'async',
            ),
        ),
        'dlmap-app'                          => array(
            'dlmap-app',
            Plugin::getPluginDirUrl('/assets/js/map/map-app.js'),
            array(
                'underscore',
                'backbone',
                'google-map',
                'google-map-custom-marker',
                'google-map-custom-marker-clusterer',
                'dllistings',
                'dlgeo-user-geolocation',
                'dlgeo-geocoding',
            ),
            $dev ? time() : '',
            true,
            '__return_true',
            '__return_false',
        ),
        'dlmap-listings'                     => array(
            'dlmap-listings',
            Plugin::getPluginDirUrl('/assets/js/map/map-listings.js'),
            array(
                'dllistings',
                'dlgeo-user-geolocation',
                'dlmap-app'
            ),
            $dev ? time() : '',
            true,
            '__return_true',
            function () {
                return F\isListingsArchive();
            },
        ),
        'dlmap-toggler'                      => array(
            'dlmap-toggler',
            Plugin::getPluginDirUrl('/assets/js/map/map-toggler.js'),
            array('jquery', 'underscore', 'dlmap-listings'),
            $dev ? time() : '',
            true,
            '__return_true',
            function () {
                return F\isListingsArchive();
            },
            array(
                'async' => 'async',
            ),
        ),


        // Add Event Calendar
        'appmap-ev-add-calendar'    => array(
            'appmap-ev-add-calendar',
            Plugin::getPluginDirUrl('/assets/js/events/vendor/ouical.js'),
            array('jquery'),
            $dev ? time() : '',
            function () {
                return true;
            },
            function () {
                return is_singular('events');
            },
            array(
                'defer' => 'defer',
            ),
        ),
        // Sidebar.
        'appmap-ev-sidebar'         => array(
            'appmap-ev-sidebar',
            Plugin::getPluginDirUrl('/assets/js/events/sidebar.js'),
            array('underscore', 'jquery', 'force-js'),
            $dev ? time() : '',
            true,
            function () {
                return is_singular('events');
            },
            function () {
                return is_singular('events');
            },
        ),
        // Calendar Filter.
        'appmap-ev-calendar-filter' => array(
            'appmap-ev-calendar-filter',
            Plugin::getPluginDirUrl('/assets/js/events/front/calendarFilter.js'),
            array('underscore', 'jquery'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),
        // Search dates.
        'appmap-ev-search-dates' => array(
            'appmap-ev-search-dates',
            Plugin::getPluginDirUrl('/assets/js/events/front/searchDates.js'),
            array('underscore', 'jquery'),
            $dev ? time() : '',
            true,
            function () {
                return true;
            },
            function () {
                return false;
            },
        ),

    ));

    // Front Styles.
    $scripts['styles'] = array_merge($scripts['styles'], array(
        // Select2.
        'select2'            => array(
            'select2',
            Plugin::getPluginDirUrl('/assets/css/vendor/select2.min.css'),
            array(),
            $dev ? time() : '',
            'screen',
            '__return_true',
            '__return_true',
        ),

        // PhotoSwipe.
        'photoswipe'         => array(
            'photoswipe',
            Plugin::getPluginDirUrl('/assets/vendor/photoswipe/photoswipe.css'),
            array(),
            $dev ? time() : '',
            'screen',
            function () {
                return Context::isSingleListings();
            },
            '__return_false',
        ),
        'photoswipe-skin'    => array(
            'photoswipe-skin',
            Plugin::getPluginDirUrl('/assets/vendor/photoswipe/default-skin.css'),
            array('photoswipe'),
            $dev ? time() : '',
            'screen',
            function () {
                return Context::isSingleListings();
            },
            '__return_false',
        ),
    ));

    // Vendor.
    $scripts['styles']['vendor'] = array(
        'vendor',
        Plugin::getPluginDirUrl('/assets/css/vendor/vendor.min.css'),
        array(),
        $dev ? time() : '',
        'screen',
    );

    // Dropzone.
    $scripts['styles']['dropzone'] = array(
        'dropzone',
        Plugin::getPluginDirUrl('/assets/css/dropzone.min.css'),
        array(),
        $dev ? time() : '',
        'screen',
        '__return_true',
        '__return_false',
    );

    if ('off' === F\getPluginOption('general', 'disable_css', true)) {
        $scripts['styles']['qibla-events-main'] = array(
            'qibla-events-main',
            Plugin::getPluginDirUrl('/assets/css/front.min.css'),
            array(),
            $dev ? time() : '',
            'screen',
        );
    } else {
        $scripts['styles']['qibla-events-base'] = array(
            'qibla-events-base',
            Plugin::getPluginDirUrl('/assets/css/base.min.css'),
            array(),
            $dev ? time() : '',
            'screen',
        );
    }


    /**
     * Filter Scripts
     *
     * @since 1.0.0
     *
     * @param array $scripts The array of the scripts
     */
    $scripts = apply_filters('qibla_events_front_scripts_list', $scripts);
}

return $scripts;