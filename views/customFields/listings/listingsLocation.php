<?php
/**
 * View Google Map
 *
 * @since 1.0.0
 *
 * Copyright (C) 2018 Alfio Piccione
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
?>

<?php if ($dataMapOptions || $data->locationInfoList) : ?>
    <div class="dllisting-location"<?php echo $dataMapOptions ?: '' ?>>
        <?php if ($data->locationInfoList) : ?>
            <address class="dllisting-address">
                <ul class="dllisting-meta">
                    <?php
                    foreach ($data->locationInfoList as $key => $item) :
                        $key = sanitize_key(str_replace('_', '-', $key));
                        if ($item) : ?>
                            <li class="dllisting-meta__item dllisting-meta__item--<?php echo esc_attr($key) ?>">
                                <b class="screen-reader-text"><?php echo esc_html($item['label']) . ' ' ?></b>
                                <i class="dllisting-meta-icon <?php echo esc_attr(F\sanitizeHtmlClass($item['icon_html_class'])) ?>"
                                   aria-hidden="true"></i>
                                <?php
                                // @codingStandardsIgnoreLine
                                echo F\ksesPost($item['data']) ?>
                            </li>
                        <?php
                        endif;
                    endforeach; ?>
                </ul>
            </address>
        <?php endif; ?>
    </div>
<?php endif; ?>