<?php

namespace QiblaEvents\Admin\Metabox;

use QiblaEvents\Plugin;

/**
 * Review
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package    QiblaEvents\Admin\Comments
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
 * Class Review
 *
 * @since 1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Admin\Comments
 */
class Review extends AbstractMetaboxForm
{
    /**
     * The Comment
     *
     * @since 1.0.0
     *
     * @var \WP_Comment The comment related with the review
     */
    protected $comment;

    /**
     * Review Constructor
     *
     * @since 1.0.0
     *
     * @param \WP_Comment $comment The comment related with the review.
     * @param array       $args    The list of the arguments for this meta-box.
     */
    public function __construct(\WP_Comment $comment, array $args = array())
    {
        // Set the comment object.
        $this->comment = $comment;

        parent::__construct(wp_parse_args($args, array(
            'id'            => 'review',
            'title'         => esc_html__('Review', 'qibla-events'),
            'callback'      => array($this, 'callBack'),
            'screen'        => array('comment'),
            'context'       => 'normal',
            'priority'      => 'high',
            'callback_args' => array(),
        )));

        parent::setFields(include Plugin::getPluginDirPath('/inc/metaboxFields/reviewsFields.php'));
    }
}
