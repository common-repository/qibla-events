<?php
/**
 * Scripts Register
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

namespace Unprefix\Scripts;

use QiblaEvents\RegisterInterface;

/**
 * Class Register
 *
 * @since  1.0.0
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class Register extends ScriptAbstract implements RegisterInterface
{
    /**
     * Must De-Register
     *
     * @since  1.0.0
     *
     * @param array $item The current item script or style.
     *
     * @return bool If the script or style must be de-registered or not.
     */
    protected function mustDeregistered($item)
    {
        // Only two values 'front' or 'admin', if not exists the index, must be de-registered in both context.
        if (! isset($item['context'])) {
            return true;
        }

        return ('front' === $item['context'] && is_admin() ? false : true);
    }

    /**
     * Append Min if should
     *
     * The function append the minified string if necessary.
     * Based on SCRIPT_DEBUG constant.
     *
     * @since  1.0.0
     *
     * @param array  $item The script item.
     * @param string $ext  The extension of the file.
     *
     * @return array The filtered item
     */
    public function appendMinIfNeeded($item, $ext)
    {
        // Sanitize the extension.
        $ext = trim($ext, '.');

        // Set the environment.
        $env = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? 'dev' : 'prod';
        // If the file is not within the current plugin domain, rejected it.
        if (false === strpos($item[1], home_url('/'))) {
            return $item;
        }

        // Set the properly file url based on environment.
        // Prod env have .min suffix.
        if ('prod' === $env && false === strpos($item[1], '.min.')) {
            // Get the file path and sanitize.
            $filePath = trim(substr($item[1], 0, -3), '.');
            $item[1]  = $filePath . '.min.' . $ext;
        }

        return $item;
    }

    /**
     * Register Scripts and Styles
     *
     * @since  1.0.0
     *
     * @throws \Exception In case the list to retrieve doesn't exists.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->getList() as $context => $items) :
            if (in_array($context, array('styles', 'scripts'), true)) :
                $self = $this;
                array_walk($items, function ($item) use ($context, $self) {
                    // Based on callback return value.
                    if (! $self->mustPerformed($item, 5)) {
                        return;
                    }

                    switch ($context) {
                        case 'styles':
                            $suffix       = 'css';
                            $funcRegister = 'wp_register_style';
                            break;
                        default:
                            $suffix       = 'js';
                            $funcRegister = 'wp_register_script';
                            break;
                    }

                    // Set the properly file url based on environment.
                    $item = $self->appendMinIfNeeded($item, $suffix);

                    // Register.
                    call_user_func_array($funcRegister, $item);
                });
            endif;
        endforeach;
    }

    /**
     * De-register Scripts
     *
     * @since  1.0.0
     */
    public function deregister()
    {
        foreach ($this->getList('styles') as $item) {
            if (! $this->mustDeregistered($item)) {
                continue;
            }

            wp_dequeue_style($item);
            wp_deregister_style($item);
        }

        foreach ($this->getList('scripts') as $item) {
            if (! $this->mustDeregistered($item)) {
                continue;
            }

            wp_dequeue_script($item);
            wp_deregister_script($item);
        }
    }
}
