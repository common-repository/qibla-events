<?php
/**
 * Post Adjacent Navigation View
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

use QiblaEvents\Functions as F;
?>

<div <?php F\scopeClass('adjacent-articles') ?>>
    <div <?php F\scopeClass('container', '', 'flex') ?>>

        <?php foreach ($data->adjacentPosts as $key => $curr) : if ($curr) : ?>
            <div <?php F\scopeClass('adjacent-articles', $key) ?>>
                <a href="<?php echo esc_url(get_permalink($curr)) ?>"
                    <?php F\scopeClass('adjacent-articles', 'link') ?>>

                    <?php F\thePostThumbnailTmpl($curr, 'qibla_thumbnail', true, '') ?>

                    <span <?php F\scopeClass('adjacent-articles', 'labels') ?>>
                        <span <?php F\scopeClass('adjacent-articles', 'label') ?>>
                            <?php printf(esc_html__('%s post', 'qibla-events'), ucfirst($key)) ?>
                        </span>

                        <span <?php F\scopeClass('adjacent-articles', 'post-title') ?>>
                            <?php echo esc_html(sanitize_text_field($curr->post_title)) ?>
                        </span>
                    </span>
                </a>
            </div>
        <?php endif; endforeach; ?>

    </div>
</div>