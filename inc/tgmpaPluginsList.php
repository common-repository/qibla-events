<?php
/**
 * TGMPA Plugins List
 *
 * @since     1.0.0
 * @author    Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright Copyright (c) 2016, Guido Scialfa
 * @license   GNU General Public License, version 2
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

use QiblaEvents\Plugin;
/*
 * Array of plugin arrays. Required keys are name and slug.
 * If the source is NOT from the .org repo, then source is also required.
 *
 * [
 *      'name'     => 'PluginName',
 *      'slug'     => 'plugin-slug',
 *      'required' => true|false
 * ]
 */

return array(
    // Qibla Events Importer.
    array(
        'name'               => esc_html__('Qibla Events Importer', 'qibla-events'),
        'slug'               => 'qibla-events-importer',
        'source'             => Plugin::getPluginDirPath('/libs/plugins/qibla-events-importer.zip'),
        'required'           => false,
        'version'            => '1.0.0',
        'force_deactivation' => true,
        'source_type'        => 'bundled',
    ),
    // Sassy Social Share.
    array(
        'name'        => esc_html__('Sassy Social Share', 'qibla-events'),
        'source'      => 'https://it.wordpress.org/plugins/sassy-social-share/',
        'slug'        => 'sassy-social-share',
        'required'    => false,
        'source_type' => 'repo',
    ),
);
