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

// Set default custom logo
function dulichvietnhat_set_default_logo() {
    // Check if custom logo is not set
    if (!get_theme_mod('custom_logo')) {
        $logo_path = get_stylesheet_directory() . '/assets/images/logo1.png';
        $logo_url = get_stylesheet_directory_uri() . '/assets/images/logo1.png';
        
        // Check if logo file exists
        if (file_exists($logo_path)) {
            // Create attachment for the logo
            $attachment = array(
                'post_mime_type' => 'image/png',
                'post_title' => 'VJLINK Logo',
                'post_content' => '',
                'post_status' => 'inherit'
            );
            
            // Insert the attachment
            $attach_id = wp_insert_attachment($attachment, $logo_path);
            
            if (!is_wp_error($attach_id)) {
                // Generate attachment metadata
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $logo_path);
                wp_update_attachment_metadata($attach_id, $attach_data);
                
                // Set as custom logo
                set_theme_mod('custom_logo', $attach_id);
            }
        }
    }
}
add_action('after_setup_theme', 'dulichvietnhat_set_default_logo', 20);

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

/**
 * Enqueue scripts and styles.
 */
function dulichvietnhat_scripts() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2');
    
    // Bootstrap Icons
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css', array(), '1.11.1');
    
    // Bootstrap RTL CSS (for Arabic/Hebrew support)
    if (is_rtl()) {
        wp_enqueue_style('bootstrap-rtl', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css', array('bootstrap-css'), '5.3.2');
    }
    
    $style_path = get_stylesheet_directory() . '/style.css';
    $style_version = file_exists($style_path) ? filemtime($style_path) : _S_VERSION;
    wp_enqueue_style('dulichvietnhat-style', get_stylesheet_uri(), array('bootstrap-css'), $style_version);

    // Ensure Font Awesome is available for header icons
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', array(), '6.5.0');

    $assets = array(
        'header-css'              => '/assets/css/header.css',
        'header-override-css'     => '/assets/css/header-override.css',
        'banner-css'              => '/assets/css/banner.css',
        'featured-posts-css'      => '/assets/css/featured-posts.css',
        'featured-tours-css'      => '/assets/css/featured-tours.css',
        'placeholder-images-css'  => '/assets/css/placeholder-images.css'
    );
    foreach ($assets as $handle => $rel) {
        $path = get_stylesheet_directory() . $rel;
        if (file_exists($path)) {
            $ver = filemtime($path);
            wp_enqueue_style($handle, get_stylesheet_directory_uri() . $rel, array('bootstrap-css','dulichvietnhat-style','fontawesome'), $ver);
        }
    }

    // Enqueue icon-fix.css if present
    $icon_fix = get_stylesheet_directory() . '/assets/css/icon-fix.css';
    if (file_exists($icon_fix)) {
        wp_enqueue_style('icon-fix', get_stylesheet_directory_uri() . '/assets/css/icon-fix.css', array('bootstrap-css','fontawesome'), filemtime($icon_fix));
    }

    wp_enqueue_script('jquery');
    
    // Bootstrap JS
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.2', true);
    
    wp_enqueue_script('dulichvietnhat-main-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'bootstrap-js'), _S_VERSION, true);
    wp_enqueue_script('header-js', get_template_directory_uri() . '/assets/js/header.js', array('jquery', 'bootstrap-js'), _S_VERSION, true);
    wp_enqueue_script('banner-js', get_template_directory_uri() . '/assets/js/banner.js', array('jquery', 'bootstrap-js'), _S_VERSION, true);
    wp_enqueue_script('dulichvietnhat-custom-js', get_template_directory_uri() . '/assets/js/custom.js', array('jquery', 'bootstrap-js'), _S_VERSION, true);

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
        wp_enqueue_style('dulichvietnhat-main', get_stylesheet_directory_uri() . '/main.css', array('bootstrap-css','dulichvietnhat-style','header-css','banner-css','featured-posts-css','featured-tours-css'), $main_version);
        $overlay_fix_css = '.posts-grid .post-category,.post-card .post-category,.tour-card .post-category,.card .post-category{display:none!important}.post-thumbnail .overlay,.post-thumbnail::before,.post-thumbnail::after,.post-image::before,.post-image::after,.tour-image::before,.tour-image::after,.destination-image::before,.destination-image::after,.entry-media::before,.entry-media::after{content:none!important;display:none!important;background:transparent!important;opacity:0!important}.post-thumbnail img,.post-image img,.tour-image img,.destination-image img,.entry-media img{filter:none!important;opacity:1!important}.custom-logo{max-height:48px;width:auto;height:auto}.site-header .logo-text{margin-left:10px;display:inline-block;vertical-align:middle}.hero-section{position:relative!important;width:100%!important;padding:40px 0!important;background:#f8f9fa!important;margin-top:0!important;margin-bottom:0!important}.hero-section .slider-container{position:relative!important;width:100%!important;height:400px!important;border-radius:12px!important;overflow:hidden!important;box-shadow:0 4px 12px rgba(0,0,0,0.1)!important}.map-section{background:#fff!important;border-radius:12px!important;padding:20px!important;box-shadow:0 4px 12px rgba(0,0,0,0.1)!important;height:100%!important}.map-title{color:#333!important;font-size:20px!important;font-weight:600!important;margin:0!important;display:flex!important;align-items:center!important}.info-item{display:flex!important;align-items:flex-start!important;margin-bottom:15px!important;padding:10px!important;background:#f8f9fa!important;border-radius:8px!important;transition:background 0.3s ease!important}.info-item:hover{background:#e9ecef!important}.info-item i{font-size:16px!important;margin-right:12px!important;margin-top:2px!important;width:20px!important;text-align:center!important}.info-content{flex:1!important}.info-content strong{display:block!important;color:#333!important;font-weight:600!important;margin-bottom:4px!important}.info-content span,.info-content a{color:#666!important;font-size:14px!important;line-height:1.4!important}.info-content a:hover{color:#007cba!important}.map-actions{display:flex!important;gap:10px!important;flex-wrap:wrap!important}.map-actions .btn{flex:1!important;min-width:120px!important;font-size:14px!important;padding:8px 16px!important;border-radius:6px!important;font-weight:500!important;transition:all 0.3s ease!important}.map-actions .btn:hover{transform:translateY(-2px)!important;box-shadow:0 4px 12px rgba(0,0,0,0.15)!important}.contact-form-section{background:#fff!important;border-radius:12px!important;padding:20px!important;box-shadow:0 4px 12px rgba(0,0,0,0.1)!important;height:100%!important}.form-title{color:#333!important;font-size:20px!important;font-weight:600!important;margin:0!important;display:flex!important;align-items:center!important}.contact-form .form-control{border:2px solid #e9ecef!important;border-radius:8px!important;padding:12px 16px!important;font-size:14px!important;transition:all 0.3s ease!important}.contact-form .form-control:focus{border-color:#ffc107!important;box-shadow:0 0 0 0.2rem rgba(255,193,7,0.25)!important}.contact-form .btn-warning{background-color:#ffc107!important;border-color:#ffc107!important;color:#000!important;font-weight:600!important;padding:12px 24px!important;border-radius:8px!important;transition:all 0.3s ease!important}.contact-form .btn-warning:hover{background-color:#e0a800!important;border-color:#d39e00!important;transform:translateY(-2px)!important;box-shadow:0 4px 12px rgba(255,193,7,0.3)!important}.contact-map-section{padding:40px 0!important;background:#f8f9fa!important}.contact-map-section .contact-form-section{background:#fff!important;border-radius:12px!important;padding:20px!important;box-shadow:0 4px 12px rgba(0,0,0,0.1)!important;height:100%!important}.contact-map-section .map-section{background:#fff!important;border-radius:12px!important;padding:20px!important;box-shadow:0 4px 12px rgba(0,0,0,0.1)!important;height:100%!important}';
        wp_add_inline_style('dulichvietnhat-main', $overlay_fix_css);
        
        // High quality image rendering CSS
        $high_quality_css = '
        .slide-image img {
            filter: brightness(1.2) contrast(1.3) saturate(1.4) !important;
            image-rendering: -webkit-optimize-contrast !important;
            image-rendering: crisp-edges !important;
            image-rendering: high-quality !important;
            -webkit-backface-visibility: hidden !important;
            -webkit-transform: translateZ(0) !important;
            transform: translateZ(0) !important;
            will-change: transform !important;
            backface-visibility: hidden !important;
            -webkit-font-smoothing: antialiased !important;
            -moz-osx-font-smoothing: grayscale !important;
        }
        #image-slider .slide-image img {
            filter: brightness(1.2) contrast(1.3) saturate(1.4) !important;
            image-rendering: -webkit-optimize-contrast !important;
            image-rendering: crisp-edges !important;
            image-rendering: high-quality !important;
        }
        img {
            image-rendering: -webkit-optimize-contrast !important;
            image-rendering: crisp-edges !important;
            image-rendering: high-quality !important;
            -webkit-backface-visibility: hidden !important;
            -webkit-transform: translateZ(0) !important;
            transform: translateZ(0) !important;
            filter: brightness(1.05) contrast(1.1) saturate(1.1) !important;
        }
        ';
        wp_add_inline_style('dulichvietnhat-main', $high_quality_css);
        
        // Logo styling CSS with Customizer values
        $logo_max_height = get_theme_mod('logo_max_height', 60);
        $mobile_logo_max_height = get_theme_mod('mobile_logo_max_height', 45);
        
        $logo_css = '
        .custom-logo {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .logo-image {
            max-height: ' . $logo_max_height . 'px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            transition: all 0.3s ease !important;
            filter: brightness(1.1) contrast(1.1) saturate(1.1) !important;
            image-rendering: -webkit-optimize-contrast !important;
            image-rendering: crisp-edges !important;
            image-rendering: high-quality !important;
            -webkit-backface-visibility: hidden !important;
            -webkit-transform: translateZ(0) !important;
            transform: translateZ(0) !important;
            will-change: transform !important;
            backface-visibility: hidden !important;
            -webkit-font-smoothing: antialiased !important;
            -moz-osx-font-smoothing: grayscale !important;
        }
        .logo-image:hover {
            transform: scale(1.05) !important;
            filter: brightness(1.2) contrast(1.2) saturate(1.2) !important;
        }
        .mobile-logo .logo-image {
            max-height: ' . ($mobile_logo_max_height + 5) . 'px !important;
        }
        .logo-container {
            display: flex !important;
            align-items: center !important;
            gap: 15px !important;
        }
        @media (max-width: 768px) {
            .logo-image {
                max-height: ' . $mobile_logo_max_height . 'px !important;
            }
            .mobile-logo .logo-image {
                max-height: ' . ($mobile_logo_max_height - 5) . 'px !important;
            }
            .logo-container {
                gap: 10px !important;
            }
        }
        ';
        wp_add_inline_style('dulichvietnhat-main', $logo_css);
    }
}
add_action('wp_enqueue_scripts', 'dulichvietnhat_scripts', 100);

// Logo management functions
function dulichvietnhat_get_logo_url() {
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        return wp_get_attachment_image_url($custom_logo_id, 'full');
    }
    return get_stylesheet_directory_uri() . '/assets/images/logo1.png';
}

function dulichvietnhat_get_logo_alt() {
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
        return $alt ? $alt : get_bloginfo('name');
    }
    return get_bloginfo('name');
}

// Add logo settings to existing customizer
function dulichvietnhat_add_logo_customizer_settings($wp_customize) {
    // Logo section
    $wp_customize->add_section('dulichvietnhat_logo', array(
        'title' => __('Logo Settings', 'dulichvietnhat'),
        'priority' => 30,
    ));
    
    // Logo size setting
    $wp_customize->add_setting('logo_max_height', array(
        'default' => 60,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('logo_max_height', array(
        'label' => __('Logo Max Height (px)', 'dulichvietnhat'),
        'section' => 'dulichvietnhat_logo',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 20,
            'max' => 200,
            'step' => 5,
        ),
    ));
    
    // Mobile logo size setting
    $wp_customize->add_setting('mobile_logo_max_height', array(
        'default' => 45,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('mobile_logo_max_height', array(
        'label' => __('Mobile Logo Max Height (px)', 'dulichvietnhat'),
        'section' => 'dulichvietnhat_logo',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 20,
            'max' => 100,
            'step' => 5,
        ),
    ));
}
add_action('customize_register', 'dulichvietnhat_add_logo_customizer_settings');

// Contact Form JavaScript
function dulichvietnhat_contact_form_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('tour-consultation-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(form);
                const data = {
                    action: 'submit_tour_consultation',
                    fullname: formData.get('fullname'),
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    destination: formData.get('destination'),
                    departure_date: formData.get('departure_date'),
                    message: formData.get('message'),
                    consent: formData.get('consent'),
                    nonce: '<?php echo wp_create_nonce('tour_consultation_nonce'); ?>'
                };
                
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';
                submitBtn.disabled = true;
                
                // Send AJAX request
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        // Show success message
                        alert('Cảm ơn bạn! Chúng tôi sẽ liên hệ lại sớm nhất có thể.');
                        form.reset();
                    } else {
                        // Show error message
                        alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'dulichvietnhat_contact_form_script');

// Slider JavaScript
function dulichvietnhat_slider_script() {
    ?>
    <script>
    let currentSlideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const totalSlides = slides.length;

    function showSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Add active class to current slide and dot
        if (slides[index]) {
            slides[index].classList.add('active');
        }
        if (dots[index]) {
            dots[index].classList.add('active');
        }
        
        currentSlideIndex = index;
    }

    function changeSlide(direction) {
        let newIndex = currentSlideIndex + direction;
        
        if (newIndex >= totalSlides) {
            newIndex = 0;
        } else if (newIndex < 0) {
            newIndex = totalSlides - 1;
        }
        
        showSlide(newIndex);
    }

    function currentSlide(index) {
        showSlide(index - 1);
    }

    // Auto slide every 5 seconds
    setInterval(() => {
        changeSlide(1);
    }, 5000);

    // Make functions global
    window.changeSlide = changeSlide;
    window.currentSlide = currentSlide;
    </script>
    <?php
}
add_action('wp_footer', 'dulichvietnhat_slider_script');

// AJAX handler for tour consultation form
function handle_tour_consultation_submission() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tour_consultation_nonce')) {
        wp_die('Security check failed');
    }
    
    // Sanitize form data
    $fullname = sanitize_text_field($_POST['fullname']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $destination = sanitize_text_field($_POST['destination']);
    $departure_date = sanitize_text_field($_POST['departure_date']);
    $message = sanitize_textarea_field($_POST['message']);
    $consent = isset($_POST['consent']) ? 1 : 0;
    
    // Validate required fields
    if (empty($fullname) || empty($email) || !$consent) {
        wp_send_json_error('Vui lòng điền đầy đủ thông tin bắt buộc.');
        return;
    }
    
    // Send email notification
    $to = get_option('admin_email');
    $subject = 'Yêu cầu tư vấn tour mới từ ' . $fullname;
    $body = "
    <h3>Thông tin khách hàng:</h3>
    <p><strong>Họ tên:</strong> {$fullname}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Điện thoại:</strong> {$phone}</p>
    <p><strong>Điểm đến:</strong> {$destination}</p>
    <p><strong>Ngày khởi hành dự kiến:</strong> {$departure_date}</p>
    <p><strong>Tin nhắn:</strong></p>
    <p>{$message}</p>
    <p><strong>Thời gian:</strong> " . current_time('d/m/Y H:i:s') . "</p>
    ";
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    if (wp_mail($to, $subject, $body, $headers)) {
        wp_send_json_success('Email đã được gửi thành công!');
    } else {
        wp_send_json_error('Không thể gửi email. Vui lòng thử lại.');
    }
}
add_action('wp_ajax_submit_tour_consultation', 'handle_tour_consultation_submission');
add_action('wp_ajax_nopriv_submit_tour_consultation', 'handle_tour_consultation_submission');

/**
 * Handle custom contact form submission
 */
function handle_contact_form_submission() {
    if (isset($_POST['contact_form_nonce']) && wp_verify_nonce($_POST['contact_form_nonce'], 'contact_form_action')) {
        $name = sanitize_text_field($_POST['contact_name']);
        $phone = sanitize_text_field($_POST['contact_phone']);
        $email = sanitize_email($_POST['contact_email']);
        $tour = sanitize_text_field($_POST['contact_tour']);
        $message = sanitize_textarea_field($_POST['contact_message']);

        // Email content
        $subject = 'Yêu cầu tư vấn tour từ ' . $name;
        $body = "Thông tin khách hàng:\n\n";
        $body .= "Họ và tên: " . $name . "\n";
        $body .= "Số điện thoại: " . $phone . "\n";
        $body .= "Email: " . $email . "\n";
        $body .= "Tour quan tâm: " . $tour . "\n";
        $body .= "Tin nhắn: " . $message . "\n\n";
        $body .= "Thời gian: " . current_time('d/m/Y H:i:s');

        // Send email
        $admin_email = get_option('admin_email');
        $headers = array('Content-Type: text/plain; charset=UTF-8');

        if (wp_mail($admin_email, $subject, $body, $headers)) {
            // Success message
            add_action('wp_footer', function() {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        alert("Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.");
                    });
                </script>';
            });
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
function dulichvietnhat_resource_hints($urls, $relation_type) {
    if (wp_style_is('google-fonts', 'queue') && 'preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'dulichvietnhat_resource_hints', 10, 2);

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
}
add_action('after_setup_theme', 'dulichvietnhat_add_image_sizes');

function dulichvietnhat_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'dulichvietnhat_excerpt_length', 999);

function dulichvietnhat_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'dulichvietnhat_excerpt_more');
function add_tour_rewrite_rules() {
    add_rewrite_rule(
        '^tour-7-ngay-6-dem/?$',
        'index.php?pagename=tour-7-ngay-6-dem',
        'top'
    );

    add_rewrite_rule(
        '^tour-nhat-ban-mua-thu-2025/?$',
        'index.php?pagename=tour-nhat-ban-mua-thu-2025',
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

add_action('change_locale', function($locale){
    $theme = wp_get_theme();
    $domain = $theme->get('TextDomain');
    if (!$domain) { $domain = 'dulichvietnhat'; }
    unload_textdomain($domain);
    load_theme_textdomain($domain, get_stylesheet_directory() . '/languages');
});

add_filter('body_class', function($classes){
    $locale = determine_locale();
    $classes[] = 'locale-' . sanitize_html_class(strtolower($locale));
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

// Auto ensure temp directory exists for plugin/theme installs on local
function dln_ensure_temp_dir() {
    if (defined('WP_TEMP_DIR') && WP_TEMP_DIR && !is_dir(WP_TEMP_DIR)) {
        wp_mkdir_p(WP_TEMP_DIR);
    }
}
add_action('init', 'dln_ensure_temp_dir', 0);

// On local environment only, relax SSL verification for WordPress.org endpoints to avoid cURL CA issues on localhost
function dln_relax_ssl_on_local($args, $url) {
    if (defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'local') {
        $host = parse_url($url, PHP_URL_HOST);
        if (in_array($host, array('api.wordpress.org','downloads.wordpress.org','wordpress.org'), true)) {
            $args['sslverify'] = false; // local-only fallback; fix php.ini CA for production
        }
        // Also raise timeout for slow Windows cURL on localhost
        if (empty($args['timeout']) || $args['timeout'] < 60) {
            $args['timeout'] = 60;
        }
    }
    return $args;
}
add_filter('http_request_args', 'dln_relax_ssl_on_local', 10, 2);