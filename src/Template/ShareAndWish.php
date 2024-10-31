<?php
/**
 * ShareAndWish
 *
 * @since      1.0.0
 * @package    QiblaEvents\Template
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

use QiblaEvents\TemplateEngine\Engine;

/**
 * Class ShareAndWish
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class ShareAndWish implements TemplateInterface
{
    /**
     * @since 1.0.0
     *
     * @var \WP_Post
     */
    private $post;

    /**
     * ShareAndWish constructor.
     *
     * @param \WP_Post $post The post object
     */
    public function __construct(\WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        // Data for view.
        $data = new \stdClass();

        // Has Sassy Social Share?
        $dataShare = false;
        if (class_exists('Sassy_Social_Share')) {
            $dataShare = true;
        }

        $data->shareAndWish = array(
            'has_share'   => $dataShare,
            'share_label' => esc_html__('Share', 'qibla-events'),
        );

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new Engine('qibla_share_and_wishlist', $data, '/views/share/shareAndWish.php');
        $engine->render();
    }

    /**
     * @inheritdoc
     */
    public static function template($object = null)
    {
        $instance = new self(get_post());
        $instance->tmpl($instance->getData());
    }
}
