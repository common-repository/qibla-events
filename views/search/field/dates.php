<?php
/**
 * dates
 *
 * @since 1.0.0
 * @author     alfiopiccione <alfio.piccione@gmail.com>
 * @copyright  Copyright (c) 2018, alfiopiccione
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 alfiopiccione <alfio.piccione@gmail.com>
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

use \QiblaEvents\Functions as F;

?>

<div class="dlsearch__input-wrapper">
    <?php
    // @codingStandardsIgnoreStart
    echo F\ksesPost($data->field);
    echo F\ksesPost($data->nonce);
    // @codingStandardsIgnoreEnd ?>
    <?php if (isset($data->taxonomy) && $data->taxonomy): ?>
        <input type="hidden" name="qibla_taxonomy_filter_taxonomy" value="<?php echo esc_attr($data->taxonomy) ?>"/>
    <?php endif; ?>
</div>