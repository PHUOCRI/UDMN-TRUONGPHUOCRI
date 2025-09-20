<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package dulichvietnhat
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function dulichvietnhat_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    // Add a class if the site has a custom header
    if (has_custom_header()) {
        $classes[] = 'has-custom-header';
    }

    // Add a class if WooCommerce is active
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce';
    }

    return $classes;
}
add_filter('body_class', 'dulichvietnhat_body_classes');

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails.
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param string|array $size Registered image size or flat array of height and width dimensions.
 * @return array The filtered attributes for the image markup.
 */
function dulichvietnhat_post_thumbnail_sizes_attr($attr, $attachment, $size) {
    if (is_singular()) {
        if (in_array($size, array('post-thumbnail', 'post-thumbnail-large'), true)) {
            $attr['sizes'] = '(max-width: 767px) 100vw, (max-width: 1024px) 75vw, 1200px';
        }
    } else {
        if ('post-thumbnail' === $size) {
            $attr['sizes'] = '(max-width: 767px) 100vw, (max-width: 1024px) 50vw, 33.3333vw';
        }
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'dulichvietnhat_post_thumbnail_sizes_attr', 10, 3);

/**
 * Add custom image sizes to the media library dropdown
 *
 * @param array $sizes Array of image size labels keyed by their name.
 * @return array Modified array of image size labels.
 */
function dulichvietnhat_custom_image_sizes_choose($sizes) {
    return array_merge($sizes, array(
        'tour-thumbnail' => __('Tour Thumbnail', 'dulichvietnhat'),
        'destination-thumbnail' => __('Destination Thumbnail', 'dulichvietnhat'),
        'post-thumbnail-large' => __('Large Post Thumbnail', 'dulichvietnhat'),
    ));
}
add_filter('image_size_names_choose', 'dulichvietnhat_custom_image_sizes_choose');

/**
 * Add SVG support
 */
function dulichvietnhat_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'dulichvietnhat_mime_types');

/**
 * Add async/defer attributes to enqueued scripts that have the specified script as a dependency
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag.
 */
function dulichvietnhat_script_loader_tag($tag, $handle) {
    // Add async to specific scripts
    $scripts_to_async = array('dulichvietnhat-main-js');
    
    foreach ($scripts_to_async as $async_script) {
        if ($async_script === $handle) {
            return str_replace(' src', ' async="async" src', $tag);
        }
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'dulichvietnhat_script_loader_tag', 10, 2);

/**
 * Add a dropdown icon to the main menu for items with children
 */
function dulichvietnhat_add_dropdown_icons($output, $item, $depth, $args) {
    if (isset($args->theme_location) && 'primary' === $args->theme_location) {
        // Add dropdown toggle for mobile
        if (in_array('menu-item-has-children', $item->classes, true)) {
            $output .= '<button class="dropdown-toggle" aria-expanded="false">';
            $output .= '<span class="screen-reader-text">' . esc_html__('Expand child menu', 'dulichvietnhat') . '</span>';
            $output .= '<i class="fas fa-chevron-down"></i>';
            $output .= '</button>';
        }
    }
    return $output;
}
add_filter('walker_nav_menu_start_el', 'dulichvietnhat_add_dropdown_icons', 10, 4);

/**
 * Add a custom class to the read more link
 */
function dulichvietnhat_modify_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '">' . esc_html__('Read More', 'dulichvietnhat') . '</a>';
}
add_filter('the_content_more_link', 'dulichvietnhat_modify_read_more_link');

/**
 * Filter the categories archive widget to add a span around post count
 */
function dulichvietnhat_cat_count_span($links) {
    $links = str_replace('</a> (', '</a><span class="post-count">', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}
add_filter('wp_list_categories', 'dulichvietnhat_cat_count_span');

/**
 * Filter the archives widget to add a span around post count
 */
function dulichvietnhat_archives_count_span($links) {
    $links = str_replace('</a>&nbsp;(', '</a><span class="post-count">', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}
add_filter('get_archives_link', 'dulichvietnhat_archives_count_span');

/**
 * Add a custom class to the navigation menu items
 */
function dulichvietnhat_add_menu_item_class($classes, $item, $args) {
    if (isset($args->add_li_class)) {
        $classes[] = $args->add_li_class;
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'dulichvietnhat_add_menu_item_class', 1, 3);

/**
 * Add a custom class to the navigation menu links
 */
function dulichvietnhat_add_menu_link_class($atts, $item, $args) {
    if (property_exists($args, 'link_class')) {
        $atts['class'] = $args->link_class;
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'dulichvietnhat_add_menu_link_class', 1, 3);

/**
 * Add a custom class to the submenu in the navigation menu
 */
function dulichvietnhat_add_submenu_class($classes, $args, $depth) {
    $classes[] = 'sub-menu';
    $classes[] = 'sub-menu--depth-' . $depth;
    return $classes;
}
add_filter('nav_menu_submenu_css_class', 'dulichvietnhat_add_submenu_class', 10, 3);
