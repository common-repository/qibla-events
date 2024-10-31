<?php
use \QiblaEvents\Form\Factories\FieldFactory;
use \QiblaEvents\Review\RatingCommentMeta;
use \QiblaEvents\Review\Rating;

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
 * Filter Comments Additional Fields.
 *
 * @since 1.0.0
 *
 * @param array $array The list of the additional comment fields.
 */
return apply_filters('qibla_mb_inc_comment_additional_fields', array(
    'qibla_mb_comment_rating:select' => $fieldFactory->base(array(
        'type'         => 'select',
        'name'         => 'qibla_mb_comment_rating',
        'value'        => get_comment_meta(
            $this->comment->comment_ID,
            '_qibla_mb_comment_rating',
            true
        ) ?: RatingCommentMeta::getDefaultRatingValue(),
        'label'        => esc_html__('Rating', 'qibla-events'),
        'options'      => Rating::getRatingList(),
        'exclude_none' => true,
        'description'  => esc_html__('Set the number of the rating.', 'qibla-events'),
        'display'      => array($this, 'displayField'),
        'attrs'        => array(
            'required' => 'required',
        ),
    )),
    'qibla_mb_comment_title:text'    => $fieldFactory->base(array(
        'type'         => 'text',
        'name'         => 'qibla_mb_comment_title',
        'label'        => esc_html__('Review Title', 'qibla-events'),
        'exclude_none' => true,
        'description'  => esc_html__('Type the title for the review.', 'qibla-events'),
        'display'      => array($this, 'displayField'),
        'attrs'        => array(
            'class'    => 'widefat',
            'value'    => get_comment_meta($this->comment->comment_ID, '_qibla_mb_comment_title'),
            'required' => 'required',
        ),
    )),
));
