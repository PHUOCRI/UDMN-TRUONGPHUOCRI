<?php
/**
 * Multilingual Support for VJL Travel Plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_Multilingual {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'add_hreflang_tags'));
        add_action('admin_init', array($this, 'register_polylang_strings'));
    }
    
    public function init() {
        // Load text domain
        load_plugin_textdomain('vjl-travel', false, dirname(plugin_basename(__FILE__)) . '/../languages');
        
        // Register Polylang strings
        if (function_exists('pll_register_string')) {
            $this->register_polylang_strings();
        }
    }
    
    public function enqueue_scripts() {
        wp_localize_script('vjl-travel-script', 'vjlMultilingual', array(
            'currentLanguage' => $this->get_current_language(),
            'availableLanguages' => $this->get_available_languages(),
            'strings' => $this->get_js_strings(),
        ));
    }
    
    public function add_hreflang_tags() {
        if (function_exists('pll_the_languages')) {
            $languages = pll_the_languages(array('raw' => 1));
            
            if ($languages) {
                foreach ($languages as $lang) {
                    echo '<link rel="alternate" hreflang="' . esc_attr($lang['locale']) . '" href="' . esc_url($lang['url']) . '" />' . "\n";
                }
            }
        }
    }
    
    public function register_polylang_strings() {
        if (!function_exists('pll_register_string')) {
            return;
        }
        
        // Contact Form Strings
        pll_register_string('Contact Form Title', 'Liên hệ tư vấn tour', 'vjl-travel');
        pll_register_string('Name Label', 'Họ và tên', 'vjl-travel');
        pll_register_string('Email Label', 'Email', 'vjl-travel');
        pll_register_string('Phone Label', 'Số điện thoại', 'vjl-travel');
        pll_register_string('Subject Label', 'Chủ đề', 'vjl-travel');
        pll_register_string('Message Label', 'Nội dung', 'vjl-travel');
        pll_register_string('Submit Button', 'Gửi tin nhắn', 'vjl-travel');
        pll_register_string('Success Message', 'Cảm ơn bạn! Tin nhắn đã được gửi thành công.', 'vjl-travel');
        pll_register_string('Error Message', 'Có lỗi xảy ra. Vui lòng thử lại.', 'vjl-travel');
        
        // Tour Strings
        pll_register_string('Book Now', 'Đặt ngay', 'vjl-travel');
        pll_register_string('Learn More', 'Tìm hiểu thêm', 'vjl-travel');
        pll_register_string('Price', 'Giá', 'vjl-travel');
        pll_register_string('Duration', 'Thời gian', 'vjl-travel');
        pll_register_string('Rating', 'Đánh giá', 'vjl-travel');
        pll_register_string('Reviews', 'Đánh giá', 'vjl-travel');
        pll_register_string('From', 'Từ', 'vjl-travel');
        pll_register_string('To', 'Đến', 'vjl-travel');
        pll_register_string('Departure', 'Khởi hành', 'vjl-travel');
        pll_register_string('Destination', 'Điểm đến', 'vjl-travel');
        pll_register_string('Includes', 'Bao gồm', 'vjl-travel');
        pll_register_string('Excludes', 'Không bao gồm', 'vjl-travel');
        pll_register_string('Schedule', 'Lịch trình', 'vjl-travel');
        pll_register_string('Gallery', 'Thư viện ảnh', 'vjl-travel');
        pll_register_string('Testimonials', 'Đánh giá khách hàng', 'vjl-travel');
        
        // Navigation Strings
        pll_register_string('Home', 'Trang chủ', 'vjl-travel');
        pll_register_string('Tours', 'Tours', 'vjl-travel');
        pll_register_string('Destinations', 'Điểm đến', 'vjl-travel');
        pll_register_string('Services', 'Dịch vụ', 'vjl-travel');
        pll_register_string('About Us', 'Về chúng tôi', 'vjl-travel');
        pll_register_string('Contact', 'Liên hệ', 'vjl-travel');
        pll_register_string('Blog', 'Blog', 'vjl-travel');
        pll_register_string('News', 'Tin tức', 'vjl-travel');
        
        // Common Strings
        pll_register_string('Loading', 'Đang tải...', 'vjl-travel');
        pll_register_string('Error', 'Có lỗi xảy ra', 'vjl-travel');
        pll_register_string('Success', 'Thành công!', 'vjl-travel');
        pll_register_string('Save', 'Lưu', 'vjl-travel');
        pll_register_string('Edit', 'Chỉnh sửa', 'vjl-travel');
        pll_register_string('Delete', 'Xóa', 'vjl-travel');
        pll_register_string('View', 'Xem', 'vjl-travel');
        pll_register_string('Search', 'Tìm kiếm', 'vjl-travel');
        pll_register_string('Filter', 'Lọc', 'vjl-travel');
        pll_register_string('Sort', 'Sắp xếp', 'vjl-travel');
        pll_register_string('Previous', 'Trước', 'vjl-travel');
        pll_register_string('Next', 'Tiếp', 'vjl-travel');
        pll_register_string('Close', 'Đóng', 'vjl-travel');
        pll_register_string('Read More', 'Đọc thêm', 'vjl-travel');
        pll_register_string('Show Less', 'Thu gọn', 'vjl-travel');
        pll_register_string('Show More', 'Xem thêm', 'vjl-travel');
    }
    
    private function get_current_language() {
        if (function_exists('pll_current_language')) {
            return pll_current_language();
        }
        return get_locale();
    }
    
    private function get_available_languages() {
        if (function_exists('pll_the_languages')) {
            $languages = pll_the_languages(array('raw' => 1));
            $available = array();
            
            if ($languages) {
                foreach ($languages as $lang) {
                    $available[] = array(
                        'code' => $lang['slug'],
                        'name' => $lang['name'],
                        'url' => $lang['url'],
                        'current' => $lang['current_lang'],
                    );
                }
            }
            
            return $available;
        }
        
        return array();
    }
    
    private function get_js_strings() {
        return array(
            'loading' => __('Đang tải...', 'vjl-travel'),
            'error' => __('Có lỗi xảy ra', 'vjl-travel'),
            'success' => __('Thành công!', 'vjl-travel'),
            'save' => __('Lưu', 'vjl-travel'),
            'edit' => __('Chỉnh sửa', 'vjl-travel'),
            'delete' => __('Xóa', 'vjl-travel'),
            'view' => __('Xem', 'vjl-travel'),
            'book_now' => __('Đặt ngay', 'vjl-travel'),
            'learn_more' => __('Tìm hiểu thêm', 'vjl-travel'),
            'contact_us' => __('Liên hệ', 'vjl-travel'),
            'read_more' => __('Đọc thêm', 'vjl-travel'),
            'show_less' => __('Thu gọn', 'vjl-travel'),
            'show_more' => __('Xem thêm', 'vjl-travel'),
            'previous' => __('Trước', 'vjl-travel'),
            'next' => __('Tiếp', 'vjl-travel'),
            'close' => __('Đóng', 'vjl-travel'),
            'search' => __('Tìm kiếm', 'vjl-travel'),
            'filter' => __('Lọc', 'vjl-travel'),
            'sort' => __('Sắp xếp', 'vjl-travel'),
            'price' => __('Giá', 'vjl-travel'),
            'duration' => __('Thời gian', 'vjl-travel'),
            'rating' => __('Đánh giá', 'vjl-travel'),
            'reviews' => __('Đánh giá', 'vjl-travel'),
            'from' => __('Từ', 'vjl-travel'),
            'to' => __('Đến', 'vjl-travel'),
            'departure' => __('Khởi hành', 'vjl-travel'),
            'destination' => __('Điểm đến', 'vjl-travel'),
            'includes' => __('Bao gồm', 'vjl-travel'),
            'excludes' => __('Không bao gồm', 'vjl-travel'),
            'schedule' => __('Lịch trình', 'vjl-travel'),
            'gallery' => __('Thư viện ảnh', 'vjl-travel'),
            'testimonials' => __('Đánh giá khách hàng', 'vjl-travel'),
            'services' => __('Dịch vụ', 'vjl-travel'),
            'tours' => __('Tours', 'vjl-travel'),
            'destinations' => __('Điểm đến', 'vjl-travel'),
            'about_us' => __('Về chúng tôi', 'vjl-travel'),
            'contact' => __('Liên hệ', 'vjl-travel'),
            'home' => __('Trang chủ', 'vjl-travel'),
            'blog' => __('Blog', 'vjl-travel'),
            'news' => __('Tin tức', 'vjl-travel'),
        );
    }
}
