<?php
/**
 * ModalTemplate
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace QiblaEvents\Modal;

use QiblaEvents\TemplateEngine\Engine;
use QiblaEvents\TemplateEngine\TemplateInterface;

/**
 * Class ModalTemplate
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Modal
 */
class ModalTemplate implements TemplateInterface
{
    /**
     * Callback
     *
     * The callback to call within the template for the modal content
     *
     * @since  1.0.0
     *
     * @var callable A callback to call within the template
     */
    protected $callback;

    /**
     * Arguments
     *
     * @since  1.0.0
     *
     * @var array A list of arguments for the modal
     */
    protected $args;

    /**
     * The template path
     *
     * @since  1.0.0
     *
     * @var string The template path
     */
    protected static $templatePath = '/views/modal.php';

    /**
     * ModalTemplate constructor
     *
     * @since 1.0.0
     *
     * @throws \InvalidArgumentException If the $callback parameter is not a valid callable function
     *
     * @param callable $callback The callback to call within the template that fill the modal.
     * @param array    $args     The arguments for the modal
     */
    public function __construct($callback, array $args = array())
    {
        // Not a callable function? Stop here.
        if (! is_callable($callback)) {
            throw new \InvalidArgumentException('Callback is not callable. in ' . __METHOD__);
        }

        // Parse the arguments.
        $this->args = wp_parse_args($args, array(
            'class_container'   => '',
            'context'           => 'html',
            'show_close_button' => true,
            'title'             => '',
            'subtitle'          => '',
        ));

        // Set the callback.
        $this->callback = $callback;
    }

    /**
     * Get Template data
     *
     * @since  1.0.0
     *
     * @return \stdClass The data object to pass to the template
     */
    public function getData()
    {
        $data           = new \stdClass();
        $data->callback = $this->callback;
        $data->args     = $this->args;

        return $data;
    }

    /**
     * Template
     *
     * @since  1.0.0
     *
     * @param \stdClass $data The data to send to the template.
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new Engine('modal', $data, static::$templatePath);
        $engine->render();

        if (wp_script_is('dl-modal', 'registered')) {
            wp_enqueue_script('dl-modal');
        }
    }
}
