<?php
/**
 * Button
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

use QiblaEvents\IconsSet\Icon;
use QiblaEvents\Plugin;

/**
 * Class Button
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Button extends AbstractShortcode
{
    /**
     * Create the icon class attribute
     *
     * @since 1.0.0
     *
     * @param string $icon The icon style may be a compact version of the icon or a simple icon string.
     *
     * @return string
     */
    private function buildIconClassAttribute($icon)
    {
        $newIcon = str_replace('la ', '', $icon);

        // Try to retrieve the btn style from Icon compact version.
        // This is only for visual composer support right know.
        if (false !== strpos($icon, '::')) {
            try {
                $icon    = new Icon($icon);
                $newIcon = $icon->getIconSlug();
            } catch (\InvalidArgumentException $e) {
                $newIcon = '';
            }
        }

        $icon = $newIcon;

        return $icon;
    }

    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->tag      = 'dl_button';
        $this->defaults = array(
            'label'       => '',
            'style'       => '',
            'icon_after'  => '',
            'icon_before' => '',
            'url'         => '',
        );
    }

    /**
     * Build Model
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code attributes.
     * @param string $content The content within the short-code.
     *
     * @return \stdClass The data instance or null otherwise.
     */
    public function buildData(array $atts, $content = '')
    {
        // Initialize Data Instance.
        $data = new \stdClass();

        $style              = sanitize_key($atts['style']);
        $data->label        = $atts['label'];
        $data->url          = $atts['url'];
        $data->btnHtmlClass = 'dlbtn' . ($style ? " dlbtn--{$style}" : '');
        $data->iconBefore   = $this->buildIconClassAttribute($atts['icon_before']);
        $data->iconAfter    = $this->buildIconClassAttribute($atts['icon_after']);

        return $data;
    }

    /**
     * Callback
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code's attributes.
     * @param string $content The content within the short-code.
     *
     * @return string The shortcode markup
     */
    public function callback($atts = array(), $content = '')
    {
        $atts = $this->parseAttrsArgs($atts);

        if (! $atts['label']) {
            return '';
        }

        // Build Data.
        $data = $this->buildData($atts, $content);

        return $this->loadTemplate('dl_sc_button', $data, '/views/shortcodes/button.php');
    }
}
