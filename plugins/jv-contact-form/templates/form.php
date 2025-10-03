<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="jvcf-wrapper">
    <h3 class="jvcf-title"><?php echo esc_html( $context['title'] ); ?></h3>

    <form class="jvcf-form" method="post" novalidate>
        <div class="jvcf-row">
            <label>Họ và tên*</label>
            <input type="text" name="name" required placeholder="Nguyễn Văn A">
        </div>

        <div class="jvcf-row">
            <label>Email*</label>
            <input type="email" name="email" required placeholder="email@domain.com">
        </div>

        <div class="jvcf-row">
            <label>Điện thoại</label>
            <input type="tel" name="phone" placeholder="0367xxxxxx">
        </div>

        <div class="jvcf-row">
            <label>Điểm đến</label>
            <input type="text" name="destination" placeholder="Đà Nẵng, Nha Trang, Nhật Bản...">
        </div>

        <div class="jvcf-row">
            <label>Ngày khởi hành dự kiến</label>
            <input type="date" name="travel_date">
        </div>

        <div class="jvcf-row">
            <label>Tin nhắn</label>
            <textarea name="message" rows="4" placeholder="Nội dung yêu cầu tư vấn..."></textarea>
        </div>

        <div class="jvcf-row jvcf-consent">
            <label>
                <input type="checkbox" name="consent" value="1">
                Tôi đồng ý cho phép website lưu thông tin để tư vấn.
            </label>
        </div>

        <div class="jvcf-actions">
            <button type="submit" class="jvcf-submit">Gửi liên hệ</button>
            <span class="jvcf-status" aria-live="polite"></span>
        </div>
        <input type="hidden" name="action" value="jvcf_submit">
        <input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'jvcf_submit' ) ); ?>">
    </form>
</div>