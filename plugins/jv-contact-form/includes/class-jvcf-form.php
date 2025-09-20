<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class JV_Contact_Form_Form {

    public static function shortcode( $atts = [] ) {
        wp_enqueue_style( 'jvcf-style' );
        wp_enqueue_script( 'jvcf-form' );

        ob_start();
        $defaults = shortcode_atts( [
            'title' => 'Liên hệ tư vấn tour',
        ], $atts );

        $context = [
            'title' => sanitize_text_field( $defaults['title'] ),
        ];
        include JVCF_PATH . 'templates/form.php';
        return ob_get_clean();
    }

    public static function handle_submit() {
        check_ajax_referer( 'jvcf_submit', 'nonce' );

        $name        = sanitize_text_field( $_POST['name'] ?? '' );
        $email       = sanitize_email( $_POST['email'] ?? '' );
        $phone       = sanitize_text_field( $_POST['phone'] ?? '' );
        $destination = sanitize_text_field( $_POST['destination'] ?? '' );
        $travel_date = sanitize_text_field( $_POST['travel_date'] ?? '' );
        $message     = sanitize_textarea_field( $_POST['message'] ?? '' );
        $consent     = isset( $_POST['consent'] ) ? 1 : 0;

        if ( empty( $name ) || empty( $email ) || ! is_email( $email ) ) {
            wp_send_json_error( [ 'msg' => __( 'Vui lòng nhập họ tên và email hợp lệ.', 'jv-contact-form' ) ], 400 );
        }

        global $wpdb;
        $table = $wpdb->prefix . 'jvcf_submissions';

        $wpdb->insert( $table, [
            'name'        => $name,
            'email'       => $email,
            'phone'       => $phone,
            'destination' => $destination,
            'travel_date' => $travel_date ? date( 'Y-m-d', strtotime( $travel_date ) ) : null,
            'message'     => $message,
            'consent'     => $consent,
            'created_at'  => current_time( 'mysql' ),
        ], [ '%s','%s','%s','%s','%s','%s','%d','%s' ] );

        // Gửi email
        $to      = get_option( 'jvcf_recipient_email', get_option( 'admin_email' ) );
        $subject = 'Liên hệ mới từ website du lịch';
        $body    = sprintf(
            "Họ tên: %s\nEmail: %s\nĐiện thoại: %s\nĐiểm đến: %s\nNgày khởi hành: %s\n\nTin nhắn:\n%s\n",
            $name, $email, $phone, $destination, $travel_date, $message
        );
        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

        wp_mail( $to, $subject, $body, $headers );

        wp_send_json_success( [ 'msg' => __( 'Gửi thành công', 'jv-contact-form' ) ] );
    }
}