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
if ( ! defined( 'VBP_CACHE_TTL' ) ) {
	define( 'VBP_CACHE_TTL', 60 * 60 * 24 * 30 ); // 30日
}
if ( ! defined( 'VBP_API_THROTTLE_SECONDS' ) ) {
	define( 'VBP_API_THROTTLE_SECONDS', 60 ); // 60秒
}

function vbp_get_pattern_api_data( $page = 1, $per_page = 20 ) {
	// オプション値を取得.
	$options = vbp_get_options();
	// メールアドレスを取得.
	$user_email = ! empty( $options['VWSMail'] ) ? $options['VWSMail'] : '';
	// ページング付きのキャッシュキー.
	$transient_key = 'vk_patterns_api_data_' . absint( $page ) . '_' . absint( $per_page );
	// キャッシュを先に確認.
	$cached = get_transient( $transient_key );
	if ( is_array( $cached ) ) {
		return $cached;
	}
	// デフォルトの返り値.
	$return = array();

	if ( ! empty( $user_email ) ) {

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
				// HTTPステータスコードが「成功（2xx）」じゃない時にエラー扱い
				error_log( 'VK Block Patterns API error: HTTP ' . $response_code );
				return $return;
			}
			$return = json_decode( $result['body'], true );
			if ( null === $return && json_last_error() !== JSON_ERROR_NONE ) {
				error_log( 'VK Block Patterns API error: Invalid JSON response' );
				return array();
			}
			if ( is_array( $return ) ) {
				set_transient( $transient_key, $return, VBP_CACHE_TTL );
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
	$cache_time = VBP_CACHE_TTL;

	// 最後にキャッシュされた時間を取得.
	$last_cached = $options['last-pattern-cached'];

	// 現在の時刻を取得.
	$current_time = wp_date( 'Y-m-d H:i:s' );

	// 差分を取得・キャッシュが初めてならキャッシュの有効時間が経過したものとみなす.
	$diff = ! empty( $last_cached ) ? strtotime( $current_time ) - strtotime( $last_cached ) : $cache_time + 1;

	// フラグがなければパターンのデータのキャッシュをパージ.
	if ( $diff > $cache_time ) {
		if ( wp_using_ext_object_cache() ) {
			// External object cache: expire logic is handled by cache backend.
			// Force a sweep of all expired transients to align with DB behavior.
			if ( function_exists( 'delete_expired_transients' ) ) {
				delete_expired_transients();
			}
		} else {
			global $wpdb;
			$like_timeout = $wpdb->esc_like( '_transient_timeout_vk_patterns_api_data_' ) . '%';
			$timeout_rows = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s",
					$like_timeout
				)
			);
			if ( false === $timeout_rows ) {
				error_log(
					'VK Block Patterns: vbp_reload_pattern_api_data failed. ' .
					'last_error=' . $wpdb->last_error
				);
				return;
			}
			foreach ( $timeout_rows as $row ) {
				if ( ! isset( $row->option_name, $row->option_value ) ) {
					continue;
				}
				if ( (int) $row->option_value >= time() ) {
					continue;
				}
				$cache_key = preg_replace( '/^_transient_timeout_/', '', $row->option_name );
				if ( is_string( $cache_key ) && '' !== $cache_key ) {
					delete_transient( $cache_key );
				}
			}
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
		$per_page = ! empty( $options['patternsPerPage'] ) ? absint( $options['patternsPerPage'] ) : 20;
		$per_page = max( 20, min( $per_page, 50 ) ); // 20〜50の範囲に制限.
		$per_page = apply_filters( 'vbp_patterns_api_per_page', $per_page );
		$xt9_enabled      = ( 'x-t9' === $current_template && empty( $options['disableXT9Pattern'] ) );
		$page             = 1;
		$has_more         = true;
		$max_pages        = apply_filters( 'vbp_patterns_max_pages', 100 ); // 安全策として最大ページ数を設定.
		$favorite_category_registered = false;
		$xt9_category_registered      = false;

		// API呼び出しの最小間隔を確認（ページネーション開始前に1回だけチェック）.
		$last_api_call = (int) get_option( 'vk_patterns_api_last_call', 0 );
		$current_time  = time();
		if ( ( $current_time - $last_api_call ) < VBP_API_THROTTLE_SECONDS ) {
			// 最小間隔未満なので API を呼ばずに結果を返す.
			return $result;
		}
		// API 呼び出し時刻を記録（呼び出し前に記録することで、同時リクエストも防ぐ）.
		update_option( 'vk_patterns_api_last_call', $current_time, false );

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
