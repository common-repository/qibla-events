<?php
/**
 * Term
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package   Taxonomy
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

namespace QiblaEvents\Taxonomy;

use QiblaEvents\Functions as F;
use QiblaEvents\IconsSet\Icon;

/**
 * Class Term
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Term
{
    /**
     * Thumbnail meta key
     *
     * @since 1.0.0
     *
     * @var string The thumbnail meta key
     */
    const THUMBNAIL_META_KEY = '_qibla_tb_thumbnail';

    /**
     * Icon Meta key
     *
     * @since 1.0.0
     *
     * @var string The term icon meta key
     */
    const ICON_META_KEY = '_qibla_tb_icon';

    /**
     * Default Icon
     *
     * The default compact version of the icon if term has no one.
     *
     * @since 1.0.0
     *
     * @var string The default compact version of the icon if term has no one.
     */
    private static $defaultIcon = 'Lineawesome::la-check';

    /**
     * Term
     *
     * The term object wrapped into the instance
     *
     * @var \WP_Term
     */
    private $term;

    /**
     * Term constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Term $term The term to use internally.
     */
    public function __construct(\WP_Term $term)
    {
        $this->term = $term;
    }

    /**
     * Thumbnail
     *
     * @since 1.0.0
     *
     * @return int The ID of the thumbnail related to the term.
     */
    public function thumbnail()
    {
        $meta = F\getTermMeta(self::THUMBNAIL_META_KEY, $this->term, '');

        return intval($meta);
    }

    /**
     * Term Icon
     *
     * @since 1.0.0
     *
     * @return Icon The instance of the icon associated to the term
     */
    public function icon()
    {
        // Default value is used on Icon creation.
        // Meta always return a compact version of the icon.
        $meta = (string)F\getTermMeta(self::ICON_META_KEY, $this->term, '');

        return new Icon($meta, self::$defaultIcon);
    }

    /**
     * Description
     *
     * @since 1.0.0
     *
     * @return string The description of the term
     */
    public function description()
    {
        return F\ksesPost(apply_filters('the_content', $this->term->description));
    }

    /**
     * Name
     *
     * @since 1.0.0
     *
     * @return string The name of the term.
     */
    public function name()
    {
        return sanitize_text_field($this->term->name);
    }

    /**
     * Slug
     *
     * @since 1.0.0
     *
     * @return string The slug of the term
     */
    public function slug()
    {
        return sanitize_title($this->term->slug);
    }
}
