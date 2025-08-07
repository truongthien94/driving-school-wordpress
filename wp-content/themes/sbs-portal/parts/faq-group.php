<?php

/**
 * FAQ Group Component
 * 
 * Individual FAQ group with questions
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get group data from args
$group = isset($args['group']) ? $args['group'] : null;

if (!$group) {
    return;
}

$expanded_class = $group['expanded'] ? 'expanded' : '';
?>

<div class="faq-group <?php echo esc_attr($expanded_class); ?>" data-group-id="<?php echo esc_attr($group['id']); ?>">
    <!-- Group Header -->
    <div class="faq-group-header">
        <div class="group-indicator" style="background-color: <?php echo esc_attr($group['color']); ?>"></div>
        <div class="group-title-container">
            <h3 class="group-title"><?php echo esc_html($group['title']); ?></h3>
            <div class="group-toggle">
                <div class="toggle-icon">
                    <?php if ($group['expanded']): ?>
                        <div class="icon-container expanded">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-minus.svg" alt="Minus" />
                        </div>
                    <?php else: ?>
                        <div class="icon-container collapsed">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-plus.svg" alt="Plus" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Group Content -->
    <div class="faq-group-content">
        <?php if (!empty($group['questions'])): ?>
            <div class="faq-questions">
                <?php foreach ($group['questions'] as $question): ?>
                    <?php get_template_part('parts/faq-item', null, array('question' => $question)); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>