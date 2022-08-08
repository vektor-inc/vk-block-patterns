<?php
/**
 * Class AlertTest
 *
 * @package vk-block-patterns
 */

class AlertTest extends WP_UnitTestCase {

	public function test_vbp_vws_alert() {
		
		$alerts       = vbp_vws_alert_list();
		$test_data = array(
			/* VWS 連携が未入力の場合 */
			// デフォルトの状態
			array(
				'option'  => array(
					'VWSMail'                    => '',
					'account-check' => array(
						'date'                   => null,
						'disable-empty-notice'   => false,
					),
				),
				'correct' => $alerts['empty-user'],
			),
			// 無効化されてから１年経過前
			array(
				'option'  => array(
					'VWSMail'                    => '',
					'account-check' => array(
						'date'                 => date( 'Y-m-d H:i:s', strtotime("-11 month") ),
						'disable-empty-notice' => true,
					),
				),
				'correct' => '',
			),
			// 無効化されてから１年経過後
			array(
				'option'  => array(
					'VWSMail'                    => '',
					'account-check'        => array(
						'date'                 => date( 'Y-m-d H:i:s', strtotime("-13 month") ),
						'disable-empty-notice' => true,
					),
				),
				'correct' => '',
			),
			/* VWS 連携が無効な値を返したの場合 */
			// デフォルトの状態
			array(
				'option'  => array(
					'VWSMail'                    => 'vk-support@vektor-inc.co.jp',
					'account-check' => array(
						'date'                   => null,
						'disable-invalid-notice' => false,
					),
				),
				'api' => array(
					'role' => 'invalid-user'
				),
				'correct' => $alerts['invalid-user'],
			),
			// 無効化されてから１年経過前
			array(
				'option'  => array(
					'VWSMail'                    => 'vk-support@vektor-inc.co.jp',
					'account-check' => array(
						'date'                   => date( 'Y-m-d H:i:s', strtotime("-11 month") ),
						'disable-invalid-notice' => true,
					),
				),
				'api' => array(
					'role' => 'invalid-user'
				),
				'correct' => '',
			),
			// 無効化されてから１年経過後
			array(
				'option'  => array(
					'VWSMail'                    => 'vk-support@vektor-inc.co.jp',
					'account-check' => array(
						'date'                   => date( 'Y-m-d H:i:s', strtotime("-13 month") ),
						'disable-invalid-notice' => true,
					),
				),
				'api' => array(
					'role' => 'invalid-user'
				),
				'correct' => $alerts['invalid-user'],
			),
			/* VWS 連携が無料版ユーザーを検出した場合 */
			// デフォルトの状態
			array(
				'option'  => array(
					'VWSMail'                    => 'vk-support@vektor-inc.co.jp',
					'account-check' => array(
						'date'                => null,
						'disable-free-notice' => false,
					),
				),
				'api' => array(
					'role' => 'free-user'
				),
				'correct' => $alerts['free-user'],
			),
			// 無効化されてから１年経過前
			array(
				'option'  => array(
					'VWSMail'                    => 'vk-support@vektor-inc.co.jp',
					'account-check' => array(
						'date'                => date( 'Y-m-d H:i:s', strtotime("-11 month") ),
						'disable-free-notice' => true,
					),
				),
				'api' => array(
					'role' => 'free-user'
				),
				'correct' => '',
			),
			// 無効化されてから１年経過後
			array(
				'option'  => array(
					'VWSMail'                    => 'vk-support@vektor-inc.co.jp',
					'account-check' => array(
						'date'                => date( 'Y-m-d H:i:s', strtotime("-13 month") ),
						'disable-free-notice' => true,
					),
				),
				'api' => array(
					'role' => 'free-user'
				),
				'correct' => $alerts['free-user'],
			),
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'alert()' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		foreach ( $test_data as $test_value ) {;
			if ( empty( $test_value['option'] ) ){
				delete_option( 'vk_block_patterns_options' );
			} else {
				update_option( 'vk_block_patterns_options', $test_value['option'] );
			}
			// 日本語環境でないとテストにならない
			switch_to_locale('ja_JP');
			vbp_admin_control();
			$api = ! empty( $test_value['api'] ) ? $test_value['api'] : array();



			$return  = vbp_vws_alert( $api );
			$correct = $test_value['correct'];

			print 'return:' . $return . PHP_EOL;
			print 'correct :' . $correct . PHP_EOL;
			$this->assertEquals( $correct, $return );

		}
	}
}
