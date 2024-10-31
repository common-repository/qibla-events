<?php
use \QiblaEvents\Functions as F;
use QiblaEvents\Form\Factories\FieldFactory;

/**
 * Thumbnail Term-box Fields
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
 * Filter Thumbnail Terms Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the term-box fields.
 */
return apply_filters('qibla_tb_inc_thumbnail_fields', array(
    /**
     * Term Thumbnail
     *
     * @since 1.0.0
     */
    'qibla_tb_thumbnail:media_image' => $fieldFactory->termbox(array(
        'type'           => 'media_image',
        'name'           => 'qibla_tb_thumbnail',
        'attrs'          => array(
            'value' => F\getTermMeta('_qibla_tb_thumbnail', self::$currTerm->term_id),
        ),
        'label'          => esc_html__('Featured Image', 'qibla-events'),
        'desc_container' => 'p',
        'description'    => esc_html__('Upload or Select the Featured Image', 'qibla-events'),
        'display'        => array($this, 'display'),
    )),
));
