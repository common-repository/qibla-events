/**
 * Media Uploader
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

;(
    function (_, DlMedia, wp)
    {
        "use strict";

        var frame,
            inputs = document.querySelectorAll('[data-type="media-uploader"]');

        if (!inputs.length) {
            return;
        }

        /**
         * In Gallery
         *
         * @since 1.0.0
         *
         * @param attachment
         * @param input
         * @returns {number|Number}
         */
        function inGallery(attachment, input)
        {
            return -1 !== _.indexOf(attachment.id, getGallery(input));
        }

        /**
         * Retrieve the Gallery
         *
         * @since 1.0.0
         *
         * @param input
         * @returns {Array}
         */
        function getGallery(input)
        {
            var g = input.getAttribute('value');
            if (!g) {
                return [];
            }

            var list = [];
            _.forEach(g.split(','), function (id)
            {
                list.push(parseInt(id));
            });

            return list;
        }

        /**
         * Set Remove Btn
         *
         * Create the Html for the button and set the event listener.
         *
         * @since 1.0.0
         *
         * @param wrapper The wrapper element under which the button will be inserted
         * @param input The current related input
         */
        function setRemoveBtn(wrapper, input)
        {
            var rBtn = document.createElement('a');
            rBtn.classList.add('dl-media-rm-btn');
            rBtn.setAttribute('data-dlmime', 'image');
            rBtn.innerHTML = '<i class="la la-close"></i><span class="dl-media-rm-btn__label">Remove</span>';

            wrapper.appendChild(rBtn);

            rBtn.addEventListener('click', function (e)
            {
                removeAttachment.call(rBtn, e, input);
            });
        }

        /**
         * Build the Image Element
         *
         * @since 1.0.0
         *
         * @param attachment The json attachment object.
         * @param wrapper    The jquery element that is parent of the element where append the image.
         * @param isMultiple If the current input media is for multiple files or not.
         */
        function buildImageElement(attachment, wrapper, isMultiple, input)
        {
            if (typeof attachment !== 'object') {
                return;
            }

            var wrapperSelector = isMultiple ? '.dl-media-gallery' : '.dl-media-img-wrapper',
                imageWrapper    = wrapper.querySelector(wrapperSelector);

            // Create the wrapper for the multiple images if not exits.
            if (!imageWrapper) {
                imageWrapper = document.createElement('div');
                imageWrapper.classList.add('dl-media-gallery');
                wrapper.appendChild(imageWrapper);
            }

            if (!isMultiple && imageWrapper) {
                imageWrapper.remove();
                imageWrapper = null;
            }

            if (!imageWrapper || isMultiple) {
                imageWrapper = document.createElement('div');
                imageWrapper.classList.add('dl-media-img-wrapper');
            }

            imageWrapper.innerHTML = '<img class="dl-media-attachment" data-id="' + attachment.id + '" src="' + attachment.url + '" alt="" style="max-width:100%; width:150px" />';

            if (isMultiple) {
                wrapper.querySelector(wrapperSelector).appendChild(imageWrapper);
            } else {
                wrapper.appendChild(imageWrapper);
            }

            setRemoveBtn(imageWrapper, input);
        }

        /**
         * Add Attachment
         *
         * @since 1.0.0
         *
         * @param e The event object
         * @param currInput The current input element associated to the attachment/s
         */
        function addAttachment(e, currInput)
        {
            e.preventDefault();
            e.stopPropagation();

            var isMultiple = ('yes' === this.getAttribute('data-multiple')),
                // this is the btn that open the frame on click.
                wrapper    = this.parentElement;

            // Build the media frame based on input type.
            if (isMultiple) {
                frame = new DlMedia.Gallery();
            } else {
                frame = wp.media({
                    title: this.getAttribute('data-mediatitle'),
                    multiple: false
                });
            }

            // Show the Image after selected.
            frame.on('select', function ()
            {
                var attachments;

                if (isMultiple) {
                    attachments = frame.state().get('library').toJSON();
                } else {
                    attachments = [frame.state().get('selection').first().toJSON()];
                }

                _.forEach(attachments, function (attachment)
                {
                    // Perform the correct operation based on media handler.
                    switch (this.getAttribute('data-dlmime')) {
                        case 'image':
                            var value = attachment.id;
                            // Merge the new value/s with the previous ones.
                            if (isMultiple) {
                                var values = currInput.getAttribute('value');
                                values     = _.unique(_.union(values.split(','), [value]));
                                value      = _.filter(values, function (val)
                                {
                                    return parseInt(val);
                                });

                                // Don't add the html element if exists within the gallery.
                                if (!inGallery(attachment, currInput)) {
                                    buildImageElement(attachment, wrapper, isMultiple, currInput);
                                }

                                value = value.join(',');
                            } else {
                                buildImageElement(attachment, wrapper, isMultiple, currInput);
                            }

                            // Update the input type with the ID/s of the attachment.
                            currInput.setAttribute('value', value);

                            if (!isMultiple) {
                                this.style.display = 'none';
                            }
                            break;

                        default :
                            break;
                    }
                }.bind(this));
            }.bind(this));

            // Let's open the frame.
            frame.open();
        }

        /**
         * Remove Attachment
         *
         * @since 1.0.0
         */
        function removeAttachment(e, input)
        {
            e.preventDefault();
            e.stopImmediatePropagation();

            var isMultiple = ('yes' === e.target.getAttribute('data-multiple'));

            switch (this.getAttribute('data-dlmime')) {
                case 'image':
                    // The image is the element above the remove btn.
                    var currAttach = e.target.previousElementSibling;
                    if (!currAttach) {
                        break;
                    }

                    var inputValues = input.getAttribute('value').split(','),
                        index       = inputValues.indexOf(currAttach.getAttribute('data-id'));

                    if (-1 !== index) {
                        // Update the status of the input value by remove the current attachment ID.
                        inputValues.splice(index, 1);
                    }

                    // Restore the value. If no IDs has been found it will be the same.
                    input.setAttribute('value', inputValues.join(','));
                    // Remove the attachment element.
                    this.parentElement.remove();

                    if (!isMultiple) {
                        input.nextElementSibling.style.display = 'block';
                    }
                    break;

                default:
                    break;
            }
        }

        // Initialize Inputs
        _.forEach(inputs, function (input)
        {
            // The add media button. This will open the media frame.
            var addBtn          = input.nextElementSibling;
            // Hide the input and btn based on input value.
            input.style.display = 'none';

            // Open the media
            addBtn.addEventListener('click', function (e)
            {
                addAttachment.call(this, e, input);
            });

            // Select the label to add the on click event.
            var label = document.querySelector('label[for="' + input.getAttribute('id') + '"]');
            if (label) {
                label.addEventListener('click', function (e)
                {
                    addAttachment.call(addBtn, e, input);
                });
            }

            // Set the remove attachment button for every image within the field.
            _.forEach(input.parentElement.querySelectorAll('.dl-media-img-wrapper'), function (wrapper)
            {
                setRemoveBtn(wrapper, input);
            });
        });

    }(_, DlMedia, window.wp)
);