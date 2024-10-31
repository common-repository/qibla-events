<?php
/**
 * Conditional Functions
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

use QiblaEvents\ListingsContext\Context;
use QiblaEvents\ListingsContext\Types;

/**
 * Is JSON
 *
 * Check if a string is a valid json or not.
 *
 * @link  https://codepad.co/snippet/jHa0m4DB
 *
 * @since 1.0.0
 *
 * @param string $data The json string.
 *
 * @return bool True if the string is a json, false otherwise
 */
function isJSON($data)
{
    if (! is_string($data) || ! trim($data)) {
        return false;
    }

    return (
               // Maybe an empty string, array or object.
               $data === '""' ||
               $data === '[]' ||
               $data === '{}' ||
               // Maybe an encoded JSON string.
               $data[0] === '"' ||
               // Maybe a flat array.
               $data[0] === '[' ||
               // Maybe an associative array.
               $data[0] === '{'
           ) && json_decode($data) !== null;
}

/**
 * Is WooCommerce active
 *
 * @since 1.0.0
 *
 * @return bool If the function WC exists and the theme support WooCommerce.
 */
function isWooCommerceActive()
{
    return function_exists('WC');
}

/**
 * Importing
 *
 * @since 1.0.0
 *
 * @return bool If the QB_IMPORT constant is defined and is true.
 */
function isImporting()
{
    return defined('QB_IMPORT') && QB_IMPORT;
}

/**
 * Is Ajax Request
 *
 * Define that this is a request made via ajax.
 * It's not the traditional WordPress ajax request, infact the DOING_AJAX is not set.
 * It's a convention that the argument passed with the dlajax_action query are made by an ajax request.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isAjaxRequest()
{
    // @codingStandardsIgnoreLine
    $action = filterInput($_POST, 'dlajax_action', FILTER_SANITIZE_STRING);

    return (bool)$action;
}

/**
 * Is Edit Term Page
 *
 * @since 1.0.0
 *
 * @return bool True if the current screen is the edit term, false otherwise
 */
function isEditTerm()
{
    return 'term' === currentScreen()->base;
}

/**
 * Is Listings Archive
 *
 * @since 1.0.0
 *
 * @return bool True if one of the conditions are meet false otherwise.
 */
function isListingsArchive()
{
    $context = new Context(getWpQuery(), new Types());

    return $context->isListingsTypeArchive() || $context->isListingsTaxArchive();
}

/**
 * Is Listings Main Query
 *
 * Check whatever the current query object is for the main query regarding the post type listings.
 *
 * @since 1.0.0
 *
 * @param \WP_Query $query The query to check.
 *
 * @return bool True if main listings query, false otherwise
 */
function isListingsMainQuery(\WP_Query $query)
{
    return isListingsArchive() && $query->is_main_query();
}

/**
 * Is Date Archive
 *
 * @since 1.0.0
 *
 * @return bool If the current archive is one of teh date, year, month, day, time.
 */
function isDateArchive()
{
    // It is blog if the queried object is null and one of the "time" conditional return true.
    return (null === get_queried_object() && (is_date() || is_year() || is_month() || is_day() || is_time()));
}

/**
 * Is Blog
 *
 * @since 1.0.0
 *
 * @return bool True if one of the category, home, tag page is displaying, false otherwise
 */
function isBlog()
{
    return is_home() || is_category() || is_tag() || is_author() ||
           // It is blog if the current query is for search and post type is 'post'.
           (is_search() && 'post' === get_post_type()) || isDateArchive();
}

/**
 * Is WooCommerce
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isWooCommerce()
{
    return isWooCommerceActive() && is_woocommerce();
}

/**
 * Is Shop
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isShop()
{
    return isWooCommerceActive() && is_shop();
}

/**
 * Is Product Taxonomy
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isProductTaxonomy()
{
    return isWooCommerceActive() && is_product_taxonomy();
}

/**
 * Is Product Category
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isProductCategory()
{
    return isWooCommerceActive() && is_product_category();
}

/**
 * Is Product Tag
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isProductTag()
{
    return isWooCommerceActive() && is_product_tag();
}

/**
 * Is Product
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isProduct()
{
    return isWooCommerceActive() && is_product();
}

/**
 * Is Cart
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isCart()
{
    return isWooCommerceActive() && is_cart();
}

/**
 * Is Checkout
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isCheckout()
{
    return isWooCommerceActive() && is_checkout();
}

/**
 * Is WooCommerce Archive
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isWooCommerceArchive()
{
    return isShop() || isProductTaxonomy();
}

/**
 * Is Account Page
 *
 * @since 1.0.0
 *
 * @return bool
 */
function isAccountPage()
{
    return isWooCommerceActive() && is_account_page();
}

/**
 * Is Archive Page
 *
 * Archive page are the ones like page for posts and shop.
 * All of the pages that works like archives.
 *
 * @since 1.0.0
 * @uses  getArchivePage()
 *
 * @return bool If the page works as an archive.
 */
function isArchivePage()
{
    return getArchivePage() instanceof \WP_Post;
}

/**
 * Is WpMl active
 *
 * @since 1.0.0
 *
 * @return bool If the class SitePress exists and the theme support WpMl.
 */
function isWpMlActive()
{
    return class_exists('SitePress');
}
