<?php
/**
 * Plugin Name: VJL Travel Plugin
 * Plugin URI: https://vjlinks.com
 * Description: Plugin du lịch Huế với Custom Post Types, ACF Fields, Slider, Form liên hệ, Gallery, Rating và đa ngôn ngữ
 * Version: 1.0.0
 * Author: VJL Links
 * Author URI: https://vjlinks.com
 * Text Domain: vjl-travel
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('VJL_TRAVEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VJL_TRAVEL_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('VJL_TRAVEL_PLUGIN_VERSION', '1.0.0');

/**
 * Main Plugin Class
 */
class VJL_Travel_Plugin {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Load required files
        $this->load_includes();
        
        // Initialize components only if classes exist
        if (class_exists('VJL_Travel_Post_Types')) {
            new VJL_Travel_Post_Types();
        }
        if (class_exists('VJL_Travel_Taxonomies')) {
            new VJL_Travel_Taxonomies();
        }
        if (class_exists('VJL_Travel_ACF_Fields')) {
            new VJL_Travel_ACF_Fields();
        }
        if (class_exists('VJL_Travel_Admin_Settings')) {
            new VJL_Travel_Admin_Settings();
        }
        if (class_exists('VJL_Travel_Slider')) {
            new VJL_Travel_Slider();
        }
        if (class_exists('VJL_Travel_Contact_Form')) {
            new VJL_Travel_Contact_Form();
        }
        if (class_exists('VJL_Travel_Gallery')) {
            new VJL_Travel_Gallery();
        }
        if (class_exists('VJL_Travel_Rating')) {
            new VJL_Travel_Rating();
        }
        if (class_exists('VJL_Travel_API')) {
            new VJL_Travel_API();
        }
        if (class_exists('VJL_Travel_Multilingual')) {
            new VJL_Travel_Multilingual();
        }
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }
    
    public function load_includes() {
        // Load core classes
        require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-activator.php';
        require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-post-types.php';
        require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-taxonomies.php';
        require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-contact-form.php';
        
        // Load simple implementations for missing classes
        require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-simple.php';
        
        // Try to load full implementations if they exist
        if (file_exists(VJL_TRAVEL_PLUGIN_PATH . 'includes/class-acf-fields.php')) {
            require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-acf-fields.php';
        }
        if (file_exists(VJL_TRAVEL_PLUGIN_PATH . 'includes/class-admin-settings.php')) {
            require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-admin-settings.php';
        }
        if (file_exists(VJL_TRAVEL_PLUGIN_PATH . 'includes/class-slider.php')) {
            require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-slider.php';
        }
        if (file_exists(VJL_TRAVEL_PLUGIN_PATH . 'includes/class-gallery.php')) {
            require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-gallery.php';
        }
        if (file_exists(VJL_TRAVEL_PLUGIN_PATH . 'includes/class-rating.php')) {
            require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-rating.php';
        }
        if (file_exists(VJL_TRAVEL_PLUGIN_PATH . 'includes/class-api.php')) {
            require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-api.php';
        }
               if (file_exists(VJL_TRAVEL_PLUGIN_PATH . 'includes/class-multilingual.php')) {
                   require_once VJL_TRAVEL_PLUGIN_PATH . 'includes/class-multilingual.php';
               }
               if (file_exists(VJL_TRAVEL_PLUGIN_PATH . 'setup-multilingual.php')) {
                   require_once VJL_TRAVEL_PLUGIN_PATH . 'setup-multilingual.php';
               }
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('vjl-travel', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('vjl-travel-style', VJL_TRAVEL_PLUGIN_URL . 'assets/css/style.css', array(), VJL_TRAVEL_PLUGIN_VERSION);
        wp_enqueue_script('vjl-travel-script', VJL_TRAVEL_PLUGIN_URL . 'assets/js/script.js', array('jquery'), VJL_TRAVEL_PLUGIN_VERSION, true);
        
        // Localize script for AJAX
        wp_localize_script('vjl-travel-script', 'vjlTravel', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('vjl_travel_nonce'),
            'strings' => array(
                'loading' => __('Đang tải...', 'vjl-travel'),
                'error' => __('Có lỗi xảy ra', 'vjl-travel'),
                'success' => __('Thành công!', 'vjl-travel'),
            )
        ));
    }
    
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'vjl-travel') !== false) {
            wp_enqueue_style('vjl-travel-admin-style', VJL_TRAVEL_PLUGIN_URL . 'assets/css/admin.css', array(), VJL_TRAVEL_PLUGIN_VERSION);
            wp_enqueue_script('vjl-travel-admin-script', VJL_TRAVEL_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), VJL_TRAVEL_PLUGIN_VERSION, true);
        }
    }
    
    public function activate() {
        // Use Activator class
        VJL_Travel_Activator::activate();
    }
    
    public function deactivate() {
        // Use Activator class
        VJL_Travel_Activator::deactivate();
    }
    
    private function create_default_options() {
        $default_options = array(
            'vjl_travel_logo' => '',
            'vjl_travel_phone' => '0349421736',
            'vjl_travel_email' => 'ri01652965673@gmail.com',
            'vjl_travel_address' => '52 Huỳnh Tấn Phát, phường An Đông, TP Huế',
            'vjl_travel_facebook' => '',
            'vjl_travel_instagram' => '',
            'vjl_travel_youtube' => '',
            'vjl_travel_google_maps_api' => '',
            'vjl_travel_currency' => 'VND',
            'vjl_travel_language' => 'vi',
            'vjl_travel_enable_rating' => true,
            'vjl_travel_enable_gallery' => true,
            'vjl_travel_enable_slider' => true,
        );
        
        foreach ($default_options as $key => $value) {
            if (!get_option($key)) {
                add_option($key, $value);
            }
        }
    }
}

// Initialize the plugin
new VJL_Travel_Plugin();

// Uninstall hook
register_uninstall_hook(__FILE__, 'vjl_travel_plugin_uninstall');
function vjl_travel_plugin_uninstall() {
    VJL_Travel_Activator::uninstall();
}
