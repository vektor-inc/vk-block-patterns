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
	 * Test backward compatibility: is_method_selected with native panel values.
	 * 後方互換: ネイティブパネルの値で is_method_selected が正しく動作するかテスト。
	 */
	public function test_is_method_selected_with_panel_values() {
		// When post type is set and add method is empty, should default to 'show'.
		$this->assertEquals( 'show', VKBlockPatterns\AddMetaBox::is_method_selected( 'post', '' ) );
		// When both are set, should return the add method.
		$this->assertEquals( 'add', VKBlockPatterns\AddMetaBox::is_method_selected( 'post', 'add' ) );
		// When unspecified is explicitly set.
		$this->assertEquals( 'unspecified', VKBlockPatterns\AddMetaBox::is_method_selected( 'post', 'unspecified' ) );
	}
}
