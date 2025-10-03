<?php

if (!defined('_S_VERSION')) {
    define('_S_VERSION', '1.0.0');
}

function dulichvietnhat_setup() {
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(
        array(
            'primary' => esc_html__('Primary Menu', 'dulichvietnhat'),
            'footer'  => esc_html__('Footer Menu', 'dulichvietnhat'),
        )
    );

   
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    add_theme_support('customize-selective-refresh-widgets');


    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'dulichvietnhat_setup');

add_action('after_setup_theme', function(){
    add_theme_support('title-tag');
});

add_filter('document_title_separator', function($sep){ return '|'; });
add_filter('document_title_parts', function($parts){
    if (is_front_page() || is_home()) {
        $parts['title'] = get_bloginfo('name');
        $parts['tagline'] = get_bloginfo('description');
    }
    return $parts;
});

add_action('wp_head', function(){
    if (!function_exists('has_site_icon') || !has_site_icon()) {
        $base = get_stylesheet_directory_uri() . '/icon';
        echo '<link rel="icon" href="' . esc_url($base . '/favicon.ico') . '" sizes="any">';
        echo '<link rel="icon" type="image/png" href="' . esc_url($base . '/favicon-32.png') . '" sizes="32x32">';
        echo '<link rel="icon" type="image/png" href="' . esc_url($base . '/favicon-16.png') . '" sizes="16x16">';
        echo '<link rel="apple-touch-icon" href="' . esc_url($base . '/apple-touch-icon.png') . '" sizes="180x180">';
        $manifest = $base . '/site.webmanifest';
        echo '<link rel="manifest" href="' . esc_url($manifest) . '">';
        echo '<meta name="theme-color" content="#ffffff">';
    }
}, 1);

add_action('wp_head', function(){
    // Override CDN-delivered @font-face to add font-display for faster text/icon rendering
    // Only output once on frontend
    if (is_admin()) return;
    ?>
    <style id="font-display-overrides">
    /* Font Awesome 6 Free/Brands from cdnjs (match families commonly used by FA CSS) */
    @font-face {
      font-family: "Font Awesome 6 Free";
      src: url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/webfonts/fa-solid-900.woff2") format("woff2");
      font-weight: 900;
      font-style: normal;
      font-display: swap;
    }
    @font-face {
      font-family: "Font Awesome 6 Brands";
      src: url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/webfonts/fa-brands-400.woff2") format("woff2");
      font-weight: 400;
      font-style: normal;
      font-display: swap;
    }
    /* If Regular set is used in the theme, include it as well */
    @font-face {
      font-family: "Font Awesome 6 Free";
      src: url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/webfonts/fa-regular-400.woff2") format("woff2");
      font-weight: 400;
      font-style: normal;
      font-display: optional; /* Icons are decorative; optional avoids layout shift */
    }

    /* Slick carousel font from cdnjs */
    @font-face {
      font-family: "slick";
      src: url("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/fonts/slick.woff") format("woff");
      font-weight: normal;
      font-style: normal;
      font-display: swap;
    }
    </style>
    <?php
}, 20);

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function dulichvietnhat_content_width() {
    $GLOBALS['content_width'] = apply_filters('dulichvietnhat_content_width', 1200);
}
add_action('after_setup_theme', 'dulichvietnhat_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dulichvietnhat_widgets_init() {
    // Main Sidebar
    register_sidebar(
        array(
            'name'          => esc_html__('Sidebar', 'dulichvietnhat'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'dulichvietnhat'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    // Footer Widget Areas
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(
            array(
                'name'          => sprintf(esc_html__('Footer Widget Area %d', 'dulichvietnhat'), $i),
                'id'            => 'footer-' . $i,
                'description'   => esc_html__('Add footer widgets here.', 'dulichvietnhat'),
                'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );
    }
}
add_action('widgets_init', 'dulichvietnhat_widgets_init');

add_action('acf/init', function () {
    // Thêm "Taxonomy Term" vào danh sách Location Rules
    add_filter('acf/location/rule_types', function ($choices) {
        $choices['Taxonomy']['taxonomy_term'] = 'Taxonomy Term';
        return $choices;
    });

    // Liệt kê các taxonomy có trong site
    add_filter('acf/location/rule_values/taxonomy_term', function ($choices) {
        $taxonomies = get_taxonomies([], 'objects');
        foreach ($taxonomies as $taxonomy) {
            $choices[$taxonomy->name] = $taxonomy->label;
        }
        return $choices;
    });

    add_filter('acf/location/rule_match/taxonomy_term', function ($match, $rule, $options) {
        if (isset($options['taxonomy']) && $options['taxonomy'] == $rule['value']) {
            $match = true;
        }
        return $match;
    }, 10, 3);
});

function register_consultation_post_type() {
    $labels = array(
        'name'               => __('Đăng ký tư vấn', 'doan'),
        'singular_name'      => __('Đăng ký tư vấn', 'doan'),
        'menu_name'          => __('Đăng ký tư vấn', 'doan'),
        'name_admin_bar'     => __('Đăng ký tư vấn', 'doan'),
        'add_new'            => __('Thêm mới', 'doan'),
        'add_new_item'       => __('Thêm đăng ký mới', 'doan'),
        'new_item'           => __('Đăng ký mới', 'doan'),
        'edit_item'          => __('Chỉnh sửa đăng ký', 'doan'),
        'view_item'          => __('Xem đăng ký', 'doan'),
        'all_items'          => __('Tất cả đăng ký', 'doan'),
        'search_items'       => __('Tìm kiếm đăng ký', 'doan'),
        'not_found'          => __('Không tìm thấy', 'doan'),
        'not_found_in_trash' => __('Không có trong thùng rác', 'doan'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-email-alt',
        'supports'           => array('title'),
    );

    register_post_type('consultation', $args);
}
add_action('init', 'register_consultation_post_type');

function dulichvietnhat_scripts() {
    $style_path = get_stylesheet_directory() . '/style.css';
    $style_version = file_exists($style_path) ? filemtime($style_path) : _S_VERSION;
    wp_enqueue_style('dulichvietnhat-style', get_stylesheet_uri(), array(), $style_version);
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', array(), '6.5.0');

    // Prefer minified Bootstrap if available
    $bootstrap_min = get_template_directory() . '/assets/css/bootstrap.min.css';
    $bootstrap_uri = file_exists($bootstrap_min)
        ? get_template_directory_uri() . '/assets/css/bootstrap.min.css'
        : get_template_directory_uri() . '/assets/css/bootstrap.css';
    $bootstrap_ver_file = file_exists($bootstrap_min) ? $bootstrap_min : (get_template_directory() . '/assets/css/bootstrap.css');
    $bootstrap_ver = file_exists($bootstrap_ver_file) ? filemtime($bootstrap_ver_file) : _S_VERSION;
    wp_enqueue_style('bootstrap-css', $bootstrap_uri, array(), $bootstrap_ver);

    // Google Fonts (display=swap) via WP enqueue to allow resource hints
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@400;500;600;700;800;900&display=swap', array(), null);

    // Helper: prefer .min.css if available
    $resolve_min = function($rel) {
        $base_path = get_stylesheet_directory() . $rel;
        $base_uri  = get_stylesheet_directory_uri() . $rel;
        // Try .min.css variant next to the file
        if (substr($rel, -4) === '.css') {
            $min_rel  = substr($rel, 0, -4) . '.min.css';
            $min_path = get_stylesheet_directory() . $min_rel;
            if (file_exists($min_path)) {
                return array($min_path, get_stylesheet_directory_uri() . $min_rel);
            }
        }
        return array($base_path, $base_uri);
    };

    $assets = array(
        'header-css'              => '/assets/css/header.css',
        'banner-css'              => '/assets/css/banner.css',
        'featured-posts-css'      => '/assets/css/featured-posts.css',
        'featured-tours-css'      => '/assets/css/featured-tours.css',
        'placeholder-images-css'  => '/assets/css/placeholder-images.css',
        'tour-pages-css'          =>  '/assets/css/tour-pages.css',
        'main-css'                => '/main.css'
    );
    foreach ($assets as $handle => $rel) {
        list($path, $uri) = $resolve_min($rel);
        if (file_exists($path)) {
            $ver = filemtime($path);
            wp_enqueue_style($handle, $uri, array('dulichvietnhat-style','fontawesome','bootstrap-css'), $ver);
        }
    }

    
    $icon_fix = get_stylesheet_directory() . '/assets/css/icon-fix.css';
    if (file_exists($icon_fix)) {
        wp_enqueue_style('icon-fix', get_stylesheet_directory_uri() . '/assets/css/icon-fix.css', array('fontawesome'), filemtime($icon_fix));
    }
    wp_enqueue_style('slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', array(), '1.8.1');
    wp_enqueue_style('slick-theme-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css', array('slick-css'), '1.8.1');
    wp_enqueue_script('slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array('jquery'), '1.8.1', true);

    $news_slider_init = <<<'JS'
(function($){
  $(function(){
    var $el = $('.news-grid.news-slider');
    if(!$el.length) return;
    if($el.hasClass('slick-initialized')) return;
    $el.slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      infinite: false,
      speed: 500,
      cssEase: 'cubic-bezier(.22,.61,.36,1)',
      autoplay: true,
      autoplaySpeed: 3500,
      pauseOnHover: true,
      swipeToSlide: true,
      touchThreshold: 12,
      adaptiveHeight: false,
      arrows: true,
      dots: true,
      prevArrow: '<button type="button" class="slick-prev" aria-label="Previous" title="Previous">\n         <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">\n           <path d="M15 18L9 12L15 6" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n         </svg>\n       </button>',
      nextArrow: '<button type="button" class="slick-next" aria-label="Next" title="Next">\n         <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">\n           <path d="M9 6L15 12L9 18" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n         </svg>\n       </button>',
      responsive: [
        { breakpoint: 1280, settings: { slidesToShow: 3 } },
        { breakpoint: 992,  settings: { slidesToShow: 2 } },
        { breakpoint: 576,  settings: { slidesToShow: 1 } }
      ]
    });
  });
})(jQuery);
JS;
     wp_add_inline_script('slick-js', $news_slider_init);

    wp_enqueue_script('jquery');
    // Helper: prefer .min.js if available for theme JS assets
    $resolve_min_js = function($rel_js) {
        $base_path = get_template_directory() . $rel_js;
        $base_uri  = get_template_directory_uri() . $rel_js;
        if (substr($rel_js, -3) === '.js') {
            $min_rel  = substr($rel_js, 0, -3) . '.min.js';
            $min_path = get_template_directory() . $min_rel;
            if (file_exists($min_path)) {
                return array($min_path, get_template_directory_uri() . $min_rel);
            }
        }
        return array($base_path, $base_uri);
    };

    // Main JS (min if available)
    list($path_main_js, $uri_main_js) = $resolve_min_js('/assets/js/main.js');
    wp_enqueue_script('dulichvietnhat-main-js', $uri_main_js, array('jquery'), file_exists($path_main_js) ? filemtime($path_main_js) : _S_VERSION, true);

    // Header JS (site-wide UI)
    list($path_header_js, $uri_header_js) = $resolve_min_js('/assets/js/header.js');
    wp_enqueue_script('header-js', $uri_header_js, array('jquery'), file_exists($path_header_js) ? filemtime($path_header_js) : _S_VERSION, true);

    // Banner JS: only where banner/slider renders (same condition as banner CSS)
    $render_banner = (!is_single() && !is_search() && !is_page('lich-khoi-hanh') && !is_page('dang-ky-tu-van')
        && !is_page('hinh-anh-thuc-te') && !is_page('kham-pha-nhat-ban')
        && !is_page('tour-hue-mua-thu-2025') && !is_page('tour-7-ngay-6-dem')
        && !is_page('tour-6-ngay-5-dem') && !is_page('tour-5-ngay-4-dem')
        && !is_page_template('page-dang-ky-tu-van.php') && !is_page_template('page-tai-khoan.php')
        && !is_page('tai-khoan'));
    if ($render_banner) {
        list($path_banner_js, $uri_banner_js) = $resolve_min_js('/assets/js/banner.js');
        wp_enqueue_script('banner-js', $uri_banner_js, array('jquery'), file_exists($path_banner_js) ? filemtime($path_banner_js) : _S_VERSION, true);
    }

    // Bootstrap bundle (with Popper): only where carousel/banner is used
    if ($render_banner) {
        // Prefer minified bundle if present
        list($path_boot_js, $uri_boot_js) = $resolve_min_js('/assets/js/bootstrap.bundle.js');
        wp_enqueue_script('bootstrap-js', $uri_boot_js, array('jquery'), file_exists($path_boot_js) ? filemtime($path_boot_js) : _S_VERSION, true);
    }

    // Custom JS (min if available)
    list($path_custom_js, $uri_custom_js) = $resolve_min_js('/assets/js/custom.js');
    wp_enqueue_script('dulichvietnhat-custom-js', $uri_custom_js, array('jquery'), file_exists($path_custom_js) ? filemtime($path_custom_js) : _S_VERSION, true);

    wp_localize_script('dulichvietnhat-custom-js', 'dulichvietnhatSettings', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'homeUrl' => home_url(),
        'isMobile' => wp_is_mobile(),
    ));

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    $main_path = get_stylesheet_directory() . '/main.css';
    if (file_exists($main_path)) {
        $main_version = filemtime($main_path);
        wp_enqueue_style('dulichvietnhat-main', get_stylesheet_directory_uri() . '/main.css', array('dulichvietnhat-style','header-css','banner-css','featured-posts-css','featured-tours-css'), $main_version);
        $overlay_fix_css = '.posts-grid .post-category,.post-card .post-category,.tour-card .post-category,.card .post-category,.category-tag,.post-badge,.image-badge{display:none!important}.post-thumbnail .overlay,.post-thumbnail::before,.post-thumbnail::after,.post-thumbnail .post-category,.post-image::before,.post-image::after,.tour-image::before,.tour-image::after,.destination-image::before,.destination-image::after,.entry-media::before,.entry-media::after{content:none!important;display:none!important;background:transparent!important;opacity:0!important}.post-thumbnail img,.post-image img,.tour-image img,.destination-image img,.entry-media img{filter:none!important;opacity:1!important}.custom-logo{max-height:48px;width:auto;height:auto}.site-header .logo-text{margin-left:10px;display:inline-block;vertical-align:middle}';
        wp_add_inline_style('dulichvietnhat-main', $overlay_fix_css);
    }

    // Enqueue header-override.css LAST so it can override previous styles (depends on main if present)
    $header_override_rel = '/assets/css/header-override.css';
    list($header_override_path_eff, $header_override_uri_eff) = $resolve_min($header_override_rel);
    if (file_exists($header_override_path_eff)) {
        $deps = array('dulichvietnhat-style','fontawesome','bootstrap-css');
        if (wp_style_is('dulichvietnhat-main', 'registered') || wp_style_is('dulichvietnhat-main', 'enqueued')) {
            $deps[] = 'dulichvietnhat-main';
        } else {
            // still ensure it comes after header.css
            $deps[] = 'header-css';
        }
        $ver = file_exists($header_override_path_eff) ? filemtime($header_override_path_eff) : filemtime(get_stylesheet_directory() . '/assets/css/header-override.css');
        $uri = file_exists($header_override_path_eff) ? $header_override_uri_eff : (get_stylesheet_directory_uri() . '/assets/css/header-override.css');
        wp_enqueue_style('header-override-css', $uri, $deps, $ver);
    }
    
    // Enqueue tour pages stylesheet last so it overrides where needed
    $tour_pages_rel = '/assets/css/tour-pages.css';
    list($tour_pages_path_eff, $tour_pages_uri_eff) = $resolve_min($tour_pages_rel);
    if (file_exists($tour_pages_path_eff)) {
        $deps = array('dulichvietnhat-style','fontawesome','bootstrap-css');
        if (wp_style_is('dulichvietnhat-main', 'registered') || wp_style_is('dulichvietnhat-main', 'enqueued')) {
            $deps[] = 'dulichvietnhat-main';
        }
        if (wp_style_is('header-override-css', 'registered') || wp_style_is('header-override-css', 'enqueued')) {
            $deps[] = 'header-override-css';
        }
        $ver = file_exists($tour_pages_path_eff) ? filemtime($tour_pages_path_eff) : filemtime(get_stylesheet_directory() . '/assets/css/tour-pages.css');
        $uri = file_exists($tour_pages_path_eff) ? $tour_pages_uri_eff : (get_stylesheet_directory_uri() . '/assets/css/tour-pages.css');
        wp_enqueue_style('tour-pages-css', $uri, $deps, $ver);
    }
}
add_action('wp_enqueue_scripts', 'dulichvietnhat_scripts', 100);

// Move jQuery (and core/migrate) to footer to avoid render-blocking in head
add_action('wp_enqueue_scripts', function(){
    if (is_admin()) return;
    wp_script_add_data('jquery', 'group', 1);
    wp_script_add_data('jquery-core', 'group', 1);
    wp_script_add_data('jquery-migrate', 'group', 1);
}, 1);

// Optionally remove jquery-migrate on frontend to reduce JS weight (re-enable if a plugin needs it)
add_action('wp_default_scripts', function($scripts){
    if (is_admin()) return;
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array('jquery-core');
    }
    if (!empty($scripts->registered['jquery-migrate'])) {
        unset($scripts->registered['jquery-migrate']);
    }
});

// Defer non-critical scripts to improve initial render (respect order and skip jQuery)
add_filter('script_loader_tag', function($tag, $handle){
    $skip = array('jquery','jquery-core','jquery-migrate');
    if (in_array($handle, $skip, true)) {
        return $tag;
    }
    // Only add defer to front-end enqueued scripts
    if (is_admin()) return $tag;
    if (false === strpos($tag, ' defer')) {
        $tag = str_replace('<script ', '<script defer ', $tag);
    }
    return $tag;
}, 10, 2);

// Load non-critical styles asynchronously using preload+onload swap
add_filter('style_loader_tag', function($html, $handle, $href, $media){
    if (is_admin()) return $html;
    // Critical CSS keeps blocking: base theme style, header layout, and Bootstrap
    $critical = array('dulichvietnhat-style', 'header-css', 'bootstrap-css');
    if (in_array($handle, $critical, true)) {
        return $html;
    }
    // Only transform if it is a stylesheet link
    if (false === strpos($html, "rel='stylesheet'")) {
        return $html;
    }
    // Preload then swap
    $preload  = "<link rel='preload' as='style' href='" . esc_url($href) . "' onload=\"this.onload=null;this.rel='stylesheet'\" media='all' />";
    $noscript = "<noscript>" . $html . "</noscript>";
    return $preload . $noscript;
}, 10, 4);

function dulichvietnhat_resource_hints($urls, $relation_type) {
    // Always provide early connection hints for critical third-party origins
    $preconnect_hosts = array(
        array('href' => 'https://fonts.googleapis.com'),
        array('href' => 'https://fonts.gstatic.com', 'crossorigin'),
        array('href' => 'https://cdnjs.cloudflare.com'),
    );

    if ('preconnect' === $relation_type) {
        foreach ($preconnect_hosts as $h) { $urls[] = $h; }
    }

    if ('dns-prefetch' === $relation_type) {
        // Also add DNS prefetch for same hosts
        $dns_hosts = array(
            '//fonts.googleapis.com',
            '//fonts.gstatic.com',
            '//cdnjs.cloudflare.com',
        );
        foreach ($dns_hosts as $h) { $urls[] = $h; }
    }
    return $urls;
}
add_filter('wp_resource_hints', 'dulichvietnhat_resource_hints', 10, 2);

function handle_contact_form_submission() {
    if (isset($_POST['contact_form_nonce']) && wp_verify_nonce($_POST['contact_form_nonce'], 'contact_form_action')) {
        // Collect and sanitize
        $name = isset($_POST['contact_name']) ? sanitize_text_field($_POST['contact_name']) : '';
        $phone = isset($_POST['contact_phone']) ? sanitize_text_field($_POST['contact_phone']) : '';
        $email = isset($_POST['contact_email']) ? sanitize_email($_POST['contact_email']) : '';
        $tour = isset($_POST['contact_tour']) ? sanitize_text_field($_POST['contact_tour']) : '';
        $message = isset($_POST['contact_message']) ? sanitize_textarea_field($_POST['contact_message']) : '';

        // Save to admin as a custom post type entry
        $post_id = wp_insert_post(array(
            'post_type'   => 'consultation',
            'post_title'  => $name ? ( 'Tư vấn: ' . $name . ' - ' . current_time('d/m/Y H:i') ) : ( 'Tư vấn - ' . current_time('d/m/Y H:i') ),
            'post_status' => 'private',
            'meta_input'  => array(
                '_consultation_name'    => $name,
                '_consultation_phone'   => $phone,
                '_consultation_email'   => $email,
                '_consultation_tour'    => $tour,
                '_consultation_message' => $message,
                '_consultation_time'    => current_time('mysql'),
            ),
        ));

        $subject = 'Yêu cầu tư vấn tour từ ' . ($name ? $name : 'Khách hàng');
        $body = "Thông tin khách hàng:\n\n";
        $body .= "Họ và tên: " . $name . "\n";
        $body .= "Số điện thoại: " . $phone . "\n";
        $body .= "Email: " . $email . "\n";
        $body .= "Tour quan tâm: " . $tour . "\n";
        $body .= "Tin nhắn: " . $message . "\n\n";
        $body .= "Thời gian: " . current_time('d/m/Y H:i:s');

        $admin_email = get_option('admin_email');
        $headers = array('Content-Type: text/plain; charset=UTF-8');

       
        $thank_url = '';
        if (!empty($_POST['redirect_to'])) {
            $thank_url = esc_url_raw($_POST['redirect_to']);
        }
        if (!$thank_url) {
            $thank_page = get_page_by_path('dang-ky-tu-van');
            // If Polylang is active, redirect to the translated page in current language
            if (function_exists('pll_current_language')) {
                $lang = pll_current_language('slug');
                if ($thank_page && function_exists('pll_get_post')) {
                    $translated_id = pll_get_post($thank_page->ID, $lang);
                    if ($translated_id) {
                        $thank_url = get_permalink($translated_id);
                    }
                }
                if (!$thank_url && function_exists('pll_home_url')) {
                    $thank_url = trailingslashit(pll_home_url($lang)) . 'dang-ky-tu-van/';
                }
            }
            // Fallback if Polylang not active or translation missing
            if (!$thank_url) {
                $thank_url  = $thank_page ? get_permalink($thank_page) : home_url('/dang-ky-tu-van');
            }
        }

        
        if ($post_id && !is_wp_error($post_id)) {
            if ($admin_email) { wp_mail($admin_email, $subject, $body, $headers); }
          
            $redir = add_query_arg(array(
                'consult' => 'success',
                'tour'    => rawurlencode($tour),
            ), $thank_url);
            wp_safe_redirect($redir);
            exit;
        } else {
            $redir = add_query_arg(array('consult' => 'error'), $thank_url);
            wp_safe_redirect($redir);
            exit;
        }
    }
}
add_action('init', 'handle_contact_form_submission');

/**
 * Remove Website (URL) field from comment form
 */
function dulichvietnhat_remove_comment_url_field($fields) {
    if (isset($fields['url'])) {
        unset($fields['url']);
    }
    return $fields;
}
add_filter('comment_form_default_fields', 'dulichvietnhat_remove_comment_url_field');

/**
 * Optional: adjust comment form defaults (shorten notes)
 */
function dulichvietnhat_comment_form_defaults($defaults) {
    $defaults['comment_notes_before'] = '<p class="comment-notes">Email của bạn sẽ không được hiển thị công khai. Các trường bắt buộc được đánh dấu <span class="required">*</span></p>';
    return $defaults;
}
add_filter('comment_form_defaults', 'dulichvietnhat_comment_form_defaults');

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
    require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Register Custom Post Type for Tours
 */
function create_tour_post_type() {
    register_post_type('tour',
        array(
            'labels' => array(
                'name' => __('Tours', 'dulichvietnhat'),
                'singular_name' => __('Tour', 'dulichvietnhat'),
                'add_new' => __('Add New', 'dulichvietnhat'),
                'add_new_item' => __('Add New Tour', 'dulichvietnhat'),
                'edit_item' => __('Edit Tour', 'dulichvietnhat'),
                'new_item' => __('New Tour', 'dulichvietnhat'),
                'view_item' => __('View Tour', 'dulichvietnhat'),
                'search_items' => __('Search Tours', 'dulichvietnhat'),
                'not_found' => __('No tours found', 'dulichvietnhat'),
                'not_found_in_trash' => __('No tours found in Trash', 'dulichvietnhat')
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'tours'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            'menu_icon' => 'dashicons-palmtree',
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'create_tour_post_type');

/**
 * Register Custom Taxonomies
 */
function create_tour_taxonomies() {
    // Destination Taxonomy
    register_taxonomy(
        'destination',
        'tour',
        array(
            'labels' => array(
                'name' => _x('Destinations', 'taxonomy general name', 'dulichvietnhat'),
                'singular_name' => _x('Destination', 'taxonomy singular name', 'dulichvietnhat'),
                'search_items' => __('Search Destinations', 'dulichvietnhat'),
                'all_items' => __('All Destinations', 'dulichvietnhat'),
                'edit_item' => __('Edit Destination', 'dulichvietnhat'),
                'update_item' => __('Update Destination', 'dulichvietnhat'),
                'add_new_item' => __('Add New Destination', 'dulichvietnhat'),
                'new_item_name' => __('New Destination Name', 'dulichvietnhat'),
                'menu_name' => __('Destinations', 'dulichvietnhat'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => array('slug' => 'destination'),
        )
    );

    register_taxonomy(
        'tour_type',
        'tour',
        array(
            'labels' => array(
                'name' => _x('Tour Types', 'taxonomy general name', 'dulichvietnhat'),
                'singular_name' => _x('Tour Type', 'taxonomy singular name', 'dulichvietnhat'),
                'search_items' => __('Search Tour Types', 'dulichvietnhat'),
                'all_items' => __('All Tour Types', 'dulichvietnhat'),
                'edit_item' => __('Edit Tour Type', 'dulichvietnhat'),
                'update_item' => __('Update Tour Type', 'dulichvietnhat'),
                'add_new_item' => __('Add New Tour Type', 'dulichvietnhat'),
                'new_item_name' => __('New Tour Type Name', 'dulichvietnhat'),
                'menu_name' => __('Tour Types', 'dulichvietnhat'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => array('slug' => 'tour-type'),
        )
    );
}
add_action('init', 'create_tour_taxonomies', 0);

function dulichvietnhat_add_image_sizes() {
    add_image_size('tour-thumbnail', 350, 250, true);
    add_image_size('destination-thumbnail', 400, 300, true);
    add_image_size('post-thumbnail-large', 800, 500, true);

    // Slider/Hero responsive sizes (not hard-cropped to preserve aspect)
    add_image_size('slider-hero', 1600, 0, false);   // up to 1600px wide
    add_image_size('slider-large', 1200, 0, false);  // up to 1200px wide
    add_image_size('slider-medium', 992, 0, false);  // up to 992px wide
    add_image_size('slider-small', 768, 0, false);   // up to 768px wide
}
add_action('after_setup_theme', 'dulichvietnhat_add_image_sizes');

// Prefer WebP for generated sizes when supported (WP 6.1+)
add_filter('image_editor_output_format', function($formats){
    $formats['image/jpeg'] = 'image/webp';
    $formats['image/png']  = 'image/webp';
    return $formats;
});

// Reduce JPEG quality slightly for better weight without noticeable loss
add_filter('jpeg_quality', function(){ return 82; });
add_filter('wp_editor_set_quality', function(){ return 82; });

// Cap very large originals to avoid gigantic uploads (still keeps a large source)
add_filter('big_image_size_threshold', function($threshold){
    // 1920px is typically enough for hero backgrounds
    return 1920;
}, 10, 1);

// Provide a sane default sizes attribute for content images without explicit sizes
add_filter('wp_calculate_image_sizes', function($sizes, $size, $image_src, $image_meta, $attachment_id){
    // If not in content context or already has sizes, keep default
    if (!is_string($sizes) || empty($sizes)) {
        // Full width on mobile, clamp to container widths on larger screens
        $sizes = '(max-width: 576px) 100vw, (max-width: 992px) 92vw, (max-width: 1200px) 85vw, 1200px';
    }
    return $sizes;
}, 10, 5);

function dulichvietnhat_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'dulichvietnhat_excerpt_length', 999);

function dulichvietnhat_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'dulichvietnhat_excerpt_more');

// Ensure external CPTs like 'slider' support thumbnails (featured images)
add_action('init', function(){
    if (post_type_exists('slider')) {
        add_post_type_support('slider', array('thumbnail', 'title', 'editor')); 
    }
}, 20);

function add_tour_rewrite_rules() {
    add_rewrite_rule(
        '^tour-7-ngay-6-dem/?$',
        'index.php?pagename=tour-7-ngay-6-dem',
        'top'
    );

    add_rewrite_rule(
        '^tour-hue-mua-thu-2025/?$',
        'index.php?pagename=tour-hue-mua-thu-2025',
        'top'
    );

    add_rewrite_rule(
        '^tour-6-ngay-5-dem/?$',
        'index.php?pagename=tour-6-ngay-5-dem',
        'top'
    );

    add_rewrite_rule(
        '^tour-5-ngay-4-dem/?$',
        'index.php?pagename=tour-5-ngay-4-dem',
        'top'
    );
}
add_action('init', 'add_tour_rewrite_rules');
function flush_tour_rewrite_rules() {
    add_tour_rewrite_rules();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_tour_rewrite_rules');

function force_flush_rewrite_rules() {
    if (get_option('tour_rewrite_rules_flushed') !== 'yes') {
        flush_rewrite_rules();
        update_option('tour_rewrite_rules_flushed', 'yes');
    }
}
add_action('admin_init', 'force_flush_rewrite_rules');

if (!function_exists('dln_ensure_consultation_page')) {
    function dln_ensure_consultation_page(){
        $slug = 'dang-ky-tu-van';
        $tpl  = 'page-dang-ky-tu-van.php';

        // Find by slug first
        $page = get_page_by_path($slug);

        // If not found, also try to find by exact title
        if (!$page) {
            $q = new WP_Query(array(
                'post_type' => 'page',
                'title'     => 'Đăng ký tư vấn',
                'post_status' => array('publish','draft','pending','private'),
                'posts_per_page' => 1,
            ));
            if ($q->have_posts()) { $page = $q->posts[0]; }
            wp_reset_postdata();
        }

        if (!$page) {
            // Create page
            $page_id = wp_insert_post(array(
                'post_type'   => 'page',
                'post_title'  => 'Đăng ký tư vấn',
                'post_name'   => $slug,
                'post_status' => 'publish',
                'meta_input'  => array(
                    '_wp_page_template' => $tpl,
                ),
            ));
            if (!is_wp_error($page_id)) {
                update_option('consultation_page_id', (int)$page_id);
                if (get_option('consultation_rewrite_flushed') !== 'yes') {
                    flush_rewrite_rules();
                    update_option('consultation_rewrite_flushed', 'yes');
                }
            }
        } else {
            // Ensure correct slug and template
            $needs_update = false;
            $arr = array('ID' => (int)$page->ID);
            if ($page->post_name !== $slug) { $arr['post_name'] = $slug; $needs_update = true; }
            $current_tpl = get_post_meta($page->ID, '_wp_page_template', true);
            if ($current_tpl !== $tpl) { update_post_meta($page->ID, '_wp_page_template', $tpl); }
            if ($page->post_status !== 'publish') { $arr['post_status'] = 'publish'; $needs_update = true; }
            if ($needs_update) { wp_update_post($arr); }
        }
    }
}

// Run in admin always
add_action('admin_init', 'dln_ensure_consultation_page');
// Also run on front-end for logged-in users to repair without entering admin
add_action('init', function(){ if (!is_admin() && is_user_logged_in()) { dln_ensure_consultation_page(); } }, 20);

function dulichvietnhat_kill_thumbnail_overlays_css() {
    ?>
    <style id="dulichvietnhat-kill-overlays">
    .post-thumbnail::before,.post-thumbnail::after,.post-image::before,.post-image::after,.tour-image::before,.tour-image::after,.destination-image::before,.destination-image::after,.entry-media::before,.entry-media::after{content:none!important;display:none!important;background:transparent!important;opacity:0!important}
    .post-thumbnail .post-category,.post-card .post-category,.tour-card .post-category,.card .post-category,.category-tag,.post-badge,.image-badge{display:none!important}
    .post-thumbnail [class*="overlay"],.post-thumbnail [class*="mask"],.post-thumbnail [class*="shade"],.post-thumbnail [class*="cover"],.post-image [class*="overlay"],.post-image [class*="mask"],.post-image [class*="shade"],.post-image [class*="cover"],.tour-image [class*="overlay"],.destination-image [class*="overlay"],.entry-media [class*="overlay"]{display:none!important;opacity:0!important}
    .post-thumbnail img,.post-image img,.tour-image img,.destination-image img,.entry-media img{filter:none!important;opacity:1!important}
    body.search-results .post-thumbnail::before,body.search-results .post-thumbnail::after,body.search-results .post-thumbnail [class*="overlay"]{display:none!important;opacity:0!important}
    </style>
    <?php
}
add_action('wp_head', 'dulichvietnhat_kill_thumbnail_overlays_css', 999);

function dulichvietnhat_strip_overlays_dom() {
    ?>
    <script>
    (function(){
      function killOverlays(){
        var sel = [
          '.post-thumbnail .overlay', '.post-thumbnail .mask', '.post-thumbnail .shade', '.post-thumbnail .cover',
          '.post-image .overlay', '.post-image .mask', '.post-image .shade', '.post-image .cover',
          '.tour-image .overlay', '.destination-image .overlay', '.entry-media .overlay',
          '.post-thumbnail .post-category', '.post-card .post-category', '.tour-card .post-category',
          '.card .post-category', '.category-tag', '.post-badge', '.image-badge'
        ];
        try { document.querySelectorAll(sel.join(',')).forEach(function(el){ el.style.display='none'; el.removeAttribute('style'); el.remove(); }); } catch(e){}

        var wrappers = document.querySelectorAll('.post-thumbnail, .post-image, .tour-image, .destination-image, .entry-media');
        wrappers.forEach(function(w){
          Array.prototype.slice.call(w.children).forEach(function(ch){
            if (ch.tagName && ch.tagName.toLowerCase() === 'img') return;
            var cs = window.getComputedStyle(ch);
            var isAbs = cs.position === 'absolute' || cs.position === 'fixed';
            var covers = (cs.top === '0px' && cs.left === '0px') || (cs.inset === '0px');
            var hasBg = cs.backgroundColor && cs.backgroundColor !== 'rgba(0, 0, 0, 0)' && cs.backgroundColor !== 'transparent';
            if (isAbs && covers) { ch.style.display = 'none'; }
            if (hasBg) { ch.style.background = 'transparent'; ch.style.opacity = '0'; }
          });
        });
      }
      if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', killOverlays); }
      else { killOverlays(); }
      window.addEventListener('load', function(){ setTimeout(killOverlays, 0); setTimeout(killOverlays, 300); });
    })();
    </script>
    <?php
}
add_action('wp_footer', 'dulichvietnhat_strip_overlays_dom', 9999);

add_action('after_setup_theme', function () {
    $domains = array();
    $theme = wp_get_theme();
    $td = $theme->get('TextDomain');
    if ($td) { $domains[] = $td; }
    $domains[] = 'doan';
    $domains[] = 'dulichvietnhat';
    $domains = array_unique($domains);
    foreach ($domains as $domain) {
        load_theme_textdomain($domain, get_stylesheet_directory() . '/languages');
    }
});

add_action('change_locale', function($locale){
    $domains = array();
    $theme = wp_get_theme();
    $td = $theme->get('TextDomain');
    if ($td) { $domains[] = $td; }
    $domains[] = 'doan';
    $domains[] = 'dulichvietnhat';
    $domains = array_unique($domains);
    foreach ($domains as $domain) {
        unload_textdomain($domain);
        load_theme_textdomain($domain, get_stylesheet_directory() . '/languages');
    }
});

function dln_detect_locale_from_request() {
    $supported = array(
        'vi' => 'vi',
        'en' => 'en_US',
    );
    $locale = '';
    if (isset($_GET['lang'])) {
        $q = strtolower(sanitize_text_field($_GET['lang']));
        if (isset($supported[$q])) { $locale = $supported[$q]; }
    }
    if (!$locale && isset($_COOKIE['site_lang'])) {
        $c = strtolower(sanitize_text_field($_COOKIE['site_lang']));
        if (isset($supported[$c])) { $locale = $supported[$c]; }
    }
    return $locale;
}

if (!function_exists('pll_current_language') && !defined('ICL_SITEPRESS_VERSION')) {
    add_filter('locale', function($current){
        $override = dln_detect_locale_from_request();
        return $override ? $override : $current;
    }, 1);

    add_action('setup_theme', function(){
        $locale = dln_detect_locale_from_request();
        if ($locale) { switch_to_locale($locale); }
    });

    // Only set cookies on the front-end to avoid headers sent warnings during admin/plugin activation
    if (!is_admin() && !wp_doing_ajax()) {
        add_action('init', 'dln_set_lang_cookie', 0);
    }
}

add_filter('body_class', function($classes){
    $locale = determine_locale();
    $classes[] = 'locale-' . sanitize_html_class(strtolower($locale));
    // Add a safe page slug class for easier page-specific styling
    if (is_page()) {
        $post = get_queried_object();
        if ($post && !empty($post->post_name)) {
            $classes[] = 'page-slug-' . sanitize_html_class($post->post_name);
        }
    }
    return $classes;
});

function dln_current_url() {
    $scheme = is_ssl() ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri  = strtok($_SERVER['REQUEST_URI'], '#');
    return esc_url_raw($scheme . '://' . $host . $uri);
}

function dln_lang_switcher($show_labels = true) {
    $langs = array(
        'vi' => 'VI',
        'en' => 'EN',
     
    );
    $current_param = isset($_COOKIE['site_lang']) ? strtolower(sanitize_text_field($_COOKIE['site_lang'])) : '';
    if (!$current_param) {
        $det = strtolower(determine_locale());
        if (strpos($det, 'vi') === 0) $current_param = 'vi';
        elseif (strpos($det, 'ja') === 0) $current_param = 'ja';
        else $current_param = 'en';
    }
    $url = dln_current_url();
    $out = '<div class="lang-switcher" role="navigation" aria-label="Language">';
    foreach ($langs as $code => $label) {
        $u = esc_url(add_query_arg(array('lang' => $code), $url));
        $active = $code === $current_param ? ' active' : '';
        $out .= '<a class="lang-item' . $active . '" href="' . $u . '" rel="nofollow">' . ($show_labels ? esc_html($label) : esc_html($code)) . '</a>';
    }
    $out .= '</div>';
    return $out;
}

// Polylang-aware language switcher: reuse the same VI/EN pill UI
if (!function_exists('dln_poly_switcher')) {
    function dln_poly_switcher($show_labels = true) {
        if (!function_exists('pll_the_languages')) { return ''; }
        $items = pll_the_languages(array('raw' => true));
        if (!is_array($items) || empty($items)) { return ''; }
        $out = '<div class="lang-switcher" role="navigation" aria-label="Language">';
        foreach ($items as $it) {
            $slug = strtolower($it['slug']);
            $label = $show_labels ? strtoupper(substr($slug, 0, 2)) : $slug;
            if ($slug === 'vi') { $label = 'VI'; }
            if ($slug === 'en') { $label = 'EN'; }
            $active = !empty($it['current_lang']) ? ' active' : '';
            $out .= '<a class="lang-item' . $active . '" href="' . esc_url($it['url']) . '">' . esc_html($label) . '</a>';
        }
        $out .= '</div>';
        return $out;
    }
}

function dln_set_lang_cookie() {
    // Extra safety: never attempt to modify headers in admin or after output started
    if (is_admin() || (function_exists('wp_doing_ajax') && wp_doing_ajax())) {
        return;
    }
    if (!headers_sent() && isset($_GET['lang'])) {
        $supported = array('vi','en','ja','fr','zh');
        $q = strtolower(sanitize_text_field($_GET['lang']));
        if (in_array($q, $supported, true)) {
            $path = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
            $domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
            setcookie('site_lang', $q, time()+3600*24*365, $path, $domain);
            $_COOKIE['site_lang'] = $q;
        }
    }
}

// Floating Contact Widget (no plugin)
add_action('wp_footer', function(){
    // Do not show in admin or login pages
    if (is_admin()) return;

    // Get phone from existing theme mod or default
    $raw_phone = get_theme_mod('header_phone', '0123456789');
    $digits    = preg_replace('/[^0-9]/', '', (string)$raw_phone);

    // Allow overrides via theme mods (optional)
    $wa_number   = preg_replace('/[^0-9]/', '', (string) get_theme_mod('contact_whatsapp', $digits));
    $zalo_number = preg_replace('/[^0-9]/', '', (string) get_theme_mod('contact_zalo', $digits));

    // Build links
    $tel_link  = $digits ? 'tel:' . esc_attr($digits) : '';
    $wa_text   = rawurlencode( __( 'Xin chào! Tôi cần được tư vấn tour.', 'doan' ) );
    $wa_link   = $wa_number ? 'https://wa.me/' . esc_attr($wa_number) . '?text=' . $wa_text : '';
    $zalo_link = $zalo_number ? 'https://zalo.me/' . esc_attr($zalo_number) : '';

    ?>
    <div class="floating-contact" aria-label="Liên hệ nhanh">
        <?php if ($tel_link): ?>
        <a class="contact-btn contact-phone" href="<?php echo esc_url($tel_link); ?>" rel="nofollow" aria-label="<?php echo esc_attr__('Gọi điện', 'doan'); ?>">
            <i class="fas fa-phone"></i>
            <span><?php echo esc_html( $raw_phone ? $raw_phone : __( 'Gọi điện', 'doan') ); ?></span>
        </a>
        <?php endif; ?>

        <?php if ($wa_link): ?>
        <a class="contact-btn contact-whatsapp" href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="nofollow noopener" aria-label="<?php echo esc_attr__('Liên hệ tư vấn (WhatsApp)', 'doan'); ?>">
            <i class="fab fa-whatsapp"></i>
            <span><?php echo esc_html__('Liên hệ tư vấn', 'doan'); ?></span>
        </a>
        <?php endif; ?>

        <?php if ($zalo_link): ?>
        <a class="contact-btn contact-zalo" href="<?php echo esc_url($zalo_link); ?>" target="_blank" rel="nofollow noopener" aria-label="<?php echo esc_attr__('Chat Zalo', 'doan'); ?>">
            <i class="fas fa-comment-dots"></i>
            <span><?php echo esc_html__('Chat Zalo', 'doan'); ?></span>
        </a>
        <?php endif; ?>
    </div>
    <?php
});

add_action('customize_register', function($wp_customize){
    $wp_customize->add_section('contact_widget_section', array(
        'title'       => __('Liên hệ nhanh', 'doan'),
        'priority'    => 35,
        'description' => __('Cấu hình số điện thoại, WhatsApp, Zalo cho nút liên hệ nổi.', 'doan'),
    ));
    if (!$wp_customize->get_setting('header_phone')) {
        $wp_customize->add_setting('header_phone', array(
            'default'           => '0123456789',
            'sanitize_callback' => function($v){ return preg_replace('/[^0-9 +()-]/', '', (string)$v); },
        ));
    }
    if (!$wp_customize->get_control('header_phone')) {
        $wp_customize->add_control('header_phone', array(
            'label'       => __('Số điện thoại', 'doan'),
            'section'     => 'contact_widget_section',
            'type'        => 'text',
            'description' => __('Ví dụ: 0123456789', 'doan'),
        ));
    }

    // WhatsApp number
    $wp_customize->add_setting('contact_whatsapp', array(
        'default'           => '',
        'sanitize_callback' => function($v){ return preg_replace('/[^0-9]/', '', (string)$v); },
    ));
    $wp_customize->add_control('contact_whatsapp', array(
        'label'       => __('Số WhatsApp', 'doan'),
        'section'     => 'contact_widget_section',
        'type'        => 'text',
        'description' => __('Chỉ nhập số. Ví dụ: 84901234567', 'doan'),
    ));

    // Zalo number
    $wp_customize->add_setting('contact_zalo', array(
        'default'           => '',
        'sanitize_callback' => function($v){ return preg_replace('/[^0-9]/', '', (string)$v); },
    ));
    $wp_customize->add_control('contact_zalo', array(
        'label'       => __('Số Zalo', 'doan'),
        'section'     => 'contact_widget_section',
        'type'        => 'text',
        'description' => __('Chỉ nhập số. Ví dụ: 0123456789', 'doan'),
    ));
});

add_action('add_meta_boxes', function(){
    $pts = get_post_types(array('public' => true), 'names');
    foreach ($pts as $pt) {
        add_meta_box('dln_gallery_metabox', __('Gallery', 'dulichvietnhat'), 'dln_gallery_metabox_html', $pt, 'normal', 'default');
    }
});

function dln_gallery_metabox_html($post){
    $ids = get_post_meta($post->ID, '_dln_gallery_ids', true);
    $ids = is_array($ids) ? $ids : array();
    wp_nonce_field('dln_gallery_save', 'dln_gallery_nonce');
    echo '<div class="dln-gallery-wrapper">';
    echo '<input type="hidden" class="dln-gallery-ids" name="dln_gallery_ids" value="' . esc_attr(implode(',', array_map('intval', $ids))) . '">';
    echo '<div class="dln-gallery-items" style="display:flex;gap:10px;flex-wrap:wrap;">';
    foreach ($ids as $id) {
        $thumb = wp_get_attachment_image($id, 'thumbnail', false, array('style' => 'border:1px solid #ddd;border-radius:4px;'));
        echo '<div class="dln-gallery-item" data-id="' . intval($id) . '" style="position:relative">' . $thumb . '<button type="button" class="button-link dln-remove" style="position:absolute;top:-6px;right:-6px;background:#fff;border:1px solid #ddd;border-radius:50%;width:22px;height:22px;line-height:20px;text-align:center;">×</button></div>';
    }
    echo '</div>';
    echo '<p><button type="button" class="button button-primary dln-gallery-add">' . esc_html__('Add Images', 'dulichvietnhat') . '</button> ';
    echo '<button type="button" class="button dln-gallery-clear">' . esc_html__('Clear', 'dulichvietnhat') . '</button></p>';
    echo '</div>';
}

add_action('save_post', function($post_id){
    if (!isset($_POST['dln_gallery_nonce']) || !wp_verify_nonce($_POST['dln_gallery_nonce'], 'dln_gallery_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    $raw = isset($_POST['dln_gallery_ids']) ? sanitize_text_field($_POST['dln_gallery_ids']) : '';
    $ids = $raw ? array_filter(array_map('intval', array_map('trim', explode(',', $raw)))) : array();
    update_post_meta($post_id, '_dln_gallery_ids', $ids);
});

add_action('admin_enqueue_scripts', function($hook){
    if ($hook !== 'post.php' && $hook !== 'post-new.php') return;
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen) return;
    wp_enqueue_media();
    wp_enqueue_script('jquery');
    add_action('admin_print_footer_scripts', function(){ ?>
        <script>
        (function($){var f;function u(w){var a=[];w.find('.dln-gallery-item').each(function(){a.push($(this).data('id'));});w.find('.dln-gallery-ids').val(a.join(','));}
        $(document).on('click','.dln-gallery-add',function(e){e.preventDefault();var $wrap=$(this).closest('.dln-gallery-wrapper');if(!f){f=wp.media({title:'<?php echo esc_js(__('Select images','dulichvietnhat')); ?>',multiple:true,library:{type:'image'}});}f.off('select');f.on('select',function(){var s=f.state().get('selection'),w=$wrap;s.each(function(att){att=att.toJSON();var h='<div class="dln-gallery-item" data-id="'+att.id+'" style="position:relative">'+
        '<img src="'+(att.sizes&&att.sizes.thumbnail?att.sizes.thumbnail.url:att.url)+'" style="border:1px solid #ddd;border-radius:4px;" />'+
        '<button type="button" class="button-link dln-remove" style="position:absolute;top:-6px;right:-6px;background:#fff;border:1px solid #ddd;border-radius:50%;width:22px;height:22px;line-height:20px;text-align:center;">×</button>'+
        '</div>';w.find('.dln-gallery-items').append(h);});u($wrap);});f.open();});
        $(document).on('click','.dln-gallery-item .dln-remove',function(){var $wrap=$(this).closest('.dln-gallery-wrapper');$(this).closest('.dln-gallery-item').remove();u($wrap);});
        $(document).on('click','.dln-gallery-clear',function(){var $wrap=$(this).closest('.dln-gallery-wrapper');$wrap.find('.dln-gallery-items').empty();u($wrap);});})(jQuery);
        </script>
        <style>
            #dln-gallery-wrapper .dln-gallery-item img{display:block;width:100px;height:100px;object-fit:cover}
        </style>
    <?php });
});

function dln_get_gallery_image_ids($post_id = null){
    $post_id = $post_id ? $post_id : get_the_ID();
    $ids = get_post_meta($post_id, '_dln_gallery_ids', true);
    return is_array($ids) ? array_map('intval', $ids) : array();
}

function dln_render_gallery($post_id = null){
    $ids = dln_get_gallery_image_ids($post_id);
    if (empty($ids)) return '';
    $html = '<div class="dln-gallery">';
    foreach ($ids as $id){ $html .= '<a href="' . esc_url(wp_get_attachment_url($id)) . '" class="dln-gallery-link">' . wp_get_attachment_image($id, 'large') . '</a>'; }
    $html .= '</div>';
    return $html;
}

add_filter('comments_open', function($open, $post_id){
    if (is_page('dang-ky-tu-van') || is_page_template('page-dang-ky-tu-van.php')) {
        return false;
    }
    return $open;
}, 10, 2);

add_filter('pings_open', function($open){
    if (is_page('dang-ky-tu-van') || is_page_template('page-dang-ky-tu-van.php')) {
        return false;
    }
    return $open;
});

add_action('init', function(){
    if (empty($_POST['acc_action'])) return;

    $redir = isset($_POST['redirect_to']) ? esc_url_raw($_POST['redirect_to']) : home_url('/');
    $redir = $redir ?: home_url('/');

   
    if ($_POST['acc_action'] === 'login') {
        if (!isset($_POST['acc_login_nonce']) || !wp_verify_nonce($_POST['acc_login_nonce'], 'acc_login_action')) {
            wp_safe_redirect(add_query_arg(['acc'=>'error','msg'=>rawurlencode(__('Token không hợp lệ.', 'doan'))], $redir));
            exit;
        }
        $user_login = isset($_POST['acc_user']) ? sanitize_text_field($_POST['acc_user']) : '';
        $pass       = isset($_POST['acc_pass']) ? (string)$_POST['acc_pass'] : '';
        $remember   = !empty($_POST['remember']);
        if (!$user_login || !$pass) {
            wp_safe_redirect(add_query_arg(['acc'=>'error','msg'=>rawurlencode(__('Vui lòng nhập đầy đủ thông tin.', 'doan'))], $redir));
            exit;
        }
     
        if (is_email($user_login)) {
            $u = get_user_by('email', $user_login);
            if ($u) { $user_login = $u->user_login; }
        }
        $signon = wp_signon(['user_login'=>$user_login,'user_password'=>$pass,'remember'=>$remember], is_ssl());
        if (is_wp_error($signon)) {
            wp_safe_redirect(add_query_arg(['acc'=>'error','msg'=>rawurlencode($signon->get_error_message())], $redir));
            exit;
        }
        wp_safe_redirect(add_query_arg(['acc'=>'login_ok'], $redir));
        exit;
    }

    if ($_POST['acc_action'] === 'register') {
        if (!isset($_POST['acc_register_nonce']) || !wp_verify_nonce($_POST['acc_register_nonce'], 'acc_register_action')) {
            wp_safe_redirect(add_query_arg(['acc'=>'error','msg'=>rawurlencode(__('Token không hợp lệ.', 'doan'))], $redir));
            exit;
        }
        $display   = isset($_POST['acc_name']) ? sanitize_text_field($_POST['acc_name']) : '';
        $user_login= isset($_POST['acc_user']) ? sanitize_user($_POST['acc_user'], true) : '';
        $email     = isset($_POST['acc_email']) ? sanitize_email($_POST['acc_email']) : '';
        $pass      = isset($_POST['acc_pass']) ? (string)$_POST['acc_pass'] : '';
        $pass2     = isset($_POST['acc_pass2']) ? (string)$_POST['acc_pass2'] : '';

        if (!$user_login || !$email || !$pass || !$pass2) {
            wp_safe_redirect(add_query_arg(['acc'=>'error','msg'=>rawurlencode(__('Vui lòng nhập đầy đủ thông tin.', 'doan'))], $redir));
            exit;
        }
        if ($pass !== $pass2) {
            wp_safe_redirect(add_query_arg(['acc'=>'error','msg'=>rawurlencode(__('Mật khẩu xác nhận không khớp.', 'doan'))], $redir));
            exit;
        }
        if (username_exists($user_login) || email_exists($email)) {
            wp_safe_redirect(add_query_arg(['acc'=>'error','msg'=>rawurlencode(__('Tên đăng nhập hoặc email đã tồn tại.', 'doan'))], $redir));
            exit;
        }
        $uid = wp_create_user($user_login, $pass, $email);
        if (is_wp_error($uid)) {
            wp_safe_redirect(add_query_arg(['acc'=>'error','msg'=>rawurlencode($uid->get_error_message())], $redir));
            exit;
        }
        if ($display) { wp_update_user(['ID'=>$uid,'display_name'=>$display]); }
      
        wp_set_current_user($uid);
        wp_set_auth_cookie($uid);
        wp_safe_redirect(add_query_arg(['acc'=>'registered'], $redir));
        exit;
    }
});

add_filter('comments_open', function($open){
    if (is_page_template('page-dang-ky-tu-van.php') || is_page_template('page-tai-khoan.php')) { return false; }
    return $open;
});
add_filter('pings_open', function($open){
    if (is_page_template('page-dang-ky-tu-van.php') || is_page_template('page-tai-khoan.php')) { return false; }
    return $open;
});

add_action('init', function(){
    // Disable WordPress emojis (frontend, backend, RSS, emails)
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');

    // Feeds, emails, comments
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // TinyMCE
    add_filter('tiny_mce_plugins', function($plugins){
        if (is_array($plugins)) {
            return array_diff($plugins, array('wpemoji'));
        }
        return $plugins;
    });

    // SVG emoji URL
    add_filter('emoji_svg_url', '__return_false');
}, 1);

/**
 * Custom Walker for Mobile Menu with submenu toggle buttons
 */
class Mobile_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"mobile-sub-menu\">\n";
    }
    
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        
        // Add has-sub class if item has children
        if ( in_array('menu-item-has-children', $classes) ) {
            $classes[] = 'has-sub';
        }
        
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        
        $output .= $indent . '<li' . $class_names .'>';
        
        $atts = array();
        $atts['href'] = ! empty( $item->url ) ? $item->url : '';
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        // Check if consultation button
        $is_consultation = ( strpos($item->url, 'dang-ky-tu-van') !== false );
        $link_class = $is_consultation ? ' class="mobile-consultation-btn"' : '';
        
        $item_output = $args->before;
        $item_output .= '<a'. $attributes . $link_class .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        
        // Add toggle button for items with children
        if ( in_array('menu-item-has-children', $classes) ) {
            $item_output .= '<button class="mobile-sub-toggle" aria-label="Mở danh mục" aria-expanded="false"><i class="fas fa-chevron-down"></i></button>';
        }
        
        $item_output .= $args->after;
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}