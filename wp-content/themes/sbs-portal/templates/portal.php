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
                <div class="py-2">
                    <div class="portal-box">
                        <div class="d-flex align-items-center gap-3">
                            <div class="box-icon flex-shrink-0">
                                <?php echo sbs_get_icon('bus'); ?>
                            </div>
                            <div class="box-info flex-grow-1">
                                <h3 class="box-title mb-1">SBS自動車</h3>
                                <p class="box-description mb-0">Add description</p>
                            </div>
                        </div>
                        <div class="box-line "></div>
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
                                    <h3 class="box-title mb-1">SBSドライビングスクール姉崎</h3>
                                    <p class="box-description mb-0">Add description</p>
                                </div>
                            </div>
                            <div class="box-line position-absolute"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row portal-box-container hero-container row-gap-0 ">
            <!-- Box 1: 姉崎詳細 -->
            <div class="col-xl-3  py-2">
                <div class="portal-box ">
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

            <!-- Box 2: 稲毛詳細 -->
            <div class="col-xl-3 py-2">
                <div class="portal-box ">
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
                            <p class="box-description mb-0">教習、宿泊䛾予約</p>
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