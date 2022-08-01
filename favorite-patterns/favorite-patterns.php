<?php
/**
 * お気に入りのパターンを呼び出して登録
 */

/**
 * API からデータを読み込み
 */
function vbp_get_api_data() {
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

