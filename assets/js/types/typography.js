/**
 * Typography Field
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

'use strict';

;(
    function ($, _)
    {
        /**
         * Set the Weights Options
         *
         * @since 1.0.0
         */
        function setWeightsOptions(target)
        {
            // Get the variants select.
            var variants     = target.parentNode.querySelector('[data-typography-variants]'),
                // Set the value of the option selected.
                variant      = variants ? variants.selectedOptions[0].value : 'normal',
                // Retrieve the weights element.
                weightInput  = target.parentNode.querySelector('[data-typography-weights]'),
                // Get the family select related to the variant.
                family       = target.parentNode.querySelector('[data-typography-family]'),
                // Get the selected option from the family to able to retrieve the data needed to
                // set the properly font weight select.
                familyOption = family.selectedOptions[0],
                // Retrieve, clean and store the font weight data.
                // Es. italic:400|700|900;normal:400|700|900
                weights      = familyOption.getAttribute('data-font-weight').split(';');

            if (!weightInput) {
                return false;
            }

            // Retrieve the current selected option value.
            var selectedVal = weightInput.selectedOptions[0].value.replace('w', ''),
                // Used to know if the current value is found within the new variants list.
                foundVal    = false;
            // We want only the weights for the current variant selected.
            for (var c = 0; c < weights.length; ++c) {
                if (-1 !== weights[c].indexOf(variant)) {
                    weights = weights[c];
                    break;
                }
            }
            // Clean the node within the weight select.
            for (c = weightInput.options.length - 1; c >= 0; --c) {
                weightInput.options.remove(c);
            }

            // Search for the correct weights.
            weights.split(':')[1].split('|').forEach(function (el)
            {
                var option   = document.createElement('option');
                option.text  = el;
                option.value = 'w' + el;
                // Add the new options.
                weightInput.appendChild(option);
            });

            // Try to set the current value even if the variants change.
            // If not value fallback to the first option within the new list.
            for (c = 0; c < weightInput.options.length; ++c) {
                if (parseInt(selectedVal) === parseInt(weightInput.options[c].getAttribute('value').replace('w', ''))) {
                    selectedVal = weightInput.options[c].getAttribute('value');
                    foundVal    = true;
                }
            }
            // Fallback?
            selectedVal       = foundVal ? selectedVal : weightInput.options[0].getAttribute('value');
            weightInput.value = 'w' + selectedVal.replace('w', '');
            // Dispatch to select2 the changes.
            $(weightInput).trigger('change.select2');
        }

        /**
         * Set Variants Options
         *
         * @since 1.0.0
         *
         * @return object The current variants element.
         */
        function setVariantsOptions(target)
        {
            // Get the current selected element.
            var option = target.selectedOptions[0];

            // Retrieve the variants.
            var variantsEl = target.parentNode.querySelector('[data-typography-variants]');

            if (!variantsEl) {
                return;
            }

            // The current option value, needed to restore the correct value after the list has been updated.
            var variantOptVal = variantsEl.selectedOptions[0].value,
                // The newly variants list.
                variantsList  = [],
                // If the current value has been found within the newly list, so we can set it again in the new options list.
                foundVal      = false;

            // May be the variants is not included in the list of input defined, so we must
            // get the correct weight based on 'normal' variant.
            try {
                variantsList = option.getAttribute('data-font-variant').split(';');
                if (!variantsList) {
                    variantsList = ['normal'];
                }
            } catch (exc) {
                variantsList = ['normal'];
            }

            if (variantsEl) {
                // Remove the previous options from the variants list.
                for (var c = variantsEl.options.length - 1; c >= 0; --c) {
                    variantsEl.remove(c);
                }

                // Add the new options.
                variantsList.forEach(function (el)
                {
                    if (variantOptVal === el) {
                        foundVal = true;
                    }

                    var option   = document.createElement('option');
                    option.value = option.text = el;
                    variantsEl.add(option);
                });

                // Set the previously selected value if exists, fallback to the first one.
                variantsEl.value = foundVal ? variantOptVal : variantsEl.options[0].value;
                // Update the select2 list.
                $(variantsEl).trigger('change.select2');

                return variantsEl;
            }
        }

        // Get the typography elements.
        var typo     = document.querySelectorAll('[data-typography-family]'),
            variants = document.querySelectorAll('[data-typography-variants]');

        if (!typo.length) {
            return false;
        }

        // Font Family Select
        _.forEach(typo, function (elem)
        {
            elem.addEventListener('select2change', function (e)
            {
                e.preventDefault();
                e.stopPropagation();

                // Set the variants options.
                setVariantsOptions(e.target);
                // Set the weights options based on the variants element set.
                setWeightsOptions(e.target);
            });
        });

        // Variants Select.
        if (variants.length) {
            _.forEach(variants, function (elem)
            {
                setWeightsOptions(elem);

                elem.addEventListener('select2change', function (e)
                {
                    e.preventDefault();
                    e.stopPropagation();

                    setWeightsOptions(e.target);
                });
            });
        }
    }(jQuery, _)
);