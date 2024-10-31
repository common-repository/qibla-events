<?php
namespace QiblaEvents\Review;

use QiblaEvents\Form\Factories\FieldFactory;
use QiblaEvents\Form\Interfaces\Fields;
use QiblaEvents\Functions as F;

/**
 * AbstractReviewField
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
 * Class AbstractReviewField
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Review
 */
abstract class AbstractReviewField
{
    /**
     * The field
     *
     * @since 1.0.0
     *
     * @var Fields The internal field
     */
    protected $field;

    /**
     * Create the field
     *
     * @since 1.0.0
     *
     * @param array $args The arguments for create the field.
     *
     * @return mixed
     */
    protected function createField(array $args)
    {
        $fieldFactory = new FieldFactory();

        return $fieldFactory->base($args);
    }

    /**
     * AbstractReviewField constructor
     *
     * @since 1.0.0
     *
     * @param array $args The arguments to build the field.
     */
    public function __construct(array $args)
    {
        $this->field = $this->createField($args);
    }

    /**
     * Show the field
     *
     * @since 1.0.0
     *
     * @return void Echo the field.
     */
    public function showField()
    {
        echo F\ksesPost($this->field);
    }

    /**
     * Get the field
     *
     * @since 1.0.0
     *
     * @return Fields The internal field
     */
    public function getField()
    {
        return $this->field;
    }
}
