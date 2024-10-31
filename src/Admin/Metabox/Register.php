<?php
/**
 * Meta-box Register
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Metabox
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

namespace QiblaEvents\Admin\Metabox;

use QiblaEvents\Functions as F;
use QiblaEvents\RegisterInterface;

/**
 * Class Register
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Register implements RegisterInterface
{
    /**
     * Meta-boxes List
     *
     * @since  1.0.0
     *
     * @var array The list of the meta-boxes to register
     */
    private $metaboxes;

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @param array $metaBoxes The list of the meta-boxes to register
     */
    public function __construct(array $metaBoxes)
    {
        $this->metaboxes = $metaBoxes;
    }

    /**
     * Add Meta-boxes
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function register()
    {
        // Get the current screen, used to know which meta-box must be processed.
        $currentScreen = F\currentScreen();
        // Don't do anything if current screen is not set.
        if (! $currentScreen) {
            return;
        }

        // As WordPress register all meta-boxes without take care of the current screen, we do the same.
        foreach ($this->metaboxes as $metabox) :
            // Get the Meta-box arguments.
            $metaboxArgs = $metabox->getArgs();

            // Check if the meta-box is a valid one to work with.
            if (! in_array($currentScreen->id, $metaboxArgs['screen'], true)) {
                continue;
            } elseif (method_exists($metabox, 'exclude') and $metabox->exclude()) {
                continue;
            }

            // Additional post box Classes. Added to the meta-box wrapper.
            foreach ($metaboxArgs['screen'] as $screen) {
                if (method_exists($metabox, 'postboxClasses')) {
                    add_filter("postbox_classes_{$screen}_{$metaboxArgs['id']}", array($metabox, 'postboxClasses'));
                }
            }

            // The meta-box must be hidden?
            // We don't need to check if the key exists/isset because the key is added directly to the top class.
            if (true === $metaboxArgs['is_hidden']) {
                add_filter('hidden_meta_boxes', function ($hidden) use ($metaboxArgs) {
                    array_push($hidden, $metaboxArgs['id']);

                    return $hidden;
                });
            }

            /**
             * Filter Meta-box Arguments
             *
             * @since 1.0.0
             *
             * @param array            $metaBoxArgs The arguments for the add_meta_box function.
             * @param MetaboxInterface $metaBox     The meta-box object.
             */
            $metaboxArgs = apply_filters('qibla_events_add_meta_box_arguments', $metaboxArgs, $metabox);

            /*
             * Add Meta box
             *
             * Don't use the meta-box arguments directly because of the 'screen' argument may not be in the correct
             * position. see Metabox::__construct.
            */
            if (! empty($metaboxArgs)) {
                add_meta_box(
                    $metaboxArgs['id'],
                    $metaboxArgs['title'],
                    $metaboxArgs['callback'],
                    $metaboxArgs['screen'],
                    $metaboxArgs['context'],
                    $metaboxArgs['priority'],
                    $metaboxArgs['callback_args']
                );
            }
        endforeach;
    }
}
