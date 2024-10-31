<?php
namespace QiblaEvents\Template;

use QiblaEvents\Front\Settings\Listings;
use QiblaEvents\TemplateEngine\Engine as TEngine;

/**
 * Google Map
 *
 * @since      1.0.0
 * @package    QiblaEvents\Front
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione
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
 * Class Front-end Google Map
 *
 * @since   1.0.0
 * @package QiblaEvents\Template
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class GoogleMap implements TemplateInterface
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        return new \stdClass();
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new TEngine('google_map', $data, '/views/map/googleMap.php');
        $engine->render();
    }

    /**
     * @inheritdoc
     */
    public static function template($object = null)
    {
        if (Listings::showMapOnArchive()) {
            $instance = new static;
            $instance->tmpl($instance->getData());
        }
    }
}
