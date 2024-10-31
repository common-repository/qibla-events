<?php
/**
 * Ajax File Uploader
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

namespace QiblaEvents\Form\Ajax;

use QiblaEvents\Form\Interfaces\Forms;
use QiblaEvents\Form\Types\File;
use QiblaEvents\Functions as F;
use QiblaEvents\Request\AbstractRequestAjax;

/**
 * Class AjaxStoreMediaFile
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AjaxStoreMediaFile extends AbstractRequestAjax
{
    /**
     * @inheritDoc
     */
    public function isValidRequest()
    {
        // @codingStandardsIgnoreLine
        $action  = F\filterInput($_POST, 'dlajax_action', FILTER_SANITIZE_STRING);
        $isValid = 'store_media_file' === $action;

        return $isValid;
    }

    /**
     * Initialize Environment
     *
     * @since  1.0.0
     *
     * @return void
     */
    protected function initializeEnv()
    {
        require_once untrailingslashit(ABSPATH) . '/wp-admin/includes/admin.php';

        do_action('admin_init');
    }

    /**
     * Filter File Fields
     *
     * Return only the files fields from the form passed as argument.
     *
     * @since  1.0.0
     *
     * @param Forms $form The form from which retrieve the files fields
     *
     * @return array The file fields
     */
    protected function filterFileFields(Forms $form)
    {
        $fields = array();
        foreach ($form->getFields() as $key => $field) {
            // Only files and only if use dropzone. Others has been send separately.
            if ($field->getType() instanceof File && $field->getType()->getArg('use_dropzone')) {
                $fields[$key] = $field;
            }
        }

        return $fields;
    }
}
