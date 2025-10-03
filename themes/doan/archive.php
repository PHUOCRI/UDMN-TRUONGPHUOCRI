<?php
/**
 * The template for displaying archive pages
 */

get_header(); ?>

<div class="container">
    <div class="page-header">
        <?php
        the_archive_title('<h1 class="page-title">', '</h1>');
        the_archive_description('<div class="archive-description">', '</div>');
        ?>
    </div>

    <div class="tours-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('tour-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="tour-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('large', ['class' => 'tour-image']); ?>
                            </a>
                            <div class="tour-price">
                                <?php echo get_field('price') ? get_field('price') : 'Liên hệ'; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="tour-content">
                        <header class="entry-header">
                            <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
                            
                            <?php if (get_field('duration')) : ?>
                                <div class="tour-duration">
                                    <i class="far fa-clock"></i> <?php echo get_field('duration'); ?>
                                </div>
                            <?php endif; ?>
                        </header>

                        <div class="entry-content">
                            <?php 
                            the_excerpt();
                            ?>
                        </div>

                        <div class="tour-meta">
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php _e('Xem chi tiết', 'doan'); ?> <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>

            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('<i class="fas fa-chevron-left"></i>', 'doan'),
                    'next_text' => __('<i class="fas fa-chevron-right"></i>', 'doan'),
                ));
                ?>
            </div>

        <?php else : ?>
            <p><?php _e('Không có bài viết nào được tìm thấy.', 'doan'); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
