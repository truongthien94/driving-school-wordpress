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
    <div class="faq-question">
        <div class="question-content">
            <h4 class="question-text"><?php echo esc_html($question['question']); ?></h4>
            <div class="question-toggle">
                <?php if ($question['expanded']): ?>
                    <?php echo sbs_get_icon('minus'); ?>
                <?php else: ?>
                    <?php echo sbs_get_icon('plus'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Answer -->
    <?php if ($question['expanded'] && (!empty($question['answer']) || !empty($question['detail']))): ?>
        <div class="faq-answer">
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
    <div class="faq-divider"></div>
</div>