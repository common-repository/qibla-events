<?php
/**
 * ActionsTemplate
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

use QiblaEvents\TemplateEngine\Engine;

/**
 * Class ActionsTemplate
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Listing\ManagerPosts
 */
class ActionsTemplate
{
    /**
     * Actions
     *
     * @since  1.0.0
     * @access protected
     *
     * @var Actions The instance of the Actions class
     */
    protected $actions;

    /**
     * ActionsTemplate constructor
     *
     * @since 1.0.0
     *
     * @param Actions $actions The instance of the actions class
     */
    public function __construct(Actions $actions)
    {
        $this->actions = $actions;
    }

    /**
     * Get Data
     *
     * @since  1.0.0
     * @access public
     *
     * @return \stdClass The instance of a class to use within the template
     */
    public function getData()
    {
        $data          = new \stdClass();
        $data->actions = $this->actions->getActions();

        return $data;
    }

    /**
     * Template
     *
     * @since  1.0.0
     * @access public
     *
     * @param \stdClass $data The instance of a class to use within the template
     *
     * @return void
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new Engine('manager_post_actions', $data, '/views/listings/managerActionsTmpl.php');
        $engine->render();

        if (wp_script_is('manager-post-actions', 'registered')) {
            wp_enqueue_script('manager-post-actions');
        }
    }
}
