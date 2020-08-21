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

		<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
			<?php
			wp_nonce_field( 'vbp-nonce-key', 'vbp-setting-page' );
			require_once dirname( __FILE__ ) . '/admin-role.php';
			?>
		</form>
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

	Vk_Admin::admin_page_frame( $get_page_title, 'vbp_setting', $get_logo_html, $get_menu_html );
}

/**
 * Save Option
 */
function vbp_setting_option_save() {
	if ( isset( $_POST['vk_block_patterns_options'] ) && $_POST['vk_block_patterns_options'] ) {

		if ( check_admin_referer( 'vbp-nonce-key', 'vbp-setting-page' ) ) {
			if ( isset( $_POST['vk_block_patterns_options'] ) && $_POST['vk_block_patterns_options'] ) {
				update_option( 'vk_block_patterns_options', $_POST['vk_block_patterns_options'] );
			} else {
				update_option( 'vk_block_patterns_options', '' );
			}

			wp_safe_redirect( menu_page_url( 'vk_block_patterns_options', false ) );
		}
	}
}
add_action( 'admin_init', 'vbp_setting_option_save', 10, 2 );
