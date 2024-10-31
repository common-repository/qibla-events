<?php
/**
 * Class Store
 *
 * Store Meta-boxes Fields values.
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
 * along with this program; if not, write to the Free Software.
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace QiblaEvents\Admin\Metabox;

use QiblaEvents\Debug;
use QiblaEvents\Functions as F;
use QiblaEvents\Form\Validate;
use QiblaEvents\Listings\ListingLocation;
use QiblaEvents\Listings\ListingLocationStore;
use QiblaEvents\Admin\AbstractMetaboxStore;
use QiblaEvents\User\User;

/**
 * Class Store
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Store extends AbstractMetaboxStore
{
    /**
     * Store Post Meta
     *
     * @since  1.0.0
     *
     * @throws \Exception If method is called directly.
     *
     * @param int      $postID Post ID.
     * @param \WP_Post $post   Post object.
     * @param bool     $update Whether this is an existing post being updated or not.
     *
     * @return void
     */
    public function meta($postID, $post, $update)
    {
        // Listings Managers are able to update post meta.
        if (! current_user_can('edit_posts') && ! User::isListingsManager()) {
            wp_die(esc_html__('You are not allowed to store post meta.', 'qibla-events'));
        }

        // Do nothing during auto save or if importing demo content.
        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || F\isImporting()) {
            return;
        }

        // Do nothing during auto draft.
        // If the post meta will be saved, the default values may not works as expected.
        // Do nothing if the post status is 'trash' or WordPress is performing an untrash action.
        // @codingStandardsIgnoreLine
        $action = F\filterInput($_GET, 'action', FILTER_SANITIZE_STRING);
        if ('auto-draft' === $post->post_status || 'trash' === $post->post_status || 'untrash' === $action) {
            return;
        }

        // Get the current screen, used to know which meta-box must be processed.
        // Since this method may be hooked in various points, the function get_current_screen may not exists.
        $currentScreen = F\currentScreen();
        // Don't perform anything if there is no screen set.
        if (! $currentScreen) {
            return;
        }

        // Get the Fields from meta-boxes.
        foreach ($this->metaBoxes as $metabox) :
            // Get the Meta-box arguments.
            $metaboxArgs = $metabox->getArgs();

            // Check if the meta-box is a valid one to work with.
            if (! in_array($currentScreen->id, $metaboxArgs['screen'], true)) {
                continue;
            }

            // Exclude metaboxes that are not allowed in this new screen.
            if (method_exists($metabox, 'exclude') and $metabox->exclude($post)) {
                continue;
            }

            // Check the admin referrer.
            $metabox->checkAdminReferer();
            // Retrieve the fields.
            $fields = $metabox->getFields();

            if (! empty($fields)) :
                $validateFormInstance = new Validate();
                $validate             = $validateFormInstance->validate($fields, array('allow_empty' => true));

                // Update the post meta.
                foreach ($validate['valid'] as $name => $value) :
                    switch ($name) :
                        /*
                         * Listings Map Location
                         *
                         * Listing map location is not a single simple value to store within the database.
                         * Instead it is a composed meta value treated in two different ways.
                         *
                         * 1 - As a string with the following components: lat,lng:address
                         * 2 - As an instance of ListingLocation.
                         *
                         * Why this?
                         *
                         * Because the latitude and longitude are post meta but the address is a term.
                         * This allow us to improve the queries to search the listings by address.
                         *
                         * When search for an address, we can just sanitize by title the address and include within the
                         * query as tax_query.
                         */
                        case 'qibla_mb_map_location':
                            // Get the current location from the db.
                            $location = new ListingLocation($post);
                            $oldValue = $location->location();

                            try {
                                // Create the instance and store the data.
                                $locationDataStore = ListingLocationStore::createFromString($post, $value);
                                $locationDataStore->store($update);
                            } catch (\Exception $e) {
                                $debugInstance = new Debug\Exception($e);
                                'dev' === QB_ENV && $debugInstance->display();
                            }
                            break;

                        default:
                            $oldValue = get_post_meta($postID, '_' . $name, true);

                            if ($update) {
                                update_post_meta($postID, '_' . $name, $value);
                            } else {
                                add_post_meta($postID, '_' . $name, $value, true);
                            }
                            break;
                    endswitch;

                    /**
                     * After store post meta
                     *
                     * @since 1.0.0
                     *
                     * @param string $name     The name of the meta. Aka the meta key.
                     * @param mixed  $value    The value of the meta.
                     * @param mixed  $post     The current post.
                     * @param bool   $update   If the post has been updated or created.
                     * @param mixed  $oldValue The old meta value.
                     * @param Store  $this     The instance of the store class.
                     */
                    do_action('qibla_events_metabox_after_store_meta', $name, $value, $post, $update, $oldValue, $this);
                endforeach;

                unset($oldValue);

            endif;
        endforeach;
    }
}
