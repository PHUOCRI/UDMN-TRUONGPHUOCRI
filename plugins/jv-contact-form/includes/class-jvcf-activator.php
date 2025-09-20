<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class JV_Contact_Form_Activator {
    public static function activate() {
        global $wpdb;
        $table   = $wpdb->prefix . 'jvcf_submissions';
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(191) NOT NULL,
            email VARCHAR(191) NOT NULL,
            phone VARCHAR(50) NULL,
            destination VARCHAR(191) NULL,
            travel_date DATE NULL,
            message TEXT NULL,
            consent TINYINT(1) DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY created_at (created_at)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        // Tạo option mặc định
        if ( ! get_option( 'jvcf_recipient_email' ) ) {
            update_option( 'jvcf_recipient_email', get_option( 'admin_email' ) );
        }
    }
}