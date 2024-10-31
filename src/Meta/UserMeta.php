<?php
/**
 * UserMeta
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

namespace QiblaEvents\Meta;

use QiblaEvents\Functions as F;

/**
 * Class UserMeta
 *
 * @since   1.0.0
 * @package QiblaEvents\Meta
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class UserMeta implements MetaInterface
{
    /**
     * User
     *
     * @since 1.0.0
     *
     * @var \WP_User The user from which retrieve the meta
     */
    private $user;

    /**
     * Key
     *
     * @since 1.0.0
     *
     * @var string The meta key
     */
    private $key;

    /**
     * Value
     *
     * @since 1.0.0
     *
     * @var string The meta value
     */
    private $value;

    /**
     * Single
     *
     * @since 1.0.0
     *
     * @var bool If the meta must be a single or multiple
     */
    private $single;

    /**
     * Previous Value
     *
     * @since 1.0.0
     *
     * @var string The previous value to compare when update meta
     */
    private $prev;

    /**
     * UserMeta constructor
     *
     * @since 1.0.0
     *
     * @param \WP_User $user   The user from which retrieve the meta.
     * @param string   $key    The meta key.
     * @param string   $value  The meta value.
     * @param bool     $single If the meta must be a single or multiple.
     * @param string   $prev   The previous value to compare when update meta.
     */
    public function __construct(\WP_User $user, $key, $value = '', $single = true, $prev = '')
    {
        // Make sure we have valid key.
        if (! is_string($key) || '' === $key) {
            throw new \InvalidArgumentException('The key parameter must be a non empty string.');
        }

        $key = F\sanitizeMetaKey($key);

        if (! $key) {
            throw new \InvalidArgumentException('The meta key appear not a valid key.');
        }

        $this->user   = $user;
        $this->key    = F\sanitizeMetaKey($key);
        $this->value  = $value;
        $this->prev   = '';
        $this->single = $single;
    }

    /**
     * @inheritdoc
     */
    public function create()
    {
        return add_user_meta($this->user->ID, $this->key, $this->value, $this->single);
    }

    /**
     * @inheritdoc
     */
    public function read()
    {
        return get_user_meta($this->user->ID, $this->key, $this->single);
    }

    /**
     * @inheritdoc
     *
     * @throws \RuntimeException In case the meta is set to be single but not exists in db.
     */
    public function update()
    {
        // If meta is single but not exists don't do anything here.
        // Otherwise WordPress will try to create the meta in multiple mode.
        if ($this->single and ! $this->exists()) {
            throw new \RuntimeException('Cannot update single meta if the meta doesn\'t exists.');
        }

        return update_user_meta($this->user->ID, $this->key, $this->value, $this->prev);
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        return delete_post_meta($this->user->ID, $this->key, $this->value);
    }

    /**
     * @inheritdoc
     */
    public function exists()
    {
        return metadata_exists('user', $this->user->ID, $this->key);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->key;
    }
}
