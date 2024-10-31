<?php
namespace QiblaEvents\Front\CustomFields;

use QiblaEvents\Debug;
use QiblaEvents\Functions as F;
use QiblaEvents\TemplateEngine\Engine;

/**
 * Abstract Meta
 *
 * @since     1.0.0
 * @package   QiblaEvents\Front\CustomFields
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Alfio Piccione
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

/**
 * Class AbstractMeta
 *
 * @since      1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
abstract class AbstractMeta
{
    /**
     * Context
     *
     * The context may be post, term, user etc...
     *
     * @since  1.0.0
     *
     * @var string The context of the custom meta
     */
    protected $context;

    /**
     * Meta-box Key
     *
     * The meta-box key is a string that define the context of the meta-box.
     * mb stay for meta-box, tb stay for term-box.
     *
     * @since  1.0.0
     *
     * @var string The meta-box key
     */
    protected $mbKey;

    /**
     * Meta
     *
     * The meta array containing all of the meta-keys for the current custom box
     *
     * @since  1.0.0
     *
     * @var array An array containing all meta-keys for the current box
     */
    protected $meta;

    /**
     * ID
     *
     * The current queried id of the current queried object
     *
     * @since 1.0.0
     *
     * @var int The id of the current queried object
     */
    protected $id;

    /**
     * Taxonomy
     *
     * @since  1.0.0
     *
     * The taxonomy related to the current term if exists.
     */
    protected $taxonomy;

    /**
     * Obj
     *
     * @since 1.0.0
     *
     * @var mixed May be the \WP_Post instance or the \WP_Term.
     */
    protected $obj;

    /**
     * Get Meta
     *
     * Get the meta value based on context
     *
     * @param string $metaKey The meta-key of the meta.
     * @param mixed  $default The default value to return.
     * @param bool   $single  If must be retrieved the single value instead of the array. Optional. Default true.
     *
     * @return mixed The meta value
     */
    public function getMeta($metaKey, $default = false, $single = true)
    {
        try {
            $metaKey = $this->getMetaKey($metaKey);
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            return $default ?: '';
        }

        switch ($this->context) {
            case 'term':
                $meta = F\getTermMeta($metaKey, $this->id, $default, $single);
                break;
            case 'post':
                $meta = F\getPostMeta($metaKey, $default, $this->id, $single);
                break;
            default:
                $meta = '';
                break;
        }

        return $meta;
    }

    /**
     * Initialize Object
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function init()
    {
        // Get the current queried object.
        $currObj = get_queried_object();

        // Set the work stuffs for Taxonomies.
        if (is_tax() || is_category() || is_tag()) {
            $this->context = 'term';
            $this->mbKey   = 'tb';
            $this->id      = $currObj->term_id;
            // The current term may have multiple relationships.
            $this->taxonomy = is_array($currObj->taxonomy) ? $currObj->taxonomy[0] : $currObj->taxonomy;
        } elseif (is_singular() || F\isShop() || is_home()) {
            $this->context = 'post';
            $this->mbKey   = 'mb';

            if (F\isShop()) {
                $this->id = intval(get_option('woocommerce_shop_page_id'));
            } elseif (is_home()) {
                $this->id = intval(get_option('page_for_posts'));
            } else {
                $this->id = $currObj->ID;
            }
        }

        $this->obj = $currObj;
    }

    /**
     * ID
     *
     * @since 1.0.0
     *
     * @return int The id of the current queried object
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Context
     *
     * @since 1.0.0
     *
     * @return string The context of the meta data
     */
    public function context()
    {
        return $this->context;
    }

    /**
     * Load View
     *
     * @since  1.0.0
     *
     * @param string    $name         The name of the current template.
     * @param \stdClass $data         The data to use in view.
     * @param array     $templatePath The template to load.
     *
     * @return mixed Whatever the Engine::render().
     */
    protected function loadTemplate($name, $data, $templatePath)
    {
        $engine = new Engine($name, $data, $templatePath);

        return $engine->render();
    }

    /**
     * Construct
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->mbKey    = '';
        $this->context  = '';
        $this->id       = null;
        $this->meta     = array();
        $this->taxonomy = '';
    }

    /**
     * Get Meta Slug
     *
     * @since  1.0.0
     *
     * @throws \Exception in case the meta key doesn't exists.
     *
     * @param string $key The meta key to retrieve in short version.
     *
     * @return string The meta key
     */
    public function getMetaKey($key)
    {
        // Clean the slug.
        $key = sanitize_key($key);

        // Check whatever the slug exists.
        if (! isset($this->meta[$key])) {
            throw new \Exception(sprintf(
                '%1$s for meta %2$s does not exists.',
                __METHOD__,
                $key
            ));
        }

        return $this->meta[$key];
    }
}
