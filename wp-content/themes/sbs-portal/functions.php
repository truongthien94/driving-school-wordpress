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
    // Make theme available for translation
    load_theme_textdomain('sbs-portal', get_template_directory() . '/languages');

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
    // Ensure theme styles load after Bootstrap and core block library to keep our overrides
    wp_enqueue_style('sbs-style', get_stylesheet_directory_uri() . '/assets/css/main.css', array('bootstrap-css', 'wp-block-library'), '1.0.1');

    // Enqueue blog list stylesheet (contains shared blog styles)
    wp_enqueue_style('sbs-blog-list-style', get_stylesheet_directory_uri() . '/assets/css/blog-list.css', array('sbs-style'), '1.0.0');

    // Enqueue blog detail specific stylesheet on real single blog posts OR custom route /blog-detail
    $is_blog_detail_page = (get_query_var('sbs_page') === 'blog-detail') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/blog-detail') !== false);
    if (is_singular('blog') || $is_blog_detail_page) {
        wp_enqueue_style('sbs-blog-detail-style', get_stylesheet_directory_uri() . '/assets/css/blog-detail.css', array('sbs-style'), '1.0.0');
    }

    // Enqueue campaign detail specific stylesheet when viewing campaign-detail custom page
    $is_campaign_detail_page = (get_query_var('sbs_page') === 'campaign-detail') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/campaign-detail') !== false);
    if ($is_campaign_detail_page) {
        // Enqueue dedicated CSS for campaign detail page
        wp_enqueue_style('sbs-campaign-detail-style', get_stylesheet_directory_uri() . '/assets/css/campaign-detail.css', array('sbs-style'), '1.0.0');
    }

    // Enqueue Bootstrap JS (bundle includes Popper)
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);
    // Enqueue theme JS
    wp_enqueue_script('sbs-script', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery', 'bootstrap-js'), '1.0.1', true);

    // Localize script for AJAX
    wp_localize_script('sbs-script', 'sbs_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sbs_nonce'),
    ));

    // Localize theme data
    wp_localize_script('sbs-script', 'sbsThemeData', array(
        'templateDirectoryUri' => get_template_directory_uri(),
        'restUrl' => esc_url_raw(rest_url()),
    ));
}
// Load our assets late to reduce chance of being overridden by plugins
add_action('wp_enqueue_scripts', 'sbs_enqueue_scripts', 100);

/**
 * Enqueue admin-specific scripts and styles.
 */
function sbs_enqueue_admin_scripts($hook)
{
    global $typenow;

    if (in_array($hook, ['post.php', 'post-new.php'])) {
        // Target the 'campaign' post type edit/new screens
        if ('campaign' === $typenow) {
            wp_enqueue_script(
                'sbs-admin-campaign-validation',
                get_template_directory_uri() . '/assets/js/admin-campaign.js',
                ['jquery', 'wp-data'],
                '1.0.0',
                true
            );
        }

        // Target the 'blog' post type edit/new screens
        if ('blog' === $typenow) {
            wp_enqueue_script(
                'sbs-admin-blog-validation',
                get_template_directory_uri() . '/assets/js/admin-blog.js',
                ['jquery', 'wp-data'],
                '1.0.0',
                true
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'sbs_enqueue_admin_scripts');

/**
 * Fix image upload issues and increase limits
 */
function sbs_fix_image_upload_limits()
{
    // Increase memory limit for image processing
    if (function_exists('ini_set')) {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', 300);
        @ini_set('max_input_time', 300);
    }

    // Increase upload file size limits
    if (function_exists('ini_set')) {
        @ini_set('upload_max_filesize', '64M');
        @ini_set('post_max_size', '64M');
        @ini_set('max_file_uploads', '20');
    }
}
add_action('init', 'sbs_fix_image_upload_limits');

/**
 * Disable WordPress image size validation
 */
function sbs_disable_image_size_validation($sizes, $metadata, $attachment_id)
{
    // Remove size validation for large images
    if (isset($sizes['large']) && isset($sizes['medium'])) {
        // Keep only essential sizes
        return array(
            'thumbnail' => $sizes['thumbnail'],
            'medium' => $sizes['medium'],
            'large' => $sizes['large']
        );
    }
    return $sizes;
}
add_filter('wp_generate_attachment_metadata', 'sbs_disable_image_size_validation', 10, 3);

/**
 * Increase image quality and disable compression
 */
function sbs_improve_image_quality($quality, $mime_type)
{
    // Set high quality for JPEG images
    if ($mime_type === 'image/jpeg') {
        return 95;
    }
    // Set high quality for PNG images
    if ($mime_type === 'image/png') {
        return 9;
    }
    return $quality;
}
add_filter('wp_editor_set_quality', 'sbs_improve_image_quality', 10, 2);

/**
 * Disable WordPress automatic image resizing for large images
 */
function sbs_disable_large_image_resize($sizes, $metadata, $attachment_id)
{
    // Get original image dimensions
    if (isset($metadata['width']) && isset($metadata['height'])) {
        $width = $metadata['width'];
        $height = $metadata['height'];

        // If image is larger than 2560px, don't create additional sizes
        if ($width > 2560 || $height > 2560) {
            // Only keep thumbnail and medium sizes
            return array(
                'thumbnail' => $sizes['thumbnail'],
                'medium' => $sizes['medium']
            );
        }
    }

    return $sizes;
}
add_filter('wp_generate_attachment_metadata', 'sbs_disable_large_image_resize', 15, 3);

/**
 * Add custom image sizes for better performance
 */
function sbs_add_custom_image_sizes()
{
    // Add optimized image sizes
    add_image_size('sbs-optimized-large', 1920, 1080, false);
    add_image_size('sbs-optimized-medium', 1200, 675, false);
    add_image_size('sbs-optimized-small', 800, 450, false);
}
add_action('after_setup_theme', 'sbs_add_custom_image_sizes');

/**
 * SEO Functions and Meta Tags
 */



/**
 * Generate JSON-LD structured data
 */
function sbs_generate_structured_data()
{
    $schema = array();

    // Organization schema
    $organization = array(
        '@context' => 'https://schema.org',
        '@type' => 'EducationalOrganization',
        'name' => 'SBS Driving School',
        'alternateName' => 'SBSドライビングスクール',
        'url' => home_url(),
        'logo' => get_template_directory_uri() . '/assets/images/logo-circle.png',
        'description' => 'SBS Driving School Portal - 姉崎・稲毛校の総合ポータルサイト',
        'address' => array(
            '@type' => 'PostalAddress',
            'addressCountry' => 'JP',
            'addressRegion' => '千葉県'
        ),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
            'availableLanguage' => array('Japanese', 'English')
        )
    );

    if (is_front_page()) {
        $schema[] = $organization;

        // Website schema
        $website = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url(),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => 'SBS Driving School'
            )
        );
        $schema[] = $website;
    }

    if (is_singular('blog')) {
        global $post;
        if ($post) {
            $article = array(
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => get_the_title(),
                'description' => wp_trim_words(strip_tags($post->post_content), 30, '...'),
                'datePublished' => get_the_date('c'),
                'dateModified' => get_the_modified_date('c'),
                'author' => array(
                    '@type' => 'Organization',
                    'name' => 'SBS Driving School'
                ),
                'publisher' => array(
                    '@type' => 'Organization',
                    'name' => 'SBS Driving School',
                    'logo' => array(
                        '@type' => 'ImageObject',
                        'url' => get_template_directory_uri() . '/assets/images/logo-circle.png'
                    )
                )
            );

            if (has_post_thumbnail()) {
                $article['image'] = get_the_post_thumbnail_url($post->ID, 'full');
            }

            $schema[] = $article;
        }
    }

    if (!empty($schema)) {
        foreach ($schema as $schema_item) {
            echo '<script type="application/ld+json">' . json_encode($schema_item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
        }
    }
}

/**
 * Optimize page titles for SEO
 */
function sbs_custom_document_title_parts($title)
{
    if (is_front_page()) {
        $title['title'] = 'SBS Driving School Portal';
        $title['tagline'] = 'ドライビングスクール総合ポータルサイト';
    } elseif (is_page('blog') || get_query_var('sbs_page') === 'blog-list') {
        $title['title'] = 'ブログ・ニュース一覧';
        $title['site'] = 'SBS Driving School';
    } elseif (is_singular('blog')) {
        // Keep default title for blog posts
        $title['site'] = 'SBS Driving School';
    }

    return $title;
}
add_filter('document_title_parts', 'sbs_custom_document_title_parts');

/**
 * Add custom meta box for SEO on posts and pages
 */
function sbs_add_seo_meta_boxes()
{
    add_meta_box(
        'sbs_seo_meta',
        'SEO Settings',
        'sbs_seo_meta_box_callback',
        array('post', 'page', 'blog', 'campaign'),
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sbs_add_seo_meta_boxes');

/**
 * SEO meta box callback
 */
function sbs_seo_meta_box_callback($post)
{
    wp_nonce_field('sbs_seo_meta_nonce', 'sbs_seo_meta_nonce');

    $meta_description = get_post_meta($post->ID, '_sbs_meta_description', true);
    $meta_keywords = get_post_meta($post->ID, '_sbs_meta_keywords', true);
    $focus_keyword = get_post_meta($post->ID, '_sbs_focus_keyword', true);
?>
    <table class="form-table">
        <tr>
            <th><label for="sbs_meta_description">Meta Description</label></th>
            <td>
                <textarea id="sbs_meta_description" name="sbs_meta_description" rows="3" cols="50" maxlength="160"><?php echo esc_textarea($meta_description); ?></textarea>
                <p class="description">Recommended length: 120-160 characters</p>
            </td>
        </tr>
        <tr>
            <th><label for="sbs_meta_keywords">Meta Keywords</label></th>
            <td>
                <input type="text" id="sbs_meta_keywords" name="sbs_meta_keywords" value="<?php echo esc_attr($meta_keywords); ?>" size="50" />
                <p class="description">Separate keywords with commas</p>
            </td>
        </tr>
        <tr>
            <th><label for="sbs_focus_keyword">Focus Keyword</label></th>
            <td>
                <input type="text" id="sbs_focus_keyword" name="sbs_focus_keyword" value="<?php echo esc_attr($focus_keyword); ?>" size="30" />
                <p class="description">Primary keyword for this content</p>
            </td>
        </tr>
    </table>
<?php
}

/**
 * Save SEO meta box data
 */
function sbs_save_seo_meta_box($post_id)
{
    if (!isset($_POST['sbs_seo_meta_nonce']) || !wp_verify_nonce($_POST['sbs_seo_meta_nonce'], 'sbs_seo_meta_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['sbs_meta_description'])) {
        update_post_meta($post_id, '_sbs_meta_description', sanitize_textarea_field($_POST['sbs_meta_description']));
    }

    if (isset($_POST['sbs_meta_keywords'])) {
        update_post_meta($post_id, '_sbs_meta_keywords', sanitize_text_field($_POST['sbs_meta_keywords']));
    }

    if (isset($_POST['sbs_focus_keyword'])) {
        update_post_meta($post_id, '_sbs_focus_keyword', sanitize_text_field($_POST['sbs_focus_keyword']));
    }
}
add_action('save_post', 'sbs_save_seo_meta_box');

/**
 * Update SEO meta tags function to use custom meta fields
 */
function sbs_generate_seo_meta_tags()
{
    global $post;

    // Get current page info
    $page_title = wp_get_document_title();
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $current_url = home_url(add_query_arg(NULL, NULL));

    // Default meta description
    $meta_description = $site_description;
    $meta_keywords = '';
    $og_image = get_template_directory_uri() . '/assets/images/hero-bg-main-f14c9b.jpg';

    // Check for custom meta fields first
    if (is_singular() && $post) {
        $custom_description = get_post_meta($post->ID, '_sbs_meta_description', true);
        $custom_keywords = get_post_meta($post->ID, '_sbs_meta_keywords', true);

        if (!empty($custom_description)) {
            $meta_description = $custom_description;
        }
        if (!empty($custom_keywords)) {
            $meta_keywords = $custom_keywords;
        }
    }

    // Page-specific meta data
    if (is_front_page()) {
        if (empty($meta_description) || $meta_description === $site_description) {
            $meta_description = 'SBS Driving School Portal - ドライビングスクール予約システム、求人マッチング、キャンペーン情報を一括管理。姉崎・稲毛校の最新情報をお届けします。';
        }
        if (empty($meta_keywords)) {
            $meta_keywords = 'SBS自動車学校, ドライビングスクール, 姉崎, 稲毛, 予約システム, 求人マッチング, キャンペーン';
        }
    } elseif (is_singular('blog')) {
        if ($post && empty($meta_description)) {
            $meta_description = wp_trim_words(strip_tags($post->post_content), 30, '...');
        }
        if (has_post_thumbnail($post->ID)) {
            $og_image = get_the_post_thumbnail_url($post->ID, 'full');
        }
    } elseif (is_page('blog') || get_query_var('sbs_page') === 'blog-list') {
        if (empty($meta_description)) {
            $meta_description = 'SBS Driving School最新ニュース・ブログ記事一覧。教習所の最新情報、キャンペーン、お役立ち情報をお届けします。';
        }
        if (empty($meta_keywords)) {
            $meta_keywords = 'SBS, ブログ, ニュース, 教習所, 最新情報, キャンペーン';
        }
    }

    // Output meta tags
?>
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr($meta_description); ?>">
    <?php if ($meta_keywords): ?>
        <meta name="keywords" content="<?php echo esc_attr($meta_keywords); ?>">
    <?php endif; ?>
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo esc_url($current_url); ?>">

    <!-- Open Graph Tags -->
    <meta property="og:type" content="<?php echo is_front_page() ? 'website' : 'article'; ?>">
    <meta property="og:title" content="<?php echo esc_attr($page_title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($meta_description); ?>">
    <meta property="og:url" content="<?php echo esc_url($current_url); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    <meta property="og:image" content="<?php echo esc_url($og_image); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="ja_JP">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($page_title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($meta_description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">

    <!-- Additional SEO Meta -->
    <meta name="theme-color" content="#f14c9b">
    <meta name="msapplication-TileColor" content="#f14c9b">
<?php
}

/**
 * Add XML Sitemap functionality
 */
function sbs_enable_xml_sitemaps()
{
    // Add sitemap rewrite rules
    add_rewrite_rule('^sitemap\.xml$', 'index.php?sbs_sitemap=1', 'top');
    add_rewrite_rule('^sitemap-posts\.xml$', 'index.php?sbs_sitemap=posts', 'top');
    add_rewrite_rule('^sitemap-pages\.xml$', 'index.php?sbs_sitemap=pages', 'top');
}
add_action('init', 'sbs_enable_xml_sitemaps');

/**
 * Flush rewrite rules on theme activation
 */
function sbs_flush_rewrites()
{
    sbs_enable_xml_sitemaps();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'sbs_flush_rewrites');

/**
 * Add sitemap query vars
 */
function sbs_add_sitemap_query_vars($vars)
{
    $vars[] = 'sbs_sitemap';
    return $vars;
}
add_filter('query_vars', 'sbs_add_sitemap_query_vars');

/**
 * Handle sitemap requests
 */
function sbs_handle_sitemap_request()
{
    global $wp_query;

    if (get_query_var('sbs_sitemap')) {
        $sitemap_type = get_query_var('sbs_sitemap');

        header('Content-Type: application/xml; charset=utf-8');

        if ($sitemap_type === '1') {
            // Main sitemap index
            sbs_generate_sitemap_index();
        } elseif ($sitemap_type === 'posts') {
            // Posts sitemap
            sbs_generate_posts_sitemap();
        } elseif ($sitemap_type === 'pages') {
            // Pages sitemap
            sbs_generate_pages_sitemap();
        }

        exit;
    }
}
add_action('template_redirect', 'sbs_handle_sitemap_request');

/**
 * Generate sitemap index
 */
function sbs_generate_sitemap_index()
{
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    echo '<sitemap><loc>' . home_url('/sitemap-pages.xml') . '</loc></sitemap>' . "\n";
    echo '<sitemap><loc>' . home_url('/sitemap-posts.xml') . '</loc></sitemap>' . "\n";
    echo '</sitemapindex>';
}

/**
 * Generate posts sitemap
 */
function sbs_generate_posts_sitemap()
{
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => array('blog', 'campaign'),
        'post_status' => 'publish'
    ));

    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($posts as $post) {
        echo '<url>' . "\n";
        echo '<loc>' . get_permalink($post->ID) . '</loc>' . "\n";
        echo '<lastmod>' . get_the_modified_date('c', $post->ID) . '</lastmod>' . "\n";
        echo '<changefreq>weekly</changefreq>' . "\n";
        echo '<priority>0.8</priority>' . "\n";
        echo '</url>' . "\n";
    }

    echo '</urlset>';
}

/**
 * Generate pages sitemap
 */
function sbs_generate_pages_sitemap()
{
    $pages = get_pages(array(
        'post_status' => 'publish'
    ));

    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    // Add homepage
    echo '<url>' . "\n";
    echo '<loc>' . home_url('/') . '</loc>' . "\n";
    echo '<lastmod>' . wp_date('c') . '</lastmod>' . "\n";
    echo '<changefreq>weekly</changefreq>' . "\n";
    echo '<priority>1.0</priority>' . "\n";
    echo '</url>' . "\n";

    // Add custom pages
    $custom_pages = array(
        '/blog-list/' => array('priority' => '0.8', 'changefreq' => 'daily'),
    );

    foreach ($custom_pages as $url => $config) {
        echo '<url>' . "\n";
        echo '<loc>' . home_url($url) . '</loc>' . "\n";
        echo '<lastmod>' . wp_date('c') . '</lastmod>' . "\n";
        echo '<changefreq>' . $config['changefreq'] . '</changefreq>' . "\n";
        echo '<priority>' . $config['priority'] . '</priority>' . "\n";
        echo '</url>' . "\n";
    }

    // Add WordPress pages
    foreach ($pages as $page) {
        echo '<url>' . "\n";
        echo '<loc>' . get_permalink($page->ID) . '</loc>' . "\n";
        echo '<lastmod>' . get_the_modified_date('c', $page->ID) . '</lastmod>' . "\n";
        echo '<changefreq>monthly</changefreq>' . "\n";
        echo '<priority>0.6</priority>' . "\n";
        echo '</url>' . "\n";
    }

    echo '</urlset>';
}

/**
 * Register Custom Post Types
 */

/**
 * Register Campaign Post Type
 */
function sbs_register_campaign_post_type()
{
    $labels = array(
        'name'               => _x('Campaigns', 'post type general name', 'sbs-portal'),
        'singular_name'      => _x('Campaign', 'post type singular name', 'sbs-portal'),
        'menu_name'          => _x('Campaigns', 'admin menu', 'sbs-portal'),
        'name_admin_bar'     => _x('Campaign', 'add new on admin bar', 'sbs-portal'),
        'add_new'            => _x('Add New', 'campaign', 'sbs-portal'),
        'add_new_item'       => __('Add New Campaign', 'sbs-portal'),
        'new_item'           => __('New Campaign', 'sbs-portal'),
        'edit_item'          => __('Edit Campaign', 'sbs-portal'),
        'view_item'          => __('View Campaign', 'sbs-portal'),
        'all_items'          => __('All Campaigns', 'sbs-portal'),
        'search_items'       => __('Search Campaigns', 'sbs-portal'),
        'parent_item_colon'  => __('Parent Campaigns:', 'sbs-portal'),
        'not_found'          => __('No campaigns found.', 'sbs-portal'),
        'not_found_in_trash' => __('No campaigns found in Trash.', 'sbs-portal')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Campaign custom post type.', 'sbs-portal'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'campaign'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'          => 'dashicons-megaphone'
    );

    register_post_type('campaign', $args);
}
add_action('init', 'sbs_register_campaign_post_type');

/**
 * Add Campaign meta boxes
 */
function sbs_add_campaign_meta_boxes()
{
    add_meta_box(
        'sbs_campaign_highlight',
        __('Campaign Highlight', 'sbs-portal'),
        'sbs_campaign_highlight_meta_box_callback',
        'campaign',
        'side',
        'high'
    );

    add_meta_box(
        'sbs_campaign_metrics',
        __('Campaign Metrics', 'sbs-portal'),
        'sbs_campaign_metrics_meta_box_callback',
        'campaign',
        'side',
        'default'
    );

    add_meta_box(
        'sbs_campaign_settings',
        __('Campaign Settings', 'sbs-portal'),
        'sbs_campaign_settings_meta_box_callback',
        'campaign',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sbs_add_campaign_meta_boxes');

/**
 * Campaign highlight meta box callback
 */
function sbs_campaign_highlight_meta_box_callback($post)
{
    wp_nonce_field('sbs_campaign_highlight_meta_box', 'sbs_campaign_highlight_meta_box_nonce');

    $is_highlight = get_post_meta($post->ID, '_campaign_highlight', true);
    $current_highlight = sbs_get_highlighted_campaign();

?>
    <label>
        <input type="checkbox" name="campaign_highlight" value="1" <?php checked($is_highlight, '1'); ?> />
        <?php _e('Set as highlighted campaign', 'sbs-portal'); ?>
    </label>

    <?php if ($current_highlight && $current_highlight['id'] != $post->ID): ?>
        <p style="color: #d63638; font-size: 12px; margin-top: 8px;">
            <?php printf(__('Note: "%s" is currently highlighted. Checking this will remove the highlight from that campaign.', 'sbs-portal'), esc_html($current_highlight['title'])); ?>
        </p>
    <?php endif; ?>

    <p style="color: #666; font-size: 12px; margin-top: 8px;">
        <?php _e('Only one campaign can be highlighted at a time. The highlighted campaign will appear in the popup banner.', 'sbs-portal'); ?>
    </p>
<?php
}

/**
 * Save Campaign meta boxes
 */
function sbs_save_campaign_meta_boxes($post_id)
{
    // Check if user has permissions to save data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if not an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save campaign highlight
    if (
        isset($_POST['sbs_campaign_highlight_meta_box_nonce']) &&
        wp_verify_nonce($_POST['sbs_campaign_highlight_meta_box_nonce'], 'sbs_campaign_highlight_meta_box')
    ) {

        $is_highlight = isset($_POST['campaign_highlight']) ? '1' : '0';

        if ($is_highlight === '1') {
            // Remove highlight from all other campaigns first
            $args = array(
                'post_type' => 'campaign',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => '_campaign_highlight',
                        'value' => '1',
                        'compare' => '='
                    )
                )
            );

            $highlighted_campaigns = get_posts($args);
            foreach ($highlighted_campaigns as $campaign) {
                if ($campaign->ID != $post_id) {
                    update_post_meta($campaign->ID, '_campaign_highlight', '0');
                }
            }

            // Set this campaign as highlighted
            update_post_meta($post_id, '_campaign_highlight', '1');
        } else {
            update_post_meta($post_id, '_campaign_highlight', '0');
        }
    }

    // Save campaign settings
    if (
        isset($_POST['sbs_campaign_settings_meta_box_nonce']) &&
        wp_verify_nonce($_POST['sbs_campaign_settings_meta_box_nonce'], 'sbs_campaign_settings_meta_box')
    ) {

        // Save campaign type
        if (isset($_POST['campaign_type'])) {
            update_post_meta($post_id, '_campaign_type', sanitize_text_field($_POST['campaign_type']));
        }

        // Save start date
        if (isset($_POST['campaign_start_date'])) {
            update_post_meta($post_id, '_campaign_start_date', sanitize_text_field($_POST['campaign_start_date']));
        }

        // Save end date
        if (isset($_POST['campaign_end_date'])) {
            update_post_meta($post_id, '_campaign_end_date', sanitize_text_field($_POST['campaign_end_date']));
        }

        // Save external URL
        if (isset($_POST['campaign_external_url'])) {
            update_post_meta($post_id, '_campaign_external_url', esc_url_raw($_POST['campaign_external_url']));
        }

        // Save target audience
        if (isset($_POST['campaign_target_audience'])) {
            update_post_meta($post_id, '_campaign_target_audience', sanitize_textarea_field($_POST['campaign_target_audience']));
        }

        // Save tracking enabled
        $tracking_enabled = isset($_POST['campaign_tracking_enabled']) ? '1' : '0';
        update_post_meta($post_id, '_campaign_tracking_enabled', $tracking_enabled);

        // Save max impressions
        if (isset($_POST['campaign_max_impressions'])) {
            update_post_meta($post_id, '_campaign_max_impressions', absint($_POST['campaign_max_impressions']));
        }

        // Save max clicks
        if (isset($_POST['campaign_max_clicks'])) {
            update_post_meta($post_id, '_campaign_max_clicks', absint($_POST['campaign_max_clicks']));
        }
    }
}
add_action('save_post', 'sbs_save_campaign_meta_boxes');

/**
 * AJAX handler to reset campaign metrics
 */
function sbs_ajax_reset_campaign_metrics()
{
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sbs_reset_metrics')) {
        wp_die(json_encode(array('success' => false, 'message' => 'Security check failed')));
    }

    // Check permissions
    if (!current_user_can('edit_posts')) {
        wp_die(json_encode(array('success' => false, 'message' => 'Insufficient permissions')));
    }

    $campaign_id = absint($_POST['campaign_id']);
    if (!$campaign_id) {
        wp_die(json_encode(array('success' => false, 'message' => 'Invalid campaign ID')));
    }

    // Check if campaign exists
    $post = get_post($campaign_id);
    if (!$post || $post->post_type !== 'campaign') {
        wp_die(json_encode(array('success' => false, 'message' => 'Campaign not found')));
    }

    // Reset metrics
    update_post_meta($campaign_id, '_campaign_impressions', 0);
    update_post_meta($campaign_id, '_campaign_clicks', 0);

    wp_die(json_encode(array(
        'success' => true,
        'message' => 'Metrics reset successfully'
    )));
}
add_action('wp_ajax_sbs_reset_campaign_metrics', 'sbs_ajax_reset_campaign_metrics');

/**
 * Get the currently highlighted campaign
 */
function sbs_get_highlighted_campaign()
{
    $args = array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => '_campaign_highlight',
                'value' => '1',
                'compare' => '='
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $post = $query->posts[0];
        wp_reset_postdata();

        return array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'featured_image' => has_post_thumbnail($post->ID) ? get_the_post_thumbnail_url($post->ID, 'full') : '',
            'permalink' => get_permalink($post->ID),
            'detail_url' => add_query_arg('post_id', $post->ID, home_url('/campaign-detail/'))
        );
    }

    return null;
}

/**
 * Campaign metrics meta box callback
 */
function sbs_campaign_metrics_meta_box_callback($post)
{
    $impressions = (int) get_post_meta($post->ID, '_campaign_impressions', true);
    $clicks = (int) get_post_meta($post->ID, '_campaign_clicks', true);
    $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

    $created_date = get_the_date('Y-m-d', $post->ID);
    $days_active = max(1, floor((current_time('timestamp') - strtotime($created_date)) / DAY_IN_SECONDS));
    $avg_impressions_per_day = round($impressions / $days_active, 1);
    $avg_clicks_per_day = round($clicks / $days_active, 1);

?>
    <div class="campaign-metrics-container">
        <div class="metrics-grid">
            <div class="metric-item">
                <div class="metric-number"><?php echo number_format($impressions); ?></div>
                <div class="metric-label"><?php _e('Total Impressions', 'sbs-portal'); ?></div>
            </div>

            <div class="metric-item">
                <div class="metric-number"><?php echo number_format($clicks); ?></div>
                <div class="metric-label"><?php _e('Total Clicks', 'sbs-portal'); ?></div>
            </div>

            <div class="metric-item">
                <div class="metric-number"><?php echo $ctr; ?>%</div>
                <div class="metric-label"><?php _e('Click-Through Rate', 'sbs-portal'); ?></div>
            </div>

            <div class="metric-item">
                <div class="metric-number"><?php echo $avg_impressions_per_day; ?></div>
                <div class="metric-label"><?php _e('Avg. Impressions/Day', 'sbs-portal'); ?></div>
            </div>
        </div>

        <div class="metrics-actions">
            <button type="button" class="button" id="reset-campaign-metrics" data-campaign-id="<?php echo $post->ID; ?>">
                <?php _e('Reset Metrics', 'sbs-portal'); ?>
            </button>
            <button type="button" class="button button-primary" id="refresh-campaign-metrics" data-campaign-id="<?php echo $post->ID; ?>">
                <?php _e('Refresh', 'sbs-portal'); ?>
            </button>
        </div>

        <div class="metrics-info">
            <p><small>
                    <?php printf(__('Campaign active for %d days since %s', 'sbs-portal'), $days_active, $created_date); ?>
                </small></p>
        </div>
    </div>

    <style>
        .campaign-metrics-container {
            padding: 10px 0;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .metric-item {
            text-align: center;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .metric-number {
            font-size: 18px;
            font-weight: bold;
            color: #2271b1;
            margin-bottom: 5px;
        }

        .metric-label {
            font-size: 12px;
            color: #666;
        }

        .metrics-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .metrics-info {
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>

    <script>
        jQuery(document).ready(function($) {
            $('#refresh-campaign-metrics').on('click', function() {
                location.reload();
            });

            $('#reset-campaign-metrics').on('click', function() {
                if (confirm('<?php _e('Are you sure you want to reset all metrics for this campaign? This action cannot be undone.', 'sbs-portal'); ?>')) {
                    var campaignId = $(this).data('campaign-id');
                    $.post(ajaxurl, {
                        action: 'sbs_reset_campaign_metrics',
                        campaign_id: campaignId,
                        nonce: '<?php echo wp_create_nonce('sbs_reset_metrics'); ?>'
                    }, function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('<?php _e('Failed to reset metrics. Please try again.', 'sbs-portal'); ?>');
                        }
                    });
                }
            });
        });
    </script>
<?php
}

/**
 * Campaign settings meta box callback
 */
function sbs_campaign_settings_meta_box_callback($post)
{
    wp_nonce_field('sbs_campaign_settings_meta_box', 'sbs_campaign_settings_meta_box_nonce');

    $start_date = get_post_meta($post->ID, '_campaign_start_date', true);
    $end_date = get_post_meta($post->ID, '_campaign_end_date', true);
    $target_audience = get_post_meta($post->ID, '_campaign_target_audience', true);
    $campaign_type = get_post_meta($post->ID, '_campaign_type', true);
    $external_url = get_post_meta($post->ID, '_campaign_external_url', true);
    $tracking_enabled = get_post_meta($post->ID, '_campaign_tracking_enabled', true) ?: '1';
    $max_impressions = get_post_meta($post->ID, '_campaign_max_impressions', true);
    $max_clicks = get_post_meta($post->ID, '_campaign_max_clicks', true);

?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="campaign_type"><?php _e('Campaign Type', 'sbs-portal'); ?></label>
            </th>
            <td>
                <select name="campaign_type" id="campaign_type">
                    <option value="promotion" <?php selected($campaign_type, 'promotion'); ?>><?php _e('Promotion', 'sbs-portal'); ?></option>
                    <option value="announcement" <?php selected($campaign_type, 'announcement'); ?>><?php _e('Announcement', 'sbs-portal'); ?></option>
                    <option value="event" <?php selected($campaign_type, 'event'); ?>><?php _e('Event', 'sbs-portal'); ?></option>
                    <option value="course" <?php selected($campaign_type, 'course'); ?>><?php _e('Course', 'sbs-portal'); ?></option>
                    <option value="other" <?php selected($campaign_type, 'other'); ?>><?php _e('Other', 'sbs-portal'); ?></option>
                </select>
                <p class="description"><?php _e('Select the type of campaign', 'sbs-portal'); ?></p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="campaign_start_date"><?php _e('Start Date', 'sbs-portal'); ?></label>
            </th>
            <td>
                <input type="date" name="campaign_start_date" id="campaign_start_date" value="<?php echo esc_attr($start_date); ?>" />
                <p class="description"><?php _e('When should this campaign start being displayed?', 'sbs-portal'); ?></p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="campaign_end_date"><?php _e('End Date', 'sbs-portal'); ?></label>
            </th>
            <td>
                <input type="date" name="campaign_end_date" id="campaign_end_date" value="<?php echo esc_attr($end_date); ?>" />
                <p class="description"><?php _e('When should this campaign stop being displayed?', 'sbs-portal'); ?></p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="campaign_external_url"><?php _e('External URL', 'sbs-portal'); ?></label>
            </th>
            <td>
                <input type="url" name="campaign_external_url" id="campaign_external_url" value="<?php echo esc_attr($external_url); ?>" class="large-text" />
                <p class="description"><?php _e('Optional: External URL to redirect to instead of campaign detail page', 'sbs-portal'); ?></p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="campaign_target_audience"><?php _e('Target Audience', 'sbs-portal'); ?></label>
            </th>
            <td>
                <textarea name="campaign_target_audience" id="campaign_target_audience" rows="3" class="large-text"><?php echo esc_textarea($target_audience); ?></textarea>
                <p class="description"><?php _e('Describe the target audience for this campaign', 'sbs-portal'); ?></p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="campaign_tracking_enabled"><?php _e('Enable Tracking', 'sbs-portal'); ?></label>
            </th>
            <td>
                <label>
                    <input type="checkbox" name="campaign_tracking_enabled" id="campaign_tracking_enabled" value="1" <?php checked($tracking_enabled, '1'); ?> />
                    <?php _e('Track impressions and clicks for this campaign', 'sbs-portal'); ?>
                </label>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="max_impressions"><?php _e('Max Impressions', 'sbs-portal'); ?></label>
            </th>
            <td>
                <input type="number" name="campaign_max_impressions" id="max_impressions" value="<?php echo esc_attr($max_impressions); ?>" min="0" />
                <p class="description"><?php _e('Stop displaying after this many impressions (0 = unlimited)', 'sbs-portal'); ?></p>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="max_clicks"><?php _e('Max Clicks', 'sbs-portal'); ?></label>
            </th>
            <td>
                <input type="number" name="campaign_max_clicks" id="max_clicks" value="<?php echo esc_attr($max_clicks); ?>" min="0" />
                <p class="description"><?php _e('Stop displaying after this many clicks (0 = unlimited)', 'sbs-portal'); ?></p>
            </td>
        </tr>
    </table>
<?php
}

/**
 * Register Hero Item Post Type
 */
function sbs_register_hero_item_post_type()
{
    $labels = array(
        'name'               => _x('Hero Items', 'post type general name', 'sbs-portal'),
        'singular_name'      => _x('Hero Item', 'post type singular name', 'sbs-portal'),
        'menu_name'          => _x('Hero Items', 'admin menu', 'sbs-portal'),
        'name_admin_bar'     => _x('Hero Item', 'add new on admin bar', 'sbs-portal'),
        'add_new'            => _x('Add New', 'hero item', 'sbs-portal'),
        'add_new_item'       => __('Add New Hero Item', 'sbs-portal'),
        'new_item'           => __('New Hero Item', 'sbs-portal'),
        'edit_item'          => __('Edit Hero Item', 'sbs-portal'),
        'view_item'          => __('View Hero Item', 'sbs-portal'),
        'all_items'          => __('All Hero Items', 'sbs-portal'),
        'search_items'       => __('Search Hero Items', 'sbs-portal'),
        'parent_item_colon'  => __('Parent Hero Items:', 'sbs-portal'),
        'not_found'          => __('No hero items found.', 'sbs-portal'),
        'not_found_in_trash' => __('No hero items found in Trash.', 'sbs-portal')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Hero Item custom post type for portal homepage.', 'sbs-portal'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'hero-item'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'menu_icon'          => 'dashicons-star-filled',
        'show_in_rest'       => true,
    );

    register_post_type('hero_item', $args);
}
add_action('init', 'sbs_register_hero_item_post_type');

/**
 * Register FAQ Group Post Type
 */
function sbs_register_faq_group_post_type()
{
    $labels = array(
        'name'               => _x('FAQ Groups', 'post type general name', 'sbs-portal'),
        'singular_name'      => _x('FAQ Group', 'post type singular name', 'sbs-portal'),
        'menu_name'          => _x('FAQ Groups', 'admin menu', 'sbs-portal'),
        'name_admin_bar'     => _x('FAQ Group', 'add new on admin bar', 'sbs-portal'),
        'add_new'            => _x('Add New', 'faq group', 'sbs-portal'),
        'add_new_item'       => __('Add New FAQ Group', 'sbs-portal'),
        'new_item'           => __('New FAQ Group', 'sbs-portal'),
        'edit_item'          => __('Edit FAQ Group', 'sbs-portal'),
        'view_item'          => __('View FAQ Group', 'sbs-portal'),
        'all_items'          => __('All FAQ Groups', 'sbs-portal'),
        'search_items'       => __('Search FAQ Groups', 'sbs-portal'),
        'not_found'          => __('No FAQ groups found.', 'sbs-portal'),
        'not_found_in_trash' => __('No FAQ groups found in Trash.', 'sbs-portal')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('FAQ Groups for organizing FAQs.', 'sbs-portal'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'faq-group'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title'),
        'menu_icon'          => 'dashicons-format-chat',
        'show_in_rest'       => true,
    );

    register_post_type('faq_group', $args);
}
add_action('init', 'sbs_register_faq_group_post_type');

/**
 * Add custom meta boxes for Hero Items
 */
function sbs_add_hero_item_meta_boxes()
{
    add_meta_box(
        'sbs_hero_item_details',
        __('Hero Item Details', 'sbs-portal'),
        'sbs_hero_item_meta_box_callback',
        'hero_item',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sbs_add_hero_item_meta_boxes');

/**
 * Meta box callback function
 */
function sbs_hero_item_meta_box_callback($post)
{
    // Add nonce for security
    wp_nonce_field('sbs_hero_item_meta_box', 'sbs_hero_item_meta_box_nonce');

    // Get current values
    $description = get_post_meta($post->ID, '_hero_item_description', true);
    $link = get_post_meta($post->ID, '_hero_item_link', true);
    $icon = get_post_meta($post->ID, '_hero_item_icon', true);
    $order = get_post_meta($post->ID, '_hero_item_order', true);

    // Available icons
    $available_icons = array(
        'bus' => 'Bus Icon',
        'building' => 'Building Icon',
        'calendar' => 'Calendar Icon',
        'briefcase' => 'Briefcase Icon',
    );

?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="hero_item_description"><?php _e('Description', 'sbs-portal'); ?></label>
            </th>
            <td>
                <textarea name="hero_item_description" id="hero_item_description" rows="3" class="large-text"><?php echo esc_textarea($description); ?></textarea>
                <p class="description"><?php _e('Enter a brief description for this hero item.', 'sbs-portal'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="hero_item_link"><?php _e('Link URL', 'sbs-portal'); ?></label>
            </th>
            <td>
                <input type="url" name="hero_item_link" id="hero_item_link" value="<?php echo esc_url($link); ?>" class="large-text" />
                <p class="description"><?php _e('Enter the URL this item should link to (optional).', 'sbs-portal'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="hero_item_icon"><?php _e('Icon', 'sbs-portal'); ?></label>
            </th>
            <td>
                <select name="hero_item_icon" id="hero_item_icon">
                    <option value=""><?php _e('Select an icon', 'sbs-portal'); ?></option>
                    <?php foreach ($available_icons as $icon_value => $icon_label) : ?>
                        <option value="<?php echo esc_attr($icon_value); ?>" <?php selected($icon, $icon_value); ?>>
                            <?php echo esc_html($icon_label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description"><?php _e('Select an icon to display with this hero item.', 'sbs-portal'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="hero_item_order"><?php _e('Display Order', 'sbs-portal'); ?></label>
            </th>
            <td>
                <input type="number" name="hero_item_order" id="hero_item_order" value="<?php echo esc_attr($order); ?>" min="1" max="10" />
                <p class="description"><?php _e('Set the display order (1 = first, 10 = last).', 'sbs-portal'); ?></p>
            </td>
        </tr>
    </table>
<?php
}

/**
 * Save meta box data
 */
function sbs_save_hero_item_meta_box($post_id)
{
    // Check if nonce is valid
    if (!isset($_POST['sbs_hero_item_meta_box_nonce']) || !wp_verify_nonce($_POST['sbs_hero_item_meta_box_nonce'], 'sbs_hero_item_meta_box')) {
        return;
    }

    // Check if user has permissions to save data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if not an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save the data
    if (isset($_POST['hero_item_description'])) {
        update_post_meta($post_id, '_hero_item_description', sanitize_textarea_field($_POST['hero_item_description']));
    }

    if (isset($_POST['hero_item_link'])) {
        update_post_meta($post_id, '_hero_item_link', esc_url_raw($_POST['hero_item_link']));
    }

    if (isset($_POST['hero_item_icon'])) {
        update_post_meta($post_id, '_hero_item_icon', sanitize_text_field($_POST['hero_item_icon']));
    }

    if (isset($_POST['hero_item_order'])) {
        update_post_meta($post_id, '_hero_item_order', intval($_POST['hero_item_order']));
    }
}
add_action('save_post', 'sbs_save_hero_item_meta_box');

/**
 * Add custom meta boxes for FAQ Groups
 */
function sbs_add_faq_group_meta_boxes()
{
    add_meta_box(
        'sbs_faq_group_details',
        __('FAQ Group Details', 'sbs-portal'),
        'sbs_faq_group_meta_box_callback',
        'faq_group',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sbs_add_faq_group_meta_boxes');

/**
 * Meta box callback for FAQ Group
 */
function sbs_faq_group_meta_box_callback($post)
{
    wp_nonce_field('sbs_faq_group_meta_box', 'sbs_faq_group_meta_box_nonce');

    $color = get_post_meta($post->ID, '_faq_group_color', true) ?: '#DD1F01';
    $expanded = get_post_meta($post->ID, '_faq_group_expanded', true);
    $order = get_post_meta($post->ID, '_faq_group_order', true);
    $questions = get_post_meta($post->ID, '_faq_group_questions', true) ?: array();

?>
    <div id="faq-group-container">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="faq_group_color"><?php _e('Group Color', 'sbs-portal'); ?></label></th>
                <td><input type="color" id="faq_group_color" name="faq_group_color" value="<?php echo esc_attr($color); ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="faq_group_expanded"><?php _e('Expanded by Default', 'sbs-portal'); ?></label></th>
                <td><input type="checkbox" id="faq_group_expanded" name="faq_group_expanded" value="1" <?php checked($expanded, '1'); ?>></td>
            </tr>
            <tr>
                <th scope="row"><label for="faq_group_order"><?php _e('Display Order', 'sbs-portal'); ?></label></th>
                <td><input type="number" id="faq_group_order" name="faq_group_order" value="<?php echo esc_attr($order); ?>" class="small-text" min="1"></td>
            </tr>
        </table>

        <hr>
        <h3><?php _e('Questions & Answers', 'sbs-portal'); ?></h3>
        <div id="faq-questions-wrapper">
            <?php if (!empty($questions)) : foreach ($questions as $index => $q) : ?>
                    <div class="faq-question-item" data-index="<?php echo $index; ?>">
                        <p>
                            <label><strong><?php _e('Question', 'sbs-portal'); ?>:</strong></label>
                            <input type="text" name="faq_questions[<?php echo $index; ?>][question]" value="<?php echo esc_attr($q['question']); ?>" class="large-text">
                        </p>
                        <p>
                            <label><strong><?php _e('Brief Answer', 'sbs-portal'); ?>:</strong></label>
                            <textarea name="faq_questions[<?php echo $index; ?>][answer]" class="large-text"><?php echo esc_textarea($q['answer']); ?></textarea>
                        </p>
                        <p>
                            <label><strong><?php _e('Detailed Answer', 'sbs-portal'); ?>:</strong></label>
                            <textarea name="faq_questions[<?php echo $index; ?>][detail]" class="large-text"><?php echo esc_textarea($q['detail']); ?></textarea>
                        </p>
                        <p>
                            <label><strong><?php _e('Expanded by Default', 'sbs-portal'); ?>:</strong></label>
                            <input type="checkbox" name="faq_questions[<?php echo $index; ?>][expanded]" value="1" <?php checked($q['expanded'] ?? '', '1'); ?>>
                        </p>
                        <button type="button" class="button remove-faq-question"><?php _e('Remove Question', 'sbs-portal'); ?></button>
                        <hr>
                    </div>
            <?php endforeach;
            endif; ?>
        </div>
        <button type="button" class="button" id="add-faq-question"><?php _e('Add Question', 'sbs-portal'); ?></button>
    </div>
    <script>
        jQuery(document).ready(function($) {
            var questionIndex = <?php echo count($questions); ?>;
            $('#add-faq-question').on('click', function() {
                var newQuestion = `
                <div class="faq-question-item" data-index="${questionIndex}">
                    <p>
                        <label><strong><?php _e('Question', 'sbs-portal'); ?>:</strong></label>
                        <input type="text" name="faq_questions[${questionIndex}][question]" value="" class="large-text">
                    </p>
                    <p>
                        <label><strong><?php _e('Brief Answer', 'sbs-portal'); ?>:</strong></label>
                        <textarea name="faq_questions[${questionIndex}][answer]" class="large-text"></textarea>
                    </p>
                    <p>
                        <label><strong><?php _e('Detailed Answer', 'sbs-portal'); ?>:</strong></label>
                        <textarea name="faq_questions[${questionIndex}][detail]" class="large-text"></textarea>
                    </p>
                     <p>
                        <label><strong><?php _e('Expanded by Default', 'sbs-portal'); ?>:</strong></label>
                        <input type="checkbox" name="faq_questions[${questionIndex}][expanded]" value="1">
                    </p>
                    <button type="button" class="button remove-faq-question"><?php _e('Remove Question', 'sbs-portal'); ?></button>
                    <hr>
                </div>`;
                $('#faq-questions-wrapper').append(newQuestion);
                questionIndex++;
            });

            $('#faq-questions-wrapper').on('click', '.remove-faq-question', function() {
                $(this).closest('.faq-question-item').remove();
            });
        });
    </script>
<?php
}

/**
 * Add custom columns to Hero Items admin list
 */
function sbs_add_hero_item_admin_columns($columns)
{
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['hero_item_description'] = __('Description', 'sbs-portal');
            $new_columns['hero_item_link'] = __('Link', 'sbs-portal');
            $new_columns['hero_item_icon'] = __('Icon', 'sbs-portal');
            $new_columns['hero_item_order'] = __('Order', 'sbs-portal');
        }
    }
    return $new_columns;
}
add_filter('manage_hero_item_posts_columns', 'sbs_add_hero_item_admin_columns');

/**
 * Display custom column content
 */
function sbs_hero_item_admin_column_content($column, $post_id)
{
    switch ($column) {
        case 'hero_item_description':
            $description = get_post_meta($post_id, '_hero_item_description', true);
            echo esc_html($description ?: '—');
            break;
        case 'hero_item_link':
            $link = get_post_meta($post_id, '_hero_item_link', true);
            if ($link) {
                echo '<a href="' . esc_url($link) . '" target="_blank">' . esc_url($link) . '</a>';
            } else {
                echo '—';
            }
            break;
        case 'hero_item_icon':
            $icon = get_post_meta($post_id, '_hero_item_icon', true);
            echo esc_html($icon ?: '—');
            break;
        case 'hero_item_order':
            $order = get_post_meta($post_id, '_hero_item_order', true);
            echo esc_html($order ?: '—');
            break;
    }
}
add_action('manage_hero_item_posts_custom_column', 'sbs_hero_item_admin_column_content', 10, 2);

/**
 * Make custom columns sortable
 */
function sbs_hero_item_sortable_columns($columns)
{
    $columns['hero_item_order'] = 'hero_item_order';
    return $columns;
}
add_filter('manage_edit-hero_item_sortable_columns', 'sbs_hero_item_sortable_columns');

/**
 * Handle custom column sorting
 */
function sbs_hero_item_admin_orderby($query)
{
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('hero_item_order' === $orderby) {
        $query->set('meta_key', '_hero_item_order');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'sbs_hero_item_admin_orderby');

/**
 * Add quick edit fields for Hero Items
 */
function sbs_hero_item_quick_edit_fields($column_name, $post_type)
{
    if ($post_type !== 'hero_item' || $column_name !== 'hero_item_order') {
        return;
    }
?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="inline-edit-group">
                <span class="title"><?php _e('Order', 'sbs-portal'); ?></span>
                <input type="number" name="hero_item_order" value="" min="1" max="10" />
            </label>
        </div>
    </fieldset>
<?php
}
add_action('quick_edit_custom_box', 'sbs_hero_item_quick_edit_fields', 10, 2);

/**
 * Save quick edit data
 */
function sbs_hero_item_quick_edit_save($post_id)
{
    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check if user has permissions to save data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if our custom field is set
    if (isset($_POST['hero_item_order'])) {
        update_post_meta($post_id, '_hero_item_order', intval($_POST['hero_item_order']));
    }
}
add_action('save_post', 'sbs_hero_item_quick_edit_save');

/**
 * Create sample hero items on theme activation
 */
function sbs_create_sample_hero_items()
{
    // Check if hero items already exist
    $existing_items = get_posts(array(
        'post_type' => 'hero_item',
        'post_status' => 'publish',
        'posts_per_page' => 1,
    ));

    if (!empty($existing_items)) {
        return; // Don't create duplicates
    }

    $sample_items = array(
        array(
            'title' => 'SBS自動車',
            'description' => '自動車整備・販売・リースの総合サービス',
            'link' => '#',
            'icon' => 'car',
            'order' => 1,
        ),
        array(
            'title' => 'SBSドライビングスクール姉崎',
            'description' => '安全運転のプロフェッショナルを育てる',
            'link' => '#',
            'icon' => 'bus',
            'order' => 2,
        ),
        array(
            'title' => 'SBSドライビングスクール稲毛',
            'description' => '地域密着型の運転免許取得支援',
            'link' => '#',
            'icon' => 'bus',
            'order' => 3,
        ),
        array(
            'title' => '姉崎詳細',
            'description' => '姉崎校の詳細情報とアクセス',
            'link' => '#',
            'icon' => 'building',
            'order' => 4,
        ),
        array(
            'title' => '稲毛詳細',
            'description' => '稲毛校の詳細情報とアクセス',
            'link' => '#',
            'icon' => 'building',
            'order' => 5,
        ),
        array(
            'title' => '予約システム',
            'description' => '教習・宿泊のオンライン予約',
            'link' => '#',
            'icon' => 'calendar',
            'order' => 6,
        ),
        array(
            'title' => 'マッチングシステム',
            'description' => '求人情報の投稿・検索',
            'link' => '#',
            'icon' => 'briefcase',
            'order' => 7,
        ),
    );

    foreach ($sample_items as $item) {
        $post_data = array(
            'post_title' => $item['title'],
            'post_content' => $item['description'],
            'post_status' => 'publish',
            'post_type' => 'hero_item',
        );

        $post_id = wp_insert_post($post_data);

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_hero_item_description', $item['description']);
            update_post_meta($post_id, '_hero_item_link', $item['link']);
            update_post_meta($post_id, '_hero_item_icon', $item['icon']);
            update_post_meta($post_id, '_hero_item_order', $item['order']);
        }
    }
}
add_action('after_switch_theme', 'sbs_create_sample_hero_items');

/**
 * Get hero items from database
 * 
 * @param int $limit Maximum number of items to retrieve
 * @return array Array of hero items with formatted data
 */
function sbs_get_hero_items($limit = 7)
{
    $args = array(
        'post_type' => 'hero_item',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'meta_key' => '_hero_item_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_hero_item_order',
                'compare' => 'EXISTS',
            ),
            array(
                'key' => '_hero_item_order',
                'compare' => 'NOT EXISTS',
            ),
        ),
    );

    $query = new WP_Query($args);
    $hero_items = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            $hero_items[] = array(
                'id' => $post_id,
                'title' => get_the_title(),
                'description' => get_post_meta($post_id, '_hero_item_description', true),
                'link' => get_post_meta($post_id, '_hero_item_link', true),
                'icon' => get_post_meta($post_id, '_hero_item_icon', true),
                'order' => get_post_meta($post_id, '_hero_item_order', true) ?: 999,
            );
        }
        wp_reset_postdata();
    }

    // Sort by order if no meta order exists
    if (!empty($hero_items)) {
        usort($hero_items, function ($a, $b) {
            return $a['order'] - $b['order'];
        });
    }

    return $hero_items;
}


/**
 * Save meta box data
 */
function sbs_save_banner_item_meta_box($post_id)
{
    if (!isset($_POST['sbs_banner_item_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['sbs_banner_item_meta_box_nonce'], 'sbs_banner_item_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save image source preference
    if (isset($_POST['banner_item_image_source'])) {
        $selected_source = sanitize_text_field($_POST['banner_item_image_source']);

        // Reset all options first
        update_post_meta($post_id, '_banner_item_use_local', '0');

        // Set the selected option
        if ($selected_source === 'featured') {
            update_post_meta($post_id, '_banner_item_use_local', '1');
        }
        // external is already set to 0 above
    }

    // Save external image URL
    if (isset($_POST['banner_item_image_url'])) {
        update_post_meta($post_id, '_banner_item_image_url', esc_url_raw($_POST['banner_item_image_url']));
    }

    // Save link URL
    if (isset($_POST['banner_item_link_url'])) {
        update_post_meta($post_id, '_banner_item_link_url', esc_url_raw($_POST['banner_item_link_url']));
    }

    // Save display order
    if (isset($_POST['banner_item_order'])) {
        update_post_meta($post_id, '_banner_item_order', intval($_POST['banner_item_order']));
    }
}
add_action('save_post', 'sbs_save_banner_item_meta_box');

/**
 * Add custom columns to Banner Items admin list
 */
function sbs_add_banner_item_admin_columns($columns)
{
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['thumbnail'] = __('Thumbnail', 'sbs-portal');
    $new_columns['title'] = $columns['title'];
    $new_columns['image_source'] = __('Image Source', 'sbs-portal');
    $new_columns['link_url'] = __('Link URL', 'sbs-portal');
    $new_columns['order'] = __('Order', 'sbs-portal');
    $new_columns['date'] = $columns['date'];

    return $new_columns;
}
add_filter('manage_banner_item_posts_columns', 'sbs_add_banner_item_admin_columns');

/**
 * Display custom column content
 */
function sbs_banner_item_admin_column_content($column, $post_id)
{
    switch ($column) {
        case 'thumbnail':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, 'thumbnail');
            } else {
                echo '<span style="color: #999;">No thumbnail</span>';
            }
            break;

        case 'image_source':
            $use_local = get_post_meta($post_id, '_banner_item_use_local', true);

            if ($use_local == '1') {
                echo '<span style="color: #0073aa;">Featured Image</span>';
            } else {
                echo '<span style="color: #d63638;">External URL</span>';
            }
            break;

        case 'link_url':
            $link_url = get_post_meta($post_id, '_banner_item_link_url', true);
            if ($link_url) {
                echo '<a href="' . esc_url($link_url) . '" target="_blank">' . esc_html($link_url) . '</a>';
            } else {
                echo '<span style="color: #999;">No link</span>';
            }
            break;

        case 'order':
            $order = get_post_meta($post_id, '_banner_item_order', true);
            echo $order ? $order : '<span style="color: #999;">-</span>';
            break;
    }
}
add_action('manage_banner_item_posts_custom_column', 'sbs_banner_item_admin_column_content', 10, 2);

/**
 * Make columns sortable
 */
function sbs_banner_item_sortable_columns($columns)
{
    $columns['order'] = 'order';
    return $columns;
}
add_filter('manage_edit-banner_item_sortable_columns', 'sbs_banner_item_sortable_columns');

/**
 * Handle custom orderby
 */
function sbs_banner_item_admin_orderby($query)
{
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('order' == $orderby) {
        $query->set('meta_key', '_banner_item_order');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'sbs_banner_item_admin_orderby');

/**
 * Add quick edit fields
 */
function sbs_banner_item_quick_edit_fields()
{
?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="inline-edit-group">
                <span class="title"><?php _e('Order', 'sbs-portal'); ?></span>
                <span class="input-text-wrap">
                    <input type="number" name="banner_item_order" class="text" min="1" />
                </span>
            </label>
        </div>
    </fieldset>
<?php
}
add_action('quick_edit_custom_box', 'sbs_banner_item_quick_edit_fields', 10, 2);

/**
 * Save quick edit data
 */
function sbs_banner_item_quick_edit_save($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['banner_item_order']) && $_POST['banner_item_order'] !== '') {
        update_post_meta($post_id, '_banner_item_order', sanitize_text_field($_POST['banner_item_order']));
    }
}
add_action('save_post', 'sbs_banner_item_quick_edit_save');

/**
 * Create sample banner items on theme activation
 */
function sbs_create_sample_banner_items()
{
    // Check if banner items already exist
    $existing_items = get_posts(array(
        'post_type' => 'banner_item',
        'post_status' => 'publish',
        'posts_per_page' => 1,
    ));

    if (!empty($existing_items)) {
        return; // Don't create duplicates
    }

    $sample_items = array(
        array(
            'title' => 'SBS ドライビングスクール 教習風景',
            'image_url' => get_template_directory_uri() . '/assets/images/gallery-1.jpg',
            'link_url' => '#',
            'order' => 1,
            'use_local' => '0',
        ),
        array(
            'title' => 'SBS 自動車整備 サービス',
            'image_url' => get_template_directory_uri() . '/assets/images/gallery-2.jpg',
            'link_url' => '#',
            'order' => 2,
            'use_local' => '0',
        ),
        array(
            'title' => 'SBS 施設案内',
            'image_url' => get_template_directory_uri() . '/assets/images/gallery-3.jpg',
            'link_url' => '#',
            'order' => 3,
            'use_local' => '0',
        ),
    );

    foreach ($sample_items as $item) {
        $post_data = array(
            'post_title' => $item['title'],
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'banner_item',
        );

        $post_id = wp_insert_post($post_data);

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_banner_item_image_url', $item['image_url']);
            update_post_meta($post_id, '_banner_item_link_url', $item['link_url']);
            update_post_meta($post_id, '_banner_item_order', $item['order']);
            update_post_meta($post_id, '_banner_item_use_local', $item['use_local']);
        }
    }
}
add_action('after_switch_theme', 'sbs_create_sample_banner_items');

/**
 * Add custom columns to Campaign admin list
 */
function sbs_add_campaign_admin_columns($columns)
{
    $new_columns = array();

    // Keep existing columns in order
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Add custom columns after title
        if ($key === 'title') {
            $new_columns['campaign_type'] = __('Type', 'sbs-portal');
            $new_columns['campaign_impressions'] = __('Impressions', 'sbs-portal');
            $new_columns['campaign_clicks'] = __('Clicks', 'sbs-portal');
            $new_columns['campaign_ctr'] = __('CTR', 'sbs-portal');
            $new_columns['campaign_status'] = __('Status', 'sbs-portal');
        }
    }

    return $new_columns;
}
add_filter('manage_campaign_posts_columns', 'sbs_add_campaign_admin_columns');

/**
 * Display custom column content for Campaign
 */
function sbs_campaign_admin_column_content($column, $post_id)
{
    switch ($column) {
        case 'campaign_type':
            $type = get_post_meta($post_id, '_campaign_type', true);
            echo esc_html($type ? ucfirst($type) : '—');
            break;

        case 'campaign_impressions':
            $impressions = (int) get_post_meta($post_id, '_campaign_impressions', true);
            echo '<strong>' . number_format($impressions) . '</strong>';
            break;

        case 'campaign_clicks':
            $clicks = (int) get_post_meta($post_id, '_campaign_clicks', true);
            echo '<strong>' . number_format($clicks) . '</strong>';
            break;

        case 'campaign_ctr':
            $impressions = (int) get_post_meta($post_id, '_campaign_impressions', true);
            $clicks = (int) get_post_meta($post_id, '_campaign_clicks', true);
            $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

            $color = 'black';
            if ($ctr >= 5) $color = 'green';
            elseif ($ctr >= 2) $color = 'orange';
            elseif ($ctr > 0) $color = 'red';

            echo '<span style="color: ' . $color . '; font-weight: bold;">' . $ctr . '%</span>';
            break;

        case 'campaign_status':
            $start_date = get_post_meta($post_id, '_campaign_start_date', true);
            $end_date = get_post_meta($post_id, '_campaign_end_date', true);
            $tracking_enabled = get_post_meta($post_id, '_campaign_tracking_enabled', true);
            $is_highlighted = get_post_meta($post_id, '_campaign_highlight', true) === '1';

            $today = wp_date('Y-m-d');
            $status_parts = array();

            // Check if campaign is active based on dates
            if ($start_date && $start_date > $today) {
                $status_parts[] = '<span style="color: orange;">Scheduled</span>';
            } elseif ($end_date && $end_date < $today) {
                $status_parts[] = '<span style="color: red;">Expired</span>';
            } else {
                $status_parts[] = '<span style="color: green;">Active</span>';
            }

            // Check other statuses
            if ($is_highlighted) {
                $status_parts[] = '<span style="color: blue;">★ Featured</span>';
            }

            if ($tracking_enabled !== '1') {
                $status_parts[] = '<span style="color: gray;">No Tracking</span>';
            }

            echo implode('<br>', $status_parts);
            break;
    }
}
add_action('manage_campaign_posts_custom_column', 'sbs_campaign_admin_column_content', 10, 2);

/**
 * Make Campaign columns sortable
 */
function sbs_campaign_sortable_columns($columns)
{
    $columns['campaign_type'] = 'campaign_type';
    $columns['campaign_impressions'] = 'campaign_impressions';
    $columns['campaign_clicks'] = 'campaign_clicks';
    return $columns;
}
add_filter('manage_edit-campaign_sortable_columns', 'sbs_campaign_sortable_columns');

/**
 * Handle custom sorting for Campaign
 */
function sbs_campaign_custom_orderby($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ($orderby === 'campaign_type') {
        $query->set('meta_key', '_campaign_type');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'campaign_impressions') {
        $query->set('meta_key', '_campaign_impressions');
        $query->set('orderby', 'meta_value_num');
    } elseif ($orderby === 'campaign_clicks') {
        $query->set('meta_key', '_campaign_clicks');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'sbs_campaign_custom_orderby');

/**
 * Get Campaign posts for banner carousel
 *
 * @param int $limit Maximum number of campaigns to retrieve
 * @return array{id:int,title:string,image_src:string,permalink:string}[]
 */
function sbs_get_campaign_items(int $limit = 10): array
{
    $today = wp_date('Y-m-d');

    $args = array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            'relation' => 'AND',
            // Only show campaigns that are not expired
            array(
                'relation' => 'OR',
                array(
                    'key' => '_campaign_end_date',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => '_campaign_end_date',
                    'value' => '',
                    'compare' => '='
                ),
                array(
                    'key' => '_campaign_end_date',
                    'value' => $today,
                    'compare' => '>='
                )
            ),
            // Only show campaigns that have started or have no start date
            array(
                'relation' => 'OR',
                array(
                    'key' => '_campaign_start_date',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => '_campaign_start_date',
                    'value' => '',
                    'compare' => '='
                ),
                array(
                    'key' => '_campaign_start_date',
                    'value' => $today,
                    'compare' => '<='
                )
            ),
            // Only show campaigns with tracking enabled (or no setting = default enabled)
            array(
                'relation' => 'OR',
                array(
                    'key' => '_campaign_tracking_enabled',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => '_campaign_tracking_enabled',
                    'value' => '1',
                    'compare' => '='
                )
            )
        )
    );

    $query = new WP_Query($args);
    $items = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            // Check max impressions/clicks limits
            $max_impressions = (int) get_post_meta($post_id, '_campaign_max_impressions', true);
            $max_clicks = (int) get_post_meta($post_id, '_campaign_max_clicks', true);
            $current_impressions = (int) get_post_meta($post_id, '_campaign_impressions', true);
            $current_clicks = (int) get_post_meta($post_id, '_campaign_clicks', true);

            // Skip if limits exceeded
            if (($max_impressions > 0 && $current_impressions >= $max_impressions) ||
                ($max_clicks > 0 && $current_clicks >= $max_clicks)
            ) {
                continue;
            }

            $image_src = has_post_thumbnail($post_id)
                ? get_the_post_thumbnail_url($post_id, 'full')
                : '';

            $slug = get_post_field('post_name', $post_id);

            // Check for external URL
            $external_url = get_post_meta($post_id, '_campaign_external_url', true);
            $detail_url = !empty($external_url)
                ? $external_url
                : add_query_arg('post_id', $post_id, home_url('/campaign-detail/'));

            $items[] = array(
                'id' => $post_id,
                'title' => get_the_title(),
                'image_src' => $image_src,
                'permalink' => get_permalink($post_id),
                'slug' => $slug,
                'detail_url' => $detail_url,
                'external_url' => $external_url,
                'campaign_type' => get_post_meta($post_id, '_campaign_type', true),
                'target_audience' => get_post_meta($post_id, '_campaign_target_audience', true),
            );
        }
        wp_reset_postdata();
    }

    return $items;
}

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
/*
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
*/

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
 * Helper function to get latest campaign posts
/**
 * Helper function to get latest campaign posts
 */
function sbs_get_latest_campaign_posts($count = 4)
{
    $mock_data = sbs_get_mock_data();
    $posts = isset($mock_data['campaign_posts']) ? $mock_data['campaign_posts'] : array();
    return array_slice($posts, 0, $count);
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
    // Add rewrite rule for blog list page
    add_rewrite_rule(
        '^blog-list/?$',
        'index.php?sbs_page=blog-list',
        'top'
    );

    // Add rewrite rule for blog detail page
    add_rewrite_rule(
        '^blog-detail/?$',
        'index.php?sbs_page=blog-detail',
        'top'
    );
    // Optional detail by slug
    add_rewrite_rule(
        '^blog-detail/([^/]+)/?$',
        'index.php?sbs_page=blog-detail&post_slug=$matches[1]',
        'top'
    );

    // Add rewrite rules for campaign detail page
    // 1) /campaign-detail/ -> list or generic
    add_rewrite_rule(
        '^campaign-detail/?$',
        'index.php?sbs_page=campaign-detail',
        'top'
    );
    // 2) /campaign-detail/{slug}/ -> detail by slug
    add_rewrite_rule(
        '^campaign-detail/([^/]+)/?$',
        'index.php?sbs_page=campaign-detail&post_slug=$matches[1]',
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
    $vars[] = 'post_slug';
    $vars[] = 'post_id';
    return $vars;
}
add_filter('query_vars', 'sbs_add_query_vars');

/**
 * Handle custom page templates
 */
function sbs_template_redirect()
{
    $sbs_page = get_query_var('sbs_page');
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
}
add_action('template_redirect', 'sbs_template_redirect');

/**
 * Load custom templates for pretty URLs driven by sbs_page
 */
function sbs_template_include(string $template): string
{
    $sbs_page = get_query_var('sbs_page');
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';

    // Detect campaign-detail via query var, pretty permalink, or direct /campaign-detail/ path
    if (
        $sbs_page === 'campaign-detail'
        || (isset($GLOBALS['wp']) && isset($GLOBALS['wp']->query_vars['pagename']) && $GLOBALS['wp']->query_vars['pagename'] === 'campaign-detail')
        || strpos($request_uri, '/campaign-detail') !== false
    ) {
        $custom_template = get_template_directory() . '/templates/campaign-detail.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }

    // Detect blog-detail custom route and map to our template
    if (
        $sbs_page === 'blog-detail'
        || (isset($GLOBALS['wp']) && isset($GLOBALS['wp']->query_vars['pagename']) && $GLOBALS['wp']->query_vars['pagename'] === 'blog-detail')
        || strpos($request_uri, '/blog-detail') !== false
    ) {
        $custom_template = get_template_directory() . '/templates/blog-detail.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }

    // You can extend here for other custom pages (e.g., blog-list, blog-detail) if needed
    return $template;
}
add_filter('template_include', 'sbs_template_include');

/**
 * Flush rewrite rules on theme activation
 */
function sbs_flush_rewrite_rules()
{
    // Add our custom rewrite rules
    add_rewrite_rule(
        '^blog-list/?$',
        'index.php?sbs_page=blog-list',
        'top'
    );

    add_rewrite_rule(
        '^blog-detail/?$',
        'index.php?sbs_page=blog-detail',
        'top'
    );
    add_rewrite_rule(
        '^blog-detail/([^/]+)/?$',
        'index.php?sbs_page=blog-detail&post_slug=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^campaign-detail/?$',
        'index.php?sbs_page=campaign-detail',
        'top'
    );
    add_rewrite_rule(
        '^campaign-detail/([^/]+)/?$',
        'index.php?sbs_page=campaign-detail&post_slug=$matches[1]',
        'top'
    );

    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'sbs_flush_rewrite_rules');

/**
 * Force flush rewrite rules (for development)
 */
function sbs_force_flush_rewrite_rules()
{
    if (isset($_GET['flush_rules']) && current_user_can('manage_options')) {
        flush_rewrite_rules();
        wp_die('Rewrite rules flushed successfully!');
    }
}
add_action('init', 'sbs_force_flush_rewrite_rules');

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

    // Add Campaign Analytics dashboard
    add_submenu_page(
        'edit.php?post_type=campaign',
        'Campaign Analytics',
        'Analytics',
        'edit_posts',
        'sbs-campaign-analytics',
        'sbs_campaign_analytics_page'
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
 * Campaign Analytics Dashboard Page
 */
function sbs_campaign_analytics_page()
{
    // Get all campaigns
    $campaigns = get_posts(array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    $total_impressions = 0;
    $total_clicks = 0;
    $active_campaigns = 0;
    $today = wp_date('Y-m-d');

    foreach ($campaigns as $campaign) {
        $impressions = (int) get_post_meta($campaign->ID, '_campaign_impressions', true);
        $clicks = (int) get_post_meta($campaign->ID, '_campaign_clicks', true);
        $end_date = get_post_meta($campaign->ID, '_campaign_end_date', true);
        $tracking_enabled = get_post_meta($campaign->ID, '_campaign_tracking_enabled', true) !== '0';

        $total_impressions += $impressions;
        $total_clicks += $clicks;

        if ($tracking_enabled && (!$end_date || $end_date >= $today)) {
            $active_campaigns++;
        }
    }

    $overall_ctr = $total_impressions > 0 ? round(($total_clicks / $total_impressions) * 100, 2) : 0;

?>
    <div class="wrap">
        <h1>Campaign Analytics Dashboard</h1>

        <div class="sbs-analytics-summary">
            <div class="analytics-cards">
                <div class="analytics-card">
                    <div class="card-number"><?php echo number_format($total_impressions); ?></div>
                    <div class="card-label">Total Impressions</div>
                </div>

                <div class="analytics-card">
                    <div class="card-number"><?php echo number_format($total_clicks); ?></div>
                    <div class="card-label">Total Clicks</div>
                </div>

                <div class="analytics-card">
                    <div class="card-number"><?php echo $overall_ctr; ?>%</div>
                    <div class="card-label">Overall CTR</div>
                </div>

                <div class="analytics-card">
                    <div class="card-number"><?php echo $active_campaigns; ?></div>
                    <div class="card-label">Active Campaigns</div>
                </div>
            </div>
        </div>

        <div class="sbs-campaigns-table">
            <h2>Campaign Performance</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Campaign</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Impressions</th>
                        <th>Clicks</th>
                        <th>CTR</th>
                        <th>Last Activity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campaigns as $campaign):
                        $impressions = (int) get_post_meta($campaign->ID, '_campaign_impressions', true);
                        $clicks = (int) get_post_meta($campaign->ID, '_campaign_clicks', true);
                        $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;
                        $type = get_post_meta($campaign->ID, '_campaign_type', true) ?: 'general';
                        $start_date = get_post_meta($campaign->ID, '_campaign_start_date', true);
                        $end_date = get_post_meta($campaign->ID, '_campaign_end_date', true);
                        $tracking_enabled = get_post_meta($campaign->ID, '_campaign_tracking_enabled', true) !== '0';
                        $last_activity = (int) get_post_meta($campaign->ID, '_campaign_last_activity', true);

                        // Determine status
                        $status = 'Active';
                        $status_class = 'status-active';

                        if (!$tracking_enabled) {
                            $status = 'Tracking Disabled';
                            $status_class = 'status-disabled';
                        } elseif ($start_date && $start_date > $today) {
                            $status = 'Scheduled';
                            $status_class = 'status-scheduled';
                        } elseif ($end_date && $end_date < $today) {
                            $status = 'Expired';
                            $status_class = 'status-expired';
                        }

                        $last_activity_text = $last_activity ? human_time_diff($last_activity) . ' ago' : 'Never';
                    ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($campaign->post_title); ?></strong>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a href="<?php echo get_edit_post_link($campaign->ID); ?>">Edit</a>
                                    </span>
                                </div>
                            </td>
                            <td><?php echo esc_html(ucfirst($type)); ?></td>
                            <td>
                                <span class="campaign-status <?php echo $status_class; ?>">
                                    <?php echo esc_html($status); ?>
                                </span>
                            </td>
                            <td><?php echo number_format($impressions); ?></td>
                            <td><?php echo number_format($clicks); ?></td>
                            <td>
                                <span class="ctr-value <?php echo $ctr >= 2 ? 'ctr-good' : ($ctr >= 1 ? 'ctr-fair' : 'ctr-poor'); ?>">
                                    <?php echo $ctr; ?>%
                                </span>
                            </td>
                            <td><?php echo esc_html($last_activity_text); ?></td>
                            <td>
                                <button type="button" class="button button-small view-analytics"
                                    data-campaign-id="<?php echo $campaign->ID; ?>">
                                    View Analytics
                                </button>
                                <button type="button" class="button button-small reset-metrics"
                                    data-campaign-id="<?php echo $campaign->ID; ?>"
                                    data-campaign-title="<?php echo esc_attr($campaign->post_title); ?>">
                                    Reset
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Analytics Modal -->
        <div id="analytics-modal" class="analytics-modal" style="display: none;">
            <div class="analytics-modal-content">
                <div class="analytics-modal-header">
                    <h3 id="analytics-modal-title">Campaign Analytics</h3>
                    <span class="analytics-modal-close">&times;</span>
                </div>
                <div class="analytics-modal-body">
                    <div id="analytics-loading">Loading analytics data...</div>
                    <div id="analytics-content" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .sbs-analytics-summary {
            margin: 20px 0;
        }

        .analytics-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .analytics-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card-number {
            font-size: 32px;
            font-weight: bold;
            color: #2271b1;
            margin-bottom: 8px;
        }

        .card-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .campaign-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-disabled {
            background: #f8d7da;
            color: #721c24;
        }

        .status-scheduled {
            background: #fff3cd;
            color: #856404;
        }

        .status-expired {
            background: #f1f3f4;
            color: #5f6368;
        }

        .ctr-value {
            font-weight: bold;
        }

        .ctr-good {
            color: #28a745;
        }

        .ctr-fair {
            color: #ffc107;
        }

        .ctr-poor {
            color: #dc3545;
        }

        .analytics-modal {
            position: fixed;
            z-index: 100000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .analytics-modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 1000px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .analytics-modal-header {
            padding: 20px;
            background: #f7f7f7;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .analytics-modal-close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .analytics-modal-body {
            padding: 20px;
        }

        .sbs-campaigns-table {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        jQuery(document).ready(function($) {
            // View Analytics
            $('.view-analytics').on('click', function() {
                var campaignId = $(this).data('campaign-id');
                var campaignTitle = $(this).closest('tr').find('strong').text();

                $('#analytics-modal-title').text('Analytics: ' + campaignTitle);
                $('#analytics-modal').show();
                $('#analytics-loading').show();
                $('#analytics-content').hide();

                // Fetch analytics data
                $.get('<?php echo rest_url('sbs/v1/campaign/'); ?>' + campaignId + '/analytics?days=30')
                    .done(function(data) {
                        var html = '<div class="analytics-summary">';
                        html += '<div class="analytics-grid">';
                        html += '<div class="metric"><div class="metric-number">' + data.period_stats.impressions.toLocaleString() + '</div><div class="metric-label">Impressions (30 days)</div></div>';
                        html += '<div class="metric"><div class="metric-number">' + data.period_stats.clicks.toLocaleString() + '</div><div class="metric-label">Clicks (30 days)</div></div>';
                        html += '<div class="metric"><div class="metric-number">' + data.period_stats.ctr + '%</div><div class="metric-label">CTR (30 days)</div></div>';
                        html += '<div class="metric"><div class="metric-number">' + data.period_stats.avg_impressions_per_day + '</div><div class="metric-label">Avg Impressions/Day</div></div>';
                        html += '</div></div>';

                        if (data.daily_stats && Object.keys(data.daily_stats).length > 0) {
                            html += '<h4>Daily Performance</h4>';
                            html += '<table class="wp-list-table widefat">';
                            html += '<thead><tr><th>Date</th><th>Impressions</th><th>Clicks</th><th>CTR</th></tr></thead><tbody>';

                            Object.keys(data.daily_stats).slice(-10).forEach(function(date) {
                                var stat = data.daily_stats[date];
                                html += '<tr>';
                                html += '<td>' + stat.date + '</td>';
                                html += '<td>' + stat.impressions + '</td>';
                                html += '<td>' + stat.clicks + '</td>';
                                html += '<td>' + stat.ctr + '%</td>';
                                html += '</tr>';
                            });

                            html += '</tbody></table>';
                        }

                        $('#analytics-content').html(html);
                        $('#analytics-loading').hide();
                        $('#analytics-content').show();
                    })
                    .fail(function() {
                        $('#analytics-content').html('<p>Failed to load analytics data.</p>');
                        $('#analytics-loading').hide();
                        $('#analytics-content').show();
                    });
            });

            // Reset Metrics
            $('.reset-metrics').on('click', function() {
                var campaignId = $(this).data('campaign-id');
                var campaignTitle = $(this).data('campaign-title');

                if (confirm('Are you sure you want to reset all metrics for "' + campaignTitle + '"? This action cannot be undone.')) {
                    $.post(ajaxurl, {
                        action: 'sbs_reset_campaign_metrics',
                        campaign_id: campaignId,
                        nonce: '<?php echo wp_create_nonce('sbs_reset_metrics'); ?>'
                    }, function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Failed to reset metrics. Please try again.');
                        }
                    });
                }
            });

            // Close Modal
            $('.analytics-modal-close, .analytics-modal').on('click', function(e) {
                if (e.target === this) {
                    $('#analytics-modal').hide();
                }
            });
        });
    </script>

    <style>
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .metric {
            text-align: center;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .metric-number {
            font-size: 24px;
            font-weight: bold;
            color: #2271b1;
            margin-bottom: 5px;
        }

        .metric-label {
            font-size: 12px;
            color: #666;
        }
    </style>
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
        'align-justify' => 'icon-align-justify.svg',
        'icon-x' => 'icon-x.svg',
    );

    if (isset($icon_map[$icon_name])) {
        $icon_path = get_template_directory() . '/assets/images/icons/' . $icon_map[$icon_name];
        if (file_exists($icon_path)) {
            return file_get_contents($icon_path);
        }
    }

    return '<div class="icon-placeholder"></div>';
}

/**
 * Register Blog Post custom post type
 */
function sbs_register_blog_post_post_type()
{
    $labels = array(
        'name'                  => _x('Blog', 'Post Type General Name', 'sbs-portal'),
        'singular_name'         => _x('Blog Post', 'Post Type Singular Name', 'sbs-portal'),
        'menu_name'             => __('Blog', 'sbs-portal'),
        'name_admin_bar'        => __('Blog Post', 'sbs-portal'),
        'archives'              => __('Blog Archives', 'sbs-portal'),
        'attributes'            => __('Blog Attributes', 'sbs-portal'),
        'parent_item_colon'     => __('Parent Blog Post:', 'sbs-portal'),
        'all_items'             => __('All Blog Posts', 'sbs-portal'),
        'add_new_item'          => __('Add New Blog Post', 'sbs-portal'),
        'add_new'               => __('Add New', 'sbs-portal'),
        'new_item'              => __('New Blog Post', 'sbs-portal'),
        'edit_item'             => __('Edit Blog Post', 'sbs-portal'),
        'update_item'           => __('Update Blog Post', 'sbs-portal'),
        'view_item'             => __('View Blog Post', 'sbs-portal'),
        'view_items'            => __('View Blog Posts', 'sbs-portal'),
        'search_items'          => __('Search Blog Posts', 'sbs-portal'),
        'not_found'             => __('Not found', 'sbs-portal'),
        'not_found_in_trash'    => __('Not found in Trash', 'sbs-portal'),
        'featured_image'        => __('Featured Image', 'sbs-portal'),
        'set_featured_image'    => __('Set featured image', 'sbs-portal'),
        'remove_featured_image' => __('Remove featured image', 'sbs-portal'),
        'use_featured_image'    => __('Use as featured image', 'sbs-portal'),
        'insert_into_item'      => __('Insert into blog post', 'sbs-portal'),
        'uploaded_to_this_item' => __('Uploaded to this blog post', 'sbs-portal'),
        'items_list'            => __('Blog posts list', 'sbs-portal'),
        'items_list_navigation' => __('Blog posts list navigation', 'sbs-portal'),
        'filter_items_list'     => __('Filter blog posts list', 'sbs-portal'),
    );

    $args = array(
        'label'                 => __('Blog', 'sbs-portal'),
        'description'           => __('Blog posts for SBS Portal', 'sbs-portal'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-admin-post',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'blog'),
    );

    register_post_type('blog', $args);
}
add_action('init', 'sbs_register_blog_post_post_type');

/**
 * Add custom meta boxes for Blog Post
 */
function sbs_add_blog_post_meta_boxes()
{
    add_meta_box(
        'sbs_blog_post_details',
        __('Blog Post Details', 'sbs-portal'),
        'sbs_blog_post_meta_box_callback',
        'blog',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sbs_add_blog_post_meta_boxes');

/**
 * Meta box callback function for Blog Post
 */
function sbs_blog_post_meta_box_callback($post)
{
    wp_nonce_field('sbs_blog_post_meta_box', 'sbs_blog_post_meta_box_nonce');

    $category = get_post_meta($post->ID, '_blog_post_category', true);
    $status = get_post_meta($post->ID, '_blog_post_status', true);
    $order = get_post_meta($post->ID, '_blog_post_order', true);

?>
    <div class="blog-post-setup">
        <div class="blog-post-fields">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="blog_post_category"><?php _e('Category', 'sbs-portal'); ?></label>
                    </th>
                    <td>
                        <select id="blog_post_category" name="blog_post_category">
                            <option value=""><?php _e('Select Category', 'sbs-portal'); ?></option>
                            <option value="BLOG" <?php selected($category, 'BLOG'); ?>><?php _e('BLOG', 'sbs-portal'); ?></option>
                            <option value="NEWS" <?php selected($category, 'NEWS'); ?>><?php _e('NEWS', 'sbs-portal'); ?></option>
                            <option value="EVENT" <?php selected($category, 'EVENT'); ?>><?php _e('EVENT', 'sbs-portal'); ?></option>
                            <option value="CAMPAIGN" <?php selected($category, 'CAMPAIGN'); ?>><?php _e('CAMPAIGN', 'sbs-portal'); ?></option>
                        </select>
                        <p class="description"><?php _e('Select the category for this blog post', 'sbs-portal'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="blog_post_status"><?php _e('Status', 'sbs-portal'); ?></label>
                    </th>
                    <td>
                        <select id="blog_post_status" name="blog_post_status">
                            <option value="draft" <?php selected($status, 'draft'); ?>><?php _e('Draft', 'sbs-portal'); ?></option>
                            <option value="published" <?php selected($status, 'published'); ?>><?php _e('Published', 'sbs-portal'); ?></option>
                            <option value="private" <?php selected($status, 'private'); ?>><?php _e('Private', 'sbs-portal'); ?></option>
                        </select>
                        <p class="description"><?php _e('Select the publication status', 'sbs-portal'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="blog_post_order"><?php _e('Display Order', 'sbs-portal'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="blog_post_order" name="blog_post_order" value="<?php echo esc_attr($order); ?>" class="small-text" min="1" />
                        <p class="description"><?php _e('Order in which this post appears (1 = first)', 'sbs-portal'); ?></p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="blog-post-instructions">
            <h4><?php _e('How to use:', 'sbs-portal'); ?></h4>
            <ul>
                <li><strong><?php _e('Title:', 'sbs-portal'); ?></strong> <?php _e('Enter the blog post title above', 'sbs-portal'); ?></li>
                <li><strong><?php _e('Content:', 'sbs-portal'); ?></strong> <?php _e('Write the full blog post content in the main editor', 'sbs-portal'); ?></li>
                <li><strong><?php _e('Excerpt:', 'sbs-portal'); ?></strong> <?php _e('Add a short summary in the Excerpt box below the editor', 'sbs-portal'); ?></li>
                <li><strong><?php _e('Featured Image:', 'sbs-portal'); ?></strong> <?php _e('Set the featured image using the box on the right sidebar', 'sbs-portal'); ?></li>
            </ul>
        </div>
    </div>

    <style>
        .blog-post-setup {
            margin: 20px 0;
        }

        .blog-post-fields {
            background: #f9f9f9;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .blog-post-instructions {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #0073aa;
        }

        .blog-post-instructions h4 {
            margin-top: 0;
            color: #0073aa;
        }

        .blog-post-instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .blog-post-instructions li {
            margin: 8px 0;
            line-height: 1.5;
        }

        .form-table th {
            width: 150px;
        }
    </style>
<?php
}

/**
 * Save Blog Post meta box data
 */
function sbs_save_blog_post_meta_box($post_id)
{
    // Check if nonce is valid
    if (!isset($_POST['sbs_blog_post_meta_box_nonce']) || !wp_verify_nonce($_POST['sbs_blog_post_meta_box_nonce'], 'sbs_blog_post_meta_box')) {
        return;
    }



    // Check if user has permissions to save data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if not an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save category
    if (isset($_POST['blog_post_category'])) {
        update_post_meta($post_id, '_blog_post_category', sanitize_text_field($_POST['blog_post_category']));
    }

    // Save status
    if (isset($_POST['blog_post_status'])) {
        update_post_meta($post_id, '_blog_post_status', sanitize_text_field($_POST['blog_post_status']));
    }

    // Save order
    if (isset($_POST['blog_post_order'])) {
        update_post_meta($post_id, '_blog_post_order', intval($_POST['blog_post_order']));
    }
}
add_action('save_post', 'sbs_save_blog_post_meta_box');

/**
 * Add custom columns to Blog Post admin list
 */
function sbs_add_blog_post_admin_columns($columns)
{
    $new_columns = array();

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        if ($key === 'title') {
            $new_columns['category'] = __('Category', 'sbs-portal');
            $new_columns['status'] = __('Status', 'sbs-portal');
            $new_columns['order'] = __('Order', 'sbs-portal');
        }
    }

    return $new_columns;
}
add_filter('manage_blog_posts_columns', 'sbs_add_blog_post_admin_columns');

/**
 * Display custom column content for Blog Post
 */
function sbs_blog_post_admin_column_content($column, $post_id)
{
    switch ($column) {
        case 'category':
            $category = get_post_meta($post_id, '_blog_post_category', true);
            echo esc_html($category) ? esc_html($category) : '—';
            break;

        case 'status':
            $status = get_post_meta($post_id, '_blog_post_status', true);
            $status_labels = array(
                'draft' => __('Draft', 'sbs-portal'),
                'published' => __('Published', 'sbs-portal'),
                'private' => __('Private', 'sbs-portal')
            );
            echo isset($status_labels[$status]) ? esc_html($status_labels[$status]) : '—';
            break;

        case 'order':
            $order = get_post_meta($post_id, '_blog_post_order', true);
            echo esc_html($order) ? esc_html($order) : '—';
            break;
    }
}
add_action('manage_blog_posts_custom_column', 'sbs_blog_post_admin_column_content', 10, 2);

/**
 * Make Blog Post columns sortable
 */
function sbs_blog_post_sortable_columns($columns)
{
    $columns['category'] = 'category';
    $columns['status'] = 'status';
    $columns['order'] = 'order';
    return $columns;
}
add_filter('manage_edit-blog_sortable_columns', 'sbs_blog_post_sortable_columns');

/**
 * Handle custom sorting for Blog Post
 */
function sbs_blog_post_custom_orderby($query)
{
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ($orderby === 'category') {
        $query->set('meta_key', '_blog_post_category');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'status') {
        $query->set('meta_key', '_blog_post_status');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'order') {
        $query->set('meta_key', '_blog_post_order');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'sbs_blog_post_custom_orderby');

/**
 * Get Blog Posts for display
 */
function sbs_get_blog_posts($limit = 10, $category = '')
{
    $meta_query = array(
        'relation' => 'AND',
        // Status filter: include published OR missing status meta
        array(
            'relation' => 'OR',
            array(
                'key' => '_blog_post_status',
                'value' => 'published',
                'compare' => '='
            ),
            array(
                'key' => '_blog_post_status',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => '_blog_post_status',
                'value' => '',
                'compare' => '='
            )
        )
    );

    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_query' => $meta_query,
    );

    // If category provided, prefer taxonomy query (blog_category). Accept either single or multiple values.
    if (!empty($category)) {
        $delimited = preg_split('/[|,;\s]+/', $category);
        $categories = array_filter(array_map('trim', $delimited));

        if (!empty($categories)) {
            // Use tax_query to match term names or slugs
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'blog_category',
                    'field' => 'name',
                    'terms' => $categories,
                    'operator' => 'IN'
                )
            );
        } else {
            // Fallback to meta_query if no taxonomy terms
            $args['meta_query'][] = array(
                'key' => '_blog_post_category',
                'value' => $category,
                'compare' => '='
            );
        }
    }

    // Order by custom order if exists, otherwise by date desc
    $args['orderby'] = array('meta_value_num' => 'ASC', 'date' => 'DESC');
    $args['meta_key'] = '_blog_post_order';

    $query = new WP_Query($args);
    $posts = array();

    // Fallback: if nothing matched (e.g., only one post without meta), query again without status meta
    if (!$query->have_posts()) {
        unset($args['meta_query']);
        $query = new WP_Query($args);
    }

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            // Use sbs_format_blog_post to ensure taxonomy preference and consistent shape
            $formatted = sbs_format_blog_post($post_id, false);
            // Keep content for this helper output
            $posts[] = array(
                'id' => $formatted['id'],
                'title' => $formatted['title'],
                'excerpt' => $formatted['excerpt'],
                'content' => $formatted['content'],
                'featured_image' => $formatted['featured_image'],
                'date' => $formatted['date'],
                'category' => $formatted['category'],
                'status' => $formatted['status'],
                'permalink' => $formatted['permalink']
            );
        }
        wp_reset_postdata();
    }

    return $posts;
}

/**
 * Create sample Blog Posts when theme is activated
 */
function sbs_create_sample_blog_posts()
{
    // Check if sample posts already exist
    $existing_posts = get_posts(array(
        'post_type' => 'blog',
        'posts_per_page' => 1,
        'post_status' => 'any'
    ));

    if (!empty($existing_posts)) {
        return; // Sample posts already exist
    }

    $sample_posts = array(
        array(
            'title' => '敬愛学園女子バレー部敬愛学園女子バレー部',
            'content' => '皆さんこんにちは！久しぶりの投稿となってしましました。この度、ＳＢＳ自動車学校は敬愛学園女子バレーボール部のスポンサーに就任いたしました！',
            'excerpt' => '皆さんこんにちは！久しぶりの投稿となってしましました。この度、ＳＢＳ自動車学校は敬愛学園女子バレーボール部のスポンサーに就任いたしました！',
            'category' => 'BLOG',
            'status' => 'published',
            'order' => 1
        ),
        array(
            'title' => '富士山ツーリング',
            'content' => '皆様、こんにちは。ヨッシーです。富士山ツーリングの様子をご紹介します。',
            'excerpt' => '皆様、こんにちは。ヨッシーです。皆様、こんにちは。ヨッシーです。',
            'category' => 'NEWS',
            'status' => 'published',
            'order' => 2
        ),
        array(
            'title' => '奥多摩ツーリング',
            'content' => '皆さんこんにちは！ヨッシーです！更新が全然できていませんでしたが、ツーリングには行っていました！',
            'excerpt' => '皆さんこんにちは！ヨッシーです！更新が全然できていませんでしたが、ツーリングには行っていました！',
            'category' => 'BLOG',
            'status' => 'published',
            'order' => 3
        ),
        array(
            'title' => '春の教習キャンペーン開始',
            'content' => '春の新生活シーズンに向けて、SBSドライビングスクールでは特別キャンペーンを実施中です。',
            'excerpt' => 'この春、新しい免許取得に向けたお得なキャンペーンを開始いたします。早期お申し込みで特別割引をご利用いただけます。',
            'category' => 'NEWS',
            'status' => 'published',
            'order' => 4
        ),
        array(
            'title' => '新しい教習車両を導入しました',
            'content' => '生徒の皆様により良い教習環境をご提供するため、最新の安全装備を搭載した教習車両を新たに導入いたしました。',
            'excerpt' => '最新の安全装備を搭載した新しい教習車両を導入いたしました。より安全で快適な教習環境をご提供できるよう設備の充実を図っております。',
            'category' => 'BLOG',
            'status' => 'published',
            'order' => 5
        )
    );

    foreach ($sample_posts as $post_data) {
        $post_id = wp_insert_post(array(
            'post_title' => $post_data['title'],
            'post_content' => $post_data['content'],
            'post_excerpt' => $post_data['excerpt'],
            'post_status' => $post_data['status'],
            'post_type' => 'blog'
        ));

        if ($post_id) {
            update_post_meta($post_id, '_blog_post_category', $post_data['category']);
            update_post_meta($post_id, '_blog_post_status', $post_data['status']);
            update_post_meta($post_id, '_blog_post_order', $post_data['order']);
        }
    }
}
add_action('after_switch_theme', 'sbs_create_sample_blog_posts');

/**
 * Save FAQ Group meta box data
 */
function sbs_save_faq_group_meta_box($post_id)
{
    // Check if nonce is valid
    if (!isset($_POST['sbs_faq_group_meta_box_nonce']) || !wp_verify_nonce($_POST['sbs_faq_group_meta_box_nonce'], 'sbs_faq_group_meta_box')) {
        return;
    }

    // Check if user has permissions to save data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if not an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save color
    if (isset($_POST['faq_group_color'])) {
        update_post_meta($post_id, '_faq_group_color', sanitize_hex_color($_POST['faq_group_color']));
    }

    // Save expanded state
    if (isset($_POST['faq_group_expanded'])) {
        update_post_meta($post_id, '_faq_group_expanded', sanitize_text_field($_POST['faq_group_expanded']));
    }

    // Save order
    if (isset($_POST['faq_group_order'])) {
        update_post_meta($post_id, '_faq_group_order', intval($_POST['faq_group_order']));
    }

    // Save questions
    if (isset($_POST['faq_questions']) && is_array($_POST['faq_questions'])) {
        $questions = array();
        foreach ($_POST['faq_questions'] as $question_data) {
            if (!empty($question_data['question'])) {
                $questions[] = array(
                    'id' => uniqid(),
                    'question' => sanitize_textarea_field($question_data['question']),
                    'answer' => sanitize_textarea_field($question_data['answer']),
                    'detail' => sanitize_textarea_field($question_data['detail']),
                    'expanded' => sanitize_text_field($question_data['expanded'])
                );
            }
        }
        update_post_meta($post_id, '_faq_group_questions', $questions);
    }
}
add_action('save_post', 'sbs_save_faq_group_meta_box');

/**
 * Add custom columns to FAQ Group admin list
 */
function sbs_add_faq_group_admin_columns($columns)
{
    $new_columns = array();

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        if ($key === 'title') {
            $new_columns['questions_count'] = __('Questions', 'sbs-portal');
            $new_columns['color'] = __('Color', 'sbs-portal');
            $new_columns['order'] = __('Order', 'sbs-portal');
        }
    }

    return $new_columns;
}
add_filter('manage_faq_group_posts_columns', 'sbs_add_faq_group_admin_columns');

/**
 * Display custom column content for FAQ Group
 */
function sbs_faq_group_admin_column_content($column, $post_id)
{
    switch ($column) {
        case 'questions_count':
            $questions = get_post_meta($post_id, '_faq_group_questions', true);
            $count = is_array($questions) ? count($questions) : 0;
            echo esc_html($count);
            break;

        case 'color':
            $color = get_post_meta($post_id, '_faq_group_color', true);
            if ($color) {
                echo '<div style="width: 20px; height: 20px; background-color: ' . esc_attr($color) . '; border-radius: 3px; border: 1px solid #ddd;"></div>';
            }
            break;

        case 'order':
            $order = get_post_meta($post_id, '_faq_group_order', true);
            echo esc_html($order) ? esc_html($order) : '—';
            break;
    }
}
add_action('manage_faq_group_posts_custom_column', 'sbs_faq_group_admin_column_content', 10, 2);

/**
 * Make FAQ Group columns sortable
 */
function sbs_faq_group_sortable_columns($columns)
{
    $columns['questions_count'] = 'questions_count';
    $columns['order'] = 'order';
    return $columns;
}
add_filter('manage_edit-faq_group_sortable_columns', 'sbs_faq_group_sortable_columns');

/**
 * Handle custom sorting for FAQ Group
 */
function sbs_faq_group_custom_orderby($query)
{
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ($orderby === 'questions_count') {
        $query->set('meta_key', '_faq_group_questions');
        $query->set('orderby', 'meta_value');
    } elseif ($orderby === 'order') {
        $query->set('meta_key', '_faq_group_order');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'sbs_faq_group_custom_orderby');

/**
 * Get FAQ Groups for display
 */
function sbs_get_faq_groups()
{
    $args = array(
        'post_type' => 'faq_group',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => '_faq_group_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    );

    $query = new WP_Query($args);
    $groups = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            $groups[] = array(
                'id' => $post_id,
                'title' => get_the_title(),
                'color' => get_post_meta($post_id, '_faq_group_color', true) ?: '#DD1F01',
                'expanded' => get_post_meta($post_id, '_faq_group_expanded', true) === '1',
                'questions' => get_post_meta($post_id, '_faq_group_questions', true) ?: array()
            );
        }
        wp_reset_postdata();
    }

    return $groups;
}

/**
 * Create sample FAQ Groups when theme is activated
 */
function sbs_create_sample_faq_groups()
{
    // Check if sample groups already exist
    $existing_groups = get_posts(array(
        'post_type' => 'faq_group',
        'posts_per_page' => 1,
        'post_status' => 'any'
    ));

    if (!empty($existing_groups)) {
        return; // Sample groups already exist
    }

    $sample_groups = array(
        array(
            'title' => 'これから免許を取る方',
            'color' => '#DD1F01',
            'expanded' => true,
            'order' => 1,
            'questions' => array(
                array(
                    'question' => '入学から卒業までの流れをざっと教えて欲しいのですが…',
                    'answer' => '教習料金のページに各車種の「教習の流れ」の図がございます。',
                    'detail' => '教習料金のページより、各車種の卒業までのフロー図を掲載致しておりますのでご覧頂けます。また、実際に教習所にお越し頂き、説明をさせて頂く事も出来ますので、お気軽にご来所下さい。',
                    'expanded' => false
                ),
                array(
                    'question' => '入所前に一度どんなところか見ておきたいのですが、見学は出来ますか？',
                    'answer' => 'はい、見学は可能です。事前にご連絡いただければ、教習所の見学を承っております。',
                    'detail' => '見学をご希望の方は、お電話またはメールにて事前にご連絡ください。見学の際は、教習所の設備や教習の流れについて詳しくご説明いたします。',
                    'expanded' => false
                )
            )
        )
    );

    foreach ($sample_groups as $group_data) {
        $post_id = wp_insert_post(array(
            'post_title' => $group_data['title'],
            'post_status' => 'publish',
            'post_type' => 'faq_group'
        ));

        if ($post_id) {
            update_post_meta($post_id, '_faq_group_color', $group_data['color']);
            update_post_meta($post_id, '_faq_group_expanded', $group_data['expanded'] ? '1' : '0');
            update_post_meta($post_id, '_faq_group_order', $group_data['order']);
            update_post_meta($post_id, '_faq_group_questions', $group_data['questions']);
        }
    }
}
add_action('after_switch_theme', 'sbs_create_sample_faq_groups');

/**
 * AJAX handler for flushing rewrite rules
 */
function sbs_ajax_flush_rewrite_rules()
{
    // Check nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'flush_rewrite_rules')) {
        wp_die('Security check failed');
    }

    // Check if user is admin
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions');
    }

    // Flush rewrite rules
    flush_rewrite_rules();

    wp_die('Rewrite rules flushed successfully');
}
add_action('wp_ajax_flush_rewrite_rules', 'sbs_ajax_flush_rewrite_rules');
add_action('wp_ajax_nopriv_flush_rewrite_rules', 'sbs_ajax_flush_rewrite_rules');

/**
 * Force flush rewrite rules on init (for development)
 */
function sbs_force_flush_rewrite_rules_dev()
{
    // Only in development mode
    if (defined('WP_DEBUG') && WP_DEBUG) {
        // Add campaign-detail rewrite rule
        add_rewrite_rule(
            '^campaign-detail/?$',
            'index.php?sbs_page=campaign-detail',
            'top'
        );
        add_rewrite_rule(
            '^campaign-detail/([^/]+)/?$',
            'index.php?sbs_page=campaign-detail&post_slug=$matches[1]',
            'top'
        );

        // Blog-detail rules as well for dev convenience
        add_rewrite_rule(
            '^blog-detail/?$',
            'index.php?sbs_page=blog-detail',
            'top'
        );
        add_rewrite_rule(
            '^blog-detail/([^/]+)/?$',
            'index.php?sbs_page=blog-detail&post_slug=$matches[1]',
            'top'
        );
    }
}
add_action('init', 'sbs_force_flush_rewrite_rules_dev');

/**
 * ============================================================================
 * INTERNATIONALIZATION (i18n) & MULTILINGUAL SUPPORT
 * ============================================================================
 */

/**
 * Get available languages
 */
function sbs_get_available_languages()
{
    return array(
        'ja' => array(
            'name' => '日本語',
            'native_name' => '日本語',
            'flag' => '🇯🇵',
            'locale' => 'ja',
            'wp_locale' => 'ja'
        ),
        'en' => array(
            'name' => 'English',
            'native_name' => 'English',
            'flag' => '🇺🇸',
            'locale' => 'en_US',
            'wp_locale' => 'en_US'
        ),
        'id' => array(
            'name' => 'Indonesia',
            'native_name' => 'Bahasa Indonesia',
            'flag' => '🇮🇩',
            'locale' => 'id_ID',
            'wp_locale' => 'id_ID'
        )
    );
}

/**
 * Get current language
 */
function sbs_get_current_language()
{
    // Default to Japanese
    $current_lang = 'ja';

    // Check cookie first (most persistent)
    if (isset($_COOKIE['sbs_language'])) {
        $current_lang = sanitize_text_field($_COOKIE['sbs_language']);
    }

    // Then check session
    if (session_id() && isset($_SESSION['sbs_language'])) {
        $current_lang = $_SESSION['sbs_language'];
    }

    // Validate language exists
    $available_languages = sbs_get_available_languages();
    if (!array_key_exists($current_lang, $available_languages)) {
        $current_lang = 'ja'; // fallback to Japanese

        // Update cookie and session with default
        setcookie('sbs_language', $current_lang, time() + (30 * 24 * 60 * 60), '/');
        if (session_id()) {
            $_SESSION['sbs_language'] = $current_lang;
        }
    }

    return $current_lang;
}

/**
 * Set current language
 */
function sbs_set_current_language($language_code)
{
    $available_languages = sbs_get_available_languages();

    if (array_key_exists($language_code, $available_languages)) {
        // Start session if not started
        if (!session_id()) {
            session_start();
        }

        $_SESSION['sbs_language'] = $language_code;

        // Also set cookie for persistence (30 days)
        setcookie('sbs_language', $language_code, time() + (30 * 24 * 60 * 60), '/');

        // Change WordPress locale
        add_filter('locale', function () use ($language_code) {
            $languages = sbs_get_available_languages();
            return $languages[$language_code]['wp_locale'];
        });

        return true;
    }

    return false;
}

/**
 * Handle language switching via AJAX
 */
function sbs_ajax_switch_language()
{
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sbs_nonce')) {
        wp_die(json_encode(array('success' => false, 'message' => 'Security check failed')));
    }

    $language_code = sanitize_text_field($_POST['language']);

    if (sbs_set_current_language($language_code)) {
        // Force reload text domain for immediate effect
        sbs_load_textdomain();

        wp_die(json_encode(array(
            'success' => true,
            'message' => 'Language switched successfully',
            'language' => $language_code,
            'reload' => true
        )));
    } else {
        wp_die(json_encode(array(
            'success' => false,
            'message' => 'Invalid language code'
        )));
    }
}
add_action('wp_ajax_switch_language', 'sbs_ajax_switch_language');
add_action('wp_ajax_nopriv_switch_language', 'sbs_ajax_switch_language');

/**
 * Initialize session for language storage
 */
function sbs_init_session()
{
    if (!session_id()) {
        session_start();
    }

    // Initialize default language if not set
    if (!isset($_COOKIE['sbs_language']) && !isset($_SESSION['sbs_language'])) {
        $default_lang = 'ja';
        setcookie('sbs_language', $default_lang, time() + (30 * 24 * 60 * 60), '/');
        $_SESSION['sbs_language'] = $default_lang;
    }
}
add_action('init', 'sbs_init_session');

/**
 * Apply current language locale
 */
function sbs_apply_current_language_locale($locale)
{
    $current_lang = sbs_get_current_language();
    $languages = sbs_get_available_languages();

    if (isset($languages[$current_lang])) {
        return $languages[$current_lang]['wp_locale'];
    }

    return $locale;
}
add_filter('locale', 'sbs_apply_current_language_locale');

/**
 * Load text domain based on current language
 */
function sbs_load_textdomain()
{
    $current_lang = sbs_get_current_language();
    $languages = sbs_get_available_languages();

    if (isset($languages[$current_lang])) {
        $locale = $languages[$current_lang]['wp_locale'];

        // Unload existing textdomain first
        unload_textdomain('sbs-portal');

        // Set WordPress locale with high priority
        add_filter('locale', function () use ($locale) {
            return $locale;
        }, 10);

        // Load text domain with specific locale
        $mo_file = get_template_directory() . '/languages/' . $locale . '.mo';
        if (file_exists($mo_file)) {
            load_textdomain('sbs-portal', $mo_file);
        }

        // Also try to load the theme textdomain
        load_theme_textdomain('sbs-portal', get_template_directory() . '/languages');
    }
}
add_action('init', 'sbs_load_textdomain', 1);
add_action('wp_loaded', 'sbs_load_textdomain', 1);

/**
 * Helper function to get translated text based on current language
 */
function sbs_get_text($key, $translations = array())
{
    $current_lang = sbs_get_current_language();

    if (isset($translations[$current_lang])) {
        return $translations[$current_lang];
    }

    // Fallback to Japanese if translation not found
    if (isset($translations['ja'])) {
        return $translations['ja'];
    }

    // Return key if no translation found
    return $key;
}

/**
 * Debug function to check translation system status
 */
function sbs_debug_translation_status()
{
    if (!WP_DEBUG) return;

    $current_lang = sbs_get_current_language();
    $locale = get_locale();
    $mo_file = get_template_directory() . '/languages/' . $locale . '.mo';

    error_log("SBS Translation Debug:");
    error_log("Current Language: " . $current_lang);
    error_log("WordPress Locale: " . $locale);
    error_log("MO File: " . $mo_file);
    error_log("MO File Exists: " . (file_exists($mo_file) ? 'Yes' : 'No'));
    error_log("Text Domain Loaded: " . (is_textdomain_loaded('sbs-portal') ? 'Yes' : 'No'));
}

/**
 * Enqueue language-specific styles and scripts
 */
function sbs_enqueue_language_assets()
{
    $current_lang = sbs_get_current_language();
    $languages = sbs_get_available_languages();

    // Load text domain for current language
    if (isset($languages[$current_lang])) {
        $locale = $languages[$current_lang]['wp_locale'];
        load_theme_textdomain('sbs-portal', get_template_directory() . '/languages');

        // Override WordPress locale
        add_filter('locale', function () use ($locale) {
            return $locale;
        });
    }

    // Enqueue language-specific CSS if exists
    $lang_css_path = get_template_directory() . '/assets/css/languages/' . $current_lang . '.css';
    if (file_exists($lang_css_path)) {
        wp_enqueue_style(
            'sbs-language-' . $current_lang,
            get_template_directory_uri() . '/assets/css/languages/' . $current_lang . '.css',
            array('sbs-style'),
            '1.0.0'
        );
    }

    // Add current language to localized script data
    wp_localize_script('sbs-script', 'sbsLanguage', array(
        'current' => $current_lang,
        'available' => $languages,
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sbs_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'sbs_enqueue_language_assets', 110); // After main scripts

/**
 * ============================================================================
 * REST API: Campaigns & Blogs
 * ============================================================================
 */

/**
 * Register custom REST API routes under sbs/v1
 */
function sbs_register_rest_routes()
{
    register_rest_route(
        'sbs/v1',
        '/campaigns',
        array(
            'methods'  => 'GET',
            'callback' => 'sbs_api_get_campaigns',
            'permission_callback' => '__return_true',
            'args' => array(
                'page' => array(
                    'default' => 1,
                    'sanitize_callback' => 'absint',
                ),
                'per_page' => array(
                    'default' => 10,
                    'sanitize_callback' => 'absint',
                ),
                'search' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'orderby' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => 'date',
                ),
                'order' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => 'DESC',
                ),
            ),
        )
    );

    register_rest_route(
        'sbs/v1',
        '/campaign/(?P<id>\d+)',
        array(
            'methods'  => 'GET',
            'callback' => 'sbs_api_get_campaign_detail',
            'permission_callback' => '__return_true',
        )
    );

    register_rest_route(
        'sbs/v1',
        '/campaign/track',
        array(
            'methods'  => 'POST',
            'callback' => 'sbs_api_track_campaign',
            'permission_callback' => '__return_true',
            'args' => array(
                'campaign_id' => array(
                    'required' => true,
                    'sanitize_callback' => 'absint',
                ),
                'type' => array(
                    'required' => true,
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'ref' => array(
                    'required' => false,
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        )
    );

    // Metrics for a single campaign
    register_rest_route(
        'sbs/v1',
        '/campaign/(?P<id>\d+)/metrics',
        array(
            'methods'  => 'GET',
            'callback' => 'sbs_api_get_campaign_metrics',
            'permission_callback' => '__return_true',
        )
    );

    // Metrics for multiple campaigns: /campaigns/metrics?ids=1,2,3
    register_rest_route(
        'sbs/v1',
        '/campaigns/metrics',
        array(
            'methods'  => 'GET',
            'callback' => 'sbs_api_get_campaigns_metrics',
            'permission_callback' => '__return_true',
            'args' => array(
                'ids' => array(
                    'required' => true,
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        )
    );

    register_rest_route(
        'sbs/v1',
        '/blogs',
        array(
            'methods'  => 'GET',
            'callback' => 'sbs_api_get_blogs',
            'permission_callback' => '__return_true',
            'args' => array(
                'page' => array(
                    'default' => 1,
                    'sanitize_callback' => 'absint',
                ),
                'per_page' => array(
                    'default' => 10,
                    'sanitize_callback' => 'absint',
                ),
                'category' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'status' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'search' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'orderby' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => 'date',
                ),
                'order' => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => 'DESC',
                ),
            ),
        )
    );

    register_rest_route(
        'sbs/v1',
        '/blogs/(?P<id>\d+)',
        array(
            'methods'  => 'GET',
            'callback' => 'sbs_api_get_blog_detail',
            'permission_callback' => '__return_true',
        )
    );

    // Campaign analytics endpoint
    register_rest_route(
        'sbs/v1',
        '/campaign/(?P<id>\d+)/analytics',
        array(
            'methods'  => 'GET',
            'callback' => 'sbs_api_get_campaign_analytics',
            'permission_callback' => function () {
                return current_user_can('edit_posts');
            },
            'args' => array(
                'days' => array(
                    'default' => 30,
                    'sanitize_callback' => 'absint',
                ),
                'type' => array(
                    'default' => 'both',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        )
    );

    // Bulk campaign status check
    register_rest_route(
        'sbs/v1',
        '/campaigns/status',
        array(
            'methods'  => 'GET',
            'callback' => 'sbs_api_get_campaigns_status',
            'permission_callback' => '__return_true',
        )
    );
}
add_action('rest_api_init', 'sbs_register_rest_routes');

/**
 * Format a Campaign post for API responses
 * @param int $post_id
 * @param bool $include_content
 * @return array
 */
function sbs_format_campaign_post($post_id, $include_content = false)
{
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'campaign') {
        return array();
    }

    $impressions = (int) get_post_meta($post_id, '_campaign_impressions', true);
    $clicks = (int) get_post_meta($post_id, '_campaign_clicks', true);

    return array(
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'excerpt' => get_the_excerpt($post_id),
        'content' => $include_content ? apply_filters('the_content', $post->post_content) : '',
        'featured_image' => has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : '',
        'date' => get_the_date('Y-m-d', $post_id),
        'slug' => get_post_field('post_name', $post_id),
        'permalink' => get_permalink($post_id),
        'detail_url' => add_query_arg('post_id', $post_id, home_url('/campaign-detail/')),
        'metrics' => array(
            'impressions' => $impressions,
            'clicks' => $clicks,
        ),
    );
}

/**
 * Format a Blog post for API responses
 * @param int $post_id
 * @param bool $include_content
 * @return array
 */
function sbs_format_blog_post($post_id, $include_content = false)
{
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'blog') {
        return array();
    }

    // Prefer taxonomy term (blog_category) when available, fallback to legacy post meta
    $category_term = '';
    $terms = get_the_terms($post_id, 'blog_category');
    if (!empty($terms) && !is_wp_error($terms)) {
        // Use first term name (preserve label style from admin)
        $first = reset($terms);
        $category_term = $first->name;
    } else {
        $category_term = get_post_meta($post_id, '_blog_post_category', true);
    }

    return array(
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'excerpt' => get_the_excerpt($post_id),
        'content' => $include_content ? apply_filters('the_content', $post->post_content) : '',
        'featured_image' => has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : '',
        'date' => get_the_date('Y-m-d', $post_id),
        'category' => $category_term ?: 'BLOG',
        'status' => get_post_meta($post_id, '_blog_post_status', true),
        'permalink' => get_permalink($post_id),
        'slug' => get_post_field('post_name', $post_id),
    );
}

/**
 * GET /sbs/v1/campaigns
 */
function sbs_api_get_campaigns(WP_REST_Request $request)
{
    $page = max(1, (int) $request->get_param('page'));
    $per_page = max(1, min(50, (int) $request->get_param('per_page')));
    $search = (string) $request->get_param('search');
    $orderby = (string) $request->get_param('orderby');
    $order = strtoupper((string) $request->get_param('order')) === 'ASC' ? 'ASC' : 'DESC';

    $args = array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'paged' => $page,
        'posts_per_page' => $per_page,
        'orderby' => $orderby ?: 'date',
        'order' => $order,
        's' => $search,
    );

    $query = new WP_Query($args);

    $items = array();
    foreach ($query->posts as $post) {
        $items[] = sbs_format_campaign_post($post->ID, false);
    }

    $response = rest_ensure_response($items);
    $response->header('X-WP-Total', (string) $query->found_posts);
    $response->header('X-WP-TotalPages', (string) max(1, (int) $query->max_num_pages));
    return $response;
}

/**
 * GET /sbs/v1/campaign/{id}
 */
function sbs_api_get_campaign_detail(WP_REST_Request $request)
{
    $id = absint($request['id']);
    if (!$id) {
        return new WP_Error('invalid_id', 'Invalid campaign id', array('status' => 400));
    }

    $post = get_post($id);
    if (!$post || $post->post_type !== 'campaign' || $post->post_status !== 'publish') {
        return new WP_Error('not_found', 'Campaign not found', array('status' => 404));
    }

    return rest_ensure_response(sbs_format_campaign_post($id, true));
}

/**
 * POST /sbs/v1/campaign/track
 * Body: { campaign_id: int, type: impression|click, ref?: string, timestamp?: int, user_agent?: string, target_url?: string }
 */
function sbs_api_track_campaign(WP_REST_Request $request)
{
    $campaign_id = absint($request->get_param('campaign_id'));
    $type = strtolower((string) $request->get_param('type'));
    $ref = sanitize_text_field((string) $request->get_param('ref'));
    $timestamp = absint($request->get_param('timestamp')) ?: time();
    $user_agent = sanitize_text_field((string) $request->get_param('user_agent'));
    $target_url = esc_url_raw((string) $request->get_param('target_url'));
    $campaign_title = sanitize_text_field((string) $request->get_param('campaign_title'));

    if (!$campaign_id || !in_array($type, array('impression', 'click'), true)) {
        return new WP_Error('invalid_params', 'campaign_id and valid type are required', array('status' => 400));
    }

    $post = get_post($campaign_id);
    if (!$post || $post->post_type !== 'campaign' || $post->post_status !== 'publish') {
        return new WP_Error('not_found', 'Campaign not found', array('status' => 404));
    }

    // Check if tracking is enabled for this campaign
    $tracking_enabled = get_post_meta($campaign_id, '_campaign_tracking_enabled', true);
    if ($tracking_enabled === '0') {
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Tracking disabled for this campaign',
            'tracking_disabled' => true
        ));
    }

    // Check date limits
    $start_date = get_post_meta($campaign_id, '_campaign_start_date', true);
    $end_date = get_post_meta($campaign_id, '_campaign_end_date', true);
    $today = wp_date('Y-m-d');

    if (($start_date && $start_date > $today) || ($end_date && $end_date < $today)) {
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Campaign not active',
            'campaign_inactive' => true
        ));
    }

    // Check impression/click limits
    $meta_key = ($type === 'impression') ? '_campaign_impressions' : '_campaign_clicks';
    $current = (int) get_post_meta($campaign_id, $meta_key, true);

    $max_key = ($type === 'impression') ? '_campaign_max_impressions' : '_campaign_max_clicks';
    $max_limit = (int) get_post_meta($campaign_id, $max_key, true);

    if ($max_limit > 0 && $current >= $max_limit) {
        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Campaign limit reached',
            'limit_reached' => true,
            'current' => $current,
            'limit' => $max_limit
        ));
    }

    // Enhanced throttling by IP + campaign + type + user agent hash
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '0.0.0.0';
    $user_hash = substr(md5($user_agent . '|' . $ip), 0, 8);
    $key_base = sprintf('sbs_campaign_%s_%d_%s', $type, $campaign_id, $user_hash);
    $throttle_key = $key_base . '_ts';
    $cooldown = ($type === 'impression') ? MINUTE_IN_SECONDS * 10 : MINUTE_IN_SECONDS * 3; // 10m impressions, 3m clicks
    $last_ts = get_transient($throttle_key);

    if ($last_ts) {
        return rest_ensure_response(array(
            'success' => true,
            'throttled' => true,
            'cooldown_remaining' => $cooldown - (time() - $last_ts)
        ));
    }

    // Track the event
    $new_value = $current + 1;
    update_post_meta($campaign_id, $meta_key, $new_value);

    // Store detailed tracking data in separate option for analytics
    $tracking_data = get_option('sbs_campaign_tracking_' . $campaign_id, array());
    if (!is_array($tracking_data)) {
        $tracking_data = array();
    }

    if (!isset($tracking_data[$type])) {
        $tracking_data[$type] = array();
    }

    // Limit stored data to last 1000 entries per type
    if (count($tracking_data[$type]) >= 1000) {
        $tracking_data[$type] = array_slice($tracking_data[$type], -900); // Keep last 900
    }

    $tracking_data[$type][] = array(
        'timestamp' => $timestamp,
        'ref' => $ref,
        'ip_hash' => substr(md5($ip), 0, 8), // Store only hash for privacy
        'user_agent_hash' => substr(md5($user_agent), 0, 8),
        'target_url' => $target_url,
        'date' => wp_date('Y-m-d H:i:s', $timestamp)
    );

    update_option('sbs_campaign_tracking_' . $campaign_id, $tracking_data);

    set_transient($throttle_key, time(), $cooldown);

    // Update last activity
    update_post_meta($campaign_id, '_campaign_last_activity', time());

    return rest_ensure_response(array(
        'success' => true,
        'campaign_id' => $campaign_id,
        'type' => $type,
        'count' => $new_value,
        'timestamp' => $timestamp,
        'remaining_limit' => $max_limit > 0 ? max(0, $max_limit - $new_value) : null
    ));
}

/**
 * GET /sbs/v1/campaign/{id}/metrics
 */
function sbs_api_get_campaign_metrics(WP_REST_Request $request)
{
    $id = absint($request['id']);
    if (!$id) {
        return new WP_Error('invalid_id', 'Invalid campaign id', array('status' => 400));
    }

    $post = get_post($id);
    if (!$post || $post->post_type !== 'campaign') {
        return new WP_Error('not_found', 'Campaign not found', array('status' => 404));
    }

    $impressions = (int) get_post_meta($id, '_campaign_impressions', true);
    $clicks = (int) get_post_meta($id, '_campaign_clicks', true);

    return rest_ensure_response(array(
        'id' => $id,
        'impressions' => $impressions,
        'clicks' => $clicks,
    ));
}

/**
 * GET /sbs/v1/campaigns/metrics?ids=1,2,3
 */
function sbs_api_get_campaigns_metrics(WP_REST_Request $request)
{
    $idsParam = (string) $request->get_param('ids');
    if (empty($idsParam)) {
        return new WP_Error('invalid_params', 'ids is required', array('status' => 400));
    }

    $ids = array_filter(array_map('absint', preg_split('/[|,;\s]+/', $idsParam)));
    if (empty($ids)) {
        return new WP_Error('invalid_params', 'No valid ids provided', array('status' => 400));
    }

    $results = array();
    foreach ($ids as $id) {
        $post = get_post($id);
        if ($post && $post->post_type === 'campaign') {
            $results[] = array(
                'id' => $id,
                'impressions' => (int) get_post_meta($id, '_campaign_impressions', true),
                'clicks' => (int) get_post_meta($id, '_campaign_clicks', true),
            );
        }
    }

    return rest_ensure_response($results);
}

/**
 * GET /sbs/v1/blogs
 */
function sbs_api_get_blogs(WP_REST_Request $request)
{
    $page = max(1, (int) $request->get_param('page'));
    $per_page = max(1, min(50, (int) $request->get_param('per_page')));
    $category = (string) $request->get_param('category'); // BLOG|NEWS|EVENT|CAMPAIGN or multiple delimited by | , ;
    $status = (string) $request->get_param('status'); // optional: published|draft|private
    $search = (string) $request->get_param('search');
    $orderby = (string) $request->get_param('orderby');
    $order = strtoupper((string) $request->get_param('order')) === 'ASC' ? 'ASC' : 'DESC';

    $meta_query = array('relation' => 'AND');

    // Only apply status filter if provided; otherwise do not restrict by custom meta
    if (!empty($status)) {
        $meta_query[] = array(
            'key' => '_blog_post_status',
            'value' => $status,
            'compare' => '=',
        );
    }

    if (!empty($category)) {
        // Accept multiple categories separated by | , ; or space
        $delimited = preg_split('/[|,;\s]+/', $category);
        $categories = array_filter(array_map('trim', (array) $delimited));

        if (!empty($categories)) {
            $meta_query[] = array(
                'key' => '_blog_post_category',
                'value' => $categories,
                'compare' => 'IN',
            );
        }
    }

    $args = array(
        'post_type' => 'blog',
        'post_status' => 'publish',
        'paged' => $page,
        'posts_per_page' => $per_page,
        'orderby' => $orderby ?: 'date',
        'order' => $order,
        's' => $search,
        'meta_query' => $meta_query,
    );

    // If ordering by custom order first, mirror theme helper
    if ($orderby === 'menu_order' || $orderby === 'meta_value_num') {
        $args['meta_key'] = '_blog_post_order';
        $args['orderby'] = array('meta_value_num' => 'ASC', 'date' => $order);
    }

    $query = new WP_Query($args);

    $items = array();
    foreach ($query->posts as $post) {
        $formatted = sbs_format_blog_post($post->ID, false);
        // Exclude content in list
        unset($formatted['content']);
        $items[] = $formatted;
    }

    $response = rest_ensure_response($items);
    $response->header('X-WP-Total', (string) $query->found_posts);
    $response->header('X-WP-TotalPages', (string) max(1, (int) $query->max_num_pages));
    return $response;
}

/**
 * GET /sbs/v1/blogs/{id}
 */
function sbs_api_get_blog_detail(WP_REST_Request $request)
{
    $id = absint($request['id']);
    if (!$id) {
        return new WP_Error('invalid_id', 'Invalid blog id', array('status' => 400));
    }

    $post = get_post($id);
    if (!$post || $post->post_type !== 'blog' || $post->post_status !== 'publish') {
        return new WP_Error('not_found', 'Blog not found', array('status' => 404));
    }

    return rest_ensure_response(sbs_format_blog_post($id, true));
}

/**
 * GET /sbs/v1/campaign/{id}/analytics
 */
function sbs_api_get_campaign_analytics(WP_REST_Request $request)
{
    $id = absint($request['id']);
    $days = max(1, min(365, (int) $request->get_param('days')));
    $type = (string) $request->get_param('type');

    if (!$id) {
        return new WP_Error('invalid_id', 'Invalid campaign id', array('status' => 400));
    }

    $post = get_post($id);
    if (!$post || $post->post_type !== 'campaign') {
        return new WP_Error('not_found', 'Campaign not found', array('status' => 404));
    }

    // Get tracking data
    $tracking_data = get_option('sbs_campaign_tracking_' . $id, array());
    $cutoff_time = time() - ($days * DAY_IN_SECONDS);

    $analytics = array(
        'campaign_id' => $id,
        'campaign_title' => get_the_title($id),
        'period_days' => $days,
        'total_impressions' => (int) get_post_meta($id, '_campaign_impressions', true),
        'total_clicks' => (int) get_post_meta($id, '_campaign_clicks', true),
        'last_activity' => (int) get_post_meta($id, '_campaign_last_activity', true),
        'campaign_settings' => array(
            'type' => get_post_meta($id, '_campaign_type', true),
            'start_date' => get_post_meta($id, '_campaign_start_date', true),
            'end_date' => get_post_meta($id, '_campaign_end_date', true),
            'max_impressions' => (int) get_post_meta($id, '_campaign_max_impressions', true),
            'max_clicks' => (int) get_post_meta($id, '_campaign_max_clicks', true),
            'tracking_enabled' => get_post_meta($id, '_campaign_tracking_enabled', true) !== '0'
        ),
        'daily_stats' => array(),
        'hourly_stats' => array(),
        'recent_activity' => array()
    );

    if (!empty($tracking_data)) {
        // Process daily stats
        $daily_impressions = array();
        $daily_clicks = array();

        foreach (['impression', 'click'] as $event_type) {
            if (!isset($tracking_data[$event_type])) continue;

            foreach ($tracking_data[$event_type] as $event) {
                if ($event['timestamp'] < $cutoff_time) continue;

                $date = wp_date('Y-m-d', $event['timestamp']);
                $hour = wp_date('H', $event['timestamp']);

                // Daily stats
                if ($event_type === 'impression') {
                    $daily_impressions[$date] = ($daily_impressions[$date] ?? 0) + 1;
                } else {
                    $daily_clicks[$date] = ($daily_clicks[$date] ?? 0) + 1;
                }

                // Hourly stats (last 24h only)
                if ($event['timestamp'] > time() - DAY_IN_SECONDS) {
                    $hour_key = wp_date('Y-m-d H:00', $event['timestamp']);
                    if (!isset($analytics['hourly_stats'][$hour_key])) {
                        $analytics['hourly_stats'][$hour_key] = array('impressions' => 0, 'clicks' => 0);
                    }
                    $analytics['hourly_stats'][$hour_key][$event_type . 's']++;
                }

                // Recent activity (last 100 events)
                if (count($analytics['recent_activity']) < 100) {
                    $analytics['recent_activity'][] = array(
                        'type' => $event_type,
                        'timestamp' => $event['timestamp'],
                        'date' => $event['date'],
                        'ref' => $event['ref']
                    );
                }
            }
        }

        // Combine daily stats
        $all_dates = array_unique(array_merge(array_keys($daily_impressions), array_keys($daily_clicks)));
        foreach ($all_dates as $date) {
            $impressions = $daily_impressions[$date] ?? 0;
            $clicks = $daily_clicks[$date] ?? 0;
            $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;

            $analytics['daily_stats'][$date] = array(
                'date' => $date,
                'impressions' => $impressions,
                'clicks' => $clicks,
                'ctr' => $ctr
            );
        }

        // Sort by date
        ksort($analytics['daily_stats']);
        ksort($analytics['hourly_stats']);

        // Sort recent activity by timestamp desc
        usort($analytics['recent_activity'], function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
    }

    // Calculate summary stats
    $period_impressions = array_sum(array_column($analytics['daily_stats'], 'impressions'));
    $period_clicks = array_sum(array_column($analytics['daily_stats'], 'clicks'));
    $period_ctr = $period_impressions > 0 ? round(($period_clicks / $period_impressions) * 100, 2) : 0;

    $analytics['period_stats'] = array(
        'impressions' => $period_impressions,
        'clicks' => $period_clicks,
        'ctr' => $period_ctr,
        'avg_impressions_per_day' => $days > 0 ? round($period_impressions / $days, 1) : 0,
        'avg_clicks_per_day' => $days > 0 ? round($period_clicks / $days, 1) : 0
    );

    return rest_ensure_response($analytics);
}

/**
 * GET /sbs/v1/campaigns/status
 */
function sbs_api_get_campaigns_status(WP_REST_Request $request)
{
    $campaigns = get_posts(array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));

    $status_data = array();
    $today = wp_date('Y-m-d');

    foreach ($campaigns as $campaign_id) {
        $start_date = get_post_meta($campaign_id, '_campaign_start_date', true);
        $end_date = get_post_meta($campaign_id, '_campaign_end_date', true);
        $tracking_enabled = get_post_meta($campaign_id, '_campaign_tracking_enabled', true) !== '0';
        $max_impressions = (int) get_post_meta($campaign_id, '_campaign_max_impressions', true);
        $max_clicks = (int) get_post_meta($campaign_id, '_campaign_max_clicks', true);
        $current_impressions = (int) get_post_meta($campaign_id, '_campaign_impressions', true);
        $current_clicks = (int) get_post_meta($campaign_id, '_campaign_clicks', true);
        $last_activity = (int) get_post_meta($campaign_id, '_campaign_last_activity', true);

        // Determine status
        $status = 'active';
        $status_reason = '';

        if (!$tracking_enabled) {
            $status = 'disabled';
            $status_reason = 'Tracking disabled';
        } elseif ($start_date && $start_date > $today) {
            $status = 'scheduled';
            $status_reason = 'Starts ' . $start_date;
        } elseif ($end_date && $end_date < $today) {
            $status = 'expired';
            $status_reason = 'Ended ' . $end_date;
        } elseif ($max_impressions > 0 && $current_impressions >= $max_impressions) {
            $status = 'limit_reached';
            $status_reason = 'Impression limit reached';
        } elseif ($max_clicks > 0 && $current_clicks >= $max_clicks) {
            $status = 'limit_reached';
            $status_reason = 'Click limit reached';
        }

        $status_data[] = array(
            'id' => $campaign_id,
            'title' => get_the_title($campaign_id),
            'status' => $status,
            'status_reason' => $status_reason,
            'tracking_enabled' => $tracking_enabled,
            'impressions' => $current_impressions,
            'clicks' => $current_clicks,
            'last_activity' => $last_activity,
            'limits' => array(
                'max_impressions' => $max_impressions,
                'max_clicks' => $max_clicks,
                'impressions_remaining' => $max_impressions > 0 ? max(0, $max_impressions - $current_impressions) : null,
                'clicks_remaining' => $max_clicks > 0 ? max(0, $max_clicks - $current_clicks) : null
            )
        );
    }

    return rest_ensure_response($status_data);
}
