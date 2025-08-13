<?php

/**
 * Campaign Detail Template
 * 
 * Custom single template for CPT `campaign` matching the blog list/header design
 *
 * @package SBS_Portal
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Ensure header is loaded so that wp_head() prints enqueued CSS/JS
get_header();
// Get post data from query parameters
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
$post_slug = isset($_GET['post_slug']) ? sanitize_text_field($_GET['post_slug']) : '';
$post_title_param = isset($_GET['post_title']) ? sanitize_text_field($_GET['post_title']) : '';

// Try to get the campaign post
$campaign_post = null;
if ($post_id > 0) {
    $campaign_post = get_post($post_id);
    if (!$campaign_post || $campaign_post->post_type !== 'campaign') {
        $campaign_post = null;
    }
}

if (!$campaign_post && !empty($post_slug)) {
    $campaign_post = get_page_by_path($post_slug, OBJECT, 'campaign');
}

if (!$campaign_post && !empty($post_title_param)) {
    $posts = get_posts(array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'title' => $post_title_param,
    ));
    if (!empty($posts)) {
        $campaign_post = $posts[0];
    }
}

// Fallback to mock data if no post found
if (!$campaign_post) {
    $mock_campaign_posts = sbs_get_latest_campaign_posts(1);
    if (!empty($mock_campaign_posts)) {
        $mock_post = $mock_campaign_posts[0];
        $post_title = $mock_post['title'];
        $post_date = $mock_post['date'];
        $post_content = $mock_post['content'] ?? 'Campaign content not available.';
        $featured_image = get_template_directory_uri() . '/assets/images/' . $mock_post['featured_image'];
    } else {
        $post_title = 'Campaign Not Found';
        $post_date = date('Y-m-d');
        $post_content = 'This campaign could not be found.';
        $featured_image = get_template_directory_uri() . '/assets/images/campaign-detail.png';
    }
} else {
    $post_title = $campaign_post->post_title;
    $post_date = get_the_date('Y-m-d', $campaign_post->ID);
    $post_content = $campaign_post->post_content;
    $featured_image = has_post_thumbnail($campaign_post->ID) ?
        get_the_post_thumbnail_url($campaign_post->ID, 'large') :
        get_template_directory_uri() . '/assets/images/campaign-detail.png';
}

// Debug logging
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Campaign Detail - Post ID: ' . $post_id);
    error_log('Campaign Detail - Post Title: ' . $post_title);
    error_log('Campaign Detail - Campaign Post: ' . ($campaign_post ? 'Found' : 'Not Found'));
}
?>

<div class="sbs-blog-detail">
    <?php
    // Use reusable header section with campaign specific parameters
    get_template_part('parts/header-section', null, array(
        'title' => 'キャンペーン情報',
        'subtitle' => 'Campaign',
        'breadcrumb_items' => array('キャンペーン一覧', $post_title),
        'show_navigation' => true
    ));
    ?>

    <section class="blog-detail-content">
        <div class="container my-5">
            <div class="row g-4">
                <!-- Left: Main Article -->
                <div class="col-md-8">
                    <article class="blog-detail-article">
                        <div class="blog-detail-featured-image mb-4">
                            <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($post_title); ?>" class="img-fluid" />
                        </div>

                        <div class="blog-detail-meta mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 14.4c-3.5 0-6.4-2.9-6.4-6.4S4.5 1.6 8 1.6s6.4 2.9 6.4 6.4-2.9 6.4-6.4 6.4z" fill="#8E8E8E" />
                                    <path d="M8 3.2c-.4 0-.8.4-.8.8v3.2l2.4 1.6c.4.2.8 0 .8-.4V4.8c0-.4-.4-.8-.8-.8z" fill="#8E8E8E" />
                                </svg>
                                <span class="blog-detail-date"><?php echo esc_html($post_date); ?></span>
                            </div>
                            <h2 class="blog-detail-title"><?php echo esc_html($post_title); ?></h2>
                        </div>

                        <div class="blog-detail-content-text">
                            <?php echo apply_filters('the_content', $post_content); ?>
                        </div>
                    </article>
                </div>

                <!-- Right: Sidebar -->
                <aside class="col-md-4 blog-detail-sidebar">
                    <div class="blog-detail-related">
                        <h3 class="sidebar-title">関連キャンペーン</h3>
                        <div class="related-posts-grid">
                            <?php
                            // Get related campaign posts
                            $related_campaigns = get_posts(array(
                                'post_type' => 'campaign',
                                'post_status' => 'publish',
                                'posts_per_page' => 3,
                                'post__not_in' => $campaign_post ? array($campaign_post->ID) : array(),
                                'orderby' => 'date',
                                'order' => 'DESC',
                            ));

                            if (!empty($related_campaigns)) {
                                foreach ($related_campaigns as $related_campaign) {
                                    $card_post = array(
                                        'id' => $related_campaign->ID,
                                        'title' => $related_campaign->post_title,
                                        'excerpt' => wp_trim_words($related_campaign->post_excerpt ?: $related_campaign->post_content, 20),
                                        'featured_image' => has_post_thumbnail($related_campaign->ID) ?
                                            get_the_post_thumbnail_url($related_campaign->ID, 'medium') :
                                            get_template_directory_uri() . '/assets/images/campaign-detail.png',
                                        'date' => get_the_date('Y-m-d', $related_campaign->ID),
                                        'category' => 'CAMPAIGN',
                                        'permalink' => add_query_arg('post_id', $related_campaign->ID, home_url('/campaign-detail/')),
                                    );
                            ?>
                                    <div class="mb-3">
                                        <?php get_template_part('parts/blog-card', null, array('post' => $card_post)); ?>
                                    </div>
                                <?php
                                }
                            } else {
                                // Fallback to mock data
                                $mock_campaign_posts = sbs_get_latest_campaign_posts(3);
                                foreach ($mock_campaign_posts as $mock_post) {
                                    $card_post = array(
                                        'id' => 0,
                                        'title' => $mock_post['title'],
                                        'excerpt' => wp_trim_words($mock_post['content'] ?? 'Campaign content', 20),
                                        'featured_image' => get_template_directory_uri() . '/assets/images/' . $mock_post['featured_image'],
                                        'date' => $mock_post['date'],
                                        'category' => 'CAMPAIGN',
                                        'permalink' => '#',
                                    );
                                ?>
                                    <div class="mb-3">
                                        <?php get_template_part('parts/blog-card', null, array('post' => $card_post)); ?>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>

                        <div class="view-all-section text-center mt-4">
                            <a href="<?php echo esc_url(get_post_type_archive_link('campaign')); ?>" class="sbs-btn-outline-sm">
                                <span>もっと見る</span>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <?php get_template_part('parts/float-buttons'); ?>
</div>

<!-- Footer Background for Campaign Detail -->
<div class="footer-background blog-list-footer" style="background-image: url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/footer-bg.jpg');"></div>

<?php
// Ensure footer is loaded so that wp_footer() prints scripts and closing markup
get_footer();
?>