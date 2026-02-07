<?php
/**
 * お気に入りのパターンを呼び出して登録
 *
 * @package vektor-inc/vk-block-patterns
 */

/**
 * API のデータをキャッシュに格納
 *
 * @param int $page     API ページ番号.
 * @param int $per_page 1 ページ当たりの取得件数.
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
 *      has_more_x_t9: bool,
 *      total_favorites: int,
 *      total_x_t9: int
 *  }
 * } $return
 */
function vbp_get_pattern_api_data( $page = 1, $per_page = 20 ) {
	// オプション地を取得.
	$options = vbp_get_options();
	// メールアドレスを取得.
	$user_email = ! empty( $options['VWSMail'] ) ? $options['VWSMail'] : '';
	// ページング付きのキャッシュキー.
	$transient_key = 'vk_patterns_api_data_' . absint( $page ) . '_' . absint( $per_page );
	// パターン情報をキャッシュデータから読み込み.
	$transients = get_transient( $transient_key );
	// デフォルトの返り値.
	$return = array();

	if ( ! empty( $user_email ) ) {
		// パターンのキャッシュがあればキャッシュを読み込み.
		if ( ! empty( $transients ) ) {
			$return = $transients;
		} else {
			// キャッシュがない場合 API を呼び出しキャッシュに登録.
			$result = wp_remote_post(
				'https://patterns.vektor-inc.co.jp/wp-json/vk-patterns/v1/status',
				array(
					'timeout' => 10,
					'body'    => array(
						'login_id' => $user_email,
						'page'     => absint( $page ),
						'per_page' => absint( $per_page ),
						'plugin_version' => defined( 'VBP_VERSION' ) ? VBP_VERSION : '',
					),
				)
			);
			if ( is_wp_error( $result ) ) {
				error_log( 'VK Block Patterns API error: ' . $result->get_error_message() );
				return $return;
			} elseif ( ! empty( $result ) ) {
				$response_code = wp_remote_retrieve_response_code( $result );
				if ( $response_code < 200 || $response_code >= 300 ) {
					error_log( 'VK Block Patterns API error: HTTP ' . $response_code );
					return $return;
				}
				$return = json_decode( $result['body'], true );
				if ( null === $return && json_last_error() !== JSON_ERROR_NONE ) {
					error_log( 'VK Block Patterns API error: Invalid JSON response' );
					return array();
				}
				// APIで取得したパターンデータをキャッシュに登録. 1日 に設定.
				set_transient( $transient_key, $return, 60 * 60 * 24 );
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
	$cache_time = 60 * 60;

	// 最後にキャッシュされた時間を取得.
	$last_cached = $options['last-pattern-cached'];

	// 現在の時刻を取得.
	$current_time = wp_date( 'Y-m-d H:i:s' );

	// 差分を取得・キャッシュが初めてならキャッシュの有効時間が経過したものとみなす.
	$diff = ! empty( $last_cached ) ? strtotime( $current_time ) - strtotime( $last_cached ) : $cache_time + 1;

	// フラグがなければパターンのデータのキャッシュをパージ.
	if ( $diff > $cache_time ) {
		// パターンのデータのキャッシュをパージ.
		$cached_keys = get_option( 'vk_patterns_api_cached_keys', array() );
		if ( is_array( $cached_keys ) ) {
			foreach ( $cached_keys as $cached_key ) {
				delete_transient( $cached_key );
			}
		}
		update_option( 'vk_patterns_api_cached_keys', array() );
		// 最後にキャッシュされた時間を更新.
		$options['last-pattern-cached'] = $current_time;
		// 最低１時間はキャッシュを保持.
		update_option( 'vk_block_patterns_options', $options );
	}
}
add_action( 'admin_init', 'vbp_reload_pattern_api_data', 5 );



/**
 * パターンを登録
 *
 * @param array  $api テスト用に用意した API を読み込む変数（通常は空）.
 * @param string $template テスト用に用意した現在のテーマが何かを読み込む変数（通常は空）.
 *
 * @return array{
 *  'favorite' => array(),
 *  'x-t9'    => array()
 * } $returnx : 成功したらそれぞれの配列に true が入ってくる.
 */
function vbp_register_patterns( $api = null, $template = null ) {
	// オプション値を読み込み.
	$options = vbp_get_options();
	// テスト用の結果を返す配列.
	$result = array(
		'favorite' => array(),
		'x-t9'     => array(),
	);

	if ( ! empty( $options['VWSMail'] ) ) {
		$current_template = ! empty( $template ) ? $template : get_template();
		$per_page         = apply_filters( 'vbp_patterns_api_per_page', 20 );
		$xt9_enabled      = ( 'x-t9' === $current_template && empty( $options['disableXT9Pattern'] ) );
		$page             = 1;
		$has_more         = true;
		$max_pages        = apply_filters( 'vbp_patterns_max_pages', 100 ); // 安全策として最大ページ数を設定.
		$favorite_category_registered = false;
		$xt9_category_registered      = false;

		while ( $has_more && $page <= $max_pages ) {
			$pattern_api_data = ! empty( $api ) ? $api : vbp_get_pattern_api_data( $page, $per_page );

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
							'label' => __( 'Favorites of VK Pattern Library', 'vk-block-patterns' ),
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

			if ( $xt9_enabled ) {
				if ( ! empty( $pattern_api_data['x-t9'] ) ) {
					$patterns_data = $pattern_api_data['x-t9'];

					if ( function_exists( 'mb_convert_encoding' ) ) {
						$patterns_data = mb_convert_encoding( $patterns_data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
					}

					$patterns = json_decode( $patterns_data, true );
					if ( ! $xt9_category_registered ) {
						register_block_pattern_category(
							'x-t9',
							array(
								'label' => __( 'X-T9', 'vk-block-patterns' ),
							)
						);
						$xt9_category_registered = true;
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
			$has_more_xt9       = $xt9_enabled ? ! empty( $pattern_api_data['has_more_x_t9'] ) : false;
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
add_action( 'init', 'vbp_register_patterns' );
