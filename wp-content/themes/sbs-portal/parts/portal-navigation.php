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

<div class="nav-section">
    <div class="d-flex align-items-center justify-content-end">
        <ul class="d-flex list-unstyled align-items-center ms-auto mb-2 mb-lg-0 gap-3">
            <li class="nav-item  d-none d-xl-flex">
                <a class="nav-link active" aria-current="page" href="#">ごあいさつ</a>
            </li>
            <li class="nav-item dropdown  d-none d-xl-flex">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    企業情報
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">会社概要</a></li>
                    <li><a class="dropdown-item" href="#">沿革</a></li>
                </ul>
            </li>
            <li class="nav-item  d-none d-xl-flex">
                <a class="nav-link active">SBSグループについて</a>
            </li>
            <li class="nav-item dropdown d-none d-xl-flex">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    日本語
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">会社概要</a></li>
                    <li><a class="dropdown-item" href="#">沿革</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <nav class="navbar">
                    <div class="container-fluid">
                        <button class="navbar-toggler btn-custom-nav p-2 d-flex gap-2" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                            <span class=" d-none d-xl-flex">Menu</span>
                            <span><?php echo sbs_get_icon('align-justify'); ?></span>
                        </button>

                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                            <div class="offcanvas-header">

                                <button type="button" class="btn btn-dark" data-bs-dismiss="offcanvas" aria-label="Close">
                                    <?php echo sbs_get_icon('icon-x'); ?>
                                </button>
                            </div>
                            <div class="offcanvas-body">
                                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 ">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="#">ごあいさつ</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            企業情報
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">会社概要</a></li>
                                            <li><a class="dropdown-item" href="#">沿革</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active">SBSグループについて</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            日本語
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">会社概要</a></li>
                                            <li><a class="dropdown-item" href="#">沿革</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                </nav>
            </li>
        </ul>
    </div>
</div>