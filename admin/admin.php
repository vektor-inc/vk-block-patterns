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
		$options = get_option( 'vk_block_patterns_options' );
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

	$get_menu_html = '<li><a href="#role-setting">' . __( 'Role Setting', 'vk-block-patterns' ) . '</a></li>';
	$get_menu_html = '<li><a href="#default-patterns-setting">' . __( 'Default Pattern Setting', 'vk-block-patterns' ) . '</a></li>';
	$lang          = ( get_locale() === 'ja' ) ? 'ja' : 'en';
	if ( 'ja' === $lang ) {
		$get_menu_html .= '<li><a href="#pattern-library-setting">' . __( 'VK Pattern Library Setting', 'vk-block-patterns' ) . '</a></li>';
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
			'default' => false,
		),
		'disablePluginPattern' => array(
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
	wp_localize_script( 'vk-patterns-admin-js', 'vkpOptions', $vbp_options );
}
add_action( 'admin_enqueue_scripts', 'vbp_admin_enqueue_scripts' );


/**
 * 警告を追加
 */
function vbp_vws_alert() {
	$options      = vbp_get_options();
	$notice       = '';
	$lang         = ( get_locale() === 'ja' ) ? 'ja' : 'en';
	$current_url  = ( ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) ) ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$url_next     = false === strpos( $current_url, '?' ) ? '?' : '&';
	$setting_link = admin_url() . 'options-general.php?page=vk_block_patterns_options';

	if ( 'ja' === $lang ) {
		if ( ! empty( $options['VWSMail'] ) ) {
			$pattern_api_data = vbp_get_pattern_api_data();
			if ( ! empty( $pattern_api_data ) && is_array( $pattern_api_data ) && ! empty( $pattern_api_data['role'] ) ) {
				$role = $pattern_api_data['role'];
				if ( 'invalid-user' === $role && false === $options['account-check']['disable-invalid-notice'] ) {
					$notice  = '<div class="notice notice-warning"><p>';
					$notice .= __( 'The registerd VWS account linkage is invalid. Please change VWS account linkage.', 'vk-block-patterns' );
					$notice .= ' ';
					$notice .= '<a href="' . $setting_link . '" class="button button-primary">' . __( 'Go to VK Block Patterns Setting', 'vk-block-patterns' ) . '</a>';
					$notice .= ' ';
					$notice .= '<a href="' . $current_url . $url_next . 'disable-invalid-notice" class="button button-secondary">' . __( 'Dismiss', 'vk-block-patterns' ) . '</a>';
					$notice .= '</p></div>';
				} elseif ( 'free-user' === $role && false === $options['account-check']['disable-free-notice'] ) {
					$notice  = '<div class="notice notice-warning"><p>';
					$notice .= __( 'Your VWS account linkage is Outdated. Please Update VWS account license.', 'vk-block-patterns' );
					$notice .= ' ';
					$notice .= '<a href="' . $current_url . $url_next . 'disable-free-notice" class="button button-secondary">' . __( 'Dismiss', 'vk-block-patterns' ) . '</a>';
					$notice .= '</p></div>';
				}
			}
		} elseif ( false === $options['account-check']['disable-empty-notice'] ) {
			$notice  = '<div class="notice notice-warning"><p>';
			$notice .= __( 'The VWS account linkage is not registerd. Please register VWS account linkage.', 'vk-block-patterns' );
			$notice .= ' ';
			$notice .= '<a href="' . $setting_link . '" class="button button-primary">' . __( 'Go to VK Block Patterns Setting', 'vk-block-patterns' ) . '</a>';
			$notice .= ' ';
			$notice .= '<a href="' . $current_url . $url_next . 'disable-empty-notice" class="button button-secondary">' . __( 'Dismiss', 'vk-block-patterns' ) . '</a>';
			$notice .= '</p></div>';
		}
	}
	echo $notice;
}
add_action( 'admin_notices', 'vbp_vws_alert' );

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
