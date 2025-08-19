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
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-circle.png" alt="Hero Circle" class="img-fluid rounded-circle" />
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
                        <div class="portal-box" <?php if (!empty($main_item['link'])): ?> data-href="<?php echo esc_url($main_item['link']); ?>" role="link" tabindex="0" <?php endif; ?>>
                            <div class="d-flex align-items-center gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon($main_item['icon'] ?: 'car'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">
                                        <?php if ($main_item['link']) : ?>
                                            <a href="<?php echo esc_url($main_item['link']); ?>" class="text-decoration-none">
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
                                    <div class="portal-box" <?php if (!empty($item['link'])): ?> data-href="<?php echo esc_url($item['link']); ?>" role="link" tabindex="0" <?php endif; ?>>
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
                        <div class="portal-box" <?php if (!empty($item['link'])): ?> data-href="<?php echo esc_url($item['link']); ?>" role="link" tabindex="0" <?php endif; ?>>
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

    <?php
    // SECTION 2: Banner Carousel Section (render only when campaigns exist)
    $campaign_items = function_exists('sbs_get_campaign_items') ? sbs_get_campaign_items(10) : array();
    if (!empty($campaign_items)) :
    ?>
        <section class="sbs-banner-carousel-section">
            <div class="banner-carousel-container">
                <div class="banner-carousel-track">
                    <?php foreach ($campaign_items as $item): ?>
                        <a class="banner-item" data-banner-type="campaign" data-banner-id="<?php echo esc_attr($item['id']); ?>" href="<?php echo esc_url($item['detail_url'] ?? $item['permalink']); ?>">
                            <?php if (!empty($item['image_src'])): ?>
                                <img src="<?php echo esc_url($item['image_src']); ?>" alt="<?php echo esc_attr($item['title']); ?>" />
                            <?php else: ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-1.jpg" alt="<?php echo esc_attr($item['title']); ?>" />
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <script>
        (function() {
            // Attach click tracking for campaign banner items
            document.addEventListener('click', function(e) {
                var anchor = e.target.closest('a.banner-item[data-banner-type="campaign"][data-banner-id]');
                if (!anchor) return;

                var id = anchor.getAttribute('data-banner-id');
                var ref = window.location.pathname || '/';
                var restUrl = (window.sbsThemeData && window.sbsThemeData.restUrl ? window.sbsThemeData.restUrl : '/wp-json/') + 'sbs/v1/campaign/track';

                try {
                    navigator.sendBeacon && navigator.sendBeacon.length; // just to reference API if available
                } catch (err) {}

                var payload = JSON.stringify({
                    campaign_id: parseInt(id, 10),
                    type: 'click',
                    ref: ref
                });

                // Prefer sendBeacon for non-blocking tracking during navigation
                if (navigator.sendBeacon) {
                    var blob = new Blob([payload], {
                        type: 'application/json'
                    });
                    navigator.sendBeacon(restUrl, blob);
                } else if (window.fetch) {
                    fetch(restUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: payload,
                        keepalive: true
                    });
                } else {
                    // Fallback XHR
                    try {
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', restUrl, true);
                        xhr.setRequestHeader('Content-Type', 'application/json');
                        xhr.send(payload);
                    } catch (err) {}
                }
            }, true);
        })();
    </script>

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