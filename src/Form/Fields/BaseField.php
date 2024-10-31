<?php
namespace QiblaEvents\Form\Fields;

use QiblaEvents\Form\Abstracts\Field;

/**
 * Abstract Field
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Fields
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
 * Class BaseField
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class BaseField extends Field
{
    /**
     * Get Field Html
     *
     * @since  1.0.0
     *
     * @return string The html version of the field
     */
    public function getHtml()
    {
        if (false === $this->argCb('display')) {
            return '';
        }

        if ('hidden' === $this->getType()->getArg('type')) {
            return $this->getType()->getHtml();
        }

        // Set the container class.
        $containerClass = array_map('sanitize_html_class', $this->getArg('container_class'));
        // Add if required.
        $containerClass = array_filter(array_merge($containerClass, array(
            in_array('required', $this->getType()->getArg('attrs'), true) ? 'is-required' : '',
        )));
        // Open container.
        $output = sprintf(
            '<%1$s class="%2$s" %3$s>',
            tag_escape($this->getArg('container')),
            implode(' ', $containerClass),
            \QiblaEvents\Functions\attrs($this->getArg('attrs'))
        );

        if ('before' === $this->getArg('label_position')) {
            // Get the field label.
            $output .= $this->argCb('before_label') . $this->getLabel();
            // Get the field description.
            $output .= $this->getDescription();
        }

        // Get the input type.
        $output .= $this->argCb('before_input') . $this->getType() . $this->argCb('after_input');

        if ('after' === $this->getArg('label_position')) {
            // Get the field label.
            $output .= $this->argCb('before_label') . $this->getLabel();
            // Get the field description.
            $output .= $this->getDescription();
        }

        // Close the container.
        $output .= '</' . tag_escape($this->getArg('container')) . '>';

        return $output;
    }
}
