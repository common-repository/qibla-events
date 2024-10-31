<?php
/**
 * Abstract Director Request
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

/**
 * Class AbstractDirectorRequest
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Request
 */
abstract class AbstractDirectorRequest implements DirectorRequestInterface
{
    /**
     * Controller
     *
     * @since  1.0.0
     *
     * @var RequestControllerInterface The controller interface
     */
    protected $controller;

    /**
     * AbstractDirectorRequest constructor
     *
     * @since 1.0.0
     *
     * @param RequestControllerInterface $controller The instance of the controller to direct
     */
    public function __construct(RequestControllerInterface $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Inject Data into Controller
     *
     * @since  1.0.0
     *
     * @param array $data The data to inject into controller
     *
     * @return void
     */
    public function injectDataIntoController(array $data)
    {
        $this->controller->setData($data);
    }

    /**
     * @inheritdoc
     */
    public function dispatchToController()
    {
        return $this->controller->handle();
    }
}
