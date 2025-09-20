<?php
/**
 * Custom Taxonomies
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_Taxonomies {
    
    public function __construct() {
        add_action('init', array($this, 'register_taxonomies'));
    }
    
    public function register_taxonomies() {
        // Tour Taxonomies
        $this->register_tour_taxonomies();
        
        // Destination Taxonomies
        $this->register_destination_taxonomies();
        
        // Service Taxonomies
        $this->register_service_taxonomies();
        
        // Gallery Taxonomies
        $this->register_gallery_taxonomies();
    }
    
    private function register_tour_taxonomies() {
        // Tour Categories
        $labels = array(
            'name' => __('Danh mục Tour', 'vjl-travel'),
            'singular_name' => __('Danh mục Tour', 'vjl-travel'),
            'search_items' => __('Tìm kiếm danh mục', 'vjl-travel'),
            'all_items' => __('Tất cả danh mục', 'vjl-travel'),
            'parent_item' => __('Danh mục cha', 'vjl-travel'),
            'parent_item_colon' => __('Danh mục cha:', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa danh mục', 'vjl-travel'),
            'update_item' => __('Cập nhật danh mục', 'vjl-travel'),
            'add_new_item' => __('Thêm danh mục mới', 'vjl-travel'),
            'new_item_name' => __('Tên danh mục mới', 'vjl-travel'),
            'menu_name' => __('Danh mục Tour', 'vjl-travel'),
        );
        
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'tour-category'),
            'show_in_rest' => true,
        );
        
        register_taxonomy('tour_category', 'tours', $args);
        
        // Tour Tags
        $labels = array(
            'name' => __('Thẻ Tour', 'vjl-travel'),
            'singular_name' => __('Thẻ Tour', 'vjl-travel'),
            'search_items' => __('Tìm kiếm thẻ', 'vjl-travel'),
            'all_items' => __('Tất cả thẻ', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa thẻ', 'vjl-travel'),
            'update_item' => __('Cập nhật thẻ', 'vjl-travel'),
            'add_new_item' => __('Thêm thẻ mới', 'vjl-travel'),
            'new_item_name' => __('Tên thẻ mới', 'vjl-travel'),
            'menu_name' => __('Thẻ Tour', 'vjl-travel'),
        );
        
        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'tour-tag'),
            'show_in_rest' => true,
        );
        
        register_taxonomy('tour_tag', 'tours', $args);
    }
    
    private function register_destination_taxonomies() {
        // Destination Types
        $labels = array(
            'name' => __('Loại điểm đến', 'vjl-travel'),
            'singular_name' => __('Loại điểm đến', 'vjl-travel'),
            'search_items' => __('Tìm kiếm loại', 'vjl-travel'),
            'all_items' => __('Tất cả loại', 'vjl-travel'),
            'parent_item' => __('Loại cha', 'vjl-travel'),
            'parent_item_colon' => __('Loại cha:', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa loại', 'vjl-travel'),
            'update_item' => __('Cập nhật loại', 'vjl-travel'),
            'add_new_item' => __('Thêm loại mới', 'vjl-travel'),
            'new_item_name' => __('Tên loại mới', 'vjl-travel'),
            'menu_name' => __('Loại điểm đến', 'vjl-travel'),
        );
        
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'destination-type'),
            'show_in_rest' => true,
        );
        
        register_taxonomy('destination_type', 'destinations', $args);
        
        // Destination Tags
        $labels = array(
            'name' => __('Thẻ điểm đến', 'vjl-travel'),
            'singular_name' => __('Thẻ điểm đến', 'vjl-travel'),
            'search_items' => __('Tìm kiếm thẻ', 'vjl-travel'),
            'all_items' => __('Tất cả thẻ', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa thẻ', 'vjl-travel'),
            'update_item' => __('Cập nhật thẻ', 'vjl-travel'),
            'add_new_item' => __('Thêm thẻ mới', 'vjl-travel'),
            'new_item_name' => __('Tên thẻ mới', 'vjl-travel'),
            'menu_name' => __('Thẻ điểm đến', 'vjl-travel'),
        );
        
        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'destination-tag'),
            'show_in_rest' => true,
        );
        
        register_taxonomy('destination_tag', 'destinations', $args);
    }
    
    private function register_service_taxonomies() {
        // Service Categories
        $labels = array(
            'name' => __('Danh mục dịch vụ', 'vjl-travel'),
            'singular_name' => __('Danh mục dịch vụ', 'vjl-travel'),
            'search_items' => __('Tìm kiếm danh mục', 'vjl-travel'),
            'all_items' => __('Tất cả danh mục', 'vjl-travel'),
            'parent_item' => __('Danh mục cha', 'vjl-travel'),
            'parent_item_colon' => __('Danh mục cha:', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa danh mục', 'vjl-travel'),
            'update_item' => __('Cập nhật danh mục', 'vjl-travel'),
            'add_new_item' => __('Thêm danh mục mới', 'vjl-travel'),
            'new_item_name' => __('Tên danh mục mới', 'vjl-travel'),
            'menu_name' => __('Danh mục dịch vụ', 'vjl-travel'),
        );
        
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'service-category'),
            'show_in_rest' => true,
        );
        
        register_taxonomy('service_category', 'services', $args);
    }
    
    private function register_gallery_taxonomies() {
        // Gallery Categories
        $labels = array(
            'name' => __('Danh mục thư viện', 'vjl-travel'),
            'singular_name' => __('Danh mục thư viện', 'vjl-travel'),
            'search_items' => __('Tìm kiếm danh mục', 'vjl-travel'),
            'all_items' => __('Tất cả danh mục', 'vjl-travel'),
            'parent_item' => __('Danh mục cha', 'vjl-travel'),
            'parent_item_colon' => __('Danh mục cha:', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa danh mục', 'vjl-travel'),
            'update_item' => __('Cập nhật danh mục', 'vjl-travel'),
            'add_new_item' => __('Thêm danh mục mới', 'vjl-travel'),
            'new_item_name' => __('Tên danh mục mới', 'vjl-travel'),
            'menu_name' => __('Danh mục thư viện', 'vjl-travel'),
        );
        
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'gallery-category'),
            'show_in_rest' => true,
        );
        
        register_taxonomy('gallery_category', 'gallery', $args);
    }
}
