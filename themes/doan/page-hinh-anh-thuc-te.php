<?php
/**
 * Template for page: Hình ảnh thực tế
 * Slug: hinh-anh-thuc-te
 *
 * This page displays the same gallery section as the homepage ("Hình ảnh thực tế").
 * It intentionally does NOT render comments.
 */

get_header();
?>

<main id="primary" class="site-main">
    <section class="testimonials section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html__( 'Hình ảnh thực tế', 'doan' ); ?></h2>
            </div>
            <div class="destinations-gallery">
                <?php
                $dest_q = new WP_Query(array(
                    'post_type'      => 'diem-den',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'orderby'        => 'menu_order date',
                    'order'          => 'ASC'
                ));

                if ($dest_q->have_posts()):
                    $tabs   = array();
                    $panels = array();
                    $i = 0;
                    while ($dest_q->have_posts()): $dest_q->the_post();
                        $slug      = get_post_field('post_name', get_the_ID());
                        $is_active = $i === 0 ? ' active' : '';
                        $tabs[]    = '<button type="button" class="gallery-tab' . $is_active . '" data-target="panel-' . esc_attr($slug) . '">' . esc_html(get_the_title()) . '</button>';

                        $imgs = array();
                        if (function_exists('dln_get_gallery_image_ids')) {
                            $ids = dln_get_gallery_image_ids(get_the_ID());
                            if ($ids) {
                                foreach ($ids as $id) {
                                    $imgs[] = wp_get_attachment_image($id, 'large');
                                }
                            }
                        }
                        if (empty($imgs) && has_post_thumbnail()) {
                            $imgs[] = get_the_post_thumbnail(get_the_ID(), 'large');
                        }

                        $panel  = '<div class="gallery-panel' . $is_active . '" id="panel-' . esc_attr($slug) . '">';
                        if ($imgs) {
                            $panel .= '<div class="gallery-grid">' . implode('', $imgs) . '</div>';
                        } else {
                            $panel .= '<p>' . esc_html__('Chưa có hình ảnh.', 'doan') . '</p>';
                        }
                        $panel .= '</div>';
                        $panels[] = $panel;
                        $i++;
                    endwhile;
                    wp_reset_postdata();

                    if (!empty($tabs)) {
                        echo '<div class="gallery-tabs" role="tablist">' . implode('', $tabs) . '</div>';
                    }
                    if (!empty($panels)) {
                        echo '<div class="gallery-panels">' . implode('', $panels) . '</div>';
                    }
                else
                    echo '<p>' . esc_html__('Chưa có nội dung.', 'doan') . '</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
</main>

<script>
// Fallback gallery tabs handler
(function() {
    'use strict';
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGallery);
    } else {
        initGallery();
    }
    
    function initGallery() {
        var tabs = document.querySelectorAll('.gallery-tab');
        var panels = document.querySelectorAll('.gallery-panel');
        
        if (!tabs.length || !panels.length) return;
        
        // Handle tab clicks
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                var targetId = this.getAttribute('data-target');
                if (!targetId) return;
                
                // Remove active from all tabs
                tabs.forEach(function(t) {
                    t.classList.remove('active');
                });
                
                // Add active to clicked tab
                this.classList.add('active');
                
                // Hide all panels
                panels.forEach(function(panel) {
                    panel.classList.remove('active');
                    panel.style.display = 'none';
                });
                
                // Show target panel
                var targetPanel = document.getElementById(targetId);
                if (targetPanel) {
                    targetPanel.classList.add('active');
                    targetPanel.style.display = 'block';
                }
            });
        });
    }
})();
</script>

<?php get_footer(); ?>
