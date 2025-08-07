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

        <!-- Hero Container - 50/50 Split Layout -->
        <div class="hero-container">
            <!-- Left Column (50%) - Logo & Brand Message -->
            <div class="hero-left-column">
                <div class="hero-images-section position-relative">
                    <!-- Circle Image (408x408px) -->
                    <div class="hero-circle-image floating-animation position-absolute">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-circle.jpg" alt="Hero Circle" class="img-fluid rounded-circle" />
                    </div>

                    <!-- Logo Strip (492x92px) -->
                    <div class="hero-logo-strip position-absolute">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-logo-strip.png" alt="SBS Logo Strip" class="img-fluid" />
                    </div>
                </div>
            </div>

            <!-- Right Column (50%) - Navigation + Services -->
            <div class="hero-right-column d-flex flex-column gap-3">
                <!-- Navigation Menu -->
                <div class="navigation-section">
                    <?php get_template_part('parts/portal-navigation'); ?>
                </div>

                <!-- Main Auto Service Box -->
                <div class="auto-service-section">
                    <div class="portal-box auto-box h-100">
                        <div class="portal-box-content d-flex flex-column gap-3 h-100 justify-content-center">
                            <div class="box-header d-flex align-items-start gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon('bus'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">SBS自動車</h3>
                                    <p class="box-description mb-0">自動車整備・販売サービス</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>
                </div>

                <!-- Two School Service Boxes -->
                <div class="school-services-section d-flex gap-3">
                    <div class="portal-box school-box flex-fill">
                        <div class="portal-box-content d-flex flex-column gap-3 h-100 justify-content-center">
                            <div class="box-header d-flex align-items-start gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon('bus'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">SBSドライビングスクール姉崎</h3>
                                    <p class="box-description mb-0">運転免許取得をサポート</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>

                    <div class="portal-box school-box flex-fill">
                        <div class="portal-box-content d-flex flex-column gap-3 h-100 justify-content-center">
                            <div class="box-header d-flex align-items-start gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon('bus'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">SBSドライビングスクール稲毛</h3>
                                    <p class="box-description mb-0">運転免許取得をサポート</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Services Grid - 4 Columns -->
        <div class="detail-services-main-section">
            <div class="detail-services-grid row g-3">
                <!-- Box 1: 姉崎詳細 -->
                <div class="col-lg-3 col-md-6">
                    <div class="portal-box detail-box h-100">
                        <div class="portal-box-content d-flex flex-column gap-3 h-100 justify-content-center">
                            <div class="box-header d-flex align-items-start gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon('building'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">姉崎詳細</h3>
                                    <p class="box-description mb-0">スクール詳細情報</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>
                </div>

                <!-- Box 2: 稲毛詳細 -->
                <div class="col-lg-3 col-md-6">
                    <div class="portal-box detail-box h-100">
                        <div class="portal-box-content d-flex flex-column gap-3 h-100 justify-content-center">
                            <div class="box-header d-flex align-items-start gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon('building'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">稲毛詳細</h3>
                                    <p class="box-description mb-0">スクール詳細情報</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>
                </div>

                <!-- Box 3: 予約システム -->
                <div class="col-lg-3 col-md-6">
                    <div class="portal-box system-box h-100">
                        <div class="portal-box-content d-flex flex-column gap-3 h-100 justify-content-center">
                            <div class="box-header d-flex align-items-start gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon('calendar'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">予約システム</h3>
                                    <p class="box-description mb-0">教習、宿泊の予約</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>
                </div>

                <!-- Box 4: マッチングシステム -->
                <div class="col-lg-3 col-md-6">
                    <div class="portal-box system-box h-100">
                        <div class="portal-box-content d-flex flex-column gap-3 h-100 justify-content-center">
                            <div class="box-header d-flex align-items-start gap-3">
                                <div class="box-icon flex-shrink-0">
                                    <?php echo sbs_get_icon('briefcase'); ?>
                                </div>
                                <div class="box-info flex-grow-1">
                                    <h3 class="box-title mb-1">マッチングシステム</h3>
                                    <p class="box-description mb-0">求人情報投稿</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line position-absolute"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: Banner Carousel Section -->
    <section class="sbs-banner-carousel-section">
        <div class="banner-carousel-container">
            <div class="banner-carousel-track">
                <!-- Banner 1: Gallery Image 1 -->
                <div class="banner-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-1.jpg" alt="SBS ドライビングスクール 教習風景" />
                </div>

                <!-- Banner 2: Gallery Image 2 -->
                <div class="banner-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-2.jpg" alt="SBS 自動車整備 サービス" />
                </div>

                <!-- Banner 3: Gallery Image 3 -->
                <div class="banner-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-3.jpg" alt="SBS 施設案内" />
                </div>

                <!-- Duplicate banners for seamless loop -->
                <!-- Banner 1 Duplicate -->
                <div class="banner-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-1.jpg" alt="SBS ドライビングスクール 教習風景" />
                </div>

                <!-- Banner 2 Duplicate -->
                <div class="banner-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-2.jpg" alt="SBS 自動車整備 サービス" />
                </div>

                <!-- Banner 3 Duplicate -->
                <div class="banner-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-3.jpg" alt="SBS 施設案内" />
                </div>
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
            <div class="popup-header">
                <button class="popup-close" id="popup-close">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-close.svg" alt="Close" />
                </button>
            </div>

            <!-- Popup Content -->
            <div class="popup-body">
                <!-- Background Image -->
                <div class="popup-background">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-1.jpg" alt="SBS Background" />
                </div>
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