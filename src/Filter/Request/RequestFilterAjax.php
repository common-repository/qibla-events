<?php
/**
 * RequestFilter
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

namespace QiblaEvents\Filter\Request;

use QiblaEvents\Filter\JsonBuilder;
use QiblaEvents\Form\Validate;
use QiblaEvents\Functions as F;
use QiblaEvents\Request\AbstractRequestAjax;
use QiblaEvents\Request\Nonce;

/**
 * Class RequestFilter
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestFilterAjax extends AbstractRequestAjax
{
    /**
     * Action Name
     *
     * @since 1.0.0
     *
     * @var string The action name to verify the request
     */
    const ACTION_NAME = 'listings_filter';

    /**
     * @inheritDoc
     */
    public function isValidRequest()
    {
        // @codingStandardsIgnoreLine
        $action = F\filterInput($_POST, 'dlajax_action', FILTER_SANITIZE_STRING);
        $valid  = RequestFilterAjax::ACTION_NAME === $action;
        // Nonce.
        $nonce = new Nonce('qibla_form_filter');

        return $valid && $nonce->verify();
    }

    /**
     * @inheritDoc
     */
    public function handleRequest()
    {
        if (! $this->isValidRequest()) {
            return;
        }

        F\setHeaders();

        $director = new DirectorRequestFilter(new RequestFilterController(), new Validate(), new JsonBuilder());
        $director->director();
    }

    /**
     * Handle Filter Helper
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function handleFilter()
    {
        $instance = new self;
        $instance->handleRequest();
    }
}
