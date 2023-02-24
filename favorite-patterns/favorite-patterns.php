<?php
/**
 * お気に入りのパターンを呼び出して登録
 *
 * @package vektor-inc/vk-block-patterns
 */

/**
 * API のデータをキャッシュに格納
 *
 * @return array{
 *      array {
 *      role: string,
 *      title: string,
 *      categories: array,
 *      content: string,
 *  }
 * } $return
 */
function vbp_get_pattern_api_data() {
	// オプション地を取得.
	$options = vbp_get_options();
	// メールアドレスを取得.
	$user_email = ! empty( $options['VWSMail'] ) ? $options['VWSMail'] : '';
	// パターン情報をキャッシュデータから読み込み読み込み.
	$transients = get_transient( 'vk_patterns_api_data' );
	// デフォルトの返り値.
	$return = '';

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
					),
				)
			);
			if ( ! empty( $result ) && ! is_wp_error( $result ) ) {
				$return = json_decode( $result['body'], true );
				// APIで取得したパターンデータをキャッシュに登録. 1日 に設定.
				set_transient( 'vk_patterns_api_data', $return, 60 * 60 * 24 );
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
	$current_time = date( 'Y-m-d H:i:s' );

	// 差分を取得・キャッシュが初めてならキャッシュの有効時間が経過したものとみなす.
	$diff = ! empty( $last_cached ) ? strtotime( $current_time ) - strtotime( $last_cached ) : $cache_time + 1;

	// フラグがなければパターンのデータのキャッシュをパージ.
	if ( $diff > $cache_time ) {
		// パターンのデータのキャッシュをパージ.
		delete_transient( 'vk_patterns_api_data' );
		// 最後にキャッシュされた時間を更新.
		$options['last-pattern-cached'] = $current_time;
		// 最低１時間はキャッシュを保持.
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
		$pattern_api_data = ! empty( $api ) ? $api : vbp_get_pattern_api_data();
		$current_template = ! empty( $template ) ? $template : get_template();

		if ( ! empty( $pattern_api_data ) && is_array( $pattern_api_data ) ) {
			if ( ! empty( $pattern_api_data['patterns'] ) ) {
				$patterns_data = $pattern_api_data['patterns'];

				if ( function_exists( 'mb_convert_encoding' ) ) {
					$patterns_data = mb_convert_encoding( $patterns_data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
				}

				$patterns = json_decode( $patterns_data, true );
				register_block_pattern_category(
					'vk-pattern-favorites',
					array(
						'label' => __( 'Favorites of VK Pattern Library', 'vk-block-patterns' ),
					)
				);
				if ( ! empty( $patterns ) && is_array( $patterns ) ) {
					foreach ( $patterns as $pattern ) {
						$result['favorite'][] = register_block_pattern(
							$pattern['post_name'],
							array(
								'title'      => $pattern['title'],
								'categories' => $pattern['categories'],
								'content'    => $pattern['content'],
							)
						);
					}
				}
			}

			if ( 'x-t9' === $current_template && empty( $options['disableXT9Pattern'] ) ) {
				if ( ! empty( $pattern_api_data['x-t9'] ) ) {
					$patterns_data = $pattern_api_data['x-t9'];

					if ( function_exists( 'mb_convert_encoding' ) ) {
						$patterns_data = mb_convert_encoding( $patterns_data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
					}

					$patterns = json_decode( $patterns_data, true );
					register_block_pattern_category(
						'x-t9',
						array(
							'label' => __( 'X-T9', 'vk-block-patterns' ),
						)
					);
					if ( ! empty( $patterns ) && is_array( $patterns ) ) {
						foreach ( $patterns as $pattern ) {
							$result['x-t9'][] = register_block_pattern(
								$pattern['post_name'],
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
		}
	}
	return $result;
}
add_action( 'init', 'vbp_register_patterns' );
