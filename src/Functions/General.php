<?php
/**
 * General Functions
 *
 * @package QiblaEvents\Functions
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 *
 * @license GPL 2
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

use QiblaEvents\Front\Settings\Listings;
use QiblaEvents\ListingsContext\Context;
use QiblaEvents\ListingsContext\Types;
use QiblaEvents\TemplateEngine\Engine;

/**
 * Outputs the html selected attribute.
 *
 * Compares the first two arguments and if identical marks as selected.
 * This function is a wrap of WordPress select that work with arrays.
 *
 * @since 1.0.0
 *
 * @param mixed $selected One of the values to compare
 * @param mixed $current  (true) The other value to compare if not just true
 * @param bool  $echo     Whether to echo or just return the string
 *
 * @return string|null The html attribute or empty string. Echo if $echo is true.
 */
function selected($selected, $current, $echo)
{
    if (is_array($selected)) {
        $index = array_search($current, $selected, true);
        if (false === $index) {
            return '';
        }

        $selected = $selected[$index];
    }

    $selected = \selected($selected, $current, false);

    if (! $echo) {
        return $selected;
    }

    // @codingStandardsIgnoreLine
    echo $selected;
}

/**
 * Get Referrer
 *
 * @todo  Check for use wp_get_raw_referer() but pay attention to the _wp_http_referer in forms.
 *
 * @since 1.0.0
 *
 * @return string The referrer if set. Empty string otherwise.
 */
function getReferer()
{
    if (! empty($_SERVER['HTTP_REFERER'])) {
        return wp_unslash($_SERVER['HTTP_REFERER']);
    }

    return '';
}

/**
 * Get Input Data Provider
 *
 * Return the correct data container based on request method.
 *
 * @since 1.0.0
 *
 * @param mixed $method The Input method. GET, POST etc...
 *
 * @return mixed The container of the values
 */
function getInputDataProvider($method)
{
    // Canonicalize the input data provider name.
    $method = strtoupper($method);

    // @codingStandardsIgnoreStart
    switch ($method) {
        case 'GET':
            $data = $_GET;
            break;
        case 'REQUEST':
            $data = $_REQUEST;
            break;
        default:
            $data = $_POST;
            break;
    } // @codingStandardsIgnoreEnd

    return $data;
}

/**
 * Filter Input
 *
 * @since 1.0.0
 *
 * @uses  filter_var() To filter the value.
 *
 * @param array  $data    The haystack of the elements.
 * @param string $key     The key of the element within the haystack to filter.
 * @param int    $filter  The filter to use.
 * @param array  $options The option for the filter var.
 *
 * @return bool|mixed The value filtered on success false if filter fails or key doesn't exists.
 */
function filterInput($data, $key, $filter = FILTER_DEFAULT, $options = array())
{
    return isset($data[$key]) ? filter_var($data[$key], $filter, $options) : false;
}

/**
 * Get Current Screen
 *
 * @since 1.0.0
 *
 * @return \WP_Screen|null The \WP_Screen instance or null if function not exists or not screen is set.
 */
function currentScreen()
{
    return function_exists('get_current_screen') ? get_current_screen() : null;
}

/**
 * Svg Loader Template
 *
 * @since 1.0.0
 *
 * @return null|void
 * @throws \Exception
 */
function svgLoaderTmpl()
{
    if (is_admin()) {
        // Get current screen
        $screen = get_current_screen();

        if ('qibla-events-options' !== $screen->parent_base) {
            return;
        }
    }

    $engine = new Engine('svg_loader', new \stdClass(), '/assets/svg/loader.svg');
    $engine->render();
}

/**
 * Send headers for Ajax Requests
 *
 * @since  1.0.0
 *
 * @param int $status The header status.
 *
 * @return void
 */
function setHeaders($status = 200)
{

    @header('Content-Type: text/html; charset=' . get_option('blog_charset'));
    @header('X-Frame-Options: DENY');
    @header('X-Robots-Tag: noindex');

    send_origin_headers();
    send_nosniff_header();

    nocache_headers();

    status_header($status);
}

/**
 * Search Navigation Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function searchNavigationTmpl()
{
    $engine = new Engine('search_navigation_tmpl', new \stdClass(), '/views/template/searchNavigation.php');
    $engine->render();
}

/**
 * Add body classes
 *
 * Extend the WordPress body class by adding more classes about the device.
 *
 * @since 1.0.0
 *
 * @param  array $classes The body classes.
 *
 * @return array $classes The body classes filtered
 */
function bodyClass($classes)
{
    $query   = \QiblaEvents\Functions\getWpQuery();
    $context = new Context($query, new Types());

    // Add class to identify that the plugin is active.
    $classes[] = 'dlfw';

    // Additional class for:.
    if (isListingsArchive()) {
        $classes[] = 'dl-is-listings-archive';
        $classes[] = 'dl-is-listings-archive--' . (Listings::showMapOnArchive() ? 'with-map' : 'no-map');
    }

    // Add extra class to know if the user is logged in or not.
    $classes[] = is_user_logged_in() ? 'dl-user-login' : 'dl-user-logout';

    // Is Singular Listings?
    if (Context::isSingleListings()) {
        $classes[] = 'dl-is-singular-listings';
    }

    // Remove unneeded classes if search is made for listings context.
    if (is_search() and $context->context()) {
        $classes = array_filter($classes, function ($class) {
            return ! in_array($class, array('search', 'search-no-results', 'search-results'), true);
        });
    }

    return $classes;
}

/**
 * Admin Body Classes
 *
 * @since 1.0.0
 *
 * @param string $classes The attribute value for class.
 *
 * @return string The filtered classes value
 */
function adminBodyClass($classes)
{
    $currentScreen = currentScreen();
    $types         = new Types();

    // Assign a generic reusable value for when we are showing the edit list table.
    if (isset($currentScreen->post_type) and $types->isListingsType($currentScreen->post_type)) {
        $classes = rtrim($classes) . ' dl-is-listings-type-table-list';
    }

    return $classes;
}

/**
 * Get Scope Class
 *
 * @since 1.0.0
 *
 * @param string       $block    The custom block scope.
 * @param string       $element  The element within the scope.
 * @param string|array $modifier The block modifier key.
 * @param string       $attr     The attribute name for which build the scope.
 *
 * @return string $upxscope The scope class
 */
function getScopeClass($block = '', $element = '', $modifier = '', $attr = 'class')
{
    // The Scope prefix.
    $upxscope = $scope = 'dl';

    if ($block) :
        $upxscope .= $block;
    else :
        if (is_author()) {
            $upxscope .= 'author-archive';
        } elseif (is_attachment()) {
            $upxscope .= 'attachment';
        } elseif (is_archive() || is_search() || is_home()) {
            $upxscope .= 'archive';
        } elseif (is_page()) {
            $upxscope .= 'page';
        } elseif (is_singular()) {
            $postType = 'post';

            /**
             * Filter the post type scope
             *
             * @since 1.0.0
             *
             * @param string $postType The current post type
             * @param string $upxscope The current scope block
             */
            $upxscope .= apply_filters('qibla_events_get_singular_scope_post_type', $postType, $upxscope);
        } elseif (is_404()) {
            $upxscope .= '404';
        }//end if
    endif;

    if ($modifier) {
        $tmpScope = $upxscope;
        $upxscope = '';

        foreach ((array)$modifier as $mod) {
            if ($mod) {
                $upxscope .= ' ' . $tmpScope . "--{$mod}";
            }
        }

        $upxscope = trim($tmpScope . ' ' . $upxscope);

        unset($tmpScope);
    } elseif ($element) {
        $upxscope .= "__{$element}";
    }

    /**
     * Scope Filter
     *
     * Filter the scope string before it is returned.
     *
     * @since 1.0.0
     *
     * @param string $upxscope The scope prefix. Default 'upx'.
     * @param string $element  The current element of the scope.
     * @param string $block    The custom block scope. Default empty.
     * @param string $scope    The default scope prefix. Default 'upx'.
     * @param string $attr     The attribute for which the value has been build.
     * @param string $modifier The modifier value.
     */
    $upxscope = apply_filters('qibla_events_scope_attribute', $upxscope, $element, $block, $scope, $attr, $modifier);

    return $upxscope;
}

/**
 * Scope Class
 *
 * @since 1.0.0
 *
 * @uses  getScopeClass()
 *
 * @param string $block    The custom block scope.
 * @param string $element  The element within the scope.
 * @param string $modifier The block modifier key.
 *
 * @return void
 */
function scopeClass($block = '', $element = '', $modifier = '')
{
    echo 'class="' . sanitizeHtmlClass(getScopeClass($block, $element, $modifier)) . '"';
}

/**
 * Scope ID
 *
 * @since 1.0.0
 *
 * @uses  getScopeClass()
 *
 * @param string $block The custom block scope.
 *
 * @return void
 */
function scopeID($block = '')
{
    echo 'id="' . getScopeClass($block, '', '', 'id') . '"';
}

/**
 * Check QiblaEvents
 *
 * @since 1.0.0
 *
 * @return bool True if check pass, false otherwise
 */
function checkQiblaFramework()
{
    if (! function_exists('is_plugin_active')) {
        require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/plugin.php';
    }

    return is_plugin_active('qibla-framework/index.php');
}

/**
 * Disable Plugin
 *
 * This function disable the plugin.
 *
 * @since 1.0.0
 *
 * @return void
 */
function disablePlugin()
{
    if (checkQiblaFramework()) :
        add_action('admin_notices', function () {
            ?>
            <div class="error">
                <p>
                    <?php esc_html_e(
                        'Qibla Events has been deactivated. The plugin can not be activated if active Qibla Framework',
                        'qibla-events'
                    ); ?>
                </p>
            </div>
            <?php

            // Don't show the activated notice.
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        });

        // Deactivate the plugin.
        deactivate_plugins('qibla-events/index.php');
    endif;
}

/**
 * Set Viewed Post Cookie
 *
 * @since  1.0.0
 */
function setViewedCookie()
{
    // Is Admin?
    if (is_admin()) {
        return;
    }

    // Remove adjacent posts hook, for prevent executed twice.
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

    // Get Types.
    $types = new Types();

    // Check if post type is in types.
    if (in_array(get_post_type(), $types->types(), true)) :
        $postID = get_the_ID();

        // Check if isset cookie.
        if (! isset($_COOKIE['qibla_events_listings_recently_viewed'])) {
            // If there's no cookie set, set up a new array.
            $cookieArray = array($postID);
        } else {
            $cookieArray = unserialize($_COOKIE['qibla_events_listings_recently_viewed']);

            if (! is_array($cookieArray)) {
                $cookieArray = array($postID);
            }
        }

        // Add in cookie array current post id.
        if (in_array($postID, $cookieArray, true)) {
            $key = array_search($postID, $cookieArray, true);
            array_splice($cookieArray, $key, 1);
        }

        array_unshift($cookieArray, $postID);

        // Set cookie.
        setcookie(
            'qibla_events_listings_recently_viewed',
            serialize($cookieArray),
            time() + (DAY_IN_SECONDS * 31),
            '/'
        );
    endif;
}

/**
 * Set current lang
 *
 * @since  1.0.0
 *
 * @return null or current lang.
 */
function setCurrentLang()
{
    $currentLang = null;
    if (isWpMlActive() && defined('ICL_LANGUAGE_CODE')) {
        global $sitepress;
        $default = $sitepress->get_default_language();

        $currentLang = ICL_LANGUAGE_CODE !== $default ? ICL_LANGUAGE_CODE : null;
    }

    return $currentLang;
}

/**
 * Strip Content various tags such as img, script and style
 *
 * @since 1.0.0
 *
 * @param $content
 *
 * @return string
 */
function stripContent($content)
{
    // Strip shortcode.
    $content = rtrim(strip_shortcodes($content), "\n\t\r");
    // Strip images.
    $content = preg_replace('/<img[^>]+\>/i', '', $content);
    // Strip div.
    $content = preg_replace("/<div>(.*?)<\/div>/", "$1", $content);
    // Remove empty links after remove the images. Some images are wrapped around an anchor.
    $content = preg_replace('/<a[^>]+\><\/a>/i', '', $content);
    // Strip scripts.
    $content = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $content);
    // Current content html.
    $description_html = shortcode_unautop(wpautop(wptexturize(apply_filters('the_content', $content))));

    return $description_html;
}
