/**
 * Media
 *
 * This is an export of the Post object retrieved from media-views.js.
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

var DlMedia = DlMedia || {};

;(function (DlMedia, wp)
{
    'use strict';

    if (typeof wp.media === 'undefined') {
        return;
    }

    var Select  = wp.media.view.MediaFrame.Select,
        Library = wp.media.controller.Library,
        l10n    = wp.media.view.l10n;

    /**
     * Media Gallery
     *
     * @since 1.0.0
     */
    DlMedia.Gallery = Select.extend({
        initialize: function ()
        {
            _.defaults(this.options, {
                multiple: true,
                editing: false,
                state: 'gallery',
                metadata: {}
            });

            // Call 'initialize' directly on the parent class.
            Select.prototype.initialize.apply(this, arguments);
            this.createIframeStates();

        },

        /**
         * Create the default states.
         */
        createStates: function ()
        {
            var options = this.options;

            this.states.add([
                // Main states.
                new Library({
                    id: 'gallery',
                    title: l10n.createGalleryTitle,
                    priority: 40,
                    toolbar: 'main-gallery',
                    filterable: 'uploaded',
                    multiple: 'add',
                    editable: false,

                    library: wp.media.query(_.defaults({
                        type: 'image'
                    }, options.library))
                }),

                // Gallery states.
                new wp.media.controller.GalleryEdit({
                    library: options.selection,
                    editing: options.editing,
                    menu: 'gallery'
                }),

                new wp.media.controller.GalleryAdd(),
            ]);
        },

        bindHandlers: function ()
        {
            var handlers;

            Select.prototype.bindHandlers.apply(this, arguments);

            this.on('menu:create:gallery', this.createMenu, this);
            this.on('toolbar:create:main-gallery', this.createToolbar, this);

            handlers = {
                menu: {
                    'default': 'galleryMenu',
                    'gallery': 'galleryMenu',
                },

                toolbar: {
                    'main-gallery': 'mainGalleryToolbar',
                    'gallery-edit': 'galleryEditToolbar',
                    'gallery-add': 'galleryAddToolbar',
                }
            };

            _.each(handlers, function (regionHandlers, region)
            {
                _.each(regionHandlers, function (callback, handler)
                {
                    this.on(region + ':render:' + handler, this[callback], this);
                }, this);
            }, this);
        },

        /**
         * @param {wp.Backbone.View} view
         */
        galleryMenu: function (view)
        {
            var lastState = this.lastState(),
                previous  = lastState && lastState.id,
                frame     = this;

            view.set({
                cancel: {
                    text: l10n.cancelGalleryTitle,
                    priority: 20,
                    click: function ()
                    {
                        if (previous) {
                            frame.setState(previous);
                        } else {
                            frame.close();
                        }

                        // Keep focus inside media modal
                        // after canceling a gallery
                        this.controller.modal.focusManager.focus();
                    }
                },
                separateCancel: new wp.media.View({
                    className: 'separator',
                    priority: 40
                })
            });
        },

        // Toolbars

        /**
         * @param {wp.Backbone.View} view
         */
        selectionStatusToolbar: function (view)
        {
            var editable = this.state().get('editable');

            view.set('selection', new wp.media.view.Selection({
                controller: this,
                collection: this.state().get('selection'),
                priority: -40,

                // If the selection is editable, pass the callback to
                // switch the content mode.
                editable: editable && function ()
                {
                    this.controller.content.mode('edit-selection');
                }
            }).render());
        },

        /**
         * @param {wp.Backbone.View} view
         */
        mainGalleryToolbar: function (view)
        {
            var controller = this;

            this.selectionStatusToolbar(view);

            view.set('gallery', {
                style: 'primary',
                text: l10n.createNewGallery,
                priority: 60,
                requires: {selection: true},

                click: function ()
                {
                    var selection = controller.state().get('selection'),
                        edit      = controller.state('gallery-edit'),
                        models    = selection.where({type: 'image'});

                    edit.set('library', new wp.media.model.Selection(models, {
                        props: selection.props.toJSON(),
                        multiple: true
                    }));

                    this.controller.setState('gallery-edit');
                    // Have not found a way to hide it. :/
                    document.querySelector('.gallery-settings').style.display = 'none';

                    // Keep focus inside media modal
                    // after jumping to gallery view
                    this.controller.modal.focusManager.focus();
                }
            });
        },

        galleryEditToolbar: function ()
        {
            var editing = this.state().get('editing');
            this.toolbar.set(new wp.media.view.Toolbar({
                controller: this,
                items: {
                    insert: {
                        style: 'primary',
                        text: editing ? l10n.updateGallery : l10n.insertGallery,
                        priority: 80,
                        requires: {library: true},

                        /**
                         * @fires wp.media.controller.State#update
                         */
                        click: function ()
                        {
                            var controller = this.controller,
                                state      = controller.state();

                            controller.close();
                            state.trigger('select', state.get('library'));

                            // Restore and reset the default state.
                            controller.setState(controller.options.state);
                            controller.reset();
                        }
                    }
                }
            }));
        },

        galleryAddToolbar: function ()
        {
            this.toolbar.set(new wp.media.view.Toolbar({
                controller: this,
                items: {
                    insert: {
                        style: 'primary',
                        text: l10n.addToGallery,
                        priority: 80,
                        requires: {selection: true},

                        /**
                         * @fires wp.media.controller.State#reset
                         */
                        click: function ()
                        {
                            var controller = this.controller,
                                state      = controller.state(),
                                edit       = controller.state('gallery-edit');

                            edit.get('library').add(state.get('selection').models);
                            state.trigger('reset');
                            controller.setState('gallery-edit');
                        }
                    }
                }
            }));
        },
    });
}(DlMedia, window.wp));