<?php
/**
 * Additional Listings Info Meta-box Fields
 *
 * @author  Alfio Piccione <alfio.piccione@gmail.com>
 *
 * @license GPL 2
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

use \QiblaEvents\Functions as F;
use \QiblaEvents\Form\Factories\FieldFactory;

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

return array(
    /**
     * Expiration
     *
     * @since 1.0.0 - Moved from the Framework
     */
    'qibla_mb_listing_expiration'   => $fieldFactory->base(array(
        'type'        => 'number',
        'name'        => 'qibla_mb_listing_expiration',
        'label'       => esc_html__('Listing Visibility Duration'),
        'description' => esc_html__(
            'Set after how many days from the publish, the listing will be disabled. -1 to unlimited.',
            'qibla-events'
        ),
        'attrs'       => array(
            'value' => F\getPostMeta('_qibla_mb_listing_expiration', -1),
            'min'   => -1,
            'max'   => 364,
        ),
        'display'     => array($this, 'displayField'),
    )),
);
