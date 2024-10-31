<?php
namespace QiblaEvents\Admin\Page;

/**
 * Menu Page Register
 *
 * @package   QiblaEvents\Admin\Page
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
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

use QiblaEvents\RegisterInterface;

/**
 * Class Register
 *
 * @since   1.0.0
 * @package QiblaEvents\Admin\Page
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Register implements RegisterInterface
{
    /**
     * Pages
     *
     * @since  1.0.0
     *
     * @var array A list of MenuPage instances
     */
    private $pages;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param array $pages A list of MenuPage instances to register
     */
    public function __construct(array $pages)
    {
        $this->pages = $pages;
    }

    /**
     * Add Taxonomy Fields
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->pages as $page) :
            if ($page->parent) {
                add_submenu_page(
                    $page->parent,
                    $page->pageTitle,
                    $page->menuTitle,
                    $page->capability,
                    $page->menuSlug,
                    $page->callback
                );

                continue;
            } else {
                add_menu_page(
                    $page->pageTitle,
                    $page->menuTitle,
                    $page->capability,
                    $page->menuSlug,
                    $page->callback,
                    $page->iconUrl,
                    $page->position
                );
            }

            // Get the sub pages if exists and add them.
            if ($page->hasSubPages()) {
                foreach ($page->getSubPages() as $args) {
                    add_submenu_page(
                        $args['parent_slug'],
                        $args['page_title'],
                        $args['menu_title'],
                        $args['capability'],
                        $args['menu_slug'],
                        $args['callback']
                    );
                }
            }
        endforeach;
    }
}
