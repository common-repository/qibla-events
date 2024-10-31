<?php
namespace QiblaEvents\Debug;

/**
 * Class Debug
 *
 * @since      1.0.0
 * @package    QiblaEvents\Debug
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
 * Class Debug
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Debug
{
    /**
     * Message
     *
     * @since  1.0.0
     *
     * @var string The message to show to debug
     */
    protected $message;

    /**
     * Debug constructor.
     *
     * @since 1.0.0
     *
     * @param string $message The message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get Message
     *
     * @since  1.0.0
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Display
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function display()
    {
        printf(
            '<div class="dlalert dlalert--warning"><i class="la la-warning" aria-hidden="true"></i><strong>Debug:</strong> %s</div>',
            esc_html($this->getMessage())
        );
    }
}
