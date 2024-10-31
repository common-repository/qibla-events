<?php
/**
 * FormEmailAuthor
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2018 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaEvents\Front\ContactForm;

use QiblaEvents\Form\Forms\BaseForm;
use QiblaEvents\Form\Types\Hidden;
use QiblaEvents\Plugin;

/**
 * Class FormEmailAuthor
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ContactForm extends BaseForm
{
    /**
     * The Form Name
     *
     * @since  1.0.0
     *
     * @var string The name of the form.
     */
    const FORM_NAME = 'qibla_contact_form';

    /**
     * ContactForm constructor
     *
     * @since 1.0.0
     *
     * @param array $extraFields Extra fields to inject into the form.
     */
    public function __construct(array $extraFields = array())
    {
        parent::__construct(array(
            'action' => '#',
            'method' => 'post',
            'name'   => static::FORM_NAME,
            'attrs'  => array(
                'class' => 'dlform dlform--contact-form',
                'id'    => 'qibla_contact_form',
            ),
        ));

        // Add Fields.
        parent::addFields(array_merge(
            include Plugin::getPluginDirPath('/inc/contactFormFields.php'),
            $extraFields
        ));

        // Add Hidden fields.
        parent::addHidden(new Hidden(array(
            'name'  => 'dlaction',
            'attrs' => array(
                'value' => 'contact_form_request',
            ),
        )));
    }
}
