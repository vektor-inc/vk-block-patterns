<?php
/**
 * Class GetOptionsTest
 *
 * @package vk-block-patterns
 */

class GetOptionsTest extends WP_UnitTestCase {

	public function test_vbp_get_options() {
		// オプション値の追加などがあった場合は $test_data の配列の中のデータを追加してテストを追加してください.
		$test_data = array(
			array(
				'option'  => array(),
				'correct' => array(
					'role'                 => 'author',
					'showPatternsLink'     => true,
					'VWSMail'              => '',
					'disableCorePattern'   => true,
					'disablePluginPattern' => false,
					'disableXT9Pattern'    => false,
					'account-check'        => array(
						'date'                   => null,
						'disable-empty-notice'   => false,
						'disable-invalid-notice' => false,
						'disable-free-notice'    => false,
					),
					'last-pattern-cached'  => null
					'savePluginData'       => false,
				),
			),
			array(
				'option'  => array(
					'role' => 'author',
				),
				'correct' => array(
					'role'                 => 'author',
					'showPatternsLink'     => true,
					'VWSMail'              => '',
					'disableCorePattern'   => true,
					'disablePluginPattern' => false,
					'disableXT9Pattern'    => false,
					'account-check'        => array(
						'date'                   => null,
						'disable-empty-notice'   => false,
						'disable-invalid-notice' => false,
						'disable-free-notice'    => false,
					),
					'last-pattern-cached'  => null
					'savePluginData'       => false,
				),
			),
			array(
				'option'  => array(
					'role'             => 'editor',
					'showPatternsLink' => false,
				),
				'correct' => array(
					'role'                 => 'editor',
					'showPatternsLink'     => false,
					'VWSMail'              => '',
					'disableCorePattern'   => true,
					'disablePluginPattern' => false,
					'disableXT9Pattern'    => false,
					'account-check'        => array(
						'date'                   => null,
						'disable-empty-notice'   => false,
						'disable-invalid-notice' => false,
						'disable-free-notice'    => false,
					),
					'last-pattern-cached'  => null
					'savePluginData'       => false,
				),
			),
			// X-T9 のパターン読み込みパラメーター追加.
			// https://github.com/vektor-inc/vk-block-patterns/pull/132
			array(
				'option'  => array(
					'role'                 => 'editor',
					'showPatternsLink'     => false,
					'VWSMail'              => '',
					'disableCorePattern'   => false,
					'disablePluginPattern' => true,
					'account-check'        => array(
						'date'                   => null,
						'disable-empty-notice'   => false,
						'disable-invalid-notice' => false,
						'disable-free-notice'    => false,
					),
				),
				'correct' => array(
					'role'                 => 'editor',
					'showPatternsLink'     => false,
					'VWSMail'              => '',
					'disableCorePattern'   => false,
					'disablePluginPattern' => true,
					'disableXT9Pattern'    => false,
					'account-check'        => array(
						'date'                   => null,
						'disable-empty-notice'   => false,
						'disable-invalid-notice' => false,
						'disable-free-notice'    => false,
					),
					'last-pattern-cached'  => null
					'savePluginData'       => false,
				),
			),
			array(
				'option'  => array(
					'role'                 => 'editor',
					'showPatternsLink'     => false,
					'VWSMail'              => '',
					'disableCorePattern'   => true,
					'disablePluginPattern' => true,
          'disableXT9Pattern'    => false,
					'account-check'        => array(
						'date'                   => null,
						'disable-empty-notice'   => false,
						'disable-invalid-notice' => false,
						'disable-free-notice'    => false,
					'last-pattern-cached'  => '2022-11-11 11:11',
					'savePluginData'       => true,
				),
				'correct' => array(
					'role'                 => 'editor',
					'showPatternsLink'     => false,
					'VWSMail'              => '',
					'disableCorePattern'   => true,
					'disablePluginPattern' => true,
					'disableXT9Pattern'    => false,
					'account-check'        => array(
						'date'                   => null,
						'disable-empty-notice'   => false,
						'disable-invalid-notice' => false,
						'disable-free-notice'    => false,
					),
					'last-pattern-cached'  => '2022-11-11 11:11',
					'savePluginData'       => true,
				),
			),
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'vbp_get_options()' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		foreach ( $test_data as $test_value ) {

			if ( ! isset( $test_value['option'] ) ) {
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
