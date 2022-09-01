<?php
/**
 * お気に入りのパターンを呼び出して登録
 *
 * @package vektor-inc/vk-block-patterns
 */

/**
 * API からデータを読み込み
 */
function vbp_get_pattern_api_data() {
	$options    = vbp_get_options();
	$user_email = ! empty( $options['VWSMail'] ) ? $options['VWSMail'] : '';
	$return     = '';

	if ( ! empty( $user_email ) ) {
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
		}
	}
	return $return;
}

/**
 * パターンを登録
 */
function vbp_register_favorite_patterns() {
	$pattern_api_data = vbp_get_pattern_api_data();
	$current_template = get_template();
	$options          = vbp_get_options();
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
					register_block_pattern(
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

		if ( 'x-t9' === $current_template && false === $options['disableXT9Pattern'] ) {
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
						register_block_pattern(
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
add_action( 'init', 'vbp_register_favorite_patterns' );
