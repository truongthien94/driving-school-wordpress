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
                <?php
                $post_id = get_the_ID();
                $terms = get_the_terms($post_id, 'blog_category');
                $cat_label = 'BLOG';
                if ($terms && !is_wp_error($terms)) {
                    $first = reset($terms);
                    if (!empty($first->name)) {
                        $cat_label = $first->name;
                    }
                } else {
                    $meta_cat = get_post_meta($post_id, '_blog_post_category', true);
                    if (!empty($meta_cat)) {
                        $cat_label = $meta_cat;
                    }
                }

                get_template_part('parts/blog-card', null, array(
                    'post' => array(
                        'id' => $post_id,
                        'title' => get_the_title(),
                        'excerpt' => get_the_excerpt(),
                        'featured_image' => get_the_post_thumbnail_url($post_id, 'sbs-blog-featured') ?: 'blog-featured-1.jpg',
                        'date' => get_the_date('Y-m-d'),
                        'category' => $cat_label
                    )
                )); ?>
            <?php endwhile; ?>
        </div>
    </section>

    <?php wp_reset_postdata(); ?>
<?php endif; ?>