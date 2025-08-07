<?php

/**
 * The template for displaying archive pages
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<!-- Use the blog list template for blog archives -->
<?php if (is_post_type_archive('blog')) : ?>
    <?php get_template_part('templates/blog-list'); ?>
<?php else : ?>
    <main id="main" class="site-main">
        <div class="container">
            <div class="archive-header">
                <?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
                <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
            </div>

            <?php if (have_posts()) : ?>
                <div class="archive-content">
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
                </div>
            <?php else : ?>
                <div class="no-content">
                    <p><?php esc_html_e('No posts found.', 'sbs-portal'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </main>
<?php endif; ?>

<?php get_footer();
