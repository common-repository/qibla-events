<?php
/**
 * Content Not Found Listings
 *
 * @since 1.0.0
 *
 * @license GNU General Public License, version 2
 *
 *    This program is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU General Public License
 *    as published by the Free Software Foundation; either version 2
 *    of the License, or (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

use QiblaEvents\Functions as F;
?>

<section <?php F\scopeClass('nocontent-found-listings') ?>>
    <h1 <?php F\scopeClass('nocontent-found-listings', 'title') ?>>
        <?php esc_html_e('Sorry, no posts matched your criteria.', 'qibla-events'); ?>
    </h1>

    <?php echo do_shortcode(
        '[dl_button label="' .
        esc_html__('Restart the search', 'qibla-events') . '" url="' .
        esc_url(get_post_type_archive_link('events')) .
        '" icon_after="la-long-arrow-right" style="wide"]'
    ) ?>
</section>