<?php

/**
 * Blog Card Large Component
 * 
 * Larger blog post card for blog list page
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
    $image_file = get_template_directory_uri() . '/assets/images/' . (!empty($post['featured_image']) ? $post['featured_image'] : 'blog-featured-1-66030e.jpg');
}
?>

<article class="blog-card-large">
    <!-- Featured Image -->
    <div class="blog-card-large-image">
        <img src="<?php echo esc_url($image_file); ?>" alt="<?php echo esc_attr($post['title']); ?>" />
    </div>

    <!-- Card Content -->
    <div class="blog-card-large-content">
        <!-- Title -->
        <h3 class="blog-card-large-title">
            <a href="<?php echo get_permalink($post['id']); ?>">
                <?php echo esc_html($post['title']); ?>
            </a>
        </h3>

        <!-- Excerpt -->
        <div class="blog-card-large-excerpt">
            <?php echo esc_html($post['excerpt']); ?>
        </div>

        <!-- Meta Information -->
        <div class="blog-card-large-meta">
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