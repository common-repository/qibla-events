<?php
/**
 * MockFieldsByAjaxRequest
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

namespace QiblaEvents\Form;

use QiblaEvents\Form\Factories\FieldFactory;
use QiblaEvents\Functions as F;

/**
 * Class MockFieldsByAjaxRequest
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class MockFieldsByAjaxRequest
{
    /**
     * Build Extra hidden fields for form
     *
     * @since  1.0.0
     *
     * @param string $fieldPrefix The prefix form to use as prefix for the field name.
     *
     * @return array
     */
    public static function mockFields($fieldPrefix)
    {
        // @codingStandardsIgnoreStart
        $fields = F\filterInput($_POST, 'extra_data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
        $fields = F\filterInput($fields, 'fields', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
        // @codingStandardsIgnoreEnd
        $list         = array();
        $fieldFactory = new FieldFactory();

        // Save computation.
        if (! $fields) {
            return $list;
        }

        foreach ($fields as $field) {
            // Get the sanitize function.
            $sanitizeFunc = isset($field['sanitize']) ? $field['sanitize'] : 'sanitize_text_field';
            // The filterID is the int value same of a filter constant.
            // see http://php.net/manual/en/filter.constants.php.
            $value = filter_var($field['value'], intval($field['filter_id']));
            // Filter the name of the field.
            $name = sanitize_key($fieldPrefix . '-' . filter_var($field['name_suffix']));

            $list[$name . ':hidden'] = $fieldFactory->base(array(
                'type' => 'hidden',
                'name' => $name,
            ));

            // Put the value within the $_POST, so we can validate it later.
            $_POST[$name] = call_user_func($sanitizeFunc, $value);
        }
        unset($_POST['extra_data']['fields']);

        return $list;
    }
}
