<?php
/**
 * View Modal
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

<section <?php F\scopeID('modal') ?>
         data-context="<?php echo esc_attr(sanitize_key($data->args['context'])) ?>"
         data-showclosebtn="<?php echo esc_html(sanitize_text_field($data->args['show_close_button'])) ?>">

    <div class="<?php echo esc_html(F\sanitizeHtmlClass($data->args['class_container'])) ?>">

        <?php if ($data->args['title']) : ?>
            <header <?php F\scopeClass('modal', 'header') ?>>
                <h2 <?php F\scopeClass('modal', 'title') ?>>
                    <?php echo esc_html(sanitize_text_field($data->args['title'])) ?>
                </h2>

                <?php if ($data->args['subtitle']) : ?>
                    <p <?php F\scopeClass('dlmodal', 'subtitle') ?>>
                        <?php echo esc_html(sanitize_text_field($data->args['subtitle'])) ?>
                    </p>
                <?php endif; ?>
            </header>
        <?php endif;

        call_user_func($data->callback); ?>
    </div>

</section>
