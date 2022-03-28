<?php
/**
 * Class GetOptionsTest
 *
 * @package vk-block-patterns
 */

class GetOptionsTest extends WP_UnitTestCase {

	public function test_vbp_get_options() {
		$test_data = array(
			array(
				'option'  => null,
				'correct' => array(
					'role' => 'author',
					'showPatternsLink' => true,
				),
			),
			array(
				'option'  => array(
					'role' => 'editor',
					'showPatternsLink' => false,
				),
				'correct' => array(
					'role' => 'editor',
					'showPatternsLink' => false,
				),
			),
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'vbp_get_options()' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		foreach ( $test_data as $test_value ) {

			if ( empty( $test_value['option'] ) ){
				delete_option( 'vk_block_patterns_options' );
			} else {
				update_option( 'vk_block_patterns_options', $test_value['option'] );
			}

			$return  = vbp_get_options();
			$correct = $test_value['correct'];

			print 'return[\'role\']  :' . $return['role'] . PHP_EOL;
			print 'correct[\'role\'] :' . $correct['role'] . PHP_EOL;
			print 'return[\'showPatternsLink\']  :' . $return['showPatternsLink'] . PHP_EOL;
			print 'correct[\'showPatternsLink\'] :' . $correct['showPatternsLink'] . PHP_EOL;
			$this->assertEquals( $correct, $return );

		}
	}
}
