<?php

/**
 * Blog Section
 * 
 * Displays latest blog posts
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get blog posts from database using the new function
$blog_posts = sbs_get_blog_posts(6); // Get 6 posts for display
?>

<div class="blog-section">
    <!-- Section Header -->
    <div class="section-header d-flex justify-content-between align-items-end my-4">
        <div class="header-content d-flex flex-column gap-2">
            <div class="header-title d-flex align-items-center gap-3">
                <div class="logo-icon d-flex align-items-center justify-content-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-logo-small.png" alt="SBS Logo" class="img-fluid" />
                </div>
                <h2 class="section-title mb-0"><?php _e('Blog', 'sbs-portal'); ?></h2>
            </div>
            <p class="section-subtitle mb-0">BLOG and NEWS</p>
        </div>
        <div class="header-action">
            <a href="<?php echo esc_url(get_post_type_archive_link('blog') ?: home_url('/blog-list/')); ?>" class="view-all-button btn btn-outline-secondary btn-sm text-decoration-none">
                <?php _e('Show All', 'sbs-portal'); ?>
            </a>
        </div>
    </div>

    <!-- Blog Posts Grid (match blog-list.php structure) -->
    <div class="blog-posts-main-grid">
        <?php if (!empty($blog_posts)):
            $post_count = 0;
            $total_posts = count($blog_posts);
            foreach ($blog_posts as $post):
                $post_count++;
        ?>
                <!-- Blog Post Row (every 3 posts) -->
                <?php if ($post_count === 1 || ($post_count - 1) % 3 === 0): ?>
                    <div class="row g-3 mb-4">
                    <?php endif; ?>

                    <div class="col-md-4">
                        <?php get_template_part('parts/blog-card-large', null, array('post' => $post)); ?>
                    </div>

                    <?php if ($post_count % 3 === 0 || $post_count === $total_posts): ?>
                    </div>
                <?php endif; ?>
            <?php endforeach;
        else: ?>
            <div class="no-posts text-center py-5">
                <p class="text-muted mb-0">No blog posts available yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>