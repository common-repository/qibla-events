<?php
/**
 * Class Front-end Term Amenities
 *
 * @since      1.0.0
 * @package    QiblaEvents\Front\Settings
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione
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

use QiblaEvents\Debug;
use QiblaEvents\Functions as F;
use QiblaEvents\IconsSet;
use QiblaEvents\TemplateEngine as T;

/**
 * Class Amenities
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
final class AmenitiesTemplate implements T\TemplateInterface
{
    /**
     * WP Post
     *
     * @since 1.0.0
     *
     * @var \WP_Post The post instance from which retrieve the tags terms
     */
    private $post;

    /**
     * Get Terms Icon & Label
     *
     * @param array $terms An array of \WP_Term objects.
     *
     * @return array The array containing icon an label's terms
     */
    private function getTermsIconLabel(array $terms)
    {
        $list = array();

        foreach ($terms as $term) {
            // Retrieve the icon.
            $icon = F\getTermMeta('_qibla_tb_icon', $term);

            try {
                $icon          = new IconsSet\Icon($icon, 'Lineawesome::la-check');
                $iconHtmlClass = $icon->getHtmlClass();
            } catch (\Exception $e) {
                $debugInstance = new Debug\Exception($e);
                'dev' === QB_ENV && $debugInstance->display();

                $iconHtmlClass = '';
            }

            $list[$term->slug] = array(
                'icon_html_class' => $iconHtmlClass,
                'label'           => $term->name,
                'href'            => get_term_link($term, 'tags'),
            );
        }

        return $list;
    }

    /**
     * AmenitiesTemplate constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws \Exception If terms cannot be retrieved
     */
    public function getData()
    {
        // Initialize data object.
        $data = new \stdClass();

        // Set the ID.
        $data->ID = $this->post->ID;
        // Section Title.
        $data->title = esc_html__('Amenities', 'qibla-events');
        // Retrieve the terms.
        $data->terms = get_the_terms($this->post->ID, 'tags');

        // No terms?
        if (! $data->terms) {
            return $data;
        }

        // Got a WP_Error? Make it as Exception.
        if (is_wp_error($data->terms)) {
            throw new \Exception(sprintf(
                '%1$s Cannot retrieve terms for the post with ID %2$d.',
                __METHOD__,
                $data->ID
            ));
        }

        $data->list = $this->getTermsIconLabel($data->terms);

        return $data;
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function tmpl(\stdClass $data)
    {
        if ($data->terms) {
            $engine = new T\Engine('single_listings_tags', $data, 'views/terms/tags.php');
            $engine->render();
        }
    }

    /**
     * Amenities Section Filter
     *
     * This method use the `get_post()` function to retrieve the current post.
     * So, use it only within a loop or where the post you want to use is globally set.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function tagsSectionFilter()
    {
        $instance = new static(get_post());
        $instance->tmpl($instance->getData());
    }
}
