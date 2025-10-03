<?php
/**
 * Uninstall handler for JV Custom Login Logo
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete options
$option_key = 'jvcll_logo_url';

delete_option( $option_key );

global $wpdb;
// For multisite safety, also delete in sitemeta if used (not necessary here but harmless)
if ( is_multisite() ) {
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
    foreach ( $blog_ids as $blog_id ) {
        switch_to_blog( (int) $blog_id );
        delete_option( $option_key );
        restore_current_blog();
    }
}

// Note: We intentionally do NOT delete files in wp-content/jv-login-logo to avoid data loss.
