<?php
/**
 * DateTime
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

namespace QiblaEvents\Utils;

/**
 * Class DateTime
 *
 * @since   1.0.0
 * @package QiblaEvents\Utils
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class DateTime extends \DateTime
{
    /**
     * Time Stamp to DateTime
     *
     * Convert the timestamp to datetime object based on WordPress options
     *
     * @since 1.0.0
     *
     * @param int $time The timestamp value.
     *
     * @throws \Exception If the value of the parameter $time is not an int or is a negative number.
     *
     * @return \DateTime The datetime instance
     */
    public static function timeStampToDateTime($time)
    {
        // Assign to a new variable to give info to the user in case of the Exception is thrown.
        $_time = $time;

        if (! is_int($_time)) {
            throw new \Exception(sprintf(
                'The time value must be an valid timestamp in %1$s, got %2$s',
                __FUNCTION__,
                $time
            ));
        }

        // Create the DateTime based on WordPress date and time options.
        $timeZone = new TimeZone();
        $dateTime = new self('now', $timeZone->getTimeZone());
        // Set the correct timestamp.
        $dateTime->setTimestamp($_time);

        return $dateTime;
    }
}
