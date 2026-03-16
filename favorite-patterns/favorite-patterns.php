<?php
/**
 * お気に入りのパターンを呼び出して登録
 *
 * @package vektor-inc/vk-block-patterns
 */

/**
 * API のデータをキャッシュに格納
 *
 * @param int  $page       API ページ番号.
 * @param int  $per_page   1 ページ当たりの取得件数.
 * @param bool $cache_only true の場合、キャッシュのみ参照し API 呼び出しをスキップ.
 *
 * @return array{
 *      array {
 *      role: string,
 *      title: string,
 *      categories: array,
 *      content: string,
 *      page: int,
 *      per_page: int,
 *      has_more_favorites: bool,
 *      has_more_theme: bool,
 *      total_favorites: int,
 *      total_theme: int
 *  }
 * } $return
 */
function vbp_get_pattern_api_data( $page = 1, $per_page = 20, $current_template = '', $cache_only = false ) {
	// オプション値を取得.
	$options = vbp_get_options();
	// メールアドレスを取得.
	$user_email = ! empty( $options['VWSMail'] ) ? $options['VWSMail'] : '';
	// 現在のテーマを取得.
	$current_template = ! empty( $current_template ) ? $current_template : get_template();
	// ページング付きのキャッシュキー.
	$transient_key = 'vk_patterns_api_data_' . absint( $page ) . '_' . absint( $per_page );
	// パターン情報をキャッシュデータから読み込み.
	$transients = get_transient( $transient_key );
	// デフォルトの返り値.
	$return = array();

	if ( ! empty( $user_email ) && ! empty( $current_template ) ) {
		// パターンのキャッシュがあればキャッシュを読み込み.
		if ( ! empty( $transients ) ) {
			$return = $transients;
		} elseif ( ! $cache_only ) {
			// キャッシュがない場合、かつキャッシュのみモードでなければ API を呼び出しキャッシュに登録.
			$result = wp_remote_post(
				'https://patterns.vektor-inc.co.jp/wp-json/vk-patterns/v1/status',
				array(
					'timeout' => 10,
					'body'    => array(
						'login_id' => $user_email,
						'page'     => absint( $page ),
						'per_page' => absint( $per_page ),
						'plugin_version' => defined( 'VBP_VERSION' ) ? VBP_VERSION : '',
						'current_theme'  => $current_template,
					),
				)
			);
			if ( is_wp_error( $result ) ) {
				error_log( 'VK Block Patterns API error: ' . $result->get_error_message() );
				return $return;
			} elseif ( ! empty( $result ) ) {
				$response_code = wp_remote_retrieve_response_code( $result );
				if ( $response_code < 200 || $response_code >= 300 ) {
					// HTTPステータスコードが「成功（2xx）」じゃない時にエラー扱い
					error_log( 'VK Block Patterns API error: HTTP ' . $response_code );
					return $return;
				}
				$return = json_decode( $result['body'], true );
				if ( null === $return && json_last_error() !== JSON_ERROR_NONE ) {
					error_log( 'VK Block Patterns API error: Invalid JSON response' );
					return array();
				}
				// APIで取得したパターンデータをキャッシュに登録. 30日 に設定.
				set_transient( $transient_key, $return, 60 * 60 * 24 * 30 ); // 30日間キャッシュ.
				$cached_keys   = get_option( 'vk_patterns_api_cached_keys', array() );
				if ( ! in_array( $transient_key, $cached_keys, true ) ) {
					$cached_keys[] = $transient_key;
					update_option( 'vk_patterns_api_cached_keys', $cached_keys );
				}
			}
		}
	}
	return $return;
}

/**
 * 編集画面を開いた時点で条件付きでキャッシュをクリア
 */
function vbp_reload_pattern_api_data() {

	// オプションを取得.
	$options = vbp_get_options();

	// キャッシュの有効時間（秒）.
	$cache_time = 60 * 60 * 24 * 30; // 30日.

	// 最後にキャッシュされた時間を取得.
	$last_cached = $options['last-pattern-cached'];

	// 現在の時刻を取得.
	$current_time = wp_date( 'Y-m-d H:i:s' );

	// 差分を取得・キャッシュが初めてならキャッシュの有効時間が経過したものとみなす.
	$diff = ! empty( $last_cached ) ? strtotime( $current_time ) - strtotime( $last_cached ) : $cache_time + 1;

	// フラグがなければパターンのデータのキャッシュをパージ.
	if ( $diff > $cache_time ) {
		// 期限切れのキャッシュのみをパージ.
		$cached_keys = get_option( 'vk_patterns_api_cached_keys', array() );
		if ( is_array( $cached_keys ) ) {
			$remaining_keys = array();
			foreach ( $cached_keys as $cached_key ) {
				// get_transient が false の場合は期限切れと判断.
				if ( false === get_transient( $cached_key ) ) {
					delete_transient( $cached_key );
				} else {
					$remaining_keys[] = $cached_key;
				}
			}
			update_option( 'vk_patterns_api_cached_keys', $remaining_keys );
		}
		// 最後にキャッシュされた時間を更新.
		$options['last-pattern-cached'] = $current_time;
		// 最低30日時間はキャッシュを保持.
		update_option( 'vk_block_patterns_options', $options );
	}
}
add_action( 'load-post.php', 'vbp_reload_pattern_api_data' );
add_action( 'load-post-new.php', 'vbp_reload_pattern_api_data' );
add_action( 'load-site-editor.php', 'vbp_reload_pattern_api_data' );


/**
 * パターンを登録
 *
 * @param array  $api        テスト用に用意した API を読み込む変数（通常は空）.
 * @param string $template   テスト用に用意した現在のテーマが何かを読み込む変数（通常は空）.
 * @param bool   $cache_only true の場合、キャッシュのみ参照し API 呼び出しをスキップ.
 *
 * @return array{
 *  'favorite' => array(),
 *  'x-t9'    => array()
 * } $returnx : 成功したらそれぞれの配列に true が入ってくる.
 */
function vbp_register_patterns( $api = null, $template = null, $cache_only = false ) {

	// オプション値を読み込み.
	$options = vbp_get_options();
	// テスト用の結果を返す配列.
	$result = array(
		'favorite' => array(),
		'x-t9'     => array(),
	);

	if ( ! empty( $options['VWSMail'] ) ) {
		// 現在のテーマを取得.
		$current_template = ! empty( $template ) ? $template : get_template();

		// 1 ページ当たりの取得件数をオプションから取得し、20〜50の範囲に制限. デフォルトは 20.
		$per_page                     = ! empty( $options['patternsPerPage'] ) ? absint( $options['patternsPerPage'] ) : 20;
		$per_page                     = max( 20, min( $per_page, 50 ) ); // 20〜50の範囲に制限.
		$per_page                     = apply_filters( 'vbp_patterns_api_per_page', $per_page );

		// テーマのパターンも取得するかどうかのフラグ.
		$theme_enabled                = ( '' !== $current_template && empty( $options['disableThemePattern'] ) );

		// ページ数
		$page                         = 1;
		// まだ取得すべきパターンがあるかどうかのフラグ. APIのレスポンスに基づいてループを続けるか判断するためのもの.
		$has_more                     = true;
		// 最大ページ数. 無限ループ防止のため、APIのレスポンスに関わらずこのページ数を超えたらループを強制終了する. デフォルトは 100 ページ.
		$max_pages                    = apply_filters( 'vbp_patterns_max_pages', 100 ); // 安全策として最大ページ数を設定.

		// お気に入りのパターンのパターンでカテゴリ登録のフラグ
		$favorite_category_registered = false;
		
		// テーマのパターンのカテゴリ登録のフラグ
		$theme_category_registered    = false;

		while ( $has_more && $page <= $max_pages ) {
			$pattern_api_data = ! empty( $api ) ? $api : vbp_get_pattern_api_data( $page, $per_page, $current_template, $cache_only );

			if ( empty( $pattern_api_data ) || ! is_array( $pattern_api_data ) ) {
				break;
			}

			if ( ! empty( $pattern_api_data['patterns'] ) ) {
				$patterns_data = $pattern_api_data['patterns'];

				if ( function_exists( 'mb_convert_encoding' ) ) {
					$patterns_data = mb_convert_encoding( $patterns_data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
				}

				$patterns = json_decode( $patterns_data, true );
				if ( ! $favorite_category_registered ) {
					register_block_pattern_category(
						'vk-pattern-favorites',
						array(
							'label' => __( 'Favorite patterns in VK Pattern Library', 'vk-block-patterns' ),
						)
					);
					$favorite_category_registered = true;
				}
				if ( ! empty( $patterns ) && is_array( $patterns ) ) {
					foreach ( $patterns as $pattern ) {
						$result['favorite'][] = register_block_pattern(
							'vkp-favorite-' . $pattern['post_name'],
							array(
								'title'      => $pattern['title'],
								'categories' => $pattern['categories'],
								'content'    => $pattern['content'],
							)
						);
					}
				}
			}

			if ( $theme_enabled ) {
				if ( ! empty( $pattern_api_data['x-t9'] ) ) {
					$patterns_data = $pattern_api_data['x-t9'];

					if ( function_exists( 'mb_convert_encoding' ) ) {
						$patterns_data = mb_convert_encoding( $patterns_data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
					}

					$patterns = json_decode( $patterns_data, true );
					if ( ! $theme_category_registered ) {
						register_block_pattern_category(
							'x-t9',
							array(
								'label' => __( 'X-T9', 'vk-block-patterns' ),
							)
						);
						$theme_category_registered = true;
					}
					if ( ! empty( $patterns ) && is_array( $patterns ) ) {
						foreach ( $patterns as $pattern ) {
							$result['x-t9'][] = register_block_pattern(
								'vkp-theme-' . $pattern['post_name'],
								array(
									'title'      => $pattern['title'],
									'categories' => $pattern['categories'],
									'content'    => $pattern['content'],
								)
							);
						}
					}
				}
			}

			$has_more_favorites = ! empty( $pattern_api_data['has_more_favorites'] );
			$has_more_xt9       = $theme_enabled ? ! empty( $pattern_api_data['has_more_x_t9'] ) : false;
			$has_more           = ( $per_page > 0 ) && ( $has_more_favorites || $has_more_xt9 );

			$page++;
			// テスト時に単一の配列を渡された場合は無限ループ防止で抜ける.
			if ( ! empty( $api ) ) {
				break;
			}
		}
	}
	return $result;
}
add_action(
	'init',
	function() {
		// フロントやエディタ（管理画面のURLとは限らない）など場合 => キャッシュされたパターンのみ使用（API呼び出しをスキップ）.
		$cache_only = ! is_admin();
		// パターンの登録自体は init フックで実行.
		vbp_register_patterns( null, null, $cache_only );
	}
);
