<?php
/**
 * The main template file for the front page
 */

get_header(); ?>

<main id="primary" class="site-main">
    <!-- Modern Hero Banner - Realistic and Beautiful -->
    <?php
    $banner_type = get_theme_mod('banner_type', 'gradient');
    
    if ($banner_type === 'image') {
        get_template_part('template-parts/banner-image');
    } elseif ($banner_type === 'video') {
        get_template_part('template-parts/banner-video');
    } else {
        ?>
        <?php
    }
    ?>
    <section id="featured-posts" class="featured-posts section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <?php
                    $pto = get_post_type_object( 'bai-viet-noi-bat' );
                    $section_title = $pto && isset( $pto->labels->name ) ? $pto->labels->name : __( 'Bài Viết Nổi Bật', 'doan' );
                    echo esc_html( $section_title );
                    ?>
                </h2>
            </div>
            
            <div class="posts-grid">
                <?php
                $args = array(
                    'post_type'      => 'bai-viet-noi-bat',
                    'posts_per_page' => 6,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                $featured_posts = new WP_Query($args);

                if ($featured_posts->have_posts()) :
                    while ($featured_posts->have_posts()) : $featured_posts->the_post();
                        ?>
                        <article class="post-card">
                            <div class="post-thumbnail">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large', array('class' => 'post-image')); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="post-image-placeholder">
                                        <div class="placeholder-content">
                                            <i class="fas fa-mountain"></i>
                                            <span>Du lịch Nhật Bản</span>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <div class="post-category">
                                      <?php
                                    $term_label = '';
                                    $tax_objects = get_object_taxonomies( get_post_type(), 'objects' );
                                    if ( ! empty( $tax_objects ) ) {
                                        foreach ( $tax_objects as $tax ) {
                                            if ( ! $tax->hierarchical ) { continue; }
                                            $terms = get_the_terms( get_the_ID(), $tax->name );
                                            if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                                                $term_label = $terms[0]->name;
                                                break;
                                            }
                                        }
                                    }
                        
                                    if ( empty( $term_label ) ) {
                                        $categories = get_the_terms( get_the_ID(), 'category' );
                                        if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
                                            $term_label = $categories[0]->name;
                                        }
                                    }
                                    echo '<span class="category-tag">' . esc_html( $term_label ? $term_label : 'Du lịch' ) . '</span>';
                                    ?>
                                </div>
                            </div>
                            <div class="post-content">
                                <h3 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <p class="post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '…' ) ); ?></p>
                                <div class="post-meta">
                                    <span class="post-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo get_the_date('d/m/Y'); ?>
                                    </span>
                                    <span class="post-views">
                                        <i class="fas fa-eye"></i>
                                        <?php echo rand(50, 500); ?> lượt xem
                                    </span>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="read-more-btn">
                                    Đọc thêm <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>Không có bài viết nào được tìm thấy.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>

  

    <section class="testimonials section-padding">
        <div class="container">
            <div class="section-header">
                <?php $pto = get_post_type_object('diem-den'); $sec_title = $pto && isset($pto->labels->name) ? $pto->labels->name : __('Hình ảnh thực tế','doan'); ?>
                <h2 class="section-title"><?php echo esc_html($sec_title); ?></h2>
            </div>
            <div class="destinations-gallery">
                <?php
                $dest_q = new WP_Query(array(
                    'post_type' => 'diem-den',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'menu_order date',
                    'order' => 'ASC'
                ));
                if ($dest_q->have_posts()):
                    $tabs = array();
                    $panels = array();
                    $i = 0;
                    while ($dest_q->have_posts()): $dest_q->the_post();
                        $slug = get_post_field('post_name', get_the_ID());
                        $is_active = $i === 0 ? ' active' : '';
                        $tabs[] = '<button type="button" class="gallery-tab' . $is_active . '" data-target="panel-' . esc_attr($slug) . '">' . esc_html(get_the_title()) . '</button>';
                        $imgs = array();
                        if (function_exists('dln_get_gallery_image_ids')) { $ids = dln_get_gallery_image_ids(get_the_ID()); if ($ids) { foreach ($ids as $id) { $imgs[] = wp_get_attachment_image($id, 'large'); } } }
                        if (empty($imgs) && has_post_thumbnail()) { $imgs[] = get_the_post_thumbnail(get_the_ID(), 'large'); }
                        $panel = '<div class="gallery-panel' . $is_active . '" id="panel-' . esc_attr($slug) . '">';
                        if ($imgs) { $panel .= '<div class="gallery-grid">' . implode('', $imgs) . '</div>'; } else { $panel .= '<p>' . esc_html__('Chưa có hình ảnh.', 'doan') . '</p>'; }
                        $panel .= '</div>';
                        $panels[] = $panel;
                        $i++;
                    endwhile;
                    wp_reset_postdata();
                    if (!empty($tabs)) { echo '<div class="gallery-tabs" role="tablist">' . implode('', $tabs) . '</div>'; }
                    if (!empty($panels)) { echo '<div class="gallery-panels">' . implode('', $panels) . '</div>'; }
                else echo '<p>' . esc_html__('Chưa có nội dung.', 'doan') . '</p>'; endif;
                ?>
            </div>
        </div>
    </section>
    <section class="contact-form-section section-padding theme-minimal">
        <div class="container">
            <?php
            if ( function_exists('do_shortcode') && shortcode_exists('jv_contact_form') ) {
                echo do_shortcode('[jv_contact_form]');
            } else {
                echo '<p>Vui lòng kích hoạt plugin <strong>JV Contact Form</strong> để hiển thị form liên hệ.</p>';
            }
            ?>
        </div>
    </section>
    <section class="news-section section-padding">
        <div class="container">
            <div class="section-header">
                <?php
                $news_pto = post_type_exists('tin-tuc') ? get_post_type_object('tin-tuc') : null;
                $news_title = $news_pto && isset($news_pto->labels->name) ? $news_pto->labels->name : __('Tin tức & Thông tin hữu ích','doan');
                ?>
                <h2 class="section-title"><?php echo esc_html($news_title); ?></h2>
            </div>

            <div class="news-grid news-slider">
                <?php
        
                $pt = post_type_exists('tin-tuc') ? 'tin-tuc' : 'post';
                $news_q = new WP_Query(array(
                    'post_type'      => $pt,
                    'posts_per_page' => 6,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ));
                if ($news_q->have_posts()) :
                    while ($news_q->have_posts()) : $news_q->the_post(); ?>
                        <article class="news-card">
                            <a class="news-thumb" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                                <?php if (has_post_thumbnail()) {
                                    the_post_thumbnail('post-thumbnail-large', array('alt' => get_the_title()));
                                } else { ?>
                                    <span class="news-thumb--placeholder" aria-hidden="true"></span>
                                <?php } ?>
                                <?php
                                $d = get_the_date('d');
                                $m = get_the_date('m');
                                $y = get_the_date('Y');
                                ?>
                                <span class="news-date-badge" aria-hidden="true"><?php echo esc_html($d . '/' . $m . '/' . $y); ?></span>
                            </a>
                            <div class="news-content">
                                <h3 class="news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                              
                                <p class="news-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '…' ) ); ?></p>
                                <a class="news-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e('Đọc thêm','doan'); ?> <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata();
                else :
                    echo '<p>' . esc_html__('Chưa có bài viết.', 'doan') . '</p>';
                endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
