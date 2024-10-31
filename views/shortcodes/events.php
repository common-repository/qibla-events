<?php
/**
 * Short-code events view
 *
 * @since      1.0.0
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

// Get page template.
$pageTemplate = get_page_template_slug(get_the_ID());
?>

<?php if ('boxed' === $data->layout) : ?>
    <section style="<?php echo $data->sectionStyle; ?>" class="dlsc-section-events-wrap"><div class="dlcontainer">
<?php endif; ?>
    <div class="dlsc-events dlsc-listings-type--<?php echo sanitize_key($data->postType) ?>">
        <div class="dlgrid">
            <?php
            global $post;
            foreach ($data->posts as $slug => $post) :
                setup_postdata($post);
                ?>
                <article id="post-<?php echo intval($post->ID) ?>" class="<?php echo esc_attr(F\sanitizeHtmlClass(array(
                    call_user_func_array('QiblaEvents\\Functions\\getScopeClass', $post->postClass),
                    $post->gridClass,
                ))) ?>">

                    <div class="dlarticle-card-box">
                        <?php if (! empty($post->postTitle) || ! empty($post->thumbnail)) : ?>
                            <header class="dlarticle__header">
                                <a class="dlarticle__link" href="<?php echo esc_url($post->permalink) ?>">
                                    <?php
                                    // Thumbnail.
                                    if ($post->thumbnail instanceof QiblaEvents\Template\TemplateInterface) :
                                        $post->thumbnail->tmpl($post->thumbnail->getData());
                                    endif;
                                    if ($post->postTitle) : ?>
                                        <h2 class="dlarticle__title">
                                            <?php if (isset($post->icon)) : ?>
                                                <i class="dlarticle__icon <?php echo esc_attr(F\sanitizeHtmlClass($post->icon['icon_html_class'])) ?>"
                                                   aria-hidden="true"></i>
                                            <?php endif; ?>
                                            <?php $post->isListings and \QiblaEvents\Review\AverageRating::averageRatingFilter(); ?>
                                            <?php echo esc_html(sanitize_text_field($post->postTitle)) ?>
                                        </h2>
                                    <?php endif; ?>
                                </a>
                                <?php
                                if (isset($post->buttonMeta) && is_array($post->buttonMeta)) {
                                    if ('' !== $post->buttonMeta['url'] && '' !== $post->buttonMeta['text']) {
                                        $btnUrl  = '' !== $post->buttonMeta['url'] ? $post->buttonMeta['url'] : '#';
                                        $btnText = '' !== $post->buttonMeta['text'] ? $post->buttonMeta['text'] :
                                            esc_html__('Placeholder text', 'qibla-events');
                                        $target  = 'off' === $post->buttonMeta['target'] ?
                                            ' target="_blank" rel="noopener"' : '';

                                        echo '<div class="dlevent-ticket-wrapper">';
                                        echo sprintf(
                                            '<a class="%s event-ticket-button" href="%s"%s>%s</a>',
                                            esc_attr(F\sanitizeHtmlClass($post->buttonMeta['btn_class'])),
                                            esc_url($btnUrl),
                                            esc_html($target),
                                            esc_html($btnText)
                                        );
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </header>
                        <?php endif; ?>
                        <?php if (isset($data->forceAnchor) && $data->forceAnchor) : ?>
                        <a class="dlarticle__link" href="<?php echo esc_url($post->permalink) ?>">
                            <?php endif; ?>
                            <?php if (! empty($post->subtitle)) : ?>
                                <p class="dlsubtitle">
                                    <?php echo esc_html(sanitize_text_field($post->subtitle)) ?>
                                </p>
                            <?php endif;
                            if ($post->address || $post->eventsDateStart) : ?>
                                <footer class="dlarticle__meta">
                                    <?php if ($post->eventsDateStart) : ?>
                                        <?php $colClass = isset($post->eventsDateEnd) && ! $post->equalDate ? ' multi-dates' : ''; ?>
                                        <div class="dlarticle__meta--time <?php if (! isset($post->address) || '' === $post->address) : ?>no-address<?php endif; ?>">
                                            <time class="dlarticle__meta--timein<?php echo esc_attr($colClass); ?>"
                                                  datetime="<?php echo esc_attr($post->eventsDateStart); ?>">
                                                <b class="screen-reader-text"><?php echo sprintf('%s',
                                                        esc_html__('Event date', 'qibla-events')); ?></b>
                                                <span class="dlarticle__meta--day">
                                                    <?php if (isset($post->eventsDateEnd) &&
                                                              $post->eventsDateEndDay !== $post->eventsDateStartDay ||
                                                              ! $post->equalDate) : ?>
                                                        <?php echo sprintf('%s-%s',
                                                            esc_attr($post->eventsDateStartDay),
                                                            esc_attr($post->eventsDateEndDay)
                                                        ); ?>
                                                    <?php else: ?>
                                                        <?php echo esc_attr($post->eventsDateStartDay); ?>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="dlarticle__meta--mouth-day">
                                                    <span class="dlarticle__meta--mouth">
                                                        <?php if ($post->eventsDateEndMouth &&
                                                                  $post->eventsDateEndMouth !== $post->eventsDateStartMouth) : ?>
                                                            <?php echo sprintf('%s-%s',
                                                                esc_attr($post->eventsDateStartMouth),
                                                                esc_attr($post->eventsDateEndMouth)
                                                            ); ?>
                                                        <?php else: ?>
                                                            <?php echo esc_attr($post->eventsDateStartMouth); ?>
                                                        <?php endif; ?>
                                                    </span>
                                                    <?php if (isset($post->eventsDateEnd) && $post->equalDate) : ?>
                                                        <span class="dlarticle__meta--day-text">
                                                            <?php echo esc_attr($post->eventsDateStartDayText); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </span>
                                            </time>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($post->address) : ?>
                                        <div class="dlarticle__meta--address">
                                            <?php echo F\ksesPost($post->address); ?>
                                        </div>
                                    <?php endif; ?>
                                </footer>
                            <?php endif; ?>
                            <?php if (isset($data->forceAnchor) && $data->forceAnchor) : ?>
                        </a>
                    <?php endif; ?>
                    </div>
                </article>
            <?php
            endforeach;
            wp_reset_postdata(); ?>
        </div>
    </div>
<?php if ('boxed' === $data->layout) : ?>
    </div></section>
<?php endif; ?>