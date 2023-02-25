<?php

/**
 * VK Patterns Custom Fields
 *
 * @package VK Patterns
 */

/**
 * メタボックスの作成
 */
function vk_block_patterns_add_meta_box() {
	add_meta_box(
		'vk-block-patterns-init-pattern',
		'Use in initial pattern',
		'vk_block_patterns_meta_box_html',
		'vk-block-patterns',
		'side'
	);
}
add_action( 'admin_menu', 'vk_block_patterns_add_meta_box' );

/**
 * メタボックスの中身の HTML
 *
 * @param Object $post 投稿の情報が詰まったオブジェクト.
 */
function vk_block_patterns_meta_box_html( $post ) {
	$data = get_post_meta( $post->ID, 'vbp-init-post-type', true );
	$data = ! empty( $data ) ? $data : '';

	$post_types = get_post_types( array( 'public' => true ), 'objects' );

	$html  = '<div class="vk-block-patterns-input-wrap">';
	$html .= '<h4>' . esc_html__( 'Target Post Type.', 'vk-block-patterns' ) . '</h4>';
	$html .= '<p>' . esc_html__( 'If you want to use this pattern as the initial pattern for the specific post type, please specify the target post type.', 'vk-block-patterns' ) . '</p>';
	$html .= '<select name="vbp-init-post-type" id="vbp-init-post-type">';
	$html .= '<option value="">' . esc_html__( 'Do not use', 'vk-block-patterns' ) . '</option>';
	foreach ( $post_types as $post_type ) {
		if ( $post_type->name === $data ) {
			$html .= '<option value="' . $post_type->name . '" selected="selected">' . $post_type->label . '</option>';
		} else {
			$html .= '<option value="' . $post_type->name . '" >' . $post_type->label . '</option>';
		}
	};
	$html .= '</select>';
	$html .= '</div>';
	echo $html;
}

/**
 * メタボックスの中身の HTML
 *
 * @param in t $post_id 投稿ID.
 */
function vk_block_patterns_save_meta_box( $post_id ) {
	if ( ! empty( $_POST['vbp-init-post-type'] ) ) {
		update_post_meta( $post_id, 'vbp-init-post-type', $_POST['vbp-init-post-type'] );
	} else {
		delete_post_meta( $post_id, 'vbp-init-post-type' );
	}
}

add_action( 'save_post', 'vk_block_patterns_save_meta_box' );
