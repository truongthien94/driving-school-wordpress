<?php

/**
 * Portal Navigation
 * 
 * Main navigation menu for the portal
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$mock_data = sbs_get_mock_data();
?>

<div class="mega-search-box">
    <div class="portal-sticky nav-section">
        <div class="d-flex align-items-center justify-content-end">
            <ul class="d-flex list-unstyled align-items-center mb-2 mb-lg-0 navigation-items gap-0">
                <li class="nav-item d-none d-xl-flex">
                    <a class="nav-link active" aria-current="page" href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school">
                        <?php _e('Greeting', 'sbs-portal'); ?>
                    </a>
                </li>
                <li class="nav-item dropdown d-none d-xl-flex">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>
                            <?php _e('Company Information', 'sbs-portal'); ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="https://www.sbs-drivingschool.co.jp/sbsjdgk/company/outline">
                                <?php _e('Company Overview', 'sbs-portal'); ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="https://www.sbs-drivingschool.co.jp/sbsjdgk/company/history">
                                <?php _e('History', 'sbs-portal'); ?>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item  d-none d-xl-flex">
                    <a class="nav-link  active" href="https://www.sbs-drivingschool.co.jp/sbsjdgk/group">
                        <?php _e('About SBS Group', 'sbs-portal'); ?>
                    </a>
                </li>

                <li class="nav-item dropdown d-none d-xl-flex">
                    <?php
                    $current_lang = sbs_get_current_language();
                    $available_languages = sbs_get_available_languages();
                    $current_lang_data = $available_languages[$current_lang];
                    ?>
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $current_lang_data['native_name']; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach ($available_languages as $lang_code => $lang_data): ?>
                            <li>
                                <a class="dropdown-item language-option <?php echo ($lang_code === $current_lang) ? 'active' : ''; ?>"
                                    href="#"
                                    data-language="<?php echo esc_attr($lang_code); ?>"
                                    data-locale="<?php echo esc_attr($lang_data['locale']); ?>">
                                    <span class="flag-icon"><?php echo $lang_data['flag']; ?></span>
                                    <span class="lang-name"><?php echo $lang_data['native_name']; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <nav class="navbar">
                        <div class="container-navbar">
                            <button class="navbar-toggler btn-custom-nav p-2 d-flex gap-2" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                                <span class="d-none d-xl-flex align-items-center"><?php _e('Menu', 'sbs-portal'); ?></span>
                                <span><?php echo sbs_get_icon('align-justify'); ?></span>
                            </button>

                            <div class="offcanvas offcanvas-end sbs-mega-menu " tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

                                <!-- Left Brand Panel -->
                                <div class="mega-menu-brand-panel d-none d-xl-block">
                                    <div class="brand-content">
                                        <div class="brand-circle-image-container">
                                            <div class="brand-circle-image">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo_menu_light.png" alt="SBS Image" />
                                            </div>
                                            <div class="brand-logo-dark">
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo_menu_text_light.png" alt="SBS Image" />
                                            </div>
                                        </div>
                                        <div class="brand-vertical-text">
                                            <div class="vertical-text-line">S</div>
                                            <div class="vertical-text-line">B</div>
                                            <div class="vertical-text-line">S</div>
                                            <div class="vertical-text-line">自</div>
                                            <div class="vertical-text-line">動</div>
                                            <div class="vertical-text-line">車</div>
                                            <div class="vertical-text-line">学</div>
                                            <div class="vertical-text-line">校</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Content Panel -->
                                <div class="mega-menu-content-panel">
                                    <!-- Close Button -->
                                    <div class="mega-menu-header">
                                        <div class="language-switcher-container">
                                            <?php
                                            $current_lang_mobile = sbs_get_current_language();
                                            $available_languages_mobile = sbs_get_available_languages();
                                            $current_lang_data_mobile = $available_languages_mobile[$current_lang_mobile];
                                            ?>
                                            <div class="dropdown mega-language-dropdown">
                                                <!-- Dropdown Toggle Button -->
                                                <button class="language-switcher-mega dropdown-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLanguageList" aria-expanded="false" aria-controls="collapseLanguageList">
                                                    <span><?php echo $current_lang_data_mobile['native_name']; ?></span>
                                                </button>
                                                <!-- Collapsible List Group -->
                                                <div class="collapse" id="collapseLanguageList">
                                                    <ul class="list-group mt-2">
                                                        <?php foreach ($available_languages_mobile as $lang_code => $lang_data): ?>
                                                            <li class="list-group-item">
                                                                <a href="#"
                                                                    class="language-option d-flex align-items-center gap-2 text-decoration-none <?php echo ($lang_code === $current_lang_mobile) ? 'active' : ''; ?>"
                                                                    data-language="<?php echo esc_attr($lang_code); ?>"
                                                                    data-locale="<?php echo esc_attr($lang_data['locale']); ?>">
                                                                    <span class="flag-icon"><?php echo $lang_data['flag']; ?></span>
                                                                    <span class="lang-name"><?php echo $lang_data['native_name']; ?></span>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="close-button-container">
                                            <button type="button" class="mega-menu-close" data-bs-dismiss="offcanvas" aria-label="Close">
                                                <span class="close-text d-none d-xl-block">
                                                    <?php _e('Close', 'sbs-portal'); ?>
                                                </span>
                                                <?php echo sbs_get_icon('icon-x'); ?>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Navigation Content -->
                                    <div class="mega-menu-container">
                                        <div class="mega-nav-columns d-xl-flex d-block align-items-start">

                                            <!-- Portal Section -->
                                            <div class="mega-nav-column">
                                                <div class="mega-nav-section">
                                                    <h3 class="mega-section-title">
                                                        <?php _e('Portal', 'sbs-portal'); ?>
                                                    </h3>

                                                    <ul class="mega-nav-list mb-4">
                                                        <li>
                                                            <a class="mega-nav-link" href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school">
                                                                <?php _e('Greeting', 'sbs-portal'); ?>
                                                            </a>
                                                        </li>
                                                    </ul>

                                                    <div class="mega-nav-subsection gap-0 mb-4">
                                                        <h4 class="mega-subsection-title">
                                                            <?php _e('Company Information', 'sbs-portal'); ?>
                                                        </h4>
                                                        <ul class="mega-nav-sublist">
                                                            <li>
                                                                <a class="mega-nav-sublink" href="https://www.sbs-drivingschool.co.jp/sbsjdgk/company/outline">
                                                                    <?php _e('Company Overview', 'sbs-portal'); ?>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="mega-nav-sublink" href="https://www.sbs-drivingschool.co.jp/sbsjdgk/company/history">
                                                                    <?php _e('History', 'sbs-portal'); ?>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <ul class="mega-nav-list">
                                                        <li>
                                                            <a class="mega-nav-link" href="https://www.sbs-drivingschool.co.jp/sbsjdgk/group">
                                                                <?php _e('About SBS Group', 'sbs-portal'); ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!-- SBS Auto Section -->
                                            <div class="mega-nav-column">
                                                <div class="mega-nav-section">
                                                    <a class="mega-section-title hover-link" href="https://www.sbs-drivingschool.co.jp/sbsjdgk">
                                                        <?php _e('SBS Auto', 'sbs-portal'); ?>
                                                    </a>

                                                    <ul class="mega-nav-list">
                                                        <li>
                                                            <a class="mega-nav-link large" href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school/anesaki">
                                                                <?php _e('SBS Driving School Anesaki', 'sbs-portal'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="mega-nav-link main" href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school/inage">
                                                                <?php _e('SBS Driving School Inage', 'sbs-portal'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="mega-nav-link" href="https://anesaki.sbs-drivingschool.co.jp">
                                                                <?php _e('Anesaki Details', 'sbs-portal'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="mega-nav-link" href="https://inage.sbs-drivingschool.co.jp">
                                                                <?php _e('Inage Details', 'sbs-portal'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="mega-nav-link" href="https://dev.sbs-ds.com/<?php echo $current_lang_mobile; ?>/reservation-course">
                                                                <?php _e('Reservation System', 'sbs-portal'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="mega-nav-link" href="https://dev.sbs-ds.com/<?php echo $current_lang_mobile; ?>/matching">
                                                                <?php _e('Matching System', 'sbs-portal'); ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer Section -->
                                        <div class="mega-menu-footer">
                                            <div class="mega-footer-links">
                                                <a class="mega-footer-link primary" href="https://dev.sbs-ds.com/<?php echo $current_lang_mobile; ?>/site-usage">
                                                    <?php _e('Terms of Service', 'sbs-portal'); ?>
                                                </a>
                                                <a class="mega-footer-link" href="https://dev.sbs-ds.com/<?php echo $current_lang_mobile; ?>/privacy-policy">
                                                    <?php _e('Privacy Policy', 'sbs-portal'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 menu-footer-logo d-block d-xl-none">
                                        <a href="<?php echo esc_url(home_url('/')); ?>">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-footer-dark.png"
                                                alt="SBS Driving School" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                    </nav>
                </li>
            </ul>
        </div>
    </div>
</div>