<?php
/**
 * Store Term-boxes Fields values.
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Termbox
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
 * along with this program; if not, write to the Free Software.
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace QiblaEvents\Admin\Termbox;

use QiblaEvents\Functions as F;
use QiblaEvents\Form\Validate;
use QiblaEvents\Admin\AbstractMetaboxStore;
use QiblaEvents\User\User;

/**
 * Class Store
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Store extends AbstractMetaboxStore
{
    /**
     * Store Post Meta
     *
     * @since  1.0.0
     *
     * @throws \Exception If method is called directly.
     *
     * @param int    $term_id  Term ID.
     * @param int    $tt_id    Term taxonomy ID.
     * @param string $taxonomy Taxonomy slug.
     *
     * @return void
     */
    public function meta($term_id, $tt_id, $taxonomy)
    {
        // Inline editing not include metaboxes, so we don't need to do anything in this case.
        // @codingStandardsIgnoreLine
        if (F\filterInput($_POST, '_inline_edit', FILTER_SANITIZE_STRING) || F\isImporting()) {
            return;
        }

        // Listings Managers are able to update post meta.
        if (! current_user_can('edit_posts') && ! User::isListingsManager()) {
            wp_die(esc_html__('You are not allowed to store term meta.', 'qibla-events'));
        }

        // Do nothing if the taxonomy is the nav_menu.
        if ('nav_menu' === $taxonomy) {
            return;
        }

        // Get the Fields from meta-boxes.
        foreach ($this->metaBoxes as $metaBox) :
            // Don't process term-boxes that are not allowed within the current screen.
            $metaBoxArgs = $metaBox->getArgs();
            if (! in_array($taxonomy, $metaBoxArgs['screen'], true)) {
                continue;
            }

            // The check on the request does not make check_admin_referer() fail,
            // because if a category is created from the post, the nonce is not validated because it is not present.
            // @codingStandardsIgnoreLine
            $action  = ! empty($_REQUEST['action']) ? F\filterInput($_REQUEST, 'action', FILTER_SANITIZE_STRING) : null;

            if ('add-tag' === $action || 'edittag' === $action && wp_get_referer()) {
                // Check the admin referer.
                $metaBox->checkAdminReferer();
            }

            $fields = $metaBox->getFields();

            $validateFormInstance = new Validate();
            $validate             = $validateFormInstance->validate($fields, array('allow_empty' => true));

            // Update the post meta.
            foreach ($validate['valid'] as $name => $value) {
                update_term_meta($term_id, '_' . $name, $value);
            }
        endforeach;
    }
}
