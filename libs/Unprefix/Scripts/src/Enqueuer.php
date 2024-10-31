<?php
namespace Unprefix\Scripts;

/**
 * Scripts Enqueuer
 *
 * @package Unprefix\Scripts
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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
 * Class Enqueue
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Enqueuer extends ScriptAbstract
{
    /**
     * Enqueue Scripts and Styles
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function enqueuer()
    {
        foreach ($this->getList() as $context => $items) :
            if (in_array($context, array('styles', 'scripts'), true)) :
                $self = $this;
                array_walk($items, function ($item) use ($context, $self) {
                    // Based on callback return value.
                    if (! $self->mustPerformed($item, 6)) {
                        return;
                    }

                    switch ($context) {
                        case 'styles':
                            $suffix      = 'css';
                            $funcTest    = 'wp_style_is';
                            $funcEnqueue = 'wp_enqueue_style';
                            break;
                        default:
                            $suffix      = 'js';
                            $funcTest    = 'wp_script_is';
                            $funcEnqueue = 'wp_enqueue_script';
                            break;
                    }

                    // Test by the handle.
                    if (! $funcTest($item[0], 'registered')) {
                        return;
                    }

                    // Enqueue.
                    call_user_func_array($funcEnqueue, $item);
                });
            endif;
        endforeach;
    }

    /**
     * Insert Attribute in script
     *
     * @since  1.0.0
     *
     * @param string $tag    The current tag string.
     * @param string $handle The handle of the script to manipulate.
     *
     * @return string The tag string
     */
    public function insertAttributes($tag, $handle)
    {
        // Retrieve the scripts list.
        $list = $this->getList();
        // Get the type for the scripts list.
        $type = (false !== strpos($tag, 'script') ? 'scripts' : 'styles');
        // Get the def list.
        $def = (isset($list[$type][$handle]) ? $list[$type][$handle] : '');

        if ($def) {
            // Initialize the $attr value.
            // We'll store every new key value pair attribute.
            $attr = '';

            if (! empty($def[7])) {
                // For every attr made with attr => value.
                foreach ($def[7] as $key => $val) {
                    // Build the new attribute.
                    $attr .= ' ' . $key . '="' . $val . '"';
                }

                if ($attr) {
                    // Then set all attributes to the script tag.
                    $tag = str_replace(' src', $attr . ' src', $tag);
                }
            }
        }

        return $tag;
    }
}
