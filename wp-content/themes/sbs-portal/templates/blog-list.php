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

// Get blog posts data
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
            'title' => 'ブログ',
            'subtitle' => 'BLOG and NEWS',
            'show_navigation' => true
        ));
        ?>

        <!-- Blog Content Section -->
        <section class="blog-list-content">
            <?php get_template_part('parts/breadcrumbs-section', null, array('breadcrumb_items' => array('ブログ一覧'))); ?>
            <div class="blog-posts-main-grid">
                    <?php if ($blog_posts->have_posts() || $use_mock_data) :
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
                                    <div class="row g-3 mb-4">
                                    <?php endif; ?>

                                    <div class="col-md-4">
                                        <?php get_template_part('parts/blog-card-large', null, array('post' => $post_data)); ?>
                                    </div>

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
                                    <div class="row g-3 mb-4">
                                    <?php endif; ?>

                                    <div class="col-md-4">
                                        <?php get_template_part('parts/blog-card-large', null, array('post' => $post_data)); ?>
                                    </div>

                                    <?php if ($post_count % 3 === 0 || $post_count === $blog_posts->post_count): ?>
                                    </div>
                                <?php endif; ?>

                        <?php endwhile;
                        endif; ?>

                        <!-- Pagination Section -->
                        <div class="blog-pagination-section mt-5">
                            <div class="pagination-wrapper d-flex justify-content-center align-items-center">
                                <div class="pagination-info me-4">
                                    <span class="page-info">
                                        <?php
                                        $max_pages = $use_mock_data ? 1 : ($blog_posts->max_num_pages ?: 1);
                                        echo $paged . '/' . $max_pages . 'ページ';
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
                                        <?php if ($paged > 1): ?>onclick="location.href='<?php echo get_post_type_archive_link('blog') ?: home_url('/blog-list/'); ?>'" <?php endif; ?>>
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M12 11.33L8.67 8L12 4.67" stroke="currentColor" stroke-width="0.83" />
                                                <path d="M7.33 11.33L4 8L7.33 4.67" stroke="currentColor" stroke-width="0.83" />
                                            </svg>
                                    </button>

                                    <!-- Previous Page Button (single left arrow) -->
                                    <button class="pagination-btn pagination-prev <?php echo !$prev_page ? 'disabled' : ''; ?>"
                                        <?php if ($prev_page): ?>onclick="location.href='<?php echo get_pagenum_link($prev_page); ?>'" <?php endif; ?>>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M6 4L2 8L6 12" stroke="currentColor" stroke-width="1" />
                                        </svg>
                                    </button>

                                    <!-- Next Page Button (single right arrow) -->
                                    <button class="pagination-btn pagination-next <?php echo !$next_page ? 'disabled' : ''; ?>"
                                        <?php if ($next_page): ?>onclick="location.href='<?php echo get_pagenum_link($next_page); ?>'" <?php endif; ?>>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="1" />
                                        </svg>
                                    </button>

                                    <!-- Last Page Button (double right arrow) -->
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
                        <div class="no-blog-posts text-center py-5">
                            <p class="text-muted">現在、ブログ投稿はありません。</p>
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