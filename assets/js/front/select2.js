/**
 * Select2
 *
 * @since      1.0.0
 * @author     Alfio Piccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, Alfio Piccione
 * @license    GNU General Public License, version 2
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
    function (Modernizr, $, DL) {
        "use strict";

        function initSelect2()
        {
            var $select = $('select');

            _.forEach($select, function (el) {
                var $el = $(el);
                $el.select2({
                    theme: el.getAttribute('data-selecttheme'),
                    width: '100%'
                });

            });

            $select.change(function (e) {
                DL.Utils.Events.dispatchEvent('select2change', e.target);
            });
        }

        window.addEventListener('load', function () {
            initSelect2();
        });

        //
        // WorkAround for WooCommerce country field.
        // The field select2 is reinitilized and the style of the theme is missed.
        // @see https://github.com/woocommerce/woocommerce/issues/14647.
        $(document.body).bind('country_to_state_changed', function () {
            setTimeout(function () {
                initSelect2();
            }, 0);
        });
    }(window.Modernizr, window.jQuery, window.DL)
);