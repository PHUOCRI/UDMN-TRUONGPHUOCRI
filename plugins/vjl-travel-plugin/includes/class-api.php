<?php
/**
 * API Functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_API {
    
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_api_routes'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    public function enqueue_scripts() {
        wp_localize_script('vjl-travel-script', 'vjlAPI', array(
            'restUrl' => rest_url('vjl-travel/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }
    
    public function register_api_routes() {
        // Tours API
        register_rest_route('vjl-travel/v1', '/tours', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_tours'),
            'permission_callback' => '__return_true',
        ));
        
        // Destinations API
        register_rest_route('vjl-travel/v1', '/destinations', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_destinations'),
            'permission_callback' => '__return_true',
        ));
        
        // Services API
        register_rest_route('vjl-travel/v1', '/services', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_services'),
            'permission_callback' => '__return_true',
        ));
        
        // Gallery API
        register_rest_route('vjl-travel/v1', '/gallery', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_gallery'),
            'permission_callback' => '__return_true',
        ));
        
        // Testimonials API
        register_rest_route('vjl-travel/v1', '/testimonials', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_testimonials'),
            'permission_callback' => '__return_true',
        ));
        
        // Contact Form API
        register_rest_route('vjl-travel/v1', '/contact', array(
            'methods' => 'POST',
            'callback' => array($this, 'submit_contact'),
            'permission_callback' => '__return_true',
        ));
    }
    
    public function get_tours($request) {
        $params = $request->get_params();
        $limit = isset($params['limit']) ? intval($params['limit']) : 10;
        $category = isset($params['category']) ? sanitize_text_field($params['category']) : '';
        $featured = isset($params['featured']) ? $params['featured'] === 'true' : false;
        
        $args = array(
            'post_type' => 'tours',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'meta_key' => 'tour_featured',
            'orderby' => 'meta_value_num date',
            'order' => 'DESC',
        );
        
        if ($featured) {
            $args['meta_query'] = array(
                array(
                    'key' => 'tour_featured',
                    'value' => '1',
                    'compare' => '='
                )
            );
        }
        
        if (!empty($category)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'tour_category',
                    'field' => 'slug',
                    'terms' => $category,
                )
            );
        }
        
        $query = new WP_Query($args);
        $tours = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $tours[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'content' => get_the_content(),
                    'featured_image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                    'price' => get_field('tour_price'),
                    'duration' => get_field('tour_duration'),
                    'departure' => get_field('tour_departure'),
                    'rating' => get_field('tour_rating'),
                    'featured' => get_field('tour_featured'),
                    'url' => get_permalink(),
                    'categories' => wp_get_post_terms(get_the_ID(), 'tour_category', array('fields' => 'names')),
                );
            }
        }
        
        wp_reset_postdata();
        
        return new WP_REST_Response($tours, 200);
    }
    
    public function get_destinations($request) {
        $params = $request->get_params();
        $limit = isset($params['limit']) ? intval($params['limit']) : 10;
        $type = isset($params['type']) ? sanitize_text_field($params['type']) : '';
        
        $args = array(
            'post_type' => 'destinations',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        if (!empty($type)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'destination_type',
                    'field' => 'slug',
                    'terms' => $type,
                )
            );
        }
        
        $query = new WP_Query($args);
        $destinations = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $destinations[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'content' => get_the_content(),
                    'featured_image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                    'address' => get_field('destination_address'),
                    'opening_hours' => get_field('destination_opening_hours'),
                    'ticket_price' => get_field('destination_ticket_price'),
                    'rating' => get_field('destination_rating'),
                    'url' => get_permalink(),
                    'types' => wp_get_post_terms(get_the_ID(), 'destination_type', array('fields' => 'names')),
                );
            }
        }
        
        wp_reset_postdata();
        
        return new WP_REST_Response($destinations, 200);
    }
    
    public function get_services($request) {
        $params = $request->get_params();
        $limit = isset($params['limit']) ? intval($params['limit']) : 10;
        $category = isset($params['category']) ? sanitize_text_field($params['category']) : '';
        
        $args = array(
            'post_type' => 'services',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        if (!empty($category)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'service_category',
                    'field' => 'slug',
                    'terms' => $category,
                )
            );
        }
        
        $query = new WP_Query($args);
        $services = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $services[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'content' => get_the_content(),
                    'featured_image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                    'price' => get_field('service_price'),
                    'duration' => get_field('service_duration'),
                    'icon' => get_field('service_icon'),
                    'featured' => get_field('service_featured'),
                    'url' => get_permalink(),
                    'categories' => wp_get_post_terms(get_the_ID(), 'service_category', array('fields' => 'names')),
                );
            }
        }
        
        wp_reset_postdata();
        
        return new WP_REST_Response($services, 200);
    }
    
    public function get_gallery($request) {
        $params = $request->get_params();
        $limit = isset($params['limit']) ? intval($params['limit']) : 12;
        $category = isset($params['category']) ? sanitize_text_field($params['category']) : '';
        
        $args = array(
            'post_type' => 'gallery',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        if (!empty($category)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'gallery_category',
                    'field' => 'slug',
                    'terms' => $category,
                )
            );
        }
        
        $query = new WP_Query($args);
        $gallery = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $gallery_images = get_field('gallery_images');
                $images = array();
                
                if ($gallery_images) {
                    foreach ($gallery_images as $image) {
                        $images[] = array(
                            'url' => $image['url'],
                            'thumbnail' => $image['sizes']['medium'],
                            'alt' => $image['alt'],
                            'caption' => $image['caption'],
                        );
                    }
                }
                
                $gallery[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'description' => get_field('gallery_description'),
                    'location' => get_field('gallery_location'),
                    'date' => get_field('gallery_date'),
                    'images' => $images,
                    'url' => get_permalink(),
                    'categories' => wp_get_post_terms(get_the_ID(), 'gallery_category', array('fields' => 'names')),
                );
            }
        }
        
        wp_reset_postdata();
        
        return new WP_REST_Response($gallery, 200);
    }
    
    public function get_testimonials($request) {
        $params = $request->get_params();
        $limit = isset($params['limit']) ? intval($params['limit']) : 10;
        $featured = isset($params['featured']) ? $params['featured'] === 'true' : false;
        
        $args = array(
            'post_type' => 'testimonials',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        if ($featured) {
            $args['meta_query'] = array(
                array(
                    'key' => 'testimonial_featured',
                    'value' => '1',
                    'compare' => '='
                )
            );
        }
        
        $query = new WP_Query($args);
        $testimonials = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $testimonials[] = array(
                    'id' => get_the_ID(),
                    'content' => get_the_content(),
                    'author' => get_field('testimonial_author'),
                    'rating' => get_field('testimonial_rating'),
                    'avatar' => get_field('testimonial_avatar'),
                    'tour' => get_field('testimonial_tour'),
                    'featured' => get_field('testimonial_featured'),
                    'date' => get_the_date(),
                );
            }
        }
        
        wp_reset_postdata();
        
        return new WP_REST_Response($testimonials, 200);
    }
    
    public function submit_contact($request) {
        $params = $request->get_params();
        
        $name = sanitize_text_field($params['name']);
        $phone = sanitize_text_field($params['phone']);
        $email = sanitize_email($params['email']);
        $subject = sanitize_text_field($params['subject']);
        $message = sanitize_textarea_field($params['message']);
        
        // Validation
        if (empty($name) || empty($phone) || empty($email) || empty($message)) {
            return new WP_Error('missing_fields', __('Thiếu thông tin bắt buộc.', 'vjl-travel'), array('status' => 400));
        }
        
        if (!is_email($email)) {
            return new WP_Error('invalid_email', __('Email không hợp lệ.', 'vjl-travel'), array('status' => 400));
        }
        
        // Send email
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        $email_subject = sprintf(__('[%s] Tin nhắn liên hệ mới từ %s', 'vjl-travel'), $site_name, $name);
        
        $email_message = sprintf(
            __("Bạn có tin nhắn liên hệ mới từ website %s:\n\n", 'vjl-travel'),
            $site_name
        );
        
        $email_message .= sprintf(__("Họ và tên: %s\n", 'vjl-travel'), $name);
        $email_message .= sprintf(__("Số điện thoại: %s\n", 'vjl-travel'), $phone);
        $email_message .= sprintf(__("Email: %s\n", 'vjl-travel'), $email);
        $email_message .= sprintf(__("Chủ đề: %s\n", 'vjl-travel'), $subject);
        $email_message .= sprintf(__("Nội dung:\n%s\n\n", 'vjl-travel'), $message);
        $email_message .= sprintf(__("Thời gian: %s\n", 'vjl-travel'), current_time('d/m/Y H:i:s'));
        
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $site_name . ' <' . $admin_email . '>',
            'Reply-To: ' . $name . ' <' . $email . '>',
        );
        
        $sent = wp_mail($admin_email, $email_subject, $email_message, $headers);
        
        if ($sent) {
            return new WP_REST_Response(array('message' => __('Tin nhắn đã được gửi thành công!', 'vjl-travel')), 200);
        } else {
            return new WP_Error('send_failed', __('Có lỗi xảy ra khi gửi tin nhắn.', 'vjl-travel'), array('status' => 500));
        }
    }
}
