<?php

/**
 * FAQ Item Component
 * 
 * Individual FAQ question and answer
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get question data from args
$question = isset($args['question']) ? $args['question'] : null;

if (!$question) {
    return;
}

$is_expanded = !empty($question['expanded']);
$expanded_class = $is_expanded ? 'expanded' : '';

// Print component-scoped styles once per request to avoid duplication
if (!defined('SBS_FAQ_ITEM_STYLES_PRINTED')) {
    define('SBS_FAQ_ITEM_STYLES_PRINTED', true);
?>
    <style>
        /* Height-less smooth open/close using CSS grid */
        .faq-item .faq-answer-wrapper {
            display: grid;
            grid-template-rows: 0fr;
            overflow: hidden;
            transition: grid-template-rows .3s ease, opacity .2s ease;
            opacity: 0;
        }

        .faq-item.expanded .faq-answer-wrapper {
            grid-template-rows: 1fr;
            opacity: 1;
        }

        .faq-item .faq-answer {
            min-height: 0;
        }
    </style>
<?php
}
?>

<div class="faq-item <?php echo esc_attr($expanded_class); ?>" data-question-id="<?php echo esc_attr($question['id']); ?>" data-plus-url="<?php echo esc_attr(get_template_directory_uri() . '/assets/images/icons/icon-plus.svg'); ?>" data-minus-url="<?php echo esc_attr(get_template_directory_uri() . '/assets/images/icons/icon-minus.svg'); ?>">
    <!-- Question -->
    <div class="faq-question user-select-none" style="cursor: pointer;" role="button" tabindex="0" aria-expanded="<?php echo $is_expanded ? 'true' : 'false'; ?>" aria-controls="faq-answer-<?php echo esc_attr($question['id']); ?>">
        <div class="question-content d-flex justify-content-between align-items-center gap-2 py-2">
            <h4 class="question-text mb-0 flex-grow-1"><?php echo esc_html($question['question']); ?></h4>
            <div class="question-toggle d-flex align-items-center justify-content-center">
                <?php if ($question['expanded']): ?>
                    <div class="icon-container expanded d-flex align-items-center justify-content-center">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-minus.svg" alt="Minus" class="img-fluid" />
                    </div>
                <?php else: ?>
                    <div class="icon-container collapsed d-flex align-items-center justify-content-center">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-plus.svg" alt="Plus" class="img-fluid" />
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Answer -->
    <?php if (!empty($question['answer']) || !empty($question['detail'])): ?>
        <div class="faq-answer-wrapper" id="faq-answer-<?php echo esc_attr($question['id']); ?>" aria-hidden="<?php echo $is_expanded ? 'false' : 'true'; ?>">
            <div class="faq-answer pt-2 d-flex flex-column gap-2">
                <?php if (!empty($question['answer'])): ?>
                    <div class="answer-brief">
                        <?php echo wpautop($question['answer']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($question['detail'])): ?>
                    <div class="answer-detail">
                        <?php echo wpautop($question['detail']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>