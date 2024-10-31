<?php
/**
 * ListingsPostInterface
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

namespace QiblaEvents\Listings;

/**
 * Interface ListingsPostInterface
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Listings
 */
interface ListingsPostInterface extends EntityInterface
{
    /**
     * Status Expired
     *
     * The status for the expired posts
     *
     * @since  1.0.0
     *
     * @var string The status of the expired post
     */
    const EXPIRED_STATUS = 'qibla-expired';

    /**
     * Published Status
     *
     * The status for published listings
     *
     * @since  1.0.0
     *
     * @var string The status of the published post
     */
    const PUBLISHED_STATUS = 'publish';

    /**
     * Pending Status
     *
     * The status for published listings
     *
     * @since  1.0.0
     *
     * @var string The status of the pending post
     */
    const PENDING_STATUS = 'pending';

    /**
     * Pending Status
     *
     * The status for deleted listings
     *
     * @since  1.0.0
     *
     * @var string The status of the deleted post
     */
    const SOFT_DELETED_STATUS = 'qibla-soft-deleted';

    /**
     * Title
     *
     * @since {SINCE}
     *
     * @return string The post tile
     */
    public function title();

    /**
     * Slug
     *
     * @since {SINCE}
     *
     * @return string the slug of the post
     */
    public function slug();

    /**
     * Permalink
     *
     * @since 1.0.0
     *
     * @return string The permalink of the post
     */
    public function permalink();

    /**
     * Post Status
     *
     * @since 1.0.0
     *
     * @return string The status of the post
     */
    public function status();

    /**
     * Is Featured
     *
     * @since 1.0.0
     *
     * @return bool True if featured, false otherwise.
     */
    public function featured();
}
