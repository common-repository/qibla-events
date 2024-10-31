<?php
/**
 * Social Login Provider View View
 *
 * @since      1.0.0
 * @author     Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, Alfio Piccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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
?>

<a rel="nofollow" href="<?php echo esc_url($data->authenticateUrl); ?>"
   class="wp-social-login-provider wp-social-login-provider-<?php echo esc_attr($data->providerID); ?>"
   data-provider="<?php echo esc_attr($data->providerID) ?>">
    <span class="wp-social-login-provider-text">
        <?php echo esc_html(sanitize_text_field($data->providerName)) ?>
    </span>
</a>
