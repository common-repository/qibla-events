<?php

use QiblaEvents\Functions as F;
use QiblaEvents\Form\Factories\FieldFactory;
use QiblaEvents\IconsSet;

/**
 * Icon Term-box Fields
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

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Icon Terms Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the term-box fields.
 */
return apply_filters('qibla_tb_inc_icon_fields', array(
    /**
     * Icon Set
     *
     * @since 1.0.0
     */
    'qibla_tb_icon:iconlist' => $fieldFactory->termbox(array(
        'type'           => 'icon_list',
        'name'           => 'qibla_tb_icon',
        'value'          => F\getTermMeta('_qibla_tb_icon', self::$currTerm->term_id),
        'options'        => array(
            new IconsSet\Fontawesome(),
            new IconsSet\Lineawesome(),
            new IconsSet\Foundation(),
            new IconsSet\Material(),
        ),
        'label'          => esc_html__('Select Icon', 'qibla-events'),
        'desc_container' => 'p',
        'description'    => esc_html__('Select the Icon to associate to this term', 'qibla-events'),
        'display'        => array($this, 'display'),
    )),
));
