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
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * メタボックスの作成
	 */
	public static function add_meta_box() {
		add_meta_box(
			'vk-block-patterns-init-pattern',
			__( 'Initial pattern setting', 'vk-block-patterns' ),
			array( __CLASS__, 'meta_box_html' ),
			'vk-block-patterns',
			'side'
		);
	}

	public static function allowed_html() {
		$common_attr = array(
			'id'    => array(),
			'class' => array(),
			'role'  => array(),
			'style' => array(),
			'title' => array(),
			'name'  => array(),
			'value' => array(),
		);
		$tags        = array(
			'div',
			'span',
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'button',
			'p',
			'i',
			'a',
			'br',
			'strong',
			'ol',
			'ul',
			'li',
			'img',
			'input',
			'select',
			'option',
		);
		foreach ( $tags as $tag ) {
			$allowed_html[ $tag ] = $common_attr;
		}
		$allowed_html['a']['href']      = array();
		$allowed_html['a']['target']    = array();
		$allowed_html['img']['src']     = array();
		$allowed_html['img']['sizes']   = array();
		$allowed_html['form']['method'] = array();
		$allowed_html['form']['action'] = array();
		$allowed_html['input']['type']  = array();
		$allowed_html['option']         = array(
			'value'    => true,
			'selected' => true,
		);
		$allowed_html['select']         = array(
			'id'       => true,
			'class'    => true,
			'name'     => true,
			'size'     => true,
			'multiple' => true,
			'disabled' => true,
			'tabindex' => true,
			'onchange' => true,
		);
		return $allowed_html;
	}

	/**
	 *  1.26 までは投稿タイプのみ保存し、投稿タイプの保存があれば、候補（show）に表示していた（自動挿入機能なし）。
	 *  1.27 で自動挿入が選べるようになったので、1.26以前で保存されている場合は、投稿タイプのみ保存してあって、
	 *  法事方式の指定がない状態なので、その場合は自動的に show にする処理が必要があるため、
	 *  このメソッドでその処理を行っている。
	 *
	 * @param string $saved_post_type 保存されている投稿タイプ.
	 * @param string $saved_add_method 保存されている追加方法.
	 */
	public static function is_method_selected( $saved_post_type, $saved_add_method ) {
		$return = '';
		// 投稿タイプのみ保存されている場合は、自動的に show にする処理.
		if ( $saved_post_type && ( empty( $saved_add_method ) || '' === $saved_add_method ) ) {
			$return = 'show';
		} elseif ( $saved_add_method ) {
			$return = $saved_add_method;
		}
		return $return;
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

		$html  = '<div class="vk-block-patterns-input-wrap">';
		$html .= '<p>' . __( 'You can set this pattern as the default pattern for a specific post type.', 'vk-block-patterns' ) . '</p>';

		// 対象の投稿タイプを選択.
		$html .= '<h4>' . esc_html__( 'Target Post Type.', 'vk-block-patterns' ) . '</h4>';
		$html .= '<select name="vbp-init-post-type" id="vbp-init-post-type">';
		foreach ( $post_types as $post_type ) {
			if ( $post_type->name === $saved_post_type ) {
				$html .= '<option value="' . $post_type->name . '" selected>' . $post_type->label . '</option>';
			} else {
				$html .= '<option value="' . $post_type->name . '" >' . $post_type->label . '</option>';
			}
		};
		$html .= '</select>';

		// 投稿の新規作成時、候補に表示or自動追加.
		$add_method_options = array(
			''     => __( 'Unspecified', 'vk-block-patterns' ),
			'add'  => __( 'Auto add', 'vk-block-patterns' ),
			'show' => __( 'Show in Candidate', 'vk-block-patterns' ),
		);

		$html .= '<h4>' . esc_html__( 'How to Add Patterns.', 'vk-block-patterns' ) . '</h4>';

		$html .= '<select name="vbp-init-pattern-add-method" id="vbp-init-pattern-add-method">';
		foreach ( $add_method_options as $key => $value ) {
			if ( self::is_method_selected( $saved_post_type, $saved_add_method ) === $key ) {
				$selected = ' selected';
			} else {
				$selected = '';
			}
			$html .= '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $value ) . '</option>';
		}
		$html .= '</select>';

		$html .= '<p>' . esc_html__( 'If there are multiple patterns with "Auto Add" selected for one post type, only the oldest pattern will be inserted.', 'vk-block-patterns' ) . '</p>';

		$html .= '</div>';

		echo wp_kses( $html, self::allowed_html() );
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

	public static function enqueue_scripts() {
		wp_enqueue_style(
			'vk-block-patterns-editor',
			plugins_url( '/editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . '/editor.css' )
		);
	}
}
new AddMetaBox();
