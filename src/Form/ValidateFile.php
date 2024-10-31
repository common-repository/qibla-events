<?php
/**
 * ValidateFile
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

use QiblaEvents\Form\Exceptions\ExceptionUpload;

/**
 * Class ValidateFile
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ValidateFile
{
    /**
     * List
     *
     * @since  1.0.0
     *
     * @var array The list of the files to validate
     */
    protected $list;

    /**
     * Arguments
     *
     * @since  1.0.0
     *
     * @var array The arguments to use to validate the files list
     */
    protected $args;

    /**
     * Re Order Files
     *
     * @since  1.0.0
     *
     * @param array $list An array $_FILES like to re-order
     *
     * @return array The re-order array list
     */
    private function reOrderFiles($list)
    {
        $newList = array();

        /*
         * If we are validating multiple files let's reorder them.
         * By default the $_FILES structure is:
         *
         * [
         *   'name' => [
         *      0 => 'filename',
         *      1 => 'filename',
         *   ],
         *   'type' => [
         *      0 => 'mimetype',
         *      1 => 'mimetype',
         *   ]
         * etc....
         * ]
         *
         * We want to have the list ordered by index.
         */
        foreach ($list as $name => $item) {
            // Is multiple?
            if (is_array($item['name'])) {
                $numCicle        = count($item['name']);
                $keys            = array_keys($list[$name]);
                $internalCounter = count($keys);

                for ($c = 0; $c < $numCicle; ++$c) {
                    for ($ic = 0; $ic < $internalCounter; ++$ic) {
                        $newList[$name][$c][$keys[$ic]] = $list[$name][$keys[$ic]][$c];
                    }
                }
            } else {
                $newList[$name] = $item;
            }
        }

        return $newList;
    }

    /**
     * ValidateFile constructor
     *
     * @since 1.0.0
     *
     * @param array $list
     */
    public function __construct(array $list, array $args)
    {
        $this->list = $this->reOrderFiles($list);
        $this->args = $args;
        $this->pos  = 0;
    }

    /**
     * Validate
     *
     * @since  1.0.0
     *
     * @throws ExceptionUpload In case the item is empty or have some error.
     *
     * @return bool Always return true when no exception has been thrown
     */
    public function validate(array $item)
    {
        // @fixme This gones in conflict with Export Settings. Find a way to allow the use of this step.
//        if (! is_uploaded_file($item['tmp_name'])) {
//            // Die silently.
//            die;
//        }

        if (empty($item)) {
            throw new ExceptionUpload(UPLOAD_ERR_CANT_WRITE);
        }

        if (! empty($item['error'])) {
            throw new ExceptionUpload($item['error']);
        } elseif ($item['size'] > $this->args['max_size']) {
            throw new ExceptionUpload(UPLOAD_ERR_FORM_SIZE);
        } elseif (! in_array($item['type'], $this->args['allowed_mime'], true)) {
            throw new ExceptionUpload(UPLOAD_ERR_EXTENSION);
        }

        return true;
    }

    /**
     * Get the files list
     *
     * @since  1.0.0
     *
     * @return array The list of the files
     */
    public function getList()
    {
        return $this->list;
    }
}
