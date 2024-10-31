/**
 * Autocomplete
 *
 * @todo Autocomplete: Create template for format Result.
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

(
    function (_, $, DL, dllocalized)
    {
        "use strict";

        // Override some autocomplete methods.
        $.Autocomplete.prototype = _.extend($.Autocomplete.prototype, {
            /**
             * Set Listeners
             *
             * @since 1.0.0
             */
            setListeners: function ()
            {
                var that = this;

                $(window).on('resize.autocomplete', that.fixPositionCapture);
                this.el.on('keydown.autocomplete', that.onKeyPress.bind(this));
                this.el.on('keyup.autocomplete', that.onKeyUp.bind(this));
                this.el.on('blur.autocomplete', that.onBlur.bind(this));
                this.el.on('focus.autocomplete', that.onFocus.bind(this));
                this.el.on('change.autocomplete', that.onKeyUp.bind(this));
                this.el.on('input.autocomplete', that.onKeyUp.bind(this));

                if (!_.isUndefined(that.initialSuggestions) && that.initialSuggestions.length) {
                    that.el.on('click.autocomplete focus.autocomplete input.autocomplete', function ()
                    {
                        if ('' === this.value) {
                            that.suggest(that.initialSuggestions);
                        }
                    });
                }
            },

            /**
             * Init
             *
             * @since 1.0.0
             */
            initialize: function ()
            {
                var that               = this,
                    suggestionSelector = '.' + that.classes.suggestion,
                    selected           = that.classes.selected,
                    options            = that.options,
                    container;

                // Remove autocomplete attribute to prevent native suggestions:
                that.element.setAttribute('autocomplete', 'off');

                // html() deals with many types: htmlString or Element or Array or jQuery
                that.noSuggestionsContainer = $('<div class="autocomplete-no-suggestion"></div>')
                    .html(this.options.noSuggestionNotice).get(0);

                that.initialSuggestions   = this.options.initialSuggestions;
                that.suggestionsContainer = $.Autocomplete.utils.createNode(options.containerClass);

                container = $(that.suggestionsContainer);

                container.appendTo(options.appendTo || 'body');

                // Only set width if it was provided:
                if (options.width !== 'auto') {
                    container.css('width', options.width);
                }

                // Listen for mouse over event on suggestions list:
                container.on('mouseover.autocomplete', suggestionSelector, function ()
                {
                    that.activate($(this).data('index'));
                });

                // Deselect active element when mouse leaves suggestions container:
                container.on('mouseout.autocomplete', function ()
                {
                    that.selectedIndex = -1;
                    container.children('.' + selected).removeClass(selected);
                });

                // Listen for click event on suggestions list:
                container.on('click.autocomplete', suggestionSelector, function ()
                {
                    that.select($(this).data('index'));
                });

                container.on('click.autocomplete', function ()
                {
                    clearTimeout(that.blurTimeoutId);
                });

                that.fixPositionCapture = function ()
                {
                    if (that.visible) {
                        that.fixPosition();
                    }
                };

                this.setListeners();
            },

            /**
             * On Hide
             *
             * @since 1.0.0
             *
             * @param callback
             */
            hide: function (callback)
            {
                var that      = this,
                    container = $(that.suggestionsContainer);

                if ($.isFunction(that.options.onHide) && that.visible) {
                    that.options.onHide.call(that.element, container);
                }

                that.visible       = false;
                that.selectedIndex = -1;

                clearTimeout(that.onChangeTimeout);

                container.slideUp(275, function ()
                {
                    this.classList.remove('autocomplete-suggestions--is-open');

                    // Dispatch event.
                    DL.Utils.Events.dispatchEvent('autocomplete-hide', that.element);

                    if (callback && _.isFunction(callback)) {
                        callback();
                    }
                });

                that.signalHint(null);
            },

            /**
             * Suggest
             *
             * @since 1.0.0
             *
             * @param {Object} suggestions Suggestions passed directly.
             */
            suggest: function (suggestions)
            {
                if (!this.suggestions.length && _.isUndefined(suggestions)) {
                    if (this.options.showNoSuggestionNotice) {
                        this.noSuggestions();
                    } else {
                        this.hide();
                    }
                    return;
                }

                if (!_.isUndefined(suggestions)) {
                    this.suggestions = suggestions;
                }

                var that                   = this,
                    options                = that.options,
                    groupBy                = options.groupBy,
                    formatResult           = options.formatResult,
                    value                  = that.getQuery(that.currentValue),
                    className              = that.classes.suggestion,
                    classSelected          = that.classes.selected,
                    container              = $(that.suggestionsContainer),
                    noSuggestionsContainer = $(that.noSuggestionsContainer),
                    beforeRender           = options.beforeRender,
                    html                   = '',
                    category,
                    formatGroup            = function (suggestion, index)
                    {
                        var currentCategory = suggestion.data[groupBy];

                        if (category === currentCategory) {
                            return '';
                        }

                        category = currentCategory;

                        return options.formatGroup(suggestion, category);
                    };

                if (options.triggerSelectOnValidInput && that.isExactMatch(value)) {
                    that.select(0);
                    return;
                }

                // Build suggestions inner HTML:
                $.each(this.suggestions, function (i, suggestion)
                {
                    if (groupBy) {
                        html += formatGroup(suggestion, value, i);
                    }

                    html += '<div class="' + className + '" data-index="' + i + '">' +
                            formatResult(suggestion, value, i) +
                            '</div>';
                });

                this.adjustContainerWidth();

                noSuggestionsContainer.detach();
                container.html(html);

                if ($.isFunction(beforeRender)) {
                    beforeRender.call(that.element, container, that.suggestions);
                }

                that.fixPosition();
                container.slideDown(275, function ()
                {
                    this.classList.add('autocomplete-suggestions--is-open');

                    // Dispatch Event.
                    DL.Utils.Events.dispatchEvent('autocomplete-open', that.element);
                });

                // Select first value by default:
                if (options.autoSelectFirst) {
                    that.selectedIndex = 0;
                    container.scrollTop(0);
                    container.children('.' + className).first().addClass(classSelected);
                }

                that.visible = true;
                that.findBestHint();
            },
        });

        /**
         * Autocomplete
         *
         * @since 1.0.0
         */
        DL.Autocomplete = {

            /**
             * List
             *
             * Retrieve the list of the autocomplete values
             *
             * @since 1.0.0
             *
             * @return void
             */
            list: function ()
            {
                $.post(this.ajaxUrl, this.args, this.act);
            },

            /**
             * Clean Data
             *
             * @since 1.0.0
             *
             * @param json
             */
            cleanJsonData: function (json)
            {
                return _.map(json, function (item)
                {
                    // The value key may be evaluated not as string.
                    // Consider numeric values.
                    item.value += '';

                    return item;
                });
            },

            /**
             * Remove items with type of type
             *
             * @since 1.0.0
             *
             * @param {String} type      The type to check against.
             * @param {*}      container The container from which remove the items.
             */
            removeTypeFromJson: function (type, container)
            {
                return container.filter(function (item)
                {
                    return (type !== item.data.type);
                });
            },

            /**
             * Build the autocomplete
             *
             * @since 1.0.0
             *
             * @param data The data retrieved by the server
             */
            act: function (data)
            {
                if (_.isUndefined(data)) {
                    return;
                }

                // @todo Need a further investigation, seems that data is send twice.
                if (_.isUndefined(data.data)) {
                    return;
                }

                // Get the input element.
                try {
                    // Parse and store the json data for once.
                    var jsonData         = JSON.parse(data.data);
                    jsonData.suggestions = this.cleanJsonData(jsonData.suggestions);
                    jsonData.initial     = this.cleanJsonData(jsonData.initial);

                    // Remove terms from the list if search form is set to `combo`.
                    if ('term' === this.field.getAttribute('data-exclude')) {
                        jsonData.initial     = this.removeTypeFromJson('term', jsonData.initial);
                        jsonData.suggestions = this.removeTypeFromJson('term', jsonData.suggestions);
                    }

                    // Create the autocomplete.
                    $(this.field).autocomplete({
                        lookup: jsonData.suggestions,
                        minChars: 3,
                        showNoSuggestionNotice: false,
                        triggerSelectOnValidInput: false,
                        appendTo: this.wrapper,
                        onHide: this.onHide,
                        beforeRender: this.beforeRender,
                        onHint: this.onHint,
                        onSelect: this.onSelect,
                        formatResult: this.formatResult,
                        initialSuggestions: jsonData.initial,
                    });
                } catch (e) {
                    ('dev' === window.dllocalized.env) && console.warn(e);

                    return false;
                }
            },

            /**
             * On Hide
             *
             * @since 1.0.0
             */
            onHide: function ()
            {
                DL.Utils.Events.dispatchEvent('autocomplete-on-hide', this.field);
            },

            /**
             * Before Render
             *
             * @since 1.0.0
             */
            beforeRender: function ()
            {
                DL.Utils.Events.dispatchEvent('autocomplete-on-open', this.field);
            },

            /**
             * On Hint
             *
             * @since 1.0.0
             *
             * @param hint
             */
            onHint: function (hint)
            {
                this.hintEl.innerHTML = hint;
            },

            /**
             * On Select
             *
             * @since 1.0.0
             *
             * @param suggestion
             */
            onSelect: function (suggestion)
            {
                if (typeof suggestion.data !== "undefined") {

                    var form = this.field.form;

                    if (!form.querySelector('#dlautocomplete_context')) {
                        var input = document.createElement('input');
                        input.setAttribute('name', 'dlautocomplete_context');
                        input.setAttribute('id', 'dlautocomplete_context');
                        input.setAttribute('type', 'hidden');
                        form.appendChild(input);
                    }

                    if (!form.querySelector('#qibla_event_categories_filter')) {
                        input = document.createElement('input');
                        input.setAttribute('name', 'qibla_event_categories_filter');
                        input.setAttribute('id', 'qibla_event_categories_filter');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('value', suggestion.value);
                        form.appendChild(input);
                    }

                    // Set the newly action if a permalink is provided.
                    if (!_.isUndefined(suggestion.data.permalink)) {
                        form.setAttribute('action', suggestion.data.permalink);
                    }
                    // Append the data to the form.
                    form.querySelector('#dlautocomplete_context').value = JSON.stringify(suggestion.data);
                }
            },

            /**
             * Format Result
             *
             * @since 1.0.0
             *
             * @param suggestion
             * @param currentValue
             * @returns {string}
             */
            formatResult: function (suggestion, currentValue)
            {
                var pattern, suggestionText = suggestion.value;
                // Do not replace anything if the current value is empty
                if (currentValue) {
                    pattern        = '(' + $.Autocomplete.utils.escapeRegExChars(currentValue) + ')';
                    suggestionText = suggestion.value
                                               .replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>')
                                               .replace(/&/g, '&amp;')
                                               .replace(/</g, '&lt;')
                                               .replace(/>/g, '&gt;')
                                               .replace(/"/g, '&quot;')
                                               .replace(/&lt;(\/?strong)&gt;/g, '<$1>');
                }

                // Create the markup for the icon of the current suggestion.
                // Check for the icon html classes within the object and fallback to la-check.
                var iconClassAttribute = !_.isUndefined(suggestion.data.icon) ?
                    suggestion.data.icon.icon_html_class :
                    'la la-check';

                // Add the icon.
                var suggestionMarkup = '<i class="' + iconClassAttribute + '"></i>';
                // Wrap the suggestion.
                suggestionMarkup += '<span class="suggestion__content">' + suggestionText + '</span>';

                return suggestionMarkup;
            },

            /**
             * Create Hint Element
             *
             * @since 1.0.0
             */
            createHintEl: function ()
            {
                this.hintEl = document.createElement('span');
                this.hintEl.setAttribute('class', 'hint');
                // Append the hint after the input.
                this.wrapper.insertBefore(this.hintEl, this.field.nextElementSibling);
                // Set the input background to transparent
                // so hint can be visible and input don't overlap the hint value.
                this.wrapper.querySelector('input').style.background = 'transparent';
            },

            /**
             * Init
             *
             * @since 1.0.0
             *
             * @returns {DL}
             */
            init: function ()
            {
                // Create the hint element if not exists yet.
                if (!this.wrapper.querySelector('.hint')) {
                    this.createHintEl();
                }

                return this;
            },

            /**
             * Construct
             *
             * @since 1.0.0
             *
             * @param field  The field on which use the autocomplete.
             *
             * @returns {DL} This for chaining
             */
            construct: function (field, data)
            {
                _.bindAll(
                    this,
                    'list',
                    'act',
                    'onHide',
                    'beforeRender',
                    'onHint',
                    'onSelect',
                    'formatResult',
                    'createHintEl',
                    'removeTypeFromJson'
                );

                if (!field) {
                    return;
                }

                this.field   = field;
                this.wrapper = field.parentNode;
                this.args    = {
                    autocomplete: 1,
                    action: 'get',
                    data: data,
                    dlajax_action: 'autocomplete',
                    dataType: 'json',
                    timeout: 300
                };
                this.ajaxUrl = window.dllocalized.site_url + '/index.php';

                return this;
            }
        };

        /**
         * Autocomplete Factory
         *
         * @since 1.0.0
         *
         * @param {HTMLElement} field The field element for which build the autocomplete.
         * @param {string}      type  The type of the autocomplete data to retrieve.
         *
         * @returns {*} Autocomplete instance
         */
        DL.AutocompleteFactory = function (field, type)
        {
            return Object.create(DL.Autocomplete).construct(field, type);
        };

        window.addEventListener('load', function ()
        {
            _.forEach(document.querySelectorAll('.use-autocomplete'), function (field)
            {
                // Get type.
                var data     = field.getAttribute('data-autocomplete'),
                    instance = DL.AutocompleteFactory(field, data);

                instance && instance.init().list();
            });
        });
    }(window._, window.jQuery, window.DL, window.dllocalized)
);
