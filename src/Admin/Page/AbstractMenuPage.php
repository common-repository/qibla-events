<?php
namespace QiblaEvents\Admin\Page;

/**
 * Menu Page
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Page
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

/**
 * Class Abstract MenuPage
 *
 * @since   1.0.0
 * @package QiblaEvents\Admin\Page
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractMenuPage
{
    /**
     * Page Title
     *
     * @since  1.0.0
     *
     * @var string The page title
     */
    protected $pageTitle;

    /**
     * Menu Title
     *
     * @since  1.0.0
     *
     * @var string The menu Title
     */
    protected $menuTitle;

    /**
     * Menu Slug
     *
     * @since  1.0.0
     *
     * @var string The menu slug
     */
    protected $menuSlug;

    /**
     * Callback
     *
     * @since  1.0.0
     *
     * @var string|array The callback to fill the content of the page
     */
    protected $callback;

    /**
     * Icon Url
     *
     * @since  1.0.0
     *
     * @var string The icon url or the slug of a dashicon
     */
    protected $iconUrl;

    /**
     * Position
     *
     * @since  1.0.0
     *
     * @var int The position of the page within the admin menu
     */
    protected $position;

    /**
     * Sub Pages
     *
     * @since  1.0.0
     *
     * @var array Arguments for the sub-pages
     */
    protected $subPages;

    /**
     * If the menu page has sub-pages
     *
     * @since  1.0.0
     *
     * @var bool True if the menu page has sub-pages. False otherwise
     */
    protected $hasSubPages;

    /**
     * Parent Item
     *
     * @since  1.0.0
     *
     * @var string The parent slug or url fragment
     */
    protected $parent;

    /**
     * Construct
     *
     * @since  1.0.0
     */
    protected function __construct(
        $pageTitle,
        $menuTitle,
        $menuSlug = '',
        $iconUrl = 'dashicons-admin-post',
        $capability = 'manage_options',
        $callback = null,
        $position = 2,
        array $subPages = array(),
        $parent = null
    ) {
        $this->pageTitle   = $pageTitle;
        $this->menuTitle   = $menuTitle;
        $this->menuSlug    = $menuSlug ?: sanitize_title($menuTitle);
        $this->iconUrl     = $iconUrl;
        $this->capability  = $capability;
        $this->callback    = $callback;
        $this->position    = $position;
        $this->subPages    = $subPages;
        $this->hasSubPages = $this->subPages ? true : false;
        $this->parent      = $parent;
    }

    /**
     * Add Sub Pages
     *
     * @since  1.0.0
     *
     * @param array $args An array of array of the arguments for the sub-menu page.
     *
     * @return void
     */
    public function addSubPages(array $args)
    {
        foreach ($args as $subPageArgs) {
            $this->addSubPage($subPageArgs);
        }
    }

    /**
     * Add Sub Page
     *
     * @since  1.0.0
     *
     * @param array $args The arguments for the sub-menu page.
     *
     * @return void
     */
    public function addSubPage(array $args)
    {
        $args = wp_parse_args($args, array(
            'parent_slug' => $this->menuSlug,
            'capability'  => $this->capability,
            'callback'    => $this->callback,
        ));

        $this->subPages[]  = $args;
        $this->hasSubPages = true;
    }

    /**
     * Admin bar link
     *
     * @since 1.0.0
     *
     * @param $adminBar object The WP_Admin_Bar object
     */
    public function adminToolbar($adminBar)
    {
        if (! $adminBar instanceof \WP_Admin_Bar) {
            return;
        }

        $adminBar->add_menu(array(
            'id'     => $this->menuSlug,
            'parent' => '',
            'title'  => $this->menuTitle,
            'href'   => esc_url(admin_url('admin.php?page=' . $this->menuSlug)),
        ));
    }

    /**
     * Get
     *
     * @since  1.0.0
     *
     * @param string $name The name of the property from which retrieve the value.
     *
     * @return mixed The property value
     */
    public function __get($name)
    {
        return $this->{$name};
    }

    /**
     * Has Sub Pages
     *
     * @since  1.0.0
     *
     * @return bool True if the current menu page has sub-pages. False otherwise
     */
    public function hasSubPages()
    {
        return $this->hasSubPages;
    }

    /**
     * Get Sub Pages
     *
     * @since  1.0.0
     *
     * @return array An array of array containing the arguments for sub-pages
     */
    public function getSubPages()
    {
        return $this->subPages;
    }
}
