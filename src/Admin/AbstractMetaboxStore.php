<?php
/**
 * Abstract Meta Box Store
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Admin\Metabox
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    GNU General Public License, version 2
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

namespace QiblaEvents\Admin;

/**
 * Class AbstractMetaBoxStore
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Admin
 */
abstract class AbstractMetaboxStore
{
    /**
     * Meta-boxes List
     *
     * @since  1.0.0
     *
     * @var array The list of the meta-boxes to register
     */
    protected $metaBoxes;

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @param array $metaBoxes The list of the meta-boxes to register
     */
    public function __construct(array $metaBoxes)
    {
        $this->metaBoxes = $metaBoxes;
    }

    /**
     * Store Meta Data
     *
     * Helper function to store the meta for a post type.
     *
     * @since  1.0.0
     *
     * @param array $metaBoxesList The meta-boxes associated to the post.
     */
    public static function storeMetaFilter(array $metaBoxesList)
    {
        $instance = new static($metaBoxesList);
        call_user_func_array(array($instance, 'meta'), array_slice(func_get_args(), 1));
    }
}
