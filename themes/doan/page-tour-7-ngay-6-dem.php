<?php
/**
 * Template Name: Tour 7 ngày 6 đêm - Thư viện ảnh
 * Description: Hiển thị tiêu đề trang và toàn bộ ảnh đã thêm cho trang (Gallery/Media attached). Không hiển thị bình luận.
 */

get_header();

$page_id = get_queried_object_id();

// Prefer gallery meta, fallback to attached images
$ids = function_exists('dln_get_gallery_image_ids') ? dln_get_gallery_image_ids($page_id) : array();

if (empty($ids)) {
    $attachments = get_children(array(
        'post_parent'    => $page_id,
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'orderby'        => 'menu_order ID',
        'order'          => 'ASC',
        'numberposts'    => -1,
    ));
    if ($attachments) {
        foreach ($attachments as $att) { $ids[] = intval($att->ID); }
    }
}
?>

<main id="primary" class="site-main">
    <section class="tour-gallery section-padding">
        <div class="container">
            <div class="section-header">
                <h1 class="section-title"><?php echo esc_html( get_the_title($page_id) ); ?></h1>
            </div>

            <?php if (!empty($ids)) : ?>
                <div class="tour-gallery-grid">
                    <?php foreach ($ids as $id) : ?>
                        <figure class="gallery-item">
                            <a href="<?php echo esc_url( wp_get_attachment_url($id) ); ?>" class="gallery-link" aria-label="<?php echo esc_attr( get_the_title($id) ); ?>">
                                <?php echo wp_get_attachment_image($id, 'large', false, array('class' => 'gallery-img', 'alt' => get_the_title($id))); ?>
                                <span class="hover-sweep" aria-hidden="true"></span>
                            </a>
                        </figure>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p><?php esc_html_e('Chưa có hình ảnh nào cho trang này.', 'doan'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
