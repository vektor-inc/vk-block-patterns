<?php
/**
 * Admin Page
 *
 * @package VK Block Patterns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'vbp_setting' ) ) {
	/**
	 * Admin Page Setting.
	 */
	function vbp_setting() {
		$options = vbp_get_options();
		?>
		<div id="vk_block_patterns_admin"></div>
		<?php
	}
}

$admin_pages = array( 'settings_page_vk_block_patterns_options' );
Vk_Admin::admin_scripts( $admin_pages );

/**
 * Setting Menu
 */
function vbp_setting_menu() {
	global $vbp_prefix;
	$custom_page = add_options_page(
		$vbp_prefix . __( 'Block Patterns setting', 'vk-block-patterns' ), // Name of page.
		$vbp_prefix . _x( 'Block Patterns', 'label in admin menu', 'vk-block-patterns' ),  // Label in menu.
		'edit_theme_options',               // Capability required　このメニューページを閲覧・使用するために最低限必要なユーザーレベルまたはユーザーの種類と権限.
		'vk_block_patterns_options',               // ユニークなこのサブメニューページの識別子.
		'vbp_setting_page'         // メニューページのコンテンツを出力する関数.
	);
	if ( ! $custom_page ) {
		return;
	}
}
add_action( 'admin_menu', 'vbp_setting_menu' );

/**
 * Setting Pqage
 */
function vbp_setting_page() {
	global $vbp_prefix;
	$get_page_title = $vbp_prefix . __( 'Block Patterns Setting', 'vk-block-patterns' );

	$get_logo_html = '<img src="' . plugin_dir_url( __FILE__ ) . '/images/vk-block-patterns-logo_ol.svg" alt="VK Block Patterns" />';
	$get_logo_html = apply_filters( 'vbp_logo_html', $get_logo_html );

	$get_menu_html  = '<li><a href="#role-setting">' . __( 'Role Setting', 'vk-block-patterns' ) . '</a></li>';
	$get_menu_html .= '<li><a href="#default-patterns-setting">' . __( 'Default Pattern Setting', 'vk-block-patterns' ) . '</a></li>';
	$lang           = ( get_locale() === 'ja' ) ? 'ja' : 'en';
	if ( 'ja' === $lang ) {
		$get_menu_html .= '<li><a href="#pattern-library-setting">' . __( 'VK Pattern Library Setting', 'vk-block-patterns' ) . '</a></li>';
		$get_menu_html .= '<li><a href="#cache-setting">' . __( 'Patterns data cache setting', 'vk-block-patterns' ) . '</a></li>';
	}

	Vk_Admin::admin_page_frame( $get_page_title, 'vbp_setting', $get_logo_html, $get_menu_html );
}

/**
 * 設定項目の登録.
 */
function vkp_show_patterns_register_settings() {
	$properties_editor_settings = array();
	$default_editor_settings    = array();
	$default_option_settings    = array(
		'role'                 => array(
			'type'    => 'string',
			'default' => 'author',
		),
		'showPatternsLink'     => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'VWSMail'              => array(
			'type'    => 'string',
			'default' => '',
		),
		'disableCorePattern'   => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'disablePluginPattern' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'disableXT9Pattern'    => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'savePluginData'    => array(
			'type'    => 'boolean',
			'default' => false,
		),
	);

	foreach ( $default_option_settings as $key => $value ) {
		$properties_editor_settings[ $key ] = array(
			'type' => $value['type'],
		);
		$default_editor_settings[ $key ]    = $value['default'];
	}
	register_setting(
		'vbp_setting',
		'vk_block_patterns_options',
		array(
			'type'         => 'object',
			'show_in_rest' => array(
				'schema' => array(
					'type'       => 'object',
					'properties' => $properties_editor_settings,
				),
			),
			'default'      => $default_editor_settings,
		)
	);
}
add_action( 'init', 'vkp_show_patterns_register_settings' );

/**
 * Enqueue scripts
 *
 * @param mixed $hook_suffix
 * @return void
 */
function vbp_admin_enqueue_scripts( $hook_suffix ) {
	// 作成したオプションページ以外では読み込まない.
	if ( 'settings_page_vk_block_patterns_options' !== $hook_suffix ) {
		return;
	}

	$asset = include VBP_PATH . 'build/admin/index.asset.php';
	// Enqueue CSS dependencies.
	foreach ( $asset['dependencies'] as $style ) {
		wp_enqueue_style( $style );
	}

	$admin_style = VBP_URL . 'build/admin/style-index.css';
	wp_enqueue_style( 'vk-block-patterns-admin-style', $admin_style, array(), $asset['version'] );

	wp_enqueue_script(
		'vk-patterns-admin-js',
		VBP_URL . 'build/admin/index.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);
	wp_set_script_translations( 'vk-patterns-admin-js', 'vk-block-patterns' );

	// 画面読み込み時に保存値を localize_script を使って渡す.
	// boolean は 空 '' false または 1 true を渡す.
	$vbp_options             = vbp_get_options();
	$vbp_options['adminUrl'] = admin_url();
	$vbp_options['template'] = get_template();
	$vbp_options['ajaxUrl']  = admin_url( 'admin-ajax.php' );
	wp_localize_script( 'vk-patterns-admin-js', 'vkpOptions', $vbp_options );
}
add_action( 'admin_enqueue_scripts', 'vbp_admin_enqueue_scripts' );


/**
 * 警告文のリスト
 */
function vbp_vws_alert_list() {

	// 変数を定義
	$current_url  = ( ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) ) ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$url_next     = false === strpos( $current_url, '?' ) ? '?' : '&';
	$setting_link = admin_url() . 'options-general.php?page=vk_block_patterns_options';

	// 無効なメールアドレス（ patterns. に該当アカウントがない ）が設定された場合.
	$invalid_notice  = '<div class="notice notice-warning"><p>';
	$invalid_notice .= '登録されたメールアドレスのユーザーが <a href="https://patterns.vektor-inc.co.jp/" target="_blank" rel="noopener noreferrer">VK Pattern Library</a> に見つかりませんでした。<br />';
	$invalid_notice .= '<a href="https://patterns.vektor-inc.co.jp/my-account/" target="_blank" rel="noopener noreferrer">VK Pattern Library の登録メールアドレス</a> と同じメールアドレスを登録してください。<br />';
	$invalid_notice .= '有料版ユーザーで VK Pattern Library のユーザーアカウントを未発行の場合は <a href="https://vws.vektor-inc.co.jp/my-account" target="_blank" rel="noopener noreferrer">VWS のマイアカウントページ</a> から VK Pattern Library のユーザーを発行してください。</p>';
	$invalid_notice .= '<p>';
	$invalid_notice .= '<a href="https://patterns.vektor-inc.co.jp/about/flow/" class="button button-primary" target="_blank" rel="noopener noreferrer">VK Pattern Library ユーザー登録手順</a>';
	$invalid_notice .= ' ';
	$invalid_notice .= '<a href="https://patterns.vektor-inc.co.jp/about/about-favorite/" class="button button-primary" target="_blank" rel="noopener noreferrer">VK Pattern Library お気に入り設定</a>';
	$invalid_notice .= ' ';
	$invalid_notice .= '<a href="' . $setting_link . '" class="button button-primary">' . __( 'Go to VK Block Patterns Setting', 'vk-block-patterns' ) . '</a>';
	$invalid_notice .= ' ';
	$invalid_notice .= '<a href="' . $current_url . $url_next . 'disable-invalid-notice" class="button button-secondary">' . __( 'Dismiss', 'vk-block-patterns' ) . '</a>';
	$invalid_notice .= '</p></div>';

	// 無料版（＝有効期限が切れた）ユーザーが設定された場合.
	$free_notice  = '<div class="notice notice-warning"><p>';
	$free_notice .= '設定されたメールアドレスと連携している <a href="https://vws.vektor-inc.co.jp/my-account/license" target="_blank" rel="noopener noreferrer">VWSアカウント</a> はライセンスの期限が切れているためお気に入り機能などがご利用できなくなっています。ライセンスの再購入をご検討ください。';
	$free_notice .= '</p>';
	$free_notice .= '<p>';
	$free_notice .= '<a href="https://vws.vektor-inc.co.jp/product/lightning-g3-pro-pack" class="button button-primary" target="_blank" rel="noopener noreferrer">Lightning G3 Pro Pack</a>';
	$free_notice .= ' ';
	$free_notice .= '<a href="' . $current_url . $url_next . 'disable-free-notice" class="button button-secondary">' . __( 'Dismiss', 'vk-block-patterns' ) . '</a>';
	$free_notice .= '</p>';
	$free_notice .= '</div>';

	// メールアドレスが入力されていない場合.
	$empty_notice  = '<div class="notice notice-warning">';
	$empty_notice .= '<p>';
	$empty_notice .= 'VK Pattern Library のアカウント連携が設定されていません。';
	$empty_notice .= '</p>';
	$empty_notice .= '<div style="margin-bottom:10px;">';
	$empty_notice .= '<a href="' . $setting_link . '" class="button button-primary">' . __( 'Go to VK Block Patterns Setting', 'vk-block-patterns' ) . '</a>';
	$empty_notice .= ' ';
	$empty_notice .= '<a href="' . $current_url . $url_next . 'disable-empty-notice" class="button button-secondary">' . __( 'Dismiss', 'vk-block-patterns' ) . '</a>';
	$empty_notice .= '</div>';
	$empty_notice .= '</div>';

	// 配列に整えて返す.
	$alert = array(
		'invalid-user' => $invalid_notice,
		'free-user'    => $free_notice,
		'empty-user'   => $empty_notice,
	);

	return $alert;
}

/**
 * 警告を追加
 *
 * @param Array $api API for TEST.
 */
function vbp_vws_alert( $api = array() ) {
	$options = vbp_get_options();
	$alerts  = vbp_vws_alert_list();
	$notice  = '';
	$lang    = ( get_locale() === 'ja' || get_locale() === 'ja_JP' ) ? 'ja' : 'en';

	if ( 'ja' === $lang ) {
		if ( ! empty( $options['VWSMail'] ) ) {
			$pattern_api_data = ! empty( $api ) ? $api : vbp_get_pattern_api_data();
			if ( ! empty( $pattern_api_data ) && is_array( $pattern_api_data ) && ! empty( $pattern_api_data['role'] ) ) {
				$role = $pattern_api_data['role'];
				if ( 'invalid-user' === $role && false === $options['account-check']['disable-invalid-notice'] ) {
					$notice = $alerts['invalid-user'];
				} elseif ( 'free-user' === $role && false === $options['account-check']['disable-free-notice'] ) {
					$notice = $alerts['free-user'];
				}
			}
		} elseif ( false === $options['account-check']['disable-empty-notice'] ) {
			$notice = $alerts['empty-user'];
		}
	}
	return $notice;
}

function vbp_display_vws_alert() {
	$notice = vbp_vws_alert();
	echo $notice;
}
add_action( 'admin_notices', 'vbp_display_vws_alert' );

function vbp_admin_control() {
	$options      = vbp_get_options();
	$current_date = date( 'Y-m-d H:i:s' );

	if ( null !== $options['account-check']['date'] ) {
		$checked_date = $options['account-check']['date'];
		$diff_yaer    = ( strtotime( $current_date ) - strtotime( $checked_date ) ) / ( 60 * 60 * 24 * 365 );
		if ( 1 <= $diff_yaer ) {
			$options['account-check']['disable-invalid-notice'] = false;
			$options['account-check']['disable-free-notice']    = false;
		}
	}

	if ( isset( $_GET['disable-invalid-notice'] ) ) {
		$options['account-check']['disable-invalid-notice'] = true;
	}
	if ( isset( $_GET['disable-free-notice'] ) ) {
		$options['account-check']['disable-free-notice'] = true;
	}
	if ( isset( $_GET['disable-empty-notice'] ) ) {
		$options['account-check']['disable-empty-notice'] = true;
	}
	$options['account-check']['date'] = $current_date;
	update_option( 'vk_block_patterns_options', $options );
}
add_action( 'admin_init', 'vbp_admin_control' );

 /**
  * API連携で取得したパターンデータのキャッシュを削除
  * Delete Cache Pattern Data from API
  */
function vbp_clear_patterns_cache() {
	delete_transient( 'vk_patterns_api_data' );
	die();
}
// 'clear_patterns_cache' の部分は src/admin/js/index.js　で定義している.
add_action( 'wp_ajax_clear_patterns_cache', 'vbp_clear_patterns_cache' );
add_action( 'wp_ajax_nopriv_clear_patterns_cache', 'vbp_clear_patterns_cache' );
