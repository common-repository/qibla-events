<?php
/**
 * Short-code Register
 *
 * @since      1.0.0
 * @package    QiblaEvents\Shortcode
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

namespace QiblaEvents\Shortcode;

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
     * Short-codes List
     *
     * @since  1.0.0
     *
     * @var array The list of the shortcodes instances
     */
    private $shortcodes;

    /**
     * Constructor
     *
     * @since  1.0.0
     *
     * @param array $shortcodes The shortcodes to register
     */
    public function __construct(array $shortcodes)
    {
        $this->shortcodes = $shortcodes;
    }

    /**
     * Register Short-codes
     *
     * @since  1.0.0
     *
     * @uses   add_shortcode() To register the short-code
     * @uses   sanitize_key() To sanitize the tag name of the short-code
     *
     * @return void
     */
    public function register()
    {
        // Add Short-codes.
        foreach ($this->shortcodes as $shortcode) {
            // Be sure the shortcode name contain only the allowed characters.
            add_shortcode(sanitize_key($shortcode->getTag()), array($shortcode, 'callback'));

            // Add support for the visual Composer plugin if allowed by the shortcode.
            $self = $this;
            function_exists('vc_lean_map') and add_action('vc_before_init', function () use ($shortcode, $self) {
                // Try only if the shortcode implements the ShortcodeVisualComposerInterface interface.
                if ($shortcode instanceof ShortcodeVisualComposerInterface) {
                    vc_lean_map($shortcode->getTag(), array($shortcode, 'visualComposerMap'));
                }
            });
        }
    }
}
