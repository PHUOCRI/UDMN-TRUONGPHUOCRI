<?php
/**
 * Plugin Name: JV Custom Login Logo
 * Description: Thay logo trang đăng nhập WordPress và lưu ở thư mục riêng: wp-content/jv-login-logo/
 * Version: 1.0.0
 * Author: JV
 * Text Domain: jv-custom-login-logo
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Constants
if ( ! defined( 'JVCLL_DIR' ) ) {
    define( 'JVCLL_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'JVCLL_URL' ) ) {
    define( 'JVCLL_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'JVCLL_STORE_DIR' ) ) {
    define( 'JVCLL_STORE_DIR', WP_CONTENT_DIR . '/jv-login-logo' );
}
if ( ! defined( 'JVCLL_STORE_URL' ) ) {
    define( 'JVCLL_STORE_URL', content_url( '/jv-login-logo' ) );
}

// Option key
const JVCLL_OPTION_LOGO_URL = 'jvcll_logo_url';

/**
 * Ensure storage directory exists
 */
function jvcll_ensure_dir() {
    if ( ! file_exists( JVCLL_STORE_DIR ) ) {
        wp_mkdir_p( JVCLL_STORE_DIR );
    }
}

/**
 * Add settings page
 */
function jvcll_admin_menu() {
    add_options_page(
        __( 'JV Login Logo', 'jv-custom-login-logo' ),
        __( 'JV Login Logo', 'jv-custom-login-logo' ),
        'manage_options',
        'jvcll-settings',
        'jvcll_render_settings_page'
    );
}
add_action( 'admin_menu', 'jvcll_admin_menu' );

/**
 * Handle upload and settings save
 */
function jvcll_handle_post() {
    if ( ! is_admin() ) return;
    if ( ! current_user_can( 'manage_options' ) ) return;

    if ( ! isset( $_POST['jvcll_action'] ) || $_POST['jvcll_action'] !== 'save' ) return;

    check_admin_referer( 'jvcll_save_settings', 'jvcll_nonce' );

    // Remove logo
    if ( isset( $_POST['jvcll_remove_logo'] ) && $_POST['jvcll_remove_logo'] === '1' ) {
        delete_option( JVCLL_OPTION_LOGO_URL );
        add_settings_error( 'jvcll', 'logo_removed', __( 'Đã xóa logo đăng nhập.', 'jv-custom-login-logo' ), 'updated' );
        return;
    }

    if ( empty( $_FILES['jvcll_logo']['name'] ) ) {
        add_settings_error( 'jvcll', 'no_file', __( 'Vui lòng chọn một file ảnh.', 'jv-custom-login-logo' ) );
        return;
    }

    $file = $_FILES['jvcll_logo'];

    if ( $file['error'] !== UPLOAD_ERR_OK ) {
        add_settings_error( 'jvcll', 'upload_err', sprintf( __( 'Lỗi tải lên (mã %d).', 'jv-custom-login-logo' ), (int) $file['error'] ) );
        return;
    }

    // Security: allow only common image types
    $allowed_mimes = array(
        'jpg|jpeg' => 'image/jpeg',
        'png'      => 'image/png',
        'gif'      => 'image/gif',
        'svg'      => 'image/svg+xml',
        'webp'     => 'image/webp',
    );

    $check = wp_check_filetype( $file['name'], $allowed_mimes );
    if ( empty( $check['ext'] ) || empty( $check['type'] ) ) {
        add_settings_error( 'jvcll', 'mime_err', __( 'Định dạng file không hợp lệ. Hãy chọn JPG, PNG, GIF, SVG hoặc WEBP.', 'jv-custom-login-logo' ) );
        return;
    }

    // Optional: limit size to 2MB
    $max_size = 2 * 1024 * 1024; // 2MB
    if ( ! empty( $file['size'] ) && (int) $file['size'] > $max_size ) {
        add_settings_error( 'jvcll', 'size_err', __( 'Kích thước file vượt quá 2MB.', 'jv-custom-login-logo' ) );
        return;
    }

    jvcll_ensure_dir();

    // Build target path
    $sanitized_name = sanitize_file_name( $file['name'] );
    $unique_name    = wp_unique_filename( JVCLL_STORE_DIR, $sanitized_name );
    $target_path    = trailingslashit( JVCLL_STORE_DIR ) . $unique_name;

    // Move uploaded file
    if ( ! @move_uploaded_file( $file['tmp_name'], $target_path ) ) {
        add_settings_error( 'jvcll', 'move_err', __( 'Không thể lưu file vào thư mục đích.', 'jv-custom-login-logo' ) );
        return;
    }

    // Set proper permissions
    $perms = fileperms( JVCLL_STORE_DIR ) & 0000777;
    @chmod( $target_path, $perms ? $perms : 0644 );

    $logo_url = trailingslashit( JVCLL_STORE_URL ) . rawurlencode( $unique_name );

    update_option( JVCLL_OPTION_LOGO_URL, esc_url_raw( $logo_url ) );

    add_settings_error( 'jvcll', 'saved', __( 'Đã cập nhật logo đăng nhập.', 'jv-custom-login-logo' ), 'updated' );
}
add_action( 'admin_init', 'jvcll_handle_post' );

/**
 * Render settings page
 */
function jvcll_render_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    settings_errors( 'jvcll' );

    $logo_url = esc_url( get_option( JVCLL_OPTION_LOGO_URL, '' ) );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__( 'JV Login Logo', 'jv-custom-login-logo' ); ?></h1>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field( 'jvcll_save_settings', 'jvcll_nonce' ); ?>
            <input type="hidden" name="jvcll_action" value="save" />

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><?php esc_html_e( 'Logo hiện tại', 'jv-custom-login-logo' ); ?></th>
                    <td>
                        <?php if ( $logo_url ) : ?>
                            <img src="<?php echo $logo_url; ?>" alt="logo" style="max-width:240px;height:auto;border:1px solid #ccd0d4;padding:6px;background:#fff;" />
                        <?php else : ?>
                            <em><?php esc_html_e( 'Chưa thiết lập', 'jv-custom-login-logo' ); ?></em>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Tải logo mới', 'jv-custom-login-logo' ); ?></th>
                    <td>
                        <input type="file" name="jvcll_logo" accept="image/*" />
                        <p class="description"><?php esc_html_e( 'Hỗ trợ JPG, PNG, GIF, SVG, WEBP. Tối đa 2MB.', 'jv-custom-login-logo' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Xóa logo', 'jv-custom-login-logo' ); ?></th>
                    <td>
                        <label><input type="checkbox" name="jvcll_remove_logo" value="1" /> <?php esc_html_e( 'Xóa logo hiện tại', 'jv-custom-login-logo' ); ?></label>
                    </td>
                </tr>
            </table>

            <?php submit_button( __( 'Lưu thay đổi', 'jv-custom-login-logo' ) ); ?>
        </form>
    </div>
    <?php
}

/**
 * Apply logo on login screen
 */
function jvcll_login_styles() {
    $logo_url = esc_url( get_option( JVCLL_OPTION_LOGO_URL, '' ) );
    if ( ! $logo_url ) return;

    // Try to detect image dimensions (only for non-SVG)
    $width  = 84;
    $height = 84;

    $path_from_url = null;
    // Map URL back to path if within content directory
    $content_url = content_url();
    if ( strpos( $logo_url, $content_url ) === 0 ) {
        $relative = ltrim( substr( $logo_url, strlen( $content_url ) ), '/' );
        $path_from_url = WP_CONTENT_DIR . '/' . str_replace( '/', DIRECTORY_SEPARATOR, $relative );
    }

    if ( $path_from_url && file_exists( $path_from_url ) ) {
        $type = wp_check_filetype( $path_from_url );
        if ( isset( $type['ext'] ) && strtolower( $type['ext'] ) !== 'svg' ) {
            $size = @getimagesize( $path_from_url );
            if ( is_array( $size ) && isset( $size[0], $size[1] ) ) {
                $width  = (int) $size[0];
                $height = (int) $size[1];
                // Limit too large size
                $max = 320;
                if ( $width > $max ) {
                    $ratio = $max / $width;
                    $width  = (int) round( $width * $ratio );
                    $height = (int) round( $height * $ratio );
                }
            }
        } else {
            // SVG: set a sane default width
            $width  = 200;
            $height = 200;
        }
    }

    // Cache-busting for browser/CDN
    $logo_css_url = $logo_url;
    if ( $path_from_url && file_exists( $path_from_url ) ) {
        $ver = @filemtime( $path_from_url );
        if ( $ver ) {
            $logo_css_url = add_query_arg( 'ver', (int) $ver, $logo_url );
        }
    }

    ?>
    <style id="jvcll-login-logo">
        .login h1 a {
            background-image: url('<?php echo esc_url( $logo_css_url ); ?>') !important;
            background-size: contain !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            width: <?php echo (int) $width; ?>px !important;
            height: <?php echo (int) $height; ?>px !important;
        }
    </style>
    <?php
}
add_action( 'login_enqueue_scripts', 'jvcll_login_styles', 999 );

/**
 * Tweak login URL and title to site home/name
 */
function jvcll_login_headerurl( $url ) {
    return home_url('/');
}
add_filter( 'login_headerurl', 'jvcll_login_headerurl' );

function jvcll_login_headertitle( $title ) {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'jvcll_login_headertitle' );

/**
 * On activation, ensure directory exists
 */
function jvcll_activate() {
    jvcll_ensure_dir();
}
register_activation_hook( __FILE__, 'jvcll_activate' );
