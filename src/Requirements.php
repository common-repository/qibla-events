<?php
namespace QiblaEvents;

/**
 * Requirements
 *
 * @since     1.0.0
 * @package   QiblaEvents
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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

/**
 * Class Requirements
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Requirements
{
    /**
     * Requirements List
     *
     * Es.
     *  [
     *      'key' => [
     *          'current'  => '',
     *          'required' => '',
     *          'compare'  => '<|<=|>=|!=|===|==',
     *          'type'     => 'error|warning|info',
     *          'message'  => esc_html__('The message', 'text-domain')
     *      ]
     *  ]
     *
     * @since  1.0.0
     *
     * @var array A list of requirements for this plugin
     */
    private $list;

    /**
     * Construct
     *
     * @since 1.0.0
     *
     * @param array $list The list of the stuffs to compare.
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * Check Requirements
     *
     * @since  1.0.0
     *
     * @return array The list containing all of the items compared that return true.
     */
    public function check()
    {
        $list = array();

        foreach ($this->list as $key => $item) {
            // True we add the message to the list, false we skip it.
            if (version_compare($item['current'], $item['required'], $item['compare'])) {
                $list[$key] = $item;
            }
        }

        return $list;
    }
}
