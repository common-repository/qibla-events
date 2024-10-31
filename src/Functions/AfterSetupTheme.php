<?php
/**
 * AfterSetupTheme
 *
 * @package   QiblaEvents\Functions
 * @copyright Copyright (c) 2018, Alfio Piccione
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione <alfio.piccione@gmail.com>
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

namespace QiblaEvents\Functions;

/**
 * Add Image Sizes
 *
 * @since  1.0.0
 *
 * @return void
 */
function addImageSizes()
{
    // The Generic Jumbo-tron Image Size.
    add_image_size('qibla-events-gallery', 1920, 1080, true);
    add_image_size('qibla-post-thumbnail-square', 250, 250, true);
    add_image_size('qibla-post-thumbnail-wide', 530, 255, true);

    // This image size is set even within the theme.
    if (! has_image_size('qibla-post-thumbnail-loop')) {
        add_image_size('qibla-post-thumbnail-loop', 346, 295, true);
    }
}
