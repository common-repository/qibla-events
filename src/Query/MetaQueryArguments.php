<?php
/**
 * Filter Query By Meta Query Arguments
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
 * Class MetaQueryArguments
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
final class MetaQueryArguments implements QueryArgumentsInterface
{
    /**
     * Query Arguments
     *
     * @since  1.0.0
     *
     * @var array The arguments for the query
     */
    private $queryArgs;

    /**
     * MetaQueryArguments constructor
     *
     * @since 1.0.0
     *
     * @param array $args The list of the query arguments.
     */
    public function __construct(array $args)
    {
        $this->queryArgs = $args;
    }

    /**
     * @inheritDoc
     *
     * @return QueryArgumentsInterface The instance of the class for chaining
     */
    public function buildQueryArgs(\WP_Query &$wpQuery)
    {
        // Get current meta query.
        $currMetaQuery = array_filter((array)$wpQuery->get('meta_query'));
        // Get meta query arguments.
        $metaQueryArgs = $this->queryArgs;

        if (isset($currMetaQuery['meta_key'])) :
            // Include meta query if in form of separated parameters.
            $additionalArgs = array();
            foreach (array('meta_key', 'meta_value', 'meta_compare') as $key) {
                if (! empty($currMetaQuery[$key])) {
                    $additionalArgs[str_replace('meta_', '', $key)] = $currMetaQuery[$key];
                    unset($currMetaQuery[$key]);
                }
            }

            // If not set the 'meta_value' try with 'meta_value_num'.
            if (! isset($additionalArgs['value']) && isset($currMetaQuery['meta_value_num'])) {
                $additionalArgs['value'] = $currMetaQuery['meta_value_num'];
                // And set the correct type for data.
                $additionalArgs['type'] = 'NUMERIC';
                unset($currMetaQuery['meta_value_num']);
            }

            if (in_array(count($additionalArgs), array(3, 4), true)) {
                // Push the additional meta query arguments to the main query args list.
                array_push($metaQueryArgs, $additionalArgs);
            }
        endif;

        // Merge arguments.
        $currMetaQuery = array_merge($currMetaQuery, $metaQueryArgs);

        // Be sure we have a relation.
        // Dock says "Do not use with a single inner meta_query array.".
        if (! isset($currMetaQuery['relation']) && isset($currMetaQuery[1])) {
            $currMetaQuery['relation'] = 'AND';
        } elseif (isset($currMetaQuery['relation']) && ! isset($currMetaQuery[1])) {
            unset($currMetaQuery['relation']);
        }

        // Set the arguments to the current query.
        $wpQuery->set('meta_query', $currMetaQuery);

        return $this;
    }
}
