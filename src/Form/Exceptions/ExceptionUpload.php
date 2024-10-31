<?php
namespace QiblaEvents\Form\Exceptions;

/**
 * Exceptions Upload
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Exceptions
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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
 * Exception Upload
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ExceptionUpload extends \Exception
{
    /**
     * Image Size Err
     *
     * @since  1.0.0
     *
     * @var int The value of the Err for image size
     */
    const UPLOAD_ERR_IMG_SIZE = 9;

    /**
     * Get The Message
     *
     * @since  1.0.0
     *
     * @param int $code The code int about the error.
     *
     * @return string The message error.
     */
    private function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $msg = esc_html__('The file exceed the max size defined by the server.', 'qibla-events');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $msg = esc_html__('The file exceed the size defined by the form.', 'qibla-events');
                break;
            case UPLOAD_ERR_PARTIAL:
                $msg = esc_html__('The file was not uploaded correctly.', 'qibla-events');
                break;
            case UPLOAD_ERR_NO_FILE:
                $msg = esc_html__('Missed file.', 'qibla-events');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $msg = esc_html__('Temporary directory missed.', 'qibla-events');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $msg = esc_html__('Ops! Seems it is not possible to write the file.', 'qibla-events');
                break;
            case UPLOAD_ERR_EXTENSION:
                $msg = esc_html__('The file extension mismatched.', 'qibla-events');
                break;
            case self::UPLOAD_ERR_IMG_SIZE:
                $msg = esc_html__('The resolution of the image is not correct.', 'qibla-events');
                break;
            default:
                $msg = esc_html__('Unknown error message during upload.', 'qibla-events');
                break;
        }

        return $msg;
    }

    /**
     * Constructor.
     *
     * @since  1.0.0
     *
     * @param int $code The code int about the error.
     */
    public function __construct($code)
    {
        $msg = $this->codeToMessage($code);

        parent::__construct($msg, $code);
    }
}
