<?php
/**
 * User Listings Posts
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

namespace QiblaEvents\Listing;

use QiblaEvents\ListingsContext\Types;

/**
 * Class UserListingsPosts
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class UserListingsPosts implements \Iterator
{
    /**
     * User
     *
     * @since  1.0.0
     *
     * @var \WP_User The user from which retrieve the posts
     */
    protected $user;

    /**
     * Status
     *
     * @since  1.0.0
     *
     * @var array The list of the status of the posts to retrieve
     */
    protected $status;

    /**
     * The Posts
     *
     * @since  1.0.0
     *
     * @var array The posts list
     */
    protected $posts;

    /**
     * Default status for posts
     *
     * @since  1.0.0
     *
     * @var array The default posts statuses
     */
    public static $defaultStatus = array('any', 'qibla-expired');

    /**
     * Get Posts
     *
     * Made a query and retrieve the posts.
     * Used only to fill the $posts property.
     *
     * @since  1.0.0
     *
     * @return array The list of the posts. Empty array if there are no posts.
     */
    protected function getPosts()
    {
        $types = new Types();

        $posts = new \WP_Query(array(
            'post_type'      => $types->types(),
            'post_status'    => $this->status,
            'author'         => $this->user->ID,
            // @todo Paginate?
            'posts_per_page' => -1,
            'no_found_rows'  => true,
        ));

        return $posts->posts;
    }

    /**
     * UserListingsPosts constructor
     *
     * @since  1.0.0
     *
     * @param \WP_User $user   The user from which retrieve the posts.
     * @param array    $status The status of the posts to retrieve.
     */
    public function __construct(\WP_User $user, array $status)
    {
        $this->user   = $user;
        $this->status = $status;
        $this->posts  = $this->getPosts();
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return current($this->posts);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        return next($this->posts);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return key($this->posts);
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return $this->current() instanceof \WP_Post;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        reset($this->posts);
    }
}
