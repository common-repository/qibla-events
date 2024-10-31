<?php
namespace QiblaEvents\Admin\Page;

use QiblaEvents\Plugin;
use QiblaEvents\TemplateEngine\Engine as TEngine;

/**
 * Class Locations
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Page
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
 * Class Settings
 *
 * @since   1.0.0
 * @package QiblaEvents\Admin\Page
 */
class Settings extends AbstractMenuPage
{
    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            esc_html_x('Qibla Events', 'admin-menu', 'qibla-events'),
            esc_html_x('Qibla Events', 'admin-menu', 'qibla-events'),
            'qibla-events-options',
            Plugin::getPluginDirUrl('/assets/imgs/qibla-mark-16x16.png'),
            'edit_theme_options',
            array($this, 'callback'),
            5
        );
    }

    /**
     * Admin bar link
     *
     * @since 1.0.0
     *
     * @param $adminBar object The WP_Admin_Bar object
     */
    public function adminToolbar($adminBar)
    {
        if (! $adminBar instanceof \WP_Admin_Bar) {
            return;
        }

        $adminBar->add_menu(array(
            'id'    => $this->menuSlug,
            'title' => sprintf(
                '<span class="ab-icon"><img src="%s"/></span> <span class="ab-label">%s</span>',
                Plugin::getPluginDirUrl('/assets/imgs/qibla-mark-16x16.png'),
                $this->menuTitle
            ),
            'href'  => esc_url(admin_url('admin.php?page=' . $this->menuSlug)),
        ));
    }

    /**
     * Content Page Callback
     *
     * @since  1.0.0
     */
    public function callback()
    {
        // Initialize Data.
        $data = new \stdClass();

        $data->title = esc_html__('Plugin Options', 'qibla-events');

        $engine = new TEngine('admin_theme_option', $data, '/views/settings/adminSettingsPage.php');
        $engine->render();
    }
}
