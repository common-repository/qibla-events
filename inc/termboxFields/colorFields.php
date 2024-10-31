<?php
/**
 * Terms Meta Color Fields
 *
 * @author  Alfio Piccione <alfio.piccione@gmail.com>
 *
 * @license GPL 2
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
use QiblaEvents\Form\Factories\FieldFactory;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

$color = F\getTermMeta('_qibla_tb_terms_color', self::$currTerm->term_id) ?: '#f26522';
/**
 * Filter Terms Meta Color Fields
 *
 * @since  1.0.0
 *
 * @param array $array The list of the header meta-box fields.
 */
return apply_filters('qibla_tb_inc_terms_color_fields', array(
    /**
     * Terms Color
     *
     * @since 1.0.0
     */
    'qibla_tb_terms_color:color_picker' => $fieldFactory->termbox(array(
        'type'        => 'color_picker',
        'name'        => 'qibla_tb_terms_color',
        'attrs'       => array(
            'value' => $color,
        ),
        'label'       => esc_html__('Term Color', 'qibla-events'),
        'description' => esc_html__('Select your color.', 'qibla-events'),
        'display'     => array($this, 'displayField'),
    )),
));
