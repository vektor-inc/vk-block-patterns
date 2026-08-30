<?php
/**
 * Class EditorPanelAssetsTest
 *
 * @package vektor-inc/vk-block-patterns
 */

/**
 * EditorPanelAssetsTest
 */
class EditorPanelAssetsTest extends WP_UnitTestCase {

	/**
	 * Saved current_screen value restored after each test.
	 *
	 * @var WP_Screen|null
	 */
	private $_original_screen;

	/**
	 * Save the original current_screen before each test.
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		$this->_original_screen = isset( $GLOBALS['current_screen'] )
			? $GLOBALS['current_screen']
			: null;
	}

	/**
	 * Restore current_screen and dequeue test styles after each test.
	 *
	 * @return void
	 */
	public function tearDown(): void {
		$GLOBALS['current_screen'] = $this->_original_screen;
		wp_dequeue_style( 'vk-block-patterns-editor' );
		wp_deregister_style( 'vk-block-patterns-editor' );
		parent::tearDown();
	}

	/**
	 * Is_block_editor が true かつ post_type がある時に style がエンキューされる。
	 * Enqueues style when is_block_editor is true and post_type is set.
	 *
	 * @return void
	 */
	public function test_enqueue_scripts_happy_path() {
		$screen                    = WP_Screen::get( 'post' );
		$screen->is_block_editor   = true;
		$screen->post_type         = 'post';
		$GLOBALS['current_screen'] = $screen;
		VKBlockPatterns\EditorPanelAssets::enqueue_scripts();
		$this->assertTrue( wp_style_is( 'vk-block-patterns-editor', 'enqueued' ) );
	}

	/**
	 * Current_screen が null の時は何もエンキューしない。
	 * Does nothing when current_screen is null.
	 *
	 * @return void
	 */
	public function test_enqueue_scripts_guard_no_screen() {
		$GLOBALS['current_screen'] = null;
		VKBlockPatterns\EditorPanelAssets::enqueue_scripts();
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
		VKBlockPatterns\EditorPanelAssets::enqueue_scripts();
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
		VKBlockPatterns\EditorPanelAssets::enqueue_scripts();
		$this->assertFalse( wp_style_is( 'vk-block-patterns-editor', 'enqueued' ) );
		$GLOBALS['current_screen'] = null;
	}
}
