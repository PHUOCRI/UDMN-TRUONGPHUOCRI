<?php
/**
 * WooCommerce compatibility for the theme
 *
 * @package dulichvietnhat
 */

// WooCommerce setup
function dulichvietnhat_woocommerce_setup() {
    add_theme_support('woocommerce', array(
        'product_grid' => array(
            'default_columns' => 3,
            'min_columns'     => 2,
            'max_columns'     => 4,
        ),
    ));
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'dulichvietnhat_woocommerce_setup');

// WooCommerce scripts and styles
function dulichvietnhat_woocommerce_scripts() {
    wp_enqueue_style(
        'dulichvietnhat-woocommerce-style',
        get_template_directory_uri() . '/assets/css/woocommerce.css',
        array(),
        _S_VERSION
    );
}
add_action('wp_enqueue_scripts', 'dulichvietnhat_woocommerce_scripts');

// Disable default WooCommerce stylesheet
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Add WooCommerce body class
function dulichvietnhat_woocommerce_active_body_class($classes) {
    $classes[] = 'woocommerce-active';
    return $classes;
}
add_filter('body_class', 'dulichvietnhat_woocommerce_active_body_class');

// Products per page
function dulichvietnhat_woocommerce_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'dulichvietnhat_woocommerce_products_per_page');

// Products per row
function dulichvietnhat_woocommerce_loop_columns() {
    return 3;
}
add_filter('loop_shop_columns', 'dulichvietnhat_woocommerce_loop_columns');

// Related products count
function dulichvietnhat_woocommerce_related_products_args($args) {
    $args['posts_per_page'] = 3;
    $args['columns'] = 3;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'dulichvietnhat_woocommerce_related_products_args');

// Remove default WooCommerce wrappers
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// Add theme wrappers
function dulichvietnhat_woocommerce_wrapper_before() {
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
    <?php
}
add_action('woocommerce_before_main_content', 'dulichvietnhat_woocommerce_wrapper_before');

function dulichvietnhat_woocommerce_wrapper_after() {
    ?>
        </main>
    </div>
    <?php
}
add_action('woocommerce_after_main_content', 'dulichvietnhat_woocommerce_wrapper_after');

// Update cart count with AJAX
function dulichvietnhat_woocommerce_cart_link_fragment($fragments) {
    ob_start();
    ?>
    <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'dulichvietnhat'); ?>">
        <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span>
        <span class="count"><?php echo esc_html(sprintf(_n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'dulichvietnhat'), WC()->cart->get_cart_contents_count())); ?></span>
    </a>
    <?php
    $fragments['a.cart-contents'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'dulichvietnhat_woocommerce_cart_link_fragment');
