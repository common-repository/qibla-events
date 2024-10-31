<?php
namespace QiblaEvents\Taxonomy;

/**
 * Taxonomy Locations
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
 * Class Locations
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Locations extends AbstractTaxonomy
{
    /**
     * @inheritdoc
     */
    protected static $defaultRewriteRule = array(
        'slug' => 'locations',
    );

    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        parent::__construct(
            'locations',
            'events',
            esc_html__('Location', 'qibla-events'),
            esc_html__('Locations', 'qibla-events'),
            array(
                'hierarchical' => true,
                'capabilities' => array(
                    'manage_terms' => 'manage_listings',
                    'edit_terms'   => 'manage_listings',
                    'assign_terms' => 'assign_terms',
                ),
            )
        );
    }
}
