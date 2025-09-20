<?php get_header(); ?>

<main id="primary" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('tour-single'); ?>>
            <!-- Tour Gallery -->
            <div class="tour-gallery">
                <?php 
                $gallery = get_field('gallery');
                if ($gallery) : 
                    $first_image = array_shift($gallery);
                    ?>
                    <div class="main-image">
                        <img src="<?php echo esc_url($first_image['url']); ?>" alt="<?php echo esc_attr($first_image['alt']); ?>">
                    </div>
                    <?php if ($gallery) : ?>
                        <div class="gallery-thumbnails">
                            <?php foreach ($gallery as $image) : ?>
                                <div class="thumbnail">
                                    <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" 
                                         alt="<?php echo esc_attr($image['alt']); ?>"
                                         data-full="<?php echo esc_url($image['url']); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php elseif (has_post_thumbnail()) : ?>
                    <div class="main-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="container">
                <div class="tour-main-content">
                    <div class="tour-details">
                        <header class="tour-header">
                            <h1 class="tour-title"><?php the_title(); ?></h1>
                            
                            <div class="tour-meta">
                                <?php if ($price = get_field('price')) : ?>
                                    <div class="tour-price">
                                        <span class="price"><?php echo esc_html($price); ?></span>
                                        <?php if ($original_price = get_field('original_price')) : ?>
                                            <span class="original-price"><?php echo esc_html($original_price); ?></span>
                                        <?php endif; ?>
                                        <?php if ($discount = get_field('discount')) : ?>
                                            <span class="discount-badge">-<?php echo esc_html($discount); ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="tour-rating">
                                    <?php
                                    $rating = get_field('rating') ? get_field('rating') : 5;
                                    for ($i = 1; $i <= 5; $i++) :
                                        echo $i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                    endfor;
                                    ?>
                                    <span class="review-count">(<?php echo get_comments_number(); ?> đánh giá)</span>
                                </div>
                            </div>
                        </header>

                        <!-- Tour Highlights -->
                        <?php if (have_rows('highlights')) : ?>
                            <div class="tour-highlights">
                                <h3>Điểm nổi bật</h3>
                                <ul>
                                    <?php while (have_rows('highlights')) : the_row(); ?>
                                        <li>
                                            <i class="fas fa-check-circle"></i>
                                            <?php the_sub_field('highlight'); ?>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Tour Itinerary -->
                        <?php if (have_rows('itinerary')) : ?>
                            <div class="tour-itinerary">
                                <h3>Lịch trình tour</h3>
                                <div class="itinerary-tabs">
                                    <?php while (have_rows('itinerary')) : the_row(); ?>
                                        <div class="day-tab">
                                            <div class="day-header">
                                                <h4><?php the_sub_field('day'); ?></h4>
                                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                                            </div>
                                            <div class="day-content">
                                                <?php if ($day_title = get_sub_field('day_title')) : ?>
                                                    <h5><?php echo esc_html($day_title); ?></h5>
                                                <?php endif; ?>
                                                <div class="day-details">
                                                    <?php the_sub_field('day_content'); ?>
                                                </div>
                                                <?php if ($meals = get_sub_field('meals')) : ?>
                                                    <div class="meals">
                                                        <strong>Bữa ăn: </strong>
                                                        <?php echo esc_html($meals); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Tour Includes/Excludes -->
                        <div class="tour-includes-excludes">
                            <div class="includes">
                                <h4>Bao gồm</h4>
                                <ul>
                                    <?php if (have_rows('includes')) : ?>
                                        <?php while (have_rows('includes')) : the_row(); ?>
                                            <li><?php the_sub_field('item'); ?></li>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="excludes">
                                <h4>Không bao gồm</h4>
                                <ul>
                                    <?php if (have_rows('excludes')) : ?>
                                        <?php while (have_rows('excludes')) : the_row(); ?>
                                            <li><?php the_sub_field('item'); ?></li>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Tour Policies -->
                        <?php if (have_rows('policies')) : ?>
                            <div class="tour-policies">
                                <h3>Chính sách</h3>
                                <div class="policies-accordion">
                                    <?php while (have_rows('policies')) : the_row(); ?>
                                        <div class="policy-item">
                                            <div class="policy-header">
                                                <h4><?php the_sub_field('policy_title'); ?></h4>
                                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                                            </div>
                                            <div class="policy-content">
                                                <?php the_sub_field('policy_content'); ?>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Booking Sidebar -->
                    <aside class="booking-sidebar">
                        <div class="booking-widget">
                            <h3>Đặt tour ngay</h3>
                            <div class="price-summary">
                                <?php if ($price = get_field('price')) : ?>
                                    <div class="price">
                                        <span class="label">Giá từ:</span>
                                        <span class="amount"><?php echo esc_html($price); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($original_price = get_field('original_price')) : ?>
                                    <div class="original-price">
                                        <span class="label">Giá gốc:</span>
                                        <span class="amount"><?php echo esc_html($original_price); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($discount = get_field('discount')) : ?>
                                    <div class="discount">
                                        <span class="label">Tiết kiệm:</span>
                                        <span class="amount">-<?php echo esc_html($discount); ?>%</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <form class="booking-form" method="post">
                                <div class="form-group">
                                    <label for="departure-date">Ngày khởi hành</label>
                                    <select id="departure-date" name="departure_date" required>
                                        <option value="">Chọn ngày</option>
                                        <?php if (have_rows('available_dates')) : ?>
                                            <?php while (have_rows('available_dates')) : the_row(); ?>
                                                <option value="<?php the_sub_field('date'); ?>">
                                                    <?php echo date('d/m/Y', strtotime(get_sub_field('date'))); ?>
                                                    <?php if ($price = get_sub_field('price')) : ?>
                                                        - <?php echo esc_html($price); ?>
                                                    <?php endif; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="adults">Người lớn (12+ tuổi)</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" data-target="adults">-</button>
                                        <input type="number" id="adults" name="adults" min="1" value="2" readonly>
                                        <button type="button" class="qty-btn plus" data-target="adults">+</button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="children">Trẻ em (5-11 tuổi)</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" data-target="children">-</button>
                                        <input type="number" id="children" name="children" min="0" value="0" readonly>
                                        <button type="button" class="qty-btn plus" data-target="children">+</button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="infants">Trẻ nhỏ (dưới 5 tuổi)</label>
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" data-target="infants">-</button>
                                        <input type="number" id="infants" name="infants" min="0" value="0" readonly>
                                        <button type="button" class="qty-btn plus" data-target="infants">+</button>
                                    </div>
                                </div>

                                <div class="total-price">
                                    <span class="label">Tổng cộng:</span>
                                    <span class="amount" id="total-amount">0 VNĐ</span>
                                </div>

                                <button type="submit" class="btn-book-now">Đặt ngay</button>
                                <button type="button" class="btn-enquire" data-toggle="modal" data-target="#enquireModal">Yêu cầu tư vấn</button>
                            </form>

                            <div class="tour-info">
                                <?php if ($duration = get_field('duration')) : ?>
                                    <div class="info-item">
                                        <i class="far fa-clock"></i>
                                        <div>
                                            <span class="label">Thời gian:</span>
                                            <span class="value"><?php echo esc_html($duration); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($departure = get_field('departure')) : ?>
                                    <div class="info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div>
                                            <span class="label">Điểm khởi hành:</span>
                                            <span class="value"><?php echo esc_html($departure); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($transport = get_field('transport')) : ?>
                                    <div class="info-item">
                                        <i class="fas fa-bus"></i>
                                        <div>
                                            <span class="label">Phương tiện:</span>
                                            <span class="value"><?php echo esc_html($transport); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($group_size = get_field('group_size')) : ?>
                                    <div class="info-item">
                                        <i class="fas fa-users"></i>
                                        <div>
                                            <span class="label">Số lượng khách:</span>
                                            <span class="value"><?php echo esc_html($group_size); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="why-choose-us">
                            <h4>Tại sao chọn chúng tôi?</h4>
                            <ul>
                                <li><i class="fas fa-check-circle"></i> Giá tốt nhất</li>
                                <li><i class="fas fa-check-circle"></i> Đặt tour dễ dàng</li>
                                <li><i class="fas fa-check-circle"></i> Hỗ trợ 24/7</li>
                                <li><i class="fas fa-check-circle"></i> Thanh toán an toàn</li>
                                <li><i class="fas fa-check-circle"></i> Hoàn tiền đảm bảo</li>
                            </ul>
                        </div>
                    </aside>
                </div>

                <!-- Related Tours -->
                <?php 
                $related_tours = new WP_Query(array(
                    'post_type' => 'tour',
                    'posts_per_page' => 4,
                    'post__not_in' => array(get_the_ID()),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'destination',
                            'field'    => 'term_id',
                            'terms'    => wp_get_post_terms(get_the_ID(), 'destination', array('fields' => 'ids')),
                        ),
                    ),
                ));

                if ($related_tours->have_posts()) :
                ?>
                <div class="related-tours">
                    <h3>Các tour tương tự</h3>
                    <div class="tours-grid">
                        <?php while ($related_tours->have_posts()) : $related_tours->the_post(); ?>
                            <?php get_template_part('template-parts/content', 'tour'); ?>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php 
                wp_reset_postdata();
                endif; 
                ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
