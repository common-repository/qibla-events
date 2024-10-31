<?php
/**
 * DirectorRequestSearch
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

namespace QiblaEvents\Search\Request;

use QiblaEvents\Functions as F;
use QiblaEvents\Request\AbstractDirectorRequest;
use QiblaEvents\Request\RequestControllerInterface;

/**
 * Class DirectorRequestSearch
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class DirectorRequestSearch extends AbstractDirectorRequest
{
    /**
     * The context of the search
     *
     * @since 1.0.0
     *
     * @var \stdClass|string The context of the search.
     */
    private $context;

    /**
     * AbstractDirectorRequest constructor
     *
     * @since 1.0.0
     *
     * @param RequestControllerInterface $controller The instance of the controller to direct.
     * @param string|\stdClass           $context    The context of the request.
     */
    public function __construct(RequestControllerInterface $controller, $context)
    {
        parent::__construct($controller);

        $this->context = $context;
    }

    /**
     * @inheritDoc
     */
    public function director()
    {
        // @codingStandardsIgnoreStart
        $taxonomy  = F\filterInput($_POST, 'qibla_taxonomy_filter_taxonomy', FILTER_SANITIZE_STRING) ?: '';
        $filterKey = "qibla_{$taxonomy}_filter";
        $term      = (string)($taxonomy ?
            F\filterInput($_POST, $filterKey, FILTER_SANITIZE_STRING) :
            ''
        );
        // @codingStandardsIgnoreEnd

        $this->injectDataIntoController(array(
            'context'    => $this->context,
            'term'       => $term,
            'filter_key' => $filterKey,
        ));
        $this->dispatchToController();
    }
}
