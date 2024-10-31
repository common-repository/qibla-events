/**
 * Review Comments
 *
 * The header search is working in desktop and mobile. There are some differences between them.
 * First of all, the desktop version has a search navigation,
 *
 * @since 1.0.0
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

;(function (_, $, DL, dlreview)
{
    "use strict";

    /**
     * Review.
     *
     * Basic implementation just to give a better UX when listing author want to reply to a review.
     */
    DL.Review = {

        /**
         * Reply Form
         *
         * @since 1.0.0
         */
        ReplyForm: {
            /**
             * Show Cancel Reply
             *
             * The hide behavior is performed by comment-reply because the link use the same selector.
             * That's why isn't an hideCancelReply.
             *
             * @since 1.0.0
             *
             * @param element
             */
            showCancelReply: function (element)
            {
                var cancelReplyLink = element.querySelector('#cancel-comment-reply-link');

                cancelReplyLink.style.display = 'inline';
                // After showed up, let's add the listener to move the form in his previous position.
                DL.Utils.Events.addListener(cancelReplyLink, 'click', function (evt)
                {
                    evt.preventDefault();
                    evt.stopImmediatePropagation();

                    this.restoreForm(element);
                }.bind(this));
            },

            /**
             * Set Reply Labels
             *
             * @since 1.0.0
             *
             * @param form The form for what change the element labels.
             * @param container The container of the review.
             */
            setReplyLabels: function (form, container)
            {
                // Then change the element text and labels.
                var titleEl      = form.querySelector('#reply-title'),
                    titleSmallEl = titleEl.querySelector('small');

                titleEl.setAttribute('data-oldtext', titleEl.innerHTML);
                titleEl.innerHTML = window.dlreview.formLabels.replyTitle.replace(
                    '%s',
                    '<span class="u-highlight-text">' + container.querySelector('.comment-author').innerText + '</span>'
                );
                titleEl.appendChild(titleSmallEl);
                this.showCancelReply(form);

                var commentFormCommentEl = form.querySelector('.comment-form-comment label');
                commentFormCommentEl.setAttribute('data-oldtext', commentFormCommentEl.innerText);
                commentFormCommentEl.innerText = window.dlreview.formLabels.textAreaLabel;

                var submitEl = form.querySelector('input[type="submit"]');
                submitEl.setAttribute('data-oldtext', submitEl.value);
                submitEl.value = window.dlreview.formLabels.submitLabel;
            },

            /**
             * Restore Review Labels
             *
             * @since 1.0.0
             *
             * @param form
             */
            restoreReviewLabels: function (form)
            {
                // Then change the element text and labels.
                var titleEl       = form.querySelector('#reply-title');
                titleEl.innerHTML = titleEl.getAttribute('data-oldtext');

                var commentFormCommentEl       = form.querySelector('.comment-form-comment label');
                commentFormCommentEl.innerText = commentFormCommentEl.getAttribute('data-oldtext');

                var submitEl   = form.querySelector('input[type="submit"]');
                submitEl.value = submitEl.getAttribute('data-oldtext');
            },

            /**
             * Set Parent Comment Attribute Value
             *
             * @since 1.0.0
             *
             * @param {HtmlElement} form     The form from on which set the parent comment ID.
             * @param {int}         parentID The ID of the parent comment to set.
             *
             * @return void
             */
            setParentCommentAttributeValue: function (form, parentID)
            {
                parentID = parseInt(parentID);

                if (parentID) {
                    form.querySelector('#comment_parent').value = parentID;
                }
            },

            /**
             * Reset Parent Comment Attribute Value
             *
             * @since 1.0.0
             *
             * @param {HtmlElement} form The form on which reset the parent comment ID
             *
             * @return void
             */
            resetParentCommentAttributeValue: function (form)
            {
                form.querySelector('#comment_parent').value = 0;
            },

            /**
             * Move Form
             *
             * @since 1.0.0
             *
             * @param element The reply link element.
             */
            moveForm: function (element)
            {
                // Get the reference to the element where the form must be moved in.
                var refContainer = element.getAttribute('data-container');
                if (refContainer) {
                    refContainer = document.getElementById(refContainer);

                    if (refContainer.parentNode.querySelector('.comment-respond')) {
                        return;
                    }

                    // Get the form.
                    var form = document.querySelector('.comment-respond');

                    if (form && refContainer.parentNode.appendChild(form)) {
                        this.setReplyLabels(form, refContainer);
                        this.setParentCommentAttributeValue(form, element.getAttribute('data-id'));
                    }
                }
            },

            /**
             * Restore Form
             *
             * @since 1.0.0
             *
             * @param form The form to restore
             */
            restoreForm: function (form)
            {
                document.querySelector('.dlcomments').appendChild(form);
                this.restoreReviewLabels(form);
                this.resetParentCommentAttributeValue(form);
            },

            /**
             * Init
             *
             * @since 1.0.0
             *
             * @returns {DL.Review|boolean} for chaining or false if any comment reply link is not found.
             */
            init: function ()
            {
                var replyLinks = document.querySelectorAll('.comment-reply-link');
                if (!replyLinks.length) {
                    return false;
                }

                _.forEach(replyLinks, function (link)
                {
                    DL.Utils.Events.addListener(link, 'click', function (evt)
                    {
                        evt.preventDefault();
                        evt.stopPropagation();

                        this.moveForm(evt.target);
                    }.bind(this));
                }.bind(this));

                return this;
            },

            /**
             * Construct
             *
             * @since 1.0.0
             *
             * @returns {DL.Review} for chaining
             */
            construct: function ()
            {
                _.bindAll(
                    this,
                    'showCancelReply',
                    'setReplyLabels',
                    'restoreReviewLabels',
                    'setParentCommentAttributeValue',
                    'resetParentCommentAttributeValue',
                    'moveForm',
                    'restoreForm',
                    'init'
                );

                return this;
            }
        },

        /**
         * Rating
         *
         * @since 1.0.0
         */
        Rating: {
            /**
             * Hide Default Rating Fields
             *
             * @since 1.0.0
             *
             * @return void
             */
            hideDefaultRatingField: function ()
            {
                // @todo Find a more specific way to select the element.
                this.ratingList.style.display = 'none';
            },

            /**
             * Create Star Rating
             *
             * @since 1.0.0
             *
             * @returns {string}
             */
            createStarRating: function ()
            {
                if (!this.field.querySelector('.dlreview-form__rating-stars')) {
                    var stars = '<p class="dlreview-form__rating-stars stars selected">';

                    _.forEach(this.field.querySelectorAll('[name="qibla_mb_comment_rating"]'), function (el, index)
                    {
                        var attrClass = 'dlreviews__star' + (el.checked ? ' active' : '');

                        stars += '<a class="' + attrClass + '" href="#" data-value="' +
                                 el.getAttribute('value') + '">' + (++index) + '</a>';
                    });

                    stars += '</p>';

                    return stars;
                }
            },

            /**
             * Set Rating Value
             *
             * @since 1.0.0
             *
             * @param evt
             */
            setRatingValue: function (evt)
            {
                evt.preventDefault();
                evt.stopPropagation();

                this.stars.classList.add('selected');
                _.forEach(this.stars.querySelectorAll('.dlreviews__star'), function (el)
                {
                    el.classList.remove('active');
                });

                evt.target.classList.add('active');

                this.ratingList.querySelector('[value="' + evt.target.getAttribute('data-value') + '"]').checked = true;
            },

            /**
             * Init
             *
             * @since 1.0.0
             *
             * @returns {DL} The instance of the object for chaining
             */
            init: function ()
            {
                var stars = document.querySelector('.dlreview-form__rating-stars');
                // Set listener on stars hover to fill it or remove the fill.
                DL.Utils.Events.addListener(stars, 'mouseenter', function (evt)
                {
                    DL.Utils.Functions.classList(evt.target).remove('selected');
                });
                DL.Utils.Events.addListener(stars, 'mouseleave', function (evt)
                {
                    DL.Utils.Functions.classList(evt.target).add('selected');
                });

                // Set the value on click to each Stars.
                _.forEach(this.field.querySelectorAll('.dlreviews__star'), function (el)
                {
                    DL.Utils.Events.addListener(el, 'click', this.setRatingValue.bind(this));
                }.bind(this));

                return this;
            },

            /**
             * Construct
             *
             * @since 1.0.0
             *
             * @returns {*} False if fields cannot be found, this for chaining
             */
            construct: function ()
            {
                this.field = document.querySelector('.dlreview-form__rating');

                if (!this.field) {
                    return false;
                }

                this.ratingList = this.field.querySelector('ul');

                var markup = this.createStarRating();

                this.field.insertAdjacentHTML('beforeend', markup);
                this.stars = this.field.querySelector('.dlreview-form__rating-stars');

                this.hideDefaultRatingField();

                return this;
            }
        }
    };

    window.addEventListener('load', function ()
    {
        if (DL.Utils.Functions.classList(document.body).contains('dl-is-singular-listings')) {
            // The review form.
            DL.Review.ReplyForm.construct().init();

            // The rating field.
            if (DL.Review.Rating.construct()) {
                DL.Review.Rating.init();
            }
        }
    });

}(window._, window.jQuery, window.DL, window.dlreview));
