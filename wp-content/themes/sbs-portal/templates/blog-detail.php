<?php

/**
 * Blog Detail Template
 * 
 * Custom single template for CPT `blog` matching the blog list/header design
 *
 * @package SBS_Portal
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="sbs-blog-list">
    <section class="header-section">
        <div class="header-section-container">
            <div class="header-section-left">
                <div class="header-section-logo">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/sbs-logo-header.png" alt="SBS" />
                </div>
                <div class="header-section-title">
                    <h4>ブログ</h4>
                    <h1 class="fst-italic">BLOG and NEWS</h1>
                </div>
            </div>

            <div class="header-section-right">
                <div class="navigation-section-blog-list">
                    <?php get_template_part('parts/portal-navigation'); ?>
                </div>
                <div>
                    <img class="hero-circle" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero-circle.jpg" alt="circle" />
                    <img class="hero-circle-2" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/hero-circle.jpg" alt="circle" />
                </div>
            </div>
        </div>
    </section>

    <section class="blog-detail-content ">
        <div class="container my-5">
            <div class="blog-breadcrumbs mb-1">
                <div class="breadcrumb-list d-flex align-items-center ">
                    <div class="breadcrumb-item">
                        <a href="<?php echo home_url('/'); ?>" class="breadcrumb-link text-decoration-none">ポータル</a>
                    </div>
                    <div class="breadcrumb-separator d-flex align-items-center justify-content-center">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M6.4 3.2L11.07 8L6.4 12.8" stroke="currentColor" />
                        </svg>
                    </div>
                    <div class="breadcrumb-item">
                        <span class="breadcrumb-current">ブログ一覧</span>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <!-- Left: Main Article -->
                <div class="col-md-8">
                    <div class="bg-white p-4 rounded-3">
                        <div class="mb-4">
                            <span class="text-secondary">2025-07-22</span>
                            <h4>敬愛学園女子バレー部</h4>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold">コース情報</h5>
                            <div>
                                <p>皆さんこんにちは！</p>
                                <p>久しぶりの投稿となってしまいました。</p>
                                <p>この度、SBS自動車学校は敬愛学園女子バレーボール部のスポンサーに就任いたしました！</p>
                                <p>敬愛学園は、千葉市稲毛区内の教習所から近い学校でもあり、同じ敷地の敬愛大学からも多くの生徒さんが通ってくれています。お話を頂いた際には即決させていただきました。</p>
                                <p>個人的にバレーボールが大好きという理由が一番大きいですが…</p>
                                <p>そして、スポンサー契約直後にインターハイ出場が決定し、社内でもお祭り騒ぎとなっております！</p>
                                <p>しかも今回は、スポンサーとしてユニフォームへのロゴ入れをさせていただきました。</p>
                                <p>壮行会に参加させていただき、そこで新ユニフォームのお披露目となりました。なんだか感慨深いですね。</p>
                                <p>このユニフォームは教習所内に展示しますので、お越しの際にはぜひご覧ください！</p>
                                <p>インターハイは8月6日から始まります。会社をあげて応援します！</p>
                                <p>1人のバレーボールファンとしても、全力で応援していきたいと思います！</p>
                                <p>頑張れ！敬愛学園女子バレーボール部！！</p>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold">レッスン一覧</h5>
                            <div>
                                <p>皆さんこんにちは！</p>
                                <p>久しぶりの投稿となってしまいました。</p>
                                <p>この度、SBS自動車学校は敬愛学園女子バレーボール部のスポンサーに就任いたしました！</p>
                                <p>敬愛学園は、千葉市稲毛区内の教習所から近い学校でもあり、同じ敷地の敬愛大学からも多くの生徒さんが通ってくれています。お話を頂いた際には即決させていただきました。</p>
                                <p>個人的にバレーボールが大好きという理由が一番大きいですが…</p>
                                <p>そして、スポンサー契約直後にインターハイ出場が決定し、社内でもお祭り騒ぎとなっております！</p>
                                <p>しかも今回は、スポンサーとしてユニフォームへのロゴ入れをさせていただきました。</p>
                                <p>壮行会に参加させていただき、そこで新ユニフォームのお披露目となりました。なんだか感慨深いですね。</p>
                                <p>このユニフォームは教習所内に展示しますので、お越しの際にはぜひご覧ください！</p>
                                <p>インターハイは8月6日から始まります。会社をあげて応援します！</p>
                                <p>1人のバレーボールファンとしても、全力で応援していきたいと思います！</p>
                                <p>頑張れ！敬愛学園女子バレーボール部！！</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Sidebar -->
                 
                <aside class="col-md-4 blog-detail-sidebar">
                    <div class="blog-posts-main-grid">
                        <?php
                        // Always render 3 mock posts in the sidebar (vertical list)
                        $mock_blog_posts = sbs_get_latest_blog_posts(3);

                        if (!empty($mock_blog_posts)) :
                            foreach ($mock_blog_posts as $mock_post) :
                                $post_data = $mock_post;
                        ?>
                                <div class="mb-3">
                                    <?php get_template_part('parts/blog-card-large', null, array('post' => $post_data)); ?>
                                </div>
                            <?php endforeach;
                        else : ?>
                            <div class="no-blog-posts text-center py-5">
                                <p class="text-muted">現在、ブログ投稿はありません。</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-center mt-2">
                        <button type="button" class="sbs-btn-outline-sm">
                            <span class="text-secondary d-inline-flex align-items-center gap-2">
                                <span>もっと見る</span>
                            </span>
                        </button>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <?php get_template_part('parts/float-buttons'); ?>
</div>

<!-- Footer Background for Blog Detail -->
<div class="footer-background blog-list-footer" style="background-image: url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/footer-bg.jpg');"></div>