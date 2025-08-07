<?php

/**
 * Blog List Template
 * 
 * Template for displaying blog list page based on Figma design
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="sbs-blog-list">
    <!-- Blog List Header Section -->
    <section class="sbs-banner-carousel-section">
        <div class="banner-carousel-container">
            <!-- Frame 1948758074 - Main Container -->
            <div class="blog-header-main-frame">

                <!-- Frame 1948758070 - Title Section -->
                <div class="blog-header-title-frame">
                    <!-- Frame 1948758069 - Title Container with gap -->
                    <div class="blog-header-title-container">
                        <h1 class="blog-header-title">ブログ</h1>
                    </div>
                    <p class="blog-header-subtitle">BLOG and NEWS</p>
                </div>

                <!-- Mega Search Box -->
                <div class="blog-header-search-box">
                    <div class="blog-search-logo">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/sbs-logo-dark.png" alt="SBS Logo" />
                    </div>
                </div>
            </div>

            <!-- Top Navigation Bar (positioned absolute) -->
            <div class="blog-top-navigation">
                <div class="nav-menu-items">
                    <a href="<?php echo home_url('/about/'); ?>" class="nav-menu-item">ごあいさつ</a>
                    <a href="<?php echo home_url('/company/'); ?>" class="nav-menu-item">企業情報</a>
                    <a href="<?php echo home_url('/group/'); ?>" class="nav-menu-item">SBSグループについて</a>
                    <div class="nav-language-selector">
                        <span>日本語</span>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M4 6L8 10L12 6" stroke="currentColor" />
                        </svg>
                    </div>
                </div>
                <div class="nav-menu-button">
                    <span>Menu</span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M3 6H21M3 12H21M3 18H21" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Content Section -->
    <section class="blog-list-content">
        <div class="container">
            <!-- Blog Posts Grid -->
            <div class="blog-posts-main-grid">
                <?php
                // Get blog posts - try multiple methods
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;

                // First try to get from WordPress posts
                $blog_posts = new WP_Query(array(
                    'post_type' => 'blog',
                    'posts_per_page' => 9, // 3x3 grid
                    'paged' => $paged,
                    'post_status' => 'publish'
                ));

                // If no posts found, fall back to mock data
                $use_mock_data = false;
                if (!$blog_posts->have_posts()) {
                    $use_mock_data = true;
                    $mock_blog_posts = sbs_get_latest_blog_posts(9);
                }

                if ($blog_posts->have_posts() || $use_mock_data) :
                    $post_count = 0;
                    $total_posts = $use_mock_data ? count($mock_blog_posts) : $blog_posts->post_count;

                    if ($use_mock_data) :
                        // Use mock data
                        foreach ($mock_blog_posts as $mock_post) :
                            $post_count++;
                            $post_data = $mock_post;
                ?>

                            <!-- Blog Post Row (every 3 posts) -->
                            <?php if ($post_count === 1 || ($post_count - 1) % 3 === 0): ?>
                                <div class="blog-posts-row">
                                <?php endif; ?>

                                <?php get_template_part('parts/blog-card-large', null, array('post' => $post_data)); ?>

                                <?php if ($post_count % 3 === 0 || $post_count === $total_posts): ?>
                                </div>
                            <?php endif; ?>

                        <?php endforeach;
                    else :
                        // Use WordPress posts
                        while ($blog_posts->have_posts()) : $blog_posts->the_post();
                            $post_count++;

                            // Create post data array
                            $post_data = array(
                                'id' => get_the_ID(),
                                'title' => get_the_title(),
                                'excerpt' => get_the_excerpt(),
                                'featured_image' => get_the_post_thumbnail_url(get_the_ID(), 'sbs-blog-featured'),
                                'date' => get_the_date('Y-m-d'),
                                'category' => 'BLOG' // Default to BLOG, can be enhanced with taxonomy
                            );

                            // Determine if it's a NEWS post based on categories
                            $categories = get_the_terms(get_the_ID(), 'blog_category');
                            if ($categories && !is_wp_error($categories)) {
                                foreach ($categories as $category) {
                                    if (strtolower($category->name) === 'news') {
                                        $post_data['category'] = 'NEWS';
                                        break;
                                    }
                                }
                            }
                        ?>

                            <!-- Blog Post Row (every 3 posts) -->
                            <?php if ($post_count === 1 || ($post_count - 1) % 3 === 0): ?>
                                <div class="blog-posts-row">
                                <?php endif; ?>

                                <?php get_template_part('parts/blog-card-large', null, array('post' => $post_data)); ?>

                                <?php if ($post_count % 3 === 0 || $post_count === $blog_posts->post_count): ?>
                                </div>
                            <?php endif; ?>

                    <?php endwhile;
                    endif; ?>

                    <!-- Pagination Section -->
                    <div class="blog-pagination-section">
                        <div class="pagination-wrapper">
                            <div class="pagination-info">
                                <span class="page-info">
                                    <?php
                                    $max_pages = $use_mock_data ? 1 : ($blog_posts->max_num_pages ?: 1);
                                    echo $paged . '/' . $max_pages . 'ページ';
                                    ?>
                                </span>
                            </div>

                            <div class="pagination-controls">
                                <?php
                                $prev_page = $paged > 1 ? $paged - 1 : null;
                                $next_page = $paged < $max_pages ? $paged + 1 : null;
                                ?>

                                <!-- First Page Button (chevrons-left) -->
                                <button class="pagination-btn pagination-first <?php echo $paged === 1 ? 'disabled' : ''; ?>"
                                    <?php if ($paged > 1): ?>onclick="location.href='<?php echo get_post_type_archive_link('blog') ?: home_url('/blog-list/'); ?>'" <?php endif; ?>>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M4 4.67L7.33 8L4 11.33" stroke="currentColor" stroke-width="0.83" />
                                        <path d="M8.67 4.67L12 8L8.67 11.33" stroke="currentColor" stroke-width="0.83" />
                                    </svg>
                                </button>

                                <!-- Previous Page Button (chevron-left) -->
                                <button class="pagination-btn pagination-prev <?php echo !$prev_page ? 'disabled' : ''; ?>"
                                    <?php if ($prev_page): ?>onclick="location.href='<?php echo get_pagenum_link($prev_page); ?>'" <?php endif; ?>>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M6 4L2 8L6 12" stroke="currentColor" stroke-width="1" />
                                    </svg>
                                </button>

                                <!-- Next Page Button (chevron-right) -->
                                <button class="pagination-btn pagination-next <?php echo !$next_page ? 'disabled' : ''; ?>"
                                    <?php if ($next_page): ?>onclick="location.href='<?php echo get_pagenum_link($next_page); ?>'" <?php endif; ?>>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="1" />
                                    </svg>
                                </button>

                                <!-- Last Page Button (chevrons-right) -->
                                <button class="pagination-btn pagination-last <?php echo $paged === $max_pages ? 'disabled' : ''; ?>"
                                    <?php if ($paged < $max_pages): ?>onclick="location.href='<?php echo get_pagenum_link($max_pages); ?>'" <?php endif; ?>>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M4 4.67L7.33 8L4 11.33" stroke="currentColor" stroke-width="0.83" />
                                        <path d="M8.67 4.67L12 8L8.67 11.33" stroke="currentColor" stroke-width="0.83" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <?php wp_reset_postdata(); ?>

                <?php else: ?>
                    <div class="no-blog-posts">
                        <p>現在、ブログ投稿はありません。</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Floating Elements -->
    <?php get_template_part('parts/float-buttons'); ?>

    <!-- Breadcrumbs -->
    <div class="blog-breadcrumbs">
        <div class="breadcrumb-list">
            <div class="breadcrumb-item">
                <a href="<?php echo home_url('/'); ?>" class="breadcrumb-link">ポータル</a>
            </div>
            <div class="breadcrumb-separator">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M6.4 3.2L11.07 8L6.4 12.8" stroke="currentColor" />
                </svg>
            </div>
            <div class="breadcrumb-item">
                <span class="breadcrumb-current">ブログ一覧</span>
            </div>
        </div>
    </div>
</div>

<!-- Footer Background for Blog List -->
<div class="footer-background blog-list-footer" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/footer-bg.jpg');"></div>