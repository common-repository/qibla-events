<?php
/**
 * RequestFormContactFormController
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

use QiblaEvents\Request\AbstractRequestFormController;
use QiblaEvents\Request\Response;

/**
 * Class RequestFormContactFormController
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class RequestFormContactFormController extends AbstractRequestFormController
{
    /**
     * Form key
     *
     * @since  1.0.0
     *
     * @var string The form prefix for inputs
     */
    protected static $formKey = 'qibla_contact_form';

    /**
     * @inheritDoc
     */
    public function handle()
    {
        $name    = esc_html(sanitize_text_field($this->data[static::$formKey . '-name']));
        $email   = sanitize_email($this->data[static::$formKey . '-email']);
        $message = esc_html(wp_strip_all_tags($this->data[static::$formKey . '-content']));
        $mailTo  = sanitize_email($this->data[static::$formKey . '-mailto']);

        // At this point if a wrong email has been provided, just die.
        // Don't give info about anything.
        if (! $email) {
            die;
        }

        /**
         * Contact Form From Name
         *
         * @since 1.0.0
         *
         * @param string $name The name of the user sent the email.
         */
        $name = apply_filters('qibla_listings_contact_form_from_name', $name);

        // Try to send the email.
        $response = wp_mail($mailTo, $name, $message, array(
            "From: {$name} <{$email}>",
        ));

        return $response ? new Response(
            200,
            esc_html__('Thank you the email has been send successfully.', 'qibla-events')
        ) : new Response(
            500,
            esc_html__('Ops! An error occurred. Try in a few minutes.', 'qibla-events')
        );
    }
}
