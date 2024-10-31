<?php
/**
 * Listings Capabilities
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

use QiblaEvents\ListingsContext\Types;

/**
 * Class ListingsCapabilities
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ManageListings implements CapabilitiesInterface
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
        'administrator',
        'manage_listings',
    );

    /**
     * Build Caps
     *
     * @since 1.0.0
     *
     * @param array $caps Base capabilities.
     *
     * @return array The new capabilities
     */
    private function buildCaps(array $caps)
    {
        // Include the manage listings capability.
        $caps['manage_listings'] = 'manage_listings';
        // Allow manage_listings to access to admin when WooCommerce is active.
        $caps['view_admin_dashboard'] = 'view_admin_dashboard';
        $caps['assign_terms']         = 'assign_terms';

        $keys = array_values((array)$caps);
        $caps = array_fill_keys($keys, true);

        return $caps;
    }

    /**
     * ListingsCapabilities constructor
     *
     * @since 1.0.0
     *
     * @throws \InvalidArgumentException When the post type is not an instance for Listings post type.
     *
     * @param \WP_Post_Type $postType The post type instance.
     */
    public function __construct(\WP_Post_Type $postType)
    {
        $types = new Types();

        if (! $types->isListingsType($postType->name)) {
            throw new \InvalidArgumentException('Post Type Listings needed when creating the Listings Capabilities.');
        }

        $this->caps = $this->buildCaps((array)$postType->cap);
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
