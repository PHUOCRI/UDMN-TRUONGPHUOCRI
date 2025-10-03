<?php
/**
 * Plugin Name: JV Why Choose Us
 * Description: Quản lý mục "Tại sao chọn chúng tôi?" bằng Custom Post Type và shortcode [jvwcu].
 * Version: 1.0.0
 * Author: JV
 * Text Domain: jvwcu
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
if (!defined('JVWCU_VERSION')) {
    define('JVWCU_VERSION', '1.0.0');
}
if (!defined('JVWCU_FILE')) {
    define('JVWCU_FILE', __FILE__);
}
if (!defined('JVWCU_PATH')) {
    define('JVWCU_PATH', plugin_dir_path(__FILE__));
}
if (!defined('JVWCU_URL')) {
    define('JVWCU_URL', plugin_dir_url(__FILE__));
}

// Activation & deactivation hooks
register_activation_hook(__FILE__, function () {
    // Register CPT first so flush works
    require_once JVWCU_PATH . 'includes/class-jvwcu-cpt.php';
    JVWCU_CPT::register();
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function () {
    flush_rewrite_rules();
});

// Autoload includes
add_action('plugins_loaded', function () {
    // Load textdomain if needed
    load_plugin_textdomain('jvwcu', false, dirname(plugin_basename(__FILE__)) . '/languages');

    require_once JVWCU_PATH . 'includes/class-jvwcu-cpt.php';
    require_once JVWCU_PATH . 'includes/class-jvwcu-shortcode.php';

    // Init pieces
    add_action('init', ['JVWCU_CPT', 'register']);
    add_action('add_meta_boxes', ['JVWCU_CPT', 'register_meta_boxes']);
    add_action('save_post', ['JVWCU_CPT', 'save_meta_boxes']);

    JVWCU_Shortcode::init();
});

// Enqueue assets (frontend only)
add_action('wp_enqueue_scripts', function () {
    wp_register_style('jvwcu-style', JVWCU_URL . 'assets/css/jvwcu.css', [], JVWCU_VERSION);
});
