<?php
/**
 * Post Author View
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

use QiblaEvents\Functions as F;
?>

<span <?php F\scopeClass('article', 'meta__label') ?>>
    <?php esc_html_e('Author: ', 'qibla-events') ?>
</span>
<a href="<?php echo esc_url($data->authorUrl) ?>" <?php F\scopeClass('article', 'meta__link') ?> rel="author">
    <?php echo esc_html($data->user->display_name) ?>
</a>
