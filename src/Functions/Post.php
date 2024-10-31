<?php
/**
 * Post Functions
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

use QiblaEvents\Debug;
use QiblaEvents\Exceptions\InvalidPostException;
use QiblaEvents\IconsSet\Icon;
use QiblaEvents\Listings\ListingLocation;
use QiblaEvents\Listings\ListingsPost;
use QiblaEvents\ListingsContext\Context;
use QiblaEvents\ListingsContext\Types;
use QiblaEvents\TemplateEngine\Engine;
use QiblaEvents\Functions as F;

/**
 * Get Post only text modifier
 *
 * @since 1.0.0
 *
 * @throws InvalidPostException If the post cannot be retrieved.
 *
 * @param string       $upxscope The scope prefix. Default 'upx'.
 * @param string       $element  The current element of the scope.
 * @param string       $block    The custom block scope. Default empty.
 * @param string       $scope    The default scope prefix. Default 'upx'.
 * @param string       $attr     The attribute for which the value has been build.
 * @param \WP_Post|int $post     The post for which retrieve the info.
 *
 * @return string The filtered scope
 */
function getPostTextOnlyModifier($upxscope, $element, $block, $scope, $attr, $post = null)
{
    // Get the post.
    $post = get_post($post);

    if (! $post ||
        (is_singular() && is_main_query()) ||
        'article' !== $block || '' !== $element || 'class' !== $attr
    ) {
        return $upxscope;
    }

    $modifier = (has_post_thumbnail($post->ID) || getJumbotronAsThumbnailImage($post) ? '' : 'text-only');

    if ($modifier) {
        $upxscope = $upxscope . ' ' . $scope . $block . '--' . $modifier;
    }

    return $upxscope;
}

/**
 * Featured Scope Class
 *
 * @since  1.0.0
 *
 * @param string $upxscope The scope prefix. Default 'upx'.
 * @param string $element  The current element of the scope.
 * @param string $block    The custom block scope. Default empty.
 * @param string $scope    The default scope prefix. Default 'upx'.
 * @param string $attr     The attribute for which scope we are filtering the string.
 *
 * @return string The list of the header class filtered
 */
function listingsFeaturedScopeModifier($upxscope, $element, $block, $scope, $attr)
{
    if ('class' !== $attr || 'article' !== $block || '' !== $element) {
        return $upxscope;
    }

    $types = new Types();
    if ($types->isListingsType(get_post_type()) && in_the_loop() && ! Context::isSingleListings()) {
        // Retrieve the meta value.
        $meta = getPostMeta('_qibla_mb_is_featured', 'off', get_the_ID());

        if ('on' === $meta) {
            // Apply the modifier.
            $upxscope .= " {$scope}{$block}--is-featured";
        }
    }

    return $upxscope;
}

/**
 * Get the Jumbo-tron as thumbnail image
 *
 * @since 1.0.0
 *
 * @uses  wp_get_attachment_image()
 *
 * @throws InvalidPostException If the post cannot be retrieved.
 *
 * @param \WP_Post|int $post The post from which retrieve the attachment image.
 * @param string|array $size The size for which the jumbotron image must be retrieved. Optional
 *
 * @return string The jumbotron image markup. Empty string on failure.
 */
function getJumbotronAsThumbnailImage($post = null, $size = 'qibla-post-thumbnail-loop')
{
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // Fallback to the 'thumbnail' size if the one passed to the function doesn't exists.
    // Size may be an array.
    if (! is_array($size) && ! has_image_size($size)) {
        $size = 'thumbnail';
    }

    return wp_get_attachment_image(
        getPostMeta('_qibla_mb_jumbotron_background_image', null, $post->ID),
        $size,
        false,
        array(
            'class' => 'dlthumbnail__image',
            'alt'   => getAttachmentImageAlt(get_post_thumbnail_id($post->ID)),
        )
    );
}

/**
 * Get Post Thumbnail and Fallback to Jumbotron Image
 *
 * @since 1.0.0
 *
 * @throws InvalidPostException If the post cannot be retrieved.
 *
 * @param \WP_Post     $post The post from which retrieve the thumbnail or the jumbotron.
 * @param string|array $size The size of the image to retrieve. Optional.
 *
 * @return string The post thumbnail or jumbotron image markup
 */
function getPostThumbnailAndFallbackToJumbotronImage(\WP_Post $post, $size = 'qibla-post-thumbnail-loop')
{
    return get_the_post_thumbnail(
        $post->ID,
        $size,
        array(
            'class' => 'dlthumbnail__image',
            'alt'   => getAttachmentImageAlt(get_post_thumbnail_id($post->ID)),
        )
    ) ?: getJumbotronAsThumbnailImage($post->ID, $size);
}

/**
 * Filter Post Thumbnail Data
 *
 * Try to retrieve the jumbo-tron as thumbnail image if the thumbnail for post is not provided.
 * This is necessary only for the blog posts that use the thumbnail from the theme.
 * For the other contents defined by the plugin such as listings/testimonials etc...
 * refer to `QiblaEvents\Thumbnail`.
 *
 * @since 1.0.0
 *
 * @uses  getJumbotronAsThumbnailImage()
 *
 * @param \stdClass $data The data to filter.
 * @param string    $size The size for which the jumbotron image must be retrieved.
 *
 * @return \stdClass The filtered data
 */
function postThumbToJumbotronData(\stdClass $data, $size = 'qibla-post-thumbnail-loop')
{
    // If no thumbnail is provided for the current post,
    // Try to get the hero image if is set. But not in single post.
    if (! $data->thumbnail && ! is_singular()) {
        try {
            $data->thumbnail = getJumbotronAsThumbnailImage($data->ID, $size);
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();
        }
    }

    return $data;
}

/**
 * Excerpt
 *
 * @since 1.0.0
 *
 * @throws \InvalidArgumentException In case the post cannot be retrieved.
 *
 * @return \stdClass The data containing the excerpt properties
 */
function getExcerptData()
{
    $post = get_post();

    // Types.
    $types = new Types();
    // Initialize data object.
    $data = new \stdClass();

    // Post Title and Permalink are here because some post may have no post title.
    // In this situation we use the post content as link to the single post.
    $data->postTitle   = get_the_title();
    $data->permalink   = get_permalink();
    $data->postContent = '';

    // No listings allowed.
    if ($types->isListingsType(get_post_type())) {
        return $data;
    }

    // Set the excerpt length.
    // Use the WordPress filter to permit plugins to hook it.
    $excerptLength = apply_filters('excerpt_length', 55);
    // More text.
    $moreText = morePostText();

    // Get the post excerpt only if supported and if the post has excerpt.
    if (post_type_supports(get_post_type(), 'excerpt') && (has_excerpt() || is_search())) {
        $theContent = get_the_excerpt();
    } else {
        $theContent = get_the_content('');
    }

    if ($theContent) :
        // Strip Short-codes.
        $theContent = strip_shortcodes($theContent);
        // Apply the filters applied by the the_content() function.
        $theContent = apply_filters('the_content', $theContent);
        $theContent = str_replace(']]>', ']]&gt;', $theContent);

        // If the more tag is not found we need to generate an excerpt.
        // Don't leave post without content.
        if (! preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches)) {
            // Allow plugins to hook in trim excerpt.
            // The wp_trim_excerpt function doesn't trim anything if the parameter is passed.
            // Called only to able to apply the filters.
            $theContent = wp_trim_excerpt($theContent);
            // Then make a real trim.
            $theContent = wp_trim_words($theContent, $excerptLength, '');
        }
        unset($matches);
    endif;

    if ($theContent) {
        // Strip the tags.
        $theContent = wp_strip_all_tags($theContent);
        // Assign the more string.
        $theContent = str_replace($moreText, '', $theContent) . $moreText;

        // Finally set the post content property.
        $data->postContent = wpautop($theContent);
        // And set the more link label.
        $data->moreLinkLabel = esc_html__('Continue Reading', 'qibla-events');
    }

    return $data;
}

/**
 * The more text
 *
 * @since 1.0.0
 *
 * @return string
 */
function morePostText()
{
    // Able plugins to edit it.
    return apply_filters('the_content_more_link', '&hellip;');
}

/**
 * Get Post Meta
 *
 * Retrieve the post meta.
 * This function is a wrapper for get_post_meta that add a fourth argument
 * that is the default value to return in case the post meta return a false value.
 *
 * Also the post id of the get_post_meta become the third parameter and the meta key the first.
 * In this way we can prevent to call the get_post externally only to retrieve the current post.
 *
 * @since 1.0.0
 *
 * @uses  get_post To retrieve the post for the meta
 * @uses  get_post_meta To retrieve the meta
 *
 * @param string       $key     The meta key to retrieve the value.
 * @param mixed        $default The default value to return in case the get_post_meta return false.
 * @param int|\WP_Post $post    The post for which retrieve the meta. See get_post for more info.
 * @param bool         $single  If the post meta value should be an array of meta key values or not.
 *
 * @return bool|mixed The default value or the post meta
 */
function getPostMeta($key, $default = null, $post = 0, $single = true)
{
    // Get the post.
    $post = get_post($post);

    if (! $post) {
        return $default;
    }

    // Return the default value if meta data doesn't exists.
    if (! metadata_exists('post', $post->ID, $key) && null !== $default) {
        return $default;
    }

    // Retrieve the post meta.
    return get_post_meta($post->ID, $key, $single);
}

/**
 * Get post Lists
 *
 * @since 1.0.0
 * @since 1.0.0 the $fields paramter allow to define the key and value properties to use for the returned array list.
 *
 * @throws \InvalidArgumentException If the post type doesn't exists.
 *
 * @param string $postType The post type from which retrieve the posts.
 * @param array  $fields   The fields to set as key => value for the new list. Optional. Default to 'post_name' =>
 *                         'post_title'. Note, the array is an indexed hash, [0] => property_key [1] => property_value.
 *
 * @return array An array of key value pair where the key is the post name and the value the post title.
 */
function getPostList($postType, array $fields = array())
{
    if (! post_type_exists($postType)) {
        throw new \InvalidArgumentException(
            sprintf('Post Type %s does not exists.', $postType)
        );
    }

    // Initialize the list.
    $list = array();
    // Set fields.
    $fields = empty($fields) ? array('post_name', 'post_title') : $fields;

    // Retrieve the posts.
    $query = new \WP_Query(array(
        'post_type'      => $postType,
        'posts_per_page' => -1,
        'no_found_rows'  => true,
    ));

    // Create the list.
    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $list[$post->{$fields[0]}] = $post->{$fields[1]};
        }
    }

    return $list;
}

/**
 * Get post by name
 *
 * @since 1.0.0
 * @since 1.0.0 Add new $status parameter
 *
 * @throws \InvalidArgumentException If the post type doesn't exists.
 *
 * @param string       $name     The name ( slug ) of the post to retrieve.
 * @param string       $postType The post type.
 * @param string|array $status   The post status / statuses.
 *
 * @return \WP_Post|\stdClass The post object or a standard obj if the post cannot be retrieved
 */
function getPostByName($name, $postType, $status = 'publish')
{
    // Know the post type.
    $ptypeObj = get_post_type_object($postType);

    if (! $ptypeObj) {
        throw new \InvalidArgumentException('The post type %s is not valid. Cannot retrieve the post type object.');
    }

    // Set base arguments.
    $args = array(
        'post_type'           => $postType,
        'posts_per_page'      => 1,
        'ignore_sticky_posts' => true,
        'post_status'         => $status,
    );

    // Set the properly key for post name.
    if (! $ptypeObj->hierarchical) {
        $args['name'] = $name;
    } else {
        $args['pagename'] = $name;
    }

    $post = new \WP_Query($args);

    if (0 === $post->found_posts) {
        return new \stdClass();
    }

    return reset($post->posts);
}

/**
 * Listing Terms List
 *
 * Create and show a list of terms associated to the post within the loop.
 * Icons for every terms are retrieved and showed. Default to 'Lineawesome::la-check'.
 *
 * @since  1.0.0
 *
 * @throws \Exception If the taxonomy from which retrieve the terms doesn't exists.
 *
 * @param \WP_Post $post The post from which retrieve the terms list.
 *
 * @return \stdClass The data instance
 */
function getListingsTermsList($post = null)
{
    $post = get_post($post);

    // Retrieve the terms.
    $terms = get_the_terms($post, 'event_categories');
    // And do nothing if there are no terms.
    if (is_wp_error($terms)) {
        throw new \Exception(
            sprintf('Cannot retrieve terms for post: %d', intval($post->ID))
        );
    } elseif (! $terms) {
        $terms = array();
    }

    // Initialize data.
    $data = new \stdClass();

    $data->termsList = array();
    foreach ($terms as $term) {
        $icon = new Icon(getTermMeta('_qibla_tb_icon', $term->term_id), 'Lineawesome::la-check');

        $data->termsList[sanitize_key($term->slug)] = array(
            'icon_html_class' => $icon->getHtmlClass(),
            'label'           => $term->name,
            'link'            => get_term_link($term->term_id, $term->taxonomy),
        );
    }

    return $data;
}

/**
 * Listings Terms List Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function listingsTermsListTmpl()
{
    if (! Context::isSingleListings() || ! in_the_loop()) {
        return;
    }

    try {
        $data = call_user_func_array('QiblaEvents\\Functions\\getListingsTermsList', func_get_args());

        $engine = new Engine('the_listing_terms_list', $data, '/views/termsList.php');
        $engine->render();
    } catch (\Exception $e) {
        $debugInstance = new Debug\Exception($e);
        'dev' === QB_ENV && $debugInstance->display();

        return;
    }
}

/**
 * Listings Loop Footer Location
 *
 * @since 1.0.0
 *
 * @param \stdClass $data The post data to filter.
 *
 * @return \stdClass The filtered class
 */
function listingsLoopFooterLocation(\stdClass $data)
{
    $post  = get_post($data->ID);
    $types = new Types();

    if ($types->isListingsType($post->post_type)) {
        $post = new ListingLocation($post);
        // Add address.
        $data->meta['address'] = $post->address();
    }

    return $data;
}

/**
 * Listings Post Title Icon
 *
 * @since 1.0.0
 *
 * @param \stdClass $data The data to filter in which add the icon.
 *
 * @return \stdClass The filtered data
 */
function listingsPostTitleIcon(\stdClass $data)
{
    $post  = get_post($data->ID);
    $types = new Types();

    if ($types->isListingsType($post->post_type) && ! is_singular($post->post_type)) {
        $post = new ListingsPost($post);
        // Add address.
        $data->icon = $post->icon();
    }

    return $data;
}

/**
 * Get the Thumbnail
 *
 * This function override the default wp the_post_thumbnail
 *
 * @see   the_post_thumbnail()
 *
 * @since 1.0.0
 *
 * @throws InvalidPostException If the $post cannot be retrieved.
 * @throws \Exception           If the theme doesn't support post-thumbnails.
 *
 * @param int|\WP_Post $post     Optional. Post ID or WP_Post object. Default current post.
 * @param string|array $size     Optional. Image size. Defaults to 'post-thumbnail', which theme sets using
 *                               set_post_thumbnail_size( $width, $height, $crop_flag );.
 * @param string|bool  $hasLink  If the image point to a post link. Default to false.
 * @param string|array $attr     Optional. Query string or array of attributes.
 *
 * @return \stdClass The data object.
 */
function getPostThumbnailData($post = null, $size = 'post-thumbnail', $hasLink = false, $attr = '')
{
    global $wp_query;

    // View data.
    $data = new \stdClass();

    // Get the post.
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // The theme doesn't support post thumbnails.
    if (! current_theme_supports('post-thumbnails')) {
        throw new \Exception('The theme doesn\'t support the post-thumbnails.');
    }

    // Set the thumbnail attributes.
    $attr = $attr ?: array();
    $attr = array_merge($attr, array(
        // Add the bem class to the post thumbnail image.
        // @see https://core.trac.wordpress.org/ticket/36996 about the class bug.
        'class' => getScopeClass('thumbnail', 'image'),
        'alt'   => getAttachmentImageAlt(get_post_thumbnail_id()),
    ));

    $data->ID            = $post->ID;
    $data->thumbnailAttr = $attr;
    $data->thumbnail     = get_the_post_thumbnail($post, $size, $data->thumbnailAttr);
    $data->caption       = get_the_post_thumbnail_caption($post);

    $html5Support       = current_theme_supports('html5', 'caption');
    $data->containerTag = ($html5Support ? 'figure' : 'div');

    // Post Thumbnail Anchor.
    if ($data->thumbnail && $hasLink && ! (is_singular() && true === ($post === $wp_query->post))) {
        $data->thumbnail = sprintf(
            '<a href="%s" class="%s">%s</a>',
            esc_url(get_permalink($post)),
            getScopeClass('thumbnail', 'link'),
            $data->thumbnail
        );
    }

    $data->captionTag = ($html5Support ? 'figcaption' : 'p');

    return $data;
}

/**
 * The Thumbnail Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function thePostThumbnailTmpl()
{
    // Generally within the theme it is not necessary but,
    // in case of the use with framework the fallback to jumbotron will show the image.
    if (is_page_template('templates/homepage.php')) {
        return;
    }

    try {
        // Retrieve the data.
        $data = call_user_func_array('QiblaEvents\\Functions\\getPostThumbnailData', func_get_args());
        // Only if there is an image to show.
        $engine = new Engine('the_post_thumbnail', $data, 'views/posts/thumbnail.php');
        $engine->render();
    } catch (\Exception $e) {
        $debugInstance = new Debug\Exception($e);
        'dev' === QB_ENV && $debugInstance->display();

        return;
    }
}

/**
 * Post Author
 *
 * @since 1.0.0
 *
 * @throws InvalidPostException If the $post cannot be retrieved.
 *
 * @param int|\WP_Post $post The id or the object of the post from which retrieve the author.
 *
 * @return void
 */
function postAuthor($post = null)
{
    // Get the post.
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // Data for View.
    $data = new \stdClass();

    $data->ID        = $post->ID;
    $data->user      = new \WP_User($post->post_author);
    $data->authorUrl = get_author_posts_url($data->user->ID);

    // Remove fields for security.
    unset(
        $data->user->user_pass,
        $data->user->user_email,
        $data->user->user_activation_key,
        $data->user->user_status
    );

    $engine = new Engine('the_post_author', $data, 'views/posts/meta/author.php');
    $engine->render();
}

/**
 * Get the published date for a post
 *
 * @since  1.0.0
 *
 * @throws InvalidPostException If the post parameter cannot be retrieved.
 *
 * @param  int|\WP_Post $post The id or the object of the post from which retrieve the publish date.
 *
 * @return \stdClass The data instance
 */
function getPostPubDateData($post = null)
{
    // Get the post.
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    $data                  = new \stdClass();
    $data->ID              = $post->ID;
    $data->dateArchiveLink = ('page' !== get_post_type($post) ? get_day_link(
        get_the_time('Y', $post),
        get_the_time('m', $post),
        get_the_time('d', $post)
    ) : '');
    $data->datetime        = get_the_time('c', $post);
    $data->date            = get_the_date('', $post);
    $data->titleAttr       = get_the_date('l, F j, Y g:i a', $post);
    $data->label           = esc_html__('Published On: ', 'qibla-events');

    return $data;
}

/**
 * Post Pub date Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function postPubDateTmpl()
{
    try {
        $data = call_user_func_array('QiblaEvents\\Functions\\getPostPubDateData', func_get_args());
    } catch (\Exception $e) {
        $debugInstance = new Debug\Exception($e);
        'dev' === QB_ENV && $debugInstance->display();

        return;
    }

    $engine = new Engine('the_post_pubdate', $data, 'views/posts/meta/pubdate.php');
    $engine->render();
}

/**
 * Post Terms
 *
 * @since 1.0.0
 *
 * @throws InvalidPostException If the post parameter cannot be retrieved.
 * @throws \Exception           If terms cannot be retrieved.
 *
 * @param  int|\WP_Post $post     The id or the object of the post from which retrieve the post terms.
 * @param string        $taxonomy The taxonomy slug from which retrieve the terms.
 *
 * @return \stdClass The data instance
 */
function getPostTermsData($taxonomy = 'category', $post = null)
{
    // Get the post.
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // Data for View.
    $data = new \stdClass();

    $data->ID             = $post->ID;
    $data->terms          = get_the_terms($post->ID, $taxonomy);
    $data->taxonomy       = $taxonomy;
    $data->termsSeparator = ',';

    if (is_wp_error($data->terms)) {
        throw new \Exception(sprintf('Cannot retrieve the post terms for post: %d', $post->ID));
    }

    return $data;
}

/**
 * The post Terms Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function postTermsTmpl()
{
    try {
        $data = call_user_func_array('QiblaEvents\\Functions\\getPostTermsData', func_get_args());
    } catch (\Exception $e) {
        $debugInstance = new Debug\Exception($e);
        'dev' === QB_ENV && $debugInstance->display();

        return;
    }

    if (! $data->terms) {
        return;
    }

    $engine = new Engine('the_post_terms', $data, 'views/posts/meta/listTerms.php');
    $engine->render();
}

/**
 * Show the tags lists associated to the post
 *
 * @since 1.0.0
 *
 * @uses  get_post()
 * @uses  get_the_tag()
 *
 * @throws InvalidPostException If the post parameter cannot be retrieved.
 * @throws \Exception           In case some of the internal functions return a \WP_Error.
 *
 * @param int|\WP_Post $post The id of the post from which retrieve the post tags.
 *
 * @return void
 */
function singlePostTags($post = null)
{
    // Get the post.
    $post = get_post($post);

    if (! $post) {
        throw new InvalidPostException();
    }

    // Data for View.
    $data = new \stdClass();

    $data->ID   = $post->ID;
    $data->tags = get_the_tags($post->ID);

    if (is_wp_error($data->tags)) {
        throw new \Exception(sprintf('%s, cannot retrieve tags for post.', 'qibla'), __FUNCTION__);
    }

    $engine = new Engine('the_post_tags', $data, 'views/posts/meta/tags.php');
    $engine->render();
}

/**
 * Post Loop Footer
 *
 * @since 1.0.0
 *
 * @uses  get_post()
 * @uses  get_post_meta()
 * @uses  get_post_type()
 *
 * @throws InvalidPostException If the post parameter cannot be retrieved.
 *
 * @param array $args A list of arguments to use within the function {
 *                    string $taxonomy The taxonomy from which retrieve the terms of the current post type.
 *                    }
 *
 * @return void
 */
/**
 * LooFooter events post article
 *
 * @since 1.0.0
 *
 * @param null $post
 *
 * @throws \Exception
 */
function loopFooter($post = null)
{
    $post = get_post($post);

    // Default condition.
    $isPostType = false;
    if (class_exists('QiblaEvents\\ListingsContext\\Types')) {
        $types      = new Types();
        $isPostType = $types->isListingsType($post->post_type);
    }

    if (! $post && ! $isPostType) {
        return;
    }

    $data = new \stdClass();

    // Get address.
    $location = new ListingLocation(get_post($post));
    // This overwrite the parent data.
    $data->address = $location->address();

    // Initialized.
    $data->eventsDateStart        = null;
    $data->eventsDateStartDay     = null;
    $data->eventsDateStartMouth   = null;
    $data->eventsDateStartDayText = null;
    $data->equalDate              = false;
    $startTimestamp               = $endTimestamp = false;

    // Get Date start.
    $dateStart = F\getPostMeta('_qibla_mb_event_dates_multidatespicker_start', '');
    if (isset($dateStart) && '' !== $dateStart) {
        $date                         = new \DateTime($dateStart);
        $startTimestamp               = intval($date->getTimestamp());
        $data->eventsDateStart        = date_i18n('c', intval($date->getTimestamp())) ?: '';
        $data->eventsDateStartDay     = date_i18n('d', intval($date->getTimestamp())) ?: '';
        $data->eventsDateStartMouth   = date_i18n('M', intval($date->getTimestamp())) ?: '';
        $data->eventsDateStartDayText = date_i18n('D', intval($date->getTimestamp())) ?: '';
    }

    // Get Date end.
    $dateEnd = F\getPostMeta('_qibla_mb_event_dates_multidatespicker_end', '');
    if (isset($dateEnd) && '' !== $dateEnd) {
        $date                       = new \DateTime($dateEnd);
        $endTimestamp               = intval($date->getTimestamp());
        $data->eventsDateEnd        = date_i18n('c', intval($date->getTimestamp())) ?: '';
        $data->eventsDateEndDay     = date_i18n('d', intval($date->getTimestamp())) ?: '';
        $data->eventsDateEndMouth   = date_i18n('M', intval($date->getTimestamp())) ?: '';
        $data->eventsDateEndDayText = date_i18n('D', intval($date->getTimestamp())) ?: '';
    }

    $data->equalDate = is_int($startTimestamp) && is_int($endTimestamp) && $startTimestamp === $endTimestamp ? true : false;

    $engine = new Engine('events_loop_footer', $data, '/views/posts/loopFooter.php');
    $engine->render();
}

/**
 * Add Pagination to single post
 *
 * The function is made coherent with the pagination links that use the list format
 *
 * @since 1.0.0
 */
function linkPages()
{
    global $multipage;

    if (! $multipage) {
        return;
    }

    // Filter the items to be coherent with the pagination links.
    add_filter('wp_link_pages_link', function ($markup, $i) {
        // It's the current page or the link?
        if (false === strpos($markup, '<a')) {
            // Page text it's not added to the current page.
            $markup = '<span class="screen-reader-text">' . esc_html__('Page', 'qibla-events') . '</span>';
            $markup .= '<span class="page-numbers current">' . $i . '</span>';
        }

        return '<li class="' . getScopeClass('pagination', 'item') . '">' . $markup . '</li>';
    }, 10, 2);

    // Data for View.
    $data = new \stdClass();

    $data->linkPagesArgs = array(
        'echo'        => 0,
        'before'      => '<ul class="' . getScopeClass('pagination', 'list') . '">',
        'after'       => '</ul>',
        'link_before' => '<span class="screen-reader-text">' . esc_html__('Page', 'qibla-events') . '</span>',
        'link_after'  => '',
    );

    $data->pagination = wp_link_pages($data->linkPagesArgs);

    $engine = new Engine('the_post_link_pages', $data, 'views/pagination/paginatePagination.php');
    $engine->render();
}

/**
 * Show the adjacent posts links in single posts.
 *
 * @todo  Move to link template file.
 * @todo  Check for in_the_loop() because of get_adjacent_post().
 *
 * @since 1.0.0
 *
 * @return void
 */
function adjacentPostsNavigation()
{
    // Data for View.
    $data = new \stdClass();

    // Only the first will be used.
    $data->taxonomies = get_object_taxonomies(get_post_type());

    if (empty($data->taxonomies)) {
        return;
    }

    /**
     * Whether post should be in a same taxonomy term.
     *
     * @since 1.0.0
     *
     * @param string 'yes' to get the post from the same term, 'no' otherwise. Default 'yes'
     */
    $data->inSameTerm = apply_filters('qibla_adjacent_posts_navigation_same_term', 'yes');
    // Clean the value.
    $data->inSameTerm = str_replace(array('yes', 'no'), array(true, false), $data->inSameTerm);

    // Excluded Terms.
    $data->excludedTerms = array();
    // Taxonomy from which retrieve the adjacent posts.
    $data->taxonomy = $data->taxonomies[0];

    // Previous and Next posts.
    $data->adjacentPosts['prev'] = get_previous_post(
        $data->inSameTerm,
        $data->excludedTerms,
        $data->taxonomy
    ) ?: '';
    $data->adjacentPosts['next'] = get_next_post(
        $data->inSameTerm,
        $data->excludedTerms,
        $data->taxonomy
    ) ?: '';

    if (! $data->adjacentPosts['prev'] && ! $data->adjacentPosts['next']) {
        return;
    }

    $engine = new Engine('the_post_adjacent_posts', $data, 'views/posts/adjacentPosts.php');
    $engine->render();
}

/**
 * Excerpt Template
 *
 * @since 1.0.0
 *
 * @return void
 */
function excerptTmpl()
{
    try {
        $data = getExcerptData();

        if ($data->postContent) {
            $engine = new Engine('dl_post_excerpt', $data, '/views/posts/excerpt.php');
            $engine->render();
        }
    } catch (\Exception $e) {
        $debugInstance = new Debug\Exception($e);
        'dev' === QB_ENV && $debugInstance->display();

        return;
    }
}

/**
 * Post Text Only
 *
 * @since 1.0.0
 *
 * @param string $upxscope The scope prefix. Default 'upx'.
 * @param string $element  The current element of the scope.
 * @param string $block    The custom block scope. Default empty.
 * @param string $scope    The default scope prefix. Default 'upx'.
 * @param string $attr     The attribute for which the value has been build.
 *
 * @return string
 * @throws InvalidPostException
 */
function loopPostTextOnly($upxscope, $element, $block, $scope, $attr)
{
    return getPostTextOnlyModifier($upxscope, $element, $block, $scope, $attr);
}

/**
 * Single post categories
 *
 * Show the single post categories.
 *
 * @since 1.0.0
 *
 * @return void
 */
function singlePostCategories()
{
    if (is_singular('post')) {
        echo '<p class="dlpost-categories">' . get_the_category_list(', ') . '</p>';
    }
}

/**
 * Single post Footer
 *
 * Show the post footer only in single.
 *
 * @since 1.0.0
 *
 * @return void
 */
function singlePostFooter()
{
    if (is_singular('post')) {
        try {
            loopFooter(null, array('taxonomy' => 'post_tag'));
        } catch (\Exception $e) {
            $debugInstance = new Debug\Exception($e);
            'dev' === QB_ENV && $debugInstance->display();

            return;
        }
    }
}
