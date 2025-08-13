<?php

/**
 * Breadcrumbs Section Template
 * 
 * Reusable breadcrumbs section for blog, campaign, and other detail pages
 * Accepts dynamic breadcrumb parameters
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get parameters with defaults
$breadcrumb_items = isset($args['breadcrumb_items']) ? $args['breadcrumb_items'] : array('ブログ一覧');
?>

<div class="blog-breadcrumbs mb-1">
    <div class="breadcrumb-list d-flex align-items-center">
        <div class="breadcrumb-item">
            <a href="<?php echo home_url('/'); ?>" class="breadcrumb-link text-decoration-none">ポータル</a>
        </div>

        <?php foreach ($breadcrumb_items as $index => $item): ?>
            <div class="breadcrumb-separator d-flex align-items-center justify-content-center">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M6.4 3.2L11.07 8L6.4 12.8" stroke="currentColor" />
                </svg>
            </div>
            <div class="breadcrumb-item">
                <?php if ($index === count($breadcrumb_items) - 1): ?>
                    <span class="breadcrumb-current"><?php echo esc_html($item); ?></span>
                <?php else: ?>
                    <a href="#" class="breadcrumb-link text-decoration-none"><?php echo esc_html($item); ?></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>