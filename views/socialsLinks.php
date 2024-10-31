<?php
/**
 * View Social Links
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

use QiblaEvents\Functions as F; ?>

<?php if ($data->linksList) : ?>
    <div class="dlsocials-links">
        <?php if (! empty($data->showTitle)) : ?>
            <h4 class="screen-reader-text">
                <?php echo esc_html__('Follow Us On:', 'qibla-events') ?>
            </h4>
        <?php endif; ?>
        <ul class="dlsocials-links__list">
            <?php foreach ($data->linksList as $item) : ?>

                <li class="dlsocials-links__item">
                    <a href="<?php echo esc_url($item->href) ?>"
                       class="<?php echo F\sanitizeHtmlClass($item->class) ?>">
                        <span class="dlsocials-links__label"><?php echo esc_html($item->label) ?></span>
                    </a>
                </li>

                <?php
            endforeach; ?>
        </ul>
    </div>
<?php endif; ?>