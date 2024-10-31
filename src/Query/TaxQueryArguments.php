<?php
/**
 * TaxQueryArguments
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

namespace QiblaEvents\Query;

/**
 * Class TaxQueryArguments
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class TaxQueryArguments implements QueryArgumentsInterface
{
    /**
     * Arguments
     *
     * @since 1.0.0
     *
     * @var array The list of the arguments to set
     */
    private $args;

    /**
     * TaxQueryArguments constructor
     *
     * @since 1.0.0
     *
     * @param array $args The list of the arguments to set
     */
    public function __construct(array $args)
    {
        if (empty($args)) {
            throw new \InvalidArgumentException('Arguments cannot be empty in ' . __CLASS__);
        }

        // Set clean arguments.
        $this->args = isset($args['tax_query']) ? $args['tax_query'] : $args;
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function buildQueryArgs(\WP_Query &$wpQuery)
    {
        $taxQuery = $wpQuery->get('tax_query');

        // If not set or empty, just set the arguments and returns.
        if (empty($taxQuery)) {
            $wpQuery->set('tax_query', $this->args);

            return;
        }

        // AND wins over OR.
        if (isset($this->args['relation']) && 'AND' === strtoupper($this->args['relation'])) {
            $taxQuery['relation'] = 'AND';
        }

        // Merge arguments.
        $taxQuery = array_merge($taxQuery, array_filter($this->args, function ($arg) {
            return is_array($arg);
        }));

        // Set query.
        $wpQuery->set('tax_query', $taxQuery);
    }
}
