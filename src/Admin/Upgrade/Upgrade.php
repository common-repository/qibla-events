<?php
/**
 * Upgrader
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

namespace QiblaEvents\Admin\Upgrade;

/**
 * Interface Upgrader
 *
 * @since  1.0.1
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
interface Upgrade
{
    /**
     * If need Update
     *
     * If need update, do tasks.
     *
     * @since 1.0.1
     *
     * @return bool if need update or not.
     */
    public function needUpdate();

    /**
     * Do Tasks
     *
     * Tasks to perform if need update
     *
     * @since 1.0.1
     *
     * @return void
     */
    public function doTasks();

    /**
     * Upgrade Filter
     *
     * @since 1.0.1
     *
     * @param \WP_Upgrader $upgrader The \WP_Upgrader instance.
     * @param array        $args     The arguments passed by the `upgrader_process_complete` hook.
     *
     * @return void
     */
    public static function upgradeFilter($upgrader, $args);
}
