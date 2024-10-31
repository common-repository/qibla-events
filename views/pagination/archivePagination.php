<?php
use QiblaEvents\Functions as F;

/**
 * View pagination
 *
 * @since   1.0.0
 * @package Qibla\Views
 */

if ($data->list) : ?>
    <nav <?php F\scopeClass('pagination') ?>>
        <p class="screen-reader-text"><?php esc_html_e('Pages:', 'qibla-events') ?></p>
        <ul <?php F\ScopeClass('pagination', 'list') ?>>
            <?php foreach ($data->list as $item) : ?>
                <li <?php F\ScopeClass('pagination', 'item') ?>>
                    <?php echo F\ksesPost($item) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>