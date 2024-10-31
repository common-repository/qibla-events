<?php
/**
 * RequestFormFacadeInterface
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

namespace QiblaEvents\Request;

/**
 * Interface RequestFormFacadeInterface
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
interface RequestFormFacadeInterface
{
    /**
     * Handle the Registration
     *
     * @uses   add_action() To set the able to show the form when the action related to the class is performed.
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function handle();

    /**
     * Build the data for the template method
     *
     * @since  1.0.0
     *
     * @return \stdClass A instance of standard class to pass to the template method
     */
    public function getData();

    /**
     * Login Form Template
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function formTmpl(\stdClass $data);

    /**
     * Get the instance of the class
     *
     * @since  1.0.0
     *
     * @return RequestFormFacadeInterface The instance of the class
     */
    public static function getInstance();
}
