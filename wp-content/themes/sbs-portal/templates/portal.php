<?php

/**
 * Portal Template
 * 
 * Main template for the SBS Portal homepage - Restructured into 4 clear sections
 *
 * @package SBS_Portal
 * @version 2.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="sbs-portal">
    <!-- SECTION 1: Hero Section -->
    <section class="sbs-hero-section" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero-bg-main-f14c9b.jpg');">
        <!-- Background Overlay -->
        <div class="hero-overlay"></div>

        <div class="hero-navigation row-gap-0 p-0 row py-4 flex justify-content-end">
            <div class="col-xl-6">
                <?php get_template_part('parts/portal-navigation'); ?>
            </div>
        </div>

        <div class="row hero-container row-gap-0 p-0">
            <div class="col-xl-6 image-container">
                <div class="hero-left-column d-flex align-items-center justify-content-center">
                    <div class="hero-circle-image floating-animation position-absolute">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-circle.jpg" alt="Hero Circle" class="img-fluid rounded-circle" />
                    </div>
                    <div class="hero-logo-strip position-absolute d-flex align-items-center justify-content-center">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-logo-strip.png" alt="SBS Logo Strip" class="img-fluid" />
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <?php
                // Get hero items from database
                $hero_items = sbs_get_hero_items(7);

                if (!empty($hero_items)) {
                    // First item (main item) - Hàng 1
                    $main_item = array_shift($hero_items);
                ?>
                    <div class="py-2">
                        <div class="portal-box">
                            <div class="d-flex align-items-center gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon($main_item['icon'] ?: 'car'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">
                                        <?php if ($main_item['link']) : ?>
                                            <a href="<?php echo esc_url($main_item['link']); ?>">
                                                <?php echo esc_html($main_item['title']); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo esc_html($main_item['title']); ?>
                                        <?php endif; ?>
                                    </h3>
                                    <p class="box-description mb-0">
                                        <?php echo esc_html($main_item['description'] ?: 'Add description'); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="box-line"></div>
                        </div>
                    </div>

                    <?php if (!empty($hero_items)) : ?>
                        <!-- Hàng 2: Items 2-3 (2 cột) -->
                        <div class="row row-gap-0 p-0">
                            <?php
                            $row2_items = array_slice($hero_items, 0, 2);
                            foreach ($row2_items as $item) {
                            ?>
                                <div class="col-xl-6 py-2">
                                    <div class="portal-box">
                                        <div class="box-header d-flex align-items-center gap-3">
                                            <div class="box-icon flex-shrink-0">
                                                <?php echo sbs_get_icon($item['icon'] ?: 'bus'); ?>
                                            </div>
                                            <div class="box-info flex-grow-1">
                                                <h3 class="box-title mb-1">
                                                    <?php if ($item['link']) : ?>
                                                        <a href="<?php echo esc_url($item['link']); ?>" class="text-decoration-none">
                                                            <?php echo esc_html($item['title']); ?>
                                                        </a>
                                                    <?php else : ?>
                                                        <?php echo esc_html($item['title']); ?>
                                                    <?php endif; ?>
                                                </h3>
                                                <p class="box-description mb-0">
                                                    <?php echo esc_html($item['description'] ?: 'Add description'); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="box-line position-absolute"></div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                <?php } else {
                    // Fallback to static content if no hero items exist
                ?>
                    <div class="py-2">
                        <div class="portal-box">
                            <div class="d-flex align-items-center gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon('car'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">SBS自動車</h3>
                                    <p class="box-description mb-0">Add description</p>
                                </div>
                            </div>
                            <div class="box-line"></div>
                        </div>
                    </div>
                    <div class="row row-gap-0 p-0">
                        <div class="col-xl-6 py-2">
                            <div class="portal-box">
                                <div class="box-header d-flex align-items-center gap-3">
                                    <div class="box-icon flex-shrink-0">
                                        <?php echo sbs_get_icon('bus'); ?>
                                    </div>
                                    <div class="box-info flex-grow-1">
                                        <h3 class="box-title mb-1">SBSドライビングスクール姉崎</h3>
                                        <p class="box-description mb-0">Add description</p>
                                    </div>
                                </div>
                                <div class="box-line position-absolute"></div>
                            </div>
                        </div>
                        <div class="col-xl-6 py-2">
                            <div class="portal-box">
                                <div class="box-header d-flex align-items-center gap-3">
                                    <div class="box-icon flex-shrink-0">
                                        <?php echo sbs_get_icon('bus'); ?>
                                    </div>
                                    <div class="box-info flex-grow-1">
                                        <h3 class="box-title mb-1">SBSドライビングスクール稲毛</h3>
                                        <p class="box-description mb-0">Add description</p>
                                    </div>
                                </div>
                                <div class="box-line position-absolute"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="row portal-box-container hero-container row-gap-0 ">
            <?php
            // Get all hero items for the bottom row (items 4-7)
            $all_hero_items = sbs_get_hero_items(7);

            if (!empty($all_hero_items)) {
                // Skip the first 3 items (already displayed above) and show remaining items 4-7
                $bottom_items = array_slice($all_hero_items, 3, 4);

                foreach ($bottom_items as $item) {
            ?>
                    <div class="col-xl-3 py-2">
                        <div class="portal-box">
                            <div class="d-flex align-items-center gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon($item['icon'] ?: 'building'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">
                                        <?php if ($item['link']) : ?>
                                            <a href="<?php echo esc_url($item['link']); ?>" class="text-decoration-none">
                                                <?php echo esc_html($item['title']); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo esc_html($item['title']); ?>
                                        <?php endif; ?>
                                    </h3>
                                    <p class="box-description mb-0">
                                        <?php echo esc_html($item['description'] ?: 'Add description'); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="box-line position-absolute"></div>
                        </div>
                    </div>
                <?php
                }
            } else {
                // Fallback to static content if no hero items exist
                ?>
                <!-- Box 1: 姉崎詳細 -->
                <div class="col-xl-3 py-2">
                    <div class="portal-box">
                        <div class="d-flex align-items-center gap-3">
                            <div class="box-icon flex-shrink-0">
                                <?php echo sbs_get_icon('building'); ?>
                            </div>
                            <div class="box-info flex-grow-1">
                                <h3 class="box-title mb-1">姉崎詳細</h3>
                                <p class="box-description mb-0">Add description</p>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>
                </div>

                <!-- Box 2: 稲毛詳細 -->
                <div class="col-xl-3 py-2">
                    <div class="portal-box">
                        <div class="d-flex align-items-center gap-3">
                            <div class="box-icon flex-shrink-0">
                                <?php echo sbs_get_icon('building'); ?>
                            </div>
                            <div class="box-info flex-grow-1">
                                <h3 class="box-title mb-1">稲毛詳細</h3>
                                <p class="box-description mb-0">Add description</p>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>
                </div>

                <!-- Box 3: 予約システム -->
                <div class="col-xl-3 py-2">
                    <div class="portal-box">
                        <div class="d-flex align-items-center gap-3">
                            <div class="box-icon flex-shrink-0">
                                <?php echo sbs_get_icon('calendar'); ?>
                            </div>
                            <div class="box-info flex-grow-1">
                                <h3 class="box-title mb-1">予約システム</h3>
                                <p class="box-description mb-0">教習、宿泊の予約</p>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>
                </div>

                <!-- Box 4: マッチングシステム -->
                <div class="col-xl-3 py-2">
                    <div class="portal-box">
                        <div class="d-flex align-items-center gap-3">
                            <div class="box-icon flex-shrink-0">
                                <?php echo sbs_get_icon('briefcase'); ?>
                            </div>
                            <div class="box-info flex-grow-1">
                                <h3 class="box-title mb-1">マッチングシステム</h3>
                                <p class="box-description mb-0">求人情報投稿</p>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </section>

    <!-- SECTION 2: Banner Carousel Section -->
    <section class="sbs-banner-carousel-section">
        <div class="banner-carousel-container">
            <div class="banner-carousel-track">
                <?php
                // Get banner items from database
                $banner_items = sbs_get_banner_items(10);

                // If no banner items exist, create sample ones
                if (empty($banner_items)) {
                    // Try to create sample banner items
                    if (function_exists('sbs_create_sample_banner_items')) {
                        sbs_create_sample_banner_items();
                        $banner_items = sbs_get_banner_items(10);
                    }
                }

                // Debug: Check if we have banner items
                if (empty($banner_items)) {
                    // No banner items in database, use fallback static content
                ?>
                    <!-- Banner 1: Gallery Image 1 -->
                    <a class="banner-item" data-banner-type="gallery" data-banner-id="1" href="<?php echo esc_url(home_url('/campaign-detail/')); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-1.jpg" alt="SBS ドライビングスクール 教習風景" />
                    </a>

                    <!-- Banner 2: Gallery Image 2 -->
                    <a class="banner-item" data-banner-type="gallery" data-banner-id="2" href="<?php echo esc_url(home_url('/campaign-detail/')); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-2.jpg" alt="SBS 自動車整備 サービス" />
                    </a>

                    <!-- Banner 3: Gallery Image 3 -->
                    <a class="banner-item" data-banner-type="gallery" data-banner-id="3" href="<?php echo esc_url(home_url('/campaign-detail/')); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-3.jpg" alt="SBS 施設案内" />
                    </a>
                    <?php
                } else {
                    // Display dynamic banners from database
                    foreach ($banner_items as $item) {
                        // Get campaign posts to match with banner items
                        $campaign_posts = get_posts(array(
                            'post_type' => 'campaign',
                            'post_status' => 'publish',
                            'posts_per_page' => 10,
                            'orderby' => 'date',
                            'order' => 'DESC',
                        ));

                        // Find matching campaign post by title or use first available
                        $campaign_post = null;
                        if (!empty($campaign_posts)) {
                            // Try to find campaign with matching title
                            foreach ($campaign_posts as $campaign) {
                                if (
                                    strpos(strtolower($campaign->post_title), strtolower($item['title'])) !== false ||
                                    strpos(strtolower($item['title']), strtolower($campaign->post_title)) !== false
                                ) {
                                    $campaign_post = $campaign;
                                    break;
                                }
                            }
                            // If no match found, use first available campaign
                            if (!$campaign_post) {
                                $campaign_post = $campaign_posts[0];
                            }
                        }

                        // Store campaign post ID for JavaScript navigation
                        $campaign_id = $campaign_post ? $campaign_post->ID : 0;
                    ?>
                        <?php
                        $slug = $campaign_id ? get_post_field('post_name', $campaign_id) : sanitize_title($item['title']);
                        $detail_url = home_url('/campaign-detail/' . $slug . '/');
                        ?>
                        <a class="banner-item" data-banner-type="dynamic" data-banner-id="<?php echo esc_attr($item['id']); ?>" data-campaign-id="<?php echo esc_attr($campaign_id); ?>" href="<?php echo esc_url($detail_url); ?>">
                            <img src="<?php echo esc_url($item['image_src']); ?>" alt="<?php echo esc_attr($item['title']); ?>" />
                        </a>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- SECTION 3: Blog Section -->
    <section class="sbs-blog-section">
        <div class="container">
            <?php get_template_part('parts/blog-section'); ?>
        </div>
    </section>

    <!-- SECTION 4: FAQ Section -->
    <section class="sbs-faq-section">
        <div class="container">
            <?php get_template_part('parts/faq-section'); ?>
        </div>
    </section>

    <!-- Popup -->
    <div class="sbs-popup" id="sbs-popup">
        <div class="popup-content" id="draggable-popup">
            <!-- Popup Header -->
            <button class="popup-close position-absolute" id="popup-close">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-x.svg" alt="Close" class="img-fluid" />
            </button>

            <!-- Popup Content -->
            <div class="popup-background">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ads.png" alt="SBS Background" />
            </div>
        </div>
    </div>

    <!-- Float Buttons -->
    <div class="float-buttons">
        <!-- Chat Button -->
        <div class="float-button float-chat" id="float-chat">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-chat.svg" alt="Chat" />
        </div>

        <!-- Contact Button -->
        <div class="float-button float-contact" id="float-contact">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-mail.svg" alt="Contact" />
        </div>

        <!-- Back to Top Button -->
        <div class="back-to-top" id="back-to-top">
            <div class="back-to-top-content">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-arrow-up.svg" alt="Back to top" />
                <span>Page top</span>
            </div>
        </div>
    </div>
</div>

<!-- Footer Background -->
<div class="footer-background" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/footer-bg.jpg');"></div>