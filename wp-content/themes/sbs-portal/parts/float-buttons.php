<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<!-- Popup -->
<div class="sbs-popup position-fixed" id="sbs-popup" style="display: none;">
    <div class="popup-content position-relative user-select-none" id="draggable-popup" style="cursor: move;">
        <!-- Popup Header -->
        <div class="popup-header d-flex justify-content-center align-items-center position-relative" style="cursor: move;">
            <button class="popup-close position-absolute btn p-0 d-flex align-items-center justify-content-center" id="popup-close">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-close.svg" alt="Close" class="img-fluid" />
            </button>
        </div>
        <!-- Popup Content -->
        <div class="popup-body position-relative d-flex flex-column justify-content-center align-items-center">
            <!-- Background Image -->
            <div class="popup-background position-absolute w-100 h-100">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-1.jpg" alt="SBS Background" class="w-100 h-100" style="object-fit: cover;" />
            </div>
        </div>
    </div>
</div>

<!-- Float Buttons -->
<div class="float-buttons position-fixed d-flex flex-column gap-3">
    <!-- Chat Button -->
    <div class="float-button float-chat d-flex align-items-center justify-content-center" id="float-chat" style="cursor: pointer;">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-chat.svg" alt="Chat" class="img-fluid" />
    </div>
    <!-- Contact Button -->
    <div class="float-button float-contact d-flex align-items-center justify-content-center" id="float-contact" style="cursor: pointer;">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-mail.svg" alt="Contact" class="img-fluid" />
    </div>
    <!-- Back to Top Button -->
    <div class="back-to-top position-fixed d-flex align-items-center justify-content-center" id="back-to-top" style="cursor: pointer;">
        <div class="back-to-top-content d-flex align-items-center justify-content-center gap-2 h-100 px-3">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-arrow-up.svg" alt="Back to top" class="img-fluid" />
            <span class="text-nowrap">Page top</span>
        </div>
    </div>
</div>