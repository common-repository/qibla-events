<?php
/**
 * Term box Form
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
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace QiblaEvents\Admin\Termbox;

use QiblaEvents\Functions as F;

/**
 * Class AbstractTermboxForm
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractTermboxForm extends AbstractTermbox
{
    /**
     * Fields Arguments
     *
     * @since  1.0.0
     *
     * @var array A list of fields for this box
     */
    protected $fields;

    /**
     * Nonce Action
     *
     * @since  1.0.0
     *
     * @var string The nonce action
     */
    protected $nonceAction;

    /**
     * Nonce Name
     *
     * @since  1.0.0
     *
     * @var string The name of the nonce
     */
    protected $nonceName;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param array $termBoxArgs A list of arguments for this box.
     */
    public function __construct($termBoxArgs)
    {
        $class             = explode('\\', get_called_class());
        $this->nonceName   = '_dl_tb-' . strtolower(end($class));
        $this->nonceAction = strrev($this->nonceName);

        parent::__construct($termBoxArgs);
    }

    /**
     * Set Fields
     *
     * @since  1.0.0
     *
     * @param array $fields A list of arguments for build fields.
     *
     * @return void
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Get Fields
     *
     * @since  1.0.0
     *
     * @return array The fields list
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Meta-box Callback
     *
     * Show the meta-box content fields
     *
     * @since  1.0.0
     */
    public function callBack()
    {
        foreach ($this->fields as $field) {
            // @codingStandardsIgnoreLine
            echo F\ksesPost($field);
        }

        // Add the nonce.
        wp_nonce_field($this->nonceAction, $this->nonceName, true);
    }

    /**
     * Check Admin Referer
     *
     * @since  1.0.0
     *
     * @uses   check_admin_referer()
     *
     * @return mixed Whatever the check-admin_referer return.
     */
    public function checkAdminReferer()
    {
        return check_admin_referer($this->nonceAction, $this->nonceName);
    }
}
