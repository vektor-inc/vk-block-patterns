<?php
/**
 * Class AddMetaBoxTest
 *
 * @package vektor-inc/vk-block-patterns
 */

/**
 * AddMetaBoxTest
 */
class AddMetaBoxTest extends WP_UnitTestCase {

	/**
	 * AddMetaBox::is_method_selected test.
	 */
	public function test_is_method_selected() {

		$tests = array(
			'new_post'         => array(
				'saved_post_type'  => '',
				'saved_add_method' => '',
				'expected'         => '',
			),
			'before_1_26_post' => array(
				'saved_post_type'  => 'post',
				'saved_add_method' => '',
				'expected'         => 'show',
			),
			'before_1_26_post' => array(
				'saved_post_type' => 'post',
				'expected'        => 'show',
			),
			'before_1_26_post' => array(
				'saved_post_type'  => 'post',
				'saved_add_method' => null,
				'expected'         => 'show',
			),
			'after_1_26_post'  => array(
				'saved_post_type'  => 'page',
				'saved_add_method' => 'add',
				'expected'         => 'add',
			),
		);

		foreach ( $tests as $key => $test ) {
			$actual = VKBlockPatterns\AddMetaBox::is_method_selected( $test['saved_post_type'], $test['saved_add_method'] );
			$this->assertEquals( $test['expected'], $actual );

		}
	}

	/**
	 * Current_screen が null の時は何もエンキューしない。
	 * Does nothing when current_screen is null.
	 *
	 * @return void
	 */
	public function test_enqueue_scripts_guard_no_screen() {
		$GLOBALS['current_screen'] = null;
		VKBlockPatterns\AddMetaBox::enqueue_scripts();
		$this->assertFalse( wp_style_is( 'vk-block-patterns-editor', 'enqueued' ) );
	}

	/**
	 * Is_block_editor が false の時は何もエンキューしない。
	 * Does nothing when is_block_editor is false.
	 *
	 * @return void
	 */
	public function test_enqueue_scripts_guard_not_block_editor() {
		$screen                    = WP_Screen::get( 'widgets' );
		$screen->is_block_editor   = false;
		$GLOBALS['current_screen'] = $screen;
		VKBlockPatterns\AddMetaBox::enqueue_scripts();
		$this->assertFalse( wp_style_is( 'vk-block-patterns-editor', 'enqueued' ) );
		$GLOBALS['current_screen'] = null;
	}

	/**
	 * Post_type が空の時は何もエンキューしない。
	 * Does nothing when post_type is empty.
	 *
	 * @return void
	 */
	public function test_enqueue_scripts_guard_empty_post_type() {
		$screen                    = WP_Screen::get( 'widgets' );
		$screen->is_block_editor   = true;
		$GLOBALS['current_screen'] = $screen;
		VKBlockPatterns\AddMetaBox::enqueue_scripts();
		$this->assertFalse( wp_style_is( 'vk-block-patterns-editor', 'enqueued' ) );
		$GLOBALS['current_screen'] = null;
	}
}
