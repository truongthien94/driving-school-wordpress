<?php

/**
 * Related Posts Component
 * 
 * Displays related blog posts
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get current post categories
$categories = get_the_terms(get_the_ID(), 'blog_category');
$category_ids = array();

if ($categories && !is_wp_error($categories)) {
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
}

// Query related posts
$related_posts = new WP_Query(array(
    'post_type' => 'blog',
    'posts_per_page' => 3,
    'post__not_in' => array(get_the_ID()),
    'tax_query' => array(
        array(
            'taxonomy' => 'blog_category',
            'field' => 'term_id',
            'terms' => $category_ids,
            'operator' => 'IN'
        )
    ),
    'orderby' => 'rand'
));

if ($related_posts->have_posts()) : ?>
    <section class="related-posts">
        <h2 class="related-posts-title">Related Posts</h2>

        <div class="related-posts-grid">
            <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
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
    </section>

    <?php wp_reset_postdata(); ?>
<?php endif; ?>