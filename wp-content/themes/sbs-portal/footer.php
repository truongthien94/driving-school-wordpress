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
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-footer.png"
                            alt="SBS Driving School" />
                    </a>
                </div>
                <div class="company-info">
                    <p class="company-description">
                        楽しく学んで確かな知識と技術の習得をモットーに、千葉・稲毛の地で60年間歩み続けて、多くの卒業生を育成してまいりました。
                    </p>
                </div>
            </div>

            <!-- Footer Columns -->
            <div class="footer-columns">
                <!-- Column 1: 探索 -->
                <div class="footer-column">
                    <h3 class="column-title">探索</h3>
                    <ul class="column-links">
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school">ごあいさつ</a></li>
                        <li><a href="#">企業情報</a></li>
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/news/?page=1&category=0&year=2025">ニュース</a></li>
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/group">SBSグループについて</a></li>
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/recruit/">採用</a></li>
                    </ul>
                </div>

                <!-- Column 3: SBS自動車学校 -->
                <div class="footer-column">
                    <h3 class="column-title">SBS自動車学校</h3>
                    <ul class="column-links">
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school/inage">SBSドライビングスクール稲毛</a></li>
                        <li><a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school/anesaki">SBSドライビングスクール姉崎</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="footer-contact">
                <div class="contact-info">
                    <div class="contact-item">
                        <a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school/inage/">SBSドライビングスクール稲毛</a>
                        <p>Tel: 043-259-6371</p>
                    </div>
                    <div class="contact-item">
                        <a href="https://www.sbs-drivingschool.co.jp/sbsjdgk/school/anesaki">SBSドライビングスクール姉崎</a>
                        <p>Tel: 0436-61-1131</p>
                    </div>
                </div>
                <div class="contact-button-wrapper">
                    <div class="contact-button" id="contact-button">
                        <span>お問い合わせ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <!-- Legal Links -->
            <?php if (isset($footer_data['legal'])): ?>
                <div class="legal-links d-flex justify-content-center mb-2">
                    <?php foreach ($footer_data['legal'] as $legal): ?>
                        <a class="<?php echo esc_attr($legal['class']); ?>">
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