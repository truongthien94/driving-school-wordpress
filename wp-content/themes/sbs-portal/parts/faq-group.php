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

$is_group_expanded = !empty($group['expanded']);
$expanded_class = $is_group_expanded ? 'expanded' : '';
?>

<div class="faq-group <?php echo esc_attr($expanded_class); ?>" data-group-id="<?php echo esc_attr($group['id']); ?>">
    <!-- Group Header -->
    <div class="faq-group-header d-flex align-items-center gap-3 mb-3 user-select-none" style="cursor: pointer;" role="button" tabindex="0" aria-expanded="<?php echo $is_group_expanded ? 'true' : 'false'; ?>" aria-controls="faq-group-content-<?php echo esc_attr($group['id']); ?>">
        <div class="group-title-container d-flex justify-content-between align-items-center flex-grow-1 gap-3">
            <h3 class="group-title mb-0"><?php echo esc_html($group['title']); ?></h3>
            <div class="group-toggle">
                <div class="toggle-icon">
                    <?php if ($is_group_expanded): ?>
                        <div class="icon-container expanded d-flex align-items-center justify-content-center">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-minus.svg" alt="Minus" width="16" height="16" />
                        </div>
                    <?php else: ?>
                        <div class="icon-container collapsed d-flex align-items-center justify-content-center">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-plus.svg" alt="Plus" width="16" height="16" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Group Content -->
    <div class="faq-group-content" id="faq-group-content-<?php echo esc_attr($group['id']); ?>" aria-hidden="<?php echo $is_group_expanded ? 'false' : 'true'; ?>">
        <?php if (!empty($group['questions'])): ?>
            <div class="faq-questions d-flex flex-column gap-2">
                <?php foreach ($group['questions'] as $question): ?>
                    <?php get_template_part('parts/faq-item', null, array('question' => $question)); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>