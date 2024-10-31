<?php
/**
 * Endpoint Register
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Coolway\EndPoint
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

namespace QiblaEvents\EndPoint;

use QiblaEvents\RegisterInterface;

/**
 * Class Register
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Register implements RegisterInterface
{
    /**
     * End Points
     *
     * @since 1.0.0
     *
     * @var array The end points to register
     */
    protected $endPoints;

    /**
     * Register constructor
     *
     * @since 1.0.0
     *
     * @param array $endPoints The end points to register
     */
    public function __construct(array $endPoints)
    {
        $this->endPoints = $endPoints;
    }

    /**
     * Set Endpoint
     *
     * @since  1.0.0
     *
     * @param string $endPointSlug The endpoint instance.
     * @param int    $mask         The endpoint mask.
     *
     * @return void
     */
    protected function setEndPoint($endPointSlug, $mask)
    {
        // Add the rewrite endpoint.
        add_rewrite_endpoint($endPointSlug, $mask);
    }

    /**
     * Set the title for the enpoint page
     *
     * @since  1.0.0
     *
     * @param AbstractEndPoint $endPoint The endpoint instance
     *
     * @return void
     */
    protected function setTitleForEndPointPage($endPoint)
    {
        add_filter('the_title', function ($title) use ($endPoint) {
            global $wp;

            // Since we cannot remove the filter form a callback in php 5.3, use this static variable to
            // let title applied once.
            static $done = false;
            if ($done) {
                return $title;
            }

            $isEndpointNeeded = isset($wp->query_vars[$endPoint->getEndPoint()]);

            if (! is_admin() &&
                is_main_query() &&
                in_the_loop() &&
                $isEndpointNeeded
            ) {
                $title = $endPoint->getEndPointLabel();
                // Done.
                $done = true;
            }

            return $title;
        }, 20);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
     */
    public function register()
    {
        foreach ($this->endPoints as $endPoint) {
            // Sanitize the endpoint slug.
            $endPointSlug = sanitize_key($endPoint->getEndPoint());
            if ($endPoint->register()) {
                // Add the endpoint.
                $this->setEndPoint($endPointSlug, $endPoint->getEpMask());
                // Assign the title for the current page.
                $this->setTitleForEndPointPage($endPoint);
            }
        }
    }
}
