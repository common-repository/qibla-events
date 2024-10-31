<?php
/**
 * Post Excerpt
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

use QiblaEvents\Functions as F;
?>

<div <?php F\scopeClass('article', 'content') ?>>
    <?php if ('' === $data->postTitle) : ?>
    <a href="<?php echo esc_url($data->permalink) ?>">
        <?php
        endif;

        echo F\ksesPost($data->postContent);

        if ($data->moreLinkLabel && $data->postTitle) : ?>
            <a href="<?php echo esc_url($data->permalink) ?>" <?php F\scopeClass('article', 'more-link') ?>>
                <?php echo esc_html($data->moreLinkLabel) ?>
            </a>
            <?php
        endif;
        if ('' === $data->postTitle) : ?>
    </a>
<?php endif; ?>
</div>
