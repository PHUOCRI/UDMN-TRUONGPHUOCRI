    </div><!-- #content -->

<footer id="colophon" class="site-footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-widgets">
                <!-- Contact Information -->
                <div class="footer-widget">
                    <h3 class="widget-title"><?php esc_html_e('Thông tin liên hệ', 'dulichhue'); ?></h3>
                    <div class="contact-details">
                        <p class="address"><?php echo esc_html(get_theme_mod('contact_address', '52 huỳnh tấn phát - Phường an đông - TP Huế')); ?></p>
                        <p class="hotline"><?php esc_html_e('Hotline:', 'dulichhue'); ?> <a href="tel:0349421736">0349421736</a></p>
                        <p class="email"><?php esc_html_e('Email:', 'dulichhue'); ?> <a href="mailto:info@dulichhue.vn">ri0652@gmail.com</a></p>
                        <div class="office-branch">
                            <p class="office-title"><?php esc_html_e('Văn phòng tp huế', 'dulichhue'); ?></p>
                            <p class="office-address"><?php echo esc_html(get_theme_mod('office_hue', '52 huỳnh tấn phát')); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Company Info -->
                <div class="footer-widget">
                    <h3 class="widget-title"><?php esc_html_e('Du lịch Huế', 'dulichhue'); ?></h3>
                    <ul class="company-links">
                        <li><a href="<?php echo esc_url(home_url('/gioi-thieu')); ?>"><?php esc_html_e('Giới thiệu Công ty', 'dulichhue'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/faq')); ?>"><?php esc_html_e('Câu hỏi thường gặp', 'dulichhue'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/tuyen-dung')); ?>"><?php esc_html_e('Tuyển dụng', 'dulichvietnhat'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/chinh-sach-bao-mat')); ?>"><?php esc_html_e('Chính sách bảo mật', 'dulichhue'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/quyen-rieng-tu')); ?>"><?php esc_html_e('Quyền riêng tư', 'dulichhue'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/chinh-sach-hoan-huy')); ?>"><?php esc_html_e('Chính sách hoàn/hủy', 'dulichhue'); ?></a></li>
                    </ul>
                </div>

                <!-- Contact Services -->
                <div class="footer-widget">
                    <h3 class="widget-title"><?php esc_html_e('Liên hệ', 'dulichvietnhat'); ?></h3>
                    <ul class="contact-services">
                        <li><a href="<?php echo esc_url(home_url('/dai-ly')); ?>"><?php esc_html_e('Đại lý', 'dulichhue'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/faq')); ?>"><?php esc_html_e('FAQ', 'dulichhue'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/tour-rieng')); ?>"><?php esc_html_e('Làm tour riêng', 'dulichhue'); ?></a></li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div class="footer-widget">
                    <h3 class="widget-title"><?php esc_html_e('Social', 'dulichvietnhat'); ?></h3>
                    <div class="social-icons">
                        <a class="social-btn facebook" href="<?php echo esc_url(get_theme_mod('social_facebook', '#')); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <i class="fab fa-facebook-f" aria-hidden="true"></i>
                        </a>
                        <a class="social-btn instagram" href="<?php echo esc_url(get_theme_mod('social_instagram', '#')); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <i class="fab fa-instagram" aria-hidden="true"></i>
                        </a>
                        <a class="social-btn youtube" href="<?php echo esc_url(get_theme_mod('social_youtube', '#')); ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                            <i class="fab fa-youtube" aria-hidden="true"></i>
                        </a>
                        <a class="social-btn zalo" href="<?php echo esc_url(get_theme_mod('social_zalo', '#')); ?>" target="_blank" rel="noopener noreferrer" aria-label="Zalo">
                            <i class="fas fa-comments" aria-hidden="true"></i>
                        </a>
                    </div>
                    <style>
                        .footer-widget .social-icons{display:flex;gap:14px;align-items:center;margin-top:12px}
                        .footer-widget .social-icons .social-btn{display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:12px;color:#fff;text-decoration:none;box-shadow:0 8px 20px rgba(0,0,0,.12);transition:transform .15s ease, box-shadow .15s ease}
                        .footer-widget .social-icons .social-btn:hover{transform:translateY(-2px);box-shadow:0 12px 24px rgba(0,0,0,.18)}
                        .footer-widget .social-icons .social-btn i{font-size:18px;line-height:1}
                        .footer-widget .social-icons .social-btn.facebook{background:#1877F2}
                        .footer-widget .social-icons .social-btn.youtube{background:#FF0000}
                        .footer-widget .social-icons .social-btn.zalo{background:#25D366}
                        .footer-widget .social-icons .social-btn.instagram{background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%)}
                    </style>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <div class="copyright">
                    <?php esc_html_e('Copyright Du Lịch Huế', 'dulichhue'); ?>
                </div>
                <div class="payment-methods">
                    <div class="payment-icons">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-discover"></i>
                        <i class="fab fa-cc-paypal"></i>
                        <i class="fab fa-cc-jcb"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="#" id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e('Lên đầu trang', 'dulichhue'); ?>">
        <i class="fas fa-arrow-up"></i>
    </a>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
