<?php
/**
 * Register Post Type
 *
 * @since      1.0.0
 * @package    QiblaEvents\PostType
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

namespace QiblaEvents\PostType;

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
     * Post Types
     *
     * @since  1.0.0
     *
     * @var array A list of Post Type objects
     */
    private $postTypes;

    /**
     * Constructor.
     *
     * @since  1.0.0
     *
     * @param array $postTypes The post types instance to register
     */
    public function __construct(array $postTypes)
    {
        $this->postTypes = $postTypes;
    }

    /**
     * Register Post Types
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->postTypes as $postType) {
            if (post_type_exists($postType->getType())) {
                continue;
            }
            // Register the current post type.
            register_post_type($postType->getType(), $postType->getArgs());
            // Custom Columns.
            $this->customColumns($postType);
            // Row Actions.
            $this->rowActions($postType);
        }
    }

    /**
     * Custom Columns
     *
     * @since  1.0.0
     *
     * @param \WP_Post_Type $postType The post type instance.
     *
     * @return void
     */
    private function customColumns($postType)
    {
        // Then add the custom columns if methods exists.
        // Them are in separated conditional because in some cases we may want only to manage one,
        // for example by removing a column.
        if (method_exists($postType, 'columns')) {
            add_filter('manage_' . $postType->getType() . '_posts_columns', array($postType, 'columns'), 20);
        }
        if (method_exists($postType, 'customColumn')) {
            add_action('manage_' . $postType->getType() . '_posts_custom_column', array($postType, 'customColumn'), 20, 2);
        }
    }

    /**
     * Additional Row Actions
     *
     * @since  1.0.0
     *
     * @param \WP_Post_Type $postType The post type instance.
     *
     * @return void
     */
    private function rowActions($postType)
    {
        // Additional Row Actions.
        if (method_exists($postType, 'rowActions')) {
            add_filter('post_row_actions', array($postType, 'rowActions'), 20, 2);
        }
    }
}
