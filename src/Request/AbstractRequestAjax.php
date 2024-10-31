<?php
/**
 * AjaxRequest
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

use QiblaEvents\Functions as F;

/**
 * Class AjaxRequest
 *
 * @todo    Consider this a Trait when the 5.3 support will drop.
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractRequestAjax
{
    /**
     * Action Key
     *
     * @since  1.0.0
     *
     * @var string The action key value
     */
    protected static $actionKey = 'dlajax_action';

    /**
     * Parse the POST request
     *
     * Via Ajax the form is submitted serialized see the assets/js/form.js::FormHandler.
     * This method do a parse of the str within the enc_data index and rebuild it like a traditional array like
     * data.
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function parsePOSTRequest()
    {
        $placeholders = array(
            '{{quote}}'       => array(
                '&apos;',
                '&#39;',
                '\&#39;',
                '&#x00027;',
            ),
            '{{doublequote}}' => array(
                '&quot;',
                '&#x00022;',
                '&#34;',
            ),
        );

        // @codingStandardsIgnoreStart

        // Since the parsePOSTRequest is performed before the isValidRequest check,
        // and because of the ajax requests are hooked to wp_loaded, another previous hook have parsed the $_POST data.
        // This will be useless when the wp_loaded will be deprecated as hook for ajax requests.
        if (isset($_POST['enc_data'])) {
            // Get the Request extra data and the action.
            $requestAction = $_POST[self::$actionKey];
            $extraData     = isset($_POST['extra_data']) ? $_POST['extra_data'] : '';

            // Get the data from the form.
            // Remember to un-slash or a string will potentially incorrect.
            $data = F\filterInput($_POST, 'enc_data', FILTER_SANITIZE_STRING);

            // Convert problematic characters to placeholder before parse string.
            foreach ($placeholders as $placeholder => $characters) {
                $data = str_replace($characters, $placeholder, $data);
            }

            // Then, decode the data or some character will not be interpreted correctly,
            // and additional elements will be created.
            // Parse it.
            parse_str($data, $_POST);

            foreach ($_POST as &$item) {
                // Reassign the correct characters after data has been parsed.
                foreach ($placeholders as $placeholder => $characters) {
                    $item = str_replace($placeholder, $characters[0], wp_unslash($item));
                }
            }

            unset($_POST['enc_data']);
            unset($_POST['extra_data']);

            // After the POST has been parsed, let's restore them.
            $_POST[self::$actionKey] = $requestAction;

            // Don't add extra_data if not necessary.
            if ($extraData) {
                $_POST['extra_data'] = $extraData;
            }
        }
        // @codingStandardsIgnoreEnd
    }
}
