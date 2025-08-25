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
// Get post data from query parameters (prefer ID)
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
$post_slug = isset($_GET['post_slug']) ? sanitize_text_field($_GET['post_slug']) : '';
$post_title_param = isset($_GET['post_title']) ? sanitize_text_field($_GET['post_title']) : '';

// Try to get the campaign post by ID first
$campaign_post = null;
if ($post_id > 0) {
    $campaign_post = get_post($post_id);
    if (!$campaign_post || $campaign_post->post_type !== 'campaign') {
        $campaign_post = null;
    }
}

// Fallback by slug if provided
if (!$campaign_post && !empty($post_slug)) {
    $campaign_post = get_page_by_path($post_slug, OBJECT, 'campaign');
}

// Final fallback by title for legacy links
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
        $post_content = $mock_post['content'] ?? __('Campaign content not available.', 'sbs-portal');
        $featured_image = get_template_directory_uri() . '/assets/images/' . $mock_post['featured_image'];
    } else {
        $post_title = __('Campaign Not Found', 'sbs-portal');
        $post_date = date('Y-m-d');
        $post_content = __('This campaign could not be found.', 'sbs-portal');
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
        'title' => __('Campaign Information', 'sbs-portal'),
        'subtitle' => __('Campaign', 'sbs-portal'),
        'show_navigation' => true
    ));
    ?>

    <section class="blog-detail-content">
        <div class="mb-5">
            <?php get_template_part('parts/breadcrumbs-section', null, array('breadcrumb_items' => array($post_title))); ?>
            <div class="row g-4">
                <!-- Left: Main Article -->
                <div class="col-md-9">
                    <div class="bg-white p-4 rounded-3" style="border: 1px solid #EAECEE;">
                        <div class="campaign-detail-img mb-4">
                            <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($post_title); ?>" />
                        </div>

                        <div class="mb-4">
                            <h4 class="fw-bold"><?php echo esc_html($post_title); ?></h4>
                            <?php
                            if ($campaign_post) {
                                $start_date = get_post_meta($campaign_post->ID, '_campaign_start_date', true);
                                $end_date = get_post_meta($campaign_post->ID, '_campaign_end_date', true);

                                if ($start_date || $end_date) {
                                    echo '<div class="campaign-active-period mb-3">';
                                    if ($start_date) {
                                        echo '<span class="period-label">' . __('From', 'sbs-portal') . ' ' . esc_html($start_date) . ' ' . '</span>';
                                    }
                                    if ($end_date) {
                                        echo '<span class="period-label">' . __('To', 'sbs-portal') . ' ' . esc_html($end_date) . '</span>';
                                    }
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>

                        <?php
                        // Show CTA at top position if configured
                        if ($campaign_post && sbs_should_show_cta_at_position($campaign_post->ID, 'top')) {
                            echo sbs_render_campaign_cta($campaign_post->ID, 'top');
                        }
                        ?>

                        <div class="mb-4">
                            <h5 class="fw-bold"><?php _e('Course', 'sbs-portal'); ?> <?php _e('Company Information', 'sbs-portal'); ?></h5>
                            <div class="campaign-text-block">
                                <?php
                                if ($campaign_post) {
                                    // If we have a real post object, set up post data and use the_content()
                                    // which handles <!--more--> tag and other formatting correctly.
                                    setup_postdata($campaign_post);

                                    // Force full content display, ignoring the <!--more--> tag for splitting
                                    global $more;
                                    $temp_more = $more;
                                    $more = 1;

                                    // Get content and potentially insert middle CTA
                                    $content = get_the_content();
                                    $content = apply_filters('the_content', $content);

                                    // If middle CTA is enabled, try to insert it in the middle of content
                                    if (sbs_should_show_cta_at_position($campaign_post->ID, 'middle')) {
                                        $paragraphs = explode('</p>', $content);
                                        $total_paragraphs = count($paragraphs);
                                        $middle_position = intval($total_paragraphs / 2);

                                        if ($total_paragraphs > 2) {
                                            // Normal case: insert right in the middle
                                            $before_middle = array_slice($paragraphs, 0, $middle_position);
                                            $after_middle = array_slice($paragraphs, $middle_position);

                                            echo implode('</p>', $before_middle) . '</p>';
                                            echo sbs_render_campaign_cta($campaign_post->ID, 'middle');
                                            echo implode('</p>', $after_middle);
                                        } elseif ($total_paragraphs > 1) {
                                            // Short content: insert after the first paragraph
                                            echo $paragraphs[0] . '</p>';
                                            echo sbs_render_campaign_cta($campaign_post->ID, 'middle');
                                            echo implode('</p>', array_slice($paragraphs, 1));
                                        } else {
                                            // Edge case: content has 0-1 paragraph markup; show CTA after content
                                            echo $content;
                                            echo sbs_render_campaign_cta($campaign_post->ID, 'middle');
                                        }
                                    } else {
                                        echo $content;
                                    }

                                    $more = $temp_more;
                                    wp_reset_postdata();
                                } else {
                                    // For mock data, we just apply the filter as before.
                                    echo apply_filters('the_content', $post_content);
                                }
                                ?>
                            </div>
                        </div>

                        <?php
                        // Show CTA at bottom position if configured
                        if ($campaign_post && sbs_should_show_cta_at_position($campaign_post->ID, 'bottom')) {
                            echo sbs_render_campaign_cta($campaign_post->ID, 'bottom');
                        }
                        ?>
                    </div>
                </div>

                <!-- Right: Sidebar -->
                <aside class="col-md-3 blog-detail-sidebar">
                    <div class="blog-detail-related">

                        <div class="related-posts-grid campaign-related-grid">
                            <?php
                            // Get 20 latest campaigns except current and render like blog related
                            $current_campaign_id = $campaign_post ? $campaign_post->ID : 0;
                            $rel_q = new WP_Query(array(
                                'post_type' => 'campaign',
                                'post_status' => 'publish',
                                'posts_per_page' => 20,
                                'post__not_in' => $current_campaign_id ? array($current_campaign_id) : array(),
                                'orderby' => 'date',
                                'order' => 'DESC',
                            ));

                            if ($rel_q->have_posts()):
                                while ($rel_q->have_posts()): $rel_q->the_post();
                                    $rp_id   = get_the_ID();
                                    $rp_title = get_the_title();
                                    $rp_date = get_the_date('Y-m-d');
                                    $rp_img  = get_the_post_thumbnail_url($rp_id, 'medium');
                                    if (!$rp_img) {
                                        $rp_img = get_template_directory_uri() . '/assets/images/campaign-detail.png';
                                    }
                                    $rp_link = add_query_arg('post_id', $rp_id, home_url('/campaign-detail/'));
                            ?>
                                    <div class="mb-3 campaign-related-item">
                                        <a href="<?php echo esc_url($rp_link); ?>" aria-label="<?php echo esc_attr($rp_title); ?>">
                                            <img src="<?php echo esc_url($rp_img); ?>" alt="<?php echo esc_attr($rp_title); ?>" class="img-fluid campaign-related-image" />
                                        </a>
                                    </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else:
                                echo '<div class="no-related-posts"><p>' . __('No Related Campaigns Found.', 'sbs-portal') . '</p></div>';
                            endif;
                            ?>
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