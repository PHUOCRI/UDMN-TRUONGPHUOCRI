<?php
// Xóa dữ liệu khi gỡ cài đặt hoàn toàn plugin (tùy chọn)
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

global $wpdb;
$table = $wpdb->prefix . 'jvcf_submissions';
$wpdb->query( "DROP TABLE IF EXISTS $table" );

// Xóa option
delete_option( 'jvcf_recipient_email' );