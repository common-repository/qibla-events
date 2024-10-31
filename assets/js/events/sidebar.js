/**
 * Sidebar Sticky
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
;(
    function (window, _, $) {

        'use strict';

        /**
         * Is In View Port
         *
         * @since 1.0.0
         *
         * @param elem
         * @returns {boolean}
         */
        var isInViewport = function (elem) {
            var bounding = elem.getBoundingClientRect();
            return (
                bounding.top >= 0 &&
                bounding.left >= 0 &&
                bounding.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                bounding.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        };

        var viewPortWidth  = Math.round(window.innerWidth),
            sidebar        = document.getElementById('dlsidebar-listings'),
            sidebarWrap    = document.querySelector('.dlsidebar .dlsidebar-wrapper'),
            articleHeader  = document.querySelector('.dlarticle__header'),
            articleContent = document.querySelector('.dlarticle__content'),
            button         = document.querySelector('.dlsidebar .dlevent-ticket-wrapper'),
            actions        = document.querySelector('.dlsidebar .dlactions'),
            socials        = document.querySelector('.dlsidebar .dlsocials-links');

        if(sidebar) {
            var style = window.getComputedStyle(sidebar, null),
                width = (parseInt(style.getPropertyValue('width')) - parseInt(style.getPropertyValue('padding-left')));
        }
        /**
         * Render
         *
         * @since 1.0.0
         */
        function render()
        {
            setTimeout(function () {
                var viewPortWidth = Math.round(window.innerWidth),
                    parentNode;

                if (!viewPortWidth) {
                    return;
                }

                if (1024 <= viewPortWidth) {
                    parentNode = sidebarWrap;
                } else if (1024 > viewPortWidth) {
                    sidebarWrap.classList.remove('fixed');
                    sidebarWrap.style.cssText = '';
                    parentNode = articleContent;
                } else {
                    return;
                }

                if (parentNode) {
                    if (parentNode === sidebarWrap) {
                        if (actions) {
                            parentNode.insertBefore(actions, parentNode.children[1]);
                        }
                        if (button) {
                            parentNode.insertBefore(button, parentNode.children[1]);
                        }
                        if (socials) {
                            parentNode.insertBefore(socials, parentNode.lastChild);
                        }

                    } else {
                        if (actions) {
                            parentNode.insertBefore(actions, parentNode.firstChild);
                        }
                        if (socials) {
                            parentNode.insertBefore(socials, parentNode.children[1]);
                        }
                        if (button) {
                            parentNode.insertBefore(button, parentNode.firstChild);
                        }
                    }
                }
            }, 100);
        }

        /**
         * Sticky Sidebar
         *
         * @since 1.0.0
         */
        function stickySidebar()
        {
            var viewPortWidth = Math.round(window.innerWidth);

            if (1024 < viewPortWidth) {
                setTimeout(function () {
                    var main        = document.getElementById('dlmain'),
                        sidebarWrap = document.querySelector('.sidebar-sticky'),
                        header       = document.querySelector('.dlarticle__header'),
                        sidebar     = document.getElementById('dlsidebar-listings');

                    if (sidebar) {
                        var style = window.getComputedStyle(sidebar, null),
                            width = (parseInt(style.getPropertyValue('width')) - parseInt(style.getPropertyValue('padding-left')));
                    }

                    if (!sidebarWrap) {
                        return;
                    }

                    var sidebarRect       = sidebarWrap.getBoundingClientRect(),
                        sidebarParentRect = sidebarWrap.parentElement.getBoundingClientRect();

                    // Set sidebar wrapper width.
                    sidebarWrap.style.width = width + 'px';

                    // Set Position.
                    if (!isInViewport(header) && parseInt(sidebarRect.top) <= 0) {
                        sidebarWrap.classList.add('fixed');
                    }
                    if (parseInt(sidebarRect.top) > 0 &&
                        parseInt(sidebarRect.top) <= parseInt(header.getBoundingClientRect().top)
                    ) {
                        sidebarWrap.classList.remove('fixed');
                    }

                    // Set stop sidebar wrapper.
                    if (parseInt(main.getBoundingClientRect().bottom) <= parseInt(sidebarRect.bottom)) {
                        sidebarWrap.classList.add('stop');
                    }
                    if (sidebarWrap.getBoundingClientRect().y > 120) {
                        sidebarWrap.classList.remove('stop');
                    }

                }, 100);
            }
        }

        // Load event.
        window.addEventListener('load', function () {

            var anchors = document.querySelectorAll('a[href^="#"]');
            // Smooth Scrolling Fragments.
            anchors.length && _.forEach(anchors, function (element)
            {
                element.addEventListener('click', function (evt)
                {
                    var frag = evt.target.href.substr(evt.target.href.indexOf('#'));

                    if ('#' === frag) {
                        // Don't do anything with '#' fragments.
                        // This kind of links generally are used in third party plugins or to prevent empty
                        // href attribute.
                        return;
                    }

                    // Workaround for when the element is hidden.
                    // Refer to https://github.com/gravmatt/force-js/issues/2
                    if (!$(frag).is(':visible')) {
                        return;
                    }

                    evt.preventDefault();
                    force.jump(frag, {
                        easing: 'easeInOutQuad',
                        duration: 1500
                    });
                });
            });

            var sidebar = document.getElementById('dlsidebar-listings'),
                sidebarWrap = document.querySelector('.dlsidebar-wrapper'),
                sidebarEl = document.querySelectorAll('.dlsidebar-wrapper > *'),
                widget = false;

            // Check widget in sidebar.
            _.forEach(sidebarEl, function (item) {
                if (item.classList.contains('dlsidebar__widget')) {
                    widget = true
                }
            });

            if (sidebarWrap) {
                // Add class
                sidebarWrap.classList.add('sidebar-sticky');
                if (1024 < viewPortWidth && sidebarWrap.classList.contains('sidebar-sticky')) {
                    sidebarWrap.style.width = width + 'px';
                }
            }

            render();
        });

        // Scroll event.
        window.addEventListener('scroll', function () {
            stickySidebar();
        });

        // Resize event.
        window.addEventListener('resize', function () {
            render();
        });

    }(window, _, jQuery)
);
