<?php
/**
 * Review Form
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

use QiblaEvents\ListingsContext\Types;

/**
 * Class ReviewForm
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Review
 */
class ReviewForm
{
    /**
     * The fields
     *
     * @since 1.0.0
     *
     * @var array The list of the fields
     */
    private $fields;

    /**
     * Form Arguments
     *
     * @since 1.0.0
     *
     * @var array The list of the arguments
     */
    private $args;

    /**
     * The post
     *
     * @since 1.0.0
     *
     * @var \WP_Post The post for which show the Review form
     */
    private $post;

    /**
     * Non Allowed Fields for author
     *
     * @since 1.0.0
     *
     * @var array A list of class names of fields not allowed to show on form
     */
    private static $notFieldsAllowedIfAuthor = array(
        'QiblaEvents\Review\RatingField',
        'QiblaEvents\Review\ReviewTitleField',
    );

    /**
     * Set Hooks
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function setHooks()
    {
        // Additional fields.
        add_action('comment_form_field_comment', array($this, 'addFields'));
        // Filter the default fields.
        add_filter('comment_form_fields', array($this, 'filterFields'));
    }

    /**
     * Unset Hooks
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function unsetHooks()
    {
        // Additional fields.
        remove_action('comment_form_field_comment', array($this, 'addFields'));
        // Filter the default fields.
        remove_filter('comment_form_fields', array($this, 'filterFields'));
    }

    /**
     * ReviewForm constructor
     *
     * @since 1.0.0
     *
     * @param array $fields The fields for the form.
     */
    public function __construct(\WP_Post $post, array $fields)
    {
        $this->post   = $post;
        $this->fields = $fields;
        $this->args   = array(
            'title_reply'          => esc_html__('Write a Review', 'qibla-events'),
            'label_submit'         => esc_html__('Submit review', 'qibla-events'),
            'comment_field'        => '<p class="comment-form-comment"><label for="comment">' .
                                      esc_html__('Your Review', 'qibla-events') .
                                      ' <span class="required">*</span></label> <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea></p>',
            'format'               => 'html5',
            'comment_notes_before' => '',
        );
    }

    /**
     * Filter Fields Based on the Current User
     *
     * @since 1.0.0
     *
     * @param array $fields The fields to filter.
     *
     * @return array The fields filtered list
     */
    public function filterFieldsBasedOnCurrentUser(array $fields)
    {
        $author = $this->post->post_author;
        $user   = wp_get_current_user();

        if ($user->exists() && $user->ID === intval($author)) {
            $notAllowedFields = self::$notFieldsAllowedIfAuthor;
            $fields           = array_filter($fields, function ($field) use ($notAllowedFields) {
                return ! in_array(get_class($field), $notAllowedFields, true);
            });
        }

        return $fields;
    }

    /**
     * Add the additional fields
     *
     * The class use the built in comments form and hookin in there to insert extra fields.
     * This method insert those fields to the comment field form string.
     *
     * Due to the comment form implementation we cannot hook where we can add the fields directly to the list
     * of the comments fields.
     *
     * @since 1.0.0
     *
     * @param string $formMarkup The markup for the form.
     *
     * @return string The filtered form string
     */
    public function addFields($formMarkup)
    {
        $text = '';

        // Filter but keep the original fields.
        $fields = $this->filterFieldsBasedOnCurrentUser($this->fields);

        if ($fields) {
            foreach ($fields as $field) {
                $text .= $field->getField();
            }
        }

        // Append the form markup.
        $text .= $formMarkup;

        return $text;
    }

    /**
     * Filter the fields
     *
     * This method filter the fields of the WordPress comment form.
     *
     * @since 1.0.0
     *
     * @param array $commentFields The list of the comment form fields.
     *
     * @return array The filtered list
     */
    public function filterFields(array $commentFields)
    {
        // We don't need the url (website) for reviews.
        unset($commentFields['url']);

        return $commentFields;
    }

    /**
     * The Form Markup
     *
     * @since 1.0.0
     *
     * @uses  comment_form() To show the comment form.
     *
     * @return void
     */
    public function html()
    {
        $this->setHooks();
        comment_form($this->args);
        $this->unsetHooks();
    }

    /**
     * Review Form Filter
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function reviewFormFilter()
    {
        $types = new Types();
        $post  = get_post();

        if ($post instanceof \WP_Post && $types->isListingsType($post->post_type)) {
            $form = new static($post, array(
                new RatingField(),
                new ReviewTitleField(),
            ));

            $form->html();
        }
    }
}
