<?php
/**
 * Taxonomy Relation Fields
 *
 * @author    Alfio Piccione <alfio.piccione@gmail.com>
 * @package   dreamlist-framework
 * @copyright Copyright (c) 2018, Alfio Piccione
 * @license   GNU General Public License, version 2
 *
 * Copyright (C) 2018 Alfio Piccione <alfio.piccione@gmail.com>
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

return apply_filters('qibla_tb_taxonomy_relation_field', array(

    'qibla_tb_taxonomy_term_relation:multicheck' => $fieldFactory->termbox(array(
        'type'        => 'multi_check',
        'name'        => 'qibla_tb_taxonomy_term_relation',
        'value'       => F\getTermMeta('_qibla_tb_taxonomy_term_relation', self::$currTerm->term_id, 'all'),
        'label'       => esc_html__('Category Term Relation', 'qibla-events'),
        'description' => esc_html__(
            'Choose the term from the Listings Categories related to the current one.',
            'qibla-events'
        ),
        'exclude_all' => true,
        'display'     => array($this, 'displayField'),
        'options'     => F\getTermsList(array(
            'taxonomy'   => 'event_categories',
            'hide_empty' => false,
        )),
    )),
));
