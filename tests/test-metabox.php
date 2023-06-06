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
}
