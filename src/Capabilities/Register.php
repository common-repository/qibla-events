<?php
/**
 * Register
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

namespace QiblaEvents\Capabilities;

use QiblaEvents\RegisterInterface;
use QiblaEvents\ValueObject\QiblaString;

/**
 * Class Register
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Register implements RegisterInterface
{
    /**
     * Capabilities
     *
     * @since 1.0.0
     *
     * @var array The array list with capabilities
     */
    private $capabilities;

    /**
     * Register constructor
     *
     * @since 1.0.0
     *
     * @param array $capabilities The capabilities instance to register
     */
    public function __construct(array $capabilities)
    {
        $capabilities = array_filter($capabilities, function ($item) {
            return $item instanceof CapabilitiesInterface;
        });

        if (! $capabilities) {
            throw new \InvalidArgumentException('Empty Roles list');
        }

        $this->capabilities = $capabilities;
    }

    /**
     * Register
     *
     * @since 1.0.0
     */
    public function register()
    {
        // Get Roles.
        $wp_roles = wp_roles();

        foreach ($this->capabilities as $capability) {
            foreach ($capability->roles() as $role) {
                // Create the role and assign the capabilities if not exists yet.
                if (! $wp_roles->is_role($role)) {
                    $label = new QiblaString($role);
                    $label = $label->fromSlugToLabel()
                                   ->capitalize();
                    $wp_roles->add_role($role, $label->val(), $capability->caps());
                    break;
                } else {
                    // Otherwise assign the capabilities.
                    foreach ($capability->caps() as $cap => $grant) {
                        $wp_roles->add_cap($role, $cap, $grant);
                    }
                }
            }
        }
    }
}
