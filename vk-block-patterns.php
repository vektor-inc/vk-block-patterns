<?php
/**
 * Plugin Name: VK Block Patterns
 * Plugin URI: https://github.com/vektor-inc/vk-block-patterns
 * Description: You can make and register your original custom block patterns.
 * Version: 1.28.0
 * Requires at least: 6.0
 * Author:  Vektor,Inc.
 * Author URI: https://vektor-inc.co.jp
 * Text Domain: vk-block-patterns
 * License: GPL 2.0 or Later
 *
 * @package VK Block Patterns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'register_block_pattern' ) ) {
	return;
}

// Define plugin version.
$plugin_data = get_file_data( __FILE__, array( 'version' => 'Version' ) );
define( 'VBP_VERSION', $plugin_data['version'] );

// Define plugin path.
define( 'VBP_PATH', plugin_dir_path( __FILE__ ) );

// Define plugin url.
define( 'VBP_URL', plugin_dir_url( __FILE__ ) );

// define plugin prefix.
global $vbp_prefix;
$vbp_prefix = apply_filters( 'vbp_prefix', 'VK ' );

/**
 * オプション値を取得して返す
 *
 * @return $options オプション値の配列
 */
function vbp_get_options() {
	// デフォルト値.
	// この値を追加した場合は ./test/test-get-options.php のテストを追記する.
	$default = array(
		'role'                 => 'author',
		'showPatternsLink'     => true,
		'VWSMail'              => '',
		'disableCorePattern'   => true,
		'disablePluginPattern' => false,
		'disableXT9Pattern'    => false,
		'account-check'        => array(
			'date'                   => null,
			'disable-empty-notice'   => false,
			'disable-invalid-notice' => false,
			'disable-free-notice'    => false,
		),
		'last-pattern-cached'  => null,
		'savePluginData'       => false,
	);
	$options = get_option( 'vk_block_patterns_options' );
	// 後から追加される項目もあるので、option値に保存されてない時にデフォルトとマージする
	// ただし wp_parse_args は1階層目の内容しかきれいにマージしてくれないので注意.
	$options = wp_parse_args( $options, $default );
	return $options;
}


/**
 * Plugin Loaded
 */
function vbp_plugin_loaded() {

	// Load Main File.
	require_once VBP_PATH . 'inc/vk-block-patterns/vk-block-patterns-config.php';
	// Load VKAdmin.
	require_once VBP_PATH . 'inc/vk-admin/vk-admin-config.php';
	// Load Edit Post Options.
	require_once VBP_PATH . 'inc/edit-post/vk-edit-post-config.php';
	// Load Admin Options.
	require_once VBP_PATH . 'admin/admin.php';

	require VBP_PATH . '/favorite-patterns/favorite-patterns.php';
}
add_action( 'plugins_loaded', 'vbp_plugin_loaded' );

$options = vbp_get_options();
if ( ! empty( $options['disableCorePattern'] ) ) {
	remove_theme_support( 'core-block-patterns' );
}

require dirname( __FILE__ ) . '/patterns-data/class-register-patterns-from-json.php';
if ( ! empty( $options['disablePluginPattern'] ) ) {
	remove_action( 'init', array( 'wp_content\plugins\vk_block_patterns\patterns_data\Register_Patterns_From_Json', 'register_template' ) );
}

// Add a link to this plugin's settings page
function vbp_set_plugin_meta( $links ) {
	$settings_link = '<a href="options-general.php?page=vk_block_patterns_options">' . __( 'Setting', 'vk-block-patterns' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'vbp_set_plugin_meta', 10, 1 );

/**
 * Add pattern library link
 *
 * @return void
 */
function vbp_add_pattern_link() {
	$parent_slug = 'edit.php?post_type=vk-block-patterns';
	$page_title  = 'パターンライブラリ';
	$menu_title  = 'パターンライブラリ';
	$capability  = 'edit_posts';
	$menu_slug   = 'https://patterns.vektor-inc.co.jp/';
	$function    = '';
	if ( 'ja' === get_locale() ) {
		add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' );
	}
}
add_action( 'admin_menu', 'vbp_add_pattern_link' );

/**
 * アンインストール処理
 */
function vbp_uninstall() {

	$options = vbp_get_options();

	// データを削除しないにチェックが入っていたら何もしない
	if ( ! empty( $options['savePluginData'] ) ) {
		return;
	}

	// オプションを削除
	unregister_setting( 'vbp_setting', 'vk_block_patterns_options' );
	delete_option( 'vk_block_patterns_options' );
	delete_site_option( 'vk_block_patterns_options' );

}
register_uninstall_hook( __FILE__, 'vbp_uninstall' );
