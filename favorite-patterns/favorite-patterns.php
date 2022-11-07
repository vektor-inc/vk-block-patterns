<?php
/**
 * お気に入りのパターンを呼び出して登録
 *
 * @package vektor-inc/vk-block-patterns
 */

/**
 * API からデータを読み込み
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
	$options    = vbp_get_options();
	$user_email = ! empty( $options['VWSMail'] ) ? bin2hex( openssl_encrypt( esc_html( $options['VWSMail'] ), 'aes-256-cbc', '7634268425857832', OPENSSL_RAW_DATA, '2684258578322424' ) ) : '';
	$return     = '';

	if ( ! empty( $user_email ) ) {
		// パターン情報をキャッシュデータから読み込み読み込み.
		$transients = get_transient( 'vk_patterns_api_data' );

		// パターンのキャッシュがあればキャッシュを読み込み.
		if ( ! empty( $transients ) ) {
			$return = $transients;
		} else {
			// キャッシュがない場合 API を呼び出しキャッシュに登録.
			$result = wp_remote_post(
				'https://test.patterns.vektor-inc.co.jp/wp-json/vk-patterns/v1/status',
				array(
					'timeout' => 10,
					'body'    => array(
						'login_email' => $user_email,
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
function vbp_register_favorite_patterns( $api = null, $template = null ) {
	$options = vbp_get_options();
	$result  = array(
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
add_action( 'init', 'vbp_register_favorite_patterns' );
