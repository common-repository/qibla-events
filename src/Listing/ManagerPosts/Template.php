<?php
/**
 * Template
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace QiblaEvents\Listing\ManagerPosts;

use QiblaEvents\Functions as F;
use QiblaEvents\ListingsContext\Types;
use QiblaEvents\Utils\TimeZone;
use QiblaEvents\Listing\Expire\ExpirationByDate;
use QiblaEvents\Listings\ListingsPost;
use QiblaEvents\Listing\UserListingsPosts;
use QiblaEvents\TemplateEngine\Engine;

/**
 * Class Template
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Template
{
    /**
     * The Template path
     *
     * @since  1.0.0
     * @access protected static
     *
     * @var string The path where the file of the template is located
     */
    protected static $templatePath = '/views/listings/managerTmpl.php';

    /**
     * Posts
     *
     * @since  1.0.0
     * @access protected
     *
     * @var UserListingsPosts The posts that will be consumed by the template
     */
    protected $posts;

    /**
     * Template constructor
     *
     * @since 1.0.0
     *
     * @param UserListingsPosts $posts The Posts that will be consumed by the template.
     */
    public function __construct(UserListingsPosts $posts)
    {
        $this->posts = $posts;
    }

    /**
     * Get Data
     *
     * @since  1.0.0
     * @access public
     *
     * @return \stdClass The data instance to use within the template
     */
    public function getData()
    {
        $data        = new \stdClass();
        $data->posts = array();

        // Set the elements needed by the template.
        foreach ($this->posts as $post) :
            $timeZone = new TimeZone();
            $newPost  = new \stdClass();

            // Get the post ID, always useful.
            $newPost->ID = $post->ID;
            // Set the properties for the current post.
            $newPost->thumbnail  = F\getPostThumbnailAndFallbackToJumbotronImage($post, array(64, 64));
            $newPost->permalink  = $post->guid;
            $newPost->postTitle  = $post->post_title;
            $newPost->isFeatured = F\stringToBool(F\getPostMeta('_qibla_mb_is_featured', 'no', $post));
            // Set the expiration date.
            $expirationDate = new ExpirationByDate(
                $post,
                F\getPostMeta('_qibla_mb_listing_expiration', ExpirationByDate::EXPIRE_UNLIMITED, $post),
                $timeZone->getTimeZone()
            );
            $expirationDate = $expirationDate->calculateExpirationDate();
            // When -1 is the expiration date, means no expiration will be performed, the value is set explicitly
            // to 'Never'.
            $newPost->expirationDate = (
                ExpirationByDate::EXPIRE_UNLIMITED === $expirationDate) ?
                esc_html__('Never', 'qibla-events') :
                date('Y/m/d', $expirationDate);

            // Set the status.
            // The use of the prefix 'Qibla' for the post status is a workaround.
            // @todo The status must be an array, using array('label' => '', 'slug' => '')
            $newPost->status = (ListingsPost::EXPIRED_STATUS === $post->post_status) ?
                esc_html_x('Qibla Expired', 'manager-posts', 'qibla-events') :
                $post->post_status;

            // Get the package slug related with the current post.
            $package = F\getPostMeta('_qibla_mb_listing_package_related', 'none', $post);

            // Prevent to use the 'none' as valid package post slug.
            if ('none' !== $package) {
                // Get the post.
                $package = F\getPostByName($package, 'listing_package');

                // Remember to check for the instance of the object.
                // Existing listings may not have a package assigned or the package may no longer exists.
                // So, checking only for the meta value is not enough.
                if ($package instanceof \WP_Post) {
                    $newPost->actions = new ActionsTemplate(new Actions(new Types(), $package, $post));
                }
            }

            // Insert the new post data.
            $data->posts[] = $newPost;
        endforeach;

        return $data;
    }

    /**
     * Template
     *
     * @since  1.0.0
     * @access public
     *
     * @param \stdClass $data The data class to be consumed within the template.
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new Engine('manager_listings', $data, static::$templatePath);
        $engine->render();
    }
}
