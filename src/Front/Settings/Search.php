<?php
/**
 * Search
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

namespace QiblaEvents\Front\Settings;

use QiblaEvents\Functions as F;

/**
 * Class Search
 *
 * @since   1.0.0
 * @package QiblaEvents\Front\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Search
{
    /**
     * Search Type
     *
     * @since 1.0.0
     *
     * @return string The search type slug.
     */
    public static function searchType()
    {
        return F\getPluginOption('search', 'type', true);
    }
}
