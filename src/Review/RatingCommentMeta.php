<?php

namespace QiblaEvents\Review;

/**
 * Rating
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Front\Comments
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

/**
 * Class RatingCommentMeta
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Front\Review
 */
class RatingCommentMeta
{
    /**
     * The Comment
     *
     * @since 1.0.0
     *
     * @var \WP_Comment The comment related with the review
     */
    protected $comment;

    /**
     * Meta Key
     *
     * @since 1.0.0
     *
     * @var string The meta key to retrieve the rating value
     */
    protected static $metaKey = '_qibla_mb_comment_rating';

    /**
     * Default rating value
     *
     * @since 1.0.0
     *
     * @var string The default rating value slug.
     */
    protected static $defaultRating = '4';

    /**
     * Rating
     *
     * @since 1.0.0
     *
     * @var Rating The rating instance
     */
    protected $rating;

    /**
     * Rating constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Comment $comment The comment related with the rating
     */
    public function __construct(\WP_Comment $comment, Rating $rating)
    {
        $this->comment = $comment;
        $this->rating  = $rating;
    }

    /**
     * Get the rating
     *
     * Note:
     * This is for every comment.
     *
     * @since 1.0.0
     *
     * @return Rating The rating data including.
     */
    public function getRating()
    {
        // Retrieve the meta and set the values.
        $value = get_comment_meta($this->comment->comment_ID, self::$metaKey, true);

        $this->rating->setRatingValue($value);
        $this->rating->setRatingDefaultValue($value);

        return $this->rating;
    }

    /**
     * Get default rating value
     *
     * @since 1.0.0
     *
     * @return string The default rating value
     */
    public static function getDefaultRatingValue()
    {
        return static::$defaultRating;
    }

    /**
     * Get the Meta key
     *
     * @since 1.0.0
     *
     * @param bool $public To retrieve the public meta key or the hidden post meta value.
     *
     * @return string The meta key value
     */
    public static function getMetaKey($public = false)
    {
        return $public ? substr(static::$metaKey, 1) : static::$metaKey;
    }
}
