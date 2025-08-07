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

$expanded_class = $question['expanded'] ? 'expanded' : '';
?>

<div class="faq-item <?php echo esc_attr($expanded_class); ?>" data-question-id="<?php echo esc_attr($question['id']); ?>">
    <!-- Question -->
    <div class="faq-question user-select-none" style="cursor: pointer;">
        <div class="question-content d-flex justify-content-between align-items-center gap-2 py-3">
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
        <div class="faq-answer pt-3 d-flex flex-column gap-2">
            <?php if (!empty($question['answer'])): ?>
                <div class="answer-brief">
                    <?php echo esc_html($question['answer']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($question['detail'])): ?>
                <div class="answer-detail">
                    <?php echo esc_html($question['detail']); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Divider -->
    <div class="faq-divider border-bottom pb-3 mb-3"></div>
</div>