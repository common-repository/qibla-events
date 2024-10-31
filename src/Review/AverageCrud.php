<?php
/**
 * AverageReview
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Review
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

namespace QiblaEvents\Review;

/**
 * Class AverageReview
 *
 * @todo Must Implement the Crud interface.
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Review
 */
class AverageCrud
{
    /**
     * Post
     *
     * @since 1.0.0
     *
     * @var \WP_Post The post associated to the average value
     */
    protected $post;

    /**
     * Average
     *
     * @since 1.0.0
     *
     * @var string The average value
     */
    protected $average;

    /**
     * Meta Key
     *
     * @since 1.0.0
     *
     * @var string The meta key associated to the average value
     */
    protected static $metaKey;

    /**
     * Get comments count
     *
     * @since 1.0.0
     *
     * @uses   get_approved_comments() To retrieve all of the approved comments.
     *
     * @return int The numbers of comments associated to the post
     */
    protected function getCommentsCount()
    {
        $counts = get_approved_comments($this->post->ID);

        return count($counts);
    }

    /**
     * Create the average value
     *
     * @global $wpdb \wpdb The perform the query to retrieve the count value for the ratings
     *
     * @since 1.0.0
     *
     * @return string The average rating value as string.
     */
    protected function createAverage()
    {
        global $wpdb;

        $count   = $this->getCommentsCount();
        $metaKey = self::$metaKey;

        if ($count) {
            $ratings = $wpdb->get_var($wpdb->prepare("
                SELECT SUM(meta_value) FROM $wpdb->commentmeta
                LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
                WHERE meta_key = '{$metaKey}'
                AND comment_post_ID = %d
                AND comment_approved = '1'
                AND meta_value > 0
            ", $this->post->ID));
            $average = number_format($ratings / $count, 2, '.', '');
        } else {
            $average = '0.0';
        }

        return $average;
    }

    /**
     * AverageCrud constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post The post where the average must be stored or read
     */
    public function __construct(\WP_Post $post)
    {
        $this->post    = $post;
        self::$metaKey = RatingCommentMeta::getMetaKey();

        $this->setAverage();
    }

    /**
     * Set Average
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function setAverage()
    {
        $this->average = $this->createAverage();
    }

    /**
     * Read the average
     *
     * @since 1.0.0
     *
     * @return string The average associated to the post
     */
    public function readAverage()
    {
        return $this->average;
    }

    /**
     * Update the average post meta
     *
     * @since 1.0.0
     *
     * @uses   metadata_exist() To check if the meta data exists or not.
     * @uses   update_post_meta() To update the average value.
     * @uses   add_post_meta() If the post meta doesn't exists. Use it instead of the update_post_meta to pass the
     *         'true' value to make the post meta unique.
     *
     * @return void
     */
    public function updateAverage()
    {
        if (metadata_exists('post', $this->post->ID, self::$metaKey)) {
            update_post_meta($this->post->ID, self::$metaKey, true);
        } else {
            add_post_meta($this->post->ID, self::$metaKey, true);
        }
    }

    /**
     * Reset average
     *
     * This method doesn't remove the post meta value but simply update it.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function resetAverage()
    {
        $this->setAverage();
        $this->updateAverage();
    }
}
