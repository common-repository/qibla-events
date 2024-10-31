<?php
namespace QiblaEvents\Admin\Settings;

use QiblaEvents\Form\Forms\TableForm;
use QiblaEvents\Form\Types;
use QiblaEvents\Request\Nonce;

/**
 * Settings Form
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Settings
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
 * Class AbstractSettingsForm
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractSettingsForm extends TableForm
{
    /**
     * Construct
     *
     * @since 1.0.0
     *
     * @param string $name       The form name.
     * @param string $parentPage The parent page of the current one.
     */
    public function __construct($name, $parentPage = '')
    {
        $name = sanitize_key($name);

        // Build the action string.
        // We have page and sub-pages, so $parentPage come first.
        if ($parentPage) {
            $action = 'admin.php?page=' . esc_url(sanitize_text_field($parentPage)) .
                      '&subpage=' . esc_url(sanitize_text_field($name));
        } else {
            $action = 'admin.php?page=' . esc_url(sanitize_text_field($name));
        }

        parent::__construct(array(
            'name'        => $name,
            'action'      => $action,
            'method'      => 'post',
            'table_class' => 'dm-settings-table dm-settings-table--' . $name,
        ));

        // Add action input. Used to process the form before sent headers.
        parent::addHidden(new Types\Hidden(array(
            'name'  => 'qibla_action',
            'attrs' => array(
                'value' => 'qibla_process_settings',
            ),
        )));

        // Set the name of the class from with build the fields.
        $reflection = new \ReflectionClass(get_called_class());
        parent::addHidden(new Types\Hidden(array(
            'name'  => 'options_name',
            'attrs' => array('value' => $reflection->getShortName()),
        )));
    }

    /**
     * Get Form Nonce
     *
     * @since  1.0.0
     *
     * @return string The input type hidden for nonce
     */
    public function getNonce()
    {
        $nonce = new Nonce('_settings_form');

        return $nonce->field();
    }

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
            '<form action="%1$s" enctype="multipart/form-data" method="%2$s" name="%3$s"%4$s novalidate>',
            esc_url($this->getArg('action')),
            esc_attr($this->getArg('method')),
            sanitize_key($this->getArg('name')),
            $this->getAttrs()
        );

        $output .= '<table' . ($this->tableClass ? ' class="' . implode(' ', $this->tableClass) . '"' : '') . '>';
        $output .= '<tbody>';
        foreach ($this->fields as $name => $field) {
            $output .= $field;
        }
        $output .= '</tbody></table>';

        // Add hidden types.
        foreach ($this->hiddenTypes as $name => $type) {
            $output .= $type;
        }

        // Add nonce.
        $output .= $this->getNonce();

        $output .= '<p class="field-submit">
            <input type="submit" name="qibla_opt_submit" id="qibla_opt_submit" value="' .
                   esc_attr__('Save Options', 'qibla-events') .
                   '" /></p>';

        $output .= '</form>';

        return $output;
    }
}
