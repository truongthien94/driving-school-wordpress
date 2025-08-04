<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="blog-posts-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('parts/blog-card', null, array(
                        'post' => array(
                            'id' => get_the_ID(),
                            'title' => get_the_title(),
                            'excerpt' => get_the_excerpt(),
                            'featured_image' => get_the_post_thumbnail_url(get_the_ID(), 'sbs-blog-featured') ?: 'blog-featured-1-66030e.jpg',
                            'date' => get_the_date('Y-m-d'),
                            'category' => 'BLOG'
                        )
                    )); ?>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => 'Previous',
                'next_text' => 'Next',
            ));
            ?>
        <?php else : ?>
            <div class="no-content">
                <h1>Welcome to SBS Portal</h1>
                <p>No posts found. Please create some content or check if you're on the home page.</p>
                <a href="<?php echo home_url('/'); ?>" class="btn">Go to Home</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer();
