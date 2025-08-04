<?php

/**
 * The template for displaying all single posts
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
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

                    <div class="entry-meta">
                        <span class="posted-on">
                            <?php echo get_the_date(); ?>
                        </span>

                        <?php if (get_post_type() === 'blog') : ?>
                            <?php
                            $categories = get_the_terms(get_the_ID(), 'blog_category');
                            if ($categories && !is_wp_error($categories)) :
                            ?>
                                <span class="categories">
                                    <?php foreach ($categories as $category) : ?>
                                        <span class="category-tag"><?php echo esc_html($category->name); ?></span>
                                    <?php endforeach; ?>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="entry-featured-image">
                        <?php the_post_thumbnail('sbs-blog-featured'); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'sbs-portal'),
                        'after'  => '</div>',
                    ));
                    ?>
                </footer>
            </article>

            <?php
            // Related posts
            if (get_post_type() === 'blog') :
                get_template_part('parts/related-posts');
            endif;
            ?>

            <?php
            // Comments
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer();
