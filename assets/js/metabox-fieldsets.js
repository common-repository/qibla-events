/**
 * Metabox Fieldsets
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
    function (_, Modernizr, DL)
    {
        "use strict";

        // The meta-boxes.
        var metaBoxes = document.getElementsByClassName('dlui-metabox-tabs');

        // No Meta-boxes? Nothing to do.
        if (!metaBoxes.length) {
            return false;
        }

        _.forEach(metaBoxes, function (metaBox)
        {
            var
                // This is the array containing the new field-sets ready for used within the tabs.
                tabsCollection = [],
                // All of the field-sets within the metabox.
                fieldsets      = metaBox.getElementsByClassName('dl-metabox-fieldset'),
                // The parent container of the of the field-sets. Generally is a div.inside.
                fParent        = fieldsets[0].parentElement,
                // The tabs navigation titles, retrieved form the legend field-sets.
                tabsNavTitles  = [],
                // The navigation container.
                tabsNav        = document.createElement('ul');

            // Not fieldsets? Nothing to do.
            if (!fieldsets.length) {
                return false;
            }

            // Let's extract the informations from the field-sets.
            for (var tabCounter = 0; tabCounter < fieldsets.length; ++tabCounter) {
                // Set the variables.
                var fieldset     = fieldsets[tabCounter],
                    fattributes  = fieldset.attributes,
                    description  = fieldset.querySelector(':scope > [class*="__description"]'),
                    fields       = fieldset.getElementsByClassName('dl-field'),
                    currNavTitle = {};

                // Create the label the title navigation.
                currNavTitle.label = document.createTextNode(fieldset.firstElementChild.innerText);

                // If exists, add the markup for the icon associated to the nav label.
                var iconClass = fieldset.firstElementChild.getAttribute('data-icon');
                if (iconClass) {
                    var el            = document.createElement('i');
                    el.className      = iconClass;
                    currNavTitle.icon = el;
                }

                // Store the tabs nav Title for later use.
                tabsNavTitles.push(currNavTitle);

                // Remove the specific field-set attributes.
                fattributes.removeNamedItem('form');
                fattributes.removeNamedItem('name');

                // The tab Wrapper.
                var tabWrapper     = document.createElement('div'),
                    // The description.
                    tabDescription = document.createElement('p');

                // Append the Description tab. And set the properly classes.
                // The description element is optional and may not exists.
                if (description) {
                    tabDescription.appendChild(document.createTextNode(description.innerText));
                    tabDescription.className = 'dl-metabox-tabs__tab-description';
                    tabWrapper.appendChild(tabDescription);
                }

                // Add the Fields to the new table.
                for (var fc = 0; fc < fields.length; fc++) {
                    var cfield            = fields[fc].cloneNode(true),
                        tr                = document.createElement('div'),
                        tdLabel           = document.createElement('div'),
                        tdType            = document.createElement('div'),
                        cfieldLabel       = cfield.getElementsByTagName('label')[0],
                        classScope        = cfield.classList.item(0),
                        cfieldDescription = cfield.querySelector('.dl-field__description');

                    // Some Input Type may not have label like hidden.
                    if (cfieldLabel) {
                        tdLabel.appendChild(cfieldLabel);
                        tdLabel.classList.add(classScope + '__label');
                    }
                    // Some Input type may not have description.
                    // The argument is optional.
                    if (cfieldDescription) {
                        tdLabel.appendChild(cfieldDescription);
                    }

                    _.forEach(cfield.childNodes, function (node)
                    {
                        node = node.cloneNode(node);
                        tdType.appendChild(node);
                    });
                    tdType.classList.add(cfield.classList.item(0) + '__type');

                    tr.appendChild(tdLabel);
                    tr.appendChild(tdType);

                    // Copy the classes to the new element.
                    //noinspection JSFunctionExpressionToArrowFunction
                    _.forEach(cfield.classList, function (item)
                    {
                        tr.classList.add(item);
                    });

                    tabWrapper.appendChild(tr);
                }

                // Append the hidden fields since them are not within a field wrapper.
                //noinspection JSFunctionExpressionToArrowFunction
                _.forEach(fieldset.parentNode.querySelectorAll(':scope > [type="hidden"]'), function (hidden)
                {
                    tabWrapper.appendChild(hidden);
                });

                // Set the tab wrapper attributes.
                tabWrapper.setAttribute(
                    'id', fieldset.firstElementChild.innerText.toLowerCase().replace(/[^a-z0-9]/g, '_')
                );
                tabWrapper.setAttribute('class', 'dl-metabox-tabs__tab');
                // Add the tab to the collection.
                tabsCollection.push(tabWrapper);
            }

            // Set the attributes for the tabs navigation.
            tabsNav.setAttribute('class', 'dl-metabox-tabsnav');

            // Build the Tabs navigation.
            var li, a;
            for (var c = 0; c < tabsCollection.length; ++c) {
                li = document.createElement('li');
                a  = document.createElement('a');

                // Set the list item attributes.
                li.setAttribute('class', 'dl-metabox-tabsnav__item');
                // Set the anchor attributes
                a.setAttribute('class', 'dl-metabox-tabsnav__link');
                a.setAttribute('href', '#' + tabsCollection[c].getAttribute('id'));

                // If the icon exists, append it.
                if ('icon' in tabsNavTitles[c]) {
                    a.appendChild(tabsNavTitles[c].icon);
                }

                a.appendChild(tabsNavTitles[c].label);

                li.appendChild(a);
                tabsNav.appendChild(li);
            }

            var tabs = document.createElement('div');
            tabs.setAttribute('class', 'dl-metabox-tabs');

            tabs.appendChild(tabsNav);
            _.forEach(tabsCollection, function (tab)
            {
                tabs.appendChild(tab);
            });

            fParent.innerHTML = '';
            fParent.appendChild(tabs);

            // Set the first nav link as active.
            fParent.querySelectorAll('.dl-metabox-tabsnav__link')[0].classList.add('dlu-active');
            // Show only the first field-set.
            tabs = tabs.getElementsByClassName('dl-metabox-tabs__tab');
            tabs[0].classList.add('dltab-opened');
            [].slice.call(tabs, 1).forEach(function (tab)
            {
                tab.classList.add('dltab-closed');
            });

            var tabsNavLinks = Array.prototype.slice.call(
                metaBox.getElementsByClassName('dl-metabox-tabsnav')[0]
                    .getElementsByClassName('dl-metabox-tabsnav__link')
            );

            // Set the Listener to Show/Hide Field-sets.
            _.forEach(tabsNavLinks, function (el)
            {
                el.addEventListener('click', function (e)
                {
                    e.preventDefault();
                    e.stopPropagation();

                    // Remove active status from the nav links.
                    _.forEach(tabsNavLinks, function (item)
                    {
                        item.classList.remove('dlu-active');
                    });
                    // Set the current nav as active.
                    this.classList.add('dlu-active');
                    // Get the reference to the tab.
                    var tab = document.querySelector(this.getAttribute('href'));
                    // Close all tabs.
                    _.forEach(tabs, function (item)
                    {
                        if (item.getAttribute('id') === tab.getAttribute('id')) {
                            item.classList.remove('dltab-closed');
                            item.classList.add('dltab-opened');
                            return;
                        }

                        item.classList.remove('dltab-opened');
                        item.classList.add('dltab-closed');
                    });

                    // Dispatch the event to the document on tab opened.
                    DL.Utils.Events.dispatchEvent('dl-tab-opened', document);
                });
            });
        });

    }(_, window.Modernizr, window.DL)
);