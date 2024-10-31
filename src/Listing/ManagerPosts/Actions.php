<?php
/**
 * Actions
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

use QiblaEvents\ListingsContext\Types;
use QiblaEvents\Package\Package;

/**
 * Class Actions
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Actions
{
    /**
     * Actions List
     *
     * @since  1.0.0
     * @access protected
     *
     * @var array The list of the actions
     */
    protected $actions;

    /**
     * The post
     *
     * The post associated to the actions
     *
     * @since  1.0.0
     * @access protected
     *
     * @var \WP_Post The post associated to the actions
     */
    protected $post;

    /**
     * The post
     *
     * The package associated to the post listing
     *
     * @since  1.0.0
     * @access protected
     *
     * @var \WP_Post The package post associated to the post listing
     */
    protected $packagePost;

    /**
     * Actions constructor
     *
     * @since 1.0.0
     *
     * @throws \InvalidArgumentException If the post types of the post injected are not valid.
     *
     * @param Types    $types       The instance of listings types.
     * @param \WP_Post $packagePost The package post associated to the post listing.
     * @param \WP_Post $post        The post associated to the actions.
     */
    public function __construct(Types $types, \WP_Post $packagePost, \WP_Post $post)
    {
        if ('listing_package' !== $packagePost->post_type || ! $types->isListingsType($post->post_type)) {
            throw new \InvalidArgumentException('Post types mismatch when constructing the Actions for listings post.');
        }

        $package = new Package($packagePost);

        $this->packagePost = $packagePost;
        $this->post        = $post;
        $this->actions     = array(
            // Edit Action.
            'edit'   => (object)array(
                'url'   => $package->getListingEditFormPermalink($post),
                'icon'  => 'la la-edit',
                'label' => esc_html_x('Edit', 'account-posts-manager', 'qibla-events'),
            ),
            // Delete Action.
            'delete' => (object)array(
                'url'   => add_query_arg(array(
                    'dlajax_action' => 'post_request_delete',
                    'post_id'       => $post->ID,
                    'package_post'  => $this->post->ID,
                    '_nonce_nonce'  => wp_create_nonce('_nonce'),
                ), home_url()),
                'icon'  => 'la la-trash',
                'label' => esc_html_x('Delete', 'account-posts-manager', 'qibla-events'),
            ),
        );
    }

    /**
     * Get Action
     *
     * @since  1.0.0
     * @access public
     *
     * @return array The list of the actions
     */
    public function getActions()
    {
        return $this->actions;
    }
}
