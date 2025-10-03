<?php
/**
 * Template Name: Lịch khởi hành
 * Description: Hiển thị tất cả tour với ảnh đại diện theo dạng lưới hiện đại.
 * @package dulichvietnhat
 */

get_header();

// Lấy tất cả tour pages
$tour_pages_args = [
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_name__in'  => [
        'tour-5-ngay-4-dem',
        'tour-6-ngay-5-dem', 
        'tour-7-ngay-6-dem',
        'tour-hue-mua-thu-2025',
    ],
];

$tour_pages = get_posts($tour_pages_args);
?>

<main id="primary" class="site-main departures-page">
  <section class="section-padding bg-light">
    <div class="container">
      <div class="section-title">
        <h2><?php echo esc_html__('Lịch khởi hành', 'dulichvietnhat'); ?></h2>
        <p><?php echo esc_html__('Chọn một chuyên mục để xem các bài viết/tour liên quan.', 'dulichvietnhat'); ?></p>
      </div>

      <?php if (!empty($tour_pages)) : ?>
        <div class="departures-grid">
          <?php 
          // Hiển thị tất cả tour pages
          foreach ($tour_pages as $page) :
              $page_id = $page->ID;
              $page_title = get_the_title($page_id);
              $page_link = get_permalink($page_id);
              $page_excerpt = has_excerpt($page_id) ? get_the_excerpt($page_id) : '';
              
              // Lấy ảnh đại diện - ưu tiên Featured Image
              $thumbnail_url = get_the_post_thumbnail_url($page_id, 'large');
              
              // Nếu không có Featured Image, lấy từ Gallery
              if (!$thumbnail_url && function_exists('dln_get_gallery_image_ids')) {
                $gallery_ids = dln_get_gallery_image_ids($page_id);
                if (!empty($gallery_ids)) {
                  $thumbnail_url = wp_get_attachment_image_url($gallery_ids[0], 'large');
                }
              }
              
              // Nếu vẫn không có, lấy từ attachments (ảnh đính kèm)
              if (!$thumbnail_url) {
                $attachments = get_children([
                  'post_parent' => $page_id,
                  'post_type' => 'attachment',
                  'post_mime_type' => 'image',
                  'numberposts' => 1,
                  'orderby' => 'menu_order ID',
                  'order' => 'ASC',
                ]);
                if (!empty($attachments)) {
                  $first_attachment = array_shift($attachments);
                  $thumbnail_url = wp_get_attachment_image_url($first_attachment->ID, 'large');
                }
              }
              
              // Fallback: Dùng ảnh có sẵn trong thư mục assets/images
              if (!$thumbnail_url) {
                // Ảnh mặc định theo từ khóa
                $default_images = [
                  '5-ngay' => 'ba-na-hills.jpg',
                  '6-ngay' => 'son-tra-da-nang.jpg',
                  '7-ngay' => 'tour-da-nang-5-ngay.jpg',
                  'hue' => 'tour-hue.jpg',
                  'mua-thu' => '1206.thuathienhue1.jpg',
                  'nha-trang' => 'du-lich-nha-trang-thang-12-1_1623861390_1680144494.jpg',
                  'da-nang' => 'tour-da-nang-5-ngay (1).jpg',
                  'cu-lao' => 'tour-cu-lao-cham.jpg',
                  'bach-moc' => 'tour-bach-moc.jpg',
                ];
                
                // Tìm ảnh phù hợp với category
                $cat_slug_lower = strtolower($cat->slug);
                $cat_name_lower = strtolower($cat_name);
                
                foreach ($default_images as $keyword => $image) {
                  if (strpos($cat_slug_lower, $keyword) !== false || strpos($cat_name_lower, $keyword) !== false) {
                    $image_path = get_stylesheet_directory() . '/assets/images/' . $image;
                    if (file_exists($image_path)) {
                      $thumbnail_url = get_stylesheet_directory_uri() . '/assets/images/' . $image;
                      break;
                    }
                  }
                }
                
                // Nếu vẫn không có, dùng ảnh Huế đầu tiên
                if (!$thumbnail_url) {
                  $fallback_image = '1206.thuathienhue1.jpg';
                  $fallback_path = get_stylesheet_directory() . '/assets/images/' . $fallback_image;
                  if (file_exists($fallback_path)) {
                    $thumbnail_url = get_stylesheet_directory_uri() . '/assets/images/' . $fallback_image;
                  }
                }
              }
              
              // Đếm tổng số hình ảnh
              $image_count = 0;
              if (function_exists('dln_get_gallery_image_ids')) {
                $all_gallery_ids = dln_get_gallery_image_ids($page_id);
                $image_count = !empty($all_gallery_ids) ? count($all_gallery_ids) : 0;
              }
              
              if ($image_count === 0) {
                $all_attachments = get_children([
                  'post_parent' => $page_id,
                  'post_type' => 'attachment',
                  'post_mime_type' => 'image',
                  'numberposts' => -1,
                ]);
                $image_count = count($all_attachments);
              }
              
              // Tạo initials từ tên page
              $words = preg_split('/\s+/u', trim($page_title));
              $first_letter = mb_substr($words[0] ?? '', 0, 1, 'UTF-8');
              $last_letter = mb_substr(end($words) ?: '', 0, 1, 'UTF-8');
              $initials = strtoupper($first_letter . ($last_letter && $last_letter !== $first_letter ? $last_letter : ''));
              $initials = $initials ?: substr($page_title, 0, 2);
          ?>
              <a class="dep-card" href="<?php echo esc_url($page_link); ?>" aria-label="<?php echo esc_attr($page_title); ?>">
                <div class="dep-cover" aria-hidden="true">
                  <?php if ($thumbnail_url) : ?>
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($page_title); ?>" loading="lazy" />
                  <?php else : ?>
                    <div class="dep-placeholder" style="background: linear-gradient(135deg, #1E88E5, #42A5F5);">
                      <div class="dep-initials" style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 900; color: white; backdrop-filter: blur(8px); border: 2px solid rgba(255,255,255,0.3);">
                        <?php echo esc_html($initials); ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="dep-body">
                  <h3 class="dep-title"><?php echo esc_html($page_title); ?></h3>
                  <?php if ($page_excerpt) : ?>
                    <p class="dep-desc"><?php echo esc_html(wp_trim_words($page_excerpt, 15)); ?></p>
                  <?php endif; ?>
                  <div class="dep-meta">
                    <?php if ($image_count > 0) : ?>
                      <span class="dep-count"><i class="fa-regular fa-images"></i> <?php echo esc_html($image_count); ?> <?php echo esc_html__('hình ảnh', 'dulichvietnhat'); ?></span>
                    <?php endif; ?>
                    <span class="dep-view"><?php echo esc_html__('Xem', 'dulichvietnhat'); ?> <i class="fa-solid fa-arrow-right"></i></span>
                  </div>
                </div>
              </a>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        <p class="text-center"><?php echo esc_html__('Chưa có danh mục nào.', 'dulichvietnhat'); ?></p>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
