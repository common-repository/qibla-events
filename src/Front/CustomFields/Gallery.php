<?php
/**
 * Class Front-end Jumbo-tron
 *
 * @since      1.0.0
 * @package    QiblaEvents\Front\CustomFields
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

namespace QiblaEvents\Front\CustomFields;

use QiblaEvents\Functions as F;
use QiblaEvents\TemplateEngine\Engine;
use QiblaEvents\ListingsContext\Context;

/**
 * Class Jumbotron
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Gallery extends AbstractMeta
{
    /**
     * Get Background Image
     *
     * @since  1.0.0
     *
     * @return array The background image data
     */
    private function getBackgroundImage()
    {
        static $data;

        if ($data) {
            return $data;
        }

        $backgroundColor = $this->getMeta('bg_color');
        $backgroundId    = $this->getMeta('bg_image');
        $backgroundSrc   = wp_get_attachment_image_src($backgroundId, 'qibla-events-gallery');

        if (! $backgroundSrc && Context::isSingleListings() && has_post_thumbnail(get_the_ID())) {
            $backgroundSrc    = array();
            $backgroundColor  = '#fff';
            $backgroundId     = get_the_ID();
            $backgroundSrc[0] = get_the_post_thumbnail_url(get_the_ID(), 'qibla-events-gallery');
        }

        $data = array(
            'background_color' => $backgroundColor,
            'background_id'    => $backgroundId,
            'background_size'  => 'qibla-events-gallery',
            'background_src'   => $backgroundSrc,
        );

        /**
         * Filter background Data
         *
         * @since 1.0.0
         *
         * @param array     $data            The data to filter.
         * @param string    $backgroundColor The color of the background.
         * @param int       $backgroundId    The id of the background image.
         * @param string    $backgroundSrc   The background image src.
         * @param Jumbotron $this            The instance of the Jumbotron class.
         */
        $data = apply_filters(
            'qibla_events_fw_jumbotron_background',
            $data,
            $backgroundColor,
            $backgroundId,
            $backgroundSrc,
            $this
        );

        return $data;
    }


    /**
     * Get Attribute Class
     *
     * @since 1.0.0
     *
     * @param \stdClass $data The jumbotron's data for template.
     *
     * @return array The class for the markup
     */
    protected function getAttributeClass(\stdClass $data)
    {
        // Get the background.
        $background = $this->getBackgroundImage();

        $class = array(
            'dljumbotron',
            $data->hasGallery ? 'dljumbotron--has-gallery' : '',
        );

        // Has image background.
        if (! empty($background['background_src'][0])) {
            $class[] = 'dljumbotron--has-background-image';
        }

        $class = array_unique($class);

        return $class;
    }

    /**
     * Jumbotron constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize Object
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        // Build the meta-keys array.
        $this->meta = array(
            'bg_image' => "_qibla_{$this->mbKey}_jumbotron_background_image",
            'bg_color' => "_qibla_{$this->mbKey}_jumbotron_background_color",
            'disabled' => "_qibla_{$this->mbKey}_jumbotron_disable",
        );
    }

    /**
     * Jumbo-tron
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function template()
    {
        $this->init();

        // Initialize Data Object.
        $data = new \stdClass();

        // Has gallery is false by default.
        // Right now the gallery is only available within the single post type listings.
        $data->hasGallery = false;
        $data->dataLabel  = 'no';

        if (Context::isSingleListings()) {
            // A bool to know if the current object has a gallery.
            $data->hasGallery = (bool)get_post_meta($this->id, '_qibla_mb_images', true);
            // Set the gallery label for data attribute.
            $data->dataLabel = esc_html__('See gallery', 'qibla-events') . ':';
            $data->dataLabel .= implode(',', array('la', 'la-camera-retro'));
            // Set the action to able to show the gallery data within the page.
            add_action('wp_footer', array($this, 'galleryTmpl'));
        }

        // Get the classes for the markup.
        $data->class = $this->getAttributeClass($data);

        // Load the template.
        $this->loadTemplate("{$this->mbKey}_jumbotron", $data, '/views/customFields/jumbotron.php');
    }

    /**
     * Custom Css
     *
     * @since  1.0.0
     * @return void
     */
    public function customCss()
    {
        // Initialize instance.
        $this->init();

        // Initialize Data.
        $data = array();

        // Get the background image data.
        $background = $this->getBackgroundImage();

        // Set the background color.
        if ($background['background_color']) {
            $data['style'] = array(
                'background-color' => $background['background_color'],
            );
        }

        // Background Style.
        if (! empty($background['background_src'][0])) {
            $data['style'] = array(
                'background'          => $background['background_color'] . ' url(' . esc_url($background['background_src'][0]) . ') no-repeat center center',
                'background-size'     => 'cover',
                'background-position' => 'center',
                'background-repeat'   => 'no-repeat',
            );
        }

        /**
         * Filter Jumbotron Style Data
         *
         * Filter data before build the style string.
         *
         * @since 1.0.0
         *
         * @param array        $data The data to filter.
         * @param AbstractMeta $this The instance of the Jumbotron class.
         */
        $data = apply_filters('qibla_events_fw_jumbotron_data_style', $data, $this);

        // Build the style.
        $style = '';
        if (! empty($data['style']) && is_array($data['style'])) {
            $style = F\implodeAssoc(';', ':', $data['style']);
        }

        if ($style) {
            // @todo Need cssTidy
            echo '<style type="text/css">.dljumbotron{' . $style . '}</style>';
        }
    }

    /**
     * Gallery
     *
     * Include the gallery list data object.
     * The data is consumed by the PhotoSwipe script.
     *
     * @since  1.0.0
     *
     * @return array The list of the image data.
     */
    public function getGallery()
    {
        // Initialize the data object.
        $list = array();

        /**
         * Add gallery if exists within the single post.
         *
         * - Filter ids for add extra ids to gallery
         *
         * @since 1.0.0
         */
        $ids = apply_filters(
            'qibla_mb_images_gallery_ids',
            get_post_meta($this->id, '_qibla_mb_images', true)
        );

        if ($ids) :
            $ids = explode(',', $ids);
            foreach ($ids as $id) {
                $id  = intval($id);
                $img = wp_get_attachment_image_src($id, 'full');

                if (! $img) {
                    continue;
                }

                $post = get_post($id);
                /**
                 * Get Filtered post object.
                 *
                 * - Use for modify image title.
                 *
                 * @since 1.0.0
                 */
                $postFiltered  = apply_filters('qibla_mb_images_gallery_post_data', $id);
                $titleFiltered = is_object($postFiltered) ? $postFiltered->post_title : '';
                $title         = $post instanceof \WP_Post ? sanitize_text_field($post->post_title) : '';

                // Set the data for the element.
                // See the http://photoswipe.com/documentation/getting-started.html for more info.
                $list[] = array(
                    'src'   => $img[0],
                    'w'     => intval($img[1]),
                    'h'     => intval($img[2]),
                    'title' => isset($title) && ! is_object($postFiltered) ? $title : $titleFiltered,
                );
            }
        endif;

        return $list;
    }

    /**
     * Gallery Template
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function galleryTmpl()
    {
        if (! Context::isSingleListings() ||
            ! wp_script_is('dlphotoswipe', 'registered') ||
            ! wp_style_is('photoswipe-skin', 'registered')
        ) {
            return;
        }

        $list = $this->getGallery();

        // Append the list as json object.
        if (empty($list)) {
            return;
        }

        wp_enqueue_style('photoswipe-skin');
        wp_enqueue_script('dlphotoswipe');

        printf(
            '<script type="text/javascript" id="dlgallery">%s</script>',
            wp_json_encode($list)
        );

        // Render the photo swipe template.
        $engine = new Engine('jumbotron_photoswipe_view', new \stdClass(), '/views/photoswipe.php');
        $engine->render();
    }
}
