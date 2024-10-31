<?php
/**
 * Color
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Termbox
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace QiblaEvents\Admin\Termbox;

use QiblaEvents\Plugin;

/**
 * Class Color
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Color extends AbstractTermboxForm
{
    /**
     * Class Color
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct(array(
            'id'            => 'terms',
            'title'         => esc_html__('Term Color', 'qibla-events'),
            'callback'      => array($this, 'callBack'),
            'callback_args' => array(),
            'screen'        => array(
                'event_categories',
            ),
        ));

        parent::setFields(include Plugin::getPluginDirPath('/inc/termboxFields/colorFields.php'));
    }
}
