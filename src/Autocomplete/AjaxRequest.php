<?php
namespace QiblaEvents\Autocomplete;

use QiblaEvents\Functions as F;
use QiblaEvents\Request\AbstractRequestAjax;
use QiblaEvents\Utils\Json\Decoder;

/**
 * Ajax Request
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Autocomplete
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    GNU General Public License, version 2
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
 * Class AjaxRequest
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Autocomplete
 */
final class AjaxRequest extends AbstractRequestAjax
{
    /**
     * @inheritdoc
     */
    public function isValidRequest()
    {
        // @codingStandardsIgnoreLine
        $action  = F\filterInput($_POST, 'dlajax_action', FILTER_SANITIZE_STRING);
        $isValid = 'autocomplete' === $action;

        return $isValid;
    }

    /**
     * @inheritDoc
     */
    public function handleRequest()
    {
        if (! $this->isValidRequest()) {
            return;
        }

        // Set the header for ajax request.
        F\setHeaders();

        // Retrieve the data.
        // @codingStandardsIgnoreStart
        $request = intval(F\filterInput($_POST, 'autocomplete', FILTER_SANITIZE_NUMBER_INT));
        $action  = sanitize_text_field(F\filterInput($_POST, 'action', FILTER_SANITIZE_STRING));
        $data    = sanitize_text_field(F\filterInput($_POST, 'data', FILTER_SANITIZE_STRING));
        // @codingStandardsIgnoreEnd

        if (! $data) {
            return;
        }

        // Decode the Json.
        $decoder = new Decoder($data);
        $data    = $decoder->decode();

        // Dispatch to the Controller.
        if (1 === $request && $action && ! empty($data)) {
            $controller = new Controller(new CacheTransient());
            $controller->process($action, $data);
        }

        // Done.
        die('0');
    }

    /**
     * Helper
     *
     * @since 1.0.0
     */
    public static function handleRequestFilter()
    {
        $instance = new self;
        $instance->handleRequest();
    }
}
