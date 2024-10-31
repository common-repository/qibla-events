<?php

use QiblaEvents\Functions as F;

/**
 * View Searcher Form
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

<div class="<?php echo esc_attr(F\sanitizeHtmlClass($data->class)) ?>">
    <form action="<?php echo esc_url($data->action) ?>"
          autocomplete="off"
          id="<?php echo esc_attr($data->id) ?>"
          class="dlsearch__form"
          name="dlsearch_form"
          method="<?php echo esc_attr($data->method) ?>">

        <div class="dlsearch__form-fields">
            <?php
            foreach ($data->fields as $field) {
                $field->doField();
            } ?>
        </div>

        <?php foreach ($data->postTypes as $type) : ?>
            <input type="hidden"
                   name="<?php echo esc_attr($data->postTypesInputNameKey) ?>"
                   value="<?php echo esc_attr($type) ?>"/>
        <?php endforeach; ?>

        <button class="dlsearch__form-submit">
            <i class="<?php echo esc_attr($data->submitIcon->getHtmlClass()) ?> " aria-hidden="true"></i>
            <span><?php echo esc_html($data->submitLabel) ?></span>
        </button>
    </form>
</div>
