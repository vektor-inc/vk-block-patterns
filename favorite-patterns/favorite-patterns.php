<?php
/**
 * お気に入りのパターンを呼び出して登録
 */

/**
 * API からデータを読み込み
 */
function vbp_get_pattern_api_data() {
    $options    = get_option( 'vk_block_patterns_options' );
    $user_email = $options['patternLibraryUserName'];
    $return = '';

    $result = wp_remote_post(
        'https://test.patterns.vektor-inc.co.jp/wp-json/vk-patterns/v1/status',
        array(
            'timeout' => 10,
            'body' => array(
                'login_id' => $user_email,
            ),
        )
    );
    if ( ! empty( $result ) && ! is_wp_error( $result ) ) {
        $return = json_decode( $result['body'], true );
    }
    return $return;
}

/**
 * パターンを登録
 */
function vbp_register_favorite_patterns() {
    $pattern_api_data = vbp_get_pattern_api_data();
    if ( ! empty( $pattern_api_data ) && is_array( $pattern_api_data ) ) {
        $patterns_data = $pattern_api_data['patterns'];
        
        if ( function_exists( 'mb_convert_encoding' ) ) {
            $patterns_data = mb_convert_encoding( $patterns_data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
        }
        
        $patterns = json_decode( $patterns_data, true );
        register_block_pattern_category(
            'vk-pattern-favorites',
            array( 
                'label' => __( 'Favorites Pattern Library', 'vk-block-patterns' ) 
            )
        );
        if ( ! empty( $patterns ) && is_array( $patterns ) ) {
            foreach ( $patterns as $pattern ) {
                register_block_pattern( 
                    $pattern['post_name'],
                    array(
                        'title'      => $pattern['title'],
                        'categories' => array( 'vk-pattern-favorites' ),
                        'content'    => $pattern['content'],
                    )
                );
            }
        }
    }
}
add_action( 'init', 'vbp_register_favorite_patterns' );
