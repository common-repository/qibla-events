<?php
namespace QiblaEvents\Form\Abstracts;

use QiblaEvents\Form\Interfaces\Validators as IValidators;

/**
 * Form Validation Type
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Abstracts
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

/**
 * Abstract Class Validate
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class Validator implements IValidators
{
    /**
     * Validated Fields Values
     *
     * [
     *      'valid' => array
     *      'invalid' => array
     *      'attachments' => array
     * ]
     *
     * @since  1.0.0
     *
     * @var array A list of validated fields values.
     */
    protected $validated;

    /**
     * Method
     *
     * @since  1.0.0
     *
     * @var string The form method
     */
    protected $method;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param string $method The method of the form to retrieve the data submitted.
     */
    public function __construct($method = 'post')
    {
        $this->method    = $method;
        $this->validated = array(
            'valid'       => array(),
            'invalid'     => array(),
            'attachments' => array(),
        );
    }
}
