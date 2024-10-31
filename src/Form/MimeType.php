<?php
/**
 * MimeType
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

namespace QiblaEvents\Form;

use QiblaEvents\Functions as F;

/**
 * Class MimeType
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Form
 */
class MimeType
{
    /**
     * The path of the mime type file
     *
     * @since  1.0.0
     *
     * @var string The path of the mime type file
     */
    protected $mimeFilePath;

    /**
     * Retrieve the list of mime types
     *
     * @since  1.0.0
     *
     * @return array The mime types list
     */
    public function getMimeTypes()
    {
        static $json = null;

        if (! $json) {
            $filesystem = new \WP_Filesystem_Direct(array());
            // Decode the json file.
            $json = json_decode($filesystem->get_contents($this->mimeFilePath));
        }

        return $json;
    }

    /**
     * MimeType constructor
     *
     * @since 1.0.0
     *
     * @param $mimeFilePath
     */
    public function __construct($mimeFilePath)
    {
        $this->mimeFilePath = F\sanitizePath($mimeFilePath);
    }

    /**
     * Get the correct file extension from the mime file
     *
     * @since  1.0.0
     *
     * @throws \Exception If the mime type provided is not correct.
     *
     * @param string $mime The mime type of the file.
     *
     * @return string The file extension, empty string if the extension cannot be retrieved.
     */
    public function getExtFromMime($mime)
    {
        // Not a valid mime.
        if (false === strpos($mime, '/')) {
            return '';
        }

        // Get the mime types.
        $exts = array_flip($this->getMimeTypes());

        if (empty($exts)) {
            return '';
        }

        return (isset($exts[$mime]) ? $exts[$mime] : '');
    }
}
