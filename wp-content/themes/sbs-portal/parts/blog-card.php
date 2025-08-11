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
        <?php if (!empty($post['featured_image'])): ?>
            <img src="<?php echo esc_url($post['featured_image']); ?>"
                alt="<?php echo esc_attr($post['title']); ?>" class="img-fluid" />
        <?php else: ?>
            <!-- Fallback image if no featured image is set -->
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/blog-featured-1.jpg"
                alt="<?php echo esc_attr($post['title']); ?>" class="img-fluid" />
        <?php endif; ?>
    </div>

    <!-- Card Content -->
    <div class="blog-card-content d-flex flex-column h-100">
        <!-- Title -->
        <h3 class="blog-card-title">
            <a href="<?php echo esc_url($post['permalink']); ?>" class="text-decoration-none">
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