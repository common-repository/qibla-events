<?php
/**
 * Address
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

namespace QiblaEvents\Taxonomy;

use QiblaEvents\ListingsContext\Types;

/**
 * Class Address
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingsAddress extends AbstractTaxonomy
{
    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            'listings_address',
            'events',
            esc_html__('Address', 'qibla-events'),
            esc_html__('Addresses', 'qibla-events'),
            array(
                // Keep it as hierarchical, non hierarchical taxonomies treat the terms by splitting them via ',',
                // so in that case the values will not be stored correctly in db. See tags for example.
                'hierarchical'       => true,
                // Whether to generate a default UI for managing this taxonomy.
                'show_ui'            => false,
                'query_var'          => false,
                'publicly_queryable' => false,
            )
        );
    }
}
