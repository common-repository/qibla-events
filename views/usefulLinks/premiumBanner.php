<?php
/**
 * Premium Banner View
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

defined('WPINC') || die; ?>

<div class="dlbanner-premium notice notice-warning is-dismissible">
    <img src="<?php echo esc_url($data->thumbnail['url']) ?>" alt="<?php echo esc_attr($data->thumbnail['alt']) ?>"/>
    <div>
        <h2>
            <?php echo sanitize_text_field($data->title) ?>
        </h2>
        <p><?php echo sanitize_text_field($data->subtitle) ?></p>
        <a href="<?php echo esc_url($data->submitBtn['url']) ?>" target="_blank">
        <span>
            <?php echo sanitize_text_field($data->submitBtn['label']) ?>
        </span>
        </a>
    </div>
</div>
