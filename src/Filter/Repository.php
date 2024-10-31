<?php
/**
 * Repository
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

namespace QiblaEvents\Filter;

use QiblaEvents\Functions as F;
use QiblaEvents\Collection;
use QiblaEvents\ListingsContext\Context;
use QiblaEvents\ListingsContext\Types;

/**
 * Class Repository
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Filter
 */
class Repository
{
    /**
     * Collection
     *
     * @since 1.0.0
     *
     * @var Collection The collection to manage
     */
    private $collection;

    /**
     * Context
     *
     * @since 1.0.0
     *
     * @var Context The context in which the repository works
     */
    private $context;

    /**
     * Filter Factory
     *
     * @since 1.0.0
     *
     * @var FilterFactory The filter factory
     */
    private $factory;

    /**
     * Filter Listings Type RelationShip
     *
     * @since 1.0.0
     *
     * @var FilterTypeRelationship The instance containing the filter to type relationship
     */
    private $relationship;

    /**
     * Repository constructor
     *
     * @since 1.0.0
     *
     * @param Collection             $collection   The context in which the repository works.
     * @param Context                $context      The context in which the repository works.
     * @param FilterFactory          $factory      The filter factory.
     * @param FilterTypeRelationship $relationship The instance containing the filter to type relationship.
     */
    public function __construct(
        Collection $collection,
        Context $context,
        FilterFactory $factory,
        FilterTypeRelationship $relationship
    ) {
        $this->collection   = $collection;
        $this->context      = $context;
        $this->factory      = $factory;
        $this->relationship = $relationship;
    }

    /**
     * Store
     * Store the filter instances within the collection.
     *
     * @since 1.0.0
     *
     * @uses  factory::create() to create the instance of the filter.
     *
     * @return void
     */
    public function store()
    {
        $context = $this->context->context();

        if (! $context) {
            return;
        }

        // Retrieve the list of the appropriated filters based on context.
        $list = $this->relationship->filters($context);
        // Then store the filters instances.
        foreach ((array)$list as $name) {
            $instance = $this->factory->create($name);

            if ($instance instanceof FilterInterface) {
                $this->collection[$name] = $instance;
            }
        }
    }

    /**
     * Filters
     *
     * @since 1.0.0
     *
     * @return Collection The filters collection
     */
    public function filters()
    {
        return $this->collection;
    }

    /**
     * Retrieve Filters Helper
     *
     * @since 1.0.0
     *
     * @return Collection The filters collection
     */
    public static function retrieveFilters()
    {
        return self::instance()->filters();
    }

    /**
     * Build the Repository Instance
     *
     * @since 1.0.0
     *
     * @return Repository The repository instance
     */
    private static function instance()
    {
        static $instance;

        if (null === $instance) {
            $instance = new self(
                new Collection(),
                new Context(F\getWpQuery(), new Types()),
                new FilterFactory(new ListingsFiltersFields()),
                new FilterTypeRelationship()
            );

            $instance->store();
        }

        return $instance;
    }
}
