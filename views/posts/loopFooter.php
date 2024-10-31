<?php
/**
 * loopFooter.php
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

$colClass = isset($data->eventsDateEnd) && ! $data->equalDate ? ' multi-dates' : '';

if ($data->address || $data->eventsDateStart) : ?>
    <footer class="dlarticle__meta">
        <?php if ($data->eventsDateStart) : ?>
            <div class="dlarticle__meta--time <?php if (! isset($data->address) || '' === $data->address) : ?>no-address<?php endif; ?>">
                <time class="dlarticle__meta--timein<?php echo esc_attr($colClass); ?>"
                      datetime="<?php echo esc_attr($data->eventsDateStart); ?>">
                    <b class="screen-reader-text"><?php echo sprintf('%s',
                            esc_html__('Event date', 'qibla-events')); ?></b>
                    <span class="dlarticle__meta--day">
                        <?php if (isset($data->eventsDateEnd) &&
                                  $data->eventsDateEndDay !== $data->eventsDateStartDay ||
                                  ! $data->equalDate) : ?>
                            <?php echo sprintf('%s-%s',
                                esc_attr($data->eventsDateStartDay),
                                esc_attr($data->eventsDateEndDay)
                            ); ?>
                        <?php else: ?>
                            <?php echo esc_attr($data->eventsDateStartDay); ?>
                        <?php endif; ?>
                    </span>
                    <span class="dlarticle__meta--mouth-day">
                        <span class="dlarticle__meta--mouth">
                            <?php if ($data->eventsDateEndMouth &&
                                      $data->eventsDateEndMouth !== $data->eventsDateStartMouth) : ?>
                                <?php echo sprintf('%s-%s',
                                    esc_attr($data->eventsDateStartMouth),
                                    esc_attr($data->eventsDateEndMouth)
                                ); ?>
                            <?php else: ?>
                                <?php echo esc_attr($data->eventsDateStartMouth); ?>
                            <?php endif; ?>
                        </span>
                        <?php if (isset($data->eventsDateEnd) && $data->equalDate) : ?>
                            <span class="dlarticle__meta--day-text">
                            <?php echo esc_attr($data->eventsDateStartDayText); ?>
                        </span>
                        <?php endif; ?>
                    </span>
                </time>
            </div>
        <?php endif; ?>
        <?php if ($data->address) : ?>
            <div class="dlarticle__meta--address">
                <?php echo F\ksesPost($data->address); ?>
            </div>
        <?php endif; ?>
    </footer>
<?php endif; ?>