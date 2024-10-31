<?php
namespace QiblaEvents\Form\Fieldsets;

use QiblaEvents\Form\Abstracts\Fieldset;

/**
 * Field-set
 *
 *  @since      1.0.0
 * @package    QiblaEvents\Form\Fieldsets
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
 * Class BaseFieldset
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class BaseFieldset extends Fieldset
{
    /**
     * Get Field-set Html
     *
     * @since  1.0.0
     *
     * @return string The markup of the field-set
     */
    public function getHtml()
    {
        if (false === $this->argCb('display')) {
            return '';
        }

        // Set the container class.
        $containerClass = array_map('sanitize_html_class', $this->getArg('container_class'));

        // Open container.
        $output = sprintf(
            '<fieldset form="%s" id="%s" name="%s" class="%s">',
            sanitize_key(esc_attr($this->getArg('form'))),
            sanitize_key(esc_attr($this->getArg('id'))),
            sanitize_key(esc_attr($this->getArg('name'))),
            implode(' ', $containerClass)
        );

        // Get the field-set legend.
        $output .= $this->argCb('before_fieldset') . $this->getLegend();
        // Get the description.
        $output .= $this->getDescription();
        // Get the fields.
        foreach ($this->fields as $field) {
            $output .= $field->getHtml();
        }
        // Get the after fieldset callback value.
        $output .= $this->argCb('after_fieldset');
        // Close the container.
        $output .= '</fieldset>';

        return $output;
    }
}
