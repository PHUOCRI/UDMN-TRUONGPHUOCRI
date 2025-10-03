<?php
/**
 * Custom Post Type: Dịch vụ (dich-vu)
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!function_exists('dln_register_cpt_dich_vu')) {
    function dln_register_cpt_dich_vu() {
        $labels = array(
            'name'                  => _x('Dịch vụ', 'Post type general name', 'dulichvietnhat'),
            'singular_name'         => _x('Dịch vụ', 'Post type singular name', 'dulichvietnhat'),
            'menu_name'             => _x('Dịch vụ', 'Admin Menu text', 'dulichvietnhat'),
            'name_admin_bar'        => _x('Dịch vụ', 'Add New on Toolbar', 'dulichvietnhat'),
            'add_new'               => __('Thêm mới', 'dulichvietnhat'),
            'add_new_item'          => __('Thêm dịch vụ mới', 'dulichvietnhat'),
            'new_item'              => __('Dịch vụ mới', 'dulichvietnhat'),
            'edit_item'             => __('Chỉnh sửa dịch vụ', 'dulichvietnhat'),
            'view_item'             => __('Xem dịch vụ', 'dulichvietnhat'),
            'all_items'             => __('Tất cả dịch vụ', 'dulichvietnhat'),
            'search_items'          => __('Tìm kiếm dịch vụ', 'dulichvietnhat'),
            'parent_item_colon'     => __('Dịch vụ cha:', 'dulichvietnhat'),
            'not_found'             => __('Không tìm thấy', 'dulichvietnhat'),
            'not_found_in_trash'    => __('Không có trong thùng rác', 'dulichvietnhat'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'dich-vu'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 26,
            'menu_icon'          => 'dashicons-hammer',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
            'show_in_rest'       => true, // Gutenberg & REST
        );

        register_post_type('dich-vu', $args);
    }
}
add_action('init', 'dln_register_cpt_dich_vu');

// Optional: ensure rewrite rules are flushed on theme switch so the new slug works immediately
if (!function_exists('dln_flush_dich_vu_rewrite')) {
    function dln_flush_dich_vu_rewrite() {
        dln_register_cpt_dich_vu();
        flush_rewrite_rules();
    }
}
add_action('after_switch_theme', 'dln_flush_dich_vu_rewrite');
