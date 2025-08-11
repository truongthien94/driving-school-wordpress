<?php

/**
 * Blog Detail Template
 * 
 * Custom single template for CPT `blog` matching the Figma design
 * Layout: 2/3 content + 1/3 sidebar
 *
 * @package SBS_Portal
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue required CSS files
wp_enqueue_style('sbs-style', get_stylesheet_directory_uri() . '/style.css', array(), '1.0.0');
wp_enqueue_style('sbs-blog-list-style', get_stylesheet_directory_uri() . '/assets/css/blog-list.css', array('sbs-style'), '1.0.0');
wp_enqueue_style('sbs-blog-detail-style', get_stylesheet_directory_uri() . '/assets/css/blog-detail.css', array('sbs-style'), '1.0.0');

// Enqueue Bootstrap CSS if available
if (wp_style_is('bootstrap', 'registered')) {
    wp_enqueue_style('bootstrap');
} else {
    // Fallback to CDN Bootstrap
    wp_enqueue_style('bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), '5.3.0');
}

// Get post data from query parameters
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
$post_slug = isset($_GET['post_slug']) ? sanitize_text_field($_GET['post_slug']) : '';
$post_title_param = isset($_GET['post_title']) ? sanitize_text_field($_GET['post_title']) : '';

// Debug: Log the parameters
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Blog Detail - post_id: ' . $post_id);
    error_log('Blog Detail - post_slug: ' . $post_slug);
    error_log('Blog Detail - post_title_param: ' . $post_title_param);
}

// Try to get post data
$post_data = null;
$post_title = '';
$post_date = '';
$post_content = '';
$post_excerpt = '';

if ($post_id > 0) {
    // Try to get by ID
    $wp_post = get_post($post_id);
    if ($wp_post && $wp_post->post_type === 'blog') {
        $post_data = $wp_post;
        $post_title = $wp_post->post_title;
        $post_date = get_the_date('Y-m-d', $wp_post);
        $post_content = $wp_post->post_content;
        $post_excerpt = $wp_post->post_excerpt;

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Blog Detail - Found post by ID: ' . $post_title);
        }
    }
} elseif (!empty($post_slug)) {
    // Try to get by slug
    $wp_post = get_page_by_path($post_slug, OBJECT, 'blog');
    if ($wp_post) {
        $post_data = $wp_post;
        $post_title = $wp_post->post_title;
        $post_date = get_the_date('Y-m-d', $wp_post);
        $post_content = $wp_post->post_content;
        $post_excerpt = $wp_post->post_excerpt;

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Blog Detail - Found post by slug: ' . $post_title);
        }
    }
} elseif (!empty($post_title_param)) {
    // Try to get by title
    $wp_posts = get_posts(array(
        'post_type' => 'blog',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'title' => urldecode($post_title_param)
    ));
    if (!empty($wp_posts)) {
        $post_data = $wp_posts[0];
        $post_title = $post_data->post_title;
        $post_date = get_the_date('Y-m-d', $post_data);
        $post_content = $post_data->post_content;
        $post_excerpt = $post_data->post_excerpt;

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Blog Detail - Found post by title: ' . $post_title);
        }
    }
}

// If no post found, use fallback data
if (!$post_data) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Blog Detail - No post found, using fallback data');
    }

    $post_title = !empty($post_title_param) ? urldecode($post_title_param) : 'ブログ記事';
    $post_date = date('Y-m-d');
    $post_content = 'この記事の内容は準備中です。詳細情報は後日公開予定です。';
    $post_excerpt = '詳細情報は後日公開予定です。';
}

// Get related blog posts for sidebar
$related_posts = sbs_get_latest_blog_posts(3);

// Debug: Log final data
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Blog Detail - Final post_title: ' . $post_title);
    error_log('Blog Detail - Final post_date: ' . $post_date);
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

    <div class="sbs-blog-detail">
        <?php
        // Use reusable header section with blog detail specific parameters
        get_template_part('parts/header-section', null, array(
            'title' => 'ブログ',
            'subtitle' => 'BLOG and NEWS',
            'breadcrumb_items' => array('ポータル', 'ブログ一覧', $post_title),
            'show_navigation' => true
        ));
        ?>

        <!-- Blog Detail Content Section -->
        <section class="blog-detail-content">
            <div class="container">
                <div class="row g-4">
                    <!-- Left Column: Main Article Content (2/3) -->
                    <div class="col-lg-8">
                        <article class="blog-detail-article">
                            <!-- Article Metadata -->
                            <div class="blog-detail-meta">
                                <span class="blog-detail-date"><?php echo esc_html($post_date); ?></span>
                                <h1 class="blog-detail-title"><?php echo esc_html($post_title); ?></h1>
                            </div>

                            <!-- Article Content -->
                            <div class="blog-detail-content-text">
                                <!-- Course Information Section -->
                                <div class="content-section mb-4">
                                    <h2 class="content-heading">コース情報</h2>
                                    <div class="content-body">
                                        <?php echo wpautop(esc_html($post_content)); ?>
                                    </div>
                                </div>

                                <!-- Lesson List Section -->
                                <div class="content-section">
                                    <h2 class="content-heading">レッスン一覧</h2>
                                    <div class="content-body">
                                        <?php if ($post_excerpt): ?>
                                            <p><?php echo esc_html($post_excerpt); ?></p>
                                        <?php else: ?>
                                            <p>レッスンの詳細情報は準備中です。</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>

                    <!-- Right Column: Sidebar (1/3) -->
                    <div class="col-lg-4">
                        <aside class="blog-detail-sidebar">
                            <!-- Related Articles Section -->
                            <div class="blog-detail-related">
                                <h3 class="sidebar-title">関連記事</h3>
                                <div class="related-posts-grid">
                                    <?php if (!empty($related_posts)) : ?>
                                        <?php foreach ($related_posts as $related_post) : ?>
                                            <div class="related-post-card">
                                                <div class="related-post-image">
                                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/' . $related_post['featured_image']); ?>"
                                                        alt="<?php echo esc_attr($related_post['title']); ?>"
                                                        class="img-fluid" />
                                                </div>
                                                <div class="related-post-content">
                                                    <h4 class="related-post-title"><?php echo esc_html($related_post['title']); ?></h4>
                                                    <div class="related-post-meta">
                                                        <span class="related-post-date"><?php echo esc_html($related_post['date']); ?></span>
                                                        <span class="related-post-category"><?php echo esc_html($related_post['category']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="no-related-posts">
                                            <p>関連記事はありません。</p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- View All Button -->
                                <div class="view-all-section">
                                    <button type="button" class="sbs-btn-outline-sm">
                                        <span>すべて表示</span>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="1" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Staff Recruitment Section -->
                            <div class="staff-recruitment-section">
                                <div class="recruitment-tags">
                                    <span class="recruitment-tag tag-instruction">教習</span>
                                    <span class="recruitment-tag tag-office">事務</span>
                                    <span class="recruitment-tag tag-shuttle">送迎</span>
                                </div>
                                <h3 class="recruitment-title">スタッフ募集中</h3>
                                <p class="recruitment-subtitle">未経験歓迎</p>
                                <div class="recruitment-action">
                                    <span class="recruitment-text">詳細はこちら</span>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="1" />
                                    </svg>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </section>

        <!-- Floating Elements -->
        <?php get_template_part('parts/float-buttons'); ?>
    </div>

    <!-- Footer Background for Blog Detail -->
    <div class="footer-background blog-list-footer" style="background-image: url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/footer-bg.jpg');"></div>

    <!-- Enqueue Bootstrap JS -->
    <?php
    if (wp_script_is('bootstrap', 'registered')) {
        wp_enqueue_script('bootstrap');
    } else {
        // Fallback to CDN Bootstrap
        wp_enqueue_script('bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array(), '5.3.0', true);
    }
    ?>

    <?php wp_footer(); ?>
</body>

</html>