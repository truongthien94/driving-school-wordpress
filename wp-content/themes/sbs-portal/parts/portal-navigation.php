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
    <div class="nav-container">
        <!-- Main Navigation Links -->
        <div class="nav-links">
            <a href="/greeting" class="nav-link">ごあいさつ</a>
            <a href="/company" class="nav-link">企業情報</a>
            <a href="/group" class="nav-link">SBSグループについて</a>
        </div>

        <!-- Language Selector -->
        <div class="language-selector">
            <div class="language-dropdown">
                <span class="current-language">日本語</span>
                <div class="dropdown-icon">
                    <?php echo sbs_get_icon('chevron-down'); ?>
                </div>

                <div class="language-options">
                    <a href="#" class="language-option" data-lang="日本語">日本語</a>
                    <a href="#" class="language-option" data-lang="English">English</a>
                    <a href="#" class="language-option" data-lang="中文">中文</a>
                    <a href="#" class="language-option" data-lang="한국어">한국어</a>
                </div>
            </div>
        </div>

        <!-- Menu Button -->
        <div class="menu-button">
            <span>Menu</span>
        </div>
    </div>
</div>