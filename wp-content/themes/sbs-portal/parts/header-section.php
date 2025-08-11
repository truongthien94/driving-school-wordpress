<?php

/**
 * Header Section Template
 * 
 * Reusable header section for blog, campaign, and other detail pages
 * Accepts dynamic title parameters
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get parameters with defaults
$title = isset($args['title']) ? $args['title'] : 'ブログ';
$subtitle = isset($args['subtitle']) ? $args['subtitle'] : 'BLOG and NEWS';
$breadcrumb_items = isset($args['breadcrumb_items']) ? $args['breadcrumb_items'] : array('ブログ一覧');
$show_navigation = isset($args['show_navigation']) ? $args['show_navigation'] : true;
?>

<section class="header-section">
    <?php if ($show_navigation): ?>
        <div class="hero-navigation row-gap-0 p-0 row py-4 flex justify-content-end">
            <div class="col-xl-6">
                <?php get_template_part('parts/portal-navigation'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="header-section-container">
        <div class="header-section-left">
            <div class="header-section-logo">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/sbs-logo-header.png" alt="SBS" />
            </div>
            <div class="header-section-title">
                <h4><?php echo esc_html($title); ?></h4>
                <h1 class="fst-italic" style="white-space: nowrap;"><?php echo esc_html($subtitle); ?></h1>
            </div>
        </div>

        <div class="header-section-right">
            <img class="hero-circle" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero-circle.jpg" alt="circle" />
            <img class="hero-circle-2" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero-circle.jpg" alt="circle" />
        </div>
    </div>
</section>

<!-- Breadcrumbs Section -->
<section class="breadcrumbs-section">
    <div class="container">
        <div class="blog-breadcrumbs mb-1">
            <div class="breadcrumb-list d-flex align-items-center">
                <div class="breadcrumb-item">
                    <a href="<?php echo home_url('/'); ?>" class="breadcrumb-link text-decoration-none">ポータル</a>
                </div>

                <?php foreach ($breadcrumb_items as $index => $item): ?>
                    <div class="breadcrumb-separator d-flex align-items-center justify-content-center">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M6.4 3.2L11.07 8L6.4 12.8" stroke="currentColor" />
                        </svg>
                    </div>
                    <div class="breadcrumb-item">
                        <?php if ($index === count($breadcrumb_items) - 1): ?>
                            <span class="breadcrumb-current"><?php echo esc_html($item); ?></span>
                        <?php else: ?>
                            <a href="#" class="breadcrumb-link text-decoration-none"><?php echo esc_html($item); ?></a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>