<?php
/**
 * Single Post Template
 * Displays a post on its own page with a modern layout.
 * @package dulichvietnhat
 */

get_header();

if (have_posts()) : while (have_posts()) : the_post();
  $categories = get_the_category(get_the_ID());
  $primary_cat = !empty($categories) ? $categories[0] : null;
?>

<main id="primary" class="site-main single-article">
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php /* Removed hero banner slider. Featured image is shown inline in content */ ?>

    <section class="section-padding">
      <div class="container">
        <div class="single-layout">
          <div class="single-main">
            <div class="post-card">
              <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Trang chủ', 'dulichvietnhat'); ?></a>
                <?php if ($primary_cat) : ?>
                  <span class="divider">/</span>
                  <a href="<?php echo esc_url(get_category_link($primary_cat)); ?>"><?php echo esc_html($primary_cat->name); ?></a>
                <?php endif; ?>
              </nav>

              <header class="post-header">
                <?php if ($primary_cat) : ?>
                  <a class="post-category" href="<?php echo esc_url(get_category_link($primary_cat)); ?>"><?php echo esc_html($primary_cat->name); ?></a>
                <?php endif; ?>
                <h1 class="post-title"><?php the_title(); ?></h1>
                <div class="post-meta">
                  <span><i class="fa-regular fa-user"></i> <?php the_author(); ?></span>
                  <span><i class="fa-regular fa-calendar"></i> <?php echo esc_html(get_the_date()); ?></span>
                  <?php if (get_comments_number()) : ?>
                    <span><i class="fa-regular fa-comments"></i> <?php echo absint(get_comments_number()); ?></span>
                  <?php endif; ?>
                </div>
                <hr class="post-sep" />
              </header>

              <?php if (has_post_thumbnail()) : ?>
                <figure class="post-featured limited">
                  <?php the_post_thumbnail('large', ['loading' => 'lazy']); ?>
                </figure>
              <?php endif; ?>

              <div class="post-content typography">
                <?php the_content(); ?>
              </div>

              <?php the_tags('<div class="post-tags"><span class="label">Tags:</span> ', ' ', '</div>'); ?>

              <nav class="post-nav">
                <div class="prev">
                  <?php previous_post_link('%link', '<i class="fa-solid fa-arrow-left"></i> %title'); ?>
                </div>
                <div class="next">
                  <?php next_post_link('%link', '%title <i class="fa-solid fa-arrow-right"></i>'); ?>
                </div>
              </nav>
            </div>
          </div>

          <aside class="single-sidebar">
            <?php
            // 1) Filter sidebar via shortcode (requires VJ Filter plugin)
            if (shortcode_exists('vj_filter')) {
              echo do_shortcode('[vj_filter post_type="tour" price_meta="price" date_meta="start_date" tax_departure="departure" tax_destination="destination" tax_line="tour_line" tax_vehicle="vehicle" per_page="6"]');
            }

            // 2) Featured Posts list - prioritize posts with "Huế" in title or specific categories
            $featured_posts = get_posts([
              'post_type'      => ['post', 'tour'],
              'posts_per_page' => 10,
              'post_status'    => 'publish',
              's'              => 'Huế', // Tìm bài viết có từ "Huế"
              'orderby'        => 'date',
              'order'          => 'DESC',
            ]);
            
            // Nếu không đủ bài về Huế, lấy thêm bài viết mới nhất
            if (count($featured_posts) < 6) {
              $additional_posts = get_posts([
                'post_type'      => ['post', 'tour'],
                'posts_per_page' => 10 - count($featured_posts),
                'post_status'    => 'publish',
                'post__not_in'   => wp_list_pluck($featured_posts, 'ID'),
                'orderby'        => 'date',
                'order'          => 'DESC',
              ]);
              $featured_posts = array_merge($featured_posts, $additional_posts);
            }
            
            if (!empty($featured_posts)) : ?>
              <div class="sidebar-cats">
                <h3 class="sidebar-title"><?php echo esc_html__('Bài viết nổi bật', 'dulichvietnhat'); ?></h3>
                <ul class="cats-list">
                <?php foreach ($featured_posts as $post_item) :
                  $post_id = $post_item->ID;
                  $post_title = get_the_title($post_id);
                  $post_link = get_permalink($post_id);
                  
                  // Lấy ảnh thumbnail
                  $img_url = get_the_post_thumbnail_url($post_id, 'medium');
                  
                  // Nếu không có, thử từ gallery
                  if (!$img_url && function_exists('dln_get_gallery_image_ids')) {
                    $gallery_ids = dln_get_gallery_image_ids($post_id);
                    if (!empty($gallery_ids)) {
                      $img_url = wp_get_attachment_image_url($gallery_ids[0], 'medium');
                    }
                  }
                  
                  // Nếu vẫn không có, thử attachments
                  if (!$img_url) {
                    $attachments = get_children([
                      'post_parent' => $post_id,
                      'post_type' => 'attachment',
                      'post_mime_type' => 'image',
                      'numberposts' => 1,
                      'orderby' => 'menu_order',
                      'order' => 'ASC',
                    ]);
                    if (!empty($attachments)) {
                      $first_attachment = array_shift($attachments);
                      $img_url = wp_get_attachment_image_url($first_attachment->ID, 'medium');
                    }
                  }
                  
                  // Fallback cuối: dùng ảnh có sẵn trong assets
                  if (!$img_url) {
                    $img_url = get_stylesheet_directory_uri() . '/assets/images/1206.thuathienhue1.jpg';
                  }
                  
                  // Flag để biết có ảnh thật không
                  $has_real_image = get_the_post_thumbnail_url($post_id, 'medium') ? true : false;
                  
                  // Lấy giá từ ACF hoặc post meta
                  $price_to_show = '';
                  $price_keys = ['price', 'gia_tour', 'tour_price'];
                  
                  foreach ($price_keys as $key) {
                    $price_val = get_post_meta($post_id, $key, true);
                    if ($price_val !== '' && $price_val !== null) {
                      $price_to_show = $price_val;
                      break;
                    }
                  }
                  
                  // Format giá VND
                  $price_badge = '';
                  if ($price_to_show !== '' && $price_to_show !== null) {
                    $num = preg_replace('/[^0-9\.,]/', '', (string)$price_to_show);
                    $num = str_replace('.', '', $num);
                    $num = str_replace(',', '.', $num);
                    if (is_numeric($num)) { 
                      $price_badge = number_format((float)$num, 0, ',', '.') . ' đ'; 
                    } else { 
                      $price_badge = esc_html($price_to_show); 
                    }
                  }
                ?>
                  <li class="cat-item">
                    <a href="<?php echo esc_url($post_link); ?>" class="cat-link">
                      <div class="thumb" style="width: 80px; height: 64px; overflow: hidden; flex-shrink: 0;">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($post_title); ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                      </div>
                      <div class="info" style="flex: 1; padding-right: 10px;">
                        <div class="title" style="color: #111827; font-weight: 700; font-size: 14px; line-height: 1.3; margin-bottom: 4px;"><?php echo esc_html($post_title); ?></div>
                        <?php if ($price_badge) : ?><div class="price" style="color: #1E88E5; font-weight: 800; font-size: 13px;"><?php echo esc_html($price_badge); ?></div><?php endif; ?>
                      </div>
                    </a>
                  </li>
                <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
          </aside>
        </div>
      </div>
    </section>

    <?php
    // Related posts by first category
    if ($primary_cat) :
      $related = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 4,
        'cat'            => (int) $primary_cat->term_id,
        'post__not_in'   => [get_the_ID()],
        'no_found_rows'  => true,
      ]);
      if ($related->have_posts()) : ?>
        <section class="section-padding bg-light related-posts">
          <div class="container">
            <div class="section-title">
              <h2><?php echo esc_html__('Bài viết liên quan', 'dulichvietnhat'); ?></h2>
            </div>
            <div class="tour-grid">
              <?php while ($related->have_posts()) : $related->the_post(); ?>
                <article id="rel-<?php the_ID(); ?>" <?php post_class('tour-card'); ?>>
                  <a class="tour-image" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                    <?php if (has_post_thumbnail()) {
                      the_post_thumbnail('large', ['loading' => 'lazy']);
                    } else { ?>
                      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-800x500.jpg'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
                    <?php } ?>
                  </a>
                  <div class="tour-info">
                    <h3 class="tour-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="tour-meta">
                      <span><i class="fa-regular fa-calendar"></i> <?php echo esc_html(get_the_date()); ?></span>
                      <span><i class="fa-regular fa-user"></i> <?php echo esc_html(get_the_author()); ?></span>
                    </div>
                    <div class="tour-footer">
                      <a class="btn btn-outline" href="<?php the_permalink(); ?>"><?php echo esc_html__('Xem chi tiết', 'dulichvietnhat'); ?></a>
                    </div>
                  </div>
                </article>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
          </div>
        </section>
      <?php endif; endif; ?>

    <?php comments_template(); ?>
  </article>
</main>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
