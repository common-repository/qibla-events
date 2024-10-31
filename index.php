<?php
/**
 * WordPress Directory Plugin
 *
 * @link    http://www.southemes.com
 * @package QiblaEvents
 * @version 1.0.2
 *
 * @wordpress-plugin
 * Plugin Name: Qibla Events
 * Plugin URI: https://southemes.com/demos/qiblaplugin/qibla-events/
 * Description: Qibla events is a completely free event listing plugin for WordPress. Use Qibla events plugin if you want to install an event archive or directory within your site.
 * Version: 1.0.2
 * Author: App&Map <luca@appandmap.com>
 * Author URI: http://appandmap.com/en/
 * License: GPL2
 * Text Domain: qibla-events
 *
 * Copyright (C) 2018 Alfio Piccione <alfio.piccione@gmail.com>
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

if (! defined('QB_ENV')) {
    define('QB_ENV', 'prod');
}

if (! defined('UPX_ENV')) {
    define('UPX_ENV', 'prod');
}

// Base requirements.
require_once untrailingslashit(plugin_dir_path(__FILE__)) . '/src/Plugin.php';
require_once QiblaEvents\Plugin::getPluginDirPath('/requires.php');
require_once QiblaEvents\Plugin::getPluginDirPath('/src/Autoloader.php');

// Setup Auto-loader.
$loaderMap = include QiblaEvents\Plugin::getPluginDirPath('/inc/autoloaderMapping.php');
$loader    = new \QiblaEvents\Autoloader();

$loader->addNamespaces($loaderMap);
$loader->register();

// Register the activation hook.
register_activation_hook(__FILE__, array('QiblaEvents\\Activate', 'activate'));
register_deactivation_hook(__FILE__, array('QiblaEvents\\Deactivate', 'deactivate'));

add_action('plugins_loaded', function () {

    // Check Qibla Framework is active.
    if (! \QiblaEvents\Functions\checkQiblaFramework()) :
        $filters = array();

        // Retrieve and build the filters based on context.
        // First common filters, than admin or front-end filters.
        // Filters include actions and filters.
        $filters = array_merge($filters, include QiblaEvents\Plugin::getPluginDirPath('/inc/filters.php'));

        // Add filters based on context.
        if (is_admin()) {
            $filters = array_merge($filters, include QiblaEvents\Plugin::getPluginDirPath('/inc/filtersAdmin.php'));
        } else {
            $filters = array_merge($filters, include QiblaEvents\Plugin::getPluginDirPath('/inc/filtersFront.php'));
        }

        // Check if is an ajax request.
        // If so, include even the filters for the ajax actions.
        if (QiblaEvents\Functions\isAjaxRequest()) {
            $filters = array_merge($filters, include QiblaEvents\Plugin::getPluginDirPath('/inc/filtersAjax.php'));
        }

        // Let's start the game.
        $init = new QiblaEvents\Init(new QiblaEvents\Loader(), $filters);
        $init->init();

        // Then load the plugin text-domain.
        load_plugin_textdomain('qibla-events', false, '/qibla-events/languages/');

    else:
        // Disable the plugin.
        \QiblaEvents\Functions\disablePlugin();
    endif;

    /**
     * Did Init
     *
     * @since 1.0.0
     */
    do_action('qibla_events_did_init');
}, 20);
