<?php
/**
 * ListingsLocalizedScript
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

namespace QiblaEvents\Listings;

use QiblaEvents\Functions as F;
use QiblaEvents\Filter\JsonBuilder;

/**
 * Class ListingsLocalizedScript
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Listings
 */
class ListingsLocalizedScript
{
    /**
     * Json Builder
     *
     * @since 1.0.0
     *
     * @var JsonBuilder An instance of the json builder
     */
    private $jsonBuilder;

    /**
     * Json
     *
     * @since 1.0.0
     *
     * @return array The json data
     */
    public function json()
    {
        $this->jsonBuilder->prepare(F\getWpQuery());

        return $this->jsonBuilder->json();
    }

    /**
     * ListingsLocalizedScript constructor
     *
     * @since 1.0.0
     *
     * @param JsonBuilder $jsonBuilder An instance of the json builder.
     */
    public function __construct(JsonBuilder $jsonBuilder)
    {
        $this->jsonBuilder = $jsonBuilder;
    }

    /**
     * Print Script Helper
     *
     * This method print the script markup.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function printScriptFilter()
    {
        $instance = new self(new JsonBuilder());

        printf(
            "<script type=\"text/javascript\">//<![CDATA[\n var jsonListings = %s; \n//]]></script>",
            wp_json_encode($instance->json())
        );
    }
}
