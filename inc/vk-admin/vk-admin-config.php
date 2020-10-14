<?php
/**
 * VK Admin Config
 *
 * @package VK Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Vk_Admin' ) ) {
	require_once plugin_dir_path( __FILE__ ) . '/package/class-vk-admin.php';
}
