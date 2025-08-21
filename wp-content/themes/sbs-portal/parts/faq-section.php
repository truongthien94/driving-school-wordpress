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
            <h2>FAQ</h2>
        </div>
        <div class="faq-japanese-title d-xl-none align-self-center">
            よくある質問
        </div>
        <div class="faq-vertical-text d-none d-xl-block align-self-end">
            よ<br>く<br>あ<br>る<br>ご<br>質<br>問
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
                    <p class="text-muted mb-0">FAQはまだありません。</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>