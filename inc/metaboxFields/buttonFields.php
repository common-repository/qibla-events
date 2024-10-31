<?php
/**
 * buttonFields.php
 *
 * @since 1.0.0
 * @package    ${NAMESPACE}
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

use QiblaEvents\Form\Factories\FieldFactory;
use QiblaEvents\Functions as F;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

return array(
    /**
     * Button Url
     *
     * @since 1.0.0
     */
    'qibla_mb_button_url:text'  => $fieldFactory->base(array(
        'type'        => 'url',
        'name'        => 'qibla_mb_button_url',
        'attrs'       => array(
            'value'       => F\getPostMeta('_qibla_mb_button_url'),
            'placeholder' => __('http://'),
            'class'       => array('widefat'),
        ),
        'label'       => esc_html__('Button url', 'qibla-events'),
        'description' => esc_html__('Add the url for the button', 'qibla-events'),
    )),

    /**
     * Button Text
     *
     * @since 1.0.0
     */
    'qibla_mb_button_text:text' => $fieldFactory->base(array(
        'type'        => 'text',
        'name'        => 'qibla_mb_button_text',
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_button_text'),
            'class' => array('widefat'),
        ),
        'label'       => esc_html__('Button text', 'qibla-events'),
        'description' => esc_html__('Add the text of the button', 'qibla-events'),
    )),

    /**
     * Button Target
     *
     * @since 1.0.0
     */
    'qibla_mb_target_link:checkbox' => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_mb_target_link',
        'value'       => F\getPostMeta('_qibla_mb_target_link'),
        'label'       => esc_html__('Button target', 'qibla-events'),
        'description' => esc_html__('Make this as internal link (default: external)', 'qibla-events'),
    )),
);
