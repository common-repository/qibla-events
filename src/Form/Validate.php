<?php
/**
 * Form Validation Type
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

namespace QiblaEvents\Form;

use QiblaEvents\Functions as F;
use QiblaEvents\Debug;
use QiblaEvents\Form\Abstracts\Validator;

/**
 * Class Validate
 *
 * @todo    Separate class for the Single Responsible Principle.
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Validate extends Validator
{
    /**
     * Validate
     *
     * @since  1.0.0
     *
     * @todo   Allow Multiple files.
     * @todo   Skip Upload isn't complete, right now we use the dropzone but we need a way to validate the file without
     *         upload it. So, probably this mean to split the file handler logic.
     *
     * @param array $fields An array of Fields instances. The fields to validate.
     * @param array $args   Additional arguments to consume within the validation.
     *
     * @return array All of the valid submitted data. Empty array if the form has no fields. Fields added to the
     *               array in case the validation failed.
     */
    public function validate(array $fields, array $args = array())
    {
        // No fields, nothing to do.
        if (empty($fields)) {
            return array();
        }

        // Allow Empty
        // Allow empty means that the input will pass the validation even if the value of that input
        // may be evaluate as false.
        // Some forms may need to update options on database and this means that even empty strings, zero values etc...
        // must be considered valid.
        $allowEmpty = ! empty($args['allow_empty']);

        // Set the Data provider.
        $dataProvider = F\getInputDataProvider($this->method);

        foreach ($fields as &$field) :
            // Get the reflection for the current type.
            $reflectionClassInstance = new \ReflectionClass($field->getType());
            // The input type.
            $inputType = strtolower($reflectionClassInstance->getShortName());
            // The input name.
            $inputName = esc_attr($field->getType()->getArg('name'));
            // Get the filter input type.
            $filter = $field->getType()->getArg('filter') ?: FILTER_DEFAULT;
            // Get the Filter Options.
            $foptions = $field->getType()->getArg('filter_options');
            // Type Attributes.
            $typeAttrs = $field->getType()->getArg('attrs');
            // Is type required?
            $isRequired = isset($typeAttrs['required']) ? (bool)$typeAttrs['required'] : false;

            // Different types may need different treatment.
            switch ($inputType) :
                case 'file':
                    try {
                        // Have files to validate and upload?
                        if (empty($_FILES)) {
                            break;
                        }

                        // Create the Validator instance.
                        $validateFiles = new ValidateFile($_FILES, array(
                            'allowed_mime' => explode(',', $field->getType()->getArg('accept')),
                            'max_size'     => intval($field->getType()->getArg('max_size')),
                        ));
                        // Get the iterator to validate the files.
                        $fieldsIterator = new \ArrayIterator($validateFiles->getList());
                        // Validate.
                        foreach ($fieldsIterator as $key => $list) {
                            foreach ($list as $index => $file) {
                                // Is an array $file, we are using dropzone,
                                // if not, we are using the standard file input.
                                if (! is_array($file)) {
                                    $file = $list;
                                }

                                $validateFiles->validate($file);
                                // Set the input as valid.
                                $this->validated['valid'][$inputName][$index] = $file;
                            }
                        }
                    } catch (\Exception $e) {
                        $debugInstance = new Debug\Exception($e);
                        'dev' === QB_ENV && $debugInstance->display();

                        // Set the input as invalid.
                        $this->validated['invalid'][$inputName] = '';
                        // Set the error description.
                        $field->setArg('invalid_description', $e->getMessage());
                        // Set the class for the field.
                        $field->setArg('container_class', 'is-invalid', true);
                        // Set the type as invalid.
                        $field->getType()->setArg('is_invalid', true);
                    }//end try
                    break;

                case 'submit':
                case 'reset':
                case 'button':
                    break;

                case 'checkbox':
                    // Set the value to the default to the value defined in value attribute instead of get NULL.
                    // The input doesn't exists if not checked but here we are going through the fields.
                    // Remember to add the default value only if not required. In this way if the field is required
                    // we can store the field into the invalid fields array and perform the appriopiated tasks.
                    $value = F\filterInput($dataProvider, $inputName, FILTER_DEFAULT, $foptions) ?:
                        (! $isRequired ? 'off' : '');
                    break;

                default:
                    // Get the field value from the submitted form.
                    $value = F\filterInput($dataProvider, $inputName, $filter, $foptions);

                    // The typography type consist of an array of data, so we need to get the array
                    // and then filter every element.
                    if ('typography' === $inputType) {
                        $value = filter_var_array($value, $foptions);
                    }

                    // Set the value in field.
                    $field->getType()->setArg('attrs', array_merge($typeAttrs, array('value' => $value)));

                    // Don't validate not required fields when there is no value to sanitize.
                    // Only for standard form submission. Allow empty values if explicitly set via additional arguments.
                    if ($isRequired && ! $value && ! $allowEmpty) {
                        break;
                    }

                    // Sanitize the value.
                    $value = call_user_func($field->getType()->getArg('sanitize_cb'), $value);
                    break;
            endswitch;

            // Store the info of the field and input.
            if (! in_array($inputType, array('file'), true) && isset($value)) {
                if ($isRequired && ! $value) {
                    $this->validated['invalid'][$inputName] = $value;
                    // Set the class for the field.
                    $field->setArg('container_class', 'is-invalid', true);
                    // Set the type as invalid.
                    $field->getType()->setArg('is_invalid', true);
                } elseif ($value || $allowEmpty) {
                    $this->validated['valid'][$inputName] = $value;
                }
            }
        endforeach;

        // If there are no invalid fields, let's clean the input fields.
        if (empty($this->validated['invalid'])) {
            /*
             * @todo Temporary removed due to different form behaviors. See below
             *
             * 1 - Standard Form:
             *      The standard form submit data, stop, it will inform regarding errors (fields not filled correctly),
             *      or show a success alert. This is supposed to clean che form when the submit page is the same of
             *      the form. This is why the logic below.
             *
             * 2 - Ajax Form:
             *      Submit data, get json and show alert. This doesn't reload the page, so if there is a cleaning of the form
             *      that happen via javascript.
             *
             * 3 - Fill After submitted
             *      This kind of form is used to submit data from another page, the form within the action page that process
             *      the data, will fill the fields with the data provided. When this happen, we don't want to fill the
             *      'attrs' => 'value' with an empty value, because it's needed to fill the current form.
             *
             * Also, keep in mind that not all inputs use the 'attrs' => 'value', some may use the 'value' attribute directly.
             */
//            array_walk($fields, function ($field) {
//                // Don't strip the value from the input submit and hidden types.
//                if (! in_array($field->getType()->getArg('type'), array('submit', 'hidden'), true)) {
//                    $field->getType()->setArg(
//                        'attrs',
//                        array_merge($field->getType()->getArg('attrs'), array('value' => ''))
//                    );
//                }
//            });
        } else {
            // Append the edited fields if there are invalid types.
            // This because we had set additional data to the field types during validation.
            // @todo May not be needed. Check for the fields reference when validate.
            $this->validated['fields'] = $fields;
        }

        return $this->validated;
    }
}
