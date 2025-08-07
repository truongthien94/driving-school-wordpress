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
        <div class="faq-main-title">
            FAQ
        </div>
        <div class="faq-vertical-text">
            よ<br>く<br>あ<br>る<br>ご<br>質<br>問
        </div>
    </div>

    <!-- FAQ Content Container -->
    <div class="faq-content-container">
        <!-- FAQ Logos Section - Positioned in upper right -->
        <div class="faq-logos-section">
            <div class="logos-container">
                <div class="logo-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-logo-small.png" alt="SBS Logo" />
                </div>
                <div class="logo-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-logo-small.png" alt="SBS Logo" />
                </div>
                <div class="logo-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-logo-small.png" alt="SBS Logo" />
                </div>
            </div>
        </div>

        <!-- FAQ Groups - Overlapping the logos section -->
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
</div>