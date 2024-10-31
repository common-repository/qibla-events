<?php
/**
 * Post Title
 *
 * @license GNU General Public License, version 2
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
?>

<?php if ($data->postUrl) : ?>
<a <?php F\scopeClass('article', 'link') ?> href="<?php echo esc_url($data->postUrl) ?>">
    <?php endif; ?>

    <<?php echo tag_escape($data->titleTag); ?> class="<?php echo F\getScopeClass('article', 'title') ?>">

    <?php
    if (F\isWooCommerceActive() && 'product' === get_post_type($data->ID)) :
        woocommerce_template_loop_rating();
    endif;

    if (isset($data->icon)) : ?>
        <i class="dlarticle__icon <?php echo esc_attr(F\sanitizeHtmlClass($data->icon['icon_html_class'])) ?>"></i>
        <?php
    endif;

    /**
     * Before Post Title
     *
     * @since 1.0.0
     *
     * @param \stdClass $data The data for the current template.
     */
    do_action('qibla_events_before_post_title', $data);

    // The post title.
    echo F\ksesPost($data->title);

    /**
     * After Post Title
     *
     * @since 1.0.0
     *
     * @param \stdClass $data The data for the current template.
     */
    do_action('qibla_events_after_post_title', $data); ?>

</<?php echo tag_escape($data->titleTag) ?>>

<?php if ($data->postUrl) : ?>
    </a>
<?php endif; ?>
