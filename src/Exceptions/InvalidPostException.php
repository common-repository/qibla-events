<?php

namespace QiblaEvents\Exceptions;

/**
 * InvalidPostException
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Exceptions
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
 * Class InvalidPostException
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Exceptions
 */
class InvalidPostException extends PostException
{
    /**
     * InvalidPostException constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            sprintf(
            /* Translators: 1 is the name of the function that throw the exception */
                esc_html__('%s cannot retrieve post.', 'qibla-events'), $this->getCaller()
            )
        );
    }
}
