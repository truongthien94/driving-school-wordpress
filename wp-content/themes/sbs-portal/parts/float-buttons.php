<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<!-- Popup -->
<div class="sbs-popup" id="sbs-popup">
    <div class="popup-content" id="draggable-popup">
        <button class="popup-close position-absolute" id="popup-close">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-x.svg" alt="Close" class="img-fluid" />
        </button>
        <div class="popup-background">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ads.png" alt="SBS Background" />
        </div>
    </div>
</div>


<div class="float-buttons">
    <div class="float-button float-chat" id="float-chat">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-chat.svg" alt="Chat" />
    </div>
    <div class="float-button float-contact" id="float-contact">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-mail.svg" alt="Contact" />
    </div>
    <div class="back-to-top" id="back-to-top">
        <div class="back-to-top-content">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-arrow-up.svg" alt="Back to top" />
            <span>Page top</span>
        </div>
    </div>
</div>