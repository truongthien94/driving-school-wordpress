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

<div class="faq-section">
    <!-- FAQ Title Bar -->
    <div class="faq-title-bar">
        <div class="faq-vertical-text">
            よくあるご質問
        </div>
        <div class="faq-main-title">
            FAQ
        </div>
    </div>

    <!-- FAQ Groups -->
    <div class="faq-groups">
        <?php if (!empty($faq_groups)): ?>
            <?php foreach ($faq_groups as $group): ?>
                <?php get_template_part('parts/faq-group', null, array('group' => $group)); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-faqs">
                <p>FAQはまだありません。</p>
            </div>
        <?php endif; ?>
    </div>
</div>