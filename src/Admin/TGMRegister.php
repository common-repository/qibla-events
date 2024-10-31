<?php
/**
 * Class Tgm Plugin Activation Register
 *
 * @see        http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @since      1.0.0
 * @package    Qibla\Admin
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    GNU General Public License, version 2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaEvents\Admin;

use QiblaEvents\Plugin;

/**
 * Class TGMRegister
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class TGMRegister
{
    /**
     * Plugins List
     *
     * @var array The plugins List
     */
    protected $pluginsList;

    /**
     * The Class Configuration
     *
     * @since 1.0.0
     *
     * @var array The global configuration
     */
    protected $config;

    /**
     * TGMRegister constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->pluginsList = include Plugin::getPluginDirPath('/inc/tgmpaPluginsList.php');

        /*
         * Array of configuration settings. Amend each line as needed.
         *
         * TGMPA will start providing localized text strings soon. If you already have translations of our standard
         * strings available, please help us make TGMPA even better by giving us access to these translations or by
         * sending in a pull-request with .po file(s) with the translations.
         *
         * Only uncomment the strings in the config array if you want to customize the strings.
         */
        $this->config = array(
            'id'           => 'qibla-events',
            // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',
            // Default absolute path to bundled plugins.
            'menu'         => 'qibla-events-install-plugins',
            // Menu slug.
            'parent_slug'  => 'themes.php',
            // Parent menu slug.
            'capability'   => 'edit_theme_options',
            // Capability needed to view plugin install page, should be a capability associated with
            // the parent menu used.
            'has_notices'  => true,
            // Show admin notices or not.
            'dismissable'  => true,
            // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',
            // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,
            // Automatically activate plugins after installation or not.
            'message'      => '',
            'strings'      => array(
                'notice_can_install_required'    => _n_noop(
                /* translators: 1: plugin name(s). */
                    'This plugin requires the following plugin: %1$s.',
                    'This plugin requires the following plugins: %1$s.',
                    'tgmpa'
                ),
                'notice_can_install_recommended' => _n_noop(
                /* translators: 1: plugin name(s). */
                    'This plugin recommends the following plugin: %1$s.',
                    'This plugin recommends the following plugins: %1$s.',
                    'tgmpa'
                ),
                'install_link'                    => _n_noop(
                    'Begin installing plugin',
                    'Begin installing plugins',
                    'tgmpa'
                ),
                'update_link'                     => _n_noop(
                    'Begin updating plugin',
                    'Begin updating plugins',
                    'tgmpa'
                ),
                'activate_link'                   => _n_noop(
                    'Begin activating plugin',
                    'Begin activating plugins',
                    'tgmpa'
                ),
            ),
        );
    }

    /**
     * Register the required plugins for this theme.
     *
     * In this example, we register five plugins:
     * - one included with the TGMPA library
     * - two from an external source, one from an arbitrary source, one from a GitHub repository
     * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
     *
     * The variables passed to the `tgmpa()` function should be:
     * - an array of plugin arrays;
     * - optionally a configuration array.
     * If you are not changing anything in the configuration array, you can remove the array and remove the
     * variable from the function call: `tgmpa( $plugins );`.
     * In that case, the TGMPA default settings will be used.
     *
     * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
     */
    public function registerRequiredPlugins()
    {
        tgmpa($this->pluginsList, $this->config);
    }
}
