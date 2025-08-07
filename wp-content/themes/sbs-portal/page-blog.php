<?php

/**
 * Template Name: Blog List Page
 * 
 * Custom page template for blog list
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<?php get_template_part('templates/blog-list'); ?>

<?php get_footer();
