<?php

/**
 * Campaign Detail Template
 * 
 * Custom single template for CPT `campaign` matching the blog list/header design
 *
 * @package SBS_Portal
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Ensure header is loaded so that wp_head() prints enqueued CSS/JS
get_header();
// Get post data from query parameters
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
$post_slug = isset($_GET['post_slug']) ? sanitize_text_field($_GET['post_slug']) : '';
$post_title_param = isset($_GET['post_title']) ? sanitize_text_field($_GET['post_title']) : '';

// Try to get the campaign post
$campaign_post = null;
if ($post_id > 0) {
    $campaign_post = get_post($post_id);
    if (!$campaign_post || $campaign_post->post_type !== 'campaign') {
        $campaign_post = null;
    }
}

if (!$campaign_post && !empty($post_slug)) {
    $campaign_post = get_page_by_path($post_slug, OBJECT, 'campaign');
}

if (!$campaign_post && !empty($post_title_param)) {
    $posts = get_posts(array(
        'post_type' => 'campaign',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'title' => $post_title_param,
    ));
    if (!empty($posts)) {
        $campaign_post = $posts[0];
    }
}

// Fallback to mock data if no post found
if (!$campaign_post) {
    $mock_campaign_posts = sbs_get_latest_campaign_posts(1);
    if (!empty($mock_campaign_posts)) {
        $mock_post = $mock_campaign_posts[0];
        $post_title = $mock_post['title'];
        $post_date = $mock_post['date'];
        $post_content = $mock_post['content'] ?? 'Campaign content not available.';
        $featured_image = get_template_directory_uri() . '/assets/images/' . $mock_post['featured_image'];
    } else {
        $post_title = 'Campaign Not Found';
        $post_date = date('Y-m-d');
        $post_content = 'This campaign could not be found.';
        $featured_image = get_template_directory_uri() . '/assets/images/campaign-detail.png';
    }
} else {
    $post_title = $campaign_post->post_title;
    $post_date = get_the_date('Y-m-d', $campaign_post->ID);
    $post_content = $campaign_post->post_content;
    $featured_image = has_post_thumbnail($campaign_post->ID) ?
        get_the_post_thumbnail_url($campaign_post->ID, 'large') :
        get_template_directory_uri() . '/assets/images/campaign-detail.png';
}

// Debug logging
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Campaign Detail - Post ID: ' . $post_id);
    error_log('Campaign Detail - Post Title: ' . $post_title);
    error_log('Campaign Detail - Campaign Post: ' . ($campaign_post ? 'Found' : 'Not Found'));
}
?>

<div class="sbs-blog-detail">
    <?php
    // Use reusable header section with campaign specific parameters
    get_template_part('parts/header-section', null, array(
        'title' => 'キャンペーン情報',
        'subtitle' => 'Campaign',
        'breadcrumb_items' => array('キャンペーン一覧', $post_title),
        'show_navigation' => true
    ));
    ?>

    <section class="blog-detail-content">
        <?php get_template_part('parts/breadcrumbs-section', null, array('breadcrumb_items' => array('キャンペーン一覧', $post_title))); ?>
        <div class="container mb-5">
            <div class="row g-4">
                <!-- Left: Main Article -->
                <div class="col-md-9">
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
                <aside class="col-md-3 blog-detail-sidebar">
                    <div class="blog-detail-related">
                        
                        <div class="related-posts-grid">
                            <?php
                            // Get related campaign posts
                            $related_campaigns = get_posts(array(
                                'post_type' => 'campaign',
                                'post_status' => 'publish',
                                'posts_per_page' => 3,
                                'post__not_in' => $campaign_post ? array($campaign_post->ID) : array(),
                                'orderby' => 'date',
                                'order' => 'DESC',
                            ));

                            if (!empty($related_campaigns)) {
                                foreach ($related_campaigns as $related_campaign) {
                                    $card_post = array(
                                        'id' => $related_campaign->ID,
                                        'title' => $related_campaign->post_title,
                                        'excerpt' => wp_trim_words($related_campaign->post_excerpt ?: $related_campaign->post_content, 20),
                                        'featured_image' => has_post_thumbnail($related_campaign->ID) ?
                                            get_the_post_thumbnail_url($related_campaign->ID, 'medium') :
                                            get_template_directory_uri() . '/assets/images/campaign-detail.png',
                                        'date' => get_the_date('Y-m-d', $related_campaign->ID),
                                        'category' => 'CAMPAIGN',
                                        'permalink' => add_query_arg('post_id', $related_campaign->ID, home_url('/campaign-detail/')),
                                    );
                            ?>
                                    <div class="mb-3">
                                        <?php get_template_part('parts/blog-card', null, array('post' => $card_post)); ?>
                                    </div>
                                <?php
                                }
                            } else {
                                // Fallback to mock data
                                $mock_campaign_posts = sbs_get_latest_campaign_posts(4);
                                foreach ($mock_campaign_posts as $mock_post) {
                                    $card_post = array(
                                        'id' => 0,
                                        'title' => $mock_post['title'],
                                        'excerpt' => wp_trim_words($mock_post['content'] ?? 'Campaign content', 20),
                                        'featured_image' => get_template_directory_uri() . '/assets/images/' . $mock_post['featured_image'],
                                        'date' => $mock_post['date'],
                                        'category' => 'CAMPAIGN',
                                        'permalink' => '#',
                                    );

                                    $permalink = !empty($card_post['permalink']) ? $card_post['permalink'] : '#';

                                    if (!empty($card_post['featured_image']) && filter_var($card_post['featured_image'], FILTER_VALIDATE_URL)) {
                                        $image_file = $card_post['featured_image'];
                                    } else {
                                        $image_file = get_template_directory_uri() . '/assets/images/' . (!empty($card_post['featured_image']) ? $card_post['featured_image'] : 'blog-featured-1.jpg');
                                    }
                                ?>
                                    <div class="mb-4">
                                        <a href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($card_post['title']); ?>">
                                            <img src="<?php echo esc_url($image_file); ?>" alt="<?php echo esc_attr($card_post['title']); ?>" class="img-fluid" />
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <?php get_template_part('parts/float-buttons'); ?>
</div>

<!-- Footer Background for Campaign Detail -->
<div class="footer-background blog-list-footer" style="background-image: url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/footer-bg.jpg');"></div>

<?php
// Ensure footer is loaded so that wp_footer() prints scripts and closing markup
get_footer();
?>