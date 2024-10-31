<?php
/**
 * LoopTemplate
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package   dreamlist-framework
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace QiblaEvents\Template;

use QiblaEvents\Functions as F;
use QiblaEvents\Plugin;

/**
 * Class LoopTemplate
 *
 * @since   1.0.0
 * @package QiblaEvents\Template
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class LoopTemplate implements TemplateInterface
{
    /**
     * Query
     *
     * @since 1.0.0
     *
     * @var \WP_Query The query to loop through
     */
    private $query;

    /**
     * View
     *
     * @since 1.0.0
     *
     * @var string The view to use as template
     */
    private $view;

    /**
     * LoopTemplate constructor
     *
     * @since 1.0.0
     */
    public function __construct(\WP_Query $query, $view)
    {
        $this->query = $query;
        $this->view  = $view;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return (object)array(
            'query' => $this->query,
        );
    }

    /**
     * @inheritDoc
     */
    public function tmpl(\stdClass $data)
    {
        ?>
        <div class="dlcontainer dlcontainer--flex">
            <div class="dlgrid">
                <?php
                while ($data->query->have_posts()) :
                    $data->query->the_post();
                    load_template(Plugin::getPluginDirPath(F\sanitizePath($this->view)), false);
                endwhile;

                wp_reset_query(); ?>
            </div>
        </div>
        <?php
    }

    /**
     * @inheritDoc
     */
    public static function template($object = null)
    {
        $instance = new self($object->query, $object->view);
        $instance->tmpl($instance->getData());
    }
}
