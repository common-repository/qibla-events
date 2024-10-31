<?php
use \QiblaEvents\Functions as F;
use \QiblaEvents\Form\Factories\FieldFactory;

/**
 * Footer Meta-box Fields
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

// Get the instance of the Field Factory.
$fieldFactory = new FieldFactory();

/**
 * Filter Related Posts Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the related posts meta-box fields.
 */
return apply_filters('qibla_mb_inc_related_posts_fields', array(
    /**
     * Show Related or not
     *
     * @since 1.0.0
     */
    'qibla_mb_post_show_related:checkbox' => $fieldFactory->base(array(
        'type'        => 'checkbox',
        'style'       => 'toggler',
        'name'        => 'qibla_mb_post_show_related',
        'value'       => F\getPostMeta('_qibla_mb_post_show_related', 'on'),
        'label'       => esc_html__('Related Posts', 'qibla-events'),
        'description' => esc_html__('Check if you want to display related posts or not.', 'qibla-events'),
        'display'     => array($this, 'displayField'),
    )),
));
