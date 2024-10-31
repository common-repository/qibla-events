<?php
/**
 * Wp Media
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

namespace QiblaEvents\Form\Handlers;

use QiblaEvents\Exceptions\UserCapabilityErrorException;

/**
 * Class Dropzone
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Form\Handlers
 */
class WpMedia
{
    /**
     * Is User Allowed
     *
     * @since  1.0.0
     *
     * @return bool True if allowed, false otherwise
     */
    public function isUserAllowed()
    {
        return current_user_can('upload_files');
    }

    /**
     * Media Handle Sideload
     *
     * @since  1.0.0
     *
     * @param array    $list     A array $_FILES like from which retrieve the data of the file.
     * @param \WP_Post $post     The post to attach to the media.
     * @param null     $desc     The description for the side-load image.
     * @param array    $postData The post data to override.
     *
     * @throws UserCapabilityErrorException If the user isn't allowed to upload files.
     * @throws \LogicException in case the media cannot be uploaded
     *
     * @return int The id of the newly created post or 0 if the media cannot be uploaded.
     */
    public function mediaHandleSideload(array $list, \WP_Post $post, $desc = null, array $postData = array())
    {
        // Check for capability.
        if (! $this->isUserAllowed()) {
            throw new UserCapabilityErrorException('You cannot upload files. Bye!');
        }

        // Handle the side-load.
        $response = media_handle_sideload($list, $post->ID, $desc, $postData);

        if (is_wp_error($response)) {
            throw new \LogicException($response->get_message());
        }

        // This return the newly attache post object.
        return $response;
    }
}
