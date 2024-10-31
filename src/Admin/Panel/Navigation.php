<?php
namespace QiblaEvents\Admin\Panel;

use QiblaEvents\Functions as F;

/**
 * Navigation
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Panel
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
 * Class Navigation
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Navigation
{
    /**
     * Current Menu Item
     *
     * @since  1.0.0
     *
     * @var string The current menu item slug.
     */
    protected $currMenuItem;

    /**
     * Items
     *
     * @since  1.0.0
     *
     * @var array A list of items
     */
    protected $items;

    /**
     * Base Url
     *
     * The url to which add query args.
     * Generally is the page where the navigation is displayed
     *
     * @since  1.0.0
     *
     * @var string The url to which add query args.
     */
    protected $baseUrl;

    /**
     * Build Nav Items
     *
     * @todo   Need a max Depth for submenus.
     *
     * @since  1.0.0
     *
     * @param       $item
     * @param array $parent
     * @param bool  $isSubmenu
     */
    protected function navItems($item, $parent = array(), $isSubmenu = false)
    {
        // Is the current menu item?
        $isCurrent = ($item['menu_slug'] === $this->currMenuItem);

        // The item classes.
        $itemClasses = array(
            'dm-panel__nav-item',
            $isCurrent ? 'dm-panel__nav-item--current' : '',
        );

        // The classes for the link item.
        $linkClasses = array(
            'dm-panel__nav-link',
            $isCurrent ? 'dm-panel__nav-link--current' : '',
        );

        // Build the menu item url.
        $itemUrl = add_query_arg('item-slug', $item['menu_slug'], $this->baseUrl);

        // Sanitize and make the class for icon as string.
        if (isset($item['icon_class'])) {
            $iconClass = F\sanitizeHtmlClass($item['icon_class']);
        }
        ?>

        <li class="<?php echo esc_attr(implode(' ', $itemClasses)) ?>"
            data-item="<?php echo esc_attr(sanitize_key($item['menu_slug'])) ?>">
            <a class="<?php echo esc_attr(implode(' ', $linkClasses)) ?>" href="<?php echo esc_url($itemUrl) ?>">
                <?php if (isset($iconClass)) : ?>
                    <i class="dm-panel__nav-item-icon <?php echo esc_attr($iconClass) ?>"></i>
                <?php endif; ?>
                <?php echo esc_html($item['page_title']) ?>
            </a>

            <?php if (isset($item['submenu'])) : ?>
                <ul class="dm-panel__nav-submenu">
                    <?php
                    foreach ($item['submenu'] as $subItem) {
                        $this->navItems($subItem, $item, true);
                    }
                    ?>
                </ul>
            <?php endif; ?>
        </li>
        <?php
    }

    /**
     * Set Current menu Item
     *
     * Set the current menu item and fallback if not found inside the items list.
     *
     * @since  1.0.0
     *
     * @param string $currMenuItem The slug of the current menu item.
     *
     * @return string The current menu item slug
     */
    protected function buildCurrentMenuItem($currMenuItem)
    {
        // Retrieve the slugs of the menu items.
        $items = wp_list_pluck($this->items, 'menu_slug');
        // Make the currMenuItem as slug.
        $currItem = sanitize_title_with_dashes(remove_accents($currMenuItem));

        // Check if the current item is within the items collection.
        // Fallback to the first items element.
        if (! in_array($currItem, $items, true)) {
            $currItem = $items[0];
        }

        return $currItem;
    }

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param string $baseUrl      The url to which add query args.
     * @param array  $items        The list of the navigation items.
     * @param string $currMenuItem The current menu item page.
     */
    public function __construct($baseUrl, $items, $currMenuItem)
    {
        $this->baseUrl      = $baseUrl;
        $this->items        = $items;
        $this->currMenuItem = $this->buildCurrentMenuItem($currMenuItem);
    }

    /**
     * Render Navigation
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function render()
    {
        if (empty($this->items)) {
            return null;
        }
        ?>
        <nav class="dm-panel__nav">
            <ul class="dm-panel__nav-list">
                <?php
                foreach ($this->items as $item) :
                    $this->navItems($item);
                endforeach;
                ?>
            </ul>
        </nav>
        <?php
    }
}
