<?php

/**
 * Blog Detail Template
 * 
 * Custom single template for CPT `blog` matching the blog list/header design
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
    // Use reusable header section with blog detail specific parameters
    get_template_part('parts/header-section', null, array(
        'title' => 'ブログ',
        'subtitle' => 'BLOG and NEWS',
        'breadcrumb_items' => array('ブログ一覧', $post_title),
        'show_navigation' => true
    ));
    ?>

    <section class="blog-detail-content">
        <div class="container my-5">
            <div class="row g-4">
                <!-- Left: Main Article -->
                <div class="col-md-8">
                    <div class="bg-white p-4 rounded-3">
                        <div class="mb-4">
                            <span class="text-secondary"><?php echo esc_html($post_date); ?></span>
                            <h4><?php echo esc_html($post_title); ?></h4>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold">コース情報</h5>
                            <div>
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold">レッスン一覧</h5>
                            <div>
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Sidebar -->
                <aside class="col-md-4 blog-detail-sidebar">
                    <div class="blog-posts-main-grid">
                        <?php
                        // Always render 3 mock posts in the sidebar (vertical list)
                        $mock_blog_posts = sbs_get_latest_blog_posts(3);

                        if (!empty($mock_blog_posts)) :
                            foreach ($mock_blog_posts as $mock_post) :
                                $post_data = $mock_post;
                        ?>
                                <div class="mb-3">
                                    <?php get_template_part('parts/blog-card-large', null, array('post' => $post_data)); ?>
                                </div>
                            <?php endforeach;
                        else : ?>
                            <div class="no-blog-posts text-center py-5">
                                <p class="text-muted">現在、ブログ投稿はありません。</p>
                            </div>
                        <?php endif; ?>
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

<!-- Footer Background for Blog Detail -->
<div class="footer-background blog-list-footer" style="background-image: url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/footer-bg.jpg');"></div>