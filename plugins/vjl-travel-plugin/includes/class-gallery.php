<?php
/**
 * Gallery Functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_Gallery {
    
    public function __construct() {
        add_shortcode('vjl_gallery', array($this, 'gallery_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_vjl_get_gallery', array($this, 'ajax_get_gallery'));
        add_action('wp_ajax_nopriv_vjl_get_gallery', array($this, 'ajax_get_gallery'));
    }
    
    public function enqueue_scripts() {
        if (get_option('vjl_travel_enable_gallery', true)) {
            wp_enqueue_style('vjl-gallery-style', VJL_TRAVEL_PLUGIN_URL . 'assets/css/gallery.css', array(), VJL_TRAVEL_PLUGIN_VERSION);
            wp_enqueue_script('vjl-gallery-script', VJL_TRAVEL_PLUGIN_URL . 'assets/js/gallery.js', array('jquery'), VJL_TRAVEL_PLUGIN_VERSION, true);
            
            // Lightbox library
            wp_enqueue_style('lightbox2', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css', array(), '2.11.4');
            wp_enqueue_script('lightbox2', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js', array('jquery'), '2.11.4', true);
        }
    }
    
    public function gallery_shortcode($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => 12,
            'columns' => 4,
            'show_title' => true,
            'show_description' => true,
            'show_date' => true,
            'show_location' => true,
            'layout' => 'grid', // grid, masonry, carousel
            'featured_only' => false,
        ), $atts);
        
        $args = array(
            'post_type' => 'gallery',
            'post_status' => 'publish',
            'posts_per_page' => intval($atts['limit']),
            'meta_key' => 'gallery_featured',
            'orderby' => 'meta_value_num date',
            'order' => 'DESC',
        );
        
        if ($atts['featured_only']) {
            $args['meta_query'] = array(
                array(
                    'key' => 'gallery_featured',
                    'value' => '1',
                    'compare' => '='
                )
            );
        }
        
        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'gallery_category',
                    'field' => 'slug',
                    'terms' => $atts['category'],
                )
            );
        }
        
        $query = new WP_Query($args);
        
        if (!$query->have_posts()) {
            return '<p>' . __('Không có ảnh nào được tìm thấy.', 'vjl-travel') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="vjl-gallery vjl-gallery-<?php echo esc_attr($atts['layout']); ?>" data-columns="<?php echo esc_attr($atts['columns']); ?>">
            <div class="vjl-gallery-container">
                <?php while ($query->have_posts()): $query->the_post(); ?>
                    <?php
                    $gallery_images = get_field('gallery_images');
                    $gallery_description = get_field('gallery_description');
                    $gallery_location = get_field('gallery_location');
                    $gallery_date = get_field('gallery_date');
                    $gallery_categories = get_the_terms(get_the_ID(), 'gallery_category');
                    ?>
                    
                    <?php if ($gallery_images): ?>
                        <div class="vjl-gallery-item" data-category="<?php echo $gallery_categories ? esc_attr($gallery_categories[0]->slug) : ''; ?>">
                            <div class="vjl-gallery-content">
                                <?php if ($atts['show_title']): ?>
                                    <h3 class="vjl-gallery-title"><?php the_title(); ?></h3>
                                <?php endif; ?>
                                
                                <?php if ($atts['show_description'] && $gallery_description): ?>
                                    <p class="vjl-gallery-description"><?php echo esc_html($gallery_description); ?></p>
                                <?php endif; ?>
                                
                                <div class="vjl-gallery-meta">
                                    <?php if ($atts['show_location'] && $gallery_location): ?>
                                        <span class="vjl-gallery-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo esc_html($gallery_location); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($atts['show_date'] && $gallery_date): ?>
                                        <span class="vjl-gallery-date">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo date('d/m/Y', strtotime($gallery_date)); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="vjl-gallery-images">
                                    <?php foreach ($gallery_images as $index => $image): ?>
                                        <div class="vjl-gallery-image <?php echo $index === 0 ? 'featured' : ''; ?>">
                                            <a href="<?php echo esc_url($image['url']); ?>" data-lightbox="gallery-<?php echo get_the_ID(); ?>" data-title="<?php echo esc_attr($image['caption'] ?: get_the_title()); ?>">
                                                <img src="<?php echo esc_url($image['sizes']['medium']); ?>" alt="<?php echo esc_attr($image['alt'] ?: get_the_title()); ?>" />
                                                <div class="vjl-gallery-overlay">
                                                    <i class="fas fa-search-plus"></i>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <?php if (count($gallery_images) > 1): ?>
                                    <div class="vjl-gallery-count">
                                        <i class="fas fa-images"></i>
                                        <?php printf(__('%d ảnh', 'vjl-travel'), count($gallery_images)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>
            
            <?php if ($atts['layout'] === 'carousel'): ?>
                <div class="vjl-gallery-nav">
                    <button class="vjl-gallery-prev" aria-label="<?php _e('Ảnh trước', 'vjl-travel'); ?>">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="vjl-gallery-next" aria-label="<?php _e('Ảnh tiếp', 'vjl-travel'); ?>">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            <?php endif; ?>
        </div>
        
        <style>
        .vjl-gallery-grid .vjl-gallery-container {
            display: grid;
            grid-template-columns: repeat(<?php echo esc_attr($atts['columns']); ?>, 1fr);
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .vjl-gallery-grid .vjl-gallery-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .vjl-gallery-grid .vjl-gallery-container {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }
        </style>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    public function ajax_get_gallery() {
        check_ajax_referer('vjl_travel_nonce', 'nonce');
        
        $category = sanitize_text_field($_POST['category']);
        $limit = intval($_POST['limit']);
        $featured_only = $_POST['featured_only'] === 'true';
        
        $args = array(
            'post_type' => 'gallery',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'meta_key' => 'gallery_featured',
            'orderby' => 'meta_value_num date',
            'order' => 'DESC',
        );
        
        if ($featured_only) {
            $args['meta_query'] = array(
                array(
                    'key' => 'gallery_featured',
                    'value' => '1',
                    'compare' => '='
                )
            );
        }
        
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
        $gallery_items = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $gallery_images = get_field('gallery_images');
                $gallery_description = get_field('gallery_description');
                $gallery_location = get_field('gallery_location');
                $gallery_date = get_field('gallery_date');
                
                if ($gallery_images) {
                    $gallery_items[] = array(
                        'id' => get_the_ID(),
                        'title' => get_the_title(),
                        'description' => $gallery_description,
                        'location' => $gallery_location,
                        'date' => $gallery_date,
                        'images' => $gallery_images,
                        'url' => get_permalink(),
                    );
                }
            }
        }
        
        wp_reset_postdata();
        wp_send_json_success($gallery_items);
    }
}
