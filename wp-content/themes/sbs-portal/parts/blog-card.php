<?php

/**
 * Blog Card Component
 * 
 * Individual small blog post card - aligned with Blog Card Large styles
 * Uses smaller image height and 2-line excerpt clamp (via CSS)
 *
 * @package SBS_Portal
 * @version 1.1.0
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

// Resolve image source (URL or theme asset fallback)
if (!empty($post['featured_image']) && filter_var($post['featured_image'], FILTER_VALIDATE_URL)) {
    $image_file = $post['featured_image'];
} else {
    $image_file = get_template_directory_uri() . '/assets/images/' . (!empty($post['featured_image']) ? $post['featured_image'] : 'blog-featured-1.jpg');
}

// Ensure blog-list.css is loaded (shared styles)
if (!wp_style_is('sbs-blog-list', 'enqueued')) {
    wp_enqueue_style('sbs-blog-list', get_template_directory_uri() . '/assets/css/blog-list.css', array(), '1.0.0');
}

$permalink = !empty($post['permalink']) ? $post['permalink'] : '#';
?>

<article class="blog-card h-100">
    <!-- Featured Image (smaller height) -->
    <div class="blog-card-image">
        <a href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($post['title']); ?>">
            <img src="<?php echo esc_url($image_file); ?>" alt="<?php echo esc_attr($post['title']); ?>" class="img-fluid" />
        </a>
    </div>

    <!-- Card Content -->
    <div class="blog-card-content d-flex flex-column h-100">
        <!-- Title -->
        <h3 class="blog-card-title">
            <a href="<?php echo esc_url($permalink); ?>" class="text-decoration-none">
                <?php echo esc_html($post['title']); ?>
            </a>
        </h3>

        <!-- Excerpt (max 2 lines via CSS) -->
        <div class="blog-card-excerpt flex-grow-1">
            <?php echo esc_html($post['excerpt']); ?>
        </div>

        <!-- Meta Information -->
        <div class="blog-card-meta mt-auto">
            <div class="meta-tags d-flex justify-content-between align-items-center">
                <span class="category-tag category-<?php echo strtolower($post['category'] ?? 'blog'); ?>">
                    <?php echo esc_html($post['category'] ?? 'BLOG'); ?>
                </span>
                <span class="date-tag">
                    <?php echo esc_html($post['date'] ?? ''); ?>
                </span>
            </div>
        </div>
    </div>
</article>