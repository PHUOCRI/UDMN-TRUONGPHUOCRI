<?php
if (!defined('ABSPATH')) { exit; }

class JVWCU_Shortcode {
    public static function init() {
        add_shortcode('jvwcu', [__CLASS__, 'render']);
    }

    public static function render($atts = [], $content = null) {
        $atts = shortcode_atts([
            'columns' => 4,
            'title'   => __('Tại sao chọn chúng tôi?', 'jvwcu'),
            'show_title' => 'true',
            'class' => '',
        ], $atts, 'jvwcu');

        // Ensure assets
        wp_enqueue_style('jvwcu-style');

        $cols = max(1, min(6, intval($atts['columns'])));
        $query = new WP_Query([
            'post_type' => JVWCU_CPT::POST_TYPE,
            'posts_per_page' => -1,
            'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC'],
            'order' => 'ASC',
            'no_found_rows' => true,
        ]);

        ob_start();
        $container_classes = 'jvwcu-section ' . sanitize_html_class($atts['class']) . ' cols-' . $cols;
        echo '<section class="' . esc_attr($container_classes) . '">';
        if (filter_var($atts['show_title'], FILTER_VALIDATE_BOOLEAN)) {
            echo '<h2 class="jvwcu-heading">' . esc_html($atts['title']) . '</h2>';
            echo '<span class="jvwcu-heading-underline" aria-hidden="true"></span>';
        }
        echo '<div class="jvwcu-grid">';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $subtitle = get_post_meta(get_the_ID(), JVWCU_CPT::META_SUBTITLE, true);
                $link = get_post_meta(get_the_ID(), JVWCU_CPT::META_LINK, true);
                $thumb = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                $item_open = $link ? '<a class="jvwcu-card" href="' . esc_url($link) . '">' : '<div class="jvwcu-card">';
                $item_close = $link ? '</a>' : '</div>';
                echo $item_open;
                echo '<div class="jvwcu-icon">';
                if ($thumb) {
                    echo '<img src="' . esc_url($thumb) . '" alt="" loading="lazy" />';
                } else {
                    echo '<span class="jvwcu-icon-placeholder" aria-hidden="true">★</span>';
                }
                echo '</div>';
                echo '<div class="jvwcu-content">';
                echo '<h3 class="jvwcu-title">' . esc_html(get_the_title()) . '</h3>';
                if (!empty($subtitle)) {
                    echo '<p class="jvwcu-subtitle">' . esc_html($subtitle) . '</p>';
                }
                if (has_excerpt()) {
                    echo '<p class="jvwcu-desc">' . esc_html(get_the_excerpt()) . '</p>';
                }
                echo '</div>';
                echo $item_close;
            }
            wp_reset_postdata();
        } else {
            echo '<p class="jvwcu-empty">' . esc_html__('Chưa có mục nào. Hãy thêm mới trong quản trị.', 'jvwcu') . '</p>';
        }

        echo '</div>';
        echo '</section>';

        return ob_get_clean();
    }
}
