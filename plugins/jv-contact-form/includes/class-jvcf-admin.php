<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class JV_Contact_Form_Admin {

    public static function register_menu() {
        add_menu_page(
            __( 'JV Contact Form', 'jv-contact-form' ),
            'JV Contact Form',
            'manage_options',
            'jvcf',
            [ __CLASS__, 'render_submissions_page' ],
            'dashicons-email',
            26
        );

        add_submenu_page(
            'jvcf',
            __( 'Cài đặt', 'jv-contact-form' ),
            __( 'Cài đặt', 'jv-contact-form' ),
            'manage_options',
            'jvcf-settings',
            [ __CLASS__, 'render_settings_page' ]
        );
    }

    public static function register_settings() {
        register_setting( 'jvcf_settings_group', 'jvcf_recipient_email', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_email',
            'default'           => get_option( 'admin_email' ),
        ] );
    }

    public static function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>JV Contact Form - Cài đặt</h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'jvcf_settings_group' ); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="jvcf_recipient_email">Email nhận liên hệ</label></th>
                        <td>
                            <input name="jvcf_recipient_email" id="jvcf_recipient_email" type="email" class="regular-text"
                                   value="<?php echo esc_attr( get_option( 'jvcf_recipient_email' ) ); ?>" required>
                            <p class="description">Email sẽ nhận thông tin từ form liên hệ.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public static function render_submissions_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'jvcf_submissions';
        $items = $wpdb->get_results( "SELECT * FROM $table ORDER BY created_at DESC LIMIT 200" );
        ?>
        <div class="wrap">
            <h1>JV Contact Form - Danh sách liên hệ</h1>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Điểm đến</th>
                        <th>Ngày khởi hành</th>
                        <th>Tin nhắn</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ( $items ) : foreach ( $items as $i => $it ) : ?>
                    <tr>
                        <td><?php echo intval( $it->id ); ?></td>
                        <td><?php echo esc_html( $it->name ); ?></td>
                        <td><?php echo esc_html( $it->email ); ?></td>
                        <td><?php echo esc_html( $it->phone ); ?></td>
                        <td><?php echo esc_html( $it->destination ); ?></td>
                        <td><?php echo esc_html( $it->travel_date ); ?></td>
                        <td><?php echo esc_html( $it->message ); ?></td>
                        <td><?php echo esc_html( $it->created_at ); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="8">Chưa có dữ liệu.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}