<?php
/**
 * MyListingsEndPoint
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2018 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaEvents\Listing\ManagerPosts;

use QiblaEvents\Functions as F;
use QiblaEvents\Listing\UserListingsPosts;
use QiblaEvents\EndPoint\AbstractEndPoint;

/**
 * Class MyListingsEndPoint
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class MyListingsEndPoint extends AbstractEndPoint
{
    /**
     * Slug ID
     *
     * @since  1.0.0
     *
     * @var string The input slug id.
     */
    private static $slugID = 'woocommerce_myaccount_my_listings';

    /**
     * Slug
     *
     * @since  1.0.0
     *
     * @var string The slug.
     */
    private static $defaultSlug = 'my-listings';

    /**
     * Get Posts Status
     *
     * Retrieve the posts status from the $_POST request.
     * Used to build the posts list.
     *
     * @since  1.0.0
     *
     * @return string The post status or 'any' if no data has been provided.
     */
    protected function getPostsStatus()
    {
        // @codingStandardsIgnoreStart
        $status = sanitize_key(F\filterInput($_GET, 'posts_status', FILTER_SANITIZE_STRING));
        // @codingStandardsIgnoreEnd
        if ($status) {
            $status = (array)$status;
        } else {
            $status = UserListingsPosts::$defaultStatus;
        }

        return $status;
    }

    /**
     * MyListingsEndPoint constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct(array(
            'slug'        => $this->slug(),
            'label'       => esc_html_x('My Listings', 'endpoint', 'qibla-events'),
            'context'     => 'woocommerce_account',
        ), EP_ROOT | EP_PAGES);
    }

    /**
     * @inheritdoc
     */
    public function getEndPointLabel()
    {
        // @codingStandardsIgnoreLine
        return esc_html_x($this->endPointData['label'], 'endpoint', 'qibla-events');
    }

    /**
     * Endpint Slug
     *
     * @since  1.0.0
     *
     * @return string The saved slug.
     */
    public static function slug()
    {
        $slug = get_option(self::$slugID, self::$defaultSlug);

        if (! $slug) {
            $slug = self::$defaultSlug;
        }

        return sanitize_title_with_dashes($slug);
    }

    /**
     * Edit Slug
     *
     * @since  1.0.0
     *
     * @param $settings array The my account endpoint settings.
     *
     * @return array          The new array containing the new input.
     */
    public function editSlug($settings)
    {
        // Initialized.
        $newSettings = array();

        foreach ((array)$settings as $section) {
            if (isset($section['id']) && 'woocommerce_myaccount_edit_account_endpoint' === $section['id']) {
                // My Favorites section.
                $newSettings[] = array(
                    'title'    => esc_html_x('My Listings', 'my-account', 'qibla-events'),
                    'desc'     => esc_html_x(
                        'Endpoint for the page "my account &rarr; my listings"',
                        'my-account',
                        'qibla-events'
                    ),
                    'id'       => self::$slugID,
                    'type'     => 'text',
                    'default'  => self::$defaultSlug,
                    'desc_tip' => true,
                );
            }

            $newSettings[] = $section;
        }

        return $newSettings;
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        // Don't hook if the user cannot manage listings.
        if (! current_user_can('edit_listingss')) {
            return false;
        }

        // Assign the callback.
        $endPointSlug = sanitize_key($this->getEndPoint());
        add_action('woocommerce_account_' . $endPointSlug . '_endpoint', array($this, 'callback'));
        add_filter('woocommerce_account_settings', array($this, 'editSlug'));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function callback($endPointSlug)
    {
        $postStatus         = $this->getPostsStatus();
        $managerPostsFacade = new ManagerPostsFacade($postStatus);
        $managerPostsFacade->showListingsPosts();
    }
}
