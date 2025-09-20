<?php
/**
 * Automatic Multilingual Setup Script
 * Run this script to automatically configure Polylang for VJL Travel Plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_Multilingual_Setup {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_vjl_setup_multilingual', array($this, 'handle_setup'));
    }
    
    public function add_admin_menu() {
        add_submenu_page(
            'vjl-travel-settings',
            'Multilingual Setup',
            'Multilingual Setup',
            'manage_options',
            'vjl-multilingual-setup',
            array($this, 'admin_page')
        );
    }
    
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>🌐 VJL Travel - Multilingual Setup</h1>
            
            <div class="notice notice-info">
                <p><strong>Hướng dẫn:</strong> Script này sẽ tự động cấu hình Polylang cho VJL Travel Plugin.</p>
            </div>
            
            <div class="card">
                <h2>📋 Checklist trước khi chạy</h2>
                <ul>
                    <li>✅ Polylang plugin đã được kích hoạt</li>
                    <li>✅ Ít nhất 2 ngôn ngữ đã được thêm (Tiếng Việt + English)</li>
                    <li>✅ Ngôn ngữ mặc định đã được thiết lập</li>
                </ul>
            </div>
            
            <div class="card">
                <h2>🚀 Tự động cấu hình</h2>
                <p>Click nút bên dưới để tự động cấu hình:</p>
                <button id="setup-multilingual" class="button button-primary button-large">
                    🔧 Cấu hình đa ngôn ngữ
                </button>
                <div id="setup-progress" style="display: none;">
                    <p>Đang cấu hình...</p>
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                </div>
                <div id="setup-result"></div>
            </div>
            
            <div class="card">
                <h2>📖 Hướng dẫn thủ công</h2>
                <p>Nếu tự động cấu hình không hoạt động, bạn có thể làm theo hướng dẫn thủ công:</p>
                <ol>
                    <li><strong>Languages → Settings → Post types</strong>: Bật Tours, Destinations, Services, Testimonials, Gallery</li>
                    <li><strong>Languages → Settings → Taxonomies</strong>: Bật tất cả custom taxonomies</li>
                    <li><strong>Languages → Settings → Synchronization</strong>: Bật Custom fields, Post meta, Comments</li>
                    <li><strong>Languages → String translations</strong>: Dịch các strings của plugin</li>
                </ol>
            </div>
        </div>
        
        <style>
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-fill {
            height: 100%;
            background-color: #0073aa;
            width: 0%;
            transition: width 0.3s ease;
        }
        .card {
            background: white;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
        }
        .success { color: #46b450; }
        .error { color: #dc3232; }
        .warning { color: #ffb900; }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            $('#setup-multilingual').click(function() {
                var $button = $(this);
                var $progress = $('#setup-progress');
                var $result = $('#setup-result');
                
                $button.prop('disabled', true);
                $progress.show();
                $result.empty();
                
                // Simulate progress
                var progress = 0;
                var interval = setInterval(function() {
                    progress += 10;
                    $('.progress-fill').css('width', progress + '%');
                    
                    if (progress >= 100) {
                        clearInterval(interval);
                        $progress.hide();
                        $result.html('<div class="notice notice-success"><p>✅ Cấu hình hoàn tất! Vui lòng kiểm tra các settings.</p></div>');
                        $button.prop('disabled', false);
                    }
                }, 200);
                
                // AJAX call
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'vjl_setup_multilingual',
                        nonce: '<?php echo wp_create_nonce('vjl_multilingual_setup'); ?>'
                    },
                    success: function(response) {
                        clearInterval(interval);
                        $progress.hide();
                        
                        if (response.success) {
                            $result.html('<div class="notice notice-success"><p>✅ ' + response.data.message + '</p></div>');
                        } else {
                            $result.html('<div class="notice notice-error"><p>❌ ' + response.data + '</p></div>');
                        }
                        
                        $button.prop('disabled', false);
                    },
                    error: function() {
                        clearInterval(interval);
                        $progress.hide();
                        $result.html('<div class="notice notice-error"><p>❌ Có lỗi xảy ra khi cấu hình.</p></div>');
                        $button.prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    public function handle_setup() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'vjl_multilingual_setup')) {
            wp_send_json_error('Invalid nonce');
            return;
        }
        
        // Check if Polylang is active
        if (!function_exists('pll_register_string')) {
            wp_send_json_error('Polylang plugin is not active');
            return;
        }
        
        $results = array();
        
        // 1. Configure post types
        $post_types = array('tours', 'destinations', 'services', 'testimonials', 'gallery');
        $results[] = $this->configure_post_types($post_types);
        
        // 2. Configure taxonomies
        $taxonomies = array(
            'tour_category', 'tour_tag',
            'destination_type', 'destination_tag',
            'service_category',
            'gallery_category'
        );
        $results[] = $this->configure_taxonomies($taxonomies);
        
        // 3. Configure synchronization
        $results[] = $this->configure_synchronization();
        
        // 4. Register plugin strings
        $results[] = $this->register_plugin_strings();
        
        // 5. Configure URL settings
        $results[] = $this->configure_url_settings();
        
        wp_send_json_success(array(
            'message' => 'Multilingual setup completed successfully!',
            'details' => $results
        ));
    }
    
    private function configure_post_types($post_types) {
        $option_name = 'polylang';
        $options = get_option($option_name, array());
        
        if (!isset($options['post_types'])) {
            $options['post_types'] = array();
        }
        
        foreach ($post_types as $post_type) {
            $options['post_types'][$post_type] = 1;
        }
        
        update_option($option_name, $options);
        
        return "✅ Post types configured: " . implode(', ', $post_types);
    }
    
    private function configure_taxonomies($taxonomies) {
        $option_name = 'polylang';
        $options = get_option($option_name, array());
        
        if (!isset($options['taxonomies'])) {
            $options['taxonomies'] = array();
        }
        
        foreach ($taxonomies as $taxonomy) {
            $options['taxonomies'][$taxonomy] = 1;
        }
        
        update_option($option_name, $options);
        
        return "✅ Taxonomies configured: " . implode(', ', $taxonomies);
    }
    
    private function configure_synchronization() {
        $option_name = 'polylang';
        $options = get_option($option_name, array());
        
        $sync_options = array(
            'sync' => array(
                'post_meta' => 1,
                'comment_meta' => 1,
                'term_meta' => 1,
                'nav_menu' => 1,
                'sticky_posts' => 1,
                'default_lang_meta' => 1,
                'post_date' => 1,
                'post_parent' => 1,
                'post_template' => 1,
                'post_format' => 1,
                'post_status' => 1,
                'post_content' => 1,
                'post_title' => 1,
                'post_excerpt' => 1,
                'post_name' => 1,
                'post_author' => 1,
                'post_date_gmt' => 1,
                'post_modified' => 1,
                'post_modified_gmt' => 1,
                'post_content_filtered' => 1,
                'post_mime_type' => 1,
                'comment_count' => 1,
                'menu_order' => 1,
                'post_password' => 1,
                'post_type' => 1,
                'post_category' => 1,
                'post_tag' => 1,
                'post_thumbnail' => 1,
                'post_meta' => 1,
                'comment_meta' => 1,
                'term_meta' => 1,
                'nav_menu' => 1,
                'sticky_posts' => 1,
                'default_lang_meta' => 1,
                'post_date' => 1,
                'post_parent' => 1,
                'post_template' => 1,
                'post_format' => 1,
                'post_status' => 1,
                'post_content' => 1,
                'post_title' => 1,
                'post_excerpt' => 1,
                'post_name' => 1,
                'post_author' => 1,
                'post_date_gmt' => 1,
                'post_modified' => 1,
                'post_modified_gmt' => 1,
                'post_content_filtered' => 1,
                'post_mime_type' => 1,
                'comment_count' => 1,
                'menu_order' => 1,
                'post_password' => 1,
                'post_type' => 1,
                'post_category' => 1,
                'post_tag' => 1,
                'post_thumbnail' => 1
            )
        );
        
        $options = array_merge($options, $sync_options);
        update_option($option_name, $options);
        
        return "✅ Synchronization configured";
    }
    
    private function register_plugin_strings() {
        if (!function_exists('pll_register_string')) {
            return "❌ Polylang not available for string registration";
        }
        
        $strings = array(
            'Contact Form Title' => 'Liên hệ tư vấn tour',
            'Name Label' => 'Họ và tên',
            'Email Label' => 'Email',
            'Phone Label' => 'Số điện thoại',
            'Submit Button' => 'Gửi tin nhắn',
            'Book Now' => 'Đặt ngay',
            'Learn More' => 'Tìm hiểu thêm',
            'Price' => 'Giá',
            'Duration' => 'Thời gian',
            'Rating' => 'Đánh giá',
            'Tours' => 'Tours',
            'Destinations' => 'Điểm đến',
            'Contact' => 'Liên hệ',
            'Home' => 'Trang chủ'
        );
        
        foreach ($strings as $name => $string) {
            pll_register_string($name, $string, 'vjl-travel');
        }
        
        return "✅ Plugin strings registered: " . count($strings) . " strings";
    }
    
    private function configure_url_settings() {
        $option_name = 'polylang';
        $options = get_option($option_name, array());
        
        $url_options = array(
            'force_lang' => 1,
            'rewrite' => 1,
            'hide_default' => 0,
            'redirect_lang' => 1,
            'browser' => 1,
            'media_support' => 1,
            'uninstall' => 0
        );
        
        $options = array_merge($options, $url_options);
        update_option($option_name, $options);
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        return "✅ URL settings configured and rewrite rules flushed";
    }
}

// Initialize the setup class
new VJL_Travel_Multilingual_Setup();
