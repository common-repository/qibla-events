<?php

namespace QiblaEvents\IconsSet;

/**
 * Icon
 *
 * @since      1.0.0
 * @package    QiblaEvents\IconsSet
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
 * Class Icon
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Icon
{
    /**
     * Icon Slug
     *
     * @since  1.0.0
     *
     * @var string The icon name
     */
    protected $iconSlug;

    /**
     * Icon Class Name
     *
     * @since  1.0.0
     *
     * @var string The Icon class name
     */
    protected $iconClassName;

    /**
     * Compact
     *
     * @since  1.0.0
     *
     * @var string Compact version of the icon
     */
    protected $compact;

    /**
     * Icon Prefix
     *
     * @since 1.0.0
     *
     * @var string The prefix of the icon
     */
    protected $prefix;

    /**
     * Unicode
     *
     * @since 1.0.0
     *
     * @var string The unicode value for the icon
     */
    protected $unicode;

    /**
     * Constructor
     *
     * @since  1.0.0
     *
     * @throws \InvalidArgumentException If the $compact parameter is not correct.
     *
     * @param string $compact The compact version of the Icon in the format ClassName::icon-slug
     * @param string $default The default icon in compact version.
     *
     * @param string $compact The icon class.
     */
    public function __construct($compact, $default = '')
    {
        if (! $compact || false === strpos($compact, '::')) {
            if (! $default) {
                throw new \InvalidArgumentException('Empty or malformed icon slug in Icon Factory.');
            }

            // If the default icon has been provided, use it to create the Icon object.
            $compact = $default;
        }

        // Retrieve the parts.
        // They are in the form of: className::iconName lower-cased.
        $parts = explode('::', $compact);
        // Set the icon class name.
        // Generally used as value for font-family.
        $this->iconClassName = ucfirst(reset($parts));
        // Set the Icon Class name.
        $iconClass = 'QiblaEvents\\IconsSet\\' . $this->iconClassName;
        // Build the icon class.
        $iconSet = new $iconClass();
        // The compact version.
        $this->compact = $compact;
        // The icon slug.
        $this->iconSlug = end($parts);

        if (! isset($iconSet[$this->iconSlug])) {
            throw new \InvalidArgumentException('Wrong icon slug in Icon Factory.');
        }

        // Set the properties from the icon set.
        $this->prefix  = $iconSet->getPrefix();
        $this->unicode = $iconSet[$this->iconSlug];

        unset($iconSet);
    }

    /**
     * Get Icon Slug
     *
     * @since  1.0.0
     *
     * @return mixed|string The slug of the icon
     */
    public function getIconSlug()
    {
        return $this->iconSlug;
    }

    /**
     * Get Prefix
     *
     * @since  1.0.0
     *
     * @return string The prefix of the icon
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Get Html Class
     *
     * @since  1.0.0
     *
     * @return string The class of the icon
     */
    public function getHtmlClass()
    {
        return $this->getPrefix() . ' ' . $this->getIconSlug();
    }

    /**
     * Icon Class name
     *
     * @since  1.0.0
     *
     * @return string The name of the icon class
     */
    public function getIconClassName()
    {
        return $this->iconClassName;
    }

    /**
     * Get Unicode
     *
     * @since  1.0.0
     *
     * @return string The unicode version of the icon
     */
    public function getUnicode()
    {
        return $this->unicode;
    }

    /**
     * Get Compact
     *
     * @since  1.0.0
     *
     * @return string The compact version of the icon
     */
    public function getCompact()
    {
        return $this->compact;
    }

    /**
     * Array version
     *
     * @since  1.0.0
     *
     * @return array An associative array containing: icon_slug, icon_html_class, icon_class, icon_unicode.
     */
    public function getArrayVersion()
    {
        return array(
            'icon_slug'       => $this->getIconSlug(),
            'icon_html_class' => $this->getHtmlClass(),
            'icon_class'      => $this->getIconClassName(),
            'icon_unicode'    => $this->getUnicode(),
        );
    }
}
