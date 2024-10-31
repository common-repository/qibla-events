<?php
/**
 * Meta-box Form
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

use \QiblaEvents\Functions as F;

/**
 * Class AbstractMetaboxForm
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractMetaboxForm extends AbstractMetabox
{
    /**
     * Meta-box Fields
     *
     * @since  1.0.0
     *
     * @var object The object containing the fields of the meta-box
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
     * @param array $metaBoxArgs    A list of arguments for the meta-box.
     * @param array $postBoxClasses The class attribute values to apply to this meta-box.
     */
    public function __construct($metaBoxArgs, array $postBoxClasses = array())
    {
        $class             = explode('\\', get_called_class());
        $this->nonceName   = '_dl_mb-' . strtolower(end($class));
        $this->nonceAction = strrev($this->nonceName);

        parent::__construct($metaBoxArgs, $postBoxClasses);
    }

    /**
     * Set Fields
     *
     * @since  1.0.0
     *
     * @param array $fields The fields to store.
     *
     * @return void
     */
    public function setFields(array $fields)
    {
        $name = sanitize_key($this->getID());

        /**
         * Filter Fields
         *
         * @since 1.0.0
         *
         * @param array            $fields The list of the fields to set.
         * @param MetaboxInterface $this   The instance of this class.
         */
        $this->fields = apply_filters("qibla_metabox_set_fields_{$name}", $fields, $this);
    }

    /**
     * Get Fields
     *
     * @since  1.0.0
     *
     * @return object The fields related with this meta-box
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
        echo '<table class="widefat fixed stiped dl-metabox-table"><tbody class="dl-metabox-table__body">';
        foreach ($this->fields as $field) {
            // @codingStandardsIgnoreLine
            echo F\ksesPost($field);
        }
        echo '</tbody></table>';

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
