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

$blog_posts = sbs_get_latest_blog_posts(3);
?>

<div class="blog-section">
    <!-- Section Header -->
    <div class="section-header">
        <div class="header-content">
            <div class="header-title">
                <div class="logo-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-logo-small.png" alt="SBS Logo" />
                </div>
                <h2 class="section-title">ブログ</h2>
            </div>
            <p class="section-subtitle">BLOG and NEWS</p>
        </div>
        <div class="header-action">
            <a href="<?php echo get_post_type_archive_link('blog') ?: home_url('/blog-list/'); ?>" class="view-all-button">
                すべて表示
            </a>
        </div>
    </div>

    <!-- Blog Posts Grid -->
    <div class="blog-posts-grid">
        <?php if (!empty($blog_posts)): ?>
            <?php foreach ($blog_posts as $post): ?>
                <?php get_template_part('parts/blog-card', null, array('post' => $post)); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-posts">
                <p>No blog posts available yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>