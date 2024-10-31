<?php
/**
 * EventsLocation.php
 *
 * @since      1.0.0
 * @package    QiblaEvents\Front\CustomFields
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

namespace QiblaEvents\Front\CustomFields;

use QiblaEvents\Functions as F;
use QiblaEvents\Listings\ListingLocation;
use QiblaEvents\Listings\ListingsPost;
use QiblaEvents\Listings\PlainObject;
use QiblaEvents\TemplateEngine\TemplateInterface;

/**
 * Class EventsLocation
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class EventsLocation extends AbstractMeta implements TemplateInterface
{
    /**
     * Initialize Object
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        // Build the meta-keys array.
        $this->meta = array(
            'map_location' => "_qibla_{$this->mbKey}_map_location",
        );
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        // Initialize data object.
        $data     = new \stdClass();
        $post     = get_post($this->id);
        $listings = new ListingsPost($post);
        $location = new ListingLocation($post);

        // Initialize properties to avoid issue in views and js.
        $data->mapOptions       = array();
        $data->locationInfoList = array();

        // Create and Add the location properties if a valid location is provided.
        if ($location->isValidLocation()) :
            // Parse the zoom option as int or google map will throw an error.
            $data->mapOptions['zoom']   = intval(F\getPluginOption('google_map', 'zoom', true));
            $data->mapOptions['center'] = array(
                'lat' => $location->latitude(),
                'lng' => $location->longitude(),
            );

            // Set the data for the Marker Icon.
            $obj                      = new PlainObject($listings);
            $data->mapOptions['item'] = $obj->object();

            // Json Encode the map Options.
            $data->mapOptions = wp_json_encode($data->mapOptions);

            // Set address.
            $data->locationInfoList['address'] = array(
                'label'           => esc_html__('Address', 'qibla-events'),
                'icon_html_class' => array('la', 'la-map-signs'),
                'data'            => sprintf(
                    '<a class="dllisting-meta__link" href="https://www.google.com/maps/place/%1$s">%2$s</a>',
                    urlencode($location->address()),
                    $location->address()
                ),
            );
        endif;

        // Clean the location info list data.
        foreach ($data->locationInfoList as $key => $item) {
            if (null === $item) {
                unset($data->locationInfoList[$key]);
            }
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        if (is_singular('events')) {
            if ($this->loadTemplate('listing_location', $data, 'views/events/eventsLocation.php')) {
                // Enqueue the Google Map Script.
                if (wp_script_is('dllistings-google-map', 'registered')) {
                    wp_enqueue_script('dllistings-google-map');
                }
            }
        }
    }

    /**
     * Listings Location Filter
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function eventsLocationFilter()
    {
        $instance = new self;

        $instance->init();
        $instance->tmpl($instance->getData());
    }

}