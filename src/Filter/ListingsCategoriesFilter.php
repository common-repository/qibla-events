<?php
/**
 * Filters Categories
 *
 * @since      1.0.0
 * @package    QiblaEvents\Front\Filter
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione
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

use QiblaEvents\Query\TaxQueryArguments;

/**
 * Class ListingCategories
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingsCategoriesFilter implements FilterInterface, Positionable
{
    /**
     * Name
     *
     * @since 1.0.0
     *
     * @var string The filter name. It's the same of the name attribute for the field associate to this filter.
     */
    private $name;

    /**
     * Position
     *
     * @since 1.0.0
     *
     * @var string The position where the filter should appear
     */
    private static $position = 'normal';

    /**
     * Filter
     *
     * @since 1.0.0
     *
     * @var FilterFieldInterface The field to use with this filter
     */
    private $field;

    /**
     * ListingsCategoriesFilter Construct
     *
     * @since  1.0.0
     *
     * @param FilterFieldInterface $field The field to use with this filter.
     */
    public function __construct(FilterFieldInterface $field)
    {
        $this->field = $field;
        $this->name  = $field->field()->getArg('name');
    }

    /**
     * @inheritdoc
     */
    public function queryFilter(\WP_Query &$wpQuery, $args)
    {
        // Do nothing if the arguments array contains the 'all' value.
        // Or the current query doesn't include this tax.
        if (empty($args) || in_array('all', $args, true)) {
            return;
        }

        $taxQuery = new TaxQueryArguments(array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'event_categories',
                'field'    => 'slug',
                'terms'    => $args,
            ),
        ));
        $taxQuery->buildQueryArgs($wpQuery);
    }

    /**
     * @inheritdoc
     */
    public function position()
    {
        return self::$position;
    }

    /**
     * @inheritdoc
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function field()
    {
        return $this->field->field();
    }
}
