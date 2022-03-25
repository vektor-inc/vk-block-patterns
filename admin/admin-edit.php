<?php
/**
 * Edit Option
 *
 * @package VK Block Patterns
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
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
add_action('init', 'vkp_show_patterns_register_settings');

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
  // booleanは空false''もしくは1 trueを渡す
  $vbp_options = vbp_get_options();
  wp_localize_script( 'vk-patterns-admin-js', 'vkpOptions', $vbp_options );
}
add_action('admin_enqueue_scripts', 'vbp_admin_enqueue_scripts');
