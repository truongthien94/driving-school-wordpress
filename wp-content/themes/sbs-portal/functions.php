<?php

declare(strict_types=1);

/**
 * SBS Portal Theme Functions
 * 
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function sbs_setup()
{
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('title-tag');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('customize-selective-refresh-widgets');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'sbs-portal'),
        'footer' => esc_html__('Footer Menu', 'sbs-portal'),
    ));

    // Add image sizes
    add_image_size('sbs-blog-featured', 895, 518, true);
    add_image_size('sbs-gallery', 844, 492, true);
    add_image_size('sbs-hero', 2871, 892, true);
    add_image_size('sbs-hero-circle', 408, 408, true);
    add_image_size('sbs-hero-logo-strip', 492, 92, true);
}
add_action('after_setup_theme', 'sbs_setup');

/**
 * Enqueue scripts and styles
 */
function sbs_enqueue_scripts()
{
    // Enqueue Bootstrap 5 (CSS & JS)
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3');
    // Main style depends on Bootstrap
    wp_enqueue_style('sbs-style', get_stylesheet_directory_uri() . '/assets/css/main.css', array('bootstrap-css'), '1.0.0');

    // Enqueue blog list specific stylesheet
    wp_enqueue_style('sbs-blog-list-style', get_stylesheet_directory_uri() . '/assets/css/blog-list.css', array('sbs-style'), '1.0.0');

    // Enqueue blog list specific stylesheet
    if (
        is_page_template('page-blog.php') ||
        (get_query_var('sbs_page') === 'blog-list') ||
        is_post_type_archive('blog') ||
        (is_page() && get_page_template_slug() === 'page-blog.php')
    ) {
        wp_enqueue_style('sbs-blog-list-style', get_stylesheet_directory_uri() . '/assets/css/blog-list.css', array('sbs-style'), '1.0.0');
    }

    // Enqueue Bootstrap JS (bundle includes Popper)
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);
    // Enqueue theme JS
    wp_enqueue_script('sbs-script', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery', 'bootstrap-js'), '1.0.0', true);

    // Localize script for AJAX
    wp_localize_script('sbs-script', 'sbs_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sbs_nonce'),
    ));

    // Localize theme data
    wp_localize_script('sbs-script', 'sbsThemeData', array(
        'templateDirectoryUri' => get_template_directory_uri(),
    ));
}
add_action('wp_enqueue_scripts', 'sbs_enqueue_scripts');

/**
 * Register Custom Post Types
 */

// Blog Custom Post Type
function sbs_register_blog_post_type()
{
    $labels = array(
        'name' => _x('Blog Posts', 'Post Type General Name', 'sbs-portal'),
        'singular_name' => _x('Blog Post', 'Post Type Singular Name', 'sbs-portal'),
        'menu_name' => __('Blog', 'sbs-portal'),
        'name_admin_bar' => __('Blog Post', 'sbs-portal'),
        'archives' => __('Blog Archives', 'sbs-portal'),
        'attributes' => __('Post Attributes', 'sbs-portal'),
        'parent_item_colon' => __('Parent Post:', 'sbs-portal'),
        'all_items' => __('All Posts', 'sbs-portal'),
        'add_new_item' => __('Add New Post', 'sbs-portal'),
        'add_new' => __('Add New', 'sbs-portal'),
        'new_item' => __('New Post', 'sbs-portal'),
        'edit_item' => __('Edit Post', 'sbs-portal'),
        'update_item' => __('Update Post', 'sbs-portal'),
        'view_item' => __('View Post', 'sbs-portal'),
        'view_items' => __('View Posts', 'sbs-portal'),
        'search_items' => __('Search Posts', 'sbs-portal'),
    );

    $args = array(
        'label' => __('Blog Posts', 'sbs-portal'),
        'description' => __('Blog posts for SBS Portal', 'sbs-portal'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'custom-fields'),
        'taxonomies' => array('blog_category', 'blog_tag'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-edit-large',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => 'blog',
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'blog'),
    );

    register_post_type('blog', $args);
}
add_action('init', 'sbs_register_blog_post_type', 0);

// FAQ Custom Post Type
function sbs_register_faq_post_type()
{
    $labels = array(
        'name' => _x('FAQs', 'Post Type General Name', 'sbs-portal'),
        'singular_name' => _x('FAQ', 'Post Type Singular Name', 'sbs-portal'),
        'menu_name' => __('FAQ', 'sbs-portal'),
        'name_admin_bar' => __('FAQ', 'sbs-portal'),
        'all_items' => __('All FAQs', 'sbs-portal'),
        'add_new_item' => __('Add New FAQ', 'sbs-portal'),
        'add_new' => __('Add New', 'sbs-portal'),
        'new_item' => __('New FAQ', 'sbs-portal'),
        'edit_item' => __('Edit FAQ', 'sbs-portal'),
        'update_item' => __('Update FAQ', 'sbs-portal'),
        'view_item' => __('View FAQ', 'sbs-portal'),
        'search_items' => __('Search FAQs', 'sbs-portal'),
    );

    $args = array(
        'label' => __('FAQs', 'sbs-portal'),
        'description' => __('Frequently Asked Questions', 'sbs-portal'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'custom-fields'),
        'taxonomies' => array('faq_category'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-editor-help',
        'show_in_admin_bar' => true,
        'can_export' => true,
        'has_archive' => 'faq',
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'faq'),
    );

    register_post_type('faq', $args);
}
add_action('init', 'sbs_register_faq_post_type', 0);

// Campaign Custom Post Type
function sbs_register_campaign_post_type()
{
    $labels = array(
        'name' => _x('Campaigns', 'Post Type General Name', 'sbs-portal'),
        'singular_name' => _x('Campaign', 'Post Type Singular Name', 'sbs-portal'),
        'menu_name' => __('Campaigns', 'sbs-portal'),
        'all_items' => __('All Campaigns', 'sbs-portal'),
        'add_new_item' => __('Add New Campaign', 'sbs-portal'),
        'add_new' => __('Add New', 'sbs-portal'),
        'new_item' => __('New Campaign', 'sbs-portal'),
        'edit_item' => __('Edit Campaign', 'sbs-portal'),
        'update_item' => __('Update Campaign', 'sbs-portal'),
        'view_item' => __('View Campaign', 'sbs-portal'),
        'search_items' => __('Search Campaigns', 'sbs-portal'),
    );

    $args = array(
        'label' => __('Campaigns', 'sbs-portal'),
        'description' => __('Marketing campaigns and promotions', 'sbs-portal'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-megaphone',
        'has_archive' => 'campaigns',
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'campaigns'),
    );

    register_post_type('campaign', $args);
}
add_action('init', 'sbs_register_campaign_post_type', 0);

/**
 * Register Custom Taxonomies
 */

// Blog Category Taxonomy
function sbs_register_blog_category_taxonomy()
{
    $labels = array(
        'name' => _x('Blog Categories', 'Taxonomy General Name', 'sbs-portal'),
        'singular_name' => _x('Blog Category', 'Taxonomy Singular Name', 'sbs-portal'),
        'menu_name' => __('Categories', 'sbs-portal'),
        'all_items' => __('All Categories', 'sbs-portal'),
        'new_item_name' => __('New Category Name', 'sbs-portal'),
        'add_new_item' => __('Add New Category', 'sbs-portal'),
        'edit_item' => __('Edit Category', 'sbs-portal'),
        'update_item' => __('Update Category', 'sbs-portal'),
        'search_items' => __('Search Categories', 'sbs-portal'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'blog-category'),
    );

    register_taxonomy('blog_category', array('blog'), $args);
}
add_action('init', 'sbs_register_blog_category_taxonomy', 0);

// FAQ Audience Taxonomy  
function sbs_register_faq_audience_taxonomy()
{
    $labels = array(
        'name' => _x('FAQ Audiences', 'Taxonomy General Name', 'sbs-portal'),
        'singular_name' => _x('FAQ Audience', 'Taxonomy Singular Name', 'sbs-portal'),
        'menu_name' => __('Audiences', 'sbs-portal'),
        'all_items' => __('All Audiences', 'sbs-portal'),
        'new_item_name' => __('New Audience Name', 'sbs-portal'),
        'add_new_item' => __('Add New Audience', 'sbs-portal'),
        'edit_item' => __('Edit Audience', 'sbs-portal'),
        'update_item' => __('Update Audience', 'sbs-portal'),
        'search_items' => __('Search Audiences', 'sbs-portal'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'faq-audience'),
    );

    register_taxonomy('faq_audience', array('faq'), $args);
}
add_action('init', 'sbs_register_faq_audience_taxonomy', 0);

/**
 * Get mock data
 */
function sbs_get_mock_data()
{
    $json_file = get_template_directory() . '/data/mock-data.json';
    if (file_exists($json_file)) {
        $json_data = file_get_contents($json_file);
        return json_decode($json_data, true);
    }
    return array();
}

/**
 * Helper function to get portal services
 */
function sbs_get_portal_services()
{
    $mock_data = sbs_get_mock_data();
    return isset($mock_data['portal_services']) ? $mock_data['portal_services'] : array();
}

/**
 * Helper function to get latest blog posts
 */
function sbs_get_latest_blog_posts($count = 3)
{
    $mock_data = sbs_get_mock_data();
    $posts = isset($mock_data['blog_posts']) ? $mock_data['blog_posts'] : array();
    return array_slice($posts, 0, $count);
}

/**
 * Helper function to get FAQ groups
 */
function sbs_get_faq_groups()
{
    $mock_data = sbs_get_mock_data();
    return isset($mock_data['faq_groups']) ? $mock_data['faq_groups'] : array();
}

/**
 * Helper function to get footer data
 */
function sbs_get_footer_data()
{
    $mock_data = sbs_get_mock_data();
    return isset($mock_data['footer']) ? $mock_data['footer'] : array();
}

/**
 * Add custom rewrite rules for blog list page
 */
function sbs_add_rewrite_rules()
{
    add_rewrite_rule(
        '^blog-list/?$',
        'index.php?sbs_page=blog-list',
        'top'
    );
}
add_action('init', 'sbs_add_rewrite_rules');

/**
 * Add custom query vars
 */
function sbs_add_query_vars($vars)
{
    $vars[] = 'sbs_page';
    return $vars;
}
add_filter('query_vars', 'sbs_add_query_vars');

/**
 * Handle custom page templates
 */
function sbs_template_redirect()
{
    $sbs_page = get_query_var('sbs_page');

    if ($sbs_page === 'blog-list') {
        // Load the blog list template
        get_header();
        get_template_part('templates/blog-list');
        get_footer();
        exit;
    }
}
add_action('template_redirect', 'sbs_template_redirect');

/**
 * Flush rewrite rules on theme activation
 */
function sbs_flush_rewrite_rules()
{
    sbs_add_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'sbs_flush_rewrite_rules');

/**
 * Add admin menu to flush rewrite rules manually
 */
function sbs_add_admin_menu()
{
    add_management_page(
        'SBS Rewrite Rules',
        'SBS Rewrite Rules',
        'manage_options',
        'sbs-rewrite-rules',
        'sbs_rewrite_rules_page'
    );
}
add_action('admin_menu', 'sbs_add_admin_menu');

/**
 * Admin page to flush rewrite rules
 */
function sbs_rewrite_rules_page()
{
    if (isset($_POST['flush_rules']) && wp_verify_nonce($_POST['_wpnonce'], 'sbs_flush_rules')) {
        sbs_add_rewrite_rules();
        flush_rewrite_rules();
        echo '<div class="notice notice-success"><p>Rewrite rules flushed successfully!</p></div>';
    }

?>
    <div class="wrap">
        <h1>SBS Rewrite Rules</h1>
        <p>Click the button below to flush rewrite rules and make the blog-list URL work.</p>

        <form method="post">
            <?php wp_nonce_field('sbs_flush_rules'); ?>
            <input type="submit" name="flush_rules" class="button-primary" value="Flush Rewrite Rules" />
        </form>

        <h3>Current Blog URLs:</h3>
        <ul>
            <li><strong>Blog Archive:</strong> <a href="<?php echo get_post_type_archive_link('blog'); ?>" target="_blank"><?php echo get_post_type_archive_link('blog'); ?></a></li>
            <li><strong>Blog List:</strong> <a href="<?php echo home_url('/blog-list/'); ?>" target="_blank"><?php echo home_url('/blog-list/'); ?></a></li>
        </ul>
    </div>
<?php
}

/**
 * Include Custom Fields (ACF) if available
 */
if (function_exists('acf_add_local_field_group')) {
    // Add ACF field groups here if needed
}

/**
 * Add custom body classes
 */
function sbs_body_classes($classes)
{
    if (is_front_page()) {
        $classes[] = 'sbs-portal-home';
    }

    if (is_singular('blog')) {
        $classes[] = 'sbs-blog-single';
    }

    if (is_post_type_archive('blog')) {
        $classes[] = 'sbs-blog-archive';
    }

    return $classes;
}
add_filter('body_class', 'sbs_body_classes');

/**
 * Customize excerpt length
 */
function sbs_excerpt_length($length)
{
    return 20;
}
add_filter('excerpt_length', 'sbs_excerpt_length', 999);

/**
 * Add widget support
 */
function sbs_widgets_init()
{
    register_sidebar(array(
        'name'          => esc_html__('Blog Sidebar', 'sbs-portal'),
        'id'            => 'blog-sidebar',
        'description'   => esc_html__('Add widgets here.', 'sbs-portal'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'sbs_widgets_init');

/**
 * Helper function to get icon SVG
 */
function sbs_get_icon($icon_name)
{
    $icon_map = array(
        'bus' => 'icon-bus.svg',
        'building' => 'icon-building.svg',
        'calendar' => 'icon-calendar.svg',
        'briefcase' => 'icon-briefcase.svg',
        'chevron-down' => 'icon-chevron-down.svg',
    );

    if (isset($icon_map[$icon_name])) {
        $icon_path = get_template_directory() . '/assets/images/icons/' . $icon_map[$icon_name];
        if (file_exists($icon_path)) {
            return file_get_contents($icon_path);
        }
    }

    return '<div class="icon-placeholder"></div>';
}
