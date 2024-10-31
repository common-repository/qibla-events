<?php
/**
 * Button
 *
 * @since      1.0.0
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

namespace QiblaEvents\Front\CustomFields;

use QiblaEvents\TemplateEngine\TemplateInterface;
use QiblaEvents\Functions as F;
/**
 * Class Button
 *
 * @since  1.0.0
 * @author alfiopiccione <alfio.piccione@gmail.com>
 */
class Button extends AbstractMeta implements TemplateInterface
{
    /**
     * Initialize Object
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function init()
    {
        if (is_singular('events')) {
            parent::init();
        }

        // Build the meta-keys array.
        $this->meta = array(
            'button_url'    => "_qibla_{$this->mbKey}_button_url",
            'button_text'   => "_qibla_{$this->mbKey}_button_text",
            'button_target' => "_qibla_{$this->mbKey}_target_link",
        );
    }

    /**
     * Get Data
     *
     * @inheritDoc
     */
    public function getData()
    {
        // Initialize data class.
        $data = new \stdClass();

        // Do nothing if the context is not a singular post type.
        if (! is_singular('events') && 'events' !== get_post_type()) {
            return $data;
        }

        // Retrieve the meta.
        $data->buttonMeta = is_singular('events') ? array(
            'url'    => $this->getMeta('button_url', ''),
            'text'   => $this->getMeta('button_text', ''),
            'target' => $this->getMeta('button_target', ''),
        ) : array(
            'url'    => F\getPostMeta('_qibla_mb_button_url'),
            'text'   => F\getPostMeta('_qibla_mb_button_text'),
            'target' => F\getPostMeta('_qibla_mb_target_link'),
        );

        // Button classes.
        $data->buttonMeta['btn_class'] = 'dlbtn dlbtn--tiny';
        if (is_singular('events')) {
            $data->buttonMeta['btn_class'] = 'dlbtn dlbtn--wide';
        }

        return $data;
    }

    /**
     * Template
     *
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        $this->loadTemplate('qibla_mb_events_button', $data, '/views/events/buttonEvents.php');
    }

    /**
     * Related Posts Filter
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function buttonFilter()
    {
        $instance = new self;

        $instance->init();
        $instance->tmpl($instance->getData());
    }
}
