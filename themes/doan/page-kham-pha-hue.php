<?php
/**
 * Template Name: Khám phá Nhật Bản
 * Description: Trang hiển thị danh sách các bài viết từ chuyên mục Khám phá (post type: kham-pha), không có bình luận.
 */

get_header();
?>

<main id="primary" class="site-main">
    <section class="explore-list section-padding">
        <div class="container">
        <div class="section-header">
    <h2 class="section-title"><?php echo esc_html( get_the_title() ); ?></h2>
    </div>

            <div class="posts-grid explore-grid">
                <?php
                $paged = max(1, get_query_var('paged'));
                $args = array(
                    'post_type'      => 'kham-pha',
                    'post_status'    => 'publish',
                    'posts_per_page' => 9,
                    'paged'          => $paged,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                $q = new WP_Query($args);

                if ($q->have_posts()):
                    while ($q->have_posts()): $q->the_post(); ?>
                        <article class="post-card explore-card">
                            <div class="post-thumbnail">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large', array('class' => 'post-image', 'alt' => get_the_title())); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="post-image-placeholder">
                                        <div class="placeholder-content">
                                            <i class="fas fa-compass"></i>
                                            <span><?php esc_html_e('Khám phá', 'doan'); ?></span>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="post-content">
                                <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '…' ) ); ?></p>
                                <a class="read-more-btn" href="<?php the_permalink(); ?>"><?php esc_html_e('Đọc thêm','doan'); ?> <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </article>
                    <?php endwhile; ?>
            </div>

            <div class="pagination-wrapper">
                <?php
                echo paginate_links(array(
                    'total'   => $q->max_num_pages,
                    'current' => $paged,
                    'mid_size'=> 2,
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                ));
                ?>
            </div>

            <?php wp_reset_postdata(); else: ?>
                <p><?php esc_html_e('Chưa có bài viết nào.', 'doan'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
