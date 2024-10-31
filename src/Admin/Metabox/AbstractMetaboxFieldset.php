<?php
/**
 * Meta-box Field-set
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

/**
 * Class AbstractMetaboxFieldset
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractMetaboxFieldset extends AbstractMetaboxForm
{
    /**
     * Meta-box Field-set
     *
     * @since  1.0.0
     *
     * @var array A list of field-set
     */
    protected $fieldsets;

    /**
     * AbstractMetaboxFieldset Constructor
     *
     * @since 1.0.0
     *
     * @inheritDoc
     */
    public function __construct(array $metaBoxArgs, array $postBoxClasses = array())
    {
        parent::__construct($metaBoxArgs, $postBoxClasses);

        $this->fieldsets = array();
    }

    /**
     * Set Field-sets
     *
     * @since  1.0.0
     *
     * @param array $fieldsets A list of fieldsets.
     *
     * @return void
     */
    public function setFieldsets($fieldsets)
    {
        $name = sanitize_key($this->getID());

        /**
         * Filter Fieldsets
         *
         * @since 1.0.0
         *
         * @param array            $fieldsets The list of the fieldsets to set.
         * @param MetaboxInterface $this      The instance of this class.
         */
        $fieldsets = apply_filters("qibla_metabox_set_fieldsets_{$name}", $fieldsets, $this);

        // Set the property.
        $this->fieldsets = array_merge($this->fieldsets, $fieldsets);
    }

    /**
     * Get Field-sets
     *
     * @since  1.0.0
     *
     * @return array The fields related with this meta-box
     */
    public function getFieldsets()
    {
        return $this->fieldsets;
    }

    /**
     * Get Fields
     *
     * @since  1.0.0
     *
     * @return array The fields that are into the fieldset
     */
    public function getFields()
    {
        $fields = array();

        foreach ($this->fieldsets as $fieldset) {
            $fields = array_merge($fields, $fieldset->getFields());
        }

        return $fields;
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
        foreach ($this->fieldsets as $fieldset) {
            // @codingStandardsIgnoreLine
            echo F\ksesPost($fieldset);
        }

        // Add the nonce.
        wp_nonce_field($this->nonceAction, $this->nonceName, true);
    }
}
