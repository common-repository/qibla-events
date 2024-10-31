<?php
namespace QiblaEvents\Form\Forms;

use QiblaEvents\Form\Abstracts\Form;

/**
 * Base Form
 *
 *  @since      1.0.0
 * @package    QiblaEvents\Form\Forms
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
 * Class BaseForm
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class BaseForm extends Form
{
    /**
     * Get Html
     *
     * @since  1.0.0
     *
     * @return string The form markup. Empty string if there are no fields to show
     */
    public function getHtml()
    {
        if (empty($this->fields)) {
            return '';
        }

        $output = sprintf(
            '<form action="%s" method="%s" name="%s"%s>',
            esc_url($this->getArg('action')),
            esc_attr($this->getArg('method')),
            sanitize_key($this->getArg('name')),
            $this->getAttrs()
        );

        foreach ($this->fields as $name => $field) {
            $output .= $field;
        }

        // Add hidden types.
        foreach ($this->hiddenTypes as $name => $type) {
            $output .= $type;
        }

        // Add nonce.
        $output .= $this->getNonce();

        $output .= '</form>';

        return $output;
    }
}
