<?php
/**
 * Class RegisterMetaTest
 *
 * @package vektor-inc/vk-block-patterns
 */

/**
 * Test that post meta keys are registered and work correctly.
 * メタキーが正しく登録され動作するかテストする。
 */
class RegisterMetaTest extends WP_UnitTestCase {

	/**
	 * Test meta values can be saved and retrieved for vk-block-patterns post type.
	 * vk-block-patterns 投稿タイプでメタ値の保存・取得ができるかテスト。
	 */
	public function test_meta_save_and_retrieve() {
		$post_id = $this->factory->post->create( array( 'post_type' => 'vk-block-patterns' ) );

		update_post_meta( $post_id, 'vbp-init-post-type', 'post' );
		update_post_meta( $post_id, 'vbp-init-pattern-add-method', 'add' );

		$this->assertEquals( 'post', get_post_meta( $post_id, 'vbp-init-post-type', true ) );
		$this->assertEquals( 'add', get_post_meta( $post_id, 'vbp-init-pattern-add-method', true ) );
	}

	/**
	 * Test sanitize callback strips dangerous input.
	 * サニタイズコールバックが危険な入力を除去するかテスト。
	 */
	public function test_meta_sanitize() {
		$post_id = $this->factory->post->create( array( 'post_type' => 'vk-block-patterns' ) );

		update_post_meta( $post_id, 'vbp-init-post-type', '<script>alert("xss")</script>' );
		$value = get_post_meta( $post_id, 'vbp-init-post-type', true );

		// sanitize_text_field should strip HTML tags.
		$this->assertStringNotContainsString( '<script>', $value );
	}

	/**
	 * Regression test for GitHub issue #275.
	 * The block editor saves via the REST API, so $_POST is always empty on
	 * that path. The legacy classic meta box's save_post handler used to
	 * unconditionally delete the vbp-init-* meta whenever $_POST did not
	 * contain the field, wiping out the settings saved by the new sidebar
	 * panel on every save. This test guards against that regression by
	 * firing save_post with an empty $_POST (simulating a REST save) and
	 * asserting the previously saved meta is still intact afterwards.
	 * GitHub issue #275 の回帰テスト。
	 * ブロックエディタは REST API 経由で保存するため、その経路では常に
	 * $_POST が空になる。かつて存在した旧式クラシックメタボックスの
	 * save_post ハンドラは、$_POST にフィールドが無い場合に vbp-init-* メタ
	 * を無条件で削除しており、新しいサイドバーパネルで保存した設定が保存の
	 * たびに消えてしまっていた。$_POST を空にした状態（REST 保存を模した
	 * 状態）で save_post を発火させ、事前に保存しておいたメタが保存後も
	 * そのまま残っていることを確認し、この不具合の再発を防ぐ。
	 *
	 * @return void
	 */
	public function test_meta_survives_save_post_with_empty_post_array() {
		$post_id = $this->factory->post->create( array( 'post_type' => 'vk-block-patterns' ) );

		update_post_meta( $post_id, 'vbp-init-post-type', 'post' );
		update_post_meta( $post_id, 'vbp-init-pattern-add-method', 'add' );

		// $_POST を空にして、ブロックエディタの REST 保存（$_POST を伴わない
		// 保存）を模した状態で save_post フックを発火させる.
		// Empty $_POST to simulate a block editor REST save (a save without $_POST),
		// then fire the save_post hook.
		$_POST = array();
		do_action( 'save_post', $post_id, get_post( $post_id ), true );

		$this->assertEquals( 'post', get_post_meta( $post_id, 'vbp-init-post-type', true ) );
		$this->assertEquals( 'add', get_post_meta( $post_id, 'vbp-init-pattern-add-method', true ) );
	}
}
