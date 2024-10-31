<?php
/**
 * RequestFormContactFormAjax
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

use QiblaEvents\Form\Validate;
use QiblaEvents\Request\AbstractRequestFormAjax;
use QiblaEvents\Request\ResponseAjax;

/**
 * Class RequestFormContactFormAjax
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestFormContactFormAjax extends AbstractRequestFormAjax
{
    /**
     * @inheritDoc
     */
    public function handleRequest()
    {
        if (! $this->isValidRequest()) {
            return;
        }

        try {
            $director = new DirectorRequestFormContactFormAjax(
                $this->form,
                new Validate(),
                new RequestFormContactFormController()
            );

            $response = $director->director();
            // Rebuild the Response for the Ajax call.
            $response = new ResponseAjax(
                $response->getCode(),
                $response->getMessage(),
                $response->getData()
            );
        } catch (\Exception $e) {
            $response = new ResponseAjax(500, '');
        }

        // End.
        $response->sendAjaxResponse();
    }

    /**
     * Handle the Request Filter
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function handleFilter()
    {
        $instance = new static(new ContactForm(), 'contact_form_request');
        $instance->handleRequest();
    }
}
