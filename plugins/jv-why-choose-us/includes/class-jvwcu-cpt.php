<?php
if (!defined('ABSPATH')) { exit; }

class JVWCU_CPT {
    const POST_TYPE = 'jvwcu_item';
    const META_SUBTITLE = '_jvwcu_subtitle';
    const META_LINK = '_jvwcu_link';

    public static function register() {
        $labels = [
            'name' => __('Why Choose Us', 'jvwcu'),
            'singular_name' => __('Why Choose Us Item', 'jvwcu'),
            'menu_name' => __('Why Choose Us', 'jvwcu'),
            'add_new' => __('Add New', 'jvwcu'),
            'add_new_item' => __('Add New Item', 'jvwcu'),
            'edit_item' => __('Edit Item', 'jvwcu'),
            'new_item' => __('New Item', 'jvwcu'),
            'view_item' => __('View Item', 'jvwcu'),
            'search_items' => __('Search Items', 'jvwcu'),
            'not_found' => __('No items found', 'jvwcu'),
            'not_found_in_trash' => __('No items found in Trash', 'jvwcu'),
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-awards',
            'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
            'has_archive' => false,
            'rewrite' => false,
        ];

        register_post_type(self::POST_TYPE, $args);
    }

    public static function register_meta_boxes() {
        add_meta_box('jvwcu_meta', __('Item Details', 'jvwcu'), [__CLASS__, 'render_meta_box'], self::POST_TYPE, 'normal', 'default');
    }

    public static function render_meta_box($post) {
        wp_nonce_field('jvwcu_meta_nonce', 'jvwcu_meta_nonce_field');
        $subtitle = get_post_meta($post->ID, self::META_SUBTITLE, true);
        $link = get_post_meta($post->ID, self::META_LINK, true);
        ?>
        <p>
            <label for="jvwcu_subtitle"><strong><?php _e('Subtitle', 'jvwcu'); ?></strong></label><br />
            <input type="text" id="jvwcu_subtitle" name="jvwcu_subtitle" class="widefat" value="<?php echo esc_attr($subtitle); ?>" placeholder="<?php esc_attr_e('Ví dụ: Dịch vụ chất lượng cao', 'jvwcu'); ?>" />
        </p>
        <p>
            <label for="jvwcu_link"><strong><?php _e('Optional Link', 'jvwcu'); ?></strong></label><br />
            <input type="url" id="jvwcu_link" name="jvwcu_link" class="widefat" value="<?php echo esc_attr($link); ?>" placeholder="https://" />
            <em><?php _e('Nếu nhập, toàn bộ ô sẽ liên kết đến URL này.', 'jvwcu'); ?></em>
        </p>
        <p>
            <?php _e('Gợi ý sử dụng:', 'jvwcu'); ?><br/>
            - <?php _e('Tiêu đề: Tiêu đề ngắn gọn của box (ví dụ: Đảm bảo chất lượng)', 'jvwcu'); ?><br/>
            - <?php _e('Nội dung: Mô tả ngắn', 'jvwcu'); ?><br/>
            - <?php _e('Ảnh đại diện: Icon hoặc hình tròn', 'jvwcu'); ?>
        </p>
        <?php
    }

    public static function save_meta_boxes($post_id) {
        if (!isset($_POST['jvwcu_meta_nonce_field']) || !wp_verify_nonce($_POST['jvwcu_meta_nonce_field'], 'jvwcu_meta_nonce')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
        if (isset($_POST['post_type']) && self::POST_TYPE === $_POST['post_type']) {
            if (!current_user_can('edit_post', $post_id)) { return; }
        } else {
            return;
        }

        $subtitle = isset($_POST['jvwcu_subtitle']) ? sanitize_text_field($_POST['jvwcu_subtitle']) : '';
        $link = isset($_POST['jvwcu_link']) ? esc_url_raw($_POST['jvwcu_link']) : '';
        update_post_meta($post_id, self::META_SUBTITLE, $subtitle);
        update_post_meta($post_id, self::META_LINK, $link);
    }
}
