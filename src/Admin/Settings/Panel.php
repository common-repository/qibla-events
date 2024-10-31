<?php
/**
 * Class Settings Panel
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Settings
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

namespace QiblaEvents\Admin\Settings;

use QiblaEvents\Admin\Panel\Navigation;
use QiblaEvents\Admin\Panel\Panel as BasePanel;
use QiblaEvents\Plugin;

/**
 * Class Panel
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Panel extends BasePanel
{
    /**
     * Theme Info
     *
     * @since  1.0.0
     *
     * @var \WP_Theme The theme instance
     */
    private $pluginData;

    /**
     * Constructor
     *
     * @since  1.0.0
     *
     * @param Navigation $navigation A Navigation instance for this panel.
     */
    public function __construct(Navigation $navigation)
    {
        // Set the plugin data.
        $this->pluginData = get_plugin_data(Plugin::getPluginDirPath('/index.php'), false);

        parent::__construct($this->pluginData['Name'], $navigation);
    }

    /**
     * Header
     *
     * @todo Need a view
     *
     * @since  1.0.0
     *
     * @return void
     */
    protected function header()
    {
        ?>
        <div class="dm-panel__sidebar">
            <header class="dm-panel__header">
                <h1 class="dm-panel__title">
                    <?php echo esc_html($this->title) ?>
                </h1>

                <span class="dm-panel__theme-version">
                    <?php printf(
                        esc_html__('Version: %s', 'qibla-events'),
                        esc_html($this->pluginData['Version'])
                    ); ?>
                </span>

                <span class="dm-panel__credits">
                    <?php echo sprintf(
                        'By <a href="%s">%s</a>',
                        esc_url('http://appandmap.com/en/'),
                        esc_html__('App&Map', 'qibla-events')
                    ); ?>
                </span>
            </header>
            <?php $this->navigation->render(); ?>
        </div>
        <?php
    }
}
