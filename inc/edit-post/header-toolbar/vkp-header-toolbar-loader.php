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
function vbp_edit_post_enqueue_scripts() {
  $asset = include  VBP_PATH . 'build/edit-post/header-toolbar/index.asset.php';
  wp_enqueue_script( 
    'vk-patterns-header-toolbar-js', 
    VBP_URL . 'build/edit-post/header-toolbar/index.js', 
    $asset['dependencies'], 
    $asset['version'], 
    true 
  );
  $vbp_options = vbp_get_options();
  wp_localize_script( 'vk-patterns-header-toolbar-js', 'vkpOptions', $vbp_options );

  wp_enqueue_style( 
    'vk-patterns-header-toolbar-css', 
    VBP_URL . 'build/edit-post/header-toolbar/style-index.css' 
  );
}
add_action( 'enqueue_block_editor_assets', 'vbp_edit_post_enqueue_scripts' );
