<?php
namespace QiblaEvents\Form\Types;

use QiblaEvents\Form\Abstracts\Type;

/**
 * Form PlaceholderPremium Type
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Types
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2016, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2016 Guido Scialfa <dev@guidoscialfa.com>
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

/**
 * Class PlaceholderPremium
 *
 * This type is used in free version of the framework only.
 * It does do nothing. Just add a placeholder within the form for those input that are premium only.
 *
 * @since   1.0.0
 * @author  Guido Scialfa <dev@guidoscialfa.com>
 */
final class PlaceholderPremium extends Type
{
    /**
     * Constructor
     *
     * @since  1.0.0
     * @access public
     *
     * @param array $args The arguments for this type.
     */
    public function __construct($args)
    {
        $args = wp_parse_args($args, array(
            'type'      => 'placeholder_premium',
            'image_url' => '',
        ));

        parent::__construct($args);
    }

    /**
     * Get Html
     *
     * @since  1.0.0
     * @access public
     *
     * @return string The html version of this type
     */
    public function getHtml()
    {
        return sprintf(
            '<img src="%s" class="dlfw-placeholder-type" alt="" />',
            esc_url($this->args['image_url'])
        );
    }
}
