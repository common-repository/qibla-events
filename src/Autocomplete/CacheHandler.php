<?php
namespace QiblaEvents\Autocomplete;

/**
 * Cache Handler
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

use QiblaEvents\Utils\Json\Encoder;

/**
 * Class CacheHandler
 *
 * @todo Remove the `cases` logic when WordPress will support the `after_delete_post_{$post->post_type}`
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Autocomplete
 */
class CacheHandler
{
    /**
     * Cacher
     *
     * @since  1.0.0
     *
     * @var CacheInterface The instance to manage the cached data
     */
    private $cacher;

    /**
     * Post Type
     *
     * @since 1.0.0
     *
     * @var string The post type from which build the suggestions
     */
    private $postType;

    /**
     * Containers
     *
     * @since 1.0.0
     *
     * @var array Containers to use in addition of the post. Such as: taxonomies, post meta
     */
    private $containers;

    /**
     * Initial Container
     *
     * @since 1.0.0
     *
     * @var string The container to use to show the initial suggestions
     */
    private $initialContainer;

    /**
     * Cases
     *
     * @since 1.0.0
     *
     * @var array A list of filters allowed to perform the action
     */
    private $cases;

    /**
     * Deleted Post
     *
     * Since WordPress doesn't have an action like `after_delete_post_{$post->post_type}`
     * We need to know that the post deleted was or wasn't a listings post type.
     * This to prevent to update the list of the post type set in this instance even when isn't necessary.
     *
     * @since 1.0.0
     *
     * @var \WP_Post The post instance before being deleted.
     */
    private $deletedPost;

    /**
     * AutocompleteDataCacheHandler constructor
     *
     * @since 1.0.0
     *
     * @throw \InvalidArgumentException If the passed post type is not valid string.
     *
     * @param CacheInterface $cacher           The instance to manage the cached data.
     * @param string         $postType         The post type from which build the suggestions.
     * @param array          $cases            A list of filters allowed to perform the action.
     * @param array          $containers       Containers to use in addition of the post. Such as: taxonomies, post
     *                                         meta.
     * @param string         $initialContainer The container to use to show the initial suggestions.
     */
    public function __construct(
        CacheInterface $cacher,
        $postType,
        array $cases = array(),
        array $containers = array(),
        $initialContainer = ''
    ) {
        if (! is_string($postType)) {
            throw new \InvalidArgumentException($postType . ': is not a valid string.');
        }

        $this->cacher           = $cacher;
        $this->postType         = $postType;
        $this->cases            = $cases;
        $this->containers       = $containers;
        $this->initialContainer = $initialContainer;
        $this->deletedPost      = null;

        // Since WordPress doesn't have an action like `after_delete_post_{$post->post_type}`
        // We need to know that the post deleted was or wasn't a listings post type.
        // This to prevent to update the list of the post type set in this instance even when isn't necessary.
        if (in_array('after_delete_post', $this->cases, true)) {
            $that = $this;
            add_action('before_delete_post', function ($postID) use ($that) {
                $that->deletedPost = get_post($postID);
            });
        }
    }

    /**
     * Update the Data Transient
     *
     * @since  1.0.0
     *
     * @throws \RuntimeException in case the post type doesn't exists.
     *
     * @return void
     */
    public function updateCachedDataOnPostInsert()
    {
        if (! in_array(current_action(), $this->cases, true)) {
            return;
        }

        // Since WordPress doesn't include an action like `after_delete_post_{$post->post_type}`
        // In this case we need to check it separately.
        // So, if the deleted post was not one of the listings post type, just return.
        if ('after_delete_post' === current_action()) {
            if (property_exists($this->deletedPost, 'post_type') &&
                $this->deletedPost->post_type !== $this->postType
            ) {
                return;
            }
        }

        if (! post_type_exists($this->postType)) {
            throw new \RuntimeException($this->postType . ': post type does not exists');
        }

        $that = $this;
        add_action('shutdown', function () use ($that) {
            // Switch lang.
            $lang          = null;
            $transientName = $that->postType;
            if (\QiblaEvents\Functions\isWpMlActive()) {
                $lang = \QiblaEvents\Functions\setCurrentLang();
                if ($lang) {
                    global $sitepress;
                    $sitepress->switch_lang($lang);
                    // Set transient name.
                    $transientName = esc_attr($that->postType . '-' . $lang);
                }
            }

            // Generate the json.
            $generator = new Generator(
                new \WP_Query(array(
                    'post_type'      => $that->postType,
                    'posts_per_page' => -1,
                    'no_found_rows'  => true,
                )),
                $that->containers,
                $that->initialContainer
            );

            $generator->prepare()
                      ->includeTerms()
                      ->generate();

            // Encode it.
            $json = new Encoder($generator->data());

            // Set the $json value so we can pass it to the wp functions.
            $json = $json->prepare()
                         ->json();

            $that->cacher->set($json, $transientName);
        });
    }
}
