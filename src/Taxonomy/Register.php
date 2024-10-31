<?php
/**
 * Register Taxonomy
 *
 * @since      1.0.0
 * @package    QiblaEvents\Taxonomy
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

namespace QiblaEvents\Taxonomy;

use QiblaEvents\RegisterInterface;

/**
 * Class Register
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Register implements RegisterInterface
{
    /**
     * Taxonomies
     *
     * @since  1.0.0
     *
     * @var array A list of Post Type objects
     */
    private $taxonomies;

    /**
     * Constructor.
     *
     * @since  1.0.0
     *
     * @param array $taxonomies The taxonomies instance to register
     */
    public function __construct(array $taxonomies)
    {
        $this->taxonomies = $taxonomies;
    }

    /**
     * Register Taxonomies
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->taxonomies as $tax) {
            if (taxonomy_exists($tax->getName())) {
                continue;
            }
            // Register the current post type.
            register_taxonomy($tax->getName(), $tax->getPostType(), $tax->getArgs());
        }
    }
}
