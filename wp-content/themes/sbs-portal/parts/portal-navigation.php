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
$navigation = isset($mock_data['navigation']) ? $mock_data['navigation'] : array();
?>

<div class="nav-section">
    <div class="d-flex align-items-center justify-content-end">
        <ul class="d-flex list-unstyled align-items-center ms-auto mb-2 mb-lg-0 gap-2 gap-xxl-4">
            <li class="nav-item d-none d-xl-flex">
                <a class="nav-link active" aria-current="page" href="#">
                    <?php echo sbs_get_text('greeting', array(
                        'ja' => 'ごあいさつ',
                        'en' => 'Greeting',
                        'id' => 'Salam'
                    )); ?>
                </a>
            </li>
            <li class="nav-item dropdown d-none d-xl-flex">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span>
                        <?php echo sbs_get_text('company_info', array(
                            'ja' => '企業情報',
                            'en' => 'Company Info',
                            'id' => 'Info Perusahaan'
                        )); ?>
                    </span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#">
                            <?php echo sbs_get_text('company_overview', array(
                                'ja' => '会社概要',
                                'en' => 'Company Overview',
                                'id' => 'Gambaran Perusahaan'
                            )); ?>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <?php echo sbs_get_text('history', array(
                                'ja' => '沿革',
                                'en' => 'History',
                                'id' => 'Sejarah'
                            )); ?>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item  d-none d-xl-flex">
                <a class="nav-link  active" href="#">
                    <?php echo sbs_get_text('about_sbs_group', array(
                        'ja' => 'SBSグループについて',
                        'en' => 'About SBS Group',
                        'id' => 'Tentang Grup SBS'
                    )); ?>
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
                    <div class="container-fluid">
                        <button class="navbar-toggler btn-custom-nav p-2 d-flex gap-2" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                            <span class="d-none d-xl-flex align-items-center"><?php echo sbs_get_text('menu', array(
                                                                                    'ja' => 'Menu',
                                                                                    'en' => 'Menu',
                                                                                    'id' => 'Menu'
                                                                                )); ?></span>
                            <span><?php echo sbs_get_icon('align-justify'); ?></span>
                        </button>

                        <div class="offcanvas offcanvas-end sbs-mega-menu" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

                            <!-- Left Brand Panel -->
                            <div class="mega-menu-brand-panel">
                                <div class="brand-content">
                                    <div class="brand-circle-image">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo_menu.png" alt="SBS Image" />
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
                                            <a class="language-switcher-mega" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="lang-name"><?php echo $current_lang_data_mobile['native_name']; ?></span>
                                                <span class="dropdown-icon"><?php echo sbs_get_icon('chevron-down'); ?></span>
                                            </a>
                                            <ul class="dropdown-menu language-dropdown-menu-mega">
                                                <?php foreach ($available_languages_mobile as $lang_code => $lang_data): ?>
                                                    <li>
                                                        <a class="dropdown-item language-option <?php echo ($lang_code === $current_lang_mobile) ? 'active' : ''; ?>"
                                                            href="#"
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

                                    <button type="button" class="mega-menu-close" data-bs-dismiss="offcanvas" aria-label="Close">
                                        <span class="close-text">
                                            <?php echo sbs_get_text('close', array(
                                                'ja' => 'Close',
                                                'en' => 'Close',
                                                'id' => 'Close'
                                            )); ?>
                                        </span>
                                        <?php echo sbs_get_icon('icon-x'); ?>
                                    </button>
                                </div>

                                <!-- Navigation Content -->
                                <div class="mega-menu-body">
                                    <div class="mega-nav-columns">

                                        <!-- Portal Section -->
                                        <div class="mega-nav-column">
                                            <div class="mega-nav-section">
                                                <h3 class="mega-section-title">
                                                    <?php echo sbs_get_text('portal', array(
                                                        'ja' => 'ポータル',
                                                        'en' => 'Portal',
                                                        'id' => 'Portal'
                                                    )); ?>
                                                </h3>

                                                <ul class="mega-nav-list">
                                                    <li>
                                                        <a class="mega-nav-link" href="#">
                                                            <?php echo sbs_get_text('greeting', array(
                                                                'ja' => 'ごあいさつ',
                                                                'en' => 'Greeting',
                                                                'id' => 'Salam'
                                                            )); ?>
                                                        </a>
                                                    </li>
                                                </ul>

                                                <div class="mega-nav-subsection">
                                                    <h4 class="mega-subsection-title">
                                                        <?php echo sbs_get_text('company_info', array(
                                                            'ja' => '企業情報',
                                                            'en' => 'Company Information',
                                                            'id' => 'Informasi Perusahaan'
                                                        )); ?>
                                                    </h4>
                                                    <ul class="mega-nav-sublist">
                                                        <li>
                                                            <a class="mega-nav-sublink" href="#">
                                                                <?php echo sbs_get_text('company_overview', array(
                                                                    'ja' => '会社概要',
                                                                    'en' => 'Company Overview',
                                                                    'id' => 'Gambaran Perusahaan'
                                                                )); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="mega-nav-sublink" href="#">
                                                                <?php echo sbs_get_text('history', array(
                                                                    'ja' => '沿革',
                                                                    'en' => 'History',
                                                                    'id' => 'Sejarah'
                                                                )); ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <ul class="mega-nav-list">
                                                    <li>
                                                        <a class="mega-nav-link" href="#">
                                                            <?php echo sbs_get_text('about_sbs_group', array(
                                                                'ja' => 'SBSグループについて',
                                                                'en' => 'About SBS Group',
                                                                'id' => 'Tentang Grup SBS'
                                                            )); ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- SBS Auto Section -->
                                        <div class="mega-nav-column">
                                            <div class="mega-nav-section">
                                                <h3 class="mega-section-title">
                                                    <?php echo sbs_get_text('sbs_auto', array(
                                                        'ja' => 'SBS自動車',
                                                        'en' => 'SBS Auto',
                                                        'id' => 'SBS Auto'
                                                    )); ?>
                                                </h3>

                                                <ul class="mega-nav-list">
                                                    <li>
                                                        <a class="mega-nav-link large" href="#">
                                                            <?php echo sbs_get_text('sbs_driving_school_anesaki', array(
                                                                'ja' => 'SBSドライビングスクール姉崎',
                                                                'en' => 'SBS Driving School Anesaki',
                                                                'id' => 'SBS Driving School Anesaki'
                                                            )); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="mega-nav-link main" href="#">
                                                            <?php echo sbs_get_text('sbs_driving_school_inage', array(
                                                                'ja' => 'SBSドライビングスクール稲毛',
                                                                'en' => 'SBS Driving School Inage',
                                                                'id' => 'SBS Driving School Inage'
                                                            )); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="mega-nav-link" href="#">
                                                            <?php echo sbs_get_text('anesaki_details', array(
                                                                'ja' => '姉崎詳細',
                                                                'en' => 'Anesaki Details',
                                                                'id' => 'Detail Anesaki'
                                                            )); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="mega-nav-link" href="#">
                                                            <?php echo sbs_get_text('inage_details', array(
                                                                'ja' => '稲毛詳細',
                                                                'en' => 'Inage Details',
                                                                'id' => 'Detail Inage'
                                                            )); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="mega-nav-link" href="#">
                                                            <?php echo sbs_get_text('reservation_system', array(
                                                                'ja' => '予約システム',
                                                                'en' => 'Reservation System',
                                                                'id' => 'Sistem Reservasi'
                                                            )); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="mega-nav-link" href="#">
                                                            <?php echo sbs_get_text('matching_system', array(
                                                                'ja' => 'マッチングシステム',
                                                                'en' => 'Matching System',
                                                                'id' => 'Sistem Matching'
                                                            )); ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer Section -->
                                    <div class="mega-menu-footer">
                                        <div class="mega-footer-links">
                                            <a class="mega-footer-link primary" href="#">
                                                <?php echo sbs_get_text('terms_of_use', array(
                                                    'ja' => '利用規約',
                                                    'en' => 'Terms of Use',
                                                    'id' => 'Syarat Penggunaan'
                                                )); ?>
                                            </a>
                                            <a class="mega-footer-link" href="#">
                                                <?php echo sbs_get_text('privacy', array(
                                                    'ja' => 'プライバシー',
                                                    'en' => 'Privacy',
                                                    'id' => 'Privasi'
                                                )); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </nav>
            </li>
        </ul>
    </div>
</div>