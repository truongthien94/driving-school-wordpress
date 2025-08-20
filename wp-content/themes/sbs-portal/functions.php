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
 * Save Campaign highlight meta box
 */
function sbs_save_campaign_highlight_meta_box($post_id)
{
    if (
        !isset($_POST['sbs_campaign_highlight_meta_box_nonce']) ||
        !wp_verify_nonce($_POST['sbs_campaign_highlight_meta_box_nonce'], 'sbs_campaign_highlight_meta_box')
    ) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

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
add_action('save_post', 'sbs_save_campaign_highlight_meta_box');

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
        'car' => 'Car Icon',
        'star' => 'Star Icon'
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
 * Get Campaign posts for banner carousel
 *
 * @param int $limit Maximum number of campaigns to retrieve
 * @return array{id:int,title:string,image_src:string,permalink:string}[]
 */
function sbs_get_campaign_items(int $limit = 10): array
{
    $args = array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $query = new WP_Query($args);
    $items = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            $image_src = has_post_thumbnail($post_id)
                ? get_the_post_thumbnail_url($post_id, 'full')
                : '';

            $slug = get_post_field('post_name', $post_id);
            // Build campaign-detail link by ID to avoid title/slug mismatch
            $detail_url = add_query_arg('post_id', $post_id, home_url('/campaign-detail/'));

            $items[] = array(
                'id' => $post_id,
                'title' => get_the_title(),
                'image_src' => $image_src,
                'permalink' => get_permalink($post_id),
                'slug' => $slug,
                'detail_url' => $detail_url,
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
    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_query' => array(
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
        )
    );

    // Add category filter if specified
    if (!empty($category)) {
        $args['meta_query'][] = array(
            'key' => '_blog_post_category',
            'value' => $category,
            'compare' => '='
        );
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

            $posts[] = array(
                'id' => $post_id,
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'content' => get_the_content(),
                'featured_image' => has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'full') : '',
                'date' => get_the_date('Y-m-d'),
                'category' => get_post_meta($post_id, '_blog_post_category', true),
                'status' => get_post_meta($post_id, '_blog_post_status', true),
                'permalink' => get_permalink()
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
            'locale' => 'ja'
        ),
        'en' => array(
            'name' => 'English',
            'native_name' => 'English',
            'flag' => '🇺🇸',
            'locale' => 'en'
        ),
        'id' => array(
            'name' => 'Indonesia',
            'native_name' => 'Indonesia',
            'flag' => '🇮🇩',
            'locale' => 'id'
        )
    );
}

/**
 * Get current language
 */
function sbs_get_current_language()
{
    // Get from session/cookie first, then default to Japanese
    $current_lang = isset($_SESSION['sbs_language']) ? $_SESSION['sbs_language'] : 'ja';

    // Also check if stored in cookie
    if (isset($_COOKIE['sbs_language'])) {
        $current_lang = sanitize_text_field($_COOKIE['sbs_language']);
    }

    // Validate language exists
    $available_languages = sbs_get_available_languages();
    if (!array_key_exists($current_lang, $available_languages)) {
        $current_lang = 'ja'; // fallback to Japanese
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
            return $languages[$language_code]['locale'];
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
        wp_die(json_encode(array(
            'success' => true,
            'message' => 'Language switched successfully',
            'language' => $language_code
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
        return $languages[$current_lang]['locale'];
    }

    return $locale;
}
add_filter('locale', 'sbs_apply_current_language_locale');

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
 * Enqueue language-specific styles and scripts
 */
function sbs_enqueue_language_assets()
{
    $current_lang = sbs_get_current_language();

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
        'available' => sbs_get_available_languages(),
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sbs_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'sbs_enqueue_language_assets');

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

    return array(
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'excerpt' => get_the_excerpt($post_id),
        'content' => $include_content ? apply_filters('the_content', $post->post_content) : '',
        'featured_image' => has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : '',
        'date' => get_the_date('Y-m-d', $post_id),
        'category' => get_post_meta($post_id, '_blog_post_category', true),
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
 * Body: { campaign_id: int, type: impression|click, ref?: string }
 */
function sbs_api_track_campaign(WP_REST_Request $request)
{
    $campaign_id = absint($request->get_param('campaign_id'));
    $type = strtolower((string) $request->get_param('type'));
    $ref = sanitize_text_field((string) $request->get_param('ref'));

    if (!$campaign_id || !in_array($type, array('impression', 'click'), true)) {
        return new WP_Error('invalid_params', 'campaign_id and valid type are required', array('status' => 400));
    }

    $post = get_post($campaign_id);
    if (!$post || $post->post_type !== 'campaign') {
        return new WP_Error('not_found', 'Campaign not found', array('status' => 404));
    }

    // Basic throttling by IP + campaign + type
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '0.0.0.0';
    $key_base = sprintf('sbs_campaign_%s_%d_%s', $type, $campaign_id, md5($ip . '|' . $ref));
    $throttle_key = $key_base . '_ts';
    $cooldown = ($type === 'impression') ? MINUTE_IN_SECONDS * 15 : MINUTE_IN_SECONDS * 5; // 15m impressions, 5m clicks
    $last_ts = get_transient($throttle_key);

    if ($last_ts) {
        // Already counted recently
        return rest_ensure_response(array('success' => true, 'throttled' => true));
    }

    $meta_key = ($type === 'impression') ? '_campaign_impressions' : '_campaign_clicks';
    $current = (int) get_post_meta($campaign_id, $meta_key, true);
    $new_value = $current + 1;
    update_post_meta($campaign_id, $meta_key, $new_value);

    set_transient($throttle_key, time(), $cooldown);

    return rest_ensure_response(array(
        'success' => true,
        'campaign_id' => $campaign_id,
        'type' => $type,
        'count' => $new_value,
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
