<?php
namespace QiblaEvents\Form\Types;

/**
 * Form Wysiwyg Type
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
 * Class Text
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Wysiwyg extends Textarea
{
    /**
     * Pre Build Tasks
     *
     * @since  1.0.0
     *
     * @return void
     */
    protected function preEditorBuildTasks()
    {
        // Get the settings.
        $editorArgs = $this->getArg('editor_settings');

        // If the paste as text is set.
        // Teeny mce doesn't add it by default.
        if (isset($editorArgs['teeny']) && isset($editorArgs['paste_as_text'])) {
            add_action('teeny_mce_plugins', function (array $pluginsList) {
                $pluginsList[] = 'paste';

                return $pluginsList;
            });
        }
    }

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
            'type'            => 'wysiwig',
            'attrs'           => array(),
            'editor_settings' => array(),
        ));

        $args['attrs'] = array_merge($args['attrs'], array(
            'data-type' => 'wysiwyg',
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
        // Make pre Editor Tasks.
        $this->preEditorBuildTasks();

        ob_start();

        wp_editor(
            $this->escape(),
            esc_attr($this->getArg('id')),
            $this->getArg('editor_settings')
        );

        $output = ob_get_clean();

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_wysiwyg_output', $output, $this);

        return $output;
    }
}
