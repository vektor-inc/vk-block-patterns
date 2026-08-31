<?php
/**
 * VK Block Patterns editor panel assets class
 * VK Block Patterns エディタパネル用アセットクラス
 *
 * The legacy classic meta box ("Initial pattern setting" via add_meta_box())
 * has been removed because its save_post handler unconditionally overwrote
 * the vbp-init-* meta with the (always empty) $_POST data whenever the block
 * editor saved via the REST API, silently deleting the settings saved by the
 * new sidebar panel (src/editor-panel/index.js). This class now only keeps
 * the asset enqueue responsibility that the legacy class also handled.
 * レガシーなクラシックメタボックス（add_meta_box() による「Initial pattern
 * setting」）は、その save_post ハンドラがブロックエディタの REST API 保存時
 * （常に空になる $_POST）で vbp-init-* メタを無条件に上書きし、新しいサイド
 * バーパネル（src/editor-panel/index.js）で保存した設定を毎回削除してしまう
 * ため削除した。このクラスは、レガシークラスが兼務していたアセットの
 * enqueue 処理のみを引き継ぐ。
 *
 * @package vektor-inc/vk-block-patterns
 */

namespace VKBlockPatterns;

/**
 * ブロックエディタ用パネルのアセット（CSS/JS）を読み込むクラス。
 */
class EditorPanelAssets {

	/**
	 * Constructor
	 * コンストラクタ.
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * ブロックエディター用スクリプト・スタイルを読み込む
	 * Load scripts and styles for the block editor.
	 *
	 * 投稿タイプのあるブロックエディター画面でのみ実行する。
	 * ウィジェット編集画面（wp-edit-widgets / wp-customize-widgets）では
	 * wp-editor ハンドルを enqueue すると PHP notice が発生するため除外する。
	 * Only runs on block editor screens with a post type.
	 * Skips widget editor screens to avoid the PHP notice caused by enqueuing wp-editor.
	 *
	 * @return void
	 */
	public static function enqueue_scripts() {
		// 投稿タイプのあるブロックエディター画面以外では処理しない。
		// Skip if not on a block editor screen that has a post type.
		$screen = get_current_screen();
		if ( ! $screen || ! $screen->is_block_editor || empty( $screen->post_type ) ) {
			return;
		}

		wp_enqueue_style(
			'vk-block-patterns-editor',
			plugins_url( '/editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . '/editor.css' )
		);

		// Block editor native sidebar panel.
		// ブロックエディタ用ネイティブサイドバーパネル。
		$asset_path = plugin_dir_path( __DIR__ ) . '../../build/editor-panel/index.asset.php';
		if ( file_exists( $asset_path ) ) {
			$asset_file = include $asset_path;
			wp_enqueue_script(
				'vbp-editor-panel',
				plugins_url( '../../build/editor-panel/index.js', __DIR__ ),
				$asset_file['dependencies'],
				$asset_file['version'],
				true
			);
			// Pass translated strings and post types to JS.
			// 翻訳済み文字列と投稿タイプの情報をJSに渡す。
			$post_types      = get_post_types( array( 'public' => true ), 'objects' );
			$post_type_data  = array();
			foreach ( $post_types as $pt ) {
				$post_type_data[ $pt->name ] = array( 'label' => $pt->label );
			}
			wp_localize_script(
				'vbp-editor-panel',
				'vbpEditor',
				array(
					'postTypes' => $post_type_data,
					'i18n'      => array(
						'panelTitle'       => __( 'Initial pattern setting', 'vk-block-patterns' ),
						'description'      => __( 'You can set this pattern as the default pattern for a specific post type.', 'vk-block-patterns' ),
						'targetPostType'   => __( 'Target Post Type.', 'vk-block-patterns' ),
						'howToAddPatterns' => __( 'How to Add Patterns.', 'vk-block-patterns' ),
						'unspecified'      => __( 'Unspecified', 'vk-block-patterns' ),
						'autoAdd'          => __( 'Auto add', 'vk-block-patterns' ),
						'showInCandidate'  => __( 'Show in Candidate', 'vk-block-patterns' ),
						'multiplePatterns' => __( 'If there are multiple patterns with "Auto Add" selected for one post type, only the oldest pattern will be inserted.', 'vk-block-patterns' ),
					),
				)
			);
		}
	}
}
new EditorPanelAssets();
