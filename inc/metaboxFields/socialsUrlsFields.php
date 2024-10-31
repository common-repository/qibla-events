<?php
use \QiblaEvents\Functions as F;
use \QiblaEvents\Form\Factories\FieldFactory;

/**
 * Socials Urls Meta-box Fields
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
 * Filter Socials Fields
 *
 * @since 1.0.0
 *
 * @param array $array The list of the socials meta-box fields.
 */
return apply_filters('qibla_mb_inc_social_fields', array(
    /**
     * Facebook
     *
     * @since 1.0.0
     */
    'qibla_mb_social_facebook:text'  => $fieldFactory->base(array(
        'type'        => 'text',
        'name'        => 'qibla_mb_social_facebook',
        'attrs'       => array(
            'class' => array('widefat'),
            'value' => F\getPostMeta('_qibla_mb_social_facebook'),
        ),
        'label'       => esc_html__('Facebook', 'qibla-events'),
        'description' => esc_html__('Your Facebook profile page url', 'qibla-events'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Twitter
     *
     * @since 1.0.0
     */
    'qibla_mb_social_twitter:text'   => $fieldFactory->base(array(
        'type'        => 'text',
        'name'        => 'qibla_mb_social_twitter',
        'attrs'       => array(
            'class' => array('widefat'),
            'value' => F\getPostMeta('_qibla_mb_social_twitter'),
        ),
        'label'       => esc_html__('Twitter', 'qibla-events'),
        'description' => esc_html__('Your Twitter profile url', 'qibla-events'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Instagram
     *
     * @since 1.0.0
     */
    'qibla_mb_social_instagram:text' => $fieldFactory->base(array(
        'type'        => 'text',
        'name'        => 'qibla_mb_social_instagram',
        'attrs'       => array(
            'class' => array('widefat'),
            'value' => F\getPostMeta('_qibla_mb_social_instagram'),
        ),
        'label'       => esc_html__('Instagram', 'qibla-events'),
        'description' => esc_html__('Your Instagram profile url', 'qibla-events'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Linkedin
     *
     * @since 1.0.0
     */
    'qibla_mb_social_linkedin:text'  => $fieldFactory->base(array(
        'type'        => 'text',
        'name'        => 'qibla_mb_social_linkedin',
        'attrs'       => array(
            'class' => array('widefat'),
            'value' => F\getPostMeta('_qibla_mb_social_linkedin'),
        ),
        'label'       => esc_html__('Linkedin', 'qibla-events'),
        'description' => esc_html__('Your Linkedin profile page url', 'qibla-events'),
        'display'     => array($this, 'displayField'),
    )),

    /**
     * Trip Advisor
     *
     * @since 1.0.0
     */
    'qibla_mb_social_tripadvisor:text'  => $fieldFactory->base(array(
        'type'        => 'text',
        'name'        => 'qibla_mb_social_tripadvisor',
        'attrs'       => array(
            'class' => array('widefat'),
            'value' => F\getPostMeta('_qibla_mb_social_tripadvisor'),
        ),
        'label'       => esc_html__('Trip Advisor', 'qibla-events'),
        'description' => esc_html__('Your tripadvisor profile page url', 'qibla-events'),
        'display'     => array($this, 'displayField'),
    )),
));
