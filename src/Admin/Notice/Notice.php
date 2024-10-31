<?php
/**
 * Admin Notice
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Notice
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

namespace QiblaEvents\Admin\Notice;

use QiblaEvents\Functions as F;

/**
 * Class Notice
 *
 * @todo Implement The TemplateInterface
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Notice
{
    /**
     * Notice Message
     *
     * @since  1.0.0
     *
     * @var string The primary notice message.
     */
    protected $message;

    /**
     * Notice Type
     *
     * Es. error, warning, info, success.
     *
     * @see    https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices for more info.
     *
     * @since  1.0.0
     *
     * @var string The notice type.
     */
    protected $type;

    /**
     * Is Dismissible
     *
     * @since  1.0.0
     *
     * @var bool If the notice is dismissible or not.
     */
    protected $isDismissible;

    /**
     * Get Html Class
     *
     * @since  1.0.0
     *
     * @return string The html class list for the notice container.
     */
    protected function getHtmlClass()
    {
        return implode(' ', array(
            'notice',
            'notice-' . esc_attr(sanitize_key($this->type)),
            $this->isDismissible ? 'is-dismissible' : '',
        ));
    }

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param string $message     The main message to show above the list of notices.
     * @param string $type        The type of the notice. Optional. Default 'info'.
     * @param bool   $dismissible If the notice must be dismissible or not. Optional. Default to false.
     */
    public function __construct($message, $type = 'info', $dismissible = false)
    {
        $this->message       = $message;
        $this->isDismissible = $dismissible;
        $this->type          = $type;
    }

    /**
     * Notice
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function notice()
    {
        // No messages to show.
        if (! $this->message) {
            return;
        }
        ?>
        <div class="<?php echo esc_attr($this->getHtmlClass()) ?>">
            <p>
                <?php
                // @codingStandardsIgnoreLine
                echo F\ksesPost($this->message) ?>
            </p>
        </div>
        <?php
    }
}
