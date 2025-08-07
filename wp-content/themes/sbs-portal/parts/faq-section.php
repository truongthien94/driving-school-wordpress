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

<div class="faq-section position-relative">
    <!-- FAQ Title Bar -->
    <div class="faq-title-bar d-flex flex-column justify-content-between align-items-center p-4">
        <div class="faq-main-title">
            FAQ
        </div>
        <div class="faq-vertical-text text-center">
            よ<br>く<br>あ<br>る<br>ご<br>質<br>問
        </div>
    </div>

    <!-- FAQ Content Container -->
    <div class="faq-content-container position-relative">
        <!-- FAQ Logos Section - Positioned in upper right -->
        <div class="faq-logos-section position-absolute d-flex align-items-center justify-content-center">
            <div class="logos-container d-flex flex-column align-items-center gap-3">
                <div class="logo-item d-flex align-items-center justify-content-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-logo-small.png" alt="SBS Logo" class="img-fluid" />
                </div>
                <div class="logo-item d-flex align-items-center justify-content-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-logo-small.png" alt="SBS Logo" class="img-fluid" />
                </div>
                <div class="logo-item d-flex align-items-center justify-content-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-logo-small.png" alt="SBS Logo" class="img-fluid" />
                </div>
            </div>
        </div>

        <!-- FAQ Groups - Overlapping the logos section -->
        <div class="faq-groups position-relative d-flex flex-column gap-4 p-4">
            <?php if (!empty($faq_groups)): ?>
                <?php foreach ($faq_groups as $group): ?>
                    <?php get_template_part('parts/faq-group', null, array('group' => $group)); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-faqs text-center py-5">
                    <p class="text-muted mb-0">FAQはまだありません。</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>