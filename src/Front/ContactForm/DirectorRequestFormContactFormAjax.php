<?php
/**
 * DirectorRequestFormContactFormAjax
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

/**
 * Class DirectorRequestFormContactFormAjax
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Front\ContactForm
 */
class DirectorRequestFormContactFormAjax extends DirectorRequestFormContactForm
{
    /**
     * Get Fields Properties
     *
     * @since  1.0.0
     *
     * @param array $fields    The Field objects from which extract the arguments.
     * @param array $fieldsKey An array of keys referencing the field arguments.
     *
     * @return array The array containing the fields to return from the ajax request.
     */
    protected function getFieldsProperties($fields, $fieldsKey)
    {
        $fieldsProperties = array();

        foreach ($this->getForm()->getFields() as $field) {
            if (in_array($field->getArg('name'), $fieldsKey, true)) {
                $fieldsProperties[$field->getArg('name')] = array(
                    'selector'           => '#' . $field->getType()->getArg('id'),
                    'invalidDescription' => '',
                    'attrs'              => $field->getArg('attrs'),
                    'containerClass'     => $field->getArg('container_class'),
                );
            }
        }

        return $fieldsProperties;
    }

    /**
     * @inheritDoc
     */
    public function director()
    {
        $response = parent::director();

        $responseData = $response->getData();
        if (! $response->isValidStatus() && isset($responseData['validation_data']['invalid'])) {
            $validationInvalidFields = $this->getFieldsProperties(
                $this->form->getFields(),
                array_keys($responseData['validation_data']['invalid'])
            );
            $response->setData($validationInvalidFields, false);
        }

        return $response;
    }

}
