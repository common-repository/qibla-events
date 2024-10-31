<?php
namespace QiblaEvents\Form\Types;

use QiblaEvents\IconsSet;

/**
 * Form IconList Type
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Types
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

/**
 * Class IconList
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class IconList extends Select
{
    /**
     * List
     *
     * [
     *  categoryName => [
     *      'prefix' => string,
     *      'obj'    => IconsSetInterface instance
     *  ]
     * ]
     *
     * @since  1.0.0
     *
     * @var array The list of icons with the prefix and obj.
     */
    protected $list;

    /**
     * Constructor
     *
     * @since  1.0.0
     *
     * @param array $args The arguments for this type.
     */
    public function __construct($args)
    {
        $args = wp_parse_args($args, array(
            'options' => array(),
            'attrs'   => array(),
        ));

        $this->list      = array();
        $list            = $args['options'];
        $args['options'] = array();

        // Build the options.
        foreach ($list as $icons) {
            // Be sure to work with the correct objects.
            if (! $icons instanceof IconsSet\IconsSetInterface) {
                continue;
            }

            // Store the category icons.
            $this->list = array_merge($this->list, array(
                get_class($icons) => array(
                    'prefix' => $icons->getPrefix(),
                    'obj'    => $icons,
                ),
            ));

            // Build the option.
            $args['options'] = array_merge($args['options'], $icons->compact());
        }

        // Add the option none to the list of the icons.
        $args['options'] = array_merge($args['options'], array('none::none'));

        // Set the data type. It's a custom type.
        $args['attrs'] = array_merge($args['attrs'], array(
            'data-type' => 'icon-list',
        ));

        parent::__construct($args);
    }

    /**
     * Get Html
     *
     * @since  1.0.0
     *
     * @return string The html version of this type
     */
    public function getHtml()
    {
        $options = $this->getArg('options');
        if (empty($options)) {
            return '';
        }

        $valueFrag           = explode('::', $this->getValue());
        $currentVendor       = strtolower($valueFrag[0]);
        $currentVendorPrefix = '';
        $vendors             = array_keys($this->list);

        // Add the 'none' option to the category input.
        $options = sprintf(
            '<option value="none" data-vendor-prefix="none"%s>%s</option>',
            selected($currentVendor, 'none', false),
            esc_html__('None', 'qibla-events')
        );

        // Build the vendor options.
        foreach ($vendors as $vendor) {
            $vendorPrefix        = $this->list[$vendor]['prefix'];
            $vendor              = explode('\\', $vendor);
            $vendor              = strtolower(end($vendor));
            $selected            = selected($currentVendor, $vendor, false);
            $currentVendorPrefix = $selected ? $vendorPrefix : $currentVendorPrefix;

            $options .= sprintf(
                '<option value="%s" data-vendor-prefix="%s"%s>%s</option>',
                $vendor,
                $vendorPrefix,
                $selected,
                ucwords($vendor)
            );
        }

        // Add the options to the icon category select.
        $output = sprintf(
            '<select name="%1$s" id="%2$s" class="dltype-icon-list-category">%3$s</select>',
            esc_attr($this->getArg('name') . '_category'),
            esc_attr($this->getArg('id') . '_category'),
            $options
        );

        // Build the options.
        $output .= parent::getHtml();

        // Append the Current Icon.
        $iconClasses = $currentVendorPrefix ? ' ' . $currentVendorPrefix . ' ' . end($valueFrag) : '';
        $output      .= '<i class="dltype-icon-list-current' . $iconClasses . '"></i>';

        if (wp_script_is('admin-iconlist-type', 'registered') &&
            wp_style_is('qibla-form-types', 'registered')
        ) {
            wp_enqueue_style('qibla-form-types');
            wp_enqueue_script('admin-iconlist-type');
        }

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_iconlist_output', $output, $this);

        return $output;
    }
}
