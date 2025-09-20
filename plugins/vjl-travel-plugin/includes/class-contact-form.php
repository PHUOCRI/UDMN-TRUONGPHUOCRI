<?php
/**
 * Contact Form Functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_Contact_Form {
    
    public function __construct() {
        add_shortcode('vjl_contact_form', array($this, 'contact_form_shortcode'));
        add_action('wp_ajax_vjl_contact_form_submit', array($this, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_vjl_contact_form_submit', array($this, 'handle_form_submission'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('vjl-contact-form', VJL_TRAVEL_PLUGIN_URL . 'assets/js/contact-form.js', array('jquery'), VJL_TRAVEL_PLUGIN_VERSION, true);
        wp_enqueue_style('vjl-contact-form', VJL_TRAVEL_PLUGIN_URL . 'assets/css/contact-form.css', array(), VJL_TRAVEL_PLUGIN_VERSION);
        
        wp_localize_script('vjl-contact-form', 'vjlContactForm', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('vjl_contact_form_nonce'),
            'messages' => array(
                'success' => __('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.', 'vjl-travel'),
                'error' => __('Có lỗi xảy ra. Vui lòng thử lại sau.', 'vjl-travel'),
                'required' => __('Vui lòng điền đầy đủ thông tin bắt buộc.', 'vjl-travel'),
                'email_invalid' => __('Email không hợp lệ.', 'vjl-travel'),
                'phone_invalid' => __('Số điện thoại không hợp lệ.', 'vjl-travel'),
            )
        ));
    }
    
    public function contact_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Liên hệ với chúng tôi', 'vjl-travel'),
            'show_title' => true,
            'show_phone' => true,
            'show_email' => true,
            'show_address' => true,
        ), $atts);
        
        ob_start();
        ?>
        <div class="vjl-contact-form-wrapper">
            <?php if ($atts['show_title']): ?>
                <h3 class="vjl-contact-form-title"><?php echo esc_html($atts['title']); ?></h3>
            <?php endif; ?>
            
            <form id="vjl-contact-form" class="vjl-contact-form" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vjl-name" class="form-label"><?php _e('Họ và tên *', 'vjl-travel'); ?></label>
                            <input type="text" class="form-control" id="vjl-name" name="name" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vjl-phone" class="form-label"><?php _e('Số điện thoại *', 'vjl-travel'); ?></label>
                            <input type="tel" class="form-control" id="vjl-phone" name="phone" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vjl-email" class="form-label"><?php _e('Email *', 'vjl-travel'); ?></label>
                            <input type="email" class="form-control" id="vjl-email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vjl-subject" class="form-label"><?php _e('Chủ đề', 'vjl-travel'); ?></label>
                            <select class="form-select" id="vjl-subject" name="subject">
                                <option value=""><?php _e('Chọn chủ đề', 'vjl-travel'); ?></option>
                                <option value="tour_booking"><?php _e('Đặt tour', 'vjl-travel'); ?></option>
                                <option value="tour_inquiry"><?php _e('Tư vấn tour', 'vjl-travel'); ?></option>
                                <option value="service_inquiry"><?php _e('Tư vấn dịch vụ', 'vjl-travel'); ?></option>
                                <option value="complaint"><?php _e('Khiếu nại', 'vjl-travel'); ?></option>
                                <option value="other"><?php _e('Khác', 'vjl-travel'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="vjl-message" class="form-label"><?php _e('Nội dung *', 'vjl-travel'); ?></label>
                    <textarea class="form-control" id="vjl-message" name="message" rows="5" required placeholder="<?php _e('Vui lòng mô tả chi tiết yêu cầu của bạn...', 'vjl-travel'); ?>"></textarea>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="privacy" required>
                    <label class="form-check-label">
                        <?php _e('Tôi đồng ý với', 'vjl-travel'); ?> 
                        <a href="<?php echo get_privacy_policy_url(); ?>" target="_blank"><?php _e('chính sách bảo mật', 'vjl-travel'); ?></a>
                    </label>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span class="btn-text"><?php _e('Gửi tin nhắn', 'vjl-travel'); ?></span>
                        <span class="btn-loading" style="display: none;">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            <?php _e('Đang gửi...', 'vjl-travel'); ?>
                        </span>
                    </button>
                </div>
                
                <div class="alert mt-3" style="display: none;"></div>
            </form>
            
            <?php if ($atts['show_phone'] || $atts['show_email'] || $atts['show_address']): ?>
                <div class="vjl-contact-info mt-4">
                    <h4><?php _e('Thông tin liên hệ', 'vjl-travel'); ?></h4>
                    
                    <?php if ($atts['show_phone']): ?>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <span><?php echo esc_html(get_option('vjl_travel_phone', '0349421736')); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($atts['show_email']): ?>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            <span><?php echo esc_html(get_option('vjl_travel_email', 'ri01652965673@gmail.com')); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($atts['show_address']): ?>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span><?php echo esc_html(get_option('vjl_travel_address', '52 Huỳnh Tấn Phát, phường An Đông, TP Huế')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function handle_form_submission() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'vjl_contact_form_nonce')) {
            wp_send_json_error(array('message' => __('Lỗi bảo mật. Vui lòng thử lại.', 'vjl-travel')));
        }
        
        // Sanitize and validate input
        $name = sanitize_text_field($_POST['name']);
        $phone = sanitize_text_field($_POST['phone']);
        $email = sanitize_email($_POST['email']);
        $subject = sanitize_text_field($_POST['subject']);
        $message = sanitize_textarea_field($_POST['message']);
        $privacy = isset($_POST['privacy']) ? true : false;
        
        // Validation
        $errors = array();
        
        if (empty($name)) {
            $errors[] = __('Họ và tên là bắt buộc.', 'vjl-travel');
        }
        
        if (empty($phone)) {
            $errors[] = __('Số điện thoại là bắt buộc.', 'vjl-travel');
        } elseif (!preg_match('/^[0-9+\-\s()]+$/', $phone)) {
            $errors[] = __('Số điện thoại không hợp lệ.', 'vjl-travel');
        }
        
        if (empty($email) || !is_email($email)) {
            $errors[] = __('Email không hợp lệ.', 'vjl-travel');
        }
        
        if (empty($message)) {
            $errors[] = __('Nội dung tin nhắn là bắt buộc.', 'vjl-travel');
        }
        
        if (!$privacy) {
            $errors[] = __('Vui lòng đồng ý với chính sách bảo mật.', 'vjl-travel');
        }
        
        if (!empty($errors)) {
            wp_send_json_error(array('message' => implode('<br>', $errors)));
        }
        
        // Send email notification
        $this->send_email_notification($name, $phone, $email, $subject, $message);
        
        // Send auto-reply to customer
        $this->send_auto_reply($name, $email);
        
        wp_send_json_success(array('message' => __('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.', 'vjl-travel')));
    }
    
    private function send_email_notification($name, $phone, $email, $subject, $message) {
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        $email_subject = sprintf(__('[%s] Tin nhắn liên hệ mới từ %s', 'vjl-travel'), $site_name, $name);
        
        $email_message = sprintf(
            __("Bạn có tin nhắn liên hệ mới từ website %s:\n\n", 'vjl-travel'),
            $site_name
        );
        
        $email_message .= sprintf(__("Họ và tên: %s\n", 'vjl-travel'), $name);
        $email_message .= sprintf(__("Số điện thoại: %s\n", 'vjl-travel'), $phone);
        $email_message .= sprintf(__("Email: %s\n", 'vjl-travel'), $email);
        $email_message .= sprintf(__("Chủ đề: %s\n", 'vjl-travel'), $subject);
        $email_message .= sprintf(__("Nội dung:\n%s\n\n", 'vjl-travel'), $message);
        $email_message .= sprintf(__("Thời gian: %s\n", 'vjl-travel'), current_time('d/m/Y H:i:s'));
        $email_message .= sprintf(__("IP: %s\n", 'vjl-travel'), $_SERVER['REMOTE_ADDR']);
        
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $site_name . ' <' . $admin_email . '>',
            'Reply-To: ' . $name . ' <' . $email . '>',
        );
        
        wp_mail($admin_email, $email_subject, $email_message, $headers);
    }
    
    private function send_auto_reply($name, $email) {
        $site_name = get_bloginfo('name');
        $admin_phone = get_option('vjl_travel_phone', '0349421736');
        $admin_email = get_option('vjl_travel_email', 'ri01652965673@gmail.com');
        
        $email_subject = sprintf(__('[%s] Cảm ơn bạn đã liên hệ', 'vjl-travel'), $site_name);
        
        $email_message = sprintf(__("Xin chào %s,\n\n", 'vjl-travel'), $name);
        $email_message .= sprintf(__("Cảm ơn bạn đã liên hệ với %s. Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi trong thời gian sớm nhất.\n\n", 'vjl-travel'), $site_name);
        $email_message .= __("Thông tin liên hệ của chúng tôi:\n", 'vjl-travel');
        $email_message .= sprintf(__("Điện thoại: %s\n", 'vjl-travel'), $admin_phone);
        $email_message .= sprintf(__("Email: %s\n\n", 'vjl-travel'), $admin_email);
        $email_message .= __("Trân trọng,\n", 'vjl-travel');
        $email_message .= sprintf(__("Đội ngũ %s", 'vjl-travel'), $site_name);
        
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $site_name . ' <' . $admin_email . '>',
        );
        
        wp_mail($email, $email_subject, $email_message, $headers);
    }
}
