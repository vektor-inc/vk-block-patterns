<?php
/**
 * Configuration of VK Block Patterns
 *
 * @package VK Block Patterns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'VK_Block_Patterns' ) ) {
	require_once plugin_dir_path( __FILE__ ) . '/package/class-vk-block-patterns.php';
	require_once plugin_dir_path( __FILE__ ) . '/package/class-add-meta-box.php';
}