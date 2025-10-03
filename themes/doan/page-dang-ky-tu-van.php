<?php
/*
 * Template Name: Đăng ký tư vấn
 * Description: Trang form đăng ký tư vấn, hiển thị thông báo thành công sau khi gửi.
 */

/*
 * Page template: Đăng ký tư vấn (auto-loaded for slug dang-ky-tu-van)
 */

get_header();
?>
<style>
.consultation-page .consultation-section { padding: 56px 0 72px; background: #f8fafc; }
.consultation-page .section-header { text-align: center; margin-bottom: 28px; }
.consultation-page .section-title { font-size: 32px; font-weight: 800; color: #111827; margin: 0 0 8px; }
.consultation-page .section-title::after { content: ''; display: block; width: 80px; height: 4px; margin: 12px auto 0; background: linear-gradient(90deg,#f59e0b,#f97316); border-radius: 999px; }
.consultation-page .section-subtitle { margin: 8px auto 0; max-width: 720px; color: #6b7280; font-size: 16px; }

.consultation-page .consultation-card { max-width: 880px; margin: 24px auto 0; background: #fff; border-radius: 16px; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08); padding: 24px; border: 1px solid #eef2f7; }

.consultation-page .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
.consultation-page .form-group { display: flex; flex-direction: column; gap: 8px; }
.consultation-page .form-group--full { grid-column: 1 / -1; }

.consultation-page label { font-weight: 600; color: #374151; font-size: 14px; }
.consultation-page input[type="text"],
.consultation-page input[type="tel"],
.consultation-page input[type="email"],
.consultation-page select,
.consultation-page textarea { width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 10px; background: #fff; color: #111827; font-size: 15px; transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease; }
.consultation-page select { appearance: none; background-image: linear-gradient(45deg, transparent 50%, #9ca3af 50%), linear-gradient(135deg, #9ca3af 50%, transparent 50%); background-position: calc(100% - 18px) calc(50% - 2px), calc(100% - 12px) calc(50% - 2px); background-size: 6px 6px, 6px 6px; background-repeat: no-repeat; }
.consultation-page textarea { resize: vertical; min-height: 120px; }
.consultation-page input::placeholder,
.consultation-page textarea::placeholder { color: #9ca3af; }
.consultation-page input:focus,
.consultation-page input:focus-visible,
.consultation-page select:focus,
.consultation-page select:focus-visible,
.consultation-page textarea:focus,
.consultation-page textarea:focus-visible { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245, 158, 11, .15); }

.consultation-page .form-actions { text-align: center; margin-top: 10px; }
.consultation-page .btn-submit { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg,#f59e0b,#f97316); color: #fff; border: none; border-radius: 999px; padding: 12px 28px; font-weight: 700; cursor: pointer; letter-spacing: .2px; box-shadow: 0 12px 20px rgba(249, 115, 22, .25); transition: transform .12s ease, box-shadow .12s ease, filter .12s ease; }
.consultation-page .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 16px 28px rgba(249, 115, 22, .3); filter: brightness(1.02); }
.consultation-page .btn-submit:active { transform: translateY(0); box-shadow: 0 10px 20px rgba(249, 115, 22, .25); }

.consultation-page .notice {
  max-width: 880px; margin: 0 auto 16px; padding: 14px 16px; border-radius: 12px; border: 1px solid transparent; display: flex; align-items: flex-start; gap: 10px;
}
.consultation-page .notice i { margin-top: 2px; }
.consultation-page .notice-success { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
.consultation-page .notice-error { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
.consultation-page .notice strong { font-weight: 800; }

@media (max-width: 992px) {
  .consultation-page .consultation-card { padding: 20px; border-radius: 14px; }
}
@media (max-width: 768px) {
  .consultation-page .form-grid { grid-template-columns: 1fr; gap: 14px; }
  .consultation-page .section-title { font-size: 26px; }
}
@media (max-width: 480px) {
  .consultation-page .consultation-card { padding: 16px; border-radius: 12px; }
  .consultation-page .btn-submit { width: 100%; }
}
</style>
<main id="primary" class="site-main consultation-page">
    <section class="consultation-section">
        <div class="container">
            <header class="section-header">
                <h1 class="section-title"><?php esc_html_e('Đăng ký tư vấn', 'doan'); ?></h1>
                <p class="section-subtitle">
                    <?php esc_html_e('Vui lòng điền thông tin bên dưới. Chúng tôi sẽ liên hệ bạn trong thời gian sớm nhất.', 'doan'); ?>
                </p>
            </header>

            <?php
            $consult_status = isset($_GET['consult']) ? sanitize_text_field($_GET['consult']) : '';
            $consult_tour   = isset($_GET['tour']) ? sanitize_text_field( urldecode($_GET['tour']) ) : '';
            if ($consult_status === 'success') : ?>
                <div class="notice notice-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong><?php esc_html_e('Gửi đăng ký thành công!', 'doan'); ?></strong>
                        <div>
                            <?php echo esc_html__('Cảm ơn bạn. Chúng tôi đã nhận thông tin và sẽ liên hệ sớm nhất.', 'doan'); ?>
                            <?php if ($consult_tour) : ?>
                                <br><?php echo esc_html__('Tour quan tâm:', 'doan'); ?> <strong><?php echo esc_html($consult_tour); ?></strong>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php elseif ($consult_status === 'error') : ?>
                <div class="notice notice-error">
                    <i class="fas fa-triangle-exclamation"></i>
                    <div>
                        <strong><?php esc_html_e('Có lỗi xảy ra!', 'doan'); ?></strong>
                        <div><?php echo esc_html__('Vui lòng thử lại sau ít phút.', 'doan'); ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="consultation-card">
                <form class="consultation-form" method="post" action="">
                    <?php wp_nonce_field('contact_form_action', 'contact_form_nonce'); ?>
                    <input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>">
                    <?php $tour_selected = isset($_GET['tour']) ? sanitize_text_field( urldecode($_GET['tour']) ) : ''; ?>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="contact_name"><?php esc_html_e('Họ và tên', 'doan'); ?> *</label>
                            <input type="text" id="contact_name" name="contact_name" required placeholder="Nguyễn Văn A" />
                        </div>
                        <div class="form-group">
                            <label for="contact_phone"><?php esc_html_e('Số điện thoại', 'doan'); ?> *</label>
                            <input type="tel" id="contact_phone" name="contact_phone" required placeholder="09xx xxx xxx" />
                        </div>

                        <div class="form-group">
                            <label for="contact_email">Email</label>
                            <input type="email" id="contact_email" name="contact_email" placeholder="email@domain.com" />
                        </div>
                        <div class="form-group">
                            <label for="contact_tour"><?php esc_html_e('Tour quan tâm', 'doan'); ?></label>
                            <select id="contact_tour" name="contact_tour" required>
                                <option value="" disabled <?php echo $tour_selected ? '' : 'selected'; ?>><?php esc_html_e('Chọn tour...', 'doan'); ?></option>
                                <?php
                                $tour_args = array(
                                    'post_type'      => 'tour',
                                    'posts_per_page' => -1,
                                    'post_status'    => 'publish',
                                    'orderby'        => 'title',
                                    'order'          => 'ASC',
                                    'fields'         => 'ids',
                                );
                                $tour_ids = get_posts($tour_args);
                                if (!empty($tour_ids)) {
                                    foreach ($tour_ids as $tid) {
                                        $title = get_the_title($tid);
                                        $sel = selected($tour_selected, $title, false);
                                        echo '<option value="' . esc_attr($title) . '" ' . $sel . '>' . esc_html($title) . '</option>';
                                    }
                                } else {
                                    echo '<option value="" disabled>' . esc_html__('Chưa có tour nào', 'doan') . '</option>';
                                }
                                ?>
                                <option value="Khác" <?php echo selected($tour_selected, 'Khác', false); ?>><?php esc_html_e('Khác...', 'doan'); ?></option>
                            </select>
                        </div>

                        <div class="form-group form-group--full">
                            <label for="contact_message"><?php esc_html_e('Nội dung', 'doan'); ?></label>
                            <textarea id="contact_message" name="contact_message" rows="5" placeholder="Thông tin yêu cầu thêm..."></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <?php esc_html_e('Gửi đăng ký', 'doan'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
