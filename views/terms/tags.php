<?php
/**
 * View Tags
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

<div <?php F\scopeClass('listing-main-section') ?>>
    <div <?php F\scopeClass('container', '', 'flex') ?>>
        <table <?php F\scopeClass('listing-main-section', 'content') ?>>
            <?php if ($data->title) : ?>
                <thead <?php F\scopeClass('terms-list', 'header') ?>>
                <tr <?php F\scopeClass('terms-list', 'row') ?>>
                    <th <?php F\scopeClass('listing-main-section', 'title') ?>>
                        <?php echo esc_html(sanitize_text_field($data->title)) ?>
                    </th>
                </tr>
                </thead>
            <?php endif; ?>

            <tbody <?php F\scopeClass('terms-list') ?>>
            <?php foreach (array_chunk($data->list, 2) as $terms) : ?>
                <tr <?php F\scopeClass('terms-list', 'row') ?>>
                    <?php foreach ($terms as $term) : ?>
                        <td <?php F\scopeClass('terms-list', 'item') ?>>
                            <i class="<?php echo esc_attr(F\sanitizeHtmlClass($term['icon_html_class'])) ?>"></i>
                            <a <?php F\scopeClass('terms-list', 'item-label') ?>
                                    href="<?php echo esc_url($term['href']) ?>">
                                <?php echo esc_html(sanitize_text_field($term['label'])) ?>
                            </a>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
