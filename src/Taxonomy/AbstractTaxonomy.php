<?php
/**
 * Abstract Taxonomy
 *
 * @since      1.0.0
 * @package    QiblaEvents\Taxonomy
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

namespace QiblaEvents\Taxonomy;

use QiblaEvents\Admin\PermalinkSettings;
use QiblaEvents\Functions as F;

/**
 * Class AbstractTaxonomy
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractTaxonomy
{
    /**
     * Name
     *
     * @since  1.0.0
     *
     * @var string The name of the current taxonomy
     */
    protected $name;

    /**
     * Post Type
     *
     * @since  1.0.0
     *
     * @var string The post type associated with this taxonomy
     */
    private $postType;

    /**
     * Arguments
     *
     * @since  1.0.0
     *
     * @var array A list of taxonomy arguments
     */
    protected $args;

    /**
     * Default Rewrite Rule
     *
     * @since  1.0.0
     *
     * @var array The default rewrite arguments.
     */
    protected static $defaultRewriteRule;

    /**
     * Build the Rewrite Rule arguments
     *
     * @since  1.0.0
     *
     * @return array|null The arguments or null to use the default rewrite options
     */
    protected function buildRewriteRuleArg()
    {
        $key             = sanitize_key($this->name);
        $permalinkOption = F\getOption(PermalinkSettings::OPTION_NAME);
        $rewrite         = null;

        if (! empty($permalinkOption["permalink_{$key}_tax"])) {
            $rewrite = array(
                'slug' => $permalinkOption["permalink_{$key}_tax"],
            );
            // Some taxonomies may want to define a different slug.
        } elseif (! empty(static::$defaultRewriteRule)) {
            $rewrite = static::$defaultRewriteRule;
        }

        return $rewrite;
    }

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param string       $name     The name of this taxonomy.
     * @param string|array $postType The post type associated with the this taxonomy.
     * @param string       $singular The singular name of the taxonomy.
     * @param string       $plural   The plural name of this taxonomy.
     * @param array        $args     The arguments to build the taxonomy.
     */
    protected function __construct($name, $postType, $singular, $plural, array $args = array())
    {
        $this->name     = $name;
        $this->postType = $postType;
        $this->args     = wp_parse_args($args, array(
            'label'   => $plural,
            'labels'  => array(
                'name'                      => $plural,
                'singular_name'             => $singular,
                'menu_name'                 => $plural,
                'all_items'                 => sprintf(esc_html__('All %s', 'qibla-events'), $plural),
                'edit_item'                 => sprintf(esc_html__('Edit %s', 'qibla-events'), $singular),
                'view_item'                 => sprintf(esc_html__('View %s', 'qibla-events'), $singular),
                'update_item'               => sprintf(esc_html__('Update %s', 'qibla-events'), $singular),
                'add_new_item'              => sprintf(esc_html__('Add New %s', 'qibla-events'), $singular),
                'new_item_name'             => sprintf(esc_html__('New %s', 'qibla-events'), $singular),
                'parent_item'               => sprintf(esc_html__('Parent %s', 'qibla-events'), $singular),
                'parent_item_column'        => sprintf(esc_html__('Parent %s:', 'qibla-events'), $singular),
                'search_items'              => sprintf(esc_html__('Search %s', 'qibla-events'), $plural),
                'popular_items'             => sprintf(esc_html__('Popular %s', 'qibla-events'), $plural),
                'separate_items_with_comma' => sprintf(
                    esc_html__('Separate %s with comma', 'qibla-events'),
                    $plural
                ),
                'add_or_remove_items'       => sprintf(esc_html__('Add or remove %s', 'qibla-events'), $plural),
                'choose_from_most_used'     => sprintf(
                    esc_html__('Choose from most used %s', 'qibla-events'),
                    $plural
                ),
                'not_found'                 => sprintf(esc_html__('No %s found.', 'qibla-events'), $plural),
            ),
            'public'  => true,
            'rewrite' => $this->buildRewriteRuleArg(),
        ));
    }

    /**
     * Get Args
     *
     * @since  1.0.0
     *
     * @return array The arguments of the current taxonomy
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Get Name
     *
     * @since  1.0.0
     *
     * @return string The name of the current taxonomy
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Post Type
     *
     * @since  1.0.0
     *
     * @return string|array The post type/s related with the taxonomy
     */
    public function getPostType()
    {
        return $this->postType;
    }
}
