<?php

/**
 * The header for our theme
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php
    // Generate SEO meta tags
    if (function_exists('sbs_generate_seo_meta_tags')) {
        sbs_generate_seo_meta_tags();
    }
    ?>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <?php wp_head(); ?>

    <?php
    // Generate structured data
    if (function_exists('sbs_generate_structured_data')) {
        sbs_generate_structured_data();
    }
    ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'sbs-portal'); ?></a>

        <?php if (!is_front_page()): ?>
            <header id="masthead" class="site-header" role="banner">
                <!-- Site header content for non-homepage -->
            </header>
        <?php endif; ?>