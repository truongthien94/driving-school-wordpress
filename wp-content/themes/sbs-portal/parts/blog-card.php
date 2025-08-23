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

// Normalize permalink to our blog-detail route
$permalink = home_url('/blog-detail/');
if (!empty($post['id'])) {
    $permalink = add_query_arg('post_id', $post['id'], $permalink);
} elseif (!empty($post['slug'])) {
    $permalink = add_query_arg('post_slug', $post['slug'], $permalink);
} elseif (!empty($post['title'])) {
    $permalink = add_query_arg('post_title', urlencode($post['title']), $permalink);
}
?>

<article class="blog-card h-100">
    <a href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($post['title']); ?>" class="blog-card-link d-block h-100 text-decoration-none">
        <!-- Featured Image (smaller height) -->
        <div class="blog-card-image">
            <img src="<?php echo esc_url($image_file); ?>" alt="<?php echo esc_attr($post['title']); ?>" class="img-fluid" />
        </div>

        <!-- Card Content -->
        <div class="blog-card-content d-flex flex-column h-100">
            <!-- Title -->
            <h3 class="blog-card-title">
                <?php echo esc_html($post['title']); ?>
            </h3>

            <!-- Excerpt (min 2 lines, clamp 2 lines) -->
            <div class="blog-card-excerpt mb-2">
                <?php echo esc_html($post['excerpt']); ?>
            </div>

            <!-- Meta Information -->
            <div class="blog-card-meta mt-auto">
                <div class="meta-tags d-flex justify-content-between align-items-center">
                    <?php
                    // Get category from post data and normalize it
                    $category_raw = !empty($post['category']) ? strtolower(trim($post['category'])) : 'blog';

                    // Map category values to translation keys (unique to avoid conflicts)
                    $category_mapping = array(
                        'blog'     => 'Blog Category',
                        'news'     => 'News Category',
                        'event'    => 'Event Category',
                        'campaign' => 'Campaign Category Label'
                    );

                    // Get translation key, fallback to Blog Category
                    $translation_key = isset($category_mapping[$category_raw]) ? $category_mapping[$category_raw] : 'Blog Category';

                    // Generate CSS class
                    $cat_class = 'category-' . $category_raw;

                    // Get translated label
                    $cat_label = esc_html__($translation_key, 'sbs-portal');
                    ?>
                    <span class="category-tag <?php echo esc_attr($cat_class); ?>">
                        <?php echo $cat_label; ?>
                    </span>
                    <span class="date-tag">
                        <?php echo esc_html($post['date'] ?? ''); ?>
                    </span>
                </div>
            </div>
        </div>
    </a>
</article>