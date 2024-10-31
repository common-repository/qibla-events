<?php
/**
 * Abstract End Point
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

/**
 * Class EndPoints
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractEndPoint
{
    /**
     * Endpoint Data Def
     *
     * [
     *      'slug'    => 'endpoint-slug',
     *      'label'   => esc_html__('Endpoint Label', 'textdomain'),
     *      'context' => 'endpoint_context',
     * ]
     *
     * @since 1.0.0
     *
     * @var array The end point
     */
    protected $endPointData;

    /**
     * Ep Mask
     *
     * @since 1.0.0
     *
     * @var int The ep mask for endpoints
     */
    protected $epMask;

    /**
     * Callback
     *
     * @since  1.0.0
     *
     * @param string $endPointSlug The endpoint slug, also know as query_var
     *
     * @return void Callback function for the endpoint
     */
    abstract public function callback($endPointSlug);

    /**
     * Register
     *
     * The tasks to perform to allow the endpoint to register itself.
     *
     * @since  1.0.0
     *
     * @return bool True if the endpoint can be registered, false otherwise.
     */
    abstract public function register();

    /**
     * EndPoints constructor
     *
     * @since 1.0.0
     *
     * @param array $endPoint The list of the endpoints.
     * @param int   $epMask   The value for the EP mask.
     */
    public function __construct(array $endPoint, $epMask = EP_PAGES)
    {
        $this->epMask       = $epMask;
        $this->endPointData = wp_parse_args($endPoint, array(
            'slug'    => '',
            'label'   => '',
            'context' => '',
        ));
    }

    /**
     * Get the end Point
     *
     * @since  1.0.0
     *
     * @return string The slug of the endpoint
     */
    public function getEndPoint()
    {
        return $this->endPointData['slug'];
    }

    /**
     * Get end Point Label
     *
     * @since  1.0.0
     *
     * @return string The end point label
     */
    public function getEndPointLabel()
    {
        // @codingStandardsIgnoreLine
        return esc_html_x($this->endPointData['label'], 'endpoint', 'qibla-events');
    }

    /**
     * Get Context
     *
     * @since  1.0.0
     *
     * @return string The context for the endpoint
     */
    public function getContext()
    {
        return $this->endPointData['context'];
    }

    /**
     * Get Ep Mask
     *
     * @since  1.0.0
     *
     * @return int The number associated to this endpoint.
     */
    public function getEpMask()
    {
        return $this->epMask;
    }
}
