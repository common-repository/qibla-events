<?php
/**
 * VerifyNonce
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
 * Class Nonce
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Nonce
{
    /**
     * Nonce Action
     *
     * @since 1.0.0
     *
     * @var string The action name for the nonce
     */
    private $action;

    /**
     * Nonce Name
     *
     * @since 1.0.0
     *
     * @var string The nonce name used to retrieve the nonce value from the request.
     */
    private $name;

    /**
     * Provider
     *
     * The provider of the data. Can be POST or GET.
     * Don't use REQUEST for security reason.
     *
     * @var string The data provider as string.
     */
    private $provider;

    /**
     * Die
     *
     * @var bool If wp_die must be called or not
     */
    private $die;

    /**
     * Nonce constructor
     *
     * @since 1.0.0
     *
     * @param string $action   The action name for the nonce.
     * @param string $provider The data provider as string.
     * @param bool   $die      If wp_die must be called or not.
     */
    public function __construct($action, $provider = 'POST', $die = false)
    {
        $this->action   = $action;
        $this->name     = $this->action . '_nonce';
        $this->provider = $provider;
        $this->die      = $die;
    }

    /**
     * Nonce Action
     *
     * @since 1.0.0
     *
     * @return string The nonce action
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * Nonce Name
     *
     * @since 1.0.0
     *
     * @return string The nonce name
     */
    public function name()
    {
        return $this->action . '_nonce';
    }

    /**
     * Nonce Verification
     *
     * @since  1.0.0
     *
     * @return mixed Depending in which context we are.
     */
    public function verify()
    {
        // Initialize the control variable.
        $verified = false;
        // Set the nonce name.
        // Get the Data Provider. POST, GET ...
        $dataProvider = F\getInputDataProvider($this->provider);
        // Retrieve the nonce value.
        $nonceValue = F\filterInput($dataProvider, $this->name, FILTER_SANITIZE_STRING);

        // May not requested a form validation. Don't give info about the request it self.
        if (! $nonceValue) {
            return null;
        }

        // Check for nonce.
        if (is_admin()) {
            $verified = check_admin_referer($this->action, $this->name);
        } else {
            if (! wp_verify_nonce($nonceValue, $this->action)) {
                if ($this->die) {
                    wp_die(esc_html__('You are not allowed to submit this data.', 'qibla-events'));
                } else {
                    $verified = false;
                }
            } else {
                $verified = true;
            }
        }

        return $verified;
    }

    /**
     * Nonce
     *
     * @since 1.0.0
     *
     * @uses  wp_create_nonce() To create the nonce value
     *
     * @return string A nonce
     */
    public function nonce()
    {
        return wp_create_nonce($this->action);
    }

    /**
     * Field
     *
     * @since 1.0.0
     *
     * @uses  wp_nonce_field() To create the nonce field
     *
     * @return string The nonce field markup string
     */
    public function field()
    {
        return wp_nonce_field($this->action, $this->name, false, false);
    }
}
