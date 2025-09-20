<?php
/**
 * Admin Settings Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_Admin_Settings {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            __('VJL Travel Settings', 'vjl-travel'),
            __('VJL Travel', 'vjl-travel'),
            'manage_options',
            'vjl-travel-settings',
            array($this, 'admin_page'),
            'dashicons-admin-site-alt3',
            30
        );
        
        add_submenu_page(
            'vjl-travel-settings',
            __('General Settings', 'vjl-travel'),
            __('General', 'vjl-travel'),
            'manage_options',
            'vjl-travel-settings',
            array($this, 'admin_page')
        );
        
        add_submenu_page(
            'vjl-travel-settings',
            __('Logo Settings', 'vjl-travel'),
            __('Logo', 'vjl-travel'),
            'manage_options',
            'vjl-travel-logo',
            array($this, 'logo_page')
        );
    }
    
    public function register_settings() {
        // General Settings
        register_setting('vjl_travel_settings', 'vjl_travel_phone');
        register_setting('vjl_travel_settings', 'vjl_travel_email');
        register_setting('vjl_travel_settings', 'vjl_travel_address');
        register_setting('vjl_travel_settings', 'vjl_travel_facebook');
        register_setting('vjl_travel_settings', 'vjl_travel_instagram');
        register_setting('vjl_travel_settings', 'vjl_travel_youtube');
        register_setting('vjl_travel_settings', 'vjl_travel_google_maps_api');
        register_setting('vjl_travel_settings', 'vjl_travel_currency');
        register_setting('vjl_travel_settings', 'vjl_travel_language');
        
        // Feature Settings
        register_setting('vjl_travel_settings', 'vjl_travel_enable_rating');
        register_setting('vjl_travel_settings', 'vjl_travel_enable_gallery');
        register_setting('vjl_travel_settings', 'vjl_travel_enable_slider');
        
        // Logo Settings
        register_setting('vjl_travel_logo', 'vjl_travel_logo');
        register_setting('vjl_travel_logo', 'vjl_travel_logo_width');
        register_setting('vjl_travel_logo', 'vjl_travel_logo_height');
    }
    
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('VJL Travel Settings', 'vjl-travel'); ?></h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('vjl_travel_settings');
                do_settings_sections('vjl_travel_settings');
                ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Số điện thoại', 'vjl-travel'); ?></th>
                        <td>
                            <input type="text" name="vjl_travel_phone" value="<?php echo esc_attr(get_option('vjl_travel_phone', '0349421736')); ?>" class="regular-text" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Email', 'vjl-travel'); ?></th>
                        <td>
                            <input type="email" name="vjl_travel_email" value="<?php echo esc_attr(get_option('vjl_travel_email', 'ri01652965673@gmail.com')); ?>" class="regular-text" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Địa chỉ', 'vjl-travel'); ?></th>
                        <td>
                            <textarea name="vjl_travel_address" rows="3" cols="50" class="large-text"><?php echo esc_textarea(get_option('vjl_travel_address', '52 Huỳnh Tấn Phát, phường An Đông, TP Huế')); ?></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Facebook URL', 'vjl-travel'); ?></th>
                        <td>
                            <input type="url" name="vjl_travel_facebook" value="<?php echo esc_attr(get_option('vjl_travel_facebook')); ?>" class="regular-text" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Instagram URL', 'vjl-travel'); ?></th>
                        <td>
                            <input type="url" name="vjl_travel_instagram" value="<?php echo esc_attr(get_option('vjl_travel_instagram')); ?>" class="regular-text" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('YouTube URL', 'vjl-travel'); ?></th>
                        <td>
                            <input type="url" name="vjl_travel_youtube" value="<?php echo esc_attr(get_option('vjl_travel_youtube')); ?>" class="regular-text" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Google Maps API Key', 'vjl-travel'); ?></th>
                        <td>
                            <input type="text" name="vjl_travel_google_maps_api" value="<?php echo esc_attr(get_option('vjl_travel_google_maps_api')); ?>" class="regular-text" />
                            <p class="description"><?php _e('API key để hiển thị bản đồ Google Maps', 'vjl-travel'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Tiền tệ', 'vjl-travel'); ?></th>
                        <td>
                            <select name="vjl_travel_currency">
                                <option value="VND" <?php selected(get_option('vjl_travel_currency', 'VND'), 'VND'); ?>><?php _e('VNĐ', 'vjl-travel'); ?></option>
                                <option value="USD" <?php selected(get_option('vjl_travel_currency'), 'USD'); ?>><?php _e('USD', 'vjl-travel'); ?></option>
                                <option value="EUR" <?php selected(get_option('vjl_travel_currency'), 'EUR'); ?>><?php _e('EUR', 'vjl-travel'); ?></option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Ngôn ngữ mặc định', 'vjl-travel'); ?></th>
                        <td>
                            <select name="vjl_travel_language">
                                <option value="vi" <?php selected(get_option('vjl_travel_language', 'vi'), 'vi'); ?>><?php _e('Tiếng Việt', 'vjl-travel'); ?></option>
                                <option value="en" <?php selected(get_option('vjl_travel_language'), 'en'); ?>><?php _e('English', 'vjl-travel'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <h2><?php _e('Tính năng', 'vjl-travel'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Bật đánh giá', 'vjl-travel'); ?></th>
                        <td>
                            <input type="checkbox" name="vjl_travel_enable_rating" value="1" <?php checked(get_option('vjl_travel_enable_rating', true), 1); ?> />
                            <label><?php _e('Cho phép khách hàng đánh giá tours và dịch vụ', 'vjl-travel'); ?></label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Bật thư viện ảnh', 'vjl-travel'); ?></th>
                        <td>
                            <input type="checkbox" name="vjl_travel_enable_gallery" value="1" <?php checked(get_option('vjl_travel_enable_gallery', true), 1); ?> />
                            <label><?php _e('Hiển thị thư viện ảnh trên website', 'vjl-travel'); ?></label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Bật slider', 'vjl-travel'); ?></th>
                        <td>
                            <input type="checkbox" name="vjl_travel_enable_slider" value="1" <?php checked(get_option('vjl_travel_enable_slider', true), 1); ?> />
                            <label><?php _e('Hiển thị slider trên trang chủ', 'vjl-travel'); ?></label>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    public function logo_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Logo Settings', 'vjl-travel'); ?></h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('vjl_travel_logo');
                do_settings_sections('vjl_travel_logo');
                ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Logo', 'vjl-travel'); ?></th>
                        <td>
                            <input type="url" name="vjl_travel_logo" value="<?php echo esc_attr(get_option('vjl_travel_logo')); ?>" class="regular-text" id="logo-url" />
                            <button type="button" class="button" id="upload-logo"><?php _e('Chọn ảnh', 'vjl-travel'); ?></button>
                            <p class="description"><?php _e('URL của logo website', 'vjl-travel'); ?></p>
                            
                            <?php if (get_option('vjl_travel_logo')): ?>
                                <div id="logo-preview" style="margin-top: 10px;">
                                    <img src="<?php echo esc_url(get_option('vjl_travel_logo')); ?>" style="max-width: 200px; height: auto;" />
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Chiều rộng logo (px)', 'vjl-travel'); ?></th>
                        <td>
                            <input type="number" name="vjl_travel_logo_width" value="<?php echo esc_attr(get_option('vjl_travel_logo_width', '200')); ?>" class="small-text" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Chiều cao logo (px)', 'vjl-travel'); ?></th>
                        <td>
                            <input type="number" name="vjl_travel_logo_height" value="<?php echo esc_attr(get_option('vjl_travel_logo_height', '60')); ?>" class="small-text" />
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#upload-logo').click(function(e) {
                e.preventDefault();
                
                var mediaUploader = wp.media({
                    title: '<?php _e('Chọn Logo', 'vjl-travel'); ?>',
                    button: {
                        text: '<?php _e('Chọn ảnh', 'vjl-travel'); ?>'
                    },
                    multiple: false
                });
                
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#logo-url').val(attachment.url);
                    $('#logo-preview').html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto;" />');
                });
                
                mediaUploader.open();
            });
        });
        </script>
        <?php
    }
}
