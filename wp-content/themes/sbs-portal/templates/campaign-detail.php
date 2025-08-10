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
        <div class="hero-navigation row-gap-0 p-0 row py-4 flex justify-content-end">
            <div class="col-xl-6">
                <?php get_template_part('parts/portal-navigation'); ?>
            </div>
        </div>
        <div class="header-section-container">
            <div class="header-section-left">
                <div class="header-section-logo">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/sbs-logo-header.png" alt="SBS" />
                </div>
                <div class="header-section-title">
                    <h4>キャンペーン情報</h4>
                    <h1 class="fst-italic text-nowrap">Campaign</h1>
                </div>
            </div>

            <div class="header-section-right d-flex justify-content-end">
                <div class="d-flex justify-content-end">
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
                        <div class="campaign-detail-img mb-4">
                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/campaign-detail.png" alt="campaign-detail-img" />
                        </div>

                        <div class="mb-4">
                            <h4 class="fw-bold">「自動車免許がタダで取れる？」そんな夢みたいな話が現実に！</h4>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold">コース情報</h5>
                            <div class="campaign-text-block">
                                <p class="campaign-text">「自動車免許が無料で取れたら良いな…」と思ったことはありませんか？でも、普通はそんなことはありえないですよね。ところが実は、現実にそんなチャンスがあるのです。方法は簡単――SBS自動車学校に入社するだけ！</p>

                                <p class="campaign-text">SBS自動車学校では、採用キャンペーンとして通常289,830円の教習費が<strong>完全無料</strong>になる特典を実施中です。この特典は、働きながら教習を進めることで実現しています。社員として活躍していただきながら、免許取得に必要な費用を<strong>全額負担</strong>する仕組みです。</p>

                                <p class="campaign-text">現在、入社後「自動車免許をタダで取れる」求人は、<strong>教習指導員候補</strong>と<strong>事務員</strong>です。免許を持っていない方でも、未経験から安心してスタートできる環境が整っています。</p>
                                <p class="campaign-text">さらに、働きやすい職場づくりのために、以下のような制度やサポートも充実しています。</p>

                                <p class="campaign-text bullet">副業OKでライフスタイルに合わせた働き方が可能</p>
                                <p class="campaign-text bullet">充実の研修制度で未経験者もスムーズにスタート</p>
                                <p class="campaign-text bullet">年齢も幅広く、女性職員・指導員も多数在籍</p>

                                <p class="campaign-text">「免許を取りたい」「働きながらスキルを身につけたい」と思っている方、<strong>今が絶好のチャンス</strong>です！</p>
                                <p class="campaign-text">詳しい情報や応募方法については、こちらからご確認ください。</p>

                                <p class="campaign-text text-muted small">※送迎バス運転手の募集については「免許無料取得特典」の対象外となりますので、あらかじめご了承ください。</p>
                            </div>

                            <div class="campaign-text-block mb-4">
                                <p class="campaign-text">皆さんこんにちは！</p>

                                <p class="campaign-text">久しぶりの投稿となってしまいました。</p>

                                <p class="campaign-text">この度、SBS自動車学校は敬愛学園女子バレーボール部のスポンサーに就任いたしました！</p>

                                <p class="campaign-text">敬愛学園は、千葉市稲毛区内の教習所から近い学校でもあり、同じ敷地の敬愛大学からも沢山の生徒さんが通ってくれていることもあり、お話を頂いた際には即決させていただきました。</p>

                                <p class="campaign-text">個人的にバレーボールが大好きという理由が一番大きいですが・・・</p>

                                <p class="campaign-text">そして、スポンサー契約をした直後にインターハイ出場することが決定し、社内でもお祭り騒ぎとなっております！</p>

                                <p class="campaign-text">しかも今回は、スポンサーとしてユニフォームへのロゴ入れをさせていただきました。</p>

                                <p class="campaign-text">壮行会に参加させていただき、そこで新ユニフォームのお披露目となりました。</p>
                                <p class="campaign-text">なんだか感慨深いですね。</p>
                                <p class="campaign-text">このユニフォームは、教習所内に展示しますので、お越しになった際には是非ご覧ください！</p>
                                <p class="campaign-text">インターハイは8月6日から始まります。</p>
                                <p class="campaign-text">会社をあげて応援します！</p>
                                <p class="campaign-text">1人のバレーボールファンとしても応援していきたいと思います！</p>
                                <p class="campaign-text">頑張れ！敬愛学園女子バレーボール部！！</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Sidebar -->

                <aside class="col-md-4 blog-detail-sidebar">
                    <div class="blog-posts-main-grid">
                        <?php
                        // Always render 3 mock posts in the sidebar (vertical list)
                        $mock_campaign_posts = sbs_get_latest_campaign_posts(4);

                        if (!empty($mock_campaign_posts)) :
                            foreach ($mock_campaign_posts as $mock_post) :
                                $post_data = $mock_post;
                        ?>
                                <div class="mb-4">
                                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/<?php echo $post_data['featured_image']; ?>" alt="<?php echo $post_data['title']; ?>" />
                                </div>
                            <?php endforeach;
                        else : ?>

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