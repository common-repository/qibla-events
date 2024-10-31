<?php
/**
 * AlertUpdateMetaListings
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaEvents\Notice;

/**
 * Class AlertUpdateMetaListings
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class NoticeUpdateMetaListings
{
    /**
     * Notice
     *
     * @since 1.0.0
     */
    public static function noticeFilter()
    {
        // Here for BC.
        // @codingStandardsIgnoreLine.
        if ('1' !== \QiblaEvents\Functions\filterInput($_GET, 'uoml', FILTER_SANITIZE_NUMBER_INT)) {
            return;
        }

        delete_option('qibla_meta_locations_update');

        $invalid = get_option('qibla_meta_locations_no_updated', 'not_exists');
        if (empty($invalid)) {
            return;
        }

        if ('not_exists' === $invalid) {
            $invalid = array();
        }

        $count = wp_count_posts('events');
        $count = intval($count->publish) + intval($count->trash);

        if (! $count) {
            return;
        }

        ?>
        <section class="notice notice-error" style="padding: 1em; margin-left: 0;">
            <h1><?php esc_html_e('Qibla Update', 'qibla-events') ?></h1>

            <article>
                <h2 style="font-size: 1.25rem; margin-top: 0">
                    <?php esc_html_e(
                        'Qibla need to update the listings meta data in order to make the site work properly.',
                        'qibla-events'
                    ); ?>
                </h2>
                <p>
                    <?php echo \QiblaEvents\Functions\ksesPost(__(
                        'Since we introduced the Geolocation feature, qibla need to access to address, latitude and longitude data in a different manner. This is why we need to update the current information stored into the db. <br><br> Old data will not be deleted to ensure data re-storing. By the way with the new update all new listings will save the address information only in the new format.',
                        'qibla-events'
                    )); ?>
                </p>

                <p>
                    <?php echo \QiblaEvents\Functions\ksesPost(sprintf(__(
                        'You have %s listings stored.', 'qibla-events'
                    ), '<strong>' . $count . '</strong>')); ?>

                    <?php if (count($invalid)) {
                        echo \QiblaEvents\Functions\ksesPost(sprintf(__(
                            'And %s need a manual update.', 'qibla-events'
                        ), '<strong>' . count($invalid) . '</strong>'));
                    } ?>

                    <?php if (500 < $count) : ?>
                        <span><?php _e('This may take a while. Please, be patient.', 'qibla-events') ?></span>
                    <?php endif; ?>
                </p>

                <?php if (count($invalid)) : ?>
                    <p>
                        <?php esc_html_e(
                            'For some reason one or more listings locations cannot be updated correctly and need manual action by you.',
                            'qibla-events'
                        ) ?>
                    </p>

                    <p>
                        <?php esc_html_e(
                            'To prevent to show a lots of content here the first 10.',
                            'qibla-events'
                        ) ?>
                    </p>
                    <ul>
                        <?php foreach (array_slice($invalid, 0, 10) as $item) : ?>
                            <li>
                                <a href="<?php echo esc_url(admin_url("/post.php?post={$item['id']}&action=edit")) ?>">
                                    <?php echo sanitize_text_field($item['title']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php
                endif;

                $trigger = new \QiblaEvents\Task\TaskRefactorListingsMetaLocationTriggerTemplate();
                $trigger->tmpl($trigger->getData());
                ?>
            </article>
        </section>
        <?php
    }
}
