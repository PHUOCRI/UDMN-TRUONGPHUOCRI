<?php
/**
 * Plugin Name: VJL Travel Plugin (Simple)
 * Description: Plugin du lịch Huế - Version đơn giản để test
 * Version: 1.0.0
 * Author: VJL Links
 * Text Domain: vjl-travel
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('VJL_TRAVEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VJL_TRAVEL_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('VJL_TRAVEL_PLUGIN_VERSION', '1.0.0');

/**
 * Simple Plugin Class
 */
class VJL_Travel_Plugin_Simple {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Register custom post types
        $this->register_post_types();
        
        // Register custom taxonomies
        $this->register_taxonomies();
        
        // Add shortcode
        add_shortcode('vjl_contact_form', array($this, 'contact_form_shortcode'));
        
        // AJAX handlers
        add_action('wp_ajax_vjl_contact_submit', array($this, 'handle_contact_submission'));
        add_action('wp_ajax_nopriv_vjl_contact_submit', array($this, 'handle_contact_submission'));
        
        // Enqueue scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    public function register_post_types() {
        // Tours Post Type
        register_post_type('tours', array(
            'labels' => array(
                'name' => 'Tours',
                'singular_name' => 'Tour',
                'add_new' => 'Thêm Tour mới',
                'add_new_item' => 'Thêm Tour mới',
                'edit_item' => 'Chỉnh sửa Tour',
                'new_item' => 'Tour mới',
                'view_item' => 'Xem Tour',
                'search_items' => 'Tìm kiếm Tours',
                'not_found' => 'Không tìm thấy Tours',
                'not_found_in_trash' => 'Không có Tours trong thùng rác',
                'all_items' => 'Tất cả Tours',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'tours'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-camera',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments'),
        ));
        
        // Destinations Post Type
        register_post_type('destinations', array(
            'labels' => array(
                'name' => 'Điểm đến',
                'singular_name' => 'Điểm đến',
                'add_new' => 'Thêm điểm đến mới',
                'add_new_item' => 'Thêm điểm đến mới',
                'edit_item' => 'Chỉnh sửa điểm đến',
                'new_item' => 'Điểm đến mới',
                'view_item' => 'Xem điểm đến',
                'search_items' => 'Tìm kiếm điểm đến',
                'not_found' => 'Không tìm thấy điểm đến',
                'not_found_in_trash' => 'Không có điểm đến trong thùng rác',
                'all_items' => 'Tất cả điểm đến',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'destinations'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 6,
            'menu_icon' => 'dashicons-location',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments'),
        ));
    }
    
    public function register_taxonomies() {
        // Tour Categories
        register_taxonomy('tour_category', 'tours', array(
            'labels' => array(
                'name' => 'Danh mục Tour',
                'singular_name' => 'Danh mục Tour',
                'search_items' => 'Tìm kiếm danh mục',
                'all_items' => 'Tất cả danh mục',
                'parent_item' => 'Danh mục cha',
                'parent_item_colon' => 'Danh mục cha:',
                'edit_item' => 'Chỉnh sửa danh mục',
                'update_item' => 'Cập nhật danh mục',
                'add_new_item' => 'Thêm danh mục mới',
                'new_item_name' => 'Tên danh mục mới',
                'menu_name' => 'Danh mục Tour',
            ),
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'tour-category'),
            'query_var' => true,
        ));
        
        // Destination Types
        register_taxonomy('destination_type', 'destinations', array(
            'labels' => array(
                'name' => 'Loại điểm đến',
                'singular_name' => 'Loại điểm đến',
                'search_items' => 'Tìm kiếm loại',
                'all_items' => 'Tất cả loại',
                'parent_item' => 'Loại cha',
                'parent_item_colon' => 'Loại cha:',
                'edit_item' => 'Chỉnh sửa loại',
                'update_item' => 'Cập nhật loại',
                'add_new_item' => 'Thêm loại mới',
                'new_item_name' => 'Tên loại mới',
                'menu_name' => 'Loại điểm đến',
            ),
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'destination-type'),
            'query_var' => true,
        ));
    }
    
    public function contact_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => 'Liên hệ tư vấn tour',
        ), $atts);
        
        ob_start();
        ?>
        <div class="vjl-contact-form-wrapper">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            <form class="vjl-contact-form" method="post">
                <div class="form-group">
                    <label>Họ và tên *</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Điện thoại</label>
                    <input type="tel" name="phone">
                </div>
                
                <div class="form-group">
                    <label>Tin nhắn</label>
                    <textarea name="message" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="consent" value="1" required>
                        Tôi đồng ý cho phép website lưu thông tin để tư vấn.
                    </label>
                </div>
                
                <button type="submit">Gửi liên hệ</button>
                <div class="form-status"></div>
                
                <input type="hidden" name="action" value="vjl_contact_submit">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('vjl_contact_nonce'); ?>">
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function handle_contact_submission() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'vjl_contact_nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        
        // Sanitize data
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $message = sanitize_textarea_field($_POST['message']);
        $consent = isset($_POST['consent']) ? 1 : 0;
        
        // Validate required fields
        if (empty($name) || empty($email) || !is_email($email)) {
            wp_send_json_error('Vui lòng nhập họ tên và email hợp lệ.');
        }
        
        // Send email notification
        $to = get_option('admin_email');
        $subject = 'Liên hệ mới từ website du lịch';
        $body = "Thông tin liên hệ mới:\n\n";
        $body .= "Họ tên: " . $name . "\n";
        $body .= "Email: " . $email . "\n";
        $body .= "Điện thoại: " . $phone . "\n";
        $body .= "Tin nhắn: " . $message . "\n";
        
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        if (wp_mail($to, $subject, $body, $headers)) {
            wp_send_json_success('Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm.');
        } else {
            wp_send_json_error('Có lỗi xảy ra khi gửi email.');
        }
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('jquery');
        
        wp_register_script(
            'vjl-contact-form',
            VJL_TRAVEL_PLUGIN_URL . 'assets/js/contact-form.js',
            array('jquery'),
            VJL_TRAVEL_PLUGIN_VERSION,
            true
        );
        
        wp_localize_script('vjl-contact-form', 'vjlContact', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('vjl_contact_nonce'),
            'messages' => array(
                'success' => 'Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm.',
                'error' => 'Đã có lỗi xảy ra, vui lòng thử lại.',
                'sending' => 'Đang gửi...'
            )
        ));
        
        wp_register_style(
            'vjl-contact-form',
            VJL_TRAVEL_PLUGIN_URL . 'assets/css/contact-form.css',
            array(),
            VJL_TRAVEL_PLUGIN_VERSION
        );
    }
    
    public function activate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Create default options
        if (!get_option('vjl_travel_phone')) {
            update_option('vjl_travel_phone', '0349421736');
        }
        if (!get_option('vjl_travel_email')) {
            update_option('vjl_travel_email', 'ri01652965673@gmail.com');
        }
    }
    
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}

// Initialize the plugin
new VJL_Travel_Plugin_Simple();
