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

            // 2) Category list with image + price (vertical list)
            $cats = get_categories(['hide_empty' => false]);
            if (!empty($cats)) : ?>
              <div class="sidebar-cats">
                <h3 class="sidebar-title"><?php echo esc_html__('Danh mục', 'dulichvietnhat'); ?></h3>
                <ul class="cats-list">
                <?php foreach ($cats as $cat) :
                  // Bỏ 'Uncategorized' (mọi ngôn ngữ) và biến thể slug phổ biến
                  $default_cat_id = (int) get_option('default_category');
                  $ban_slugs = ['uncategorized', 'chua-phan-loai', 'khong-phan-loai'];
                  if ($cat->term_id === $default_cat_id || in_array($cat->slug, $ban_slugs, true)) { continue; }
                  // Lấy ảnh chuyên mục: dùng ảnh bài viết mới nhất trong chuyên mục
                  $img_url = '';
                  // Ưu tiên: ảnh đại diện bài viết mới nhất trong chuyên mục (có thumbnail)
                  $q_img = new WP_Query([
                    'post_type'      => ['tour','post'],
                    'posts_per_page' => 1,
                    'no_found_rows'  => true,
                    'meta_query'     => [[ 'key' => '_thumbnail_id', 'compare' => 'EXISTS' ]],
                    'tax_query'      => [[ 'taxonomy' => 'category', 'field' => 'term_id', 'terms' => [(int)$cat->term_id] ]],
                  ]);
                  if ($q_img->have_posts()) {
                    $q_img->the_post();
                    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    wp_reset_postdata();
                  }
                  // Fallback: ảnh gán cho term
                  if (!$img_url) {
                    $thumb_id = (int) get_term_meta($cat->term_id, 'thumbnail_id', true);
                    $img_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium') : '';
                  }
                  // Fallback cuối: meta 'image' URL hoặc placeholder trong theme
                  if (!$img_url) {
                    $img_meta = get_term_meta($cat->term_id, 'image', true);
                    if (filter_var($img_meta, FILTER_VALIDATE_URL)) { $img_url = $img_meta; }
                    else { $img_url = get_template_directory_uri() . '/assets/images/placeholder-800x500.jpg'; }
                  }
                  // Lấy giá: ưu tiên term meta 'price'; fallback tìm min price của bài trong category
                  $term_price = get_term_meta($cat->term_id, 'price', true);
                  $price_to_show = '';
                  if ($term_price !== '' && $term_price !== null) {
                    $price_to_show = $term_price;
                  } else {
                    $candidate_post_types = ['tour', 'post'];
                    $candidate_price_keys = ['price', 'gia_tour', 'tour_price'];
                    $found = false;
                    foreach ($candidate_post_types as $cpt) {
                      if ($found) break;
                      foreach ($candidate_price_keys as $price_key) {
                        $q = new WP_Query([
                          'post_type'      => $cpt,
                          'posts_per_page' => 1,
                          'orderby'        => 'meta_value_num',
                          'order'          => 'ASC',
                          'no_found_rows'  => true,
                          'meta_key'       => $price_key,
                          'meta_query'     => [[ 'key' => $price_key, 'compare' => 'EXISTS' ]],
                          'tax_query'      => [[ 'taxonomy' => 'category', 'field' => 'term_id', 'terms' => [(int)$cat->term_id] ]],
                        ]);
                        if ($q->have_posts()) {
                          $q->the_post();
                          $val = get_post_meta(get_the_ID(), $price_key, true);
                          wp_reset_postdata();
                          if ($val !== '' && $val !== null) { $price_to_show = $val; $found = true; break; }
                        }
                      }
                    }
                  }
                  // Format VND
                  $price_badge = '';
                  if ($price_to_show !== '' && $price_to_show !== null) {
                    $num = preg_replace('/[^0-9\.,]/', '', (string)$price_to_show);
                    $num = str_replace('.', '', $num);
                    $num = str_replace(',', '.', $num);
                    if (is_numeric($num)) { $price_badge = number_format((float)$num, 0, ',', '.') . ' đ'; }
                    else { $price_badge = esc_html($price_to_show); }
                  }
                ?>
                  <li class="cat-item">
                    <a href="<?php echo esc_url(get_category_link($cat)); ?>" class="cat-link">
                      <div class="thumb">
                        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($cat->name); ?>" loading="lazy" />
                      </div>
                      <div class="info">
                        <div class="title"><?php echo esc_html($cat->name); ?></div>
                        <?php if ($price_badge) : ?><div class="price"><?php echo esc_html($price_badge); ?></div><?php endif; ?>
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
