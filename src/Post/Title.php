<?php
/**
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    Qibla
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    GNU General Public License, version 2
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

namespace QiblaEvents\Post;

use QiblaEvents\Debug;
use QiblaEvents\Exceptions\InvalidPostException;
use QiblaEvents\Functions as F;
use QiblaEvents\TemplateEngine\Engine as TEngine;
use QiblaEvents\ListingsContext\Context;


/**
 * Class PostTitle
 *
 * @todo    Implement the TemplateInterface.
 * @todo    Rename to Title.
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package Qibla
 */
class Title
{
    /**
     * Instance args
     *
     * @since  1.0.0
     *
     * @var array The list of the arguments
     */
    protected $args;

    /**
     * PostTitle constructor
     *
     * @since 1.0.0
     *
     * @param array $args The arguments for this class
     */
    public function __construct(array $args = array())
    {
        $this->args = wp_parse_args($args, array(
            'screen_reader_text' => false,
        ));
    }

    /**
     * Get Post Title
     *
     * @since 1.0.0
     *
     * @uses  get_post()
     * @uses  get_the_title()
     * @uses  get_post_type()
     * @uses  \QiblaEvents\Functions\getListingIcon()
     *
     * @throws InvalidPostException If the post parameter cannot be retrieved.
     *
     * @param \WP_Post|int $post The post from which retrieve the data.
     *
     * @return \stdClass The data object.
     */
    public function getPostTitleData($post = null)
    {
        // Initialize Object.
        $data = new \stdClass();

        // Get the post.
        $post = get_post($post);

        if (! $post) {
            throw new InvalidPostException();
        }

        $isSingleListings = false;
        if (class_exists('QiblaEvents\\ListingsContext\\Context')) {
            $isSingleListings = Context::isSingleListings();
        }

        $data->ID       = $post->ID;
        $data->titleTag = $this->args['screen_reader_text'] || $isSingleListings ? 'h1' : 'h2';
        $data->title    = get_the_title($post);
        $data->postUrl  = '';

        return $data;
    }

    /**
     * The post title Template
     *
     * {@inheritdoc}
     *
     * @return void
     */
    public function postTitleTmpl()
    {
        try {
            // Retrieve the data.
            $data = call_user_func_array(array($this, 'getPostTitleData'), func_get_args());

            // Generally used within the singular post where there is the jumbo-tron and we want to
            // show the title on it but not lost the markup hierarchy nor the semantic of the page.
            if ($this->args['screen_reader_text'] &&
                ! has_filter('qibla_scope_attribute', array($this, 'screenReaderTitleScopeModifier'))
            ) {
                add_filter('qibla_scope_attribute', array($this, 'screenReaderTitleScopeModifier'), 0, 5);
            }

            // Create and render the template.
            $engine = new TEngine('the_post_title', $data, 'views/posts/title.php');
            $engine->render();
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            return;
        }//end try
    }

    /**
     * Scope Title Modifier Filter
     *
     * Filter the scope string before it is returned.
     *
     * @since 1.0.0
     *
     * @param string $upxscope The scope prefix. Default 'upx'.
     * @param string $element  The current element of the scope.
     * @param string $block    The custom block scope. Default empty.
     * @param string $scope    The default scope prefix. Default 'upx'.
     * @param string $attr     The attribute for which the value has been build.
     *
     * @return string The filtered $upxscope parameter
     */
    public function screenReaderTitleScopeModifier($upxscope, $element, $block, $scope, $attr)
    {
        // Only for article__title.
        if ('article' === $block && 'title' === $element && $this->args['screen_reader_text']) {
            // Don't use a modifier here for portability.
            $upxscope .= ' screen-reader-text';

            // Sometimes within a page there are additional loops that don't need the screen-reader-text class.
            // Since the filter is added within the postTitleTmpl method the same will be applied to
            // other articles.
            remove_filter('qibla_scope_attribute', array($this, 'screenReaderTitleScopeModifier'), 0, 5);
        }

        return $upxscope;
    }
}
