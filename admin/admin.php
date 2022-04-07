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

	$get_menu_html  = '<li><a href="#role-setting">' . __( 'Role Setting', 'vk-block-patterns' ) . '</a></li>';
	$lang           = ( get_locale() === 'ja' ) ? 'ja' : 'en';
	if ( 'ja' === $lang ) {
		$get_menu_html  .= '<li><a href="#editor-setting">' . __( 'Editor Setting', 'vk-block-patterns' ) . '</a></li>';
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
    'role'         => array(
      'type'    => 'string',
      'default' => 'author',
    ),
    'showPatternsLink'         => array(
      'type'    => 'boolean',
      'default' => true,
    ),
  );

  foreach ($default_option_settings as $key => $value) {
    $properties_editor_settings[$key] = array(
      'type' => $value['type'],
    );
    $default_editor_settings[$key]    = $value['default'];
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
    wp_enqueue_style($style);
  }

  wp_enqueue_script(
    'vk-patterns-admin-js', 
    VBP_URL . 'build/admin/index.js', 
    $asset['dependencies'], 
    $asset['version'], 
    true
  );

  // 画面読み込み時に保存値を localize_script を使って渡す.
  // booleanは空'' falseまたは1 trueを渡す
  $vbp_options = vbp_get_options();
  wp_localize_script( 'vk-patterns-admin-js', 'vkpOptions', $vbp_options );
}
add_action( 'admin_enqueue_scripts', 'vbp_admin_enqueue_scripts' );
