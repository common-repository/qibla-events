<?php
/**
 * ListingsAuthor
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

namespace QiblaEvents\Capabilities;

/**
 * Class ListingsAuthor
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingsAuthor implements CapabilitiesInterface
{
    /**
     * Capabilities
     *
     * @since 1.0.0
     *
     * @var array The list of the capabilities to manage
     */
    private $caps;

    /**
     * Roles
     *
     * @since 1.0.0
     *
     * @var array The list of the roles associated to the capabilities.
     */
    private static $roles = array(
        'listings_author',
    );

    /**
     * ListingsCapabilities constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->caps = array(
            'read'                       => false,
            'publish_listingss'          => true,
            'edit_listings'              => true,
            'edit_listingss'             => true,
            'delete_listings'            => true,
            'delete_listingss'           => true,
            'upload_files'               => true,
            'listings_author'            => true,
            'edit_published_listingss'   => true,
            'delete_published_listingss' => true,
            'assign_terms'               => true,
        );
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function caps()
    {
        return $this->caps;
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function roles()
    {
        return self::$roles;
    }
}
