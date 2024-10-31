<?php
/**
 * Post Published date
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

use QiblaEvents\Functions as F;
?>

<span class="screen-reader-text"><?php echo esc_html($data->label) ?></span>

<?php if ($data->dateArchiveLink) : ?>
    <a href="<?php echo esc_url($data->dateArchiveLink) ?>" <?php F\scopeClass('article', 'meta-link') ?>>
<?php endif; ?>

    <time title="<?php echo esc_attr($data->titleAttr) ?>" datetime="<?php echo esc_attr($data->datetime) ?>">
        <?php echo esc_html($data->date) ?>
    </time>

<?php if ($data->dateArchiveLink) : ?>
    </a>
<?php endif; ?>