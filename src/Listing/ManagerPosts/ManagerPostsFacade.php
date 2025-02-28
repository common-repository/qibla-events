<?php
/**
 * ManagerFacade
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

use QiblaEvents\Listing\UserListingsPosts;

/**
 * Class ManagerFacade
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ManagerPostsFacade
{
    /**
     * Template
     *
     * @since  1.0.0
     * @access protected
     *
     * @var Template
     */
    protected $template;

    /**
     * ManagerPostsFacade constructor
     *
     * @since 1.0.0
     *
     * @param array $status The status of the posts to retrieve.
     */
    public function __construct($status)
    {
        $posts          = new UserListingsPosts(wp_get_current_user(), $status);
        $this->template = new Template($posts);
    }

    /**
     * Show Listings Posts
     *
     * @since 1.0.0
     */
    public function showListingsPosts()
    {
        $data = $this->template->getData();
        $this->template->tmpl($data);
    }
}
