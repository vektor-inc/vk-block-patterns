<?php
/**
 * Plugin Name: VK Block Patterns
 * Plugin URI: https://github.com/vektor-inc/vk-block-patterns
 * Description: You can make and register your original custom block patterns.
 * Version: 1.0.0
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
 * Plugin Loaded
 */
function vbp_plugin_loaded() {

	// Load Main File.
	require_once VBP_PATH . '/inc/vk-block-patterns/vk-block-patterns-config.php';
}
add_action( 'plugins_loaded', 'vbp_plugin_loaded' );

