<?php
/**
 * Found Posts
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
?>
<p class="dlposts-found">
    <strong>
        <span class="dlposts-found__of"><?php echo intval($data->numberOf) ?></span>
        <span class="dlposts-found__number-separator"><?php echo esc_html($data->numSeparator) ?></span>
        <span class="dlposts-found__number"><?php echo intval($data->number) ?></span>
        <span class="dlposts-found__label"><?php echo esc_html(sanitize_text_field($data->label)) ?></span>

        <span class="dlposts-found__current-page-label">
            <?php echo F\ksesPost($data->currObjLabel) ?>
        </span>
    </strong>
</p>
