<?php
/**
 * The template for displaying archive tour pages
 *
 * @package WordPress
 * @subpackage Your_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('template-parts/content', 'tour'); ?>
            <?php endwhile; ?>
            <?php the_posts_navigation(); ?>
        <?php else : ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
?>
