<?php
/**
 * Form Typography Type
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Types
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

namespace QiblaEvents\Form\Types;

use QiblaEvents\Functions as F;
use QiblaEvents\Form\Abstracts\Type;
use QiblaEvents\Plugin;

/**
 * Class Typography
 *
 * @since  1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class Typography extends Type
{
    /**
     * Font List
     *
     * @since  1.0.0
     *
     * @var array The list of the fonts
     */
    protected $fontList;

    /**
     * Parse Json Fonts
     *
     * @since  1.0.0
     *
     * @throws \Exception If the json is not valid.
     *
     * @return array The data from the json file.
     */
    protected function parseJsonFile($path)
    {
        $path     = F\sanitizePath($path);
        $cacheKey = sanitize_key($path);

        // Retrieve the json data from cache.
        // Avoid parse every time the same file.
        $cached = wp_cache_get($cacheKey, 'json');

        if ($cached) {
            return $cached;
        }

        $file = $path;
        $data = file_get_contents($file);
        $json = json_decode($data);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception(
                sprintf('The file %s is not a valid json.', $file)
            );
        }

        // Store into cache.
        wp_cache_add($cacheKey, $json, 'json');

        return $json;
    }

    /**
     * Font Data List
     *
     * Get the font data list. The list include Variants and weight.
     *
     * @since  1.0.0
     *
     * @return array The font data list.
     */
    protected function fontDataList()
    {
        static $list = array();

        // Don't perform always the same task.
        if (! empty($list)) {
            return $list;
        }

        $fontList = (array)$this->fontList;
        $variants = (array)wp_list_pluck($fontList, 'variants');

        foreach ($variants as $fontName => $variant) {
            $variant = (array)$variant;
            foreach ($variant as $type => $data) {
                $data                   = (array)$data;
                $list[$fontName][$type] = array_keys($data);
            }
        }

        return $list;
    }

    /**
     * Get Font Variant List
     *
     * Retrieve the font variants based on font name.
     *
     * @since  1.0.0
     *
     * @param string $font The name of the font from which retrieve the data.
     *
     * @return array The font variants list
     */
    protected function getFontVariantList($font = '')
    {
        static $list = array();

        $fontDataList = $this->fontDataList();

        if ($font) {
            return isset($fontDataList[$font]) ? array_keys($fontDataList[$font]) : array();
        }

        if (! empty($list)) {
            return $list;
        }

        foreach ($fontDataList as $name => $data) {
            $list = array_merge($list, array_keys($data));
        }

        $list = array_values(array_unique($list));

        // Generally we have 'normal', 'italic' and the second become the first when ordering.
        // By default we need the normal variation, so right know we have only those values above,
        // we can reverse the array and we are done.
        $list = array_reverse($list);

        return $list;
    }

    /**
     * Get Font Weight List
     *
     * Retrieve the font weight list based on font and variant name.
     *
     * @param string $font    The name of the font.
     * @param array  $variant The variant of the font.
     *
     * @return array The font weight list
     */
    protected function getFontWeightList($font = '', $variant = array())
    {
        static $list = array();

        $fontDataList = $this->fontDataList();

        if ($font && $variant) {
            if (is_string($variant)) {
                return isset($fontDataList[$font][$variant]) ? $fontDataList[$font][$variant] : array();
            } elseif (is_array($variant) && ! empty($variant)) {
                $vList = array();
                foreach ($variant as $value) {
                    $vList[$value] = $value . ':' . implode('|', $this->getFontWeightList($font, $value));
                }

                return $vList;
            } else {
                return array();
            }
        }

        if (! empty($list)) {
            return $list;
        }

        foreach ($fontDataList as $name => $data) {
            foreach ($data as $weight) {
                $list = array_merge($list, $weight);
            }
        }

        $list = array_unique($list);
        sort($list);

        // Build the array as associative by replace the index to a string.
        // Done by adding a w before the index.
        $keys = array_map(function ($el) {
            return 'w' . $el;
        }, $list);
        $list = array_combine($keys, $list);

        return $list;
    }

    /**
     * Build Font Names Options List
     *
     * @since  1.0.0
     *
     * @see    https://github.com/jonathantneal/google-fonts-complete For the json structure.
     *
     * @return array The options list of font names
     */
    protected function fontNamesOptions()
    {
        static $list = array();

        // Don't perform always the same task.
        if ($list) {
            return $list;
        }

        foreach ($this->fontList as $name => $data) {
            // Since the name is a string containing lower and upper case character
            // We cannot use sanitize_key or some other functions. So, use sanitize_text_field.
            $name = sanitize_text_field($name);
            // The variants list.
            $variantsList = $this->getFontVariantList($name);

            // Set the font name.
            $list[$name] = array(
                'attrs' => array(
                    'data-font-variant' => implode(';', $variantsList),
                    'data-font-weight'  => implode(';', $this->getFontWeightList($name, $variantsList)),
                ),
                'label' => ucwords($name),
            );
        }

        return $list;
    }

    /**
     * Constructor
     *
     * Example of the json data.
     *
     * "ABeeZee": {
     *      "category": "sans-serif",
     *      "lastModified": "2015-04-06",
     *      "version": "v4",
     *      "variants": {
     *          "italic": {
     *              "400": {
     *                  "local": [
     *                      "'ABeeZee Italic'",
     *                      "'ABeeZee-Italic'"
     *                  ],
     *                  "url": {
     *                      "eot": "url"
     *                      "svg": "url",
     *                      "ttf": "url",
     *                      "woff": "url",
     *                      "woff2": "url"
     *                  }
     *              }
     *          },
     *          "normal": {
     *              "400": {
     *                  "local": [
     *                      "'ABeeZee'",
     *                      "'ABeeZee-Regular'"
     *                  ],
     *                   "url": {
     *                      "eot": "url"
     *                      "svg": "url",
     *                      "ttf": "url",
     *                      "woff": "url",
     *                      "woff2": "url"
     *                  }
     *              }
     *          }
     *      }
     * },
     *
     * @link   https://github.com/jonathantneal/google-fonts-complete
     * @see    assets/json/google-fonts.json
     *
     * @since  1.0.0
     *
     * @param array $args The arguments for this type.
     */
    public function __construct($args)
    {
        $args = wp_parse_args($args, array(
            'attrs' => array(),
        ));

        // Set to an empty string if not provide.
        // Will get the current value color if empty.
        if (empty($args['default_color'])) {
            $args['default_color'] = '';
        }

        if (empty($args['font_json_path'])) {
            $args['font_json_path'] = Plugin::getPluginDirPath('/assets/json/google-fonts.json');
        }

        /**
         * Json Font Path Filter
         *
         * @since 1.0.0
         *
         * @param string $font_json_path The path of the json fonts file.
         * @param array  $args           The arguments passed to this construct.
         */
        $args['font_json_path'] = apply_filters(
            'qibla_typography_type_json_font_path',
            $args['font_json_path'],
            $args
        );

        try {
            // Build the list of fonts.
            $this->fontList = $this->parseJsonFile($args['font_json_path']);
        } catch (\Exception $e) {
            $this->fontList = array();
        }

        // Set the data type. It's a custom type.
        $args['attrs'] = array_merge($args['attrs'], array(
            'data-type' => 'typography',
        ));

        parent::__construct(wp_parse_args($args, array(
            'exclude'        => array(),
            'filter_options' => array(
                // Required by filter_input/filter_var.
                'flags'    => FILTER_REQUIRE_ARRAY | FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP,
                'family'   => FILTER_SANITIZE_STRING,
                'variants' => FILTER_SANITIZE_STRING,
                'weights'  => FILTER_SANITIZE_STRING,
                'color'    => FILTER_SANITIZE_STRING,
            ),
        )));
    }

    /**
     * Sanitize
     *
     * @since  1.0.0
     *
     * @param array $values The value to sanitize.
     *
     * @return array The sanitized values for this type.
     */
    public function sanitize($values)
    {
        foreach ($values as $key => &$value) {
            switch ($key) {
                case 'color':
                    $value = sanitize_hex_color($value);
                    break;
                default:
                    $value = sanitize_text_field($value);
                    break;
            }
        }

        // Be sure at least one value is set.
        $values = array_filter($values, function ($el) {
            return ! empty($el);
        });

        return $values;
    }

    /**
     * Escape
     *
     * @since  1.0.0
     *
     * @param mixed $values The value to sanitize.
     *
     * @return mixed The escaped value of this type
     */
    public function escape($values = null)
    {
        // Retrieve the value.
        $values = $values ?: $this->getValue();
        // Get the additional arguments.
        $args = array_slice(func_get_args(), 1);

        // If not additional arguments have been passed we must escape all of the array elements.
        if (! $args) {
            foreach ($values as $key => &$value) {
                $value = esc_attr(sanitize_text_field($value));
            }
        } else {
            // First additional argument is the name of the sub-type.
            $values = isset($values[$args[0]]) ? esc_attr(sanitize_text_field($values[$args[0]])) : '';
        }

        return $values;
    }

    /**
     * Get Html
     *
     * @since  1.0.0
     *
     * @return string The html markup for this type
     */
    public function getHtml()
    {
        $output = '';

        // Build the list for font names.
        $fontNames = new Select(array(
            'name'         => $this->getArg('name') . '[family]',
            'select2'      => true,
            'options'      => $this->fontNamesOptions(),
            'value'        => $this->escape(null, 'family'),
            'exclude_none' => true,
            'attrs'        => array(
                // Add the data attribute to identify the select via js.
                'data-typography-family' => 'family',
                'class'                  => array(
                    'dlselect2--wide',
                ),
            ),
        ));
        $output    .= $fontNames->getHtml();

        // Build the list of variants.
        if (! in_array('variants', $this->getArg('exclude'), true)) {
            $fontVariants = new Select(array(
                'name'         => $this->getArg('name') . '[variants]',
                'select2'      => true,
                'options'      => array_combine($this->getFontVariantList(), $this->getFontVariantList()),
                'value'        => $this->escape(null, 'variants'),
                'exclude_none' => true,
                'attrs'        => array(
                    // Add the data attribute to identify the select via js.
                    'data-typography-variants' => 'variants',
                ),
            ));
            $output       .= $fontVariants->getHtml();
        }

        // Build the list of weights.
        if (! in_array('weights', $this->getArg('exclude'), true)) {
            $fontWeight = new Select(array(
                'name'         => $this->getArg('name') . '[weights]',
                'select2'      => true,
                'options'      => $this->getFontWeightList(),
                'value'        => $this->escape(null, 'weights'),
                'exclude_none' => true,
                'attrs'        => array(
                    // Add the data attribute to identify the select via js.
                    'data-typography-weights' => 'weights',
                ),
            ));
            $output     .= $fontWeight->getHtml();
        }

        // Add the color picker.
        if (! in_array('color', $this->getArg('exclude'), true)) {
            // Sanitize the Hex color prior to use it.
            $default = $this->sanitize(array('color' => $this->getArg('default_color')));
            $color   = new ColorPicker(array(
                'name'  => $this->getArg('name') . '[color]',
                'attrs' => array(
                    'required'           => 'required',
                    'value'              => $this->escape(null, 'color'),
                    'data-default-color' => $default ? esc_attr($default['color']) : $this->escape(null, 'color'),
                ),
            ));
            $output  .= $color->getHtml();
        }

        /**
         * Output Filter
         *
         * @since 1.0.0
         *
         * @param string                                $output The output of the input type.
         * @param \QiblaEvents\Form\Interfaces\Types $this   The instance of the type.
         */
        $output = apply_filters('qibla_events_type_typography_output', $output, $this);

        return $output;
    }
}
