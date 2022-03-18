<?php
/**
 * Enqueue assets
 *
 * @package VK Block Patterns
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Load Scripts
 */
function vbp_admin_enqueue_scripts() {
  $asset = include  VBP_PATH . 'build/edit-post/header-toolbar/index.asset.php';
  wp_enqueue_script( 'vk-patterns-header-toolbar-js', VBP_URL . 'build/edit-post/header-toolbar/index.js', $asset['dependencies'], $asset['version'], true );
  wp_enqueue_style( 'vk-patterns-header-toolbar-css', VBP_URL . 'build/edit-post/header-toolbar/style-main.css' );
}
add_action( 'enqueue_block_editor_assets', 'vbp_admin_enqueue_scripts' );
