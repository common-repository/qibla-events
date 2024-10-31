<?php
/**
 * Media Functions
 *
 * @package QiblaEvents\Functions
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 *
 * @license GPL 2
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

use QiblaEvents\Functions as F;

/**
 * Get full image url
 *
 * Retrieve the original image url from the cropped one
 *
 * @since  1.0.0
 *
 * @param  string $imageUrl The cropped image url.
 *
 * @return string The original image url
 */
function getFullImageUrl($imageUrl)
{
    preg_match('/(^http|https)\:\/\/([a-z0-9].*)(\-[0-9]+x[0-9]+)\.([a-z0-9]+$)/', $imageUrl, $matches);

    // A cropped image is found.
    if ($matches) {
        $imageUrl = $matches[1] . '://' . $matches[2] . '.' . $matches[4];

        return $imageUrl;
    }

    return $imageUrl;
}

/**
 * Get attachment image alt
 *
 * Get attachment image alternate text
 *
 * @since  1.0.0
 *
 * @param  mixed $id The id of the attachment image.
 *
 * @return mixed     The alternative attachment text or any other get_post_meta value returned
 */
function getAttachmentImageAlt($id)
{
    $alt = trim(wp_strip_all_tags(F\getPostMeta('_wp_attachment_image_alt', '', $id)));

    return $alt;
}
