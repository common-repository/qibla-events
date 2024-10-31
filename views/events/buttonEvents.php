<?php
/**
 * buttonEvents
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

use QiblaEvents\Functions as F;

// Button classes.
$buttonMeta['btn_class'] = 'dlbtn dlbtn--tiny';
if (\QiblaEvents\ListingsContext\Context::isSingleListings()) {
    $buttonMeta['btn_class'] = 'dlbtn dlbtn--wide';
}

if ($data && isset($data->buttonMeta) && is_array($data->buttonMeta)) {

    $btnClass = '' !== $data->buttonMeta['btn_class'] ? $data->buttonMeta['btn_class'] : $buttonMeta;
    $btnUrl   = '' !== $data->buttonMeta['url'] ? $data->buttonMeta['url'] : '#';
    $btnText  = '' !== $data->buttonMeta['text'] ?
        $data->buttonMeta['text'] : esc_html__('Placeholder text', 'qibla-events');
    $target = 'off' === $data->buttonMeta['target'] ? ' target="_blank" rel="noopener"' : '';

    if ('' !== $data->buttonMeta['url'] && '' !== $data->buttonMeta['text']) {
        echo '<div class="dlevent-ticket-wrapper">';
        echo sprintf(
            '<a class="%s event-ticket-button" href="%s"%s>%s</a>',
            esc_attr(F\sanitizeHtmlClass($btnClass)),
            esc_url($btnUrl),
            esc_html($target),
            esc_html($btnText)
        );
        echo '</div>';
    }
}
