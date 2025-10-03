<?php
/**
 * Template: Search Results
 *
 * @package dulichvietnhat
 */

get_header();

$search_query = get_search_query();
if (!function_exists('doan_highlight')) {
    function doan_highlight($text, $query) {
        if (empty($query)) return esc_html($text);
        $pattern = '/' . preg_quote($query, '/') . '/iu';

        $escaped = esc_html($text);
        return preg_replace($pattern, '<mark>$0</mark>', $escaped);
    }
}
?>

<main id="primary" class="site-main">
    <section class="section-padding bg-light">
        <div class="container">
            <div class="section-title">
                <h2><?php printf(esc_html__('Kết quả cho: “%s”', 'dulichvietnhat'), esc_html($search_query)); ?></h2>
                <p><?php echo esc_html__('Các nội dung khớp với từ khóa bạn tìm.', 'dulichvietnhat'); ?></p>
            </div>

            <?php if (have_posts()) : ?>
                <div class="tour-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('tour-card'); ?>>
                            <a class="tour-image" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-800x500.jpg'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
                                <?php endif; ?>
                                <span class="tour-duration">
                                    <?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?>
                                </span>
                            </a>

                            <div class="tour-info">
                                <span class="tour-category">
                                    <?php
                                    if (get_post_type() === 'post') {
                                        $cat = get_the_category();
                                        if (!empty($cat)) echo esc_html($cat[0]->name);
                                    } else {
                                        echo esc_html(get_post_type_object(get_post_type())->labels->name);
                                    }
                                    ?>
                                </span>

                                <h3 class="tour-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php echo wp_kses_post(doan_highlight(get_the_title(), $search_query)); ?>
                                    </a>
                                </h3>

                                <div class="tour-meta">
                                    <span><i class="fa-regular fa-calendar"></i> <?php echo esc_html(get_the_date()); ?></span>
                                    <span><i class="fa-regular fa-user"></i> <?php echo esc_html(get_the_author()); ?></span>
                                </div>

                                <p class="tour-excerpt">
                                    <?php
                                    $excerpt = get_the_excerpt();
                                    if (empty($excerpt)) {
                                        $excerpt = wp_trim_words(wp_strip_all_tags(get_the_content()), 25);
                                    }
                                    echo wp_kses_post(doan_highlight($excerpt, $search_query));
                                    ?>
                                </p>

                                <div class="tour-footer">
                                    <a class="btn btn-outline" href="<?php the_permalink(); ?>">
                                        <?php echo esc_html__('Xem chi tiết', 'dulichvietnhat'); ?> <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                    <a class="btn" href="<?php the_permalink(); ?>">
                                        <?php echo esc_html__('Đọc tiếp', 'dulichvietnhat'); ?>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <div class="container" style="margin-top:30px;">
                    <?php the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => '<i class="fa-solid fa-angle-left"></i>',
                        'next_text' => '<i class="fa-solid fa-angle-right"></i>',
                    )); ?>
                </div>

            <?php else : ?>
                <div class="section-title">
                    <h2><?php echo esc_html__('Không tìm thấy kết quả', 'dulichvietnhat'); ?></h2>
                    <p><?php echo esc_html__('Hãy thử lại với từ khóa khác chính xác hơn.', 'dulichvietnhat'); ?></p>
                </div>
                <div class="container" style="max-width:700px;">
                    <?php get_search_form(); ?>
                    <div style="margin-top:20px;">
                        <a class="btn" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Về trang chủ', 'dulichvietnhat'); ?></a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
