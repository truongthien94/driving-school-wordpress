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

// Get blog posts data using the same function as blog-section.php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// Get all published blog posts (without pagination limit for now)
$all_blog_posts = sbs_get_blog_posts(-1); // Get all posts

// Apply pagination manually
$posts_per_page = 9; // 3x3 grid
$total_posts = count($all_blog_posts);
$max_pages = max(1, ceil($total_posts / $posts_per_page));
$paged = max(1, min($paged, $max_pages));

// Get posts for current page
$offset = ($paged - 1) * $posts_per_page;
$blog_posts = array_slice($all_blog_posts, $offset, $posts_per_page);

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div class="sbs-blog-list">
        <?php
        // Use reusable header section with blog-specific parameters
        get_template_part('parts/header-section', null, array(
            'title' => __('Blog', 'sbs-portal'),
            'subtitle' => __('BLOG and NEWS', 'sbs-portal'),
            'show_navigation' => true
        ));
        ?>

        <!-- Blog Content Section -->
        <section class="blog-list-content">
            <?php get_template_part('parts/breadcrumbs-section', null, array('breadcrumb_items' => array(__('Blog List', 'sbs-portal')))); ?>
            <div class="blog-posts-main-grid">
                <?php if ($blog_posts && !empty($blog_posts)) :
                    $post_count = 0;

                    // Use WordPress posts
                    foreach ($blog_posts as $post) :
                        $post_count++;

                        // Post data is already formatted by sbs_get_blog_posts function
                        $post_data = $post;
                ?>

                        <!-- Blog Post Row (every 3 posts) -->
                        <?php if ($post_count === 1 || ($post_count - 1) % 3 === 0) : ?>
                            <div class="row mb-4">
                            <?php endif; ?>

                            <div class="col-lg-4">
                                <?php get_template_part('parts/blog-card-large', null, array('post' => $post_data)); ?>
                            </div>

                            <?php if ($post_count % 3 === 0 || $post_count === count($blog_posts)) : ?>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; ?>

                    <!-- Pagination Section - Only show if there are multiple pages -->
                    <?php if ($max_pages > 1) : ?>
                        <div class="blog-pagination-section mt-5">
                            <div class="pagination-wrapper d-flex justify-content-center align-items-center">
                                <div class="pagination-info me-4">
                                    <span class="page-info">
                                        <?php
                                        echo $paged . '/' . $max_pages . __('page', 'sbs-portal');
                                        ?>
                                    </span>
                                </div>

                                <div class="pagination-controls d-flex gap-2">
                                    <?php
                                    $prev_page = $paged > 1 ? $paged - 1 : null;
                                    $next_page = $paged < $max_pages ? $paged + 1 : null;
                                    ?>

                                    <!-- First Page Button (double left arrow) -->
                                    <button class="pagination-btn pagination-first <?php echo $paged === 1 ? 'disabled' : ''; ?>"
                                        <?php if ($paged > 1): ?>onclick="location.href='<?php echo home_url('/blog-list/'); ?>'" <?php endif; ?>>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M12 11.33L8.67 8L12 4.67" stroke="currentColor" stroke-width="0.83" />
                                            <path d="M7.33 11.33L4 8L7.33 4.67" stroke="currentColor" stroke-width="0.83" />
                                        </svg>
                                    </button>

                                    <!-- Previous Page Button (single left arrow) -->
                                    <button class="pagination-btn pagination-prev <?php echo !$prev_page ? 'disabled' : ''; ?>"
                                        <?php if ($prev_page): ?>onclick="location.href='<?php echo add_query_arg('paged', $prev_page, home_url('/blog-list/')); ?>'" <?php endif; ?>>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M6 4L2 8L6 12" stroke="currentColor" stroke-width="1" />
                                        </svg>
                                    </button>

                                    <!-- Next Page Button (single right arrow) -->
                                    <button class="pagination-btn pagination-next <?php echo !$next_page ? 'disabled' : ''; ?>"
                                        <?php if ($next_page): ?>onclick="location.href='<?php echo add_query_arg('paged', $next_page, home_url('/blog-list/')); ?>'" <?php endif; ?>>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="1" />
                                        </svg>
                                    </button>

                                    <!-- Last Page Button (double right arrow) -->
                                    <button class="pagination-btn pagination-last <?php echo $paged === $max_pages ? 'disabled' : ''; ?>"
                                        <?php if ($paged < $max_pages): ?>onclick="location.href='<?php echo add_query_arg('paged', $max_pages, home_url('/blog-list/')); ?>'" <?php endif; ?>>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M4 4.67L7.33 8L4 11.33" stroke="currentColor" stroke-width="0.83" />
                                            <path d="M8.67 4.67L12 8L8.67 11.33" stroke="currentColor" stroke-width="0.83" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="no-blog-posts text-center py-5">
                        <p class="text-muted"><?php _e('Currently, there are no blog posts.', 'sbs-portal'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Floating Elements -->
        <?php get_template_part('parts/float-buttons'); ?>
    </div>

    <!-- Footer Background for Blog List -->
    <div class="footer-background blog-list-footer" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/footer-bg.jpg');"></div>

    <?php wp_footer(); ?>
</body>

</html>