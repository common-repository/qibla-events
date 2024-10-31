<?php
/**
 * shareAndWish View
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

use QiblaEvents\Functions as F; ?>

<?php if ($data->shareAndWish) : ?>
    <div <?php F\scopeClass('actions') ?>>
        <ul class="dlactions-lists">
            <?php if ($data->shareAndWish['has_share']) : ?>
                <li class="dlactions-lists__item dlactions-lists__item--share">
                    <a id="share_popup_trigger" class="dlshare" href="javascript:">
                        <?php echo esc_html($data->shareAndWish['share_label']) ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
        <?php if ($data->shareAndWish['has_share']) : ?>
            <div class="dlshare-popup">
                <?php echo do_shortcode('[Sassy_Social_Share]'); ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
