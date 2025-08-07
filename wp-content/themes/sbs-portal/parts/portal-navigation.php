<?php

/**
 * Portal Navigation
 * 
 * Main navigation menu for the portal
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$mock_data = sbs_get_mock_data();
$navigation = isset($mock_data['navigation']) ? $mock_data['navigation'] : array();
?>

<div class="portal-navigation">
    <div class="nav-container d-flex align-items-center justify-content-end gap-4">
        <!-- Main Navigation Links -->
        <div class="nav-links d-flex gap-4">
            <a href="/greeting" class="nav-link text-decoration-none">ごあいさつ</a>
            <a href="/company" class="nav-link text-decoration-none">企業情報</a>
            <a href="/group" class="nav-link text-decoration-none">SBSグループについて</a>
        </div>

        <!-- Language Selector -->
        <div class="language-selector position-relative">
            <div class="language-dropdown d-flex align-items-center gap-1 user-select-none" style="cursor: pointer;">
                <span class="current-language">日本語</span>
                <div class="dropdown-icon">
                    <?php echo sbs_get_icon('chevron-down'); ?>
                </div>

                <div class="language-options position-absolute bg-white border rounded shadow-sm p-2" style="display: none; top: 100%; right: 0; min-width: 120px; z-index: 100;">
                    <a href="#" class="language-option d-block p-2 text-decoration-none" data-lang="日本語">日本語</a>
                    <a href="#" class="language-option d-block p-2 text-decoration-none" data-lang="English">English</a>
                    <a href="#" class="language-option d-block p-2 text-decoration-none" data-lang="中文">中文</a>
                    <a href="#" class="language-option d-block p-2 text-decoration-none" data-lang="한국어">한국어</a>
                </div>
            </div>
        </div>

        <!-- Menu Button -->
        <div class="menu-button btn d-flex align-items-center gap-1 px-3 py-2" style="cursor: pointer;">
            <span>Menu</span>
        </div>
    </div>
</div>