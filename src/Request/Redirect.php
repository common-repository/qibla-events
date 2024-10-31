<?php
/**
 * Redirect
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
 * Class Redirect
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Request
 */
class Redirect
{
    /**
     * Get Redirect
     *
     * @since 1.0.0
     *
     * @param bool $force To force to custom redirect despite the _wp_http_referer
     *
     * @return string The redirect url
     */
    public static function getRedirect($force = false)
    {
        // @codingStandardsIgnoreLine
        $redirect = F\filterInput($_POST, '_wp_http_referer', FILTER_SANITIZE_STRING);

        if (! $redirect || $force) {
            $redirect = F\isWooCommerceActive() ? wc_get_page_permalink('myaccount') : '';
        }

        return (string)$redirect;
    }

    /**
     * Redirect
     *
     * @since 1.0.0
     *
     * @uses   wp_make_link_relative() To make the link relative to the domain.
     * @uses   wp_safe_redirect() To redirect the request 302.
     *
     * @param string $redirectTo Custom redirect Url.
     * @param bool   $force      To force to redirect despite the _wp_http_referer.
     *
     * @return void
     */
    public static function redirect($redirectTo = '', $force = false)
    {
        if ('' === $redirectTo) {
            $redirectTo = static::getRedirect($force);
        }

        $redirect = wp_make_link_relative($redirectTo);

        if ($redirect) {
            wp_safe_redirect(esc_url_raw($redirect));
            exit;
        }
    }
}
