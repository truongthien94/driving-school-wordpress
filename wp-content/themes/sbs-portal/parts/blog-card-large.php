<?php

/**
 * Blog Card Large Component
 * 
 * Larger blog post card for blog list page
 * Enhanced with proper padding, white background, and modern design
 * CSS styles are now in assets/css/blog-list.css for reusability
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

// Use different images based on post data
if (!empty($post['featured_image']) && filter_var($post['featured_image'], FILTER_VALIDATE_URL)) {
    $image_file = $post['featured_image'];
} else {
    // Use existing image files or default
    $image_file = get_template_directory_uri() . '/assets/images/' . (!empty($post['featured_image']) ? $post['featured_image'] : 'blog-featured-1.jpg');
}

// Enqueue the CSS file if not already loaded
if (!wp_style_is('sbs-blog-list', 'enqueued')) {
    wp_enqueue_style('sbs-blog-list', get_template_directory_uri() . '/assets/css/blog-list.css', array(), '1.0.0');
}
?>

<article class="blog-card-large h-100">
    <!-- Featured Image -->
    <div class="blog-card-large-image">
        <img src="<?php echo esc_url($image_file); ?>" alt="<?php echo esc_attr($post['title']); ?>" class="img-fluid" />
    </div>

    <!-- Card Content -->
    <div class="blog-card-large-content d-flex flex-column h-100">
        <!-- Title -->
        <h3 class="blog-card-large-title">
            <a href="<?php echo get_permalink($post['id']); ?>" class="text-decoration-none">
                <?php echo esc_html($post['title']); ?>
            </a>
        </h3>

        <!-- Excerpt -->
        <div class="blog-card-large-excerpt flex-grow-1 mb-2">
            <?php echo esc_html($post['excerpt']); ?>
        </div>

        <!-- Meta Information -->
        <div class="blog-card-large-meta mt-auto">
            <div class="meta-tags">
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