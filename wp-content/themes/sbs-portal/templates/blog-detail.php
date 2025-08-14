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

// Derive category and featured image for current post (for meta UI)
$primary_category = '';
$featured_image_url = '';
if ($post_data) {
    $featured_image_url = get_the_post_thumbnail_url($post_data->ID, 'medium_large');
    if (!$featured_image_url) {
        $featured_image_url = get_template_directory_uri() . '/assets/images/blog-default-large.jpg';
    }
    $terms = get_the_terms($post_data->ID, 'blog_category');
    if ($terms && !is_wp_error($terms)) {
        $primary_category = strtoupper($terms[0]->name);
    } else {
        $primary_category = 'BLOG';
    }
} else {
    $primary_category = 'BLOG';
}

// Debug: Log final data
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Blog Detail - Final post_title: ' . $post_title);
    error_log('Blog Detail - Final post_date: ' . $post_date);
}
?>
<?php get_header(); ?>

    <div class="sbs-blog-detail">
        <?php
        // Use reusable header section with blog detail specific parameters
        get_template_part('parts/header-section', null, array(
            'title' => 'ブログ',
            'subtitle' => 'BLOG and NEWS',
            'show_navigation' => true
        ));
        ?>

        <!-- Blog Detail Content Section -->
        <section class="blog-detail-content">
            <?php get_template_part('parts/breadcrumbs-section', null, array('breadcrumb_items' => array('ブログ一覧', $post_title))); ?>
            <div class="row g-4">
                <!-- Left Column: Main Article Content (2/3) -->
                <div class="col-lg-8">
                    <article class="blog-detail-article">
                        <!-- Article Metadata -->
                        <div class="blog-detail-meta">
                            <div class="d-flex align-items-center gap-2">
                                <?php if (!empty($primary_category)) : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon_clock.svg" alt="Category" />
                                <?php endif; ?>
                                <span class="blog-detail-date"><?php echo esc_html($post_date); ?></span>
                            </div>
                            <h2 class="blog-detail-title"><?php echo esc_html($post_title); ?></h2>
                        </div>

                        <?php if (!empty($featured_image_url)) : ?>
                            <div class="blog-detail-featured-image mb-4">
                                <img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php echo esc_attr($post_title); ?>" class="img-fluid w-100" />
                            </div>
                        <?php endif; ?>

                        <!-- Article Content -->
                        <div class="blog-detail-content-text">
                            <!-- Course Information Section -->
                            <div class="content-section mb-4">
                                <h2 class="content-heading">コース情報</h2>
                                <div class="content-body">
                                    <?php
                                    // Render full post content with WordPress filters to keep formatting
                                    echo apply_filters('the_content', $post_content);
                                    ?>
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
                <aside class="col-lg-4 blog-detail-sidebar">
                    <div class="blog-posts-main-grid">
                        <div class="related-posts-grid">
                            <?php
                            // Try to get 3 related posts from WP, excluding current
                            $current_id = $post_data ? $post_data->ID : 0;
                            $related_q = new WP_Query(array(
                                'post_type' => 'blog',
                                'post_status' => 'publish',
                                'posts_per_page' => 3,
                                'post__not_in' => $current_id ? array($current_id) : array(),
                                'orderby' => 'date',
                                'order' => 'DESC',
                            ));

                            if ($related_q->have_posts()):
                                while ($related_q->have_posts()): $related_q->the_post();
                                    $rp_id    = get_the_ID();
                                    $rp_title = get_the_title();
                                    $rp_date  = get_the_date('Y-m-d');
                                    $rp_img   = get_the_post_thumbnail_url($rp_id, 'medium');
                                    if (!$rp_img) {
                                        $rp_img = get_template_directory_uri() . '/assets/images/blog-default.jpg';
                                    }
                                    // Build permalink to blog-detail route
                                    $rp_link = add_query_arg('post_id', $rp_id, home_url('/blog-detail/'));
                                    // Determine category label (fallback BLOG)
                                    $rp_cat = 'BLOG';
                                    $rp_terms = get_the_terms($rp_id, 'blog_category');
                                    if ($rp_terms && !is_wp_error($rp_terms)) {
                                        $rp_cat = strtoupper($rp_terms[0]->name);
                                    }
                                    $rp_excerpt = get_the_excerpt();

                                    $card_post = array(
                                        'id' => $rp_id,
                                        'title' => $rp_title,
                                        'excerpt' => wp_trim_words($rp_excerpt, 20, '...'),
                                        'featured_image' => $rp_img,
                                        'date' => $rp_date,
                                        'category' => $rp_cat,
                                        'permalink' => $rp_link,
                                    );
                            ?>
                                    <div class="mb-3">
                                        <?php get_template_part('parts/blog-card-large', null, array('post' => $card_post)); ?>
                                    </div>
                                    <?php
                                endwhile;
                                wp_reset_postdata();
                            else:
                                // fallback: use existing helper
                                if (!empty($related_posts)) :
                                    foreach ($related_posts as $related_post) :
                                        $fallback_link = add_query_arg('post_title', urlencode($related_post['title']), home_url('/blog-detail/'));
                                        $fallback_img  = get_template_directory_uri() . '/assets/images/' . $related_post['featured_image'];
                                        $card_post = array(
                                            'id' => 0,
                                            'title' => $related_post['title'],
                                            'excerpt' => wp_trim_words($related_post['excerpt'] ?? '', 20, '...'),
                                            'featured_image' => $fallback_img,
                                            'date' => $related_post['date'],
                                            'category' => $related_post['category'] ?? 'BLOG',
                                            'permalink' => $fallback_link,
                                        );
                                    ?>
                                        <div class="mb-3">
                                            <?php get_template_part('parts/blog-card-large', null, array('post' => $card_post)); ?>
                                        </div>
                                    <?php
                                    endforeach;
                                else:
                                    ?>
                                    <div class="no-related-posts">
                                        <p>関連記事はありません。</p>
                                    </div>
                            <?php
                                endif;
                            endif;
                            ?>
                        </div>

                        <!-- View All Button -->
                        <div class="view-all-section">
                            <a class="sbs-btn-outline-sm text-decoration-none" href="<?php echo esc_url(get_post_type_archive_link('blog') ?: home_url('/blog/')); ?>">
                                <span>すべて表示</span>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="1" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <!-- Floating Elements -->
        <?php get_template_part('parts/float-buttons'); ?>
    </div>

    <!-- Footer Background for Blog Detail -->
    <div class="footer-background blog-list-footer" style="background-image: url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/footer-bg.jpg');"></div>

<?php get_footer(); ?>