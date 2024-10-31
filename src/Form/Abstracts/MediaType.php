<?php
namespace QiblaEvents\Form\Abstracts;

use QiblaEvents\Functions as F;

/**
 * Form Media Type Type
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Abstracts
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
 * Class MediaType
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class MediaType extends Type
{
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
            'type'  => 'text',
            'btn'   => array(),
            'attrs' => array(),
        ));

        // Button Arguments.
        $args['btn'] = array_merge(array(
            'label'            => esc_html__('Add Media', 'qibla-events'),
            'data_media_title' => esc_html__('Upload Media', 'qibla-events'),
            'data_multiple'    => 'no',
        ), $args['btn']);

        if (! isset($args['attrs']['class'])) {
            $args['attrs']['class'] = array();
        }

        // Add the data-type because it's a custom type.
        $args['attrs'] = array_merge($args['attrs'], array(
            'data-type' => 'media-uploader',
        ));

        parent::__construct($args);
    }

    /**
     * Sanitize
     *
     * @since  1.0.0
     *
     * @param string $value The value to sanitize.
     *
     * @return string The sanitized value of this type. Empty string if the value is not correct.
     */
    public function sanitize($value)
    {
        $value = explode(',', $value);

        foreach ($value as &$v) {
            $v = absint($v);
        }

        if (1 === count($value)) {
            $value = $value[0];
        } else {
            $value = implode(',', $value);
        }

        return $value;
    }

    /**
     * Escape
     *
     * @since  1.0.0
     *
     * @return string The escaped value of this type
     */
    public function escape()
    {
        return $this->sanitize($this->getValue());
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
        // Get the media to perform some conditional checks.
        // False is for when there is no value stored.
        $media = $this->getValue() ? get_post($this->getValue()) : false;

        // Set the class needed by the btn.
        $btnClass = array('button');
        // Set the btn argument.
        $btnArg = $this->getArg('btn');

        // Null is for when the post cannot be retrieved.
        // Means we have the input value but the media not exists.
        if (null === $media) {
            $this->setValue(0);
        }

        $input = sprintf(
            '<input type="%s" name="%s" id="%s"%s />',
            sanitize_key($this->getArg('type')),
            esc_attr($this->getArg('name')),
            esc_attr($this->getArg('id')),
            $this->getAttrs()
        );

        // The btn is hidden by default if the type is not multiple and the media exists.
        // Right now we don't support non javascript media types.
        if ('no' === $btnArg['data_multiple'] && $media) {
            $btnClass[] = 'hidden';
        }

        $btn = sprintf(
            '<button id="%1$s" name="%2$s" data-mediatitle="%3$s" class="%4$s" data-dlmime="%5$s" data-multiple="%6$s">%7$s</button>',
            esc_attr($this->getArg('id')) . '_btn',
            esc_attr($this->getArg('name')) . '_btn',
            esc_html($btnArg['data_media_title']),
            F\sanitizeHtmlClass($btnClass),
            esc_attr($this->getArg('mime')),
            esc_html($btnArg['data_multiple']),
            esc_html($btnArg['label'])
        );

        // If media doesn't exists, append an invalid description after the button.
        // @note Don't append it after the input or the media btn will not open the media modal.
        // When the media doesn't exists but the type has a value, that probably means
        // the media was removed without update the type value.
        if (null === $media) {
            $btn .= '<p>' . esc_html__('Error Media not found.', 'qibla-events') . '</p>';
        }

        return $input . $btn;
    }
}
