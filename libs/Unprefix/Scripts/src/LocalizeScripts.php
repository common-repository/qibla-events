<?php
namespace Unprefix\Scripts;

/**
 * Localize Scripts
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Unprefix\Scripts
 * @copyright  Copyright (c) 2017, Guido Scialfa
 * @license    GNU General Public License, version 2
 *
 * Copyright (C) 2017 Guido Scialfa <dev@guidoscialfa.com>
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
 * Class LocalizeScripts
 *
 * @since 1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 * @package Unprefix\Scripts
 */
class LocalizeScripts extends ScriptAbstract
{
    /**
     * Lazy Localized Scripts
     *
     * @since 1.0.0
     *
     * @param string $path   The path from which retrieve the data.
     * @param string $action The hook during when the localized must be preformed.
     *
     * @return void
     */
    public static function lazyLocalize($path, $action)
    {
        add_action($action, function () use ($path) {
            $instance = new LocalizeScripts(include \QiblaEvents\Plugin::getPluginDirPath($path));
            $instance->localizeScripts();
        });
    }

    /**
     * Localize Scripts
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function localizeScripts()
    {
        // Localize Scripts.
        $hook = is_admin() ? 'admin_print_footer_scripts' : 'wp_print_footer_scripts';
        $self = $this;
        add_action($hook, function () use ($self) {
            $self->insertLocalizedScripts();
        }, 1);
    }

    /**
     * Localize Scripts
     *
     * This is not the wp_localized_script of WordPress.
     * Instead this is a json that will be available from all scripts within the theme.
     *
     * @since  1.0.0
     * @access public
     *
     * @return void
     */
    public function insertLocalizedScripts()
    {
        try {
            // Get the list of the localized scripts.
            $list = $this->getList('localized');
        } catch (\Exception $e) {
            $list = array();
        }

        if (empty($list)) {
            return;
        }

        // Set the context.
        $context = is_admin() ? 'admin' : 'front';
        // Retrieve the correct elements based on context.
        $list = array_filter($list, function ($item) use ($context) {
            return $context === $item['handle'];
        });

        foreach ($list as $item) :
            // Check if the data should be print.
            // By default if the function doesn't exists we assume that the data must be printed in every page.
            $print = isset($item[1]) ? $item[1]() : true;

            if ($print) {
                /**
                 * Filter Item
                 *
                 * @since 1.0.0
                 *
                 * @param array $item The data of the current item to print.
                 */
                $item = apply_filters('qibla_events_insert_localized_script_item', $item);

                printf(
                    "<script type=\"text/javascript\">/* <![CDATA[ */\n %s \n/* ]]> */</script>",
                    'var ' . sanitize_key($item['name']) . ' = ' . wp_json_encode($item[0]) . ';'
                );
            }
        endforeach;
    }
}
