<?php
namespace Unprefix\Scripts;

/**
 * Scripts
 *
 * @package Unprefix\Scripts
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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
 * Class ScriptsFacade
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
class ScriptsFacade
{
    /**
     * Constructor.
     *
     * @since  1.0.0
     * @access public
     *
     * @param array $list The scripts and styles list.
     */
    public function __construct(array $list)
    {
        $this->register = new Register($list);
        $this->enqueuer = new Enqueuer($list);
    }

    /**
     * Register Scripts and Styles
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     */
    public function register()
    {
        $this->register->register();
    }

    /**
     * De-Register Scripts and Styles
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     */
    public function deregister()
    {
        $this->register->deregister();
    }

    /**
     * Enqueue Scripts and Styles
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     */
    public function enqueuer()
    {
        add_filter('script_loader_tag', array($this->enqueuer, 'insertAttributes'), 20, 2);

        $this->enqueuer->enqueuer();
    }
}
