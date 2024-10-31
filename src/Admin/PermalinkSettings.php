<?php
/**
 * PermalinkSettings
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace QiblaEvents\Admin;

use QiblaEvents\Functions as F;
use QiblaEvents\Form\Factories\FieldFactory;
use QiblaEvents\Plugin;

/**
 * Class PermalinkSettings
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class PermalinkSettings
{
    /**
     * The Section Name
     *
     * @since  1.0.0
     *
     * @var string The section name where show the fields
     */
    const SECTION_NAME = 'posttype_taxonomy_permalink_settings_section';

    /**
     * The Option Name
     *
     * @since  1.0.0
     *
     * @var string The option name where the permalinks are stored
     */
    const OPTION_NAME = 'qibla_permalinks';

    /**
     * Section Arguments
     *
     * @since  1.0.0
     *
     * @var array A list of arguments to pass to add_settings_section()
     */
    protected static $sectionArgs;

    /**
     * Fields Arguments
     *
     * @since  1.0.0
     *
     * @var array The list of the fields and their arguments
     */
    protected static $fieldArgs;

    /**
     * PermalinkSettings constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        static::$sectionArgs = array(
            static::SECTION_NAME,
            esc_html__('Qibla Post Type &amp; Taxonomies Permalinks', 'qibla-events'),
            array($this, 'sectionCb'),
            'permalink',
        );

        static::$fieldArgs = include Plugin::getPluginDirPath('/inc/permalinkSettingsFields.php');
    }

    /**
     * Field Factory
     *
     * Create the fields
     *
     * @since  1.0.0
     *
     * @param array $args The arguments needed to build the field.
     *
     * @return void
     */
    public function fieldFactory(array $args)
    {
        // Get the field.
        $fieldArgs    = static::$fieldArgs[$args['key']];
        $fieldFactory = new FieldFactory();

        // Build and show the field.
        // @codingStandardsIgnoreLine
        echo F\ksesPost($fieldFactory->base($fieldArgs['type']));
    }

    /**
     * Section Callback
     *
     * Only add the nonce to verify on submit.
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function sectionCb()
    {
        wp_nonce_field('qibla_permalink', 'qibla_permalink', false);
    }

    /**
     * Store Options
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function store()
    {
        // Verify Nonce.
        // @codingStandardsIgnoreLine
        $nonce = F\filterInput($_POST, 'qibla_permalink', FILTER_SANITIZE_STRING);

        if (! wp_verify_nonce($nonce, 'qibla_permalink')) {
            return;
        }

        $fieldsList = array();

        foreach (static::$fieldArgs as $key => $args) {
            // Get the value.
            // Sanitize as string because we are working with permalinks.
            // @codingStandardsIgnoreLine
            $value = \QiblaEvents\Functions\filterInput($_POST, $key, FILTER_SANITIZE_STRING);
            // Then sanitize via Wp function.
            $value = call_user_func($args['sanitizeCb'], $value);
            // At last store the value.
            $fieldsList[$key] = $value;
        }

        if ($fieldsList) {
            update_option(static::OPTION_NAME, $fieldsList, true);
            // Flush the rewrite rules.
            flush_rewrite_rules();
        }
    }

    /**
     * Register
     *
     * Register the permalink fields section
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function register()
    {
        // Register the setting section.
        call_user_func_array('add_settings_section', static::$sectionArgs);
        // Add the Fields.
        foreach (static::$fieldArgs as $fieldArg) {
            call_user_func_array('add_settings_field', $fieldArg);
            register_setting('permalink', $fieldArg[0], $fieldArg['sanitizeCb']);
        }
    }

    /**
     * The Permalink Register Filter
     *
     * @since 1.0.0
     */
    public static function permalinkSettingsFilter()
    {
        $instance = new static;
        $instance->store();
        $instance->register();

        // After done remove the filter.
        remove_action('admin_init', 'QiblaEvents\\Admin\\PermalinkSettings::permalinkSettingsFilter', 0);
    }
}
