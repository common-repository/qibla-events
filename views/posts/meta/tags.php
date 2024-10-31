<?php
/**
 * Post Tags View
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

use QiblaEvents\Functions as F;

if (empty($data->tags) || is_wp_error($data->tags)) {
    return;
}
?>

<section <?php F\scopeClass('article', 'tags') ?>>
    <h5 <?php F\scopeClass('article', 'tags__title') ?>>
        <?php esc_html_e('Tags:', 'qibla-events'); ?>
    </h5>
    <ul <?php F\scopeClass('article', 'tags__list') ?>>
        <?php foreach ($data->tags as $tag) : ?>
            <li <?php F\scopeClass('article', 'tags__list__item') ?>>
                <a href="<?php echo esc_url(get_tag_link($tag)) ?>" <?php F\scopeClass('article', 'tags__link') ?>>
                    <?php echo esc_html($tag->name) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>