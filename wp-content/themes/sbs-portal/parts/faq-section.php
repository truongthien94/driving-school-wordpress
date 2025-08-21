<?php

/**
 * FAQ Section
 * 
 * Displays FAQ accordion groups
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$faq_groups = sbs_get_faq_groups();
?>

<div id="faq-section" class="faq-section position-relative">
    <!-- FAQ Title Bar -->
    <div class="faq-title-bar ">
        <div class="faq-main-title p-3">
            FAQ
        </div>
        <div class="faq-japanese-title d-xl-none align-self-center">
            <?php echo __('Frequently Asked Questions', 'sbs-portal'); ?>
        </div>
        <div class="faq-vertical-text d-none d-xl-block align-self-end">
            <?php
            $faq_text = __('Frequently Asked Questions', 'sbs-portal');
            // For Japanese, use vertical layout
            if (get_locale() === 'ja') {
                echo 'よ<br>く<br>あ<br>る<br>ご<br>質<br>問';
            } else {
                // For other languages, split into characters/words
                $chars = mb_str_split($faq_text);
                echo implode('<br>', array_slice($chars, 0, 7));
            }
            ?>
        </div>
    </div>

    <!-- FAQ Content Container -->
    <div class="faq-content-container position-relative">
        <!-- FAQ Groups -->
        <div class="faq-groups-container position-relative d-flex flex-column gap-3">
            <?php if (!empty($faq_groups)): ?>
                <?php foreach ($faq_groups as $group): ?>
                    <?php get_template_part('parts/faq-group', null, array('group' => $group)); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-faqs text-center py-4">
                    <p class="text-muted mb-0"><?php echo __('No FAQs available yet.', 'sbs-portal'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>