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
if ( ! defined( 'VBP_LOCK_TTL' ) ) {
	define( 'VBP_LOCK_TTL', 30 ); // 30秒
}
if ( ! defined( 'VBP_LOCK_WAIT_INTERVAL' ) ) {
	define( 'VBP_LOCK_WAIT_INTERVAL', 0.5 ); // 0.5秒
}
if ( ! defined( 'VBP_LOCK_MAX_WAITS' ) ) {
	define( 'VBP_LOCK_MAX_WAITS', 20 ); // 最大10秒
}

function vbp_get_pattern_api_data( $page = 1, $per_page = 20 ) {
	// オプション値を取得.
	$options = vbp_get_options();
	// メールアドレスを取得.
	$user_email = ! empty( $options['VWSMail'] ) ? $options['VWSMail'] : '';
	// ページング付きのキャッシュキー.
	$transient_key = 'vk_patterns_api_data_' . absint( $page ) . '_' . absint( $per_page );
	// スタンピード対策のロックキー.
	$lock_key      = $transient_key . '_lock';
	// ファイルキャッシュを先に確認.
	$file_cached = vbp_read_file_cache( $transient_key );
	if ( null !== $file_cached ) {
		return $file_cached;
	}
	// デフォルトの返り値.
	$return = array();

	if ( ! empty( $user_email ) ) {
		// キャッシュがない場合 API を呼び出しキャッシュに登録.
		// 先にロックを取得して同時アクセス時のAPI連打を防止.
		$lock_ttl = VBP_LOCK_TTL;
		if ( false !== get_transient( $lock_key ) ) {
			// ロック中なら少し待ってキャッシュ再確認.
			$max_waits = VBP_LOCK_MAX_WAITS;
			for ( $i = 0; $i < $max_waits; $i++ ) {
				usleep( (int) ( VBP_LOCK_WAIT_INTERVAL * 1000 * 1000 ) );
				$file_cached = vbp_read_file_cache( $transient_key );
				if ( null !== $file_cached ) {
					return $file_cached;
				}
				if ( false === get_transient( $lock_key ) ) {
					break;
				}
			}
			// まだキャッシュがなければ空で返す.
			error_log( 'VK Block Patterns: Cache lock timeout for key: ' . $transient_key );
			return $return;
		}
		// ロック取得（30秒）.
		set_transient( $lock_key, 1, $lock_ttl );

		try {
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
				// APIで取得したパターンデータをファイルキャッシュに登録. 30日 に設定.
				vbp_write_file_cache( $transient_key, $return, VBP_CACHE_TTL );
			}
		} finally {
			delete_transient( $lock_key );
		}
	}
	return $return;
}

/**
 * ファイルキャッシュの保存先を取得
 *
 * @return string
 */
function vbp_get_cache_dir() {
	$upload_dir = wp_upload_dir();
	if ( ! empty( $upload_dir['basedir'] ) && is_dir( $upload_dir['basedir'] ) && is_writable( $upload_dir['basedir'] ) ) {
		return trailingslashit( $upload_dir['basedir'] . '/vk-block-patterns-cache' );
	}
	if ( defined( 'WP_CONTENT_DIR' ) && is_dir( WP_CONTENT_DIR ) && is_writable( WP_CONTENT_DIR ) ) {
		return trailingslashit( WP_CONTENT_DIR . '/vk-block-patterns-cache' );
	}
	error_log( 'VK Block Patterns: Using temporary directory for cache. Cache may be cleared on server restart.' );
	return trailingslashit( sys_get_temp_dir() . '/vk-block-patterns-cache' );
}

/**
 * ファイルキャッシュのパスを取得
 *
 * @param string $key Cache key.
 * @return string
 */
function vbp_get_cache_file_path( $key ) {
	$safe_key = preg_replace( '/[^a-zA-Z0-9_\-]/', '_', (string) $key );
	return vbp_get_cache_dir() . $safe_key . '.json';
}

/**
 * ファイルキャッシュを読み込み
 *
 * @param string $key Cache key.
 * @return array|null
 */
function vbp_read_file_cache( $key ) {
	$file = vbp_get_cache_file_path( $key );
	if ( ! file_exists( $file ) ) {
		return null;
	}
	$raw = file_get_contents( $file );
	if ( false === $raw ) {
		return null;
	}
	$data = json_decode( $raw, true );
	if ( ! is_array( $data ) || ! isset( $data['expires'], $data['payload'] ) ) {
		return null;
	}
	if ( time() >= (int) $data['expires'] ) {
		@unlink( $file );
		return null;
	}
	return $data['payload'];
}

/**
 * ファイルキャッシュを保存
 *
 * @param string $key Cache key.
 * @param mixed  $payload Cache payload.
 * @param int    $ttl TTL in seconds.
 * @return void
 */
function vbp_write_file_cache( $key, $payload, $ttl ) {
	$dir = vbp_get_cache_dir();
	if ( ! is_dir( $dir ) ) {
		if ( ! wp_mkdir_p( $dir ) ) {
			error_log( 'VK Block Patterns: Failed to create cache directory: ' . $dir );
			return;
		}
	}
	if ( ! is_dir( $dir ) || ! is_writable( $dir ) ) {
		error_log( 'VK Block Patterns: Cache directory is not writable: ' . $dir );
		return;
	}
	// Prevent direct access to cache directory.
	$index_file = $dir . 'index.php';
	if ( ! file_exists( $index_file ) ) {
		@file_put_contents( $index_file, "<?php\n// Silence is golden.\n" );
	}
	$htaccess = $dir . '.htaccess';
	if ( ! file_exists( $htaccess ) ) {
		@file_put_contents( $htaccess, "Deny from all\n" );
	}
	$body = array(
		'expires' => time() + (int) $ttl,
		'payload' => $payload,
	);
	$file_path = vbp_get_cache_file_path( $key );
	$tmp_path  = $file_path . '.tmp';
	if ( false !== @file_put_contents( $tmp_path, wp_json_encode( $body ), LOCK_EX ) ) {
		@chmod( $tmp_path, 0644 );
		@rename( $tmp_path, $file_path );
	}
}

/**
 * 期限切れのファイルキャッシュのみ削除
 *
 * @return void
 */
function vbp_purge_expired_file_cache() {
	$dir = vbp_get_cache_dir();
	if ( ! is_dir( $dir ) ) {
		return;
	}
	$handle = opendir( $dir );
	if ( false === $handle ) {
		return;
	}
	while ( false !== ( $file = readdir( $handle ) ) ) {
		if ( '.' === $file || '..' === $file ) {
			continue;
		}
		if ( '.json' !== substr( $file, -5 ) ) {
			continue;
		}
		$path = $dir . $file;
		$raw  = file_get_contents( $path );
		if ( false === $raw ) {
			continue;
		}
		$data = json_decode( $raw, true );
		if ( ! is_array( $data ) || ! isset( $data['expires'] ) ) {
			continue;
		}
		if ( time() >= (int) $data['expires'] ) {
			@unlink( $path );
		}
	}
	closedir( $handle );
}

/**
 * ファイルキャッシュを全削除
 *
 * @return void
 */
function vbp_clear_file_cache_all() {
	$dir = vbp_get_cache_dir();
	if ( ! is_dir( $dir ) ) {
		return;
	}
	$handle = opendir( $dir );
	if ( false === $handle ) {
		return;
	}
	while ( false !== ( $file = readdir( $handle ) ) ) {
		if ( '.' === $file || '..' === $file ) {
			continue;
		}
		if ( '.json' !== substr( $file, -5 ) ) {
			continue;
		}
		@unlink( $dir . $file );
	}
	closedir( $handle );
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
		// 期限切れのファイルキャッシュのみ削除.
		vbp_purge_expired_file_cache();
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
 * 旧トランジェント/オプションを一度だけ削除
 */
function vbp_cleanup_legacy_transients() {
	$done = get_option( 'vbp_legacy_transients_purged' );
	if ( $done ) {
		return;
	}
	global $wpdb;
	$like_value   = $wpdb->esc_like( '_transient_vk_patterns_api_data_' ) . '%';
	$like_timeout = $wpdb->esc_like( '_transient_timeout_vk_patterns_api_data_' ) . '%';
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
			$like_value,
			$like_timeout
		)
	);
	delete_transient( 'vk_patterns_api_data' );
	delete_option( 'vk_patterns_api_cached_keys' );
	update_option( 'vbp_legacy_transients_purged', 1 );
}
add_action( 'plugins_loaded', 'vbp_cleanup_legacy_transients', 20 );


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
