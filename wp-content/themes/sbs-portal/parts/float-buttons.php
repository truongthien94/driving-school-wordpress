<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<!-- Popup -->
<div class="sbs-popup" id="sbs-popup">
    <div class="popup-content" id="draggable-popup">
        <!-- Popup Header -->
        <div class="popup-header">
            <button class="popup-close" id="popup-close">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-close.svg" alt="Close" />
            </button>
        </div>
        <!-- Popup Content -->
        <div class="popup-body">
            <!-- Background Image -->
            <div class="popup-background">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery-1.jpg" alt="SBS Background" />
            </div>
        </div>
    </div>
</div>

<!-- Float Buttons -->
<div class="float-buttons">
    <!-- Chat Button -->
    <div class="float-button float-chat" id="float-chat">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-chat.svg" alt="Chat" />
    </div>
    <!-- Contact Button -->
    <div class="float-button float-contact" id="float-contact">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-mail.svg" alt="Contact" />
    </div>
    <!-- Back to Top Button -->
    <div class="back-to-top" id="back-to-top">
        <div class="back-to-top-content">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-arrow-up.svg" alt="Back to top" />
            <span>Page top</span>
        </div>
    </div>
</div>