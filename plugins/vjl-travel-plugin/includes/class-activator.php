<?php
/**
 * Plugin Activator
 * Handles plugin activation tasks
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_Activator {
    
    public static function activate() {
        // Create database tables
        self::create_database_tables();
        
        // Set default options
        self::set_default_options();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    private static function create_database_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        // Contact form submissions table
        $table_name = $wpdb->prefix . 'vjl_contact_submissions';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(191) NOT NULL,
            email VARCHAR(191) NOT NULL,
            phone VARCHAR(50) NULL,
            destination VARCHAR(191) NULL,
            travel_date DATE NULL,
            message TEXT NULL,
            consent TINYINT(1) DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY created_at (created_at),
            KEY email (email)
        ) $charset_collate;";
        
        // Tour bookings table
        $table_name2 = $wpdb->prefix . 'vjl_tour_bookings';
        $sql2 = "CREATE TABLE IF NOT EXISTS $table_name2 (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            tour_id BIGINT UNSIGNED NOT NULL,
            customer_name VARCHAR(191) NOT NULL,
            customer_email VARCHAR(191) NOT NULL,
            customer_phone VARCHAR(50) NULL,
            adults INT DEFAULT 1,
            children INT DEFAULT 0,
            booking_date DATE NOT NULL,
            total_price DECIMAL(10,2) NOT NULL,
            status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
            notes TEXT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY tour_id (tour_id),
            KEY booking_date (booking_date),
            KEY status (status)
        ) $charset_collate;";
        
        // Testimonials ratings table
        $table_name3 = $wpdb->prefix . 'vjl_testimonial_ratings';
        $sql3 = "CREATE TABLE IF NOT EXISTS $table_name3 (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            testimonial_id BIGINT UNSIGNED NOT NULL,
            rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
            reviewer_name VARCHAR(191) NOT NULL,
            reviewer_email VARCHAR(191) NULL,
            review_text TEXT NULL,
            is_approved TINYINT(1) DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY testimonial_id (testimonial_id),
            KEY rating (rating),
            KEY is_approved (is_approved)
        ) $charset_collate;";
        
        // Gallery views table
        $table_name4 = $wpdb->prefix . 'vjl_gallery_views';
        $sql4 = "CREATE TABLE IF NOT EXISTS $table_name4 (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            gallery_id BIGINT UNSIGNED NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            user_agent TEXT NULL,
            viewed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY gallery_id (gallery_id),
            KEY viewed_at (viewed_at),
            KEY ip_address (ip_address)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        dbDelta($sql2);
        dbDelta($sql3);
        dbDelta($sql4);
    }
    
    private static function set_default_options() {
        // Contact form settings
        if (!get_option('vjl_contact_recipient_email')) {
            update_option('vjl_contact_recipient_email', get_option('admin_email'));
        }
        
        if (!get_option('vjl_contact_form_title')) {
            update_option('vjl_contact_form_title', 'Liên hệ tư vấn tour');
        }
        
        // Gallery settings
        if (!get_option('vjl_gallery_items_per_page')) {
            update_option('vjl_gallery_items_per_page', 12);
        }
        
        if (!get_option('vjl_gallery_enable_lightbox')) {
            update_option('vjl_gallery_enable_lightbox', 1);
        }
        
        // Rating settings
        if (!get_option('vjl_rating_auto_approve')) {
            update_option('vjl_rating_auto_approve', 0);
        }
        
        if (!get_option('vjl_rating_min_length')) {
            update_option('vjl_rating_min_length', 10);
        }
        
        // General settings
        if (!get_option('vjl_plugin_version')) {
            update_option('vjl_plugin_version', VJL_TRAVEL_PLUGIN_VERSION);
        }
    }
    
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    public static function uninstall() {
        global $wpdb;
        
        // Drop database tables
        $tables = array(
            $wpdb->prefix . 'vjl_contact_submissions',
            $wpdb->prefix . 'vjl_tour_bookings',
            $wpdb->prefix . 'vjl_testimonial_ratings',
            $wpdb->prefix . 'vjl_gallery_views'
        );
        
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
        
        // Remove options
        $options = array(
            'vjl_contact_recipient_email',
            'vjl_contact_form_title',
            'vjl_gallery_items_per_page',
            'vjl_gallery_enable_lightbox',
            'vjl_rating_auto_approve',
            'vjl_rating_min_length',
            'vjl_plugin_version'
        );
        
        foreach ($options as $option) {
            delete_option($option);
        }
    }
}
