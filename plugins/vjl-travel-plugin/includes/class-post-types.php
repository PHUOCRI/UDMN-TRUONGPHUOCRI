<?php
/**
 * Custom Post Types
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_Post_Types {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
    }
    
    public function register_post_types() {
        // Tours Post Type
        $this->register_tours_post_type();
        
        // Destinations Post Type
        $this->register_destinations_post_type();
        
        // Services Post Type
        $this->register_services_post_type();
        
        // Testimonials Post Type
        $this->register_testimonials_post_type();
        
        // Gallery Post Type
        $this->register_gallery_post_type();
    }
    
    private function register_tours_post_type() {
        $labels = array(
            'name' => __('Tours', 'vjl-travel'),
            'singular_name' => __('Tour', 'vjl-travel'),
            'menu_name' => __('Tours', 'vjl-travel'),
            'add_new' => __('Thêm Tour mới', 'vjl-travel'),
            'add_new_item' => __('Thêm Tour mới', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa Tour', 'vjl-travel'),
            'new_item' => __('Tour mới', 'vjl-travel'),
            'view_item' => __('Xem Tour', 'vjl-travel'),
            'search_items' => __('Tìm kiếm Tours', 'vjl-travel'),
            'not_found' => __('Không tìm thấy Tours', 'vjl-travel'),
            'not_found_in_trash' => __('Không có Tours trong thùng rác', 'vjl-travel'),
            'all_items' => __('Tất cả Tours', 'vjl-travel'),
        );
        
        $args = array(
            'labels' => $labels,
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
            'taxonomies' => array('tour_category', 'tour_tag'),
        );
        
        register_post_type('tours', $args);
    }
    
    private function register_destinations_post_type() {
        $labels = array(
            'name' => __('Điểm đến', 'vjl-travel'),
            'singular_name' => __('Điểm đến', 'vjl-travel'),
            'menu_name' => __('Điểm đến', 'vjl-travel'),
            'add_new' => __('Thêm điểm đến mới', 'vjl-travel'),
            'add_new_item' => __('Thêm điểm đến mới', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa điểm đến', 'vjl-travel'),
            'new_item' => __('Điểm đến mới', 'vjl-travel'),
            'view_item' => __('Xem điểm đến', 'vjl-travel'),
            'search_items' => __('Tìm kiếm điểm đến', 'vjl-travel'),
            'not_found' => __('Không tìm thấy điểm đến', 'vjl-travel'),
            'not_found_in_trash' => __('Không có điểm đến trong thùng rác', 'vjl-travel'),
            'all_items' => __('Tất cả điểm đến', 'vjl-travel'),
        );
        
        $args = array(
            'labels' => $labels,
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
            'taxonomies' => array('destination_type', 'destination_tag'),
        );
        
        register_post_type('destinations', $args);
    }
    
    private function register_services_post_type() {
        $labels = array(
            'name' => __('Dịch vụ', 'vjl-travel'),
            'singular_name' => __('Dịch vụ', 'vjl-travel'),
            'menu_name' => __('Dịch vụ', 'vjl-travel'),
            'add_new' => __('Thêm dịch vụ mới', 'vjl-travel'),
            'add_new_item' => __('Thêm dịch vụ mới', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa dịch vụ', 'vjl-travel'),
            'new_item' => __('Dịch vụ mới', 'vjl-travel'),
            'view_item' => __('Xem dịch vụ', 'vjl-travel'),
            'search_items' => __('Tìm kiếm dịch vụ', 'vjl-travel'),
            'not_found' => __('Không tìm thấy dịch vụ', 'vjl-travel'),
            'not_found_in_trash' => __('Không có dịch vụ trong thùng rác', 'vjl-travel'),
            'all_items' => __('Tất cả dịch vụ', 'vjl-travel'),
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'services'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 7,
            'menu_icon' => 'dashicons-admin-tools',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'taxonomies' => array('service_category'),
        );
        
        register_post_type('services', $args);
    }
    
    private function register_testimonials_post_type() {
        $labels = array(
            'name' => __('Đánh giá', 'vjl-travel'),
            'singular_name' => __('Đánh giá', 'vjl-travel'),
            'menu_name' => __('Đánh giá', 'vjl-travel'),
            'add_new' => __('Thêm đánh giá mới', 'vjl-travel'),
            'add_new_item' => __('Thêm đánh giá mới', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa đánh giá', 'vjl-travel'),
            'new_item' => __('Đánh giá mới', 'vjl-travel'),
            'view_item' => __('Xem đánh giá', 'vjl-travel'),
            'search_items' => __('Tìm kiếm đánh giá', 'vjl-travel'),
            'not_found' => __('Không tìm thấy đánh giá', 'vjl-travel'),
            'not_found_in_trash' => __('Không có đánh giá trong thùng rác', 'vjl-travel'),
            'all_items' => __('Tất cả đánh giá', 'vjl-travel'),
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'testimonials'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 8,
            'menu_icon' => 'dashicons-star-filled',
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        );
        
        register_post_type('testimonials', $args);
    }
    
    private function register_gallery_post_type() {
        $labels = array(
            'name' => __('Thư viện ảnh', 'vjl-travel'),
            'singular_name' => __('Thư viện ảnh', 'vjl-travel'),
            'menu_name' => __('Thư viện ảnh', 'vjl-travel'),
            'add_new' => __('Thêm ảnh mới', 'vjl-travel'),
            'add_new_item' => __('Thêm ảnh mới', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa ảnh', 'vjl-travel'),
            'new_item' => __('Ảnh mới', 'vjl-travel'),
            'view_item' => __('Xem ảnh', 'vjl-travel'),
            'search_items' => __('Tìm kiếm ảnh', 'vjl-travel'),
            'not_found' => __('Không tìm thấy ảnh', 'vjl-travel'),
            'not_found_in_trash' => __('Không có ảnh trong thùng rác', 'vjl-travel'),
            'all_items' => __('Tất cả ảnh', 'vjl-travel'),
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'gallery'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 9,
            'menu_icon' => 'dashicons-format-gallery',
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'taxonomies' => array('gallery_category'),
        );
        
        register_post_type('gallery', $args);
    }
}
