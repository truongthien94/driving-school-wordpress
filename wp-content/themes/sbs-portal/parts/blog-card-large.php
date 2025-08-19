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

// Always redirect to blog-detail.php template
$permalink = home_url('/blog-detail/');
if (!empty($post['id'])) {
    $permalink = add_query_arg('post_id', $post['id'], $permalink);
} elseif (!empty($post['slug'])) {
    $permalink = add_query_arg('post_slug', $post['slug'], $permalink);
} elseif (!empty($post['title'])) {
    $permalink = add_query_arg('post_title', urlencode($post['title']), $permalink);
}
?>

<article class="blog-card-large h-100">
    <a href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($post['title']); ?>" class="blog-card-link d-block h-100 text-decoration-none">
        <!-- Featured Image -->
        <div class="blog-card-large-image">
            <img src="<?php echo esc_url($image_file); ?>" alt="<?php echo esc_attr($post['title']); ?>" class="img-fluid" />
        </div>

        <!-- Card Content -->
        <div class="blog-card-large-content d-flex flex-column h-100 flex-grow-1">
            <!-- Title -->
            <h3 class="blog-card-large-title">
                <?php echo esc_html($post['title']); ?>
            </h3>

            <!-- Excerpt -->
            <div class="blog-card-large-excerpt mb-2 text-truncate-lines-5">
                <?php echo esc_html($post['excerpt']); ?>
            </div>

            <!-- Meta Information -->
            <div class="blog-card-large-meta mt-auto d-flex flex-column">
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
    </a>
</article>