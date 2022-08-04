<?php
/**
 * お気に入りのパターンを呼び出して登録
 */

/**
 * API からデータを読み込み
 */
function vbp_get_pattern_api_data() {
    $options    = get_option( 'vk_block_patterns_options' );
    $user_email = ! empty( $options['VWSMail'] ) ? $options['VWSMail'] : '';
    $return = '';

    if ( ! empty( $user_email ) ) {
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
                'label' => __( 'Favorites of VK Pattern Library', 'vk-block-patterns' ) 
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
add_action( 'init', 'vbp_register_favorite_patterns' );

/**
 * 警告を追加
 */
function vbp_vws_alert() {
    $options = vbp_get_options();
    $notice   = '';
    $lang          = ( get_locale() === 'ja' ) ? 'ja' : 'en';
    $setting_link  = admin_url() . 'options-general.php?page=vk_block_patterns_options';
    if ( 'ja' === $lang ) {
        if ( ! empty( $options['VWSMail'] ) ) {
            $pattern_api_data = vbp_get_pattern_api_data();
            if ( ! empty( $pattern_api_data ) && is_array( $pattern_api_data ) && ! empty( $pattern_api_data['role'] ) ) {
                $role = $pattern_api_data['role'];
                if ( 'invalid-user' === $role ) {
                    $notice .= '<div class="notice notice-warning"><p>';
                    $notice .= __( 'The registerd VWS account linkage is invalid. Please change VWS account linkage.','vk-block-patterns' );                    
                    $notice .= '<a href="' . $setting_link . '" class="button button-primary">' . __( 'Go to VK Block Patterns Setting', 'vk-block-patterns' ) . '</a>';
                    $notice .= '<a href="?disable-invalid-notice" class="button button-secondary">' . __( 'Dismiss', 'vk-block-patterns' ) . '</a>';
                    $notice .= '</p></div>';
                } elseif ( 'free-user' === $role ) {
                    $notice .= '<div class="notice notice-warning"><p>';
                    $notice .= __( 'Your VWS account linkage is Outdated. Please Update VWS account license.','vk-block-patterns' );                    
                    $notice .= '<a href="?disable-free-notice" class="button button-secondary">' . __( 'Dismiss', 'vk-block-patterns' ) . '</a>';
                    $notice .= '</p></div>';
                }                
            }
        } else {
            $notice .= '<div class="notice notice-warning"><p>';
            $notice .= __( 'The VWS account linkage is not registerd. Please register VWS account linkage.','vk-block-patterns' );                    
            $notice .= '<a href="' . $setting_link . '" class="button button-primary">' . __( 'Go to VK Block Patterns Setting', 'vk-block-patterns' ) . '</a>';
            $notice .= '<a href="?disable-empty-notice" class="button button-secondary">' . __( 'Dismiss', 'vk-block-patterns' ) . '</a>';
            $notice .= '</p></div>';
        }
    }

}
add_action( 'admin_notices', 'vbp_vws_alert' );

function vbp_admin_control() {
    $options    = vbp_get_options();
    $current_date = date('Y-m-d H:i:s');
    if (  isset( $_GET[ 'disable-invalid-notice'] ) ) {
        $options['account-check']['disable-invalid-notice'] = true;
    }
    if (  isset( $_GET[ 'disable-free-notice'] ) ) {
        $options['account-check']['disable-free-notice'] = true;
    }
    if (  isset( $_GET[ 'disable-empty-notice'] ) ) {
        $options['account-check']['disable-empty-notice'] = true;
    }
    if ( null !== $options['account-check']['date'] ) {

    }


}