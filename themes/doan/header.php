<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <?php
    // Preload the first slider image to improve LCP discovery
    // Only on pages where the big homepage slider is shown (rough heuristic: not single/search/dedicated pages)
    if (!is_single() && !is_search() && !is_page('lich-khoi-hanh') && !is_page('dang-ky-tu-van') 
        && !is_page('hinh-anh-thuc-te')
        && !is_page('kham-pha-nhat-ban')
        && !is_page('tour-hue-mua-thu-2025')
        && !is_page('tour-7-ngay-6-dem')
        && !is_page('tour-6-ngay-5-dem')
        && !is_page('tour-5-ngay-4-dem')
        && !is_page_template('page-dang-ky-tu-van.php')
        && !is_page_template('page-tai-khoan.php')
        && !is_page('tai-khoan') ) {
        $first_img_url = '';
        $first_img_id  = 0;
        $q = new WP_Query(array(
            'post_type'      => 'slider',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'orderby'        => array('menu_order' => 'ASC', 'date' => 'DESC'),
            'no_found_rows'  => true,
            'fields'         => 'ids',
        ));
        if ($q->have_posts()) {
            $pid = $q->posts[0];
            // Try featured image first
            $thumb_id = get_post_thumbnail_id($pid);
            if ($thumb_id) { $first_img_id = (int)$thumb_id; }
            // Fallback ACF fields commonly used
            if (!$first_img_id && function_exists('get_field')) {
                $acf_img = get_field('image', $pid);
                if (is_array($acf_img) && !empty($acf_img['ID'])) { $first_img_id = (int)$acf_img['ID']; }
                if (!$first_img_id) {
                    $acf_img = get_field('slider_image', $pid);
                    if (is_array($acf_img) && !empty($acf_img['ID'])) { $first_img_id = (int)$acf_img['ID']; }
                }
            }
            // Last fallback: parse content for first <img>
            if (!$first_img_id) {
                $content = get_post_field('post_content', $pid);
                if ($content && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m)) {
                    $first_img_url = esc_url_raw($m[1]);
                }
            }
        }
        wp_reset_postdata();

        if ($first_img_id) {
            $src      = wp_get_attachment_image_src($first_img_id, 'slider-large');
            $srcset   = wp_get_attachment_image_srcset($first_img_id, 'slider-large');
            $sizes    = '(max-width: 768px) 100vw, (max-width: 1200px) 90vw, 1200px';
            if ($src && !empty($src[0])) {
                printf(
                    "<link rel='preload' as='image' href='%s' imagesrcset='%s' imagesizes='%s' />\n",
                    esc_url($src[0]),
                    esc_attr($srcset ? $srcset : ''),
                    esc_attr($sizes)
                );
            }
        } elseif ($first_img_url) {
            printf("<link rel='preload' as='image' href='%s' />\n", esc_url($first_img_url));
        }
    }
    ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="contact-info">
                    <?php if ($phone = get_theme_mod('header_phone', '0123456798')) : ?>
                        <div class="contact-item"
                        >
                            <i class="fas fa-phone-alt"></i>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>">
                                <?php echo esc_html($phone); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if ($email = get_theme_mod('header_email', 'ri2005@gmail.com')) : ?>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:<?php echo esc_attr($email); ?>">
                                <?php echo esc_html($email); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="top-bar-actions">
                    <button class="search-toggle" aria-label="Search">
                        <i class="fas fa-search"></i>
                    </button>
                    <?php 
                        $acc_page = get_page_by_path('tai-khoan');
                        $account_url = $acc_page ? get_permalink($acc_page) : ( is_user_logged_in() ? admin_url('profile.php') : wp_login_url() );
                    ?>
                    <?php if ( is_user_logged_in() ) : ?>
                        <div class="user-menu">
                            <button class="user-icon topbar-user user-menu-toggle" aria-label="Tài khoản" aria-expanded="false" data-account-url="<?php echo esc_attr($account_url); ?>">
                                <i class="fas fa-user"></i>
                            </button>
                            <ul class="user-menu-dropdown" role="menu" aria-label="User menu">
                                <li role="none"><a role="menuitem" href="<?php echo esc_url($account_url); ?>"><?php esc_html_e('Tài khoản của tôi','doan'); ?></a></li>
                                <?php if ( current_user_can('edit_posts') ) : ?>
                                    <li role="none"><a role="menuitem" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e('Bảng điều khiển','doan'); ?></a></li>
                                <?php endif; ?>
                                <li role="none"><a role="menuitem" href="<?php echo esc_url( wp_logout_url( home_url('/') ) ); ?>"><?php esc_html_e('Đăng xuất','doan'); ?></a></li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url($account_url); ?>" class="user-icon topbar-user" aria-label="Tài khoản">
                            <i class="fas fa-user"></i>
                        </a>
                    <?php endif; ?>
                    <?php 
                        if (function_exists('pll_the_languages') && function_exists('dln_poly_switcher')) {
                            echo dln_poly_switcher(true);
                        } elseif (function_exists('dln_lang_switcher')) {
                            echo dln_lang_switcher(true);
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="header-wrapper">
             
                <div class="site-branding">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        <?php if (function_exists('the_custom_logo') && has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                            <div class="logo-text">
                                <h1 class="site-title"><?php bloginfo('name'); ?></h1>
                                <p class="site-tagline"><?php bloginfo('description'); ?></p>
                            </div>
                        <?php else : ?>
                            <div class="logo-container">
                                <div class="vj-logo">
                                    <span class="v-letter">V</span>
                                    <span class="j-letter">J</span>
                                </div>
                                <div class="logo-text">
                                    <h1 class="site-title"><?php bloginfo('name'); ?></h1>
                                    <p class="site-tagline"><?php bloginfo('description'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>

             
                <nav id="site-navigation" class="main-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            // Fallback menu nếu chưa tạo menu trong admin
                            echo '<ul class="primary-menu">';
                            echo '<li><a href="' . esc_url(home_url('/')) . '">Trang chủ</a></li>';
                            echo '<li><a href="' . esc_url(home_url('/kham-pha-hue')) . '">Khám phá Huế</a></li>';
                            echo '<li class="has-dropdown">';
                            echo '<a href="' . esc_url(home_url('/lich-khoi-hanh')) . '">Lịch khởi hành <i class="fas fa-chevron-down dropdown-icon"></i></a>';
                            echo '<ul class="sub-menu">';
                            echo '<li><a href="' . esc_url(home_url('/tour-hue-mua-thu-2025')) . '">Tour Huế Mùa Thu 2025</a></li>';
                            echo '<li><a href="' . esc_url(home_url('/tour-7-ngay-6-dem')) . '">Tour 7 ngày 6 đêm</a></li>';
                            echo '<li><a href="' . esc_url(home_url('/tour-6-ngay-5-dem')) . '">Tour 6 ngày 5 đêm</a></li>';
                            echo '<li><a href="' . esc_url(home_url('/tour-5-ngay-4-dem')) . '">Tour 5 ngày 4 đêm</a></li>';
                            echo '</ul></li>';
                            echo '<li><a href="' . esc_url(home_url('/hinh-anh-thuc-te')) . '">Hình ảnh thực tế</a></li>';
                            echo '</ul>';
                        }
                    ));
                    ?>
                </nav>

                <div class="header-actions">
                    <?php $account_url = is_user_logged_in() ? admin_url('profile.php') : wp_login_url(); ?>
                    <a href="<?php echo esc_url(home_url('/dang-ky-tu-van')); ?>" class="consultation-btn">
                        <?php esc_html_e('Đăng ký tư vấn','doan'); ?>
                    </a>
                    
                    <?php if ( is_user_logged_in() ) : ?>
                        <div class="user-menu header-user">
                            <button class="user-icon topbar-user user-menu-toggle" aria-label="Tài khoản" aria-expanded="false" data-account-url="<?php echo esc_attr($account_url); ?>">
                                <i class="fas fa-user"></i>
                            </button>
                            <ul class="user-menu-dropdown" role="menu" aria-label="User menu">
                                <li role="none"><a role="menuitem" href="<?php echo esc_url($account_url); ?>"><?php esc_html_e('Tài khoản của tôi','doan'); ?></a></li>
                                <?php if ( current_user_can('edit_posts') ) : ?>
                                    <li role="none"><a role="menuitem" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e('Bảng điều khiển','doan'); ?></a></li>
                                <?php endif; ?>
                                <li role="none"><a role="menuitem" href="<?php echo esc_url( wp_logout_url( home_url('/') ) ); ?>"><?php esc_html_e('Đăng xuất','doan'); ?></a></li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url($account_url); ?>" class="user-icon topbar-user header-user" aria-label="Tài khoản">
                            <i class="fas fa-user"></i>
                        </a>
                    <?php endif; ?>

                    <button class="menu-toggle" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="hamburger">
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                        </span>
                        <span class="screen-reader-text"><?php esc_html_e('Menu', 'doan'); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

  
    <div class="mobile-menu-overlay"></div>
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <div class="mobile-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    <?php if (function_exists('the_custom_logo') && has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <span class="mobile-site-title"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                </a>
            </div>
            <div class="mobile-actions">
                <button class="search-toggle" aria-label="Search"><i class="fas fa-search"></i></button>
                <button class="mobile-menu-close" aria-label="Close menu"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="mobile-menu-content">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class'     => 'mobile-menu-items',
                'container'      => false,
                'fallback_cb'    => function() {
                    echo '<ul class="mobile-menu-items">';
                    echo '<li><a href="' . esc_url(home_url('/')) . '">Trang chủ</a></li>';
                    echo '<li><a href="' . esc_url(home_url('/kham-pha-hue')) . '">Khám phá Huế</a></li>';
                    echo '<li class="has-sub">';
                    echo '<a href="' . esc_url(home_url('/lich-khoi-hanh')) . '">Lịch khởi hành</a>';
                    echo '<button class="mobile-sub-toggle" aria-label="Mở danh mục" aria-expanded="false"><i class="fas fa-chevron-down"></i></button>';
                    echo '<ul class="mobile-sub-menu">';
                    echo '<li><a href="' . esc_url(home_url('/tour-hue-mua-thu-2025')) . '">Tour Huế Mùa Thu 2025</a></li>';
                    echo '<li><a href="' . esc_url(home_url('/tour-7-ngay-6-dem')) . '">Tour 7 ngày 6 đêm</a></li>';
                    echo '<li><a href="' . esc_url(home_url('/tour-6-ngay-5-dem')) . '">Tour 6 ngày 5 đêm</a></li>';
                    echo '<li><a href="' . esc_url(home_url('/tour-5-ngay-4-dem')) . '">Tour 5 ngày 4 đêm</a></li>';
                    echo '</ul></li>';
                    echo '<li><a href="' . esc_url(home_url('/hinh-anh-thuc-te')) . '">Hình ảnh thực tế</a></li>';
                    echo '<li><a href="' . esc_url(home_url('/dang-ky-tu-van')) . '" class="mobile-consultation-btn">Đăng ký tư vấn</a></li>';
                    echo '</ul>';
                }
            ));
            ?>
        </div>
    </div>


    <div class="search-overlay">
        <div class="search-overlay-content">
            <div class="search-header">
                <h3><?php esc_html_e('Search', 'doan'); ?></h3>
                <button class="search-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="search-form-wrapper">
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>

    <?php if ( ! is_single() && ! is_search() && ! is_page('lich-khoi-hanh') && ! is_page('dang-ky-tu-van') 
    && ! is_page('hinh-anh-thuc-te')
    && ! is_page('kham-pha-hue')
    && ! is_page('tour-hue-mua-thu-2025')
    && ! is_page('tour-7-ngay-6-dem')
    && ! is_page('tour-6-ngay-5-dem')
    && ! is_page('tour-5-ngay-4-dem')
    && ! is_page_template('page-dang-ky-tu-van.php')  
    && ! is_page_template('page-tai-khoan.php') 
    && ! is_page('tai-khoan') 
     ) : ?>
      
        <section id="image-slider" class="image-slider-section">
            <?php
            $slides = new WP_Query(array(
                'post_type'      => 'slider',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => array('menu_order' => 'ASC', 'date' => 'DESC'),
            ));
            $slide_items = array();
            if ( $slides->have_posts() ) {
                while ( $slides->have_posts() ) { $slides->the_post();
                    $post_id = get_the_ID();
                    $title   = get_the_title();
                    $img_url = '';
                    $img_url = get_the_post_thumbnail_url($post_id, 'full');
                    if ( ! $img_url && function_exists('get_field') ) {
                        $acf_img = get_field('image', $post_id);
                        if (is_array($acf_img) && isset($acf_img['url'])) { $img_url = $acf_img['url']; }
                        elseif (is_string($acf_img)) { $img_url = $acf_img; }
                        if (! $img_url) {
                            $acf_img = get_field('slider_image', $post_id);
                            if (is_array($acf_img) && isset($acf_img['url'])) { $img_url = $acf_img['url']; }
                            elseif (is_string($acf_img)) { $img_url = $acf_img; }
                        }
                    }
                    if ( ! $img_url ) {
                        $meta_keys = array('image', 'slider_image', '_thumbnail_id');
                        foreach ($meta_keys as $k) {
                            $val = get_post_meta($post_id, $k, true);
                            if ($val) {
                                if (is_numeric($val)) { $img_url = wp_get_attachment_url(intval($val)); }
                                elseif (filter_var($val, FILTER_VALIDATE_URL)) { $img_url = $val; }
                            }
                            if ($img_url) break;
                        }
                    }
                    if ( ! $img_url ) {
                        $content = get_post_field('post_content', $post_id);
                        if ($content && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m)) {
                            $img_url = esc_url_raw($m[1]);
                        }
                    }

    
                    if ( ! $img_url ) {
                        $attachments = get_children(array(
                            'post_parent'    => $post_id,
                            'post_type'      => 'attachment',
                            'post_mime_type' => 'image',
                            'numberposts'    => 1,
                            'orderby'        => 'menu_order ID',
                            'order'          => 'ASC',
                        ));
                        if ($attachments) {
                            $att = array_shift($attachments);
                            $img_url = wp_get_attachment_url($att->ID);
                        }
                    }

                    if ($img_url) {
                        $slide_items[] = array(
                            'title' => $title,
                            'img'   => $img_url,
                        );
                    }
                }
                wp_reset_postdata();
            }

            if ( ! empty($slide_items) ) :
                $slide_count = count($slide_items);
            ?>
            <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php for ($i = 0; $i < $slide_count; $i++) : ?>
                        <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="<?php echo esc_attr($i); ?>" class="<?php echo $i === 0 ? 'active' : ''; ?>" aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo esc_attr($i + 1); ?>"></button>
                    <?php endfor; ?>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($slide_items as $index => $item) : ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <?php
                            $img_attrs = array(
                                'class' => 'd-block w-100',
                                'alt'   => $item['title'],
                            );
                            // First slide is likely LCP: prioritize it
                            if ($index === 0) {
                                $img_attrs['loading'] = 'eager';
                                $img_attrs['fetchpriority'] = 'high';
                                $img_attrs['decoding'] = 'async';
                                $sizes = '(max-width: 768px) 100vw, (max-width: 1200px) 90vw, 1200px';
                            } else {
                                $img_attrs['loading'] = 'lazy';
                                $img_attrs['decoding'] = 'async';
                                $sizes = '(max-width: 768px) 100vw, (max-width: 1200px) 90vw, 1200px';
                            }

                            // Prefer attachment-based output for srcset/webp if we have an ID
                            $attachment_id = 0;
                            if (!empty($item['id'])) {
                                $attachment_id = (int)$item['id'];
                            } elseif (!empty($item['img'])) {
                                $maybe_id = attachment_url_to_postid($item['img']);
                                if ($maybe_id) { $attachment_id = (int)$maybe_id; }
                            }

                            if ($attachment_id) {
                                echo wp_get_attachment_image($attachment_id, 'slider-large', false, array_merge($img_attrs, array('sizes' => $sizes)));
                            } else {
                                // Fallback to plain <img>
                                printf(
                                    '<img src="%s" class="%s" alt="%s" loading="%s" decoding="%s" fetchpriority="%s" />',
                                    esc_url($item['img']),
                                    esc_attr($img_attrs['class']),
                                    esc_attr($img_attrs['alt']),
                                    esc_attr($img_attrs['loading']),
                                    esc_attr($img_attrs['decoding']),
                                    $index === 0 ? 'high' : 'auto'
                                );
                            }
                            ?>
                            <div class="carousel-caption d-none d-md-block"></div>
                        </div>
                    <?php endforeach; ?>
                 </div>
            <?php else : ?>
                <div class="container"><p><?php esc_html_e('Chưa có slider nào được đăng hoặc thiếu ảnh.', 'doan'); ?></p></div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <div id="content" class="site-content">
