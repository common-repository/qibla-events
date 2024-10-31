<?php
/**
 * Sidebar
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

namespace QiblaListings;

/**
 * Class Sidebar
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaListings\Front
 */
class Sidebar
{
    /**
     * Remove Sidebar from Single Package
     *
     * Remove the sidebar from the single package post, and set the value to 'none'.
     *
     * @since  1.0.0
     * @access public static
     *
     * @param string $position 'none' or the passed value if the post type is not listing_package and not in singular
     *                         listing_package.
     *
     * @return string The filtered position
     */
    public static function removeSidebarFromSinglePackageListingFilter($position)
    {
        if (is_singular('listing_package') && 'listing_package' === get_post_type()) {
            $position = 'none';
        }

        return $position;
    }
}
