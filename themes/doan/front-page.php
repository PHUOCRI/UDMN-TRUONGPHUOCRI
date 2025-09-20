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
                    <?php echo esc_html( get_theme_mod('featured_section_title', 'Bài Viết Nổi Bật') ); ?>
                </h2>
                <p>
                    <?php echo esc_html( get_theme_mod('featured_section_subtitle', 'Những bài viết về du lịch Nhật Bản được yêu thích nhất') ); ?>
                </p>
            </div>
            
            <div class="posts-grid">
                <?php
                $sticky_posts = get_option('sticky_posts');
                if ( ! empty($sticky_posts) ) {
                    $args = array(
                        'post_type'           => 'post',
                        'posts_per_page'      => 6,
                        'post__in'            => $sticky_posts,
                        'ignore_sticky_posts' => 1,
                    );
                } else {
                    $args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 6,
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    );
                }
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
                                    $categories = get_the_category();
                                    if (!empty($categories) && $categories[0]->name !== 'Uncategorized') {
                                        echo '<span class="category-tag">' . esc_html($categories[0]->name) . '</span>';
                                    } else {
                                        echo '<span class="category-tag">Du lịch</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="post-content">
                                <h3 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                               
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

   

    <!-- Why Choose Us -->
    <section class="why-choose-us section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tại sao chọn chúng tôi?</h2>
            </div>
            
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Đảm bảo chất lượng</h3>
                    <p>Dịch vụ chất lượng cao với giá cả hợp lý</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Hỗ trợ 24/7</h3>
                    <p>Đội ngũ tư vấn nhiệt tình, chuyên nghiệp</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Đa dạng điểm đến</h3>
                    <p>Nhiều lựa chọn tour phù hợp với mọi nhu cầu</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h3>Giá cả cạnh tranh</h3>
                    <p>Nhiều ưu đãi và khuyến mãi hấp dẫn</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials section-padding">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Khách hàng nói gì về chúng tôi</h2>
            </div>
            
            <div class="testimonials-slider">
                <?php
                $testimonials = get_posts(array(
                    'post_type' => 'testimonial',
                    'posts_per_page' => 3
                ));

                if ($testimonials) :
                    foreach ($testimonials as $testimonial) :
                        $name = get_the_title($testimonial->ID);
                        $content = $testimonial->post_content;
                        $position = get_post_meta($testimonial->ID, 'position', true);
                        $rating = get_post_meta($testimonial->ID, 'rating', true);
                        ?>
                        <div class="testimonial-item">
                            <div class="testimonial-content">
                                <?php echo wpautop($content); ?>
                            </div>
                            <div class="testimonial-author">
                                <h4><?php echo esc_html($name); ?></h4>
                                <?php if ($position) : ?>
                                    <span class="position"><?php echo esc_html($position); ?></span>
                                <?php endif; ?>
                                <?php if ($rating) : ?>
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <i class="fas fa-star<?php echo $i <= $rating ? ' active' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Bạn đã sẵn sàng cho chuyến đi tiếp theo?</h2>
                <p>Liên hệ ngay với chúng tôi để được tư vấn tour phù hợp nhất</p>
                <a href="<?php echo esc_url(home_url('/lien-he')); ?>" class="btn btn-primary">Liên hệ ngay</a>
            </div>
        </div>
    </section>
    <section class="contact-form-section section-padding theme-minimal">
        <div class="container">
            <div class="row g-4 align-items-stretch contact-map-grid">
                <!-- Cột trái: Form tư vấn tour -->
                <div class="col-12 col-lg-6">
                    <?php
                    if ( function_exists('do_shortcode') && shortcode_exists('jv_contact_form') ) {
                        echo do_shortcode('[jv_contact_form]');
                    } else {
                        echo '<p>Vui lòng kích hoạt plugin <strong>JV Contact Form</strong> để hiển thị form liên hệ.</p>';
                    }
                    ?>
                </div>

                <!-- Cột phải: Bản đồ + thông tin -->
                <div class="col-12 col-lg-6">
                    <div class="map-panel h-100 p-3 p-md-4 bg-white rounded-3 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                            <h3 class="h5 mb-0">Vị trí văn phòng</h3>
                        </div>
                        <div class="map-container mb-3">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.7!2d107.6!3d16.4!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTbCsDI0JzAwLjAiTiAxMDfCsDM2JzAwLjAiRQ!5e0!3m2!1svi!2s!4v1234567890" 
                                width="100%"
                                height="300"
                                style="border:0; border-radius: 12px;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>

                        <div class="map-info">
                            <div class="info-item d-flex align-items-start mb-2">
                                <i class="fas fa-location-dot text-primary me-2 mt-1"></i>
                                <div>
                                    <strong>Địa chỉ:</strong>
                                    <div>52 Huỳnh Tấn Phát, phường An Đông, TP Huế</div>
                                </div>
                            </div>
                            <div class="info-item d-flex align-items-start mb-2">
                                <i class="fas fa-phone text-success me-2 mt-1"></i>
                                <div>
                                    <strong>Hotline:</strong>
                                    <div><a href="tel:0349421736" class="text-decoration-none">0349421736</a></div>
                                </div>
                            </div>
                            <div class="info-item d-flex align-items-start mb-2">
                                <i class="fas fa-envelope text-info me-2 mt-1"></i>
                                <div>
                                    <strong>Email:</strong>
                                    <div><a href="mailto:ri01652965673@gmail.com" class="text-decoration-none">ri01652965673@gmail.com</a></div>
                                </div>
                            </div>
                            <div class="info-item d-flex align-items-start">
                                <i class="fas fa-clock text-warning me-2 mt-1"></i>
                                <div>
                                    <strong>Giờ làm việc:</strong>
                                    <div>8:00 - 17:00 (T2 - T7)</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <a href="https://wa.me/840349421736" class="btn btn-success btn-sm" target="_blank">
                                <i class="fab fa-whatsapp me-1"></i> WhatsApp
                            </a>
                            <a href="tel:0349421736" class="btn btn-primary btn-sm">
                                <i class="fas fa-phone me-1"></i> Gọi ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
