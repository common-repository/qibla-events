<?php
namespace QiblaEvents\Admin\Termbox;

use QiblaEvents\Functions as F;

/**
 * Term box
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Termbox
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

/**
 * Class AbstractTermbox
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractTermbox implements TermboxInterface
{
    /**
     * Term Box Arguments
     *
     * @since  1.0.0
     *
     * @var array A list of arguments to build the box
     */
    protected $termBoxArgs;

    /**
     * The Current Term
     *
     * @since  1.0.0
     *
     * @var \WP_Term The term object
     */
    protected static $currTerm;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @throws \Exception Because of getTermAdmin().
     *
     * @param array $termBoxArgs A list of arguments for this box.
     */
    public function __construct($termBoxArgs)
    {
        // Set to all public screens by default.
        if (! isset($termBoxArgs['screen'])) {
            $termBoxArgs['screen'] = get_taxonomies(array(
                'public' => true,
            ));
        }

        self::$currTerm    = F\getTermAdmin();
        $this->termBoxArgs = $termBoxArgs;
    }

    /**
     * Get Arguments
     *
     * @since  1.0.0
     *
     * @return array The term-box Arguments
     */
    public function getArgs()
    {
        return $this->termBoxArgs;
    }
}
