<?php
/**
 * Template part for displaying tour items
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('tour-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="tour-thumbnail">
            <a href="<?php the_permalink(); ?>" class="tour-image-link">
                <?php the_post_thumbnail('tour-thumbnail', array('class' => 'tour-image')); ?>
                <?php if ($price = get_field('price')) : ?>
                    <span class="tour-price"><?php echo esc_html($price); ?></span>
                <?php endif; ?>
            </a>
            <?php if ($discount = get_field('discount')) : ?>
                <span class="tour-discount">-<?php echo esc_html($discount); ?>%</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="tour-content">
        <header class="tour-header">
            <?php the_title(sprintf('<h3 class="tour-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>'); ?>
            
            <?php if ($duration = get_field('duration')) : ?>
                <div class="tour-duration">
                    <i class="far fa-clock"></i> <?php echo esc_html($duration); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($departure = get_field('departure')) : ?>
                <div class="tour-departure">
                    <i class="fas fa-map-marker-alt"></i> <?php echo esc_html($departure); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="tour-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <div class="tour-meta">
            <div class="tour-rating">
                <?php
                $rating = get_field('rating') ? get_field('rating') : 5;
                for ($i = 1; $i <= 5; $i++) :
                    echo $i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                endfor;
                ?>
                <span class="review-count">(<?php echo get_comments_number(); ?>)</span>
            </div>
            
            <a href="<?php the_permalink(); ?>" class="btn-tour-detail">
                <?php _e('Xem chi tiết', 'dulichvietnhat'); ?>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</article>
