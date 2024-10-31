<?php
/**
 * Autocomplete Controller
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Autocomplete
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    GNU General Public License, version 2
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

namespace QiblaEvents\Autocomplete;

use QiblaEvents\Functions as F;
use QiblaEvents\Utils\Json\Encoder;

/**
 * Class Controller
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Autocomplete
 */
class Controller
{
    /**
     * Cacher
     *
     * @since  1.0.0
     *
     * @var CacheInterface The instance that cache the data
     */
    protected $cacher;

    /**
     * Controller constructor
     *
     * @since  1.0.0
     *
     * @param CacheInterface $cacher
     */
    public function __construct(CacheInterface $cacher)
    {
        $this->cacher = $cacher;
    }

    /**
     * Process Request
     *
     * @since  1.0.0
     *
     * @uses   wp_send_json_success() For when the data has been retrieved correctly and is a valid json.
     * @uses   wp_send_json_error() For when the data cannot be retrieved for some reason.
     *
     * @param string $action The Action to process.
     *
     * @return void
     */
    public function process($action, $data)
    {
        $json = '';

        if (! isset($data->type)) {
            return;
        }

        // Check for the action and perform the appropriated tasks.
        switch ($action) :
            case 'get':
                // Switch lang.
                $lang = null;
                if (isset($data->lang) && F\isWpMlActive()) {
                    $lang = $data->lang;
                    do_action('wpml_switch_language', $lang);
                }

                // Set transient name.
                $transientName = isset($lang) ? esc_attr($data->type . '-' . $lang) : $data->type;
                $transient     = $this->cacher->get($transientName);

                // If transient is available, just use it.
                if ($transient) {
                    $json = $transient;
                    break;
                }

                $taxonomies       = property_exists($data, 'containers') ? $data->containers : array();
                $initialContainer = property_exists($data, 'initialContainer') ? $data->initialContainer : '';

                // Generate the json.
                $generator = new Generator(
                    new \WP_Query(array(
                        'post_type'      => $data->type,
                        'posts_per_page' => -1,
                        'no_found_rows'  => true,
                    )),
                    (array)$taxonomies,
                    $initialContainer
                );

                // Encode it.
                $json = new Encoder(
                    $generator
                        ->prepare()
                        ->includeTerms()
                        ->generate()
                        ->data()
                );

                // Set the $json value so we can pass it to the wp functions.
                $json = $json->prepare()
                             ->json();

                // Cache the JSON.
                $this->cacher->set($json, $transientName);
                break;
            default:
                wp_die('Cheatin\' Uh?');
                break;
        endswitch;

        // Check for the json validity.
        if (F\isJSON($json)) {
            wp_send_json_success($json, 200);
        } else {
            wp_send_json_error($json, 520);
        }
    }
}
