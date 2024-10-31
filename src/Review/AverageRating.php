<?php

namespace QiblaEvents\Review;

use QiblaEvents\Exceptions\InvalidPostException;

/**
 * Average Rating
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
 * Class AverageRating
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Review
 */
class AverageRating
{
    /**
     * Average
     *
     * @since 1.0.0
     *
     * @var AverageCrud The instance of the average crud class
     */
    protected $average;

    /**
     * Rating
     *
     * @since 1.0.0
     *
     * @var Rating The rating instance
     */
    protected $rating;

    /**
     * AverageRating constructor
     *
     * @since 1.0.0
     *
     * @param AverageCrud $average The instance.
     * @param Rating      $rating  The instance.
     */
    public function __construct(AverageCrud $average, Rating $rating)
    {
        $this->average = $average;
        $this->rating  = $rating;

        $this->setAverageRating();
    }

    /**
     * Get Data
     *
     * @since 1.0.0
     *
     * @return \stdClass The data consumed by the template
     */
    public function setAverageRating()
    {
        $this->rating->setRatingValue(
            $this->average->readAverage()
        );
    }

    /**
     * The Average Template
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function averageRatingTmpl()
    {
        $this->rating->ratingTmpl();
    }

    /**
     * Get the Average rating value
     *
     * @since 1.0.0
     *
     * @return mixed Whatever the getRatingValue return
     */
    public function getAverageRatingValue()
    {
        return $this->rating->getRatingValue();
    }

    /**
     * The Average Rating Filter
     *
     * @since 1.0.0
     *
     * @throws InvalidPostException In case the post cannot be retrieved.
     *
     * @return void
     */
    public static function averageRatingFilter($post = null)
    {
        // Retrieve the post.
        $post = get_post($post);

        if (! $post) {
            throw new InvalidPostException();
        }

        $instance = new static(new AverageCrud($post), new Rating());
        $instance->averageRatingTmpl();
    }
}
