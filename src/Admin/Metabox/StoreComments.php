<?php
/**
 * StoreComments
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Admin\Metabox
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    GNU General Public License, version 2
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

namespace QiblaEvents\Admin\Metabox;

use QiblaEvents\Functions as F;
use QiblaEvents\Form\Validate;
use QiblaEvents\Admin\AbstractMetaboxStore;

/**
 * Class StoreComments
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Admin\Metabox
 */
class StoreComments extends AbstractMetaboxStore
{
    /**
     * Store Meta
     *
     * @since 1.0.0
     *
     * @param int   $commentID The comment ID.
     * @param array $data      The comment data.
     *
     * @throws \Exception If for some reason the user is not allowed to store the data.
     */
    public function meta($commentID, $data)
    {
        if (! current_user_can('edit_posts')) {
            wp_die(esc_html__('You are not allowed to store comments meta.', 'qibla-events'));
        }

        if (! in_array(current_filter(), array('edit_comment', 'comment_post'), true)) {
            throw new \Exception('You cannot save comment meta outside of the edit_comment and comment_post filters.');
        }

        // Get the current screen, used to know which meta-box must be processed.
        $currentScreen = F\currentScreen();
        // Don't do anything if current screen is not set.
        if (! $currentScreen) {
            return;
        }

        // Get the Fields from meta-boxes.
        foreach ($this->metaBoxes as $metaBox) :
            // Get the Meta-box arguments.
            $metaBoxArgs = $metaBox->getArgs();

            if (! in_array($currentScreen->id, $metaBoxArgs['screen'], true)) {
                continue;
            }

            // Check the admin referrer.
            $metaBox->checkAdminReferer();
            // Retrieve the fields.
            $fields = $metaBox->getFields();

            if (! empty($fields)) {
                $validateFormInstance = new Validate();
                $validate             = $validateFormInstance->validate($fields, array('allow_empty' => true));

                // Update the post meta.
                foreach ($validate['valid'] as $name => $value) {
                    if ('edit_comment' === current_filter()) {
                        update_comment_meta($commentID, '_' . $name, $value);
                    } else {
                        add_comment_meta($commentID, '_' . $name, $value, true);
                    }
                }
            }
        endforeach;
    }
}
