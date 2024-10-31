<?php
/**
 * eventsLocation
 *
 * @since      1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

use QiblaEvents\Functions as F;

$dataMapOptions = $data->mapOptions ? ' data-map-options="' . esc_attr($data->mapOptions) . '"' : '';

if ($dataMapOptions && $data->locationInfoList) : ?>
    <?php
    // Retrieved a tag from address data.
    $aTag = preg_replace('#(<a.*?>).*?(</a>)#', '$1$2', $data->locationInfoList['address']['data']);
    // Set new text in address data link.
    $mapLink = preg_replace_callback('#(<a.*?>).*?(</a>)#', function ($matches) {
        return $matches[1] . esc_html__('See on google map', 'qibla-events') . $matches[2];
    }, $aTag); ?>

    <div id="location_maps" class="dllisting-location"<?php echo $dataMapOptions ?: '' ?>>
        <div class="dllisting-location__header-map">
            <div class="dllisting-meta">
                <h4 class="dllisting-meta__item dllisting-meta__item--address">
                    <?php echo esc_html(F\ksesPost(strip_tags($data->locationInfoList['address']['data'], ''))); ?>
                </h4>
                <div class="dllisting-meta__item dllisting-meta__item--map-link">
                    <?php echo wp_kses_post($mapLink); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>