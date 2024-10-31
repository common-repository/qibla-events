<?php
/**
 * SubTitle
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
 * Class SubTitle
 *
 * @since   1.0.0
 * @package QiblaEvents\Template
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Subtitle implements TemplateInterface
{
    /**
     * Obj
     *
     * @since 1.0.0
     *
     * @var mixed May be the \WP_Post instance or the \WP_Term.
     */
    private $obj;

    /**
     * Subtitle constructor
     *
     * @since 1.0.0
     *
     * @throw \InvalidArgumentException In case the object isn't a \WP_Post, \WP_Term or a WP_Post_Type instance
     */
    public function __construct($obj)
    {
        if (! $obj instanceof \WP_Post && ! $obj instanceof \WP_Term && ! $obj instanceof \WP_Post_Type) {
            throw new \InvalidArgumentException('Invalid object type for subtitle.');
        }

        $this->obj = $obj;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return (object)array(
            // Post ID.
            'obj'      => $this->obj,
            // Sub Title.
            'subtitle' => $this->subtitle(),
        );
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        if ($data->subtitle) {
            $engine = new Engine('qibla_events_subtitle_template', $data, '/views/subtitle.php');
            $engine->render();
        }
    }

    /**
     * @inheritdoc
     */
    public static function template($object = null)
    {
        $instance = new self($object);

        $instance->tmpl($instance->getData());
    }

    /**
     * Subtitle
     *
     * @since 1.0.0
     *
     * @return string|null The term meta subtitle or null if the obj isn't a term or a post instance.
     */
    private function subtitle()
    {
        if ($this->obj instanceof \WP_Term) {
            return F\getTermMeta('_qibla_tb_sub_title', $this->obj, '');
        }

        if ($this->obj instanceof \WP_Post) {
            return F\getPostMeta('_qibla_mb_sub_title', '', $this->obj);
        }

        if ($this->obj instanceof \WP_Post_Type) {
            if (F\isWooCommerceActive()) {
                return F\getPostMeta('_qibla_mb_sub_title', '', intval(get_option('woocommerce_shop_page_id')));
            }
        }
    }
}
