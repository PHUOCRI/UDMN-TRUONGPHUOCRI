<?php
/**
 * Plugin Name: JV Contact Form
 * Description: Form liên hệ cho web du lịch – shortcode [jv_contact_form], lưu CSDL và gửi email.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: jv-contact-form
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'JVCF_VERSION', '1.0.0' );
define( 'JVCF_PATH', plugin_dir_path( __FILE__ ) );
define( 'JVCF_URL', plugin_dir_url( __FILE__ ) );

require_once JVCF_PATH . 'includes/class-jvcf-activator.php';
require_once JVCF_PATH . 'includes/class-jvcf-admin.php';
require_once JVCF_PATH . 'includes/class-jvcf-form.php';

/**
 * Kích hoạt: tạo bảng lưu liên hệ + option mặc định.
 */
function jvcf_activate() {
    JV_Contact_Form_Activator::activate();
}
register_activation_hook( __FILE__, 'jvcf_activate' );

/**
 * Tải asset frontend và đăng ký shortcode.
 */
function jvcf_init() {
    // Shortcode
    add_shortcode( 'jv_contact_form', [ 'JV_Contact_Form_Form', 'shortcode' ] );

    // Assets
    add_action( 'wp_enqueue_scripts', function() {
        wp_register_style( 'jvcf-style', JVCF_URL . 'assets/css/style.css', [], JVCF_VERSION );
        wp_register_script( 'jvcf-form', JVCF_URL . 'assets/js/form.js', [ 'jquery' ], JVCF_VERSION, true );
        wp_localize_script( 'jvcf-form', 'JVCF', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'jvcf_submit' ),
            'success' => __( 'Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm.', 'jv-contact-form' ),
            'error'   => __( 'Đã có lỗi xảy ra, vui lòng thử lại.', 'jv-contact-form' ),
        ] );
    } );

    // AJAX submit (logged in + guest)
    add_action( 'wp_ajax_jvcf_submit', [ 'JV_Contact_Form_Form', 'handle_submit' ] );
    add_action( 'wp_ajax_nopriv_jvcf_submit', [ 'JV_Contact_Form_Form', 'handle_submit' ] );

    // Admin menu
    add_action( 'admin_menu', [ 'JV_Contact_Form_Admin', 'register_menu' ] );
    add_action( 'admin_init', [ 'JV_Contact_Form_Admin', 'register_settings' ] );
}
add_action( 'init', 'jvcf_init' );