<?php
/**
 * Slider Functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_Slider {
    
    public function __construct() {
        add_action('init', array($this, 'register_slider_post_type'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_slider_scripts'));
        add_shortcode('vjl_slider', array($this, 'slider_shortcode'));
        add_action('wp_ajax_vjl_get_slider', array($this, 'ajax_get_slider'));
        add_action('wp_ajax_nopriv_vjl_get_slider', array($this, 'ajax_get_slider'));
    }
    
    public function register_slider_post_type() {
        $labels = array(
            'name' => __('Slider', 'vjl-travel'),
            'singular_name' => __('Slide', 'vjl-travel'),
            'menu_name' => __('Slider', 'vjl-travel'),
            'add_new' => __('Thêm slide mới', 'vjl-travel'),
            'add_new_item' => __('Thêm slide mới', 'vjl-travel'),
            'edit_item' => __('Chỉnh sửa slide', 'vjl-travel'),
            'new_item' => __('Slide mới', 'vjl-travel'),
            'view_item' => __('Xem slide', 'vjl-travel'),
            'search_items' => __('Tìm kiếm slide', 'vjl-travel'),
            'not_found' => __('Không tìm thấy slide', 'vjl-travel'),
            'not_found_in_trash' => __('Không có slide trong thùng rác', 'vjl-travel'),
            'all_items' => __('Tất cả slide', 'vjl-travel'),
        );
        
        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'vjl-travel-settings',
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'show_in_rest' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => array('title', 'thumbnail', 'custom-fields'),
        );
        
        register_post_type('vjl_slider', $args);
        
        // Add ACF fields for slider
        if (function_exists('acf_add_local_field_group')) {
            $this->register_slider_acf_fields();
        }
    }
    
    private function register_slider_acf_fields() {
        acf_add_local_field_group(array(
            'key' => 'group_slider_fields',
            'title' => __('Thông tin Slide', 'vjl-travel'),
            'fields' => array(
                array(
                    'key' => 'field_slider_image',
                    'label' => __('Ảnh slide', 'vjl-travel'),
                    'name' => 'slider_image',
                    'type' => 'image',
                    'instructions' => __('Ảnh chính của slide', 'vjl-travel'),
                    'required' => 1,
                ),
                array(
                    'key' => 'field_slider_title',
                    'label' => __('Tiêu đề', 'vjl-travel'),
                    'name' => 'slider_title',
                    'type' => 'text',
                    'instructions' => __('Tiêu đề hiển thị trên slide', 'vjl-travel'),
                ),
                array(
                    'key' => 'field_slider_subtitle',
                    'label' => __('Phụ đề', 'vjl-travel'),
                    'name' => 'slider_subtitle',
                    'type' => 'text',
                    'instructions' => __('Phụ đề hiển thị trên slide', 'vjl-travel'),
                ),
                array(
                    'key' => 'field_slider_description',
                    'label' => __('Mô tả', 'vjl-travel'),
                    'name' => 'slider_description',
                    'type' => 'textarea',
                    'instructions' => __('Mô tả ngắn gọn về slide', 'vjl-travel'),
                ),
                array(
                    'key' => 'field_slider_button_text',
                    'label' => __('Text nút bấm', 'vjl-travel'),
                    'name' => 'slider_button_text',
                    'type' => 'text',
                    'instructions' => __('Text hiển thị trên nút bấm', 'vjl-travel'),
                    'default_value' => 'Xem thêm',
                ),
                array(
                    'key' => 'field_slider_button_url',
                    'label' => __('URL nút bấm', 'vjl-travel'),
                    'name' => 'slider_button_url',
                    'type' => 'url',
                    'instructions' => __('Link khi click vào nút bấm', 'vjl-travel'),
                ),
                array(
                    'key' => 'field_slider_order',
                    'label' => __('Thứ tự', 'vjl-travel'),
                    'name' => 'slider_order',
                    'type' => 'number',
                    'instructions' => __('Thứ tự hiển thị (số nhỏ hiển thị trước)', 'vjl-travel'),
                    'default_value' => 0,
                ),
                array(
                    'key' => 'field_slider_active',
                    'label' => __('Kích hoạt', 'vjl-travel'),
                    'name' => 'slider_active',
                    'type' => 'true_false',
                    'instructions' => __('Hiển thị slide này', 'vjl-travel'),
                    'default_value' => 1,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'vjl_slider',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
        ));
    }
    
    public function enqueue_slider_scripts() {
        if (get_option('vjl_travel_enable_slider', true)) {
            wp_enqueue_style('vjl-slider-style', VJL_TRAVEL_PLUGIN_URL . 'assets/css/slider.css', array(), VJL_TRAVEL_PLUGIN_VERSION);
            wp_enqueue_script('vjl-slider-script', VJL_TRAVEL_PLUGIN_URL . 'assets/js/slider.js', array('jquery'), VJL_TRAVEL_PLUGIN_VERSION, true);
        }
    }
    
    public function slider_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 5,
            'autoplay' => true,
            'interval' => 5000,
            'show_dots' => true,
            'show_arrows' => true,
        ), $atts);
        
        $slides = $this->get_active_slides($atts['limit']);
        
        if (empty($slides)) {
            return '<p>' . __('Không có slide nào được tìm thấy.', 'vjl-travel') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="vjl-slider" data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>" data-interval="<?php echo esc_attr($atts['interval']); ?>">
            <div class="vjl-slider-container">
                <div class="vjl-slider-wrapper">
                    <?php foreach ($slides as $index => $slide): ?>
                        <div class="vjl-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>">
                            <div class="vjl-slide-image">
                                <?php if ($slide['image']): ?>
                                    <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title']); ?>" />
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($slide['title'] || $slide['subtitle'] || $slide['description']): ?>
                                <div class="vjl-slide-content">
                                    <div class="vjl-slide-inner">
                                        <?php if ($slide['title']): ?>
                                            <h2 class="vjl-slide-title"><?php echo esc_html($slide['title']); ?></h2>
                                        <?php endif; ?>
                                        
                                        <?php if ($slide['subtitle']): ?>
                                            <h3 class="vjl-slide-subtitle"><?php echo esc_html($slide['subtitle']); ?></h3>
                                        <?php endif; ?>
                                        
                                        <?php if ($slide['description']): ?>
                                            <p class="vjl-slide-description"><?php echo esc_html($slide['description']); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if ($slide['button_text'] && $slide['button_url']): ?>
                                            <a href="<?php echo esc_url($slide['button_url']); ?>" class="vjl-slide-button">
                                                <?php echo esc_html($slide['button_text']); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($atts['show_arrows']): ?>
                    <button class="vjl-slider-nav vjl-slider-prev" aria-label="<?php _e('Slide trước', 'vjl-travel'); ?>">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="vjl-slider-nav vjl-slider-next" aria-label="<?php _e('Slide tiếp', 'vjl-travel'); ?>">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                <?php endif; ?>
                
                <?php if ($atts['show_dots'] && count($slides) > 1): ?>
                    <div class="vjl-slider-dots">
                        <?php foreach ($slides as $index => $slide): ?>
                            <button class="vjl-slider-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>" aria-label="<?php printf(__('Chuyển đến slide %d', 'vjl-travel'), $index + 1); ?>"></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    private function get_active_slides($limit = 5) {
        $args = array(
            'post_type' => 'vjl_slider',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'meta_query' => array(
                array(
                    'key' => 'slider_active',
                    'value' => '1',
                    'compare' => '='
                )
            ),
            'meta_key' => 'slider_order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC'
        );
        
        $query = new WP_Query($args);
        $slides = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $image = get_field('slider_image');
                $slides[] = array(
                    'id' => get_the_ID(),
                    'title' => get_field('slider_title') ?: get_the_title(),
                    'subtitle' => get_field('slider_subtitle'),
                    'description' => get_field('slider_description'),
                    'button_text' => get_field('slider_button_text'),
                    'button_url' => get_field('slider_button_url'),
                    'image' => $image ? $image['url'] : '',
                    'order' => get_field('slider_order') ?: 0,
                );
            }
        }
        
        wp_reset_postdata();
        return $slides;
    }
    
    public function ajax_get_slider() {
        check_ajax_referer('vjl_travel_nonce', 'nonce');
        
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 5;
        $slides = $this->get_active_slides($limit);
        
        wp_send_json_success($slides);
    }
}
