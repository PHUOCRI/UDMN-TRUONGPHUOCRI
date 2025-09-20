<?php
/**
 * Template Name: Lịch khởi hành
 * Description: Hiển thị tất cả chuyên mục (category) theo dạng lưới hiện đại để người dùng xem và chọn.
 * @package dulichvietnhat
 */

get_header();

$cats = get_categories([
    'taxonomy'   => 'category',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

// Helper: tạo chữ cái viết tắt từ tên chuyên mục
function doan_get_initials($name) {
    $words = preg_split('/\s+/u', trim($name));
    $first = mb_substr($words[0] ?? '', 0, 1, 'UTF-8');
    $last  = mb_substr(end($words) ?: '', 0, 1, 'UTF-8');
    $initials = strtoupper($first . ($last && $last !== $first ? $last : ''));
    return esc_html($initials ?: 'C');
}
?>

<main id="primary" class="site-main departures-page">
  <section class="section-padding bg-light">
    <div class="container">
      <div class="section-title">
        <h2><?php echo esc_html__('Lịch khởi hành', 'dulichvietnhat'); ?></h2>
        <p><?php echo esc_html__('Chọn một chuyên mục để xem các bài viết/tour liên quan.', 'dulichvietnhat'); ?></p>
      </div>

      <?php if (!empty($cats)) : ?>
        <div class="departures-grid">
          <?php foreach ($cats as $cat) : 
            $link = get_category_link($cat);
            $count = intval($cat->count);
            $desc = trim(strip_tags(term_description($cat)));

            // Lấy ảnh đại diện: thumbnail của bài viết mới nhất trong chuyên mục
            $cover_url = '';
            $latest_posts = get_posts([
              'cat'            => (int) $cat->term_id,
              'numberposts'    => 1,
              'post_status'    => 'publish',
              'suppress_filters' => true,
            ]);
            if (!empty($latest_posts)) {
              $cover = get_the_post_thumbnail_url($latest_posts[0]->ID, 'large');
              if ($cover) { $cover_url = $cover; }
            }
          ?>
            <a class="dep-card" href="<?php echo esc_url($link); ?>" aria-label="<?php echo esc_attr($cat->name); ?>">
              <div class="dep-cover" aria-hidden="true">
                <?php if ($cover_url) : ?>
                  <img src="<?php echo esc_url($cover_url); ?>" alt="<?php echo esc_attr($cat->name); ?>" loading="lazy" />
                <?php else : ?>
                  <div class="dep-initials"><?php echo doan_get_initials($cat->name); ?></div>
                <?php endif; ?>
              </div>
              <div class="dep-body">
                <h3 class="dep-title"><?php echo esc_html($cat->name); ?></h3>
                <?php if ($desc) : ?>
                  <p class="dep-desc"><?php echo esc_html(wp_trim_words($desc, 18)); ?></p>
                <?php endif; ?>
                <div class="dep-meta">
                  <span class="dep-count"><i class="fa-regular fa-file-lines"></i> <?php echo esc_html($count); ?> <?php echo esc_html__('bài viết', 'dulichvietnhat'); ?></span>
                  <span class="dep-view"><?php echo esc_html__('Xem', 'dulichvietnhat'); ?> <i class="fa-solid fa-arrow-right"></i></span>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        <p class="text-center"><?php echo esc_html__('Chưa có chuyên mục nào.', 'dulichvietnhat'); ?></p>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
