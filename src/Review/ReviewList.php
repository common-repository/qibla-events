<?php
namespace QiblaEvents\Review;

use QiblaEvents\Plugin;
use QiblaEvents\ListingsContext\Context;

/**
 * ReviewList
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
 * Class ReviewList
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Review
 */
class ReviewList
{
    /**
     * Set hooks
     *
     * @since 1.0.0
     *
     * @return void
     */
    protected function setHooks()
    {
        // Override the comments template of the theme.
        add_filter('comments_template', function () {
            return Plugin::getPluginDirPath('/views/review/comments.php');
        });
        // Change the title for the reviews section.
        add_filter('qibla_template_engine_data_comments_section_title', function ($data) {
            if (Context::isSingleListings()) :
                // Data for view.
                $data = new \stdClass();
                // Comments Number.
                $cnumber = number_format_i18n(get_comments_number());
                // Get the Title.
                $title = get_the_title();

                // The comment Title.
                if ($title) {
                    $data->commentTitle = sprintf(
                        esc_html(
                        /* Translators: the %2$s is the title of the post. The %1$s is number of the reviews. */
                            _n('One review to %2$s', '%1$s reviews to %2$s', $cnumber, 'qibla-events')),
                        $cnumber,
                        '<span class="dlcomments__title__article-title">' . $title . '</span>'
                    );
                } else {
                    $data->commentTitle = sprintf(
                        esc_html(
                        /* Translators: the %1$s is number of the reviews. */
                            _n('One review', '%1$s reviews', $cnumber, 'qibla-events')),
                        $cnumber
                    );
                }
            endif;

            return $data;
        });
    }

    /**
     * Review list template
     *
     * @uses   comments_template() to show the comments template.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function reviewListTmpl()
    {
        $this->setHooks();
        comments_template();
    }

    /**
     * The Filter
     *
     * A convenient way to access to the review list.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function reviewListFilter()
    {
        /**
         * Disable Comments Template
         *
         * @todo  Future. In a future version of WordPress the compact template will be removed, so, have a look in wp-include/comment-template.php and see if something changed.
         *
         * @since 1.0.0
         *
         * @param string $disable 'yes' to disable comments template, 'no' otherwise
         */
        $disable = apply_filters('qibla_events_disable_reviews', 'no');

        if ('no' === $disable) {
            $reviews = new self;
            $reviews->reviewListTmpl();
        }
    }
}
