<?php
namespace QiblaEvents\Admin\Metabox;

use QiblaEvents\Form\Fieldsets\BaseFieldset;
use QiblaEvents\Form\Interfaces\Fields;
use QiblaEvents\ListingsContext\Types;
use QiblaEvents\Plugin;

/**
 * Class Event
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Metabox
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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
 * Class Listings
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Listings extends AbstractMetaboxFieldset
{
    /**
     * Field-sets & Formats
     *
     * @since  1.0.0
     *
     * @var array A list of key value pair
     */
    private $formatsList;

    /**
     * Listings Constructor
     *
     * @inheritdoc
     */
    public function __construct(array $args = array())
    {
        // Get the post to use to retrieve some data within the fields files.
        $post  = get_post() ?: new \WP_Post(new \stdClass());
        $types = new Types();

        parent::__construct(wp_parse_args(array(
            'id'       => 'listing_option',
            'title'    => esc_html__('Listing Option', 'qibla-events'),
            'callback' => array($this, 'callBack'),
            'screen'   => $types->types(),
            'context'  => 'normal',
            'priority' => 'high',
        )), array(
            'dlui-metabox-tabs',
        ));

        parent::setFieldsets(array(
            /**
             * Location Field-set
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/listingsLocationsFields.php'), array(
                'form'            => 'post',
                'id'              => 'listing_map',
                'name'            => 'map',
                'legend'          => esc_html__('Location', 'qibla-events'),
                'icon_class'      => 'la la-map-marker',
                'container_class' => array('dl-metabox-fieldset'),
            )),

            /**
             * Additional Info Field-set
             *
             * @since 1.0.0
             */
            new BaseFieldset(
                include Plugin::getPluginDirPath('/inc/metaboxFields/listingsAdditionalInfoFields.php'),
                array(
                    'form'            => 'post',
                    'id'              => 'listing_additional_info',
                    'name'            => 'additional_info',
                    'legend'          => esc_html__('Additional Info', 'qibla-events'),
                    'container_class' => array('dl-metabox-fieldset'),
                    'icon_class'      => 'la la-info',
                )
            ),

            /**
             * Socials Field-set
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/socialsUrlsFields.php'), array(
                'form'            => 'post',
                'id'              => 'listing_socials',
                'name'            => 'socials',
                'legend'          => esc_html__('Socials', 'qibla-events'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-users',
            )),

            /**
             * Gallery Field-set
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/galleryFields.php'), array(
                'form'            => 'post',
                'id'              => 'listing_gallery',
                'name'            => 'gallery',
                'legend'          => esc_html__('Gallery', 'qibla-events'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-image',
            )),

            /**
             * Related Posts Field
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/relatedPostsFields.php'), array(
                'form'            => 'post',
                'id'              => 'related_posts',
                'name'            => 'related_posts',
                'legend'          => esc_html__('Related Posts', 'qibla-events'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-columns',
            )),

            /**
             * Button Fields
             *
             * @since 1.0.0
             */
            new BaseFieldset(include Plugin::getPluginDirPath('/inc/metaboxFields/buttonFields.php'), array(
                'form'            => 'post',
                'id'              => 'listing_button',
                'name'            => 'listing_button',
                'legend'          => esc_html__('Button', 'qibla-events'),
                'container_class' => array('dl-metabox-fieldset'),
                'icon_class'      => 'la la-external-link-square',
            )),
        ));
    }

    /**
     * Display Fields Callback
     *
     * @since  1.0.0
     *
     * @param Fields $field The current field in the form
     *
     * @return bool True if can be displayed, false otherwise¬
     */
    public function displayField(Fields $field)
    {
        // By default display to true.
        $display = true;
        // Get the field arguments.
        $fieldArgs = $field->getArgs();

        switch ($fieldArgs['name']) {
            case 'qibla_mb_hide_breadcrumb':
                // Single listings post doesn't have breadcrumbs.
                $display = false;
                break;
        }

        return $display;
    }
}
