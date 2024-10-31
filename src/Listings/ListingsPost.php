<?php
/**
 * Listings Post
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

namespace QiblaEvents\Listings;

use QiblaEvents\Functions as F;
use QiblaEvents\IconsSet\Icon;

/**
 * Class Listing
 *
 * @see    https://core.trac.wordpress.org/ticket/24672 for more information.
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingsPost implements ListingsPostInterface, LocalizableInterface, ThumbnailLiableInstance, IconizeInterface, ListingTermInterface, SubTitleInterface, ListingsExtraInterface
{
    /**
     * WP Post
     *
     * @since 1.0.0
     *
     * @var \WP_Post The post to wrap
     */
    private $post;

    /**
     * ListingsPost constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post The post to wrap
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * @inheritdoc
     */
    public function extra()
    {
        $data = apply_filters('qibla_extra_data_in_listings_post', new \stdClass(), $this->post);

        if ($data) {
            return $data;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function subtitle()
    {
        return html_entity_decode(F\getPostMeta('_qibla_mb_sub_title', '', $this->ID(), true));
    }

    /**
     * @inheritdoc
     */
    public function location()
    {
        $location = new ListingLocation($this->post);

        return $location->location();
    }

    /**
     * @inheritdoc
     */
    public function icon($default = 'Lineawesome::la-check')
    {
        $iconCompact = $default;
        // Retrieve the first term of event_categories taxonomy.
        // Icons for listings are grub down by the first term.
        $terms = get_the_terms($this->ID(), 'event_categories');

        $termIcon = 'none';
        if (! is_wp_error($terms) && $terms) {
            // Get the Icon Slug from term meta.
            $termIcon = F\getTermMeta('_qibla_tb_icon', $terms[0]->term_id, 'none');
        }

        if ('none' !== $termIcon && '' !== $termIcon) {
            $iconCompact = $termIcon;
        }

        // Retrieve the Icon instance.
        $icon = new Icon($iconCompact);

        return $icon->getArrayVersion();
    }

    /**
     * @inheritdoc
     */
    public function color()
    {
        // Retrieve the first term of event_categories taxonomy.
        $terms = get_the_terms($this->ID(), 'event_categories');

        $termColor = '#f26522';

        if (! is_wp_error($terms) && $terms) {
            $term      = wp_list_pluck($terms, 'term_id');
            // Get the Term Color from term meta.
            $termColor = F\getTermMeta('_qibla_tb_terms_color', $term[0], sanitize_hex_color($termColor));

            // Default color.
            if (! $termColor) {
                $termColor = '#f26522';
            }
        }

        return sanitize_hex_color($termColor);
    }

    /**
     * @inheritdoc
     */
    public function ID()
    {
        return intval($this->post->ID);
    }

    /**
     * @inheritdoc
     */
    public function title()
    {
        return html_entity_decode(sanitize_text_field($this->post->post_title));
    }

    /**
     * @inheritdoc
     */
    public function slug()
    {
        return sanitize_title(sanitize_text_field($this->post->post_name));
    }

    /**
     * @inheritdoc
     */
    public function permalink()
    {
        return esc_url(get_permalink($this->post));
    }

    /**
     * @inheritdoc
     */
    public function thumbnail()
    {
        // Post thumbnail.
        $thumbnail = new \stdClass();
        // If the current post has not thumbnail, switch to the jumbo-tron.
        $thumbnail->ID = intval(F\getPostMeta(
            '_thumbnail_id',
            intval(F\getPostMeta('_qibla_mb_jumbotron_background_image', null, $this->ID())),
            $this->ID()
        ));

        if ($thumbnail->ID) {
            $thumbnail->image = array(
                'small'     => esc_url(wp_get_attachment_image_url($thumbnail->ID, 'thumbnail')),
                'thumbnail' => esc_url(wp_get_attachment_image_url(
                    $thumbnail->ID,
                    'qibla-post-thumbnail-loop'
                )),
                'full'      => esc_url(wp_get_attachment_image_url($thumbnail->ID, 'full')),
            );
        } else {
            // We use this data with underscore templates and we must be sure no errors will
            // generate during the replace data.
            $thumbnail->ID    = 0;
            $thumbnail->image = array(
                'small'     => '',
                'thumbnail' => '',
                'full'      => '',
            );
        }

        return $thumbnail;
    }

    /**
     * @inheritdoc
     */
    public function type()
    {
        return sanitize_key($this->post->post_type);
    }

    /**
     * @inheritdoc
     */
    public function status()
    {
        return sanitize_key($this->post->post_status);
    }

    /**
     * @inheritdoc
     */
    public function featured()
    {
        return F\stringToBool(F\getPostMeta('_qibla_mb_is_featured', false, $this->post));
    }
}
