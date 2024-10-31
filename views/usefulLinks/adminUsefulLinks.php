<?php
/**
 * Admin Useful Links Page Content
 *
 * @since 1.0.0
 */
?>
<div class="wrap">
    <header>
        <h1><?php echo esc_html($data->pageTitle) ?></h1>
    </header>
    <?php
    /**
     * Page top hook
     */
    do_action('qibla_events_useful_link_top')?>
    <div id="useful-link-container">
        <div class="stuffbox doc-box">
            <div class="header">
                <div class="inside">
                    <h2 class="ul-title"><?php esc_html_e('Documentation', 'qibla-events'); ?></h2>
                    <a href="<?php echo esc_url($data->documentation_url); ?>"
                       class="button button-primary ul-button" target="_blank">
                        <?php esc_html_e('Read', 'qibla-events'); ?>
                    </a>
                </div>
            </div>
            <div class="inside text">
                <div class="ul-image documentation"
                     style="background-image: url(<?php echo esc_url($data->documentation_bg); ?>)">
                </div>
                <p><?php esc_html_e(
                        'Read the documentation before asking us anything! Most of the times the answer is already there.',
                        'qibla-events'
                    ); ?></p>
            </div>
        </div>
        <div class="stuffbox doc-box">
            <div class="header">
                <div class="inside">
                    <h2 class="ul-title"><?php esc_html_e('Shop Themes and Plugins', 'qibla-events'); ?></h2>
                    <a href="<?php echo esc_url($data->shop_url); ?>"
                       class="button button-primary ul-button" target="_blank">
                        <?php esc_html_e('Shop', 'qibla-events'); ?>
                    </a>
                </div>
            </div>
            <div class="inside text">
                <div class="ul-image shop"
                     style="background-image: url(<?php echo esc_url($data->shop_bg); ?>)">
                </div>
                <p><?php esc_html_e(
                        'Like what you see? Start shopping into our digital store.',
                        'qibla-events'
                    ); ?></p>
            </div>
        </div>
        <div class="stuffbox doc-box">
            <div class="header">
                <div class="inside">
                    <h2 class="ul-title">
                        <?php esc_html_e('Need a customization?', 'qibla-events'); ?>
                    </h2>
                    <a href="<?php echo esc_url($data->customization_url); ?>" target="_blank"
                       class="button button-primary ul-button">
                        <?php esc_html_e('Contact', 'qibla-events'); ?>
                    </a>
                </div>
            </div>
            <div class="inside">
                <div class="ul-image customization"
                     style="background-image: url(<?php echo esc_url($data->customization_bg); ?>)">
                </div>
                <p><?php esc_html_e(
                        'Do you need help for installing the theme or building a new feature? Please ask us, we can help you.',
                        'qibla-events'
                    ); ?></p>
            </div>
        </div>
        <div class="stuffbox doc-box">
            <div class="header">
                <div class="inside">
                    <h2 class="ul-title"><?php esc_html_e('Qibla2app', 'qibla-events'); ?></h2>
                    <a href="<?php echo esc_url($data->qibla2app_url); ?>" target="_blank"
                       class="button button-primary ul-button">
                        <?php esc_html_e('More', 'qibla-events'); ?>
                    </a>
                </div>
            </div>
            <div class="inside">
                <div class="ul-image qibla2app"
                     style="background-image: url(<?php echo esc_url($data->qibla2app_bg); ?>)">
                </div>
                <p><?php esc_html_e(
                        'Do you know that we are the only WordPress vendor that allows you to build a native app?',
                        'qibla-events'
                    ); ?></p>
            </div>
        </div>
    </div>
</div>
