<?php
/**
 * EmailOnExpiration
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

namespace QiblaEvents\Listing\Expire;

use QiblaEvents\Functions as F;

/**
 * Class EmailOnExpiration
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class EmailOnExpiration
{
    /**
     * The expired post
     *
     * @since  1.0.0
     *
     * @var \WP_Post
     */
    protected $post;

    /**
     * Recipient
     *
     * @since  1.0.0
     *
     * @var \WP_User The user from which retrieve the data to send email.
     */
    protected $recipient;

    /**
     * Subject
     *
     * @since  1.0.0
     *
     * @var string The email subject
     */
    protected $subject;

    /**
     * Email Message
     *
     * @since  1.0.0
     *
     * @var string The message to send via email
     */
    protected $message;

    /**
     * Email Headers
     *
     * @since  1.0.0
     *
     * @var array A list of additional headers
     */
    protected $headers;

    /**
     * Build Message
     *
     * @since  1.0.0
     *
     * @uses   QiblaEvents\Functions\getPluginOption() to retrieve the placeholder message.
     *
     * @return string The message to send
     */
    protected function buildMessage()
    {
        $message = str_replace(array(
            '{{username}}',
            '{{listing_name}}',
        ), array(
            sanitize_user($this->recipient->first_name . ' ' . $this->recipient->last_name),
            $this->sanitizeString($this->post->post_title),
        ), F\getPluginOption('events', 'expired_listing_email'));

        return $message;
    }

    /**
     * Build the Headers
     *
     * @since  1.0.0
     *
     * @return array The list of the additional headers
     */
    protected function buildHeaders()
    {
        $headers = array();
        // Get headers values.
        $email = sanitize_email(get_option('new_admin_email'));

        // Set the From only if a valid email has been provided.
        if ($email) {
            $from      = esc_html(sanitize_text_field(get_bloginfo('name')));
            $headers[] = 'From ' . $from . '<' . $email . '>';
        }

        // Allow to use html.
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        return $headers;
    }

    /**
     * Sanitize String
     *
     * @since  1.0.0
     *
     * @param string $string The string to sanitize.
     *
     * @return string The sanitize string
     */
    protected function sanitizeString($string)
    {
        return esc_html(sanitize_text_field($string));
    }

    /**
     * EmailOnExpiration constructor
     *
     * @since 1.0.0
     *
     * @param \WP_User $recipient The user from which retrieve the data to send email.
     * @param \WP_Post $post      The post that is expired.
     */
    public function __construct(\WP_User $recipient, \WP_Post $post)
    {
        $this->recipient = $recipient;
        $this->post      = $post;
        $this->subject   = sprintf(
        /* Translators: %s is the post title */
            esc_html_x('Listing %s Expired', 'expired_email', 'qibla-events'),
            $this->sanitizeString($this->post->post_title)
        );
        $this->message   = $this->buildMessage();
        $this->headers   = $this->buildHeaders();
    }

    /**
     * Send Email
     *
     * @since  1.0.0
     *
     * @uses   wp_mail() To send the email
     * @uses   QiblaEvents\Functions\ksesPost() to sanitize the message content
     *
     * @throws \LogicException In case the message is an empty string.
     *
     * @return mixed Whatever the wp_mail function returns
     */
    public function send()
    {
        if (!$this->message) {
            throw new \LogicException('Cannot send expired listing email because message is empty.');
        }

        return wp_mail(
            sanitize_email($this->recipient->user_email),
            $this->sanitizeString($this->subject),
            F\ksesPost($this->message),
            $this->headers
        );
    }

    /**
     * Handle the email send on expiration
     *
     * @since  1.0.0
     *
     * @param \WP_Post $post The expired post
     */
    public static function handleFilter(\WP_Post $post)
    {
        // Get the author of the listing expired.
        $author = new \WP_User($post->post_author);

        $instance = new static($author, $post);
        $instance->send();
    }
}
