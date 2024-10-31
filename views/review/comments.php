<?php
/**
 * Comments Template
 *
 * The actions names used within this template are the same of the ones in theme.
 * This is for a while, until the new system for reviews will be implemented.
 *
 * @since 1.0.0
 *
 * @license GNU General Public License, version 2
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

if (have_comments() || comments_open()) : ?>

    <section id="comments" class="dlcomments">

        <?php
        if (! post_password_required()) :
            /**
             * Before comments
             *
             * @since 1.0.0
             */
            do_action('qibla_events_before_comments');

            /**
             * Before comments list
             *
             * @since 1.0.0
             */
            do_action('qibla_events_before_comments_list'); ?>

            <ol class="dlcomments__list">
                <?php wp_list_comments(array(
                    'walker'      => new \QiblaEvents\Review\ReviewWalker(),
                    'style'       => 'ul',
                    'max_depth'   => 2,
                    'type'        => 'comment',
                    'avatar_size' => 64,
                )); ?>
            </ol>

            <?php
            /**
             * After comments list
             *
             * @since 1.0.0
             */
            do_action('qibla_events_after_comments_list');
        endif; ?>

        <?php
        /**
         * After comments
         *
         * @since 1.0.0
         */
        do_action('qibla_events_after_comments'); ?>

    </section>

    <?php
endif;
