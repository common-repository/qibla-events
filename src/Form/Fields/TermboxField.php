<?php
namespace QiblaEvents\Form\Fields;

use QiblaEvents\Form\Interfaces\Types;
use QiblaEvents\Functions as F;
use QiblaEvents\Form\Abstracts\Field;

/**
 * Term Box Field
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
 * Class Term-box Field
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class TermboxField extends Field
{
    /**
     * Get Table Head
     *
     * It is a wrap for label and description field.
     *
     * @since  1.0.0
     *
     * @return string The table head content with label in it.
     */
    private function tableHeadMarkup()
    {
        return sprintf(
            '<th class="dl-field__label">%1$s%2$s</th>',
            $this->argCb('before_label'),
            $this->getLabel()
        );
    }

    /**
     * Get Table Data
     *
     * It is a wrapper for the input type.
     *
     * @since  1.0.0
     *
     * @return string The field content.
     */
    private function tableDataMarkup()
    {
        return sprintf(
            '<td class="dl-field__type">%1$s%2$s%3$s%4$s</td>',
            $this->argCb('before_input'),
            $this->getType(),
            $this->argCb('after_input'),
            $this->getDescription()
        );
    }

    /**
     * The Label and Field Markup
     *
     * @since 1.0.0
     *
     * @return string The label and field html markup
     */
    private function labelFieldMarkup()
    {
        return $this->argCb('before_label') .
               $this->getLabel() .
               $this->argCb('before_input') .
               $this->getType() .
               $this->argCb('after_input') .
               $this->getDescription();
    }

    /**
     * Field Container
     *
     * Since the field is used in create new term and edit term pages, we need to differentiate the container used for
     * the field.
     *
     * Within the edit term page the form put the fields within a table, but not within the create term page.
     *
     * @since 1.0.0
     *
     * @return string The `tr` if is edit term page, the set container otherwise.
     */
    private function container()
    {
        return F\isEditTerm() ? 'tr' : $this->getArg('container');
    }

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param Types $type The input type related to this field.
     * @param array $args The arguments to build the field.
     */
    public function __construct(Types $type, $args)
    {
        if (! isset($args['container_class'])) {
            $args['container_class'] = array();
        }

        $args['container_class'] = array_merge($args['container_class'], array(
            'form-field',
            'term-' . sanitize_key(sanitize_title($args['label'])) . '-wrap',
        ));

        parent::__construct($type, $args);
    }

    /**
     * @inheritdoc
     *
     * @since 1.0.0
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

        // Open container.
        $output = sprintf('<%s class="%s">', $this->container(), implode(' ', $containerClass));

        if (F\isEditTerm()) {
            // Retrieve the row table head.
            $output .= $this->tableHeadMarkup();
            // Build the input type.
            $output .= $this->tableDataMarkup();
        } else {
            $output .= $this->labelFieldMarkup();
        }

        // Close the container.
        $output .= sprintf('</%s>', $this->container());

        return $output;
    }
}
