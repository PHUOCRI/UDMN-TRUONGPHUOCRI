<?php
/**
 * Rating System
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_Rating {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('vjl_rating', array($this, 'rating_shortcode'));
        add_action('wp_ajax_vjl_submit_rating', array($this, 'submit_rating'));
        add_action('wp_ajax_nopriv_vjl_submit_rating', array($this, 'submit_rating'));
        add_action('init', array($this, 'create_rating_table'));
    }
    
    public function enqueue_scripts() {
        if (get_option('vjl_travel_enable_rating', true)) {
            wp_enqueue_style('vjl-rating-style', VJL_TRAVEL_PLUGIN_URL . 'assets/css/rating.css', array(), VJL_TRAVEL_PLUGIN_VERSION);
            wp_enqueue_script('vjl-rating-script', VJL_TRAVEL_PLUGIN_URL . 'assets/js/rating.js', array('jquery'), VJL_TRAVEL_PLUGIN_VERSION, true);
            
            wp_localize_script('vjl-rating-script', 'vjlRating', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('vjl_rating_nonce'),
                'messages' => array(
                    'success' => __('Cảm ơn bạn đã đánh giá!', 'vjl-travel'),
                    'error' => __('Có lỗi xảy ra. Vui lòng thử lại.', 'vjl-travel'),
                    'already_rated' => __('Bạn đã đánh giá rồi.', 'vjl-travel'),
                )
            ));
        }
    }
    
    public function rating_shortcode($atts) {
        $atts = shortcode_atts(array(
            'post_id' => get_the_ID(),
            'post_type' => get_post_type(),
            'show_average' => true,
            'show_count' => true,
            'allow_rating' => true,
        ), $atts);
        
        $post_id = intval($atts['post_id']);
        $post_type = sanitize_text_field($atts['post_type']);
        
        $average_rating = $this->get_average_rating($post_id);
        $rating_count = $this->get_rating_count($post_id);
        $user_rating = $this->get_user_rating($post_id);
        
        ob_start();
        ?>
        <div class="vjl-rating-widget" data-post-id="<?php echo esc_attr($post_id); ?>" data-post-type="<?php echo esc_attr($post_type); ?>">
            <?php if ($atts['show_average']): ?>
                <div class="vjl-rating-average">
                    <div class="vjl-rating-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo $i <= $average_rating ? 'active' : ''; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="vjl-rating-score"><?php echo number_format($average_rating, 1); ?></span>
                    <?php if ($atts['show_count']): ?>
                        <span class="vjl-rating-count">(<?php echo $rating_count; ?> <?php _e('đánh giá', 'vjl-travel'); ?>)</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($atts['allow_rating'] && !$user_rating): ?>
                <div class="vjl-rating-form">
                    <h5><?php _e('Đánh giá của bạn:', 'vjl-travel'); ?></h5>
                    <div class="vjl-rating-input">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" name="rating" value="<?php echo $i; ?>" id="rating-<?php echo $i; ?>">
                            <label for="rating-<?php echo $i; ?>" class="vjl-star-label">
                                <i class="fas fa-star"></i>
                            </label>
                        <?php endfor; ?>
                    </div>
                    <div class="vjl-rating-comment mt-3">
                        <textarea class="form-control" name="comment" rows="3" placeholder="<?php _e('Nhận xét của bạn (tùy chọn)', 'vjl-travel'); ?>"></textarea>
                    </div>
                    <button type="button" class="btn btn-primary mt-2 vjl-submit-rating">
                        <?php _e('Gửi đánh giá', 'vjl-travel'); ?>
                    </button>
                </div>
            <?php elseif ($user_rating): ?>
                <div class="vjl-user-rating">
                    <p class="text-muted"><?php _e('Bạn đã đánh giá:', 'vjl-travel'); ?> 
                        <span class="vjl-user-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $user_rating['rating'] ? 'active' : ''; ?>"></i>
                            <?php endfor; ?>
                        </span>
                    </p>
                </div>
            <?php endif; ?>
            
            <div class="vjl-rating-message mt-3" style="display: none;"></div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function submit_rating() {
        check_ajax_referer('vjl_rating_nonce', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $rating = intval($_POST['rating']);
        $comment = sanitize_textarea_field($_POST['comment']);
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        // Validation
        if ($rating < 1 || $rating > 5) {
            wp_send_json_error(array('message' => __('Đánh giá không hợp lệ.', 'vjl-travel')));
        }
        
        // Check if user already rated
        if ($this->get_user_rating($post_id)) {
            wp_send_json_error(array('message' => __('Bạn đã đánh giá rồi.', 'vjl-travel')));
        }
        
        // Save rating
        global $wpdb;
        $table_name = $wpdb->prefix . 'vjl_ratings';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'post_id' => $post_id,
                'rating' => $rating,
                'comment' => $comment,
                'user_ip' => $user_ip,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => current_time('mysql'),
            ),
            array('%d', '%d', '%s', '%s', '%s', '%s')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Có lỗi xảy ra khi lưu đánh giá.', 'vjl-travel')));
        }
        
        // Update post meta with average rating
        $average_rating = $this->get_average_rating($post_id);
        update_post_meta($post_id, 'vjl_average_rating', $average_rating);
        update_post_meta($post_id, 'vjl_rating_count', $this->get_rating_count($post_id));
        
        wp_send_json_success(array(
            'message' => __('Cảm ơn bạn đã đánh giá!', 'vjl-travel'),
            'average_rating' => $average_rating,
            'rating_count' => $this->get_rating_count($post_id)
        ));
    }
    
    private function get_average_rating($post_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vjl_ratings';
        
        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT AVG(rating) FROM $table_name WHERE post_id = %d",
            $post_id
        ));
        
        return $result ? round($result, 1) : 0;
    }
    
    private function get_rating_count($post_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vjl_ratings';
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE post_id = %d",
            $post_id
        ));
    }
    
    private function get_user_rating($post_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'vjl_ratings';
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE post_id = %d AND user_ip = %s",
            $post_id, $user_ip
        ), ARRAY_A);
    }
    
    public function create_rating_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'vjl_ratings';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            post_id bigint(20) NOT NULL,
            rating tinyint(1) NOT NULL,
            comment text,
            user_ip varchar(45) NOT NULL,
            user_agent text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY post_id (post_id),
            KEY user_ip (user_ip)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
