<?php
/**
 * Post Thumbnail View
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

use QiblaEvents\Functions as F;

if ($data->thumbnail) : ?>
    <<?php echo tag_escape($data->containerTag) . ' ';
    F\scopeClass('thumbnail') ?>>
    <?php echo F\ksesImage($data->thumbnail) ?>
    <?php if ('yes' === apply_filters('qibla_show_post_thumbnail_caption', 'no')) : ?>
        <<?php echo tag_escape($data->captionTag) . ' ';
        F\scopeClass('thumbnail', 'caption') ?>>
        <?php echo F\ksesPost($data->caption) ?>
        </<?php echo tag_escape($data->captionTag) ?>>
    <?php endif; ?>
    </<?php echo tag_escape($data->containerTag) ?>>
<?php endif;
