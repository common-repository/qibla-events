<?php
/**
 * Maps
 *
 * @since      1.0.0
 * @package    QiblaEvents\Shortcode
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

namespace QiblaEvents\Shortcode;

use QiblaEvents\Debug;
use QiblaEvents\Filter\JsonBuilder;

/**
 * Class Maps
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Maps extends AbstractShortcode
{
    /**
     * The Query
     *
     * @since  1.0.0
     *
     * @var \WP_Query
     */
    private $query;

    /**
     * Build Query Arguments List
     *
     * @since  1.0.0
     *
     * @param array $args The base arguments for the query.
     *
     * @return array The arguments to use for the query
     */
    protected function buildQueryArgsList(array $args)
    {
        // Retrieve the default arguments for the query.
        $queryArgs = array_intersect_key($args, array(
            'post_type'      => '',
            'posts_per_page' => '',
            'orderby'        => '',
            'order'          => '',
        ));

        // Initialize tax query.
        $queryArgs['tax_query'] = array();
        // Add Tax Query arguments if taxonomy is event_categories and d'not empty terms.
        if (! empty($args['event_categories']) || ! empty($args['locations'])) :
            // Set Terms.
            $listingCategories = '' !== $args['event_categories'] ? explode(',', $args['event_categories']) : null;
            $locations         = '' !== $args['locations'] ? explode(',', $args['locations']) : null;

            // Listing categories tax query.
            $taxCategories = array(
                'taxonomy' => 'event_categories',
                'field'    => 'slug',
                'terms'    => $listingCategories,
            );

            // Listing locations tax query.
            $taxLocations = array(
                'taxonomy' => 'locations',
                'field'    => 'slug',
                'terms'    => $locations,
            );

            if ($listingCategories) {
                $queryArgs['tax_query'][] = $taxCategories;
            }

            if ($locations) {
                // Relation.
                $queryArgs['tax_query']['relation'] = 'AND';
                $queryArgs['tax_query'][] = $taxLocations;
            }
        endif;

        // Order by may be a list of comma separated values.
        // In this case make it as an array.
        $args['additional_query_args'] = false !== strpos($args['orderby'], ',') ?
            explode(',', $args['additional_query_args']) :
            $args['additional_query_args'];

        return wp_parse_args($args['additional_query_args'], $queryArgs);

    }

    /**
     * Construct
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->tag      = 'dl_maps';
        $this->defaults = array(
            'post_type'             => 'events',
            'posts_per_page'        => -1,
            'orderby'               => 'date',
            'order'                 => 'DESC',
            'event_categories'    => '',
            'locations'             => '',
            'height'                => '100vh',
            'width'                 => '100%',
            'additional_query_args' => array(
                'post_status' => 'publish',
            ),
        );
    }

    /**
     * Build Data
     *
     * @since  1.0.0
     *
     * @throws \Exception In case the posts cannot be retrieved.
     *
     * @param array  $atts    The short-code attributes.
     * @param string $content The content within the short-code.
     *
     * @return \stdClass The data instance or null otherwise.
     */
    public function buildData(array $atts, $content = '')
    {
        // Build the Query Arguments List.
        // Since we allow to pass additional query args, we need to parse those arguments.
        $queryArgs = $this->buildQueryArgsList($atts);
        // Make the query.
        $query = new \WP_Query($queryArgs);
        // Initialize Data.
        $data = new \stdClass();
        // Set Query.
        $this->query = $query;

        $data->height = '' !== $atts['height'] ? $atts['height'] : '100vh';
        $data->width  = '' !== $atts['width'] ? $atts['width'] : '100%';

        return $data;
    }

    /**
     * Parse Attributes Arguments
     *
     * @since  1.0.0
     *
     * @link   https://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters
     *
     * @param array $atts The short-code's attributes
     *
     * @return array The parsed arguments
     */
    public function parseAttrsArgs($atts = array())
    {
        $atts = parent::parseAttrsArgs($atts);

        return $atts;
    }

    /**
     * Create Json
     *
     * @since  1.0.0
     */
    public function json()
    {
        $jsonBuilder = new JsonBuilder();
        $jsonBuilder->prepare($this->query);

        printf(
            "<script type=\"text/javascript\">//<![CDATA[\n var jsonListings = %s; \n//]]></script>",
            wp_json_encode($jsonBuilder->json())
        );
    }

    /**
     * Callback
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code's attributes.
     * @param string $content The content within the short-code.
     *
     * @return string The short-code markup
     */
    public function callback($atts = array(), $content = '')
    {
        $atts = $this->parseAttrsArgs($atts);

        try {
            // Build the data object needed by this short-code.
            $data = $this->buildData($atts);

            // Enqueue scripts.
            if (wp_script_is('dlmap-listings', 'registered') && wp_script_is('dlmap-toggler', 'registered')) {
                wp_enqueue_script('dlmap-listings');
            }

            // Add json in footer.
            add_action('wp_footer', array($this, 'json'));

            return $this->loadTemplate('dl_sc_maps', $data, '/views/shortcodes/maps.php');
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            return '';
        }
    }
}
