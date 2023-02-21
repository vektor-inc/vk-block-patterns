<?php
/**
 * Class UninstallTest
 *
 * @package vk-block-patterns
 */

class UninstallTest extends WP_UnitTestCase {

	public function test_delete_option() {
		// オプション値の追加などがあった場合は $test_data の配列の中のデータを追加してテストを追加してください.
		$test_data = array(
            // savePluginDataが空の場合
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
				'correct' => false
			),
            // savePluginDataが false の場合
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
                    'savePluginData'       => false,
				),
				'correct' => false
			),
            // オプションが空の場合
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
                    'savePluginData'     => true,
				),
				'correct' => array(
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
                    'savePluginData'     => true,
				),
			),
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'vbp_uninstall' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		foreach ( $test_data as $test_value ) {

			if ( ! isset( $test_value['option'] ) ) {
				delete_option( 'vk_block_patterns_options' );
			} else {
				update_option( 'vk_block_patterns_options', $test_value['option'] );
			}

            vbp_uninstall();
			$return  = get_option( 'vk_block_patterns_options' );
			$correct = $test_value['correct'];

			print 'return  :'. PHP_EOL; 
            var_dump( $return ). PHP_EOL;
			print 'correct :' . PHP_EOL;
            var_dump( $correct ) . PHP_EOL;
			$this->assertEquals( $correct, $return );

		}
	}
}
