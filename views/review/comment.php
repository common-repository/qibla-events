<?php
/**
 * Comment View
 *
 * @since 1.0.0
 * @author    Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright Copyright (c) 2018, Alfio Piccione
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione <alfio.piccione@gmail.com>
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

use QiblaEvents\Functions as F;
?>
<li id="comment-<?php comment_ID(); ?>"
    class="<?php echo \QiblaEvents\Functions\sanitizeHtmlClass($data->containerClass) ?>">
    <div id="div-comment-<?php echo esc_attr($data->ID) ?>" class="dlreview comment-body">

        <div class="dlreview__thumbnail comment-thumbnail">
            <?php echo F\ksesImage($data->avatarMarkup) ?>

            <?php if ($data->avatarMarkup) : ?>
                <div class="dlreview__author comment-author">
                    <?php echo esc_html(sanitize_text_field($data->author)) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="dlreview__content comment-content">

            <?php if ($data->title) : ?>
                <h4 class="dlreview__title comment-title">
                    <?php echo esc_html(sanitize_text_field($data->title)) ?>
                </h4>
            <?php endif; ?>

            <div class="dlreview__meta comment-metadata">
                <?php
                // Show Rating.
                $data->rating and $data->rating->ratingTmpl();

                // Show Date of posted comment.
                if ($data->date) : ?>
                    <time datetime="<?php echo esc_attr($data->date['time']) ?>">
                        <?php echo esc_html($data->date['date']) ?>
                    </time>
                <?php endif; ?>
            </div>

            <div class="dlreview__content--description">
                <?php if ('spam' !== $data->status && 0 === intval($data->status)) : ?>
                    <p class="comment-awaiting-moderation">
                        <?php esc_html_e('Your Review is awaiting moderation.', 'qibla-events'); ?>
                    </p>
                <?php
                elseif (1 === intval($data->status)) :
                    // The comment text.
                    comment_text();

                    if ($data->replyLink) : ?>
                        <a class="comment-reply-link"
                           href="<?php echo esc_url($data->replyLink->url) ?>"
                           data-container="<?php echo esc_attr($data->replyLink->data->container) ?>"
                           data-id="<?php echo intval($data->replyLink->data->id) ?>"
                           data-post="<?php echo intval($data->replyLink->data->postID) ?>">
                            <?php echo esc_html($data->replyLink->label) ?>
                        </a>
                    <?php endif;
                endif; ?>
            </div>
        </div>

    </div>
