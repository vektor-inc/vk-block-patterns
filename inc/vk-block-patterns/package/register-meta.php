<?php
/**
 * Register post meta for REST API access.
 * ブロックエディタからREST API経由でメタデータを読み書きするために登録する。
 *
 * @package vektor-inc/vk-block-patterns
 */

/**
 * Register vbp meta keys for the REST API.
 * vbp メタキーをREST APIに登録する。
 *
 * @return void
 */
function vbp_register_post_meta() {
	register_post_meta(
		'vk-block-patterns',
		'vbp-init-post-type',
		array(
			'type'              => 'string',
			'description'       => 'Target post type / 対象の投稿タイプ',
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);

	register_post_meta(
		'vk-block-patterns',
		'vbp-init-pattern-add-method',
		array(
			'type'              => 'string',
			'description'       => 'How to add pattern / パターンの追加方法',
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'init', 'vbp_register_post_meta' );
