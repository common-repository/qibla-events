<?php
namespace QiblaEvents;

use QiblaEvents\Capabilities;
use QiblaEvents\ListingsContext\Types;
use QiblaEvents\PostType;
use QiblaEvents\Taxonomy;

/**
 * Class Activate
 *
 * @since      1.0.0
 * @package    QiblaEvents
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

/**
 * Class Activate
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Activate
{
    /**
     * Register Post Types & Taxonomies
     *
     * @since  1.0.0
     *
     * @return void
     */
    private static function registerCptTax()
    {
        $register = new PostType\Register(array(
            new PostType\Listings(),
        ));
        $register->register();

        $register = new Taxonomy\Register(array(
            new Taxonomy\Locations(),
            new Taxonomy\Tags(),
            new Taxonomy\ListingCategories(),
            new Taxonomy\EventDates(),
        ));
        $register->register();
    }

    /**
     * Register Capabilities
     *
     * @since 1.0.0
     *
     * @return void
     */
    private static function registerCapabilities()
    {
        $types = new Types();

        foreach ($types->types() as $type) {
            $caps = new Capabilities\Register(array(
                new Capabilities\ManageListings(get_post_type_object($type)),
            ));

            $caps->register();
        }
    }

    /**
     * Add Role For Listings Authors
     *
     * @since  1.0.0
     * @access private static
     *
     * @return void
     */
    private static function addRoleListingsAuthor()
    {
        $list = array(
            new Capabilities\Register(array(
                new Capabilities\ListingsAuthor(),
            )),
        );

        // Register the Roles/Capabilities.
        foreach ($list as $register) {
            $register->register();
        }
    }

    /**
     * Plugin Activate
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function activate()
    {
        // Register post types and Taxonomies.
        self::registerCptTax();
        // Register the Capabilities.
        self::registerCapabilities();
        // Add the User listings Author roles.
        self::addRoleListingsAuthor();

        // Flush rules.
        flush_rewrite_rules();
    }
}
