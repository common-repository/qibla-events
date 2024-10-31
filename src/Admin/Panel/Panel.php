<?php
/**
 * Class Panel
 *
 * @since      1.0.0
 * @package    QiblaEvents\Admin\Panel
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Guido Scialfa <dev@guidoscialfa.com>
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

namespace QiblaEvents\Admin\Panel;

use \QiblaEvents\Functions as F;

/**
 * Class Panel
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Panel
{
    /**
     * Panel Title
     *
     * @since  1.0.0
     *
     * @var string The title of the panel
     */
    protected $title;

    /**
     * Panel Navigation
     *
     * @since  1.0.0
     *
     * @var Navigation The navigation instance for the panel
     */
    protected $navigation;

    /**
     * Construct
     *
     * @since  1.0.0
     *
     * @param string     $title      The title of the panel.
     * @param Navigation $navigation A Navigation instance for this panel.
     */
    public function __construct($title, Navigation $navigation)
    {
        $this->title      = $title;
        $this->navigation = $navigation;
    }

    /**
     * Header
     *
     * @since  1.0.0
     *
     * @return void
     */
    protected function header()
    {
        ?>
        <header class="dm-panel__header">
            <h1 class="dm-panel__title"><?php echo esc_html($this->title) ?></h1>
        </header>
        <?php
        $this->navigation->render();
    }

    /**
     * Footer
     *
     * @since  1.0.0
     *
     * @return void
     */
    protected function footer()
    {
        ?>
        <footer class="dm-panel__footer">
            <?php echo sprintf(
                '<p>%s <a href="%s">&#9733;&#9733;&#9733;&#9733;&#9733;</a> %s</p>',
                esc_html__('If you like Qibla Events Plugin please leave us a rating. ', 'qibla-events'),
                esc_url('https://wordpress.org/support/plugin/qibla-events/reviews/'),
                esc_html__('A huge thank you in advance!', 'qibla-events')
            ); ?>
        </footer>
        <?php
    }

    /**
     * Render
     *
     * @since  1.0.0
     *
     * @param mixed $mainContent A instance of a class that represent the main content of the panel.
     *                           The class must include the __toString method.
     *
     * @return void
     */
    public function render($mainContent)
    {
        ?>
        <div class="dm-panel">
            <div class="dm-panel__wrapper">
                <?php $this->header() ?>
                <div class="dm-panel__content">
                    <?php
                    // @codingStandardsIgnoreLine
                    echo F\ksesPost($mainContent); ?>
                </div>
            </div>
            <?php $this->footer() ?>
        </div>
        <?php
    }
}
