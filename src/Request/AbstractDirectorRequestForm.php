<?php
/**
 * Director
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

namespace QiblaEvents\Request;

use QiblaEvents\Form\Interfaces\Forms;
use QiblaEvents\Form\Interfaces\Validators;

/**
 * Interface DirectorForm
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractDirectorRequestForm extends AbstractDirectorRequest
{
    /**
     * Form
     *
     * @since  1.0.0
     *
     * @var Forms The form to process
     */
    protected $form;

    /**
     * Validator
     *
     * @since  1.0.0
     *
     * @var Validators The validator instance
     */
    protected $validator;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param Forms                          $form       The form to process.
     * @param Validators                     $validator  The validator to use to validate the form
     * @param RequestFormControllerInterface $controller The controller to use within this Director.
     */
    public function __construct(Forms &$form, Validators $validator, RequestFormControllerInterface $controller)
    {
        $this->form      = $form;
        $this->validator = $validator;

        parent::__construct($controller);
    }

    /**
     * Get Form
     *
     * @since  1.0.0
     *
     * @return Forms The form instance
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Validate the Form
     *
     * @since  1.0.0
     *
     * @param array $args Additional arguments to pass to the validator.
     *
     * @return array The list of the fields returned by the Validator
     */
    public function validate(array $args = array())
    {
        $response = $this->validator->validate($this->form->getFields(), $args);

        return $response;
    }
}
