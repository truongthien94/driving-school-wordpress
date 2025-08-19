<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get highlighted campaign
$highlighted_campaign = sbs_get_highlighted_campaign();
?>
<!-- Popup -->
<div class="sbs-popup" id="sbs-popup">
    <div class="popup-content" id="draggable-popup">
        <button class="popup-close position-absolute" id="popup-close">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/icon-x.svg" alt="Close" class="img-fluid" />
        </button>
        <div class="popup-background">
            <?php if ($highlighted_campaign && !empty($highlighted_campaign['featured_image'])): ?>
                <a href="<?php echo esc_url($highlighted_campaign['detail_url']); ?>"
                    data-campaign-id="<?php echo esc_attr($highlighted_campaign['id']); ?>"
                    class="popup-campaign-link">
                    <img src="<?php echo esc_url($highlighted_campaign['featured_image']); ?>"
                        alt="<?php echo esc_attr($highlighted_campaign['title']); ?>" />
                </a>
            <?php else: ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ads.png" alt="SBS Background" />
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($highlighted_campaign): ?>
    <script>
        (function() {
            // Campaign click tracking for popup
            document.addEventListener('click', function(e) {
                var link = e.target.closest('a.popup-campaign-link[data-campaign-id]');
                if (!link) return;

                var id = link.getAttribute('data-campaign-id');
                var ref = window.location.pathname || '/';
                var restUrl = (window.sbsThemeData && window.sbsThemeData.restUrl ? window.sbsThemeData.restUrl : '/wp-json/') + 'sbs/v1/campaign/track';

                var payload = JSON.stringify({
                    campaign_id: parseInt(id, 10),
                    type: 'click',
                    ref: ref
                });

                // Non-blocking tracking
                if (navigator.sendBeacon) {
                    var blob = new Blob([payload], {
                        type: 'application/json'
                    });
                    navigator.sendBeacon(restUrl, blob);
                } else if (window.fetch) {
                    fetch(restUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: payload,
                        keepalive: true
                    });
                }
            }, true);
        })();
    </script>
<?php endif; ?>

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