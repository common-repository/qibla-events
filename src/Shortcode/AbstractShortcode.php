<?php
/**
 * Shortcode Register
 *
 * @since      1.0.0
 * @package    QiblaEvents\Shortcode
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

namespace QiblaEvents\Shortcode;

use QiblaEvents\TemplateEngine\Engine as TEngine;

/**
 * Class Short-code
 *
 * @since   1.0.0
 * @author  GuidoScialfa <alfio.piccione@gmail.com>
 */
abstract class AbstractShortcode implements ShortcodeInterface
{
    /**
     * Short-code Tag
     *
     * @since  1.0.0
     *
     * @var string The tag name of the short-code
     */
    protected $tag;

    /**
     * Defaults Attributes
     *
     * @since  1.0.0
     *
     * @var array The default attributes of the short-code
     */
    protected $defaults;

    /**
     * Get The tag name
     *
     * Retrieve the tag name for the short-code.
     *
     * @since  1.0.0
     *
     * @return string The name of the short-code
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Get Defaults
     *
     * Retrieve the defaults value for the short-code.
     *
     * @since  1.0.0
     *
     * @return array The default attributes for the short-code
     */
    public function getDefaults()
    {
        return (array)$this->defaults;
    }

    /**
     * Parse Attributes Arguments
     *
     * @since  1.0.0
     * @uses   shortcode_atts() To parse the attributes of the shortcode.
     *
     * @param array $atts The short-code's attributes
     *
     * @return array The parsed arguments
     */
    public function parseAttrsArgs($atts = array())
    {
        // Cast the $atts value and not declare the attribute as array to prevent to cast it every time the
        // parseAttrsArgs is called.
        // Since WordPress doesn't perform any cast or check about the $atts type before pass them to the callback f
        // may be that a short-code that require atts but is not receiving any attribute will generate an error.
        return shortcode_atts($this->getDefaults(), (array)$atts, $this->getTag());
    }

    /**
     * Load View
     *
     * @since  1.0.0
     *
     * @param string    $name         The name of the current template.
     * @param \stdClass $data         The data to use in view.
     * @param array     $templatePath The template to load.
     *
     * @return mixed Whatever the Engine::render().
     */
    protected function loadTemplate($name, $data, $templatePath)
    {
        ob_start();
        $engine = new TEngine($name, $data, $templatePath);
        $engine->render();
        $output = ob_get_clean();

        return $output;
    }
}
