<?php
use QiblaEvents\Functions as F;

/**
 * View Terms List
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
?>

<?php if ($data->termsList) : ?>
    <nav class="dllisting-terms">
        <ul class="dllisting-terms__list">
            <?php foreach ($data->termsList as $key => $term) : ?>
                <li class="dllisting-terms__item">
                    <a class="dllisting-terms__link" href="<?php echo esc_url($term['link']) ?>">
                        <i class="<?php echo esc_attr(F\sanitizeHtmlClass($term['icon_html_class'])) ?>"></i>
                        <?php echo esc_html(sanitize_text_field($term['label'])) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>