<?php
/**
 * eventsSidebarCard.php
 *
 * @since      1.0.0
 * @package    ${NAMESPACE}
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

use QiblaEvents\Functions as F;


// Event date and time.
if (isset($data->eventsDateIn) && $data->eventsDateIn) : ?>
    <div class="dlevents__card">
        <h4 class="dlevents__card--title"><?php echo $data->title; ?></h4>
        <ul class="<?php echo esc_attr(F\sanitizeHtmlClass(F\getScopeClass('events', 'items'))); ?>">
            <?php
            /**
             * Before Time
             *
             * @since 1.0.0
             */
            do_action('qibla_events_before_time_sidebar_card')
            ?>
            <li class="dlevents__items--dates">
                <i class="la la-calendar"></i>
                <?php if (! $data->oneDate) {
                    echo esc_attr_x('From', 'data-in', 'qibla-events');
                } ?>
                <time class="dlarticle__times-in" datetime="<?php echo esc_attr($data->eventsDateTimeIn); ?>">
                    <?php echo esc_html($data->eventsDateIn); ?>
                </time>
                <?php if ($data->eventsDateOut && false === $data->oneDate) :
                    echo esc_attr_x('to', 'data-out', 'qibla-events'); ?>
                    <time class="dlarticle__times-out" datetime="<?php echo esc_attr($data->eventsDateTimeOut); ?>">
                        <?php echo esc_html($data->eventsDateOut); ?>
                    </time>
                <?php endif; ?>

                <?php if (isset($data->timeStart) && $data->timeStart) :
                    printf('%s', esc_html($data->timeStart));
                endif; ?>

                <?php if (isset($data->timeEnd) && $data->timeEnd) :
                    printf('- %s', esc_html($data->timeEnd));
                endif; ?>
            </li>
            <?php
            /**
             * After Time
             *
             * @since 1.0.0
             */
            do_action('qibla_events_events_after_time_sidebar_card')
            ?>
        </ul>
    </div>

<?php endif; ?>