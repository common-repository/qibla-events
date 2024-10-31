<?php
/**
 * Thumbnail
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
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

namespace QiblaEvents\Template;

use QiblaEvents\Functions as F;
use QiblaEvents\TemplateEngine\Engine;

/**
 * Class Thumbnail
 *
 * @since   1.0.0
 * @package QiblaEvents\Template
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Thumbnail implements TemplateInterface
{
    /**
     * Post
     *
     * @since 1.0.0
     *
     * @var \WP_Post The post for which show the thumbnail
     */
    private $post;

    /**
     * Arguments
     *
     * @since 1.0.0
     *
     * @var array The list of the arguments
     */
    private $args;

    /**
     * Thumbnail constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post The post for which show the thumbnail.
     * @param array    $args The arguments for the thumbnail.
     */
    public function __construct(\WP_Post $post, array $args = array())
    {
        $this->post = $post;
        $this->args = wp_parse_args($args, array(
            'size' => 'qibla-post-thumbnail-loop',
        ));
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return (object)array(
            'image' => F\getPostThumbnailAndFallbackToJumbotronImage($this->post, $this->args['size']),
        );
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        if ($data->image) {
            $engine = new Engine('thumbnail', $data, '/views/thumbnail.php');
            $engine->render();
        }
    }

    /**
     * @inheritDoc
     */
    public static function template($object = null)
    {
        $instance = new self(get_post());
        $instance->tmpl($instance->getData());
    }
}
