<?php
/**
 * Short-code Alert
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

use QiblaEvents\Functions as F;
use QiblaEvents\Plugin;
use QiblaEvents\TemplateEngine\Engine;

/**
 * Class Alert
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Alert extends AbstractShortcode
{
    /**
     * Alert constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->tag      = 'dl_alert';
        $this->defaults = array(
            'type' => 'info',
            'icon' => '',
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

        // Set the properties.
        $data->type    = $atts['type'];
        $data->content = F\ksesPost($content);

        // Retrieve the correct icon depending on the alert type.
        switch ($data->type) {
            case 'error':
                $icon = 'la-times';
                break;
            case 'warning':
                $icon = 'la-exclamation-triangle';
                break;
            case 'success':
                $icon = 'la-check';
                break;
            case 'info':
            default:
                $icon = 'la-info';
                break;
        }

        // The Icon Classes.
        $data->iconClass = array('la', $icon,);

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
     * @return string The short-code markup
     */
    public function callback($atts = array(), $content = '')
    {
        if (! $content) {
            return '';
        }

        // Parse the attributes.
        $atts = $this->parseAttrsArgs($atts);
        // Build Data.
        $data = $this->buildData($atts, $content);

        // Load the template.
        return $this->loadTemplate('dl_sc_alerts', $data, '/views/shortcodes/alerts.php');
    }

    /**
     * Alert Ajax template
     *
     * @since  1.0.0
     *
     * @throws \Exception In case the template path is incorrect or cannot be located.
     *
     * @return void
     */
    public static function alertAjaxTmpl()
    {
        $engine = new Engine('alert_ajax_tmpl', new \stdClass(), '/views/template/alert.php');
        $engine->render();
    }
}
