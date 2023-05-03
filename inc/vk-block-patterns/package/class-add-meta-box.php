<?php
/**
 * VK Block Patterns metabox class
 *
 * @package vektor-inc/vk-block-patterns
 */

namespace VKBlockPatterns;

class AddMetaBox {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( __CLASS__, 'add_meta_box' ) );
		add_action( 'save_post', array( __CLASS__, 'save_meta_box' ) );
	}

	/**
	 * メタボックスの作成
	 */
	public static function add_meta_box() {
		add_meta_box(
			'vk-block-patterns-init-pattern',
			'Use in initial pattern',
			array( __CLASS__, 'meta_box_html' ),
			'vk-block-patterns',
			'side'
		);
	}

	/**
	 * メタボックスの中身の HTML
	 *
	 * @param Object $post 投稿の情報が詰まったオブジェクト.
	 */
	public static function meta_box_html( $post ) {
		$saved_add_method = get_post_meta( $post->ID, 'vbp-init-pattern-add-method', true );
		$saved_post_type  = get_post_meta( $post->ID, 'vbp-init-post-type', true );

		$saved_add_method = ! empty( $saved_add_method ) ? $saved_add_method : '';
		$saved_post_type  = ! empty( $saved_post_type ) ? $saved_post_type : '';

		// 追加されている投稿タイプを取得.
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		$html = '<div class="vk-block-patterns-input-wrap">';

		// 投稿の新規作成時、候補に表示or自動追加.
		$add_method_options = array(
			''     => __( 'Do not use', 'vk-block-patterns' ),
			'show' => __( 'Show in Candidate', 'vk-block-patterns' ),
			'add'  => __( 'Auto add', 'vk-block-patterns' ),
		);

		$html .= '<h4>' . esc_html__( 'How to Add Patterns.', 'vk-block-patterns' ) . '</h4>';
		$html .= '<select name="vbp-init-pattern-add-method" id="vbp-init-pattern-add-method">';
		foreach ( $add_method_options as $key => $value ) {
			$selected = '';
			if ( $saved_post_type && ( empty( $saved_add_method ) || $saved_add_method === '' ) && $key === 'show' ) {
				$selected = 'selected';
			} elseif ( $key === $saved_add_method ) {
				$selected = 'selected';
			}
			$html .= '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $value ) . '</option>';
		}
		$html .= '</select>';

		// 対象の投稿タイプを選択.
		$html .= '<h4>' . esc_html__( 'Target Post Type.', 'vk-block-patterns' ) . '</h4>';
		$html .= '<p>' . esc_html__( 'If you want to use this pattern as the initial pattern for the specific post type, please specify the target post type.', 'vk-block-patterns' ) . '</p>';
		$html .= '<select name="vbp-init-post-type" id="vbp-init-post-type">';
		foreach ( $post_types as $post_type ) {
			if ( $post_type->name === $saved_post_type ) {
				$html .= '<option value="' . $post_type->name . '" selected="selected">' . $post_type->label . '</option>';
			} else {
				$html .= '<option value="' . $post_type->name . '" >' . $post_type->label . '</option>';
			}
		};
		$html .= '</select>';
		$html .= '<p>' . esc_html__( 'If there are multiple patterns with "Auto Add" selected for one post type, only the oldest pattern will be inserted.', 'vk-block-patterns' ) . '</p>';

		$html .= '</div>';
		echo $html;
	}

	/**
	 * Save meta box content.
	 *
	 * @param in t $post_id 投稿ID.
	 */
	public static function save_meta_box( $post_id ) {
		if ( isset( $_POST['vbp-init-pattern-add-method'] ) ) {
			update_post_meta( $post_id, 'vbp-init-pattern-add-method', $_POST['vbp-init-pattern-add-method'] );
		} else {
			delete_post_meta( $post_id, 'vbp-init-pattern-add-method' );
		}
		if ( isset( $_POST['vbp-init-post-type'] ) ) {
			update_post_meta( $post_id, 'vbp-init-post-type', $_POST['vbp-init-post-type'] );
		} else {
			delete_post_meta( $post_id, 'vbp-init-post-type' );
		}
		return true;
	}
}
new AddMetaBox();
