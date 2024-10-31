<?php

namespace QiblaEvents\Review;

use QiblaEvents\Functions as F;
use QiblaEvents\Form\Interfaces\Validators;
use QiblaEvents\Form\Validate;

/**
 * Store Review
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Review
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    GNU General Public License, version 2
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
 * Class ReviewFieldsStore
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Review
 */
class ReviewFieldsStore
{
    /**
     * The fields to store
     *
     * @since 1.0.0
     *
     * @var array The list of the fields to store
     */
    protected $fields;

    /**
     * Validator
     *
     * @since 1.0.0
     *
     * @var Validators The validator to validate the form data
     */
    protected $validator;

    /**
     * The comment
     *
     * @since 1.0.0
     *
     * @var \WP_Comment The comment related with the review
     */
    protected $comment;

    /**
     * Get the internal fields
     *
     * The field we use are the Review fields that include internally the real field object.
     * To work with data we need to get the real fields.
     *
     * @since 1.0.0
     *
     * @return array The list of the real fields
     */
    protected function getRealFields()
    {
        $fields = array();
        foreach ($this->fields as $field) {
            $fields[] = $field->getField();
        }

        return $fields;
    }

    /**
     * Validate the fields
     *
     * @since 1.0.0
     *
     * @return mixed Whatever the Validators::validate method return
     */
    protected function validate()
    {
        // Validate and return the response.
        return $this->validator->validate($this->getRealFields());
    }

    /**
     * Store
     *
     * @since 1.0.0
     *
     * @uses   add_comment_meta() to store the meta data.
     *
     * @param array $data The data to store as comment meta.
     *
     * return void
     */
    protected function store(array $data)
    {
        foreach ($data as $key => $value) {
            add_comment_meta($this->comment->comment_ID, '_' . $key, $value, true);
        }
    }

    /**
     * ReviewFieldsStore constructor
     *
     * @uses  get_comment() To retrieve the comment object from the ID.
     *
     * @since 1.0.0
     *
     * @param array      $fields    The field to store.
     * @param Validators $validator The validator to validate the fields.
     * @param int        $commentID The comment ID related with the review.
     */
    public function __construct(array $fields, Validators $validator, $commentID)
    {
        $this->fields    = $fields;
        $this->validator = $validator;
        $this->comment   = get_comment($commentID);
    }

    /**
     * Process the request
     *
     * Process the request to save the data.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function processRequest()
    {
        // To know if the fields can be validate.
        $canValidate = false;

        if ($this->fields) :
            $canValidate = true;

            foreach ($this->fields as &$field) :
                $theField      = $field->getField();
                $metaKey       = '_' . $theField->getType()->getArg('name');
                $filter        = $theField->getType()->getArg('filter');
                $filter        = $filter ?: FILTER_DEFAULT;
                $filterOptions = $theField->getType()->getArg('filter_options');

                // Retrieve the value from the request.
                // @codingStandardsIgnoreLine
                $value = F\filterInput($_POST, $metaKey, $filter, $filterOptions);
                if ($value) {
                    // Set the value to the field, so later we can validate it.
                    $theField->getType()->setArg('value', $value);
                }
            endforeach;
        endif;

        if ($canValidate) {
            // Validate the fields.
            $response = $this->validate();

            if (! empty($response['valid'])) {
                // Store the fields.
                $this->store($response['valid']);
            }
        }
    }

    /**
     * Filter Callback
     *
     * @since 1.0.0
     *
     * @param int    $commentID The comment ID.
     * @param string $approved  The comment status.
     * @param mixed  $data      The data of the comment.
     *
     * @return void
     */
    public static function reviewFieldsStoreFilter($commentID, $approved, $data)
    {
        // The order of the fields decide the order in which they will be stored.
        $instance = new static(
            array(
                new RatingField(),
                new ReviewTitleField(),
            ),
            new Validate(),
            $commentID
        );

        // Process the request.
        $instance->processRequest();
    }
}
