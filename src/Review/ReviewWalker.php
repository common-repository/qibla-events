<?php

namespace QiblaEvents\Review;

use QiblaEvents\TemplateEngine\Engine;

/**
 * ReviewWalker
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

/**
 * Class ReviewWalker
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Review
 */
class ReviewWalker extends \Walker_Comment
{
    /**
     * Is Commenter the Post Author?
     *
     * @since 1.0.0
     *
     * @param \WP_Comment $comment The comment instance from which retrieve the comment author email.
     * @param \WP_Post    $post    The post instance from which retrieve the post author email.
     *
     * @return bool True if commenter is the post author, false otherwise
     */
    private function isCommenterThePostAuthor(\WP_Comment $comment, \WP_Post $post)
    {
        return $comment->comment_author_email === get_user_by('ID', $post->post_author)->user_email;
    }

    /**
     * Get the comment data
     *
     * @since 1.0.0
     *
     * @param \WP_Comment $comment The comment from which retrieve data.
     * @param int         $depth   The current comment depth.
     * @param array       $args    The arguments for the comment.
     *
     * @return \stdClass The data
     */
    protected function getCommentData(\WP_Comment $comment, $depth, array $args)
    {
        // Initialize Data.
        $data = new \stdClass();
        // The post related with the comment.
        $post = get_post(intval($comment->comment_post_ID));

        // Set the ID.
        $data->ID = intval($comment->comment_ID);
        // Comment Class.
        $data->containerClass = get_comment_class($this->has_children ? 'parent' : '', $comment);
        // Set the data status for the comment.
        $data->status = $comment->comment_approved;
        // Set the comment author.
        $data->author = get_comment_author($comment);
        // Get the avatar.
        $data->avatarMarkup = 0 !== $args['avatar_size'] ? get_avatar($comment, $args['avatar_size']) : '';
        // Get Comment date.
        $data->date = array(
            'time' => get_comment_time('c'),
            'date' => get_comment_date('', $comment),
        );

        $data->rating = null;
        $data->title  = '';

        // Fields allowed only for non listing authors.
        if (false === $this->isCommenterThePostAuthor($comment, $post)) {
            $rating       = new RatingCommentMeta($comment, new Rating());
            $data->rating = $rating->getRating();

            $data->title = get_comment_meta($comment->comment_ID, '_qibla_mb_comment_title', true);
        }

        // Show Reply btn or not.
        // Show if current user is the author of the listing.
        $data->replyLink = array();
        // Remember the thread_comments must be enabled and user logged in.
        if ($post &&
            wp_get_current_user()->ID === intval($post->post_author) &&
            get_option('thread_comments') &&
            false === $this->isCommenterThePostAuthor($comment, $post) &&
            1 === $depth
        ) {
            $data->replyLink = (object)array(
                'url'   => add_query_arg(array('replytocom' => $comment->comment_ID)) . '#respond',
                'label' => esc_html__('Reply', 'qibla-events'),
                'data'  => (object)array(
                    'container' => "div-comment-{$comment->comment_ID}",
                    'id'        => $comment->comment_ID,
                    'postID'    => $post->ID,
                ),
            );
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreLine
    public function end_el(&$output, $comment, $depth = 0, $args = array())
    {
        if (! empty($args['end-callback'])) {
            ob_start();
            call_user_func($args['end-callback'], $comment, $args, $depth);
            $output .= ob_get_clean();

            return;
        }

        if ('div' === $args['style']) {
            $output .= '</div>';
        } else {
            $output .= '</li>';
        }
    }

    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreLine
    protected function comment($comment, $depth, $args){
        $data = $this->getCommentData($comment, $depth, $args);

        $engine = new Engine('listings_review_comment', $data, '/views/review/comment.php');
        $engine->render();
    }

    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreLine
    protected function html5_comment($comment, $depth, $args)
    {
        $data = $this->getCommentData($comment, $depth, $args);

        $engine = new Engine('listings_review_html5comment', $data, '/views/review/html5comment.php');
        $engine->render();
    }
}
