<?php

/**
 * The template for displaying the footer
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Footer data is now handled by translation functions
?>
<?php
// Determine current language code for building legal links
$current_lang_mobile = function_exists('sbs_get_current_language') ? sbs_get_current_language() : 'ja';

// Determine environment to choose correct legal links domain
$env_settings = function_exists('sbs_get_campaign_email_api_settings') ? sbs_get_campaign_email_api_settings() : array('environment' => 'dev');
$environment = $env_settings['environment'] ?? 'dev';

$legal_base_domain = 'https://dev.sbs-ds.com';
if ($environment === 'stg') {
    $legal_base_domain = 'https://stg.sbs-ds.com';
} elseif ($environment === 'prod') {
    $legal_base_domain = 'https://sbs-ds.com';
}
?>

</div><!-- #page -->

<footer id="colophon" class="site-footer">
    <div class="footer-container">
        <!-- Footer Main Content -->
        <div class="footer-main">
            <!-- Company Info -->
            <div class="footer-company">
                <div class="company-logo">
                    <!-- Logo would go here -->
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-footer.png"
                            alt="SBS Driving School" />
                    </a>
                </div>
                <div class="company-info">
                    <p class="company-description">
                        <?php _e('With the motto of learning joyfully and acquiring solid knowledge and skills, we have been moving forward for 60 years in Chiba and Inage, nurturing many graduates.', 'sbs-portal'); ?>
                    </p>
                </div>
            </div>

            <!-- Footer Columns -->
            <div class="footer-columns">
                <!-- Column 1: Explore -->
                <div class="footer-column">
                    <h3 class="column-title"><?php _e('Explore', 'sbs-portal'); ?></h3>
                    <ul class="column-links">
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school"><?php _e('Greetings', 'sbs-portal'); ?></a></li>
                        <li>
                            <div class="dropdown dropdown-footer">
                                <!-- Dropdown Toggle Button -->
                                <button class="dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLanguageList" aria-expanded="false" aria-controls="collapseLanguageList">
                                    <span><?php _e('Company Information', 'sbs-portal'); ?></span>
                                </button>
                                <!-- Collapsible List Group -->
                                <div class="collapse" id="collapseLanguageList">
                                    <ul class="list-group ">
                                        <li class="mt-3">
                                            <a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/company/outline"><?php _e('Company Overview', 'sbs-portal'); ?></a>
                                        </li>
                                        <li class="mt-3">
                                            <a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/company/history"><?php _e('History', 'sbs-portal'); ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/news/?page=1&category=0&year=2025"><?php _e('News', 'sbs-portal'); ?></a></li>
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/group"><?php _e('About SBS Group', 'sbs-portal'); ?></a></li>
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/recruit/"><?php _e('Recruitment', 'sbs-portal'); ?></a></li>
                    </ul>
                </div>

                <!-- Column 3: SBS School -->
                <div class="footer-column">
                    <h3 class="column-title"><?php _e('SBS School', 'sbs-portal'); ?></h3>
                    <ul class="column-links">
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school/inage"><?php _e('SBS Driving School Inage', 'sbs-portal'); ?></a></li>
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school/anesaki"><?php _e('SBS Driving School Anesaki', 'sbs-portal'); ?></a></li>
                    </ul>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="footer-contact">
                <div class="contact-info">
                    <div class="contact-item">
                        <a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school/inage/"><?php _e('SBS Driving School Inage', 'sbs-portal'); ?></a>
                        <p><?php _e('Tel: 043-259-6371', 'sbs-portal'); ?></p>
                    </div>
                    <div class="contact-item">
                        <a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school/anesaki"><?php _e('SBS Driving School Anesaki', 'sbs-portal'); ?></a>
                        <p><?php _e('Tel: 0436-61-1131', 'sbs-portal'); ?></p>
                    </div>
                </div>
                <div class="contact-button-wrapper">
                    <div class="contact-button" id="contact-button">
                        <span><?php _e('Contact', 'sbs-portal'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <!-- Legal Links -->
            <div class="legal-links d-flex justify-content-center mb-2">
                <a class="legal-link me-3" href="<?php echo esc_url($legal_base_domain . '/' . $current_lang_mobile . '/site-usage'); ?>">
                    <?php _e('Terms of Use', 'sbs-portal'); ?>
                </a>
                <a class="legal-link" href="<?php echo esc_url($legal_base_domain . '/' . $current_lang_mobile . '/privacy-policy'); ?>">
                    <?php _e('Privacy', 'sbs-portal'); ?>
                </a>
            </div>

            <!-- Copyright -->
            <div class="copyright">
                <p><?php _e('Copyright © 2025 SBS Driving School Co., Ltd. All rights reserved', 'sbs-portal'); ?></p>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>

</html>