<?php

/**
 * The template for displaying the footer
 *
 * @package SBS_Portal
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$footer_data = sbs_get_footer_data();
?>

</div><!-- #page -->

<footer id="colophon" class="site-footer">
    <div class="footer-container">
        <!-- Footer Main Content -->
        <div class="footer-main">
            <!-- Company Info -->
            <div class="footer-company">
                <div class="company-logo">
                    <!-- Logo would go here -->
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/sbs-logo-dark.png"
                        alt="SBS Driving School" />
                </div>
                <?php if (isset($footer_data['logo']['text'])): ?>
                    <p class="company-description">
                        <?php echo esc_html($footer_data['logo']['text']); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Footer Columns -->
            <div class="footer-columns">
                <?php if (isset($footer_data['columns'])): ?>
                    <?php foreach ($footer_data['columns'] as $column): ?>
                        <div class="footer-column">
                            <h3 class="column-title"><?php echo esc_html($column['title']); ?></h3>
                            <ul class="column-links">
                                <?php foreach ($column['links'] as $link): ?>
                                    <li>
                                        <a href="<?php echo esc_url($link['url']); ?>">
                                            <?php echo esc_html($link['text']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Contact Information -->
            <div class="footer-contact">
                <?php if (isset($footer_data['contact'])): ?>
                    <?php foreach ($footer_data['contact'] as $contact): ?>
                        <div class="contact-item">
                            <h4><?php echo esc_html($contact['school']); ?></h4>
                            <p><?php echo esc_html($contact['phone']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="contact-button">
                    <a href="/contact" class="btn btn-primary">お問い合わせ</a>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <!-- Legal Links -->
            <?php if (isset($footer_data['legal'])): ?>
                <div class="legal-links">
                    <?php foreach ($footer_data['legal'] as $legal): ?>
                        <a href="<?php echo esc_url($legal['url']); ?>">
                            <?php echo esc_html($legal['text']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Copyright -->
            <div class="copyright">
                <?php if (isset($footer_data['copyright'])): ?>
                    <p><?php echo esc_html($footer_data['copyright']); ?></p>
                <?php else: ?>
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>

</html>