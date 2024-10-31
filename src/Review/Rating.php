<?php

namespace QiblaEvents\Review;

use QiblaEvents\TemplateEngine\Engine;

/**
 * Rating
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
 * Class Rating
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Review
 */
class Rating
{
    /**
     * The rating value
     *
     * @since 1.0.0
     *
     * @var string The rating value
     */
    protected $value;

    /**
     * Default value
     *
     * @since 1.0.0
     *
     * @var string The default value if the value isn't provided.
     */
    protected $default;

    /**
     * Format the number
     *
     * @since 1.0.0
     *
     * @param float|int $number The number to format.
     *
     * @return string The formatted number.
     */
    protected function formatNumber($number)
    {
        return (string)intval($number);
    }

    /**
     * Rating constructor
     *
     * @since 1.0.0
     *
     * @param string $value   The value of the rating. Optional. Default to Zero.
     * @param string $default The default value. Optional. Default to zero.
     */
    public function __construct($value = '0', $default = '0')
    {
        $this->value   = $this->formatNumber($value);
        $this->default = $this->formatNumber($default);
    }

    /**
     * Set Rating Value
     *
     * @since 1.0.0
     *
     * @throws \InvalidArgumentException If the passed value is not numeric.
     *
     * @param float|int $value The rating value.
     *
     * @return void
     */
    public function setRatingValue($value)
    {
        if (! is_numeric($value)) {
            // Because the php version supported.
            throw new \InvalidArgumentException(sprintf(
                '%1$s value for %2$s is not numeric',
                $value,
                __METHOD__
            ));
        }

        $this->value = $this->formatNumber($value);
    }

    /**
     * Set Rating Default Value
     *
     * @since 1.0.0
     *
     * @throws \InvalidArgumentException If the passed value is not numeric.
     *
     * @param float|int $value The rating value.
     *
     * @return void
     */
    public function setRatingDefaultValue($value)
    {
        if (! is_numeric($value)) {
            // Because the php version supported.
            throw new \InvalidArgumentException(sprintf(
                '%1$s value for %2$s is not numeric',
                $value,
                __METHOD__
            ));
        }

        $this->default = $this->formatNumber($value);
    }

    /**
     * Get Rating Value
     *
     * @since 1.0.0
     *
     * @return string The rating value
     */
    public function getRatingValue()
    {
        return $this->value;
    }

    /**
     * Get the rating value
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getRatingDefaultValue()
    {
        return $this->default;
    }

    /**
     * Get the rating data
     *
     * @since 1.0.0
     *
     * @return \stdClass The rating data including { label, value, width }
     */
    public function getRatingData()
    {
        // Get the rating list.
        $ratingList = self::getRatingList();

        return (object)array(
            'label' => isset($ratingList[$this->value]) ?
                $ratingList[$this->value] :
                esc_html__('Unknown', 'qibla-events'),
            'value' => (0 < $this->value ? $this->value : $this->default),
            'width' => (int)(0 < $this->value ? ($this->value / 5) * 100 : 0),
        );
    }

    /**
     * Get the rating list
     *
     * @since 1.0.0
     *
     * @return array The rating values list
     */
    public static function getRatingList()
    {
        return array(
            '1' => esc_html__('Very Poor', 'qibla-events'),
            '2' => esc_html__('Not Bad', 'qibla-events'),
            '3' => esc_html__('Average', 'qibla-events'),
            '4' => esc_html__('Good', 'qibla-events'),
            '5' => esc_html__('Perfect', 'qibla-events'),
        );
    }

    /**
     * The rating template
     *
     * @since 1.0.0
     *
     * @uses   Engine To create and render the template.
     *
     * @return void
     */
    public function ratingTmpl()
    {
        // Retrieve the Data for the rating.
        $data = $this->getRatingData();

        $engine = new Engine('rating', $data, '/views/review/rating.php');
        $engine->render();
    }
}
