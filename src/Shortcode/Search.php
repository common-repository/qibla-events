<?php
/**
 * Short-code Search
 *
 * @since      1.0.0
 * @package    QiblaEvents\Shortcode
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
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

namespace QiblaEvents\Shortcode;

use QiblaEvents\Form\Factories\FieldFactory;
use QiblaEvents\ListingsContext\Types;
use QiblaEvents\Plugin;
use QiblaEvents\Search as ListingsSearch;
use QiblaEvents\Search\Field\Geocoded as FieldGeocoded;
use QiblaEvents\Search\Field\Search as FieldSearch;

/**
 * Class Search
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Search extends AbstractShortcode
{
    /**
     * Construct
     *
     * 'nav' => [
     *     'static',
     *     'autocomplete'
     * ]
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->tag      = 'dl_search';
        $this->defaults = array(
            'id'          => 'search _form',
            'search_type' => 'geocoded',
        );
    }

    /**
     * Build Data
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code attributes.
     * @param string $content The content within the short-code.
     *
     * @return \stdClass The data instance or null otherwise.
     */
    public function buildData(array $atts, $content = '')
    {
        $types   = new Types();
        $factory = new ListingsSearch\SearchFactory(
            $atts['search_type'],
            $types->types(),
            $atts['id'],
            'post'
        );

        return (object)array(
            'form' => $factory->create(),
        );
    }

    /**
     * Callback
     *
     * @since  1.0.0
     *
     * @param array  $atts    The short-code's attributes.
     * @param string $content The content within the short-code.
     *
     * @return string The short-code markup
     */
    public function callback($atts = array(), $content = '')
    {
        // Parse the attributes.
        $atts = $this->parseAttrsArgs($atts);
        // Build Data.
        $data = $this->buildData($atts, $content);

        ob_start();
        $data->form->tmpl($data->form->getData());

        return ob_get_clean();
    }
}
