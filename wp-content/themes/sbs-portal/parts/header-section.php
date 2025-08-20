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
                <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/sbs-logo-header.png" alt="SBS" />
                </a>
            </div>
            <div class="header-section-title">
                <h4><?php echo esc_html($title); ?></h4>
                <h1 class="fst-italic" style="white-space: nowrap;"><?php echo esc_html($subtitle); ?></h1>
            </div>
        </div>
    </div>
</section>