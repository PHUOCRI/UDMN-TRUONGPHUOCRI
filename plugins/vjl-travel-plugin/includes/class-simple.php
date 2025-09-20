<?php
/**
 * Simple implementations for missing classes
 */

if (!defined('ABSPATH')) {
    exit;
}

// Simple ACF Fields class
if (!class_exists('VJL_Travel_ACF_Fields')) {
    class VJL_Travel_ACF_Fields {
        public function __construct() {
            // Placeholder for ACF fields
        }
    }
}

// Simple Admin Settings class
if (!class_exists('VJL_Travel_Admin_Settings')) {
    class VJL_Travel_Admin_Settings {
        public function __construct() {
            add_action('admin_menu', array($this, 'add_admin_menu'));
        }
        
        public function add_admin_menu() {
            add_menu_page(
                'VJL Travel Settings',
                'VJL Travel',
                'manage_options',
                'vjl-travel-settings',
                array($this, 'settings_page'),
                'dashicons-admin-site',
                30
            );
        }
        
        public function settings_page() {
            echo '<div class="wrap"><h1>VJL Travel Settings</h1><p>Settings page coming soon...</p></div>';
        }
    }
}

// Simple Slider class
if (!class_exists('VJL_Travel_Slider')) {
    class VJL_Travel_Slider {
        public function __construct() {
            add_shortcode('vjl_slider', array($this, 'slider_shortcode'));
        }
        
        public function slider_shortcode($atts) {
            return '<div class="vjl-slider">Slider coming soon...</div>';
        }
    }
}

// Simple Gallery class
if (!class_exists('VJL_Travel_Gallery')) {
    class VJL_Travel_Gallery {
        public function __construct() {
            add_shortcode('vjl_gallery', array($this, 'gallery_shortcode'));
        }
        
        public function gallery_shortcode($atts) {
            return '<div class="vjl-gallery">Gallery coming soon...</div>';
        }
    }
}

// Simple Rating class
if (!class_exists('VJL_Travel_Rating')) {
    class VJL_Travel_Rating {
        public function __construct() {
            add_shortcode('vjl_rating', array($this, 'rating_shortcode'));
        }
        
        public function rating_shortcode($atts) {
            return '<div class="vjl-rating">Rating system coming soon...</div>';
        }
    }
}

// Simple API class
if (!class_exists('VJL_Travel_API')) {
    class VJL_Travel_API {
        public function __construct() {
            // Placeholder for API functionality
        }
    }
}

// Simple Multilingual class
if (!class_exists('VJL_Travel_Multilingual')) {
    class VJL_Travel_Multilingual {
        public function __construct() {
            add_action('init', array($this, 'load_textdomain'));
        }
        
        public function load_textdomain() {
            load_plugin_textdomain('vjl-travel', false, dirname(plugin_basename(__FILE__)) . '/../languages');
        }
    }
}
