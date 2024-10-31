/**
 * Icon List JavaScript Type
 *
 * @since      1.0.0
 * @author     Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, Alfio Piccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione <alfio.piccione@gmail.com>
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

window.DL = window.DL || {};

;(
    function (_, DL)
    {
        'use strict';

        // Get the reference to the type.
        // Icon Types is a list containing all of the icons grouped by vendor.
        var iconTypes = document.querySelectorAll('[data-type="icon-list"]'),
            Icons     = {};

        if (!iconTypes.length) {
            return false;
        }

        /**
         * Icons List
         *
         * @since 1.0.0
         */
        function iconsList(group, vendor)
        {
            var obj = Object.create(Object.prototype, {
                /**
                 * Icons
                 *
                 * Contain the current Icons set and info about vendor, vendor prefix, select etc...
                 *
                 * @since 1.0.0
                 */
                Icons: {
                    value: {
                        /**
                         * The Icons Groups
                         *
                         * @since 1.0.0
                         */
                        optionsGroups: [],

                        /**
                         * Icon Options
                         *
                         * @since 1.0.0
                         */
                        options: [],

                        /**
                         * Current Vendor
                         *
                         * @since 1.0.0
                         */
                        currVendor: '',

                        /**
                         * Current Vendor Prefix
                         *
                         * @since 1.0.0
                         */
                        currVendorPrefix: '',

                        /**
                         * Set Current Vendor
                         *
                         * @since 1.0.0
                         */
                        setCurrVendor: function (vendor)
                        {
                            this.currVendor = vendor;
                        },

                        /**
                         * Set Current Vendor Prefix
                         *
                         * @since 1.0.0
                         */
                        setCurrVendorPrefix: function (prefix)
                        {
                            this.currVendorPrefix = prefix;
                        },

                        /**
                         * Build Options Groups
                         *
                         * @since 1.0.0
                         */
                        buildOptionsGroups: function (optgroup)
                        {
                            // Initialize the vendor array..
                            this.optionsGroups[this.currVendor] = [];

                            // Build the options groups by take the vendor name as container
                            // and the options values as values of the new array.
                            // So, we store Vendor => Vendor::icon-name.
                            _.forEach(optgroup.childNodes, function (option)
                            {
                                this.optionsGroups[this.currVendor].push(option.getAttribute('value'));
                            }, this);
                        },

                        /**
                         * Build
                         *
                         * @since 1.0.0
                         */
                        build: function (optgroup, prefix, vendor)
                        {
                            // Set the current vendor.
                            this.setCurrVendor(vendor);
                            // Set the prefix.
                            this.setCurrVendorPrefix(prefix);
                            // Build the groups list.
                            this.buildOptionsGroups(optgroup);
                        }
                    }
                },

                /**
                 * The Elements Builder
                 *
                 * @since 1.0.0
                 */
                IconsBuilder: {
                    value: {
                        /**
                         * Html List
                         *
                         * @since 1.0.0
                         */
                        list: document.createDocumentFragment(),

                        /**
                         * Build Icons List
                         *
                         * @todo Check for fragment.
                         *
                         * @since 1.0.0
                         */
                        build: function (icons, el)
                        {
                            // Set the elements container.
                            el          = el ? el : 'li';
                            // Get the icons from the vendor.
                            var options = icons.optionsGroups[icons.currVendor.toLowerCase()];

                            _.forEach(options, function (icon)
                            {
                                // Get the icon class.
                                var iconClass = icon.replace(icons.currVendor + '::', ''),
                                    // Create the list item.
                                    item      = document.createElement(el),
                                    // Create the Icon.
                                    i         = document.createElement('i');
                                // Set the class for the icon element.
                                i.setAttribute('class', icons.currVendorPrefix + ' ' + iconClass);
                                // Add the attributes and values.
                                item.classList.add('dltype-current-icons-grid__item');
                                item.setAttribute('data-icon-value', icon);
                                item.appendChild(i);
                                // Add the current list item into the fragment.
                                this.list.appendChild(item);
                            }, this);
                        }
                    }
                }
            });

            // Create the Icons list.
            obj.Icons.build(group, vendor.getAttribute('data-vendor-prefix'), vendor.getAttribute('value'));
            // Build the list.
            obj.IconsBuilder.build(obj.Icons);

            return obj;
        }

        /**
         * Icons List Builder
         *
         * @since 1.0.0
         *
         * @param vendor
         * @param select
         */
        function buildIconsList(vendor, select)
        {
            var selectedVendor = vendor.selectedOptions[0],
                group          = select.querySelector(
                    'optgroup[label="' + selectedVendor.getAttribute('value') + '"]'
                );

            // Build the list.
            // We'll get the Icons reference.
            return iconsList(group, selectedVendor);
        }

        /**
         * Update Icons List
         *
         * @param vendor
         * @param list
         * @returns {Element}
         */
        function updateIconsList(vendor, list)
        {
            // Build the icons.
            Icons        = buildIconsList(vendor, list);
            // Check if the icons grid is opened.
            var currGrid = document.getElementById('dltype-current-icons-grid');

            if (!currGrid) {
                currGrid = document.createElement('ul');
                // Set the id for the generated icons grid.
                currGrid.setAttribute('id', 'dltype-current-icons-grid');
                currGrid.setAttribute('class', 'dltype-current-icons-grid');
            } else {
                while (currGrid.firstChild) {
                    currGrid.removeChild(currGrid.firstChild);
                }
            }

            currGrid.appendChild(Icons.IconsBuilder.list);
            setDisplayIconsGridEl('flex');

            return currGrid;
        }

        /**
         * Update Current Icon
         *
         * @since 1.0.0
         *
         * @param icon
         * @param value
         */
        function updateCurrentIcon(icon, value)
        {
            var iconClass = value.replace(Icons.Icons.currVendor + '::', '') + ' dltype-icon-list-current';
            icon.setAttribute('class', Icons.Icons.currVendorPrefix + ' ' + iconClass);
        }

        /**
         * Hide the current Icon element
         *
         * @since 1.0.0
         *
         * @param {Element} container The container of the icon current element
         * @param {String}  value     'hidden' or 'visible'.
         *
         * @return void
         */
        function setVisibilityCurrentIconEl(container, value)
        {
            var el = container.querySelector('.dltype-icon-list-current');

            if (el) {
                el.style.visibility = value;
            }
        }

        /**
         * Set the Display property for IconsGrid Element
         *
         * @since 1.0.0
         *
         * @param value
         */
        function setDisplayIconsGridEl(value)
        {
            var grid = document.getElementById('dltype-current-icons-grid');

            if (grid) {
                grid.style.display = value;
            }
        }

        /**
         * Set the None Value for the current Icon element
         *
         * @since 1.0.0
         *
         * @param selectInput
         */
        function setNoneValue(selectInput)
        {
            setVisibilityCurrentIconEl(selectInput.parentElement, 'hidden');
            setDisplayIconsGridEl('none');
            selectInput.value = 'none';
        }

        // Go through all icons listings.
        _.forEach(iconTypes, function (list)
        {
            // Retrieve the current icon.
            // This is build based on meta or option or whatever.
            var currIcon    = list.parentElement.getElementsByClassName('dltype-icon-list-current')[0],
                vendorsList = list.previousElementSibling;

            // Hide the currIcon if current value for the vendorsList is set to none.
            if ('none' === vendorsList.value) {
                setVisibilityCurrentIconEl(currIcon.parentElement, 'hidden');
            }

            if (vendorsList) {
                // Hide the icon Types.
                // We'll set the selected attribute within the change event listener.
                list.style.display = 'none';
                // Retrieve the vendor selector and assign an event on change.
                // We can then retrieve all options groups based on this selection and build the icons list.
                vendorsList.addEventListener('change', function (e)
                {
                    if ('none' !== e.target.value) {
                        // Update Icons Grid
                        updateIconsList(e.target, list);
                        // Dispatch che click event to the current icon element.
                        DL.Utils.Events.dispatchEvent('click', currIcon, null, {referer: 'list'});
                    } else {
                        setNoneValue(list);
                    }
                });

                // Set the event listener for the current icon.
                // We can show and change it.
                currIcon.addEventListener('click', function (e)
                {
                    if ('none' !== vendorsList.value) {
                        var currGrid = document.getElementById('dltype-current-icons-grid'),
                            vendor   = this.previousElementSibling.previousElementSibling,
                            list     = this.previousElementSibling;

                        // Show / Hide the icons grid.
                        // But only if the event become from the click directly, keep it visible
                        // in case it's dispatched by the icons list update.
                        if (
                            currGrid && 'flex' === currGrid.style.display &&
                            typeof e.detail.referer === 'undefined'
                        ) {
                            setDisplayIconsGridEl('none');
                            return false;
                        }

                        // Insert the newly icons list.
                        // We don't simply show the curr grid if exists because the vendor icons may changed.
                        this.parentNode.insertBefore(updateIconsList(vendor, list), this.nextSibling);

                        // The Icon Change Listener.
                        _.forEach(document.getElementsByClassName('dltype-current-icons-grid__item'), function (item)
                        {
                            item.addEventListener('click', function (e)
                            {
                                // Get the value of the current selected icon in order to select it within the select type.
                                var iconValue = this.getAttribute('data-icon-value');
                                if (!iconValue) {
                                    return false;
                                }

                                // Update the current Icon with the new value.
                                updateCurrentIcon(currIcon, iconValue);
                                // Set the new Icon value.
                                list.value = iconValue;
                            });
                        });

                        // Let show the icon element if previously hidden.
                        setVisibilityCurrentIconEl(list.parentElement, 'visible', currGrid);
                    } else {
                        setNoneValue(list);
                    }
                });
            }
        });
    }(_, window.DL)
);