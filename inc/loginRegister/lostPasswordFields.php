<?php
/**
 * Lost Password Fields
 *
 * @author    Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright Copyright (c) 2018, Alfio Piccione
 * @license   GNU General Public License, version 2
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

use QiblaEvents\Form\Factories\FieldFactory;

$fieldFactory = new FieldFactory();

return array(
    /**
     * Email
     *
     * @since 1.0.0
     */
    'qibla_events_lostpassword_form-username_email:text' => $fieldFactory->base(array(
        'type'                => 'text',
        'name'                => 'qibla_events_lostpassword_form-username_email',
        'label'               => esc_html__('Username or Email', 'qibla-events'),
        'invalid_description' => esc_html__('Username or Email not matched.', 'qibla-events'),
        'attrs'               => array(
            'required' => 'required',
        ),
    )),

    /**
     * Submit Login
     *
     * @since 1.0.0
     */
    'qibla_events_lostpassword_form-submit:submit'       => $fieldFactory->base(array(
        'type'           => 'submit',
        'name'           => 'qibla_events_lostpassword_form-submit',
        'label_position' => 'after',
        'attrs'          => array(
            'value' => esc_html__('Reset Password', 'qibla-events'),
        ),
    )),
);
