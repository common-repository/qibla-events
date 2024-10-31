<?php
/**
 * Review Reply Commenter Check
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

namespace QiblaEvents\Review;

use QiblaEvents\ListingsContext\Types;
use QiblaEvents\User\UserFactory;

/**
 * Class ReviewReplyCommenterCheck
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Comment
 */
class ReviewReplyCommenterCheck
{
    /**
     * Check Allowed Reply Filter
     *
     * The method check if the comment is a reply for listing post and in this case if the commenter
     * is the listing author.
     *
     * Reviews reply are allowed only for the listing author.
     *
     * @since 1.0.0
     *
     * @param mixed $comment Valid comment data.
     *
     * @return mixed $comment The passed comment for the check
     */
    public static function checkAllowedReplyFilter($comment)
    {
        $types      = new Types();
        $commentObj = null;

        // Clean the comment data.
        if (is_array($comment) && ! $comment instanceof \WP_Comment) {
            $commentObj = (object)$comment;
        }

        // First of all, check if we are working with a child comment.
        // If not, there's nothing to check. Users are allowed to submit comments.
        if (0 === intval($commentObj->comment_parent)) {
            return $comment;
        }

        // Then check if we have a valid post.
        // Get the post.
        $post = get_post($commentObj->comment_post_ID);

        // No post? Something went wrong.
        if (! $post) {
            wp_die('Cheatin\' Uh?');
        } elseif (! $types->isListingsType($post->post_type)) {
            // Need to check only for listings posts.
            return $comment;
        }

        // Get the comment author.
        $commentAuthor = UserFactory::create($commentObj->comment_author_email);

        // No comment author? Uhm.
        if (! $commentAuthor instanceof \WP_User) {
            wp_die('Cheatin\' Uh?');
        }

        // Check by ID.
        if (intval($post->post_author) !== $commentAuthor->ID) {
            wp_die('Cheatin\' Uh?');
        }

        return $comment;
    }
}
