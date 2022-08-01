<?php
/**
 * お気に入りのパターンを呼び出して登録
 */

/**
 * API からデータを読み込み
 */
function vbp_get_pattern_api_data() {
    $result = wp_remote_post(
        'https://test.patterns.vektor-inc.co.jp/wp-json/vk-patterns/v1/status',
        array(
            'timeout' => 10,
            'body' => array(
                'login_id' => 'vk-support@vektor-inc.co.jp',
            ),
        )
    );
    return json_decode( $result['body'] );
}

/**
 * パターンを登録
 */
function vbp_register_favorite_patterns() {
    $pattern_api_data = vbp_get_pattern_api_data();
    $patterns = $pattern_api_data['patterns'];
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
add_action( 'init', 'vbp_register_favorite_patterns' );