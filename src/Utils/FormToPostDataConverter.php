<?php
/**
 * FormToPostDataConverter
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package   QiblaEvents\Utils
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

namespace QiblaEvents\Utils;

use QiblaEvents\Form\Interfaces\Forms;

/**
 * Class FormToPostDataConverter
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class FormToPostDataConverter
{
    /**
     * Taxonomy Terms Marker
     *
     * Mark the tax input data.
     *
     * @since  1.0.0
     *
     * @var string The tax input marker
     */
    protected static $taxFieldInputMarker = '-tax-';

    /**
     * Meta Marker
     *
     * Mark the meta input data.
     *
     * @since  1.0.0
     *
     * @var string The meta input marker
     */
    protected static $metaFieldInputMarker = '-meta-';

    /**
     * Form
     *
     * @since  1.0.0
     *
     * @var Forms The form instance
     */
    protected $form;

    /**
     * FormToPostDataConverter constructor
     *
     * @since 1.0.0
     *
     * @param Forms $form The form fields prefix
     */
    public function __construct(Forms $form)
    {
        $this->form = $form;
    }

    /**
     * Create Instance From Form Data
     *
     * This helper method allow you to create the instance of the class by a submitted form data.
     *
     * @throws \Exception In case the $prefix is not a string.
     *
     * @param array $formData The data from which retrieve the parameter for the instance.
     *
     * @return array Converted data
     */
    public function convert(array $formData)
    {
        $newFormatData = array();
        $taxInput      = array();
        $metaInput     = array();

        foreach ($formData as $key => $value) {
            // Remove the prefix.
            $key = str_replace($this->form->getArg('name'), '', $key);
            // Then, try to find if the current data is a tax or meta or a simple data
            // and copy the value into the right container.
            if (false !== strpos($key, self::$taxFieldInputMarker)) {
                $taxInput[substr($key, 5)] = (array)$value;
            } elseif (false !== strpos($key, self::$metaFieldInputMarker)) {
                $metaInput[substr($key, 6)] = $value;
            } else {
                $newFormatData[substr($key, 1)] = $value;
            }
        }
        unset($formData, $key, $value);

        // Set the tax input and meta input arguments.
        $newFormatData['tax_input']  = $taxInput;
        $newFormatData['meta_input'] = $metaInput;

        return $newFormatData;
    }
}
