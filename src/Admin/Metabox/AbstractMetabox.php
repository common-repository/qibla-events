<?php
namespace QiblaEvents\Admin\Metabox;

/**
 * Meta-box Register
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Metabox
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
 * Class AbstractMetabox
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractMetabox implements MetaboxInterface
{
    /**
     * Meta Box Arguments
     *
     * @since  1.0.0
     *
     * @var array A list of arguments to build the meta box
     */
    protected $metaBoxArgs;

    /**
     * Meta Box Classes
     *
     * @since  1.0.0
     *
     * @var array The postBox Classes
     */
    protected $postBoxClasses;

    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct($metaBoxArgs, array $postBoxClasses = array())
    {
        $metaBoxArgs = wp_parse_args($metaBoxArgs, array(
            'screen'        => get_post_types(array('public' => true)),
            'callback_args' => array(),
            'is_hidden'     => false,
            'is_closed'     => false,
        ));

        /**
         * Filter Meta-box Arguments
         *
         * @since 1.0.0
         *
         * @param array            $metaBoxArgs The arguments for the current meta-box.
         * @param MetaboxInterface $this        The current instance of the meta-box.
         */
        $metaBoxArgs = apply_filters('qibla_events_metabox_arguments', $metaBoxArgs, $this);

        $this->metaBoxArgs    = $metaBoxArgs;
        $this->postBoxClasses = $postBoxClasses;
    }

    /**
     * Get the ID
     *
     * Retrieve the metabox ID
     *
     * @since  1.0.0
     *
     * @return string The metabox ID
     */
    public function getID()
    {
        $args = $this->getArgs();

        return $args['id'];
    }

    /**
     * Get Arguments
     *
     * @since  1.0.0
     *
     * @return array The meta-box Arguments
     */
    public function getArgs()
    {
        return $this->metaBoxArgs;
    }

    /**
     * Post Box Classes
     *
     * Additional classes for the meta-box container.
     *
     * @since  1.0.0
     *
     * @param array $classes The default classes for this meta-box.
     *
     * @return array The filtered classes
     */
    public function postboxClasses($classes)
    {
        // Add the close class if the meta-box must be closed.
        $args = $this->getArgs();
        if (true === $args['is_closed']) {
            $this->postBoxClasses[] = 'closed';
        }

        if (! empty($this->postBoxClasses)) {
            $classes = array_merge($classes, $this->postBoxClasses);
        }

        return $classes;
    }
}
