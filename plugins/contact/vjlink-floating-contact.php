<?php
/**
 * Plugin Name: VJLink Floating Contact
 * Description: Adds a floating WhatsApp contact button to your website
 * Version: 1.0.1
 * Author: VJLink
 * Text Domain: vjlink-floating-contact
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class VJLink_Floating_Contact {
    public function __construct() {
        // Add settings link
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add the floating button HTML
        add_action('wp_footer', array($this, 'add_floating_button'));
    }
    
    public function enqueue_scripts() {
        // Font Awesome for icons
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
        
        // Add Zalo icon CSS using proper Zalo logo
        wp_add_inline_style('font-awesome', '
            .fab.fa-zalo:before {
                content: "";
                display: inline-block;
                width: 20px;
                height: 20px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\'%3E%3Cpath fill=\'%23ffffff\' d=\'M12.001 2C6.48 2 2 6.48 2 12s4.48 10 10.001 10c5.52 0 9.999-4.48 9.999-10s-4.479-10-9.999-10zm.001 18.5c-4.69 0-8.5-3.81-8.5-8.5s3.81-8.5 8.5-8.5 8.5 3.81 8.5 8.5-3.81 8.5-8.5 8.5zm3.5-12.5h-7c-.276 0-.5.224-.5.5s.224.5.5.5h2.5v6c0 .276.224.5.5.5s.5-.224.5-.5v-6h2.5c.276 0 .5-.224.5-.5s-.224-.5-.5-.5z\'/%3E%3C/svg%3E");
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center;
                vertical-align: middle;
            }
        ');
        
        // Plugin styles
        wp_enqueue_style(
            'vjlink-floating-contact',
            plugin_dir_url(__FILE__) . 'assets/css/vjlink-floating-contact.css',
            array(),
            '1.0.1'
        );
        
        // Add custom CSS for Zalo button
        wp_add_inline_style('vjlink-floating-contact', '
            .vjlink-contact-zalo {
                background-color: #0068ff;
                color: white !important;
                border-radius: 50px;
                padding: 10px 20px;
                text-decoration: none;
                display: flex;
                align-items: center;
                margin: 5px 0;
                transition: all 0.3s ease;
            }
            .vjlink-contact-zalo:hover {
                background-color: #0052cc;
                transform: translateY(-2px);
            }
            .vjlink-contact-zalo i {
                margin-right: 8px;
                font-size: 20px;
            }
        ');
        
        // Plugin script
        wp_enqueue_script(
            'vjlink-floating-contact',
            plugin_dir_url(__FILE__) . 'assets/js/vjlink-floating-contact.js',
            array('jquery'),
            '1.0.1',
            true
        );
    }
    
    public function add_floating_button() {
        $phone_number = get_option('vjlink_contact_phone', '0349421736');
        $whatsapp_number = get_option('vjlink_whatsapp_number', '840349421736');
        $zalo_number = get_option('vjlink_zalo_number', '840349421736');
        $whatsapp_message = urlencode(get_option('vjlink_whatsapp_message', 'Xin chào, tôi muốn được tư vấn thêm về tour du lịch'));
        $button_text = get_option('vjlink_button_text', 'Liên hệ tư vấn');
        $button_position = get_option('vjlink_button_position', 'right');
        
        // Generate URLs
        $whatsapp_url = 'https://wa.me/' . $whatsapp_number . '?text=' . $whatsapp_message;
        $zalo_url = 'https://zalo.me/' . $zalo_number;
        $phone_url = 'tel:' . preg_replace('/[^0-9+]/', '', $phone_number);
        
        // Output the HTML
        echo '<div class="vjlink-floating-contact vjlink-position-' . esc_attr($button_position) . '">';
        echo '  <div class="vjlink-contact-buttons">';
        echo '    <a href="' . esc_url($phone_url) . '" class="vjlink-contact-phone" title="Gọi ngay">';
        echo '      <i class="fas fa-phone-alt"></i>';
        echo '      <span class="vjlink-phone-number">' . esc_html($phone_number) . '</span>';
        echo '    </a>';
        echo '    <a href="' . esc_url($whatsapp_url) . '" class="vjlink-contact-whatsapp" target="_blank" title="Chat WhatsApp">';
        echo '      <i class="fab fa-whatsapp"></i>';
        echo '      <span class="vjlink-whatsapp-text">' . esc_html($button_text) . '</span>';
        echo '    </a>';
        echo '    <a href="' . esc_url($zalo_url) . '" class="vjlink-contact-zalo" target="_blank" title="Chat Zalo">';
        echo '      <i class="fab fa-zalo"></i>';
        echo '      <span class="vjlink-zalo-text">Chat Zalo</span>';
        echo '    </a>';
        echo '  </div>';
        echo '</div>';
    }
    
    public function add_admin_menu() {
        add_options_page(
            'Cài đặt Nổi bật liên hệ',
            'Nổi bật liên hệ',
            'manage_options',
            'vjlink-floating-contact',
            array($this, 'settings_page')
        );
    }
    
    public function register_settings() {
        register_setting('vjlink_floating_contact', 'vjlink_contact_phone');
        register_setting('vjlink_floating_contact', 'vjlink_whatsapp_number');
        register_setting('vjlink_floating_contact', 'vjlink_zalo_number');
        register_setting('vjlink_floating_contact', 'vjlink_whatsapp_message');
        register_setting('vjlink_floating_contact', 'vjlink_button_text');
        register_setting('vjlink_floating_contact', 'vjlink_button_position');
    }
    
    public function settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('vjlink_floating_contact');
                do_settings_sections('vjlink_floating_contact');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="vjlink_contact_phone">Số điện thoại</label>
                        </th>
                        <td>
                            <input type="text" id="vjlink_contact_phone" name="vjlink_contact_phone" 
                                   value="<?php echo esc_attr(get_option('vjlink_contact_phone', '0349421736')); ?>" 
                                   class="regular-text">
                            <p class="description">Nhập số điện thoại hiển thị (VD: 0987654321)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="vjlink_whatsapp_number">Số WhatsApp (có mã quốc gia)</label>
                        </th>
                        <td>
                            <input type="text" id="vjlink_whatsapp_number" name="vjlink_whatsapp_number" 
                                   value="<?php echo esc_attr(get_option('vjlink_whatsapp_number', '840349421736')); ?>" 
                                   class="regular-text">
                            <p class="description">Nhập số WhatsApp đầy đủ với mã quốc gia (VD: 84987654321)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="vjlink_zalo_number">Số Zalo (có mã quốc gia)</label>
                        </th>
                        <td>
                            <input type="text" id="vjlink_zalo_number" name="vjlink_zalo_number" 
                                   value="<?php echo esc_attr(get_option('vjlink_zalo_number', '840349421736')); ?>" 
                                   class="regular-text">
                            <p class="description">Nhập số Zalo đầy đủ với mã quốc gia (VD: 84367722389)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="vjlink_whatsapp_message">Tin nhắn mặc định</label>
                        </th>
                        <td>
                            <input type="text" id="vjlink_whatsapp_message" name="vjlink_whatsapp_message" 
                                   value="<?php echo esc_attr(get_option('vjlink_whatsapp_message', 'Xin chào, tôi muốn được tư vấn thêm về tour du lịch')); ?>" 
                                   class="large-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="vjlink_button_text">Nút hiển thị</label>
                        </th>
                        <td>
                            <input type="text" id="vjlink_button_text" name="vjlink_button_text" 
                                   value="<?php echo esc_attr(get_option('vjlink_button_text', 'Liên hệ tư vấn')); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="vjlink_button_position">Vị trí hiển thị</label>
                        </th>
                        <td>
                            <select id="vjlink_button_position" name="vjlink_button_position">
                                <option value="right" <?php selected(get_option('vjlink_button_position', 'right'), 'right'); ?>>Bên phải</option>
                                <option value="left" <?php selected(get_option('vjlink_button_position'), 'left'); ?>>Bên trái</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Lưu cài đặt'); ?>
            </form>
        </div>
        <?php
    }
    
    public function add_settings_link($links) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=vjlink-floating-contact') . '">' . __('Cài đặt') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}

// Initialize the plugin
new VJLink_Floating_Contact();
