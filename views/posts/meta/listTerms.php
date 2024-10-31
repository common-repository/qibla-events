<?php
/**
 * Post Terms List View
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

use QiblaEvents\Functions as F;
?>

<span class="screen-reader-text"><?php esc_html_e('Posted In: ', 'qibla-events') ?></span>
<?php
$links = '';
foreach ($data->terms as $term) :
    $links .= sprintf(
        '%1$s <a href="%2$s" class="%3$s">%4$s</a>',
        $data->termsSeparator,
        esc_url(get_term_link($term->slug, $data->taxonomy)),
        F\getScopeClass('article', 'meta-link'),
        esc_html($term->name)
    );
endforeach;
// Remove the first and last comma separator.
echo trim($links, $data->termsSeparator);
