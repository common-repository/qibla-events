<?php
/**
 * Sidebar Additional Info
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
use QiblaEvents\TemplateEngine\Engine as TEngine;

/**
 * Class ListingsSocialsUrls
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class ListingsSocials extends AbstractMeta
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
        parent::init();

        // Build the meta-keys array.
        $this->meta = array(
            'facebook'    => "_qibla_{$this->mbKey}_social_facebook",
            'twitter'     => "_qibla_{$this->mbKey}_social_twitter",
            'instagram'   => "_qibla_{$this->mbKey}_social_instagram",
            'linkedin'    => "_qibla_{$this->mbKey}_social_linkedin",
            'tripadvisor' => "_qibla_{$this->mbKey}_social_tripadvisor",
            'email'       => "_qibla_{$this->mbKey}_business_email",
        );
    }

    /**
     * getData
     *
     * @since  1.0.0
     *
     * @return \stdClass $data The data to consume within the template
     */
    public function getData()
    {
        // Initialize object.
        $this->init();

        // Initialize Data object.
        $data = new \stdClass();

        // Build the socials links data list.
        $data->linksList = array();
        foreach (array_keys($this->meta) as $key) {
            $url = $this->getMeta($key);
            if ($url) {
                $data->linksList[$key] = (object)array(
                    'href'  => is_email($url) ? 'mailto:' . $url : $url,
                    'class' => F\getScopeClass('socials-links__link', '', $key),
                    'label' => str_replace('_', ' ', $key),
                );
            }
        }

        // To show the title for the section.
        $data->showTitle = true;

        return $data;
    }

    /**
     * Template
     *
     * @since  1.0.0
     *
     * @param \stdClass $data
     */
    public function tmpl(\stdClass $data)
    {
        $engine = new TEngine('settings_socials_links', $data, '/views/socialsLinks.php');
        $engine->render();

        // Load the single listing form only if the current listing have a email post meta set.
        if (isset($data->linksList['email']) &&
            wp_script_is('dllistings-contact-form', 'registered')
        ) {
            // Enqueue the script related to the listing email author form.
            wp_enqueue_script('dllistings-contact-form');
        }
    }

    /**
     * Social Links Filter
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function socialLinksFilter()
    {
        $instance = new static;
        $instance->tmpl($instance->getData());
    }
}
