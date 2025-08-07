<?php

/**
 * Blog Card Component
 * 
 * Individual blog post card - Enhanced with Bootstrap
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get post data from args
$post = isset($args['post']) ? $args['post'] : null;

if (!$post) {
    return;
}
?>

<article class="blog-card h-100">
    <!-- Featured Image -->
    <div class="blog-card-image">
        <?php
        // Use different images based on post ID for variety
        $image_file = ($post['id'] == 1 || $post['id'] == 3) ? 'blog-featured-1-66030e.jpg' : 'blog-featured-2.jpg';
        ?>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/<?php echo $image_file; ?>"
            alt="<?php echo esc_attr($post['title']); ?>" class="img-fluid" />
    </div>

    <!-- Card Content -->
    <div class="blog-card-content d-flex flex-column h-100">
        <!-- Title -->
        <h3 class="blog-card-title">
            <a href="/blog/<?php echo esc_attr($post['id']); ?>" class="text-decoration-none">
                <?php echo esc_html($post['title']); ?>
            </a>
        </h3>

        <!-- Excerpt -->
        <div class="blog-card-excerpt flex-grow-1">
            <?php echo esc_html($post['excerpt']); ?>
        </div>

        <!-- Meta Information -->
        <div class="blog-card-meta mt-auto">
            <div class="meta-tags d-flex gap-1">
                <span class="category-tag category-<?php echo strtolower($post['category']); ?>">
                    <?php echo esc_html($post['category']); ?>
                </span>
                <span class="date-tag">
                    <?php echo esc_html($post['date']); ?>
                </span>
            </div>
        </div>
    </div>
</article>