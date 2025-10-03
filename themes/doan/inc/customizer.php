<?php
/**
 * Customizer additions
 *
 * @package dulichvietnhat
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function dulichvietnhat_customize_register($wp_customize) {
    // Remove default sections
    $wp_customize->remove_section('colors');
    $wp_customize->remove_section('header_image');
    $wp_customize->remove_section('background_image');
    $wp_customize->remove_section('static_front_page');
    $wp_customize->remove_section('custom_css');

    // Add Theme Options Panel
    $wp_customize->add_panel(
        'theme_options',
        array(
            'title'       => __('Theme Options', 'dulichvietnhat'),
            'description' => __('Theme specific options', 'dulichvietnhat'),
            'priority'    => 30,
        )
    );

    // Header Section
    $wp_customize->add_section(
        'header_section',
        array(
            'title'    => __('Header Settings', 'dulichvietnhat'),
            'priority' => 10,
            'panel'    => 'theme_options',
        )
    );

    // Banner Section
    $wp_customize->add_section(
        'banner_section',
        array(
            'title'    => __('Banner Settings', 'dulichvietnhat'),
            'priority' => 5,
            'panel'    => 'theme_options',
        )
    );

    // Banner Type
    $wp_customize->add_setting(
        'banner_type',
        array(
            'default'           => 'gradient',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'banner_type',
        array(
            'type'    => 'select',
            'label'   => __('Banner Type', 'dulichvietnhat'),
            'section' => 'banner_section',
            'choices' => array(
                'gradient' => __('Gradient Background', 'dulichvietnhat'),
                'image'    => __('Image Background', 'dulichvietnhat'),
                'video'    => __('Video Background', 'dulichvietnhat'),
            ),
        )
    );

    // Banner Background Image
    $wp_customize->add_setting(
        'banner_background_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'banner_background_image',
            array(
                'label'    => __('Banner Background Image', 'dulichvietnhat'),
                'section'  => 'banner_section',
                'settings' => 'banner_background_image',
            )
        )
    );

    // Banner Title
    $wp_customize->add_setting(
        'banner_title',
        array(
            'default'           => 'Khám phá những chuyến đi tuyệt vời',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'banner_title',
        array(
            'type'    => 'text',
            'label'   => __('Banner Title', 'dulichvietnhat'),
            'section' => 'banner_section',
        )
    );

    // Banner Subtitle
    $wp_customize->add_setting(
        'banner_subtitle',
        array(
            'default'           => 'Trải nghiệm du lịch độc đáo với dịch vụ chuyên nghiệp',
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'banner_subtitle',
        array(
            'type'    => 'textarea',
            'label'   => __('Banner Subtitle', 'dulichvietnhat'),
            'section' => 'banner_section',
        )
    );

    // Banner Primary Button Text
    $wp_customize->add_setting(
        'banner_primary_text',
        array(
            'default'           => 'Khám phá ngay',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'banner_primary_text',
        array(
            'type'    => 'text',
            'label'   => __('Primary Button Text', 'dulichvietnhat'),
            'section' => 'banner_section',
        )
    );

    // Banner Primary Button Link
    $wp_customize->add_setting(
        'banner_primary_link',
        array(
            'default'           => '#featured-tours',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'banner_primary_link',
        array(
            'type'    => 'url',
            'label'   => __('Primary Button Link', 'dulichvietnhat'),
            'section' => 'banner_section',
        )
    );

    // Banner Secondary Button Text
    $wp_customize->add_setting(
        'banner_secondary_text',
        array(
            'default'           => 'Xem video',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'banner_secondary_text',
        array(
            'type'    => 'text',
            'label'   => __('Secondary Button Text', 'dulichvietnhat'),
            'section' => 'banner_section',
        )
    );

    // Banner Secondary Button Link
    $wp_customize->add_setting(
        'banner_secondary_link',
        array(
            'default'           => '#destinations',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'banner_secondary_link',
        array(
            'type'    => 'url',
            'label'   => __('Secondary Button Link', 'dulichvietnhat'),
            'section' => 'banner_section',
        )
    );

    // Stats Section
    $wp_customize->add_section(
        'banner_stats_section',
        array(
            'title'    => __('Banner Statistics', 'dulichvietnhat'),
            'priority' => 6,
            'panel'    => 'theme_options',
        )
    );

    // Stat 1
    $wp_customize->add_setting(
        'stat_1_number',
        array(
            'default'           => '500+',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'stat_1_number',
        array(
            'type'    => 'text',
            'label'   => __('Statistic 1 Number', 'dulichvietnhat'),
            'section' => 'banner_stats_section',
        )
    );

    $wp_customize->add_setting(
        'stat_1_label',
        array(
            'default'           => 'Khách hàng hài lòng',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'stat_1_label',
        array(
            'type'    => 'text',
            'label'   => __('Statistic 1 Label', 'dulichvietnhat'),
            'section' => 'banner_stats_section',
        )
    );

    // Stat 2
    $wp_customize->add_setting(
        'stat_2_number',
        array(
            'default'           => '50+',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'stat_2_number',
        array(
            'type'    => 'text',
            'label'   => __('Statistic 2 Number', 'dulichvietnhat'),
            'section' => 'banner_stats_section',
        )
    );

    $wp_customize->add_setting(
        'stat_2_label',
        array(
            'default'           => 'Điểm đến',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'stat_2_label',
        array(
            'type'    => 'text',
            'label'   => __('Statistic 2 Label', 'dulichvietnhat'),
            'section' => 'banner_stats_section',
        )
    );

    // Stat 3
    $wp_customize->add_setting(
        'stat_3_number',
        array(
            'default'           => '10+',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'stat_3_number',
        array(
            'type'    => 'text',
            'label'   => __('Statistic 3 Number', 'dulichvietnhat'),
            'section' => 'banner_stats_section',
        )
    );

    $wp_customize->add_setting(
        'stat_3_label',
        array(
            'default'           => 'Năm kinh nghiệm',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'stat_3_label',
        array(
            'type'    => 'text',
            'label'   => __('Statistic 3 Label', 'dulichvietnhat'),
            'section' => 'banner_stats_section',
        )
    );

    // Stat 4
    $wp_customize->add_setting(
        'stat_4_number',
        array(
            'default'           => '24/7',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'stat_4_number',
        array(
            'type'    => 'text',
            'label'   => __('Statistic 4 Number', 'dulichvietnhat'),
            'section' => 'banner_stats_section',
        )
    );

    $wp_customize->add_setting(
        'stat_4_label',
        array(
            'default'           => 'Hỗ trợ khách hàng',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'stat_4_label',
        array(
            'type'    => 'text',
            'label'   => __('Statistic 4 Label', 'dulichvietnhat'),
            'section' => 'banner_stats_section',
        )
    );

    // Social Links Section
    $wp_customize->add_section(
        'social_links_section',
        array(
            'title'    => __('Social Links', 'dulichvietnhat'),
            'priority' => 15,
            'panel'    => 'theme_options',
        )
    );

    $social_fields = array(
        'social_facebook'  => __('Facebook URL', 'dulichvietnhat'),
        'social_instagram' => __('Instagram URL', 'dulichvietnhat'),
        'social_tiktok'    => __('TikTok URL', 'dulichvietnhat'),
        'social_youtube'   => __('YouTube URL', 'dulichvietnhat'),
        'social_twitter'   => __('Twitter/X URL', 'dulichvietnhat'),
        'social_zalo'      => __('Zalo/Chat URL', 'dulichvietnhat'),
    );
    foreach ($social_fields as $setting_id => $label) {
        $wp_customize->add_setting(
            $setting_id,
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );
        $wp_customize->add_control(
            $setting_id,
            array(
                'label'   => $label,
                'section' => 'social_links_section',
                'type'    => 'url',
            )
        );
    }

    // Header Contact Info
    $wp_customize->add_setting(
        'header_phone',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'header_phone',
        array(
            'label'   => __('Phone Number', 'dulichvietnhat'),
            'section' => 'header_section',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'header_email',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
        )
    );

    $wp_customize->add_control(
        'header_email',
        array(
            'label'   => __('Email Address', 'dulichvietnhat'),
            'section' => 'header_section',
            'type'    => 'email',
        )
    );
}
add_action('customize_register', 'dulichvietnhat_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function dulichvietnhat_customize_preview_js() {
    wp_enqueue_script(
        'dulichvietnhat-customizer',
        get_template_directory_uri() . '/assets/js/customizer.js',
        array('customize-preview'),
        _S_VERSION,
        true
    );
}
add_action('customize_preview_init', 'dulichvietnhat_customize_preview_js');
