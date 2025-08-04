<?php

/**
 * Portal Template
 * 
 * Main template for the SBS Portal homepage
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="sbs-portal">
    <!-- Hero Section -->
    <section class="sbs-hero-section" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/hero-bg-main-f14c9b.jpg');">
        <!-- Background Overlay -->
        <div class="hero-overlay"></div>

        <!-- Main Container với 2 cột layout -->
        <div class="hero-container">
            <!-- Left Column: Logo & Images + School Services -->
            <div class="hero-left-column">
                <!-- Logo & Images Section (2/3 của left column) -->
                <div class="hero-logo-section">
                    <div class="hero-circle-image floating-animation">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-circle.jpg" alt="Hero Circle" />
                    </div>
                    <div class="hero-logo-strip">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-logo-strip.jpg" alt="SBS Logo Strip" />
                    </div>
                </div>

                <!-- School Services Section (1/3 của left column) -->
                <div class="school-services-section">
                    <div class="portal-box school-box">
                        <div class="portal-box-content">
                            <div class="box-header">
                                <div class="box-icon">
                                    <?php echo sbs_get_icon('bus'); ?>
                                </div>
                                <div class="box-info">
                                    <h3 class="box-title">SBSドライビングスクール姉崎</h3>
                                    <p class="box-description">Add description</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line"></div>
                    </div>

                    <div class="portal-box school-box">
                        <div class="portal-box-content">
                            <div class="box-header">
                                <div class="box-icon">
                                    <?php echo sbs_get_icon('bus'); ?>
                                </div>
                                <div class="box-info">
                                    <h3 class="box-title">SBSドライビングスクール稲毛</h3>
                                    <p class="box-description">Add description</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line"></div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Navigation + Services -->
            <div class="hero-right-column">
                <!-- Navigation Section -->
                <div class="navigation-section">
                    <?php get_template_part('parts/portal-navigation'); ?>
                </div>

                <!-- Auto Service Section (Full width trong right column) -->
                <div class="auto-service-section">
                    <div class="portal-box auto-box">
                        <div class="portal-box-content">
                            <div class="box-header">
                                <div class="box-icon">
                                    <?php echo sbs_get_icon('bus'); ?>
                                </div>
                                <div class="box-info">
                                    <h3 class="box-title">SBS自動車</h3>
                                    <p class="box-description">Add description</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line"></div>
                    </div>
                </div>

                <!-- Detail Services Section (4 items grid) -->
                <div class="detail-services-section">
                    <div class="portal-box detail-box">
                        <div class="portal-box-content">
                            <div class="box-header">
                                <div class="box-icon">
                                    <?php echo sbs_get_icon('building'); ?>
                                </div>
                                <div class="box-info">
                                    <h3 class="box-title">姉崎詳細</h3>
                                    <p class="box-description">Add description</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line"></div>
                    </div>

                    <div class="portal-box detail-box">
                        <div class="portal-box-content">
                            <div class="box-header">
                                <div class="box-icon">
                                    <?php echo sbs_get_icon('building'); ?>
                                </div>
                                <div class="box-info">
                                    <h3 class="box-title">稲毛詳細</h3>
                                    <p class="box-description">Add description</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line"></div>
                    </div>

                    <div class="portal-box system-box">
                        <div class="portal-box-content">
                            <div class="box-header">
                                <div class="box-icon">
                                    <?php echo sbs_get_icon('calendar'); ?>
                                </div>
                                <div class="box-info">
                                    <h3 class="box-title">予約システム</h3>
                                    <p class="box-description">教習、宿泊の予約</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line"></div>
                    </div>

                    <div class="portal-box system-box">
                        <div class="portal-box-content">
                            <div class="box-header">
                                <div class="box-icon">
                                    <?php echo sbs_get_icon('briefcase'); ?>
                                </div>
                                <div class="box-info">
                                    <h3 class="box-title">マッチングシステム</h3>
                                    <p class="box-description">求人情報投稿</p>
                                </div>
                            </div>
                        </div>
                        <div class="box-line"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="sbs-content-section">
        <div class="container">
            <!-- Gallery Images -->
            <div class="gallery-section">
                <div class="gallery-images">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-1.jpg" alt="Gallery 1" />
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-2.jpg" alt="Gallery 2" />
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-3.jpg" alt="Gallery 3" />
                </div>
            </div>

            <!-- Blog Section -->
            <div class="blog-section">
                <?php get_template_part('parts/blog-section'); ?>
            </div>

            <!-- FAQ Section -->
            <div class="faq-section">
                <?php get_template_part('parts/faq-section'); ?>
            </div>
        </div>
    </section>

    <!-- Popup/Modal -->
    <div class="sbs-popup-overlay" id="sbs-popup">
        <div class="popup-content">
            <button class="popup-close" id="popup-close">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-close.svg" alt="Close" />
            </button>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/popup-image-7b3887.jpg" alt="Popup Image" />
        </div>
    </div>

    <!-- Float Buttons -->
    <div class="float-buttons">
        <div class="float-button float-chat">
            <svg width="44" height="44" viewBox="0 0 44 44" fill="none">
                <path d="M37 7H7C4.79 7 3 8.79 3 11V25C3 27.21 4.79 29 7 29H33L41 37V11C41 8.79 39.21 7 37 7Z" fill="white" />
            </svg>
        </div>
        <div class="float-button float-contact">
            <svg width="44" height="44" viewBox="0 0 44 44" fill="none">
                <path d="M22 2C12.6 2 5 9.6 5 19C5 22.4 6.1 25.5 8 28.1L5 39L16.2 36.2C18.7 37.9 21.8 39 25 39C34.4 39 42 31.4 42 22C42 12.6 34.4 2 22 2Z" fill="white" />
            </svg>
        </div>
        <div class="back-to-top" id="back-to-top">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-arrow-up.svg" alt="Back to top" />
            <span>Page top</span>
        </div>
    </div>
</div>

<!-- Footer Background -->
<div class="footer-background" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/footer-bg.jpg');"></div>