<?php
/**
 * Class RegisterPatternsTest
 *
 * @package vk-block-patterns
 */

class RegisterPatternsTest extends WP_UnitTestCase {

	private function build_expected_results( $favorite_patterns, $xt9_patterns, $xt9_enabled ) {
		$favorites = json_decode( $favorite_patterns, true );
		$xt9       = json_decode( $xt9_patterns, true );

		$favorite_count = is_array( $favorites ) ? count( $favorites ) : 0;
		$xt9_count      = ( $xt9_enabled && is_array( $xt9 ) ) ? count( $xt9 ) : 0;

		return array(
			'favorite' => array_fill( 0, $favorite_count, true ),
			'x-t9'     => array_fill( 0, $xt9_count, true ),
		);
	}

	public function get_test_data() {

        $favorite_patterns = '[
            {
                "post_name": "dental-clinic_facility",
                "title": "設備紹介",
                "categories": [
                    "vk-pattern-favorites"
                ],
                "content": "<!-- wp:vk-blocks/outer {\"bgColor\":\"#e5e5e5\",\"outerWidth\":\"full\",\"blockId\":\"9a446231-4fa8-4f04-b724-23b98e26fa43\",\"className\":\"vkb-outer-cb6a40b9-2afa-4ba4-bc9d-25f23e5db3be\"} -->\n<div class=\"wp-block-vk-blocks-outer vkb-outer-9a446231-4fa8-4f04-b724-23b98e26fa43 vk_outer vk_outer-width-full vk_outer-paddingLR-none vk_outer-paddingVertical-use vk_outer-bgPosition-normal vkb-outer-cb6a40b9-2afa-4ba4-bc9d-25f23e5db3be\"><span class=\"vk_outer-background-area has-background has-background-dim has-background-dim-5\" style=\"background-color:#e5e5e5\"></span><div><div class=\"vk_outer_container\"><!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022far fa-hospital\\u0022\\u003e\\u003c/i\\u003e\",\"iconMargin\":16,\"iconAlign\":\"center\",\"iconType\":\"2\",\"iconColor\":\"vk-color-primary\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon\"><div class=\"vk_icon_frame text-center is-style-noline\"><div class=\"vk_icon_border has-text-color has-vk-color-primary-color\" style=\"width:calc(36px + 32px);height:calc(36px + 32px)\"><i class=\"far vk_icon_font fa-hospital\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon -->\n\n<!-- wp:vk-blocks/heading {\"align\":\"center\",\"titleStyle\":\"plain\",\"outerMarginBottom\":2.5,\"titleMarginBottom\":0.5,\"subTextFlag\":\"on\",\"subTextColor\":\"vk-color-primary\",\"subTextSize\":1} -->\n<div class=\"wp-block-vk-blocks-heading\"><div class=\"vk_heading vk_heading-style-plain\" style=\"margin-bottom:2.5rem\"><h2 style=\"margin-bottom:0.5rem;text-align:center\" class=\"vk_heading_title vk_heading_title-style-plain\"><span>設備紹介</span></h2><p style=\"font-size:1rem;text-align:center\" class=\"vk_heading_subtext vk_heading_subtext-style-plain has-text-color has-vk-color-primary-color\">Facility</p></div></div>\n<!-- /wp:vk-blocks/heading -->\n\n<!-- wp:vk-blocks/grid-column {\"col_xs\":2,\"col_md\":2,\"col_lg\":4,\"col_xl\":4,\"col_xxl\":4} -->\n<div class=\"wp-block-vk-blocks-grid-column vk_gridColumn\"><div class=\"row\"><!-- wp:vk-blocks/grid-column-item {\"col_xs\":2,\"col_md\":2,\"col_lg\":4,\"col_xl\":4,\"col_xxl\":4,\"backgroundColor\":\"white\"} -->\n<div class=\"wp-block-vk-blocks-grid-column-item vk_gridColumn_item col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3\"><div class=\"vk_gridColumn_item_inner   has-background-color has-white-background-color\"><!-- wp:image {\"id\":680,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/02/home-hall-facade-lighting-interior-design-reception-994053-pxhere.com_-1024x720.jpg\" alt=\"\" class=\"wp-image-680\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\"} -->\n<p class=\"has-text-align-center\">外観</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:vk-blocks/grid-column-item -->\n\n<!-- wp:vk-blocks/grid-column-item {\"col_xs\":2,\"col_md\":2,\"col_lg\":4,\"col_xl\":4,\"col_xxl\":4,\"backgroundColor\":\"white\"} -->\n<div class=\"wp-block-vk-blocks-grid-column-item vk_gridColumn_item col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3\"><div class=\"vk_gridColumn_item_inner   has-background-color has-white-background-color\"><!-- wp:image {\"id\":691,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/02/america-american-application-care-caucasian-clinic-1444739-pxhere.com_-1024x720.jpg?v=1643858792\" alt=\"\" class=\"wp-image-691\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\"} -->\n<p class=\"has-text-align-center\">受付</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:vk-blocks/grid-column-item -->\n\n<!-- wp:vk-blocks/grid-column-item {\"col_xs\":2,\"col_md\":2,\"col_lg\":4,\"col_xl\":4,\"col_xxl\":4,\"backgroundColor\":\"white\"} -->\n<div class=\"wp-block-vk-blocks-grid-column-item vk_gridColumn_item col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3\"><div class=\"vk_gridColumn_item_inner   has-background-color has-white-background-color\"><!-- wp:image {\"id\":682,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/02/home-property-living-room-room-apartment-luggage-769856-pxhere.com_-1024x720.jpg\" alt=\"\" class=\"wp-image-682\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\"} -->\n<p class=\"has-text-align-center\">待合室</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:vk-blocks/grid-column-item -->\n\n<!-- wp:vk-blocks/grid-column-item {\"col_xs\":2,\"col_md\":2,\"col_lg\":4,\"col_xl\":4,\"col_xxl\":4,\"backgroundColor\":\"white\"} -->\n<div class=\"wp-block-vk-blocks-grid-column-item vk_gridColumn_item col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3\"><div class=\"vk_gridColumn_item_inner   has-background-color has-white-background-color\"><!-- wp:image {\"id\":684,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/02/chair-interior-furniture-dentist-medical-dentistry-1276386-pxhere.com_-1024x720.jpg\" alt=\"\" class=\"wp-image-684\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\"} -->\n<p class=\"has-text-align-center\">診療室</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:vk-blocks/grid-column-item -->\n\n<!-- wp:vk-blocks/grid-column-item {\"col_xs\":2,\"col_md\":2,\"col_lg\":4,\"col_xl\":4,\"col_xxl\":4,\"backgroundColor\":\"white\"} -->\n<div class=\"wp-block-vk-blocks-grid-column-item vk_gridColumn_item col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3\"><div class=\"vk_gridColumn_item_inner   has-background-color has-white-background-color\"><!-- wp:image {\"id\":690,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/02/floor-building-office-property-room-sofa-769854-pxhere.com_-1024x720.jpg?v=1643858743\" alt=\"\" class=\"wp-image-690\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\"} -->\n<p class=\"has-text-align-center\">手術室</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:vk-blocks/grid-column-item -->\n\n<!-- wp:vk-blocks/grid-column-item {\"col_xs\":2,\"col_md\":2,\"col_lg\":4,\"col_xl\":4,\"col_xxl\":4,\"backgroundColor\":\"white\"} -->\n<div class=\"wp-block-vk-blocks-grid-column-item vk_gridColumn_item col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3\"><div class=\"vk_gridColumn_item_inner   has-background-color has-white-background-color\"><!-- wp:image {\"id\":689,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/02/room-interior-design-bathroom-design-hospital-accommodation-633863-pxhere.com_-1024x720.jpg?v=1643858737\" alt=\"\" class=\"wp-image-689\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\"} -->\n<p class=\"has-text-align-center\">レントゲン室</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:vk-blocks/grid-column-item -->\n\n<!-- wp:vk-blocks/grid-column-item {\"col_xs\":2,\"col_md\":2,\"col_lg\":4,\"col_xl\":4,\"col_xxl\":4,\"backgroundColor\":\"white\"} -->\n<div class=\"wp-block-vk-blocks-grid-column-item vk_gridColumn_item col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3\"><div class=\"vk_gridColumn_item_inner   has-background-color has-white-background-color\"><!-- wp:image {\"id\":685,\"sizeSlug\":\"large\",\"linkDestination\":\"none\",\"className\":\"is-style-default\"} -->\n<figure class=\"wp-block-image size-large is-style-default\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/02/book-read-white-bear-blue-toy-844147-pxhere.com_-1024x720.jpg\" alt=\"\" class=\"wp-image-685\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\"} -->\n<p class=\"has-text-align-center\">キッズスペース</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:vk-blocks/grid-column-item -->\n\n<!-- wp:vk-blocks/grid-column-item {\"col_xs\":2,\"col_md\":2,\"col_lg\":4,\"col_xl\":4,\"col_xxl\":4,\"backgroundColor\":\"white\"} -->\n<div class=\"wp-block-vk-blocks-grid-column-item vk_gridColumn_item col-6 col-sm-6 col-md-6 col-lg-3 col-xl-3 col-xxl-3\"><div class=\"vk_gridColumn_item_inner   has-background-color has-white-background-color\"><!-- wp:image {\"id\":688,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/02/floor-cup-tablet-clean-toilet-sink-651404-pxhere.com_-1024x720.jpg?v=1643858728\" alt=\"\" class=\"wp-image-688\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:paragraph {\"align\":\"center\"} -->\n<p class=\"has-text-align-center\">洗面所</p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:vk-blocks/grid-column-item --></div></div>\n<!-- /wp:vk-blocks/grid-column --></div></div></div><style type=\"text/css\">\n\t.vkb-outer-9a446231-4fa8-4f04-b724-23b98e26fa43 .vk_outer_container{\n\t\tpadding-left:0px;\n\t\tpadding-right:0px;\n\t}\n\t@media (min-width: 576px) {\n\t\t.vkb-outer-9a446231-4fa8-4f04-b724-23b98e26fa43 .vk_outer_container{\n\t\t\tpadding-left:0px;\n\t\t\tpadding-right:0px;\n\t\t}\n\t}\n\t@media (min-width: 992px) {\n\t\t.vkb-outer-9a446231-4fa8-4f04-b724-23b98e26fa43 .vk_outer_container{\n\t\t\tpadding-left:0px;\n\t\t\tpadding-right:0px;\n\t\t}\n\t}\n\t</style>\n<!-- /wp:vk-blocks/outer -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->"
            },
            {
                "post_name": "service-free",
                "title": "サービス案内",
                "categories": [
                    "vk-pattern-favorites"
                ],
                "content": "<!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:heading {\"textAlign\":\"center\",\"className\":\"is-style-vk-heading-plain\"} -->\n<h2 class=\"has-text-align-center is-style-vk-heading-plain\" id=\"vk-htags-923f1996-3424-4ccd-9381-4d7078abb57f\">徹底した顧客目線でお客様の悩みを解決いたします</h2>\n<!-- /wp:heading -->\n\n<!-- wp:vk-blocks/spacer -->\n<div class=\"wp-block-vk-blocks-spacer vk_spacer vk_spacer-type-margin-top\"><div class=\"vk_block-margin-md--margin-top\"></div></div>\n<!-- /wp:vk-blocks/spacer -->\n\n<!-- wp:spacer {\"height\":\"\",\"className\":\"is-style-spacer-lg\"} -->\n<div style=\"height:\" aria-hidden=\"true\" class=\"wp-block-spacer is-style-spacer-lg\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:paragraph -->\n<p>株式会社サンプルでは、当たり前を当たり前だと思わない、常識を常識だと思わない精神で徹底して顧客目線にたって商品開発を行っております。</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>これにより、物事の本質を見据えた斬新なアイディアで開発をされたソリューションをお客様にご提供することが可能となっております。</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>お客様からは、「最初は戸惑ったものの、今までにない体感でもう手放すことが出来ない」といったお声を最も多くいただいております。</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>これに甘えること無く、常に常識を疑ってお客様の求めるものを追求する姿勢で商品開発に取り組んでいきます。</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:vk-blocks/spacer -->\n<div class=\"wp-block-vk-blocks-spacer vk_spacer vk_spacer-type-margin-top\"><div class=\"vk_block-margin-md--margin-top\"></div></div>\n<!-- /wp:vk-blocks/spacer -->\n\n<!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":3922,\"sizeSlug\":\"large\",\"linkDestination\":\"none\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top vk_block-margin-sm\\u002d\\u002dmargin-bottom is-style-vk-image-border\"} -->\n<figure class=\"wp-block-image size-large vk_block-margin-0--margin-top vk_block-margin-sm--margin-bottom is-style-vk-image-border\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/07/pr_1-1024x410.jpg\" alt=\"\" class=\"wp-image-3922\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":4,\"className\":\"mb-0 is-style-vk-heading-plain\"} -->\n<h4 class=\"has-text-align-center mb-0 is-style-vk-heading-plain\">ホームページ制作</h4>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"align\":\"center\",\"textColor\":\"vk-color-primary\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\",\"fontSize\":\"small\"} -->\n<p class=\"has-text-align-center vk_block-margin-0--margin-top has-vk-color-primary-color has-text-color has-small-font-size\">Web</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:list {\"className\":\"is-style-vk-check-mark vk-has-vivid-red-color\",\"color\":\"#cf2e2e\"} -->\n<ul class=\"is-style-vk-check-mark vk-has-vivid-red-color\"><li>企画 / 構成 </li><li>ウェブサイト制作</li><li>ランディングページ制作</li></ul>\n<!-- /wp:list --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":3923,\"sizeSlug\":\"large\",\"linkDestination\":\"none\",\"className\":\"is-style-vk-image-border vk_block-margin-0\\u002d\\u002dmargin-top vk_block-margin-sm\\u002d\\u002dmargin-bottom\"} -->\n<figure class=\"wp-block-image size-large is-style-vk-image-border vk_block-margin-0--margin-top vk_block-margin-sm--margin-bottom\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/07/pr_2-1024x410.jpg\" alt=\"\" class=\"wp-image-3923\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":4,\"className\":\"mb-0 is-style-vk-heading-plain\"} -->\n<h4 class=\"has-text-align-center mb-0 is-style-vk-heading-plain\">印刷物</h4>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"align\":\"center\",\"textColor\":\"vk-color-primary\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\",\"fontSize\":\"small\"} -->\n<p class=\"has-text-align-center vk_block-margin-0--margin-top has-vk-color-primary-color has-text-color has-small-font-size\">Printing</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:list {\"className\":\"is-style-vk-check-mark vk-has-vivid-red-color\",\"color\":\"#cf2e2e\"} -->\n<ul class=\"is-style-vk-check-mark vk-has-vivid-red-color\"><li>チラシ / ポスター</li><li>名刺 / 各種カード</li><li>パンフレット</li></ul>\n<!-- /wp:list --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:image {\"id\":3922,\"sizeSlug\":\"large\",\"linkDestination\":\"none\",\"className\":\"is-style-vk-image-border vk_block-margin-0\\u002d\\u002dmargin-top vk_block-margin-sm\\u002d\\u002dmargin-bottom\"} -->\n<figure class=\"wp-block-image size-large is-style-vk-image-border vk_block-margin-0--margin-top vk_block-margin-sm--margin-bottom\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/07/pr_1-1024x410.jpg\" alt=\"\" class=\"wp-image-3922\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":4,\"className\":\"mb-0 is-style-vk-heading-plain\"} -->\n<h4 class=\"has-text-align-center mb-0 is-style-vk-heading-plain\">システム開発</h4>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"align\":\"center\",\"textColor\":\"vk-color-primary\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\",\"fontSize\":\"small\"} -->\n<p class=\"has-text-align-center vk_block-margin-0--margin-top has-vk-color-primary-color has-text-color has-small-font-size\">System</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:list {\"className\":\"is-style-vk-check-mark vk-has-vivid-red-color\",\"color\":\"#cf2e2e\"} -->\n<ul class=\"is-style-vk-check-mark vk-has-vivid-red-color\"><li>ウェブシステム開発</li><li>各種CMS実装</li><li>業務システム開発</li></ul>\n<!-- /wp:list --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:vk-blocks/spacer {\"spaceSize\":\"large\"} -->\n<div class=\"wp-block-vk-blocks-spacer vk_spacer vk_spacer-type-margin-top\"><div class=\"vk_block-margin-lg--margin-top\"></div></div>\n<!-- /wp:vk-blocks/spacer --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/child-page-index {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} /-->"
            }
        ]';

        $xt9_patterns = '[
            {
                "post_name": "about-us-free-001",
                "title": "会社案内",
                "categories": [
                    "x-t9"
                ],
                "content": "<!-- wp:columns {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom\"} -->\n<div class=\"wp-block-columns vk_block-margin-0--margin-bottom\"><!-- wp:column {\"width\":\"66.66%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:66.66%\"><!-- wp:heading {\"textAlign\":\"left\",\"className\":\"is-style-vk-heading-plain\"} -->\n<h2 class=\"has-text-align-left is-style-vk-heading-plain\">代表挨拶</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"style\":{\"typography\":{\"lineHeight\":\"2\"}}} -->\n<p style=\"line-height:2\">この部分には、代表からのメッセージが入ります。<br>自社のコンセプトや理念など、大切にしていることについて説明しましょう。顧客が商品・サービスを選択するにあたって事業者の姿勢はとても重要です。創業社長であれば、ほとんどの場合その事業に対する思いがあると思います。なぜその事業をはじめたのか、会社としてどういう形で社会貢献していくのかなどを飾らず記載すればストレートの顧客に伝わります。</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph {\"style\":{\"typography\":{\"lineHeight\":\"2\"}}} -->\n<p style=\"line-height:2\">反対になんとなく事業を引き継いで代表になっただけという場合や、仕事の内容にこだわりはないけれども儲かりそうだったのではじめただけで、現在もなおその状態で何も思いつかない場合は、ライターの人に有償でお願いするのが無難です。</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph {\"textColor\":\"black\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom\"} -->\n<p class=\"vk_block-margin-0--margin-bottom has-black-color has-text-color\">株式会社サンプル<br>代表取締役社長　山田太朗</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"verticalAlignment\":\"top\",\"width\":\"33.33%\"} -->\n<div class=\"wp-block-column is-vertically-aligned-top\" style=\"flex-basis:33.33%\"><!-- wp:image {\"align\":\"center\",\"id\":1497,\"sizeSlug\":\"full\",\"linkDestination\":\"media\",\"className\":\"is-style-default vk_block-margin-0\\u002d\\u002dmargin-bottom\"} -->\n<figure class=\"wp-block-image aligncenter size-full is-style-default vk_block-margin-0--margin-bottom\"><a href=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/03/dummy-human-square.png\"><img src=\"https://patterns.vektor-inc.co.jp/wp-content/uploads/2022/03/dummy-human-square.png\" alt=\"\" class=\"wp-image-1497\"/></a><figcaption>山田太郎</figcaption></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:vk-blocks/spacer {\"spaceSize\":\"large\"} -->\n<div class=\"wp-block-vk-blocks-spacer vk_spacer vk_spacer-type-margin-top\"><div class=\"vk_block-margin-lg--margin-top\"></div></div>\n<!-- /wp:vk-blocks/spacer -->\n\n<!-- wp:separator {\"backgroundColor\":\"border-normal\",\"className\":\"is-style-wide\"} -->\n<hr class=\"wp-block-separator has-text-color has-border-normal-color has-alpha-channel-opacity has-border-normal-background-color has-background is-style-wide\"/>\n<!-- /wp:separator -->\n\n<!-- wp:vk-blocks/spacer {\"spaceSize\":\"large\"} -->\n<div class=\"wp-block-vk-blocks-spacer vk_spacer vk_spacer-type-margin-top\"><div class=\"vk_block-margin-lg--margin-top\"></div></div>\n<!-- /wp:vk-blocks/spacer -->\n\n<!-- wp:heading {\"textAlign\":\"center\",\"className\":\"is-style-vk-heading-plain\"} -->\n<h2 class=\"has-text-align-center is-style-vk-heading-plain\"> 会社概要</h2>\n<!-- /wp:heading -->\n\n<!-- wp:table {\"borderColor\":\"border-normal\",\"className\":\"vk-table\\u002d\\u002dmobile-block vk-table\\u002d\\u002dth\\u002d\\u002dwidth25 vk-table\\u002d\\u002dth\\u002d\\u002dbg-bright is-style-vk-table-border-top-bottom\",\"fontSize\":\"small\"} -->\n<figure class=\"wp-block-table vk-table--mobile-block vk-table--th--width25 vk-table--th--bg-bright is-style-vk-table-border-top-bottom has-small-font-size\"><table class=\"has-border-normal-border-color has-border-color\"><tbody><tr><td>会社名</td><td>株式会社サンプル</td></tr><tr><td>英文社名</td><td>Sample Co. Ltd.</td></tr><tr><td>代表取締役</td><td>山田次郎</td></tr><tr><td>従業員数</td><td>100名</td></tr><tr><td>所在地</td><td>愛知県名古屋市中村区名駅X-XX-X　サンプルビル</td></tr><tr><td>TEL</td><td>000-000-0000</td></tr><tr><td>事業内容</td><td>・ウェブサイト企画・制作<br>・システム開発<br>・印刷物デザイン・制作<br>・動画撮影・制作<br>・その他販促サービス</td></tr></tbody></table></figure>\n<!-- /wp:table -->\n\n<!-- wp:vk-blocks/spacer {\"spaceSize\":\"large\"} -->\n<div class=\"wp-block-vk-blocks-spacer vk_spacer vk_spacer-type-margin-top\"><div class=\"vk_block-margin-lg--margin-top\"></div></div>\n<!-- /wp:vk-blocks/spacer -->\n\n<!-- wp:heading {\"textAlign\":\"center\",\"className\":\"vk_block-margin-md\\u002d\\u002dmargin-bottom is-style-vk-heading-plain vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<h2 class=\"has-text-align-center vk_block-margin-md--margin-bottom is-style-vk-heading-plain vk_block-margin-0--margin-top\">アクセス</h2>\n<!-- /wp:heading -->\n\n<!-- wp:columns {\"verticalAlignment\":\"center\",\"className\":\"vk-cols\\u002d\\u002dreverse vk-cols\\u002d\\u002dfit vk-cols\\u002d\\u002dgrid\"} -->\n<div class=\"wp-block-columns are-vertically-aligned-center vk-cols--reverse vk-cols--fit vk-cols--grid\"><!-- wp:column {\"verticalAlignment\":\"center\",\"style\":{\"spacing\":{\"padding\":{\"top\":\"2em\",\"right\":\"0em\",\"bottom\":\"2em\",\"left\":\"2em\"}}}} -->\n<div class=\"wp-block-column is-vertically-aligned-center\" style=\"padding-top:2em;padding-right:0em;padding-bottom:2em;padding-left:2em\"><!-- wp:list {\"className\":\"is-style-vk-check-square-mark               vk_block-margin-0\\u002d\\u002dmargin-top\",\"color\":\"#000000\"} -->\n<ul class=\"is-style-vk-check-square-mark vk_block-margin-0--margin-top\"><li>所在地<br>愛知県名古屋市<br>中村区名駅X-XX-X サンプルビル</li><li>電車でお越しの場合<br>名古屋駅下車 <br>JRツインタワーズのロータリーの交差点を南に徒歩５分</li><li>営業時間<br>平日9:00～18:00 / 土日祝定休</li></ul>\n<!-- /wp:list -->\n\n<!-- wp:spacer {\"className\":\"is-style-spacer-md\"} -->\n<div style=\"height:100px\" aria-hidden=\"true\" class=\"wp-block-spacer is-style-spacer-md\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:vk-blocks/button {\"subCaption\":\"ご来社の場合はお電話でお問い合わせください\",\"buttonUrl\":\"#\",\"buttonAlign\":\"block\",\"fontAwesomeIconBefore\":\"\\u003ci class=\\u0022fas fa-mobile-alt\\u0022\\u003e\\u003c/i\\u003e\",\"blockId\":\"eab9fb27-e3b9-4f61-92f0-196a963bfa07\"} -->\n<div class=\"wp-block-vk-blocks-button vk_button vk_button-color-custom vk_button-align-block\"><a href=\"#\" class=\"vk_button_link btn has-background has-vk-color-primary-background-color btn-md btn-block\" role=\"button\" aria-pressed=\"true\" rel=\"noopener\"><div class=\"vk_button_link_caption\"><i class=\"fas fa-mobile-alt vk_button_link_before\"></i><span class=\"vk_button_link_txt\">000-000-0000</span></div><p class=\"vk_button_link_subCaption\">ご来社の場合はお電話でお問い合わせください</p></a></div>\n<!-- /wp:vk-blocks/button --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"verticalAlignment\":\"center\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\"><!-- wp:html -->\n<iframe style=\"border: 0;\" src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d26091.476183777333!2d136.881666!3d35.170721!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x600376e794d78b89%3A0x81f7204bf8261663!2z5ZCN5Y-k5bGL6aeF!5e0!3m2!1sja!2sjp!4v1445077914032\" width=\"750\" height=\"400\" frameborder=\"0\" allowfullscreen=\"allowfullscreen\"></iframe>\n<!-- /wp:html --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:vk-blocks/spacer {\"unit\":\"rem\",\"pc\":4,\"tablet\":4,\"mobile\":2,\"spaceSize\":\"custom\"} -->\n<div class=\"wp-block-vk-blocks-spacer vk_spacer\"><div class=\"vk_spacer-display-pc\" style=\"margin-top:4rem\"></div><div class=\"vk_spacer-display-tablet\" style=\"margin-top:4rem\"></div><div class=\"vk_spacer-display-mobile\" style=\"margin-top:2rem\"></div></div>\n<!-- /wp:vk-blocks/spacer -->\n\n<!-- wp:vk-blocks/child-page-index /-->"
            },
            {
                "post_name": "bt-flow-001",
                "title": "サービスの流れ",
                "categories": [
                    "x-t9"
                ],
                "content": "<!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"2rem\",\"right\":\"2rem\",\"bottom\":\"2rem\",\"left\":\"2rem\"}},\"border\":{\"style\":\"solid\",\"width\":\"1px\"}},\"borderColor\":\"border-normal\",\"backgroundColor\":\"bg-light-gray\"} -->\n<div class=\"wp-block-group has-border-color has-border-normal-border-color has-bg-light-gray-background-color has-background\" style=\"border-style:solid;border-width:1px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem\"><!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"1rem\",\"right\":\"1.5rem\",\"bottom\":\"1rem\",\"left\":\"1.5rem\"}},\"border\":{\"style\":\"solid\",\"width\":\"1px\"}},\"borderColor\":\"border-normal\",\"backgroundColor\":\"white\",\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\",\"justifyContent\":\"space-between\"},\"color\":\"#233A73\"} -->\n<div class=\"wp-block-group has-border-color has-border-normal-border-color has-white-background-color has-background\" style=\"border-style:solid;border-width:1px;padding-top:1rem;padding-right:1.5rem;padding-bottom:1rem;padding-left:1.5rem\"><!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:group {\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\"}} -->\n<div class=\"wp-block-group\"><!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"right\":\"0.5rem\",\"left\":\"0.5rem\",\"top\":\"0px\",\"bottom\":\"0px\"}},\"border\":{\"radius\":\"1px\"}},\"backgroundColor\":\"primary\",\"textColor\":\"text-normal-darkbg\"} -->\n<div class=\"wp-block-group has-text-normal-darkbg-color has-primary-background-color has-text-color has-background\" style=\"border-radius:1px;padding-top:0px;padding-right:0.5rem;padding-bottom:0px;padding-left:0.5rem\"><!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">1</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:heading {\"level\":4,\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom is-style-vk-heading-plain\"} -->\n<h4 class=\"vk_block-margin-0--margin-bottom is-style-vk-heading-plain\">お問い合わせ</h4>\n<!-- /wp:heading --></div>\n<!-- /wp:group -->\n\n<!-- wp:spacer {\"height\":\"5px\"} -->\n<div style=\"height:5px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">まずはお問い合わせフォームよりご連絡ください。</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fas fa-mobile-alt\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":42,\"iconMargin\":18,\"iconRadius\":0,\"iconType\":\"2\",\"iconColor\":\"primary\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon vk_block-margin-0--margin-bottom\"><div class=\"vk_icon_frame is-style-noline\"><div class=\"vk_icon_border has-text-color has-primary-color\" style=\"width:calc(42px + 36px);height:calc(42px + 36px);border-radius:0%\"><i style=\"font-size:42px\" class=\"fas vk_icon_font fa-mobile-alt\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-solid fa-angle-down\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":32,\"iconMargin\":5,\"iconAlign\":\"center\",\"iconType\":\"2\",\"iconColor\":\"border-normal\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon vk_block-margin-0--margin-bottom\"><div class=\"vk_icon_frame text-center is-style-noline\"><div class=\"vk_icon_border has-text-color has-border-normal-color\" style=\"width:calc(32px + 10px);height:calc(32px + 10px)\"><i style=\"font-size:32px\" class=\"fa-solid vk_icon_font fa-angle-down\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon -->\n\n<!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"1rem\",\"right\":\"1.5rem\",\"bottom\":\"1rem\",\"left\":\"1.5rem\"}},\"border\":{\"style\":\"solid\",\"width\":\"1px\"}},\"borderColor\":\"border-normal\",\"backgroundColor\":\"white\",\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\",\"justifyContent\":\"space-between\"},\"color\":\"#233A73\"} -->\n<div class=\"wp-block-group has-border-color has-border-normal-border-color has-white-background-color has-background\" style=\"border-style:solid;border-width:1px;padding-top:1rem;padding-right:1.5rem;padding-bottom:1rem;padding-left:1.5rem\"><!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:group {\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\"}} -->\n<div class=\"wp-block-group\"><!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"right\":\"0.5rem\",\"left\":\"0.5rem\",\"top\":\"0px\",\"bottom\":\"0px\"}},\"border\":{\"radius\":\"1px\"}},\"backgroundColor\":\"primary\",\"textColor\":\"text-normal-darkbg\"} -->\n<div class=\"wp-block-group has-text-normal-darkbg-color has-primary-background-color has-text-color has-background\" style=\"border-radius:1px;padding-top:0px;padding-right:0.5rem;padding-bottom:0px;padding-left:0.5rem\"><!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">2</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:heading {\"level\":4,\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom is-style-vk-heading-plain\"} -->\n<h4 class=\"vk_block-margin-0--margin-bottom is-style-vk-heading-plain\">ヒアリング</h4>\n<!-- /wp:heading --></div>\n<!-- /wp:group -->\n\n<!-- wp:spacer {\"height\":\"5px\"} -->\n<div style=\"height:5px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">担当者よりご連絡させていただき、現状の確認やお客様のご要望などをお伺いいたします。</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-regular fa-comments\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":42,\"iconMargin\":18,\"iconRadius\":0,\"iconType\":\"2\",\"iconColor\":\"primary\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon\"><div class=\"vk_icon_frame is-style-noline\"><div class=\"vk_icon_border has-text-color has-primary-color\" style=\"width:calc(42px + 36px);height:calc(42px + 36px);border-radius:0%\"><i style=\"font-size:42px\" class=\"fa-regular vk_icon_font fa-comments\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-solid fa-angle-down\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":32,\"iconMargin\":5,\"iconAlign\":\"center\",\"iconType\":\"2\",\"iconColor\":\"border-normal\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon vk_block-margin-0--margin-bottom\"><div class=\"vk_icon_frame text-center is-style-noline\"><div class=\"vk_icon_border has-text-color has-border-normal-color\" style=\"width:calc(32px + 10px);height:calc(32px + 10px)\"><i style=\"font-size:32px\" class=\"fa-solid vk_icon_font fa-angle-down\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon -->\n\n<!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"1rem\",\"right\":\"1.5rem\",\"bottom\":\"1rem\",\"left\":\"1.5rem\"}},\"border\":{\"style\":\"solid\",\"width\":\"1px\"}},\"borderColor\":\"border-normal\",\"backgroundColor\":\"white\",\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\",\"justifyContent\":\"space-between\"},\"color\":\"#233A73\"} -->\n<div class=\"wp-block-group has-border-color has-border-normal-border-color has-white-background-color has-background\" style=\"border-style:solid;border-width:1px;padding-top:1rem;padding-right:1.5rem;padding-bottom:1rem;padding-left:1.5rem\"><!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:group {\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\"}} -->\n<div class=\"wp-block-group\"><!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"right\":\"0.5rem\",\"left\":\"0.5rem\",\"top\":\"0px\",\"bottom\":\"0px\"}},\"border\":{\"radius\":\"1px\"}},\"backgroundColor\":\"primary\",\"textColor\":\"text-normal-darkbg\"} -->\n<div class=\"wp-block-group has-text-normal-darkbg-color has-primary-background-color has-text-color has-background\" style=\"border-radius:1px;padding-top:0px;padding-right:0.5rem;padding-bottom:0px;padding-left:0.5rem\"><!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">3</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:heading {\"level\":4,\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom is-style-vk-heading-plain\"} -->\n<h4 class=\"vk_block-margin-0--margin-bottom is-style-vk-heading-plain\">ご提案・お見積り </h4>\n<!-- /wp:heading --></div>\n<!-- /wp:group -->\n\n<!-- wp:spacer {\"height\":\"5px\"} -->\n<div style=\"height:5px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">ヒアリングした内容を元にお客様にベストなプランとお見積りをご提案させていただきます。</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-regular fa-file-lines\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":42,\"iconMargin\":18,\"iconRadius\":0,\"iconType\":\"2\",\"iconColor\":\"primary\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon\"><div class=\"vk_icon_frame is-style-noline\"><div class=\"vk_icon_border has-text-color has-primary-color\" style=\"width:calc(42px + 36px);height:calc(42px + 36px);border-radius:0%\"><i style=\"font-size:42px\" class=\"fa-regular vk_icon_font fa-file-lines\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-solid fa-angle-down\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":32,\"iconMargin\":5,\"iconAlign\":\"center\",\"iconType\":\"2\",\"iconColor\":\"border-normal\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon vk_block-margin-0--margin-bottom\"><div class=\"vk_icon_frame text-center is-style-noline\"><div class=\"vk_icon_border has-text-color has-border-normal-color\" style=\"width:calc(32px + 10px);height:calc(32px + 10px)\"><i style=\"font-size:32px\" class=\"fa-solid vk_icon_font fa-angle-down\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon -->\n\n<!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"1rem\",\"right\":\"1.5rem\",\"bottom\":\"1rem\",\"left\":\"1.5rem\"}},\"border\":{\"style\":\"solid\",\"width\":\"1px\"}},\"borderColor\":\"border-normal\",\"backgroundColor\":\"white\",\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\",\"justifyContent\":\"space-between\"},\"color\":\"#233A73\"} -->\n<div class=\"wp-block-group has-border-color has-border-normal-border-color has-white-background-color has-background\" style=\"border-style:solid;border-width:1px;padding-top:1rem;padding-right:1.5rem;padding-bottom:1rem;padding-left:1.5rem\"><!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:group {\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\"}} -->\n<div class=\"wp-block-group\"><!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"right\":\"0.5rem\",\"left\":\"0.5rem\",\"top\":\"0px\",\"bottom\":\"0px\"}},\"border\":{\"radius\":\"1px\"}},\"backgroundColor\":\"primary\",\"textColor\":\"text-normal-darkbg\"} -->\n<div class=\"wp-block-group has-text-normal-darkbg-color has-primary-background-color has-text-color has-background\" style=\"border-radius:1px;padding-top:0px;padding-right:0.5rem;padding-bottom:0px;padding-left:0.5rem\"><!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">4</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:heading {\"level\":4,\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom is-style-vk-heading-plain\"} -->\n<h4 class=\"vk_block-margin-0--margin-bottom is-style-vk-heading-plain\">ご契約・発注</h4>\n<!-- /wp:heading --></div>\n<!-- /wp:group -->\n\n<!-- wp:spacer {\"height\":\"5px\"} -->\n<div style=\"height:5px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">秘密保持契約など、発注に際して必要な契約をいたします。 </p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-solid fa-pen-fancy\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":42,\"iconMargin\":18,\"iconRadius\":0,\"iconType\":\"2\",\"iconColor\":\"primary\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon\"><div class=\"vk_icon_frame is-style-noline\"><div class=\"vk_icon_border has-text-color has-primary-color\" style=\"width:calc(42px + 36px);height:calc(42px + 36px);border-radius:0%\"><i style=\"font-size:42px\" class=\"fa-solid vk_icon_font fa-pen-fancy\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-solid fa-angle-down\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":32,\"iconMargin\":5,\"iconAlign\":\"center\",\"iconType\":\"2\",\"iconColor\":\"border-normal\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon vk_block-margin-0--margin-bottom\"><div class=\"vk_icon_frame text-center is-style-noline\"><div class=\"vk_icon_border has-text-color has-border-normal-color\" style=\"width:calc(32px + 10px);height:calc(32px + 10px)\"><i style=\"font-size:32px\" class=\"fa-solid vk_icon_font fa-angle-down\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon -->\n\n<!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"1rem\",\"right\":\"1.5rem\",\"bottom\":\"1rem\",\"left\":\"1.5rem\"}},\"border\":{\"style\":\"solid\",\"width\":\"1px\"}},\"borderColor\":\"border-normal\",\"backgroundColor\":\"white\",\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\",\"justifyContent\":\"space-between\"},\"color\":\"#233A73\"} -->\n<div class=\"wp-block-group has-border-color has-border-normal-border-color has-white-background-color has-background\" style=\"border-style:solid;border-width:1px;padding-top:1rem;padding-right:1.5rem;padding-bottom:1rem;padding-left:1.5rem\"><!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:group {\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\"}} -->\n<div class=\"wp-block-group\"><!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"right\":\"0.5rem\",\"left\":\"0.5rem\",\"top\":\"0px\",\"bottom\":\"0px\"}},\"border\":{\"radius\":\"1px\"}},\"backgroundColor\":\"primary\",\"textColor\":\"text-normal-darkbg\"} -->\n<div class=\"wp-block-group has-text-normal-darkbg-color has-primary-background-color has-text-color has-background\" style=\"border-radius:1px;padding-top:0px;padding-right:0.5rem;padding-bottom:0px;padding-left:0.5rem\"><!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">5</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:heading {\"level\":4,\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom is-style-vk-heading-plain\"} -->\n<h4 class=\"vk_block-margin-0--margin-bottom is-style-vk-heading-plain\">サービスのご提供</h4>\n<!-- /wp:heading --></div>\n<!-- /wp:group -->\n\n<!-- wp:spacer {\"height\":\"5px\"} -->\n<div style=\"height:5px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">ご提案させていただいた内容にて業務を実施いたします。</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-solid fa-laptop-code\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":42,\"iconMargin\":18,\"iconRadius\":0,\"iconType\":\"2\",\"iconColor\":\"primary\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon\"><div class=\"vk_icon_frame is-style-noline\"><div class=\"vk_icon_border has-text-color has-primary-color\" style=\"width:calc(42px + 36px);height:calc(42px + 36px);border-radius:0%\"><i style=\"font-size:42px\" class=\"fa-solid vk_icon_font fa-laptop-code\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-solid fa-angle-down\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":32,\"iconMargin\":5,\"iconAlign\":\"center\",\"iconType\":\"2\",\"iconColor\":\"border-normal\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon vk_block-margin-0--margin-bottom\"><div class=\"vk_icon_frame text-center is-style-noline\"><div class=\"vk_icon_border has-text-color has-border-normal-color\" style=\"width:calc(32px + 10px);height:calc(32px + 10px)\"><i style=\"font-size:32px\" class=\"fa-solid vk_icon_font fa-angle-down\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon -->\n\n<!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"1rem\",\"right\":\"1.5rem\",\"bottom\":\"1rem\",\"left\":\"1.5rem\"}},\"border\":{\"style\":\"solid\",\"width\":\"1px\"}},\"borderColor\":\"border-normal\",\"backgroundColor\":\"white\",\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\",\"justifyContent\":\"space-between\"},\"color\":\"#233A73\"} -->\n<div class=\"wp-block-group has-border-color has-border-normal-border-color has-white-background-color has-background\" style=\"border-style:solid;border-width:1px;padding-top:1rem;padding-right:1.5rem;padding-bottom:1rem;padding-left:1.5rem\"><!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:group {\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\"}} -->\n<div class=\"wp-block-group\"><!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"right\":\"0.5rem\",\"left\":\"0.5rem\",\"top\":\"0px\",\"bottom\":\"0px\"}},\"border\":{\"radius\":\"1px\"}},\"backgroundColor\":\"primary\",\"textColor\":\"text-normal-darkbg\"} -->\n<div class=\"wp-block-group has-text-normal-darkbg-color has-primary-background-color has-text-color has-background\" style=\"border-radius:1px;padding-top:0px;padding-right:0.5rem;padding-bottom:0px;padding-left:0.5rem\"><!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">6</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:heading {\"level\":4,\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom is-style-vk-heading-plain\"} -->\n<h4 class=\"vk_block-margin-0--margin-bottom is-style-vk-heading-plain\">確認・納品 </h4>\n<!-- /wp:heading --></div>\n<!-- /wp:group -->\n\n<!-- wp:spacer {\"height\":\"5px\"} -->\n<div style=\"height:5px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">成果物に対して、ご確認いただきます。必要に応じて修正を行い、納品となります。</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-solid fa-list-check\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":42,\"iconMargin\":18,\"iconRadius\":0,\"iconType\":\"2\",\"iconColor\":\"primary\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon\"><div class=\"vk_icon_frame is-style-noline\"><div class=\"vk_icon_border has-text-color has-primary-color\" style=\"width:calc(42px + 36px);height:calc(42px + 36px);border-radius:0%\"><i style=\"font-size:42px\" class=\"fa-solid vk_icon_font fa-list-check\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-solid fa-angle-down\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":32,\"iconMargin\":5,\"iconAlign\":\"center\",\"iconType\":\"2\",\"iconColor\":\"border-normal\",\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon vk_block-margin-0--margin-bottom\"><div class=\"vk_icon_frame text-center is-style-noline\"><div class=\"vk_icon_border has-text-color has-border-normal-color\" style=\"width:calc(32px + 10px);height:calc(32px + 10px)\"><i style=\"font-size:32px\" class=\"fa-solid vk_icon_font fa-angle-down\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon -->\n\n<!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"1rem\",\"right\":\"1.5rem\",\"bottom\":\"1rem\",\"left\":\"1.5rem\"}},\"border\":{\"style\":\"solid\",\"width\":\"1px\"}},\"borderColor\":\"border-normal\",\"backgroundColor\":\"white\",\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\",\"justifyContent\":\"space-between\"},\"color\":\"#233A73\"} -->\n<div class=\"wp-block-group has-border-color has-border-normal-border-color has-white-background-color has-background\" style=\"border-style:solid;border-width:1px;padding-top:1rem;padding-right:1.5rem;padding-bottom:1rem;padding-left:1.5rem\"><!-- wp:group -->\n<div class=\"wp-block-group\"><!-- wp:group {\"layout\":{\"type\":\"flex\",\"flexWrap\":\"nowrap\"}} -->\n<div class=\"wp-block-group\"><!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"right\":\"0.5rem\",\"left\":\"0.5rem\",\"top\":\"0px\",\"bottom\":\"0px\"}},\"border\":{\"radius\":\"1px\"}},\"backgroundColor\":\"primary\",\"textColor\":\"text-normal-darkbg\"} -->\n<div class=\"wp-block-group has-text-normal-darkbg-color has-primary-background-color has-text-color has-background\" style=\"border-radius:1px;padding-top:0px;padding-right:0.5rem;padding-bottom:0px;padding-left:0.5rem\"><!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">7</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:heading {\"level\":4,\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-bottom is-style-vk-heading-plain\"} -->\n<h4 class=\"vk_block-margin-0--margin-bottom is-style-vk-heading-plain\">ご入金 </h4>\n<!-- /wp:heading --></div>\n<!-- /wp:group -->\n\n<!-- wp:spacer {\"height\":\"5px\"} -->\n<div style=\"height:5px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:paragraph {\"className\":\"vk_block-margin-0\\u002d\\u002dmargin-top\"} -->\n<p class=\"vk_block-margin-0--margin-top\">納品月の末締めで請求書を発行させていただきますので、翌月末にてご入金願います。</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:vk-blocks/icon {\"faIcon\":\"\\u003ci class=\\u0022fa-solid fa-yen-sign\\u0022\\u003e\\u003c/i\\u003e\",\"iconSize\":42,\"iconMargin\":18,\"iconRadius\":0,\"iconType\":\"2\",\"iconColor\":\"primary\"} -->\n<div class=\"wp-block-vk-blocks-icon vk_icon\"><div class=\"vk_icon_frame is-style-noline\"><div class=\"vk_icon_border has-text-color has-primary-color\" style=\"width:calc(42px + 36px);height:calc(42px + 36px);border-radius:0%\"><i style=\"font-size:42px\" class=\"fa-solid vk_icon_font fa-yen-sign\"></i></div></div></div>\n<!-- /wp:vk-blocks/icon --></div>\n<!-- /wp:group --></div>\n<!-- /wp:group -->\n\n<!-- wp:spacer {\"className\":\"is-style-spacer-xl\"} -->\n<div style=\"height:100px\" aria-hidden=\"true\" class=\"wp-block-spacer is-style-spacer-xl\"></div>\n<!-- /wp:spacer -->"
            }
        ]';

		// オプション値の追加などがあった場合は $test_data の配列の中のデータを追加してテストを追加してください.
		$test_data = array(
            // API があってキャッシュがない場合
            array(
				'options'  => array(
                    'VWSMail'           => 'vk-support@vektor-inc.co.jp',
                    'disableXT9Pattern' => false,
                ),
                'api' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => $xt9_patterns,
                ),
                'transients' => array(),
                'template' => 'x-t9',
                'correct'  => $this->build_expected_results( $favorite_patterns, $xt9_patterns, true ),
			),
            // API がなくてキャッシュがある場合
            array(
                'options'  => array(
                    'VWSMail'           => 'vk-support@vektor-inc.co.jp',
                    'disableXT9Pattern' => false,
                ),
                'api' => array(),
                'transients' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => $xt9_patterns,
                ),
                'template' => 'x-t9',
                'correct'  => $this->build_expected_results( $favorite_patterns, $xt9_patterns, true ),
            ),
            // X-T9 テーマでも favorites が登録されること（x-t9 が空の場合）
            array(
                'options'  => array(
                    'VWSMail'           => 'vk-support@vektor-inc.co.jp',
                    'disableXT9Pattern' => false,
                ),
                'api' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => '[]',
                ),
                'transients' => array(),
                'template' => 'x-t9',
                'correct'  => $this->build_expected_results( $favorite_patterns, '[]', true ),
            ),
            // X-T9 テーマでも favorites が登録されること（favorites のみ）
            array(
                'options'  => array(
                    'VWSMail'           => 'vk-support@vektor-inc.co.jp',
                    'disableXT9Pattern' => false,
                ),
                'api' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                ),
                'transients' => array(),
                'template' => 'x-t9',
                'correct'  => $this->build_expected_results( $favorite_patterns, '[]', true ),
            ),
             // API もキャッシュもある場合
            array(
				'options'  => array(
                    'VWSMail'           => 'vk-support@vektor-inc.co.jp',
                    'disableXT9Pattern' => false,
                ),
                'api' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => $xt9_patterns,
                ),
                'transients' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => $xt9_patterns,
                ),
                'template' => 'x-t9',
                'correct'  => $this->build_expected_results( $favorite_patterns, $xt9_patterns, true ),
			),
			array(
				'options'  => array(
                    'VWSMail'           => 'vk-support@vektor-inc.co.jp',
                    'disableXT9Pattern' => false,
                ),
                'api' => array(),
                'transients' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => $xt9_patterns,
                ),
                'template' => 'x-t9',
                'correct'  => $this->build_expected_results( $favorite_patterns, $xt9_patterns, true ),
			),
            array(
				'options'  => array(
                    'VWSMail'           => 'vk-support@vektor-inc.co.jp',
                    'disableXT9Pattern' => true,
                ),
                'api' => array(),
                'transients' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => $xt9_patterns,
                ),
                'template' => 'x-t9',
                'correct'  => $this->build_expected_results( $favorite_patterns, $xt9_patterns, false ),
			),
            array(
                'options'  => array(
                    'VWSMail'           => 'vk-support@vektor-inc.co.jp',
                    'disableXT9Pattern' => false,
                ),
                'api' => array(),
                'transients' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => $xt9_patterns,
                ),
                'template' => 'lightning',
                'correct'  => $this->build_expected_results( $favorite_patterns, $xt9_patterns, false ),
            ),
            array(
                'options'  => array(
                    'VWSMail'           => 'vk-support@vektor-inc.co.jp',
                    'disableXT9Pattern' => true,
                ),
                'api' => array(),
                'transients' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => $xt9_patterns,
                ),
                'template' => 'lightning',
                'correct'  => $this->build_expected_results( $favorite_patterns, $xt9_patterns, false ),
            ),
            array(
				'options'  => array(
                    'VWSMail'           => '',
                    'disableXT9Pattern' => false,
                ),
                'api' => array(),
                'transients' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => $xt9_patterns,
                ),
                'template' => 'x-t9',
                'correct'  => array(
                    'favorite' => array(),
                    'x-t9'     => array(),
                ),
			),
            array(
				'options'  => array(
                    'VWSMail'           => '',
                    'disableXT9Pattern' => false,
                ),
                'api' => array(),
                'transients' => array(
                    'role'     => 'pro-user',
                    'patterns' => $favorite_patterns,
                    'x-t9'     => $xt9_patterns,
                ),
                'template' => 'lightning',
                'correct'  => array(
                    'favorite' => array(),
                    'x-t9'     => array(),
                ),
			),
        );

        return $test_data;
    }

    public function test_register_patterns() {

        $test_data = self::get_test_data();
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'Register Patterns' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		foreach ( $test_data as $test_value ) {
            update_option( 'vk_block_patterns_options', $test_value['options'] );

			// テストデータにキャシュの指定がある場合.
			if ( ! empty( $test_value['transients']) ) {
				// キャッシュをセット.
				set_transient( 'vk_patterns_api_data_1_20', $test_value['transients'], 60 * 60 * 24 );
			}

			$return  = vbp_register_patterns( $test_value['api'], $test_value['template'] );
			$correct = $test_value['correct'];

			print 'return:' . PHP_EOL;
            var_dump( $return );
            print 'correct:' . PHP_EOL;
			var_dump( $correct );
			$this->assertEquals( $correct, $return );

			// キャッシュ削除.
			delete_transient( 'vk_patterns_api_data_1_20' );
		}
        delete_option( 'vk_block_patterns_options' );
	}    

    public function test_vbp_clear_patterns_cache(){
        $transients   = 'aaaa';
        $cached_keys  = array( 'vk_patterns_api_data_1_50', 'vk_patterns_api_data_2_25' );
        $legacy_cache = 'vk_patterns_api_data';

        // ユーザーを作成
        $user['administrator'] = $this->factory->user->create( array( 'role' => 'administrator' ) );
        $user['editor']        = $this->factory->user->create( array( 'role' => 'editor' ) );
        $user['author']        = $this->factory->user->create( array( 'role' => 'author' ) );
        $user['contributor']   = $this->factory->user->create( array( 'role' => 'contributor' ) );
        $user['subscriber']    = $this->factory->user->create( array( 'role' => 'subscriber' ) );

        // テストの配列
        $test_data = array(
            // 管理者
            array(
                'user_id' => $user['administrator'],
                'correct' => false,
            ),
            // 編集者
            array(
                'user_id' => $user['editor'],
                'correct' => 'aaaa',
            ),
            // 投稿者
            array(
                'user_id' => $user['author'],
                'correct' => 'aaaa',
            ),
            // 寄稿者
            array(
                'user_id' => $user['contributor'],
                'correct' => 'aaaa',
            ),
            // 購読者
            array(
                'user_id' => $user['subscriber'],
                'correct' => 'aaaa',
            ),
        );

		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'Delete Cache' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;

        foreach ( $test_data as $test_value ) {
            wp_set_current_user( $test_value['user_id'] );
            update_option( 'vk_patterns_api_cached_keys', $cached_keys );
            foreach ( $cached_keys as $cached_key ) {
                set_transient( $cached_key, $transients, 60 * 60 * 24 );
            }
            set_transient( $legacy_cache, $transients, 60 * 60 * 24 );
            vbp_clear_patterns_cache( true );
            $return = array(
                'new_keys' => array(
                    get_transient( $cached_keys[0] ),
                    get_transient( $cached_keys[1] ),
                ),
                'legacy_key' => get_transient( $legacy_cache ),
                'cached_keys_option' => get_option( 'vk_patterns_api_cached_keys' ),
            );
            $correct = array(
                'new_keys' => array( $test_value['correct'], $test_value['correct'] ),
                'legacy_key' => $test_value['correct'],
                'cached_keys_option' => ( false === $test_value['correct'] ) ? array() : $cached_keys,
            );

            print 'return:' . PHP_EOL;
            var_dump( $return );
            print 'correct:' . PHP_EOL;
            var_dump( $correct );
            $this->assertEquals( $correct, $return );

            // クリーンアップ.
            foreach ( $cached_keys as $cached_key ) {
                delete_transient( $cached_key );
            }
            delete_transient( $legacy_cache );
            delete_option( 'vk_patterns_api_cached_keys' );
        }
    }

	public function test_vbp_get_pattern_api_data_caches_response_with_page_and_per_page() {
		update_option(
			'vk_block_patterns_options',
			array(
				'VWSMail' => 'cache-test@example.com',
			)
		);

		$page          = 2;
		$per_page      = 25;
		$transient_key = 'vk_patterns_api_data_' . $page . '_' . $per_page;
		$response_body = array(
			'patterns'            => '[]',
			'x-t9'                => '[]',
			'has_more_favorites'  => false,
			'has_more_x_t9'       => false,
			'page'                => $page,
			'per_page'            => $per_page,
			'total_favorites'     => 0,
			'total_x_t9'          => 0,
		);

		delete_transient( $transient_key );
		delete_option( 'vk_patterns_api_cached_keys' );

		$pre_http_called = false;
		$http_filter = function( $_preempt, $_args, $_url ) use ( &$pre_http_called, $response_body ) {
			$pre_http_called = true;
			return array(
				'body'     => wp_json_encode( $response_body ),
				'response' => array(
					'code' => 200,
				),
			);
		};

		add_filter(
			'pre_http_request',
			$http_filter,
			10,
			3
		);

		$return        = vbp_get_pattern_api_data( $page, $per_page );
		$cached_keys   = get_option( 'vk_patterns_api_cached_keys', array() );
		$cached_return = get_transient( $transient_key );

		remove_filter( 'pre_http_request', $http_filter, 10 );
		delete_transient( $transient_key );
		delete_option( 'vk_block_patterns_options' );
		delete_option( 'vk_patterns_api_cached_keys' );

		$this->assertTrue( $pre_http_called );
		$this->assertEquals( $response_body, $return );
		$this->assertEquals( $response_body, $cached_return );
		$this->assertContains( $transient_key, $cached_keys );
	}

	public function test_vbp_get_pattern_api_data_uses_cached_data_when_available() {
		update_option(
			'vk_block_patterns_options',
			array(
				'VWSMail' => 'cache-test@example.com',
			)
		);

		$transient_key   = 'vk_patterns_api_data_1_20';
		$transient_value = array(
			'patterns' => '[]',
		);
		$pre_http_called = false;

		set_transient( $transient_key, $transient_value, 60 * 60 );

		$http_filter = function() use ( &$pre_http_called ) {
			$pre_http_called = true;
			return array(
				'body'     => wp_json_encode( array() ),
				'response' => array( 'code' => 200 ),
			);
		};

		add_filter(
			'pre_http_request',
			$http_filter,
			10,
			3
		);

		$return = vbp_get_pattern_api_data();

		remove_filter( 'pre_http_request', $http_filter, 10 );
		delete_transient( $transient_key );
		delete_option( 'vk_block_patterns_options' );

		$this->assertFalse( $pre_http_called );
		$this->assertEquals( $transient_value, $return );
	}

	public function test_vbp_register_patterns_stops_paging_when_xt9_disabled() {
		update_option(
			'vk_block_patterns_options',
			array(
				'VWSMail'           => 'paging-test@example.com',
				'disableXT9Pattern' => true,
			)
		);

		$api_call_count = 0;

		$http_filter = function( $_preempt, $args ) use ( &$api_call_count ) {
			$api_call_count++;
			$page = ! empty( $args['body']['page'] ) ? (int) $args['body']['page'] : 1;

			return array(
				'body'     => wp_json_encode(
					array(
						'patterns'           => '[]',
						'x-t9'               => '[]',
						'has_more_favorites' => false,
						'has_more_x_t9'      => true,
						'page'               => $page,
						'per_page'           => $args['body']['per_page'],
					)
				),
				'response' => array( 'code' => 200 ),
			);
		};

		$max_pages_filter = function() {
			return 2;
		};

		add_filter( 'pre_http_request', $http_filter, 10, 2 );
		add_filter( 'vbp_patterns_max_pages', $max_pages_filter );

		vbp_register_patterns( null, 'lightning' );

		remove_filter( 'pre_http_request', $http_filter, 10 );
		remove_filter( 'vbp_patterns_max_pages', $max_pages_filter );

		delete_transient( 'vk_patterns_api_data_1_20' );
		delete_transient( 'vk_patterns_api_data_2_20' );
		delete_option( 'vk_patterns_api_cached_keys' );
		delete_option( 'vk_block_patterns_options' );

		$this->assertSame( 1, $api_call_count );
	}

	public function test_vbp_reload_pattern_api_data_purges_expired_cache() {
		$old_time     = date( 'Y-m-d H:i:s', strtotime( '-2 hours' ) );
		$cached_keys  = array( 'vk_patterns_api_data_1_50', 'vk_patterns_api_data_2_25' );
		$options      = array(
			'VWSMail'             => 'reload-test@example.com',
			'last-pattern-cached' => $old_time,
		);
		$transient_value = array( 'patterns' => '[]' );

		update_option( 'vk_block_patterns_options', $options );
		update_option( 'vk_patterns_api_cached_keys', $cached_keys );
		foreach ( $cached_keys as $key ) {
			set_transient( $key, $transient_value, 60 * 60 );
		}

		vbp_reload_pattern_api_data();

		$updated_options = get_option( 'vk_block_patterns_options' );

		foreach ( $cached_keys as $key ) {
			$this->assertFalse( get_transient( $key ) );
		}
		$this->assertSame( array(), get_option( 'vk_patterns_api_cached_keys' ) );
		$this->assertGreaterThan( strtotime( $old_time ), strtotime( $updated_options['last-pattern-cached'] ) );

		delete_option( 'vk_block_patterns_options' );
		delete_option( 'vk_patterns_api_cached_keys' );
	}

	public function test_vbp_reload_pattern_api_data_keeps_recent_cache() {
		$current_time = date( 'Y-m-d H:i:s' );
		$options      = array(
			'VWSMail'             => 'reload-test@example.com',
			'last-pattern-cached' => $current_time,
		);
		$cached_keys = array( 'vk_patterns_api_data_1_50' );

		update_option( 'vk_block_patterns_options', $options );
		update_option( 'vk_patterns_api_cached_keys', $cached_keys );
		set_transient( $cached_keys[0], array( 'patterns' => '[]' ), 60 * 60 );

		vbp_reload_pattern_api_data();

		$this->assertNotFalse( get_transient( $cached_keys[0] ) );
		$this->assertEquals( $cached_keys, get_option( 'vk_patterns_api_cached_keys' ) );
		$this->assertEquals( $current_time, get_option( 'vk_block_patterns_options' )['last-pattern-cached'] );

		delete_transient( $cached_keys[0] );
		delete_option( 'vk_block_patterns_options' );
		delete_option( 'vk_patterns_api_cached_keys' );
	}
}
