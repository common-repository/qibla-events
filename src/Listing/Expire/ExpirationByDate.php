<?php
/**
 * Expiration by Date
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

use QiblaEvents\Utils\TimeZone;

/**
 * Class ExpirationByDate
 *
 * @todo Move outside of the Listing namespace, the class work with any post that have a relation with a product.
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ExpirationByDate
{
    /**
     * Post
     *
     * @since  1.0.0
     *
     * @var \WP_Post
     */
    private $post;

    /**
     * Expiration in Days
     *
     * @since  1.0.0
     *
     * @var int The days after the post will expire
     */
    private $expirationInDays;

    /**
     * Time Zone
     *
     * @since  1.0.0
     *
     * @var TimeZone The timezone for date
     */
    private $timeZone;

    /**
     * Date Format
     *
     * @since  1.0.0
     *
     * @var string The format for the dateTime
     */
    private static $dateTimeFormat = 'Y-m-d H:i:s';

    /**
     * Expire Unlimited
     *
     * @since  1.0.0
     *
     * @var int The value associated to the unlimited expiration
     */
    const EXPIRE_UNLIMITED = -1;

    /**
     * Get Post Published Date
     *
     * @since  1.0.0
     *
     * @return \DateTime The date time instance
     */
    private function getPublishedDate()
    {
        $date = get_the_date(self::$dateTimeFormat, $this->post->ID);
        $date = new \DateTime($date, $this->timeZone);

        return $date;
    }

    /**
     * Expiration constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post     The post to check for expiration.
     * @param int      $expire   The days after which the post will expire.
     * @param string   $timeZone The time zone related to the post date.
     */
    public function __construct(\WP_Post $post, $expire, $timeZone)
    {
        $this->post             = $post;
        $this->expirationInDays = intval($expire);
        $this->timeZone         = $timeZone;
    }

    /**
     * Calculate Expiration Date
     *
     * @since  1.0.0
     *
     * @return int The time when the post will expire in timestamp. self::EXPIRE_UNLIMITED if not expire.
     */
    public function calculateExpirationDate()
    {
        $time = $this->expirationInDays;

        if (self::EXPIRE_UNLIMITED !== $time) {
            // Get the published date.
            $date = $this->getPublishedDate();
            // Create a period of N days.
            $interval = new \DateInterval('P0Y' . $time . 'D');

            // Add the days.
            $date->add($interval);
            // Get the time stamp.
            $time = $date->getTimestamp();
        }

        // May return the same value of self::EXPIRE_UNLIMITED.
        return $time;
    }

    /**
     * Check if Post Expired
     *
     * @since  1.0.0
     *
     * @return bool True if expired. False otherwise.
     */
    public function isExpired()
    {
        // If unlimited cannot be expired.
        if (self::EXPIRE_UNLIMITED === $this->expirationInDays) {
            $expired = false;
        } else {
            $today      = new \DateTime('now', $this->timeZone);
            $expiration = $this->calculateExpirationDate();

            $expired = $expiration < $today->getTimestamp();
        }

        return $expired;
    }
}
