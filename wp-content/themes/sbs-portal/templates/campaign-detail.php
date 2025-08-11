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

// Get current post data
$post_title = get_the_title();
$post_date = get_the_date('Y-m-d');
?>

<div class="sbs-blog-list">
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
                    <div class="bg-white p-4 rounded-3">
                        <div class="campaign-detail-img mb-4">
                            <?php if (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                            <?php else: ?>
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/campaign-detail.png" alt="campaign-detail-img" />
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <h4 class="fw-bold"><?php echo esc_html($post_title); ?></h4>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold">コース情報</h5>
                            <div class="campaign-text-block">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Sidebar -->
                <aside class="col-md-4 blog-detail-sidebar">
                    <div class="blog-posts-main-grid">
                        <?php
                        // Always render 4 mock campaign posts in the sidebar (vertical list)
                        $mock_campaign_posts = sbs_get_latest_campaign_posts(4);

                        if (!empty($mock_campaign_posts)) :
                            foreach ($mock_campaign_posts as $mock_post) :
                                $post_data = $mock_post;
                        ?>
                                <div class="mb-4">
                                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/<?php echo $post_data['featured_image']; ?>" alt="<?php echo esc_attr($post_data['title']); ?>" class="img-fluid" />
                                </div>
                        <?php endforeach;
                        endif; ?>
                    </div>

                    <div class="d-flex justify-content-center mt-2">
                        <button type="button" class="sbs-btn-outline-sm">
                            <span class="text-secondary d-inline-flex align-items-center gap-2">
                                <span>もっと見る</span>
                            </span>
                        </button>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <?php get_template_part('parts/float-buttons'); ?>
</div>

<!-- Footer Background for Campaign Detail -->
<div class="footer-background blog-list-footer" style="background-image: url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/footer-bg.jpg');"></div>