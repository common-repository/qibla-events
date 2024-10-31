<?php
/**
 * Kses Functions
 *
 * @package QiblaEvents\Functions
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 *
 * @license GPL 2
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

namespace QiblaEvents\Functions;

/**
 * Kses Image
 *
 * This is a wrapper function for wp_kses that allow only specific  html attributes for images.
 *
 * @uses  wp_kses()
 *
 * @since 1.0.0
 *
 * @param string $img The image string to process.
 *
 * @return string The processed string containing only the allowed attributes
 */
function ksesImage($img)
{
    /**
     * Filter Kses Image
     *
     * @since 1.0.0
     *
     * @param array $list The list of the allowed attributes
     */
    $attrs = apply_filters('qibla_events_kses_image_allowed_attrs', array(
        'img' => array(
            'src'      => true,
            'srcset'   => true,
            'sizes'    => true,
            'class'    => true,
            'id'       => true,
            'width'    => true,
            'height'   => true,
            'alt'      => true,
            'longdesc' => true,
            'usemap'   => true,
            'align'    => true,
            'border'   => true,
            'hspace'   => true,
            'vspace'   => true,
            'data-id'  => true,
        ),
    ));

    return wp_kses($img, $attrs);
}

/**
 * Sanitize content for allowed HTML tags for post content.
 *
 * Post content refers to the page contents of the 'post' type and not $_POST
 * data from forms.
 *
 * @since 1.0.0
 *
 * @param string $data       Post content to filter
 * @param array  $extraAttrs Extra attributes to allow once.
 *
 * @return string Filtered post content with allowed HTML tags and attributes intact.
 */
function ksesPost($data, array $extraAttrs = array())
{
    global $allowedposttags;

    $tagsInputIncluded = array_merge($allowedposttags, array(
        'input'    => array(
            'accept'             => true,
            'autocomplete'       => true,
            'autofocus'          => true,
            'checked'            => true,
            'class'              => true,
            'disabled'           => true,
            'id'                 => true,
            'height'             => true,
            'min'                => true,
            'max'                => true,
            'minlenght'          => true,
            'maxlength'          => true,
            'name'               => true,
            'pattern'            => true,
            'placeholder'        => true,
            'readony'            => true,
            'required'           => true,
            'size'               => true,
            'src'                => true,
            'step'               => true,
            'type'               => true,
            'value'              => true,
            'width'              => true,
            'data-dzref'         => true,
            'data-autocomplete'  => true,
            'data-taxonomy'      => true,
            'data-type'          => true,
            'data-format'        => true,
            'data-append-map-to' => true,
            'data-id'            => true,
            'data-mediatitle'    => true,
            'data-exclude'       => true,
        ),
        'select'   => array(
            'autofocus'                => true,
            'class'                    => true,
            'id'                       => true,
            'disabled'                 => true,
            'form'                     => true,
            'multiple'                 => true,
            'name'                     => true,
            'required'                 => true,
            'size'                     => true,
            'data-placeholder'         => true,
            'data-selecttheme'         => true,
            'data-taxonomy'            => true,
            'data-typography-family'   => true,
            'data-typography-variants' => true,
            'data-typography-weights'  => true,
            'data-type'                => true,
        ),
        'option'   => array(
            'disabled'           => true,
            'label'              => true,
            'selected'           => true,
            'value'              => true,
            'data-vendor-prefix' => true,
            'data-font-variant'  => true,
            'data-font-weight'   => true,
        ),
        'optgroup' => array(
            'disabled' => true,
            'label'    => true,
        ),
        'textarea' => array(
            'placeholder' => true,
            'cols'        => true,
            'rows'        => true,
            'disabled'    => true,
            'name'        => true,
            'id'          => true,
            'readonly'    => true,
            'required'    => true,
            'autofocus'   => true,
            'form'        => true,
            'wrap'        => true,
        ),
        'picture'  => true,
        'source'   => array(
            'sizes'  => true,
            'src'    => true,
            'srcset' => true,
            'type'   => true,
            'media'  => true,
        ),
        'a' => array(
            'id'           => true,
            'class'        => true,
            'href'         => true,
            'rel'          => true,
            'target'       => true,
            'data-post-id' => true,
        ),
    ));

    // Extra for DIV.
    if (! isset($tagsInputIncluded['div'])) {
        $tagsInputIncluded['div'] = array();
    }
    $tagsInputIncluded['div']['data-autoupdate'] = true;

    // Extra for LEGEND.
    if (! isset($tagsInputIncluded['legend'])) {
        $tagsInputIncluded['legend'] = array();
    }
    $tagsInputIncluded['legend']['data-icon'] = true;

    // Extra for IMG.
    if (! isset($tagsInputIncluded['img'])) {
        $tagsInputIncluded['img'] = array();
    }
    $tagsInputIncluded['img']['data-id'] = true;

    // Extra for BUTTON.
    if (! isset($tagsInputIncluded['button'])) {
        $tagsInputIncluded['button'] = array();
    }
    $tagsInputIncluded['button']['data-dlmime']   = true;
    $tagsInputIncluded['button']['data-multiple'] = true;

    if ($extraAttrs) {
        // Extract the key for comparison.
        $extraAttrsKeys = array_keys($extraAttrs);
        foreach ($tagsInputIncluded as $tag => $attrs) {
            // It is a tag where we want to insert additional attributes?
            if (in_array($tag, $extraAttrsKeys, true)) {
                // If so, include the extra attributes list within the main tags input list.
                $tagsInputIncluded[$tag] = array_merge($tagsInputIncluded[$tag], $extraAttrs[$tag]);
            }
        }
    }

    // Form attributes.
    $tagsInputIncluded['form'] = array_merge($tagsInputIncluded['form'], array('novalidate' => true));
    // Fieldset attributes.
    // WordPress have an empty array.
    $tagsInputIncluded['fieldset'] = array_merge($tagsInputIncluded['fieldset'], array(
        'id'    => true,
        'class' => true,
        'form'  => true,
        'name'  => true,
    ));

    return wp_kses($data, $tagsInputIncluded);
}
