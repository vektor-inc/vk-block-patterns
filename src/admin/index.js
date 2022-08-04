import { __, _x, getLocaleData } from '@wordpress/i18n';
import { render, useState } from '@wordpress/element';
import {
	ToggleControl,
	Button,
	SelectControl,
	TextControl,
	Spinner,
} from '@wordpress/components';
import api from '@wordpress/api';
/*globals vkpOptions */

import './style.scss';

// Adminコンポーネント
const Admin = () => {

	// PHPから受け取った値 boolean は 空 '' false または 1 true を渡すので true,false に整形
	const defaultShowPatternsLink =
		vkpOptions.showPatternsLink === '1' ? true : false;
	const defaultDisableCorePattern =
		vkpOptions.disableCorePattern === '1' ? true : false;
	const defaultDisablePluginPattern =
		vkpOptions.disablePluginPattern === '1' ? true : false;	
	const [ vkpOption, setVkpOption ] = useState( {
		role: vkpOptions.role,
		showPatternsLink: defaultShowPatternsLink,
		VWSMail: vkpOptions.VWSMail,
		disableCorePattern: defaultDisableCorePattern,
		disablePluginPattern: defaultDisablePluginPattern,
	} );

	const updateOptionValue = ( newValue ) => {
		setVkpOption( newValue );
	};

	const [ isLoading, setIsLoading ] = useState( false );
	const [ isSaveSuccess, setIsSaveSuccess ] = useState( '' );

	// オプション値を保存
	const onClickUpdate = () => {
		setIsLoading( true );
		api.loadPromise.then( (/*response*/) => {
			// console.log( response );
			const model = new api.models.Settings( {
				vk_block_patterns_options: vkpOption,
			} );
			const save = model.save();

			save.success( (/* response, status */) => {
				// console.log( response );
				// console.log( status );
				setTimeout( () => {
					setIsLoading( false );
					setIsSaveSuccess( true );
				}, 600 );
			} );

			save.error( () => {
				setTimeout( () => {
					setIsLoading( false );
					setIsSaveSuccess( false );
				}, 600 );
			} );
		} );
	};

	// 言語設定を取得
	const lang = getLocaleData()[ '' ].lang;
	// パターン管理画面URL
	const patternPostTypeUrl = vkpOptions.adminUrl + 'edit.php?post_type=vk-block-patterns';
	return (
		<>
			<div>
				<section>
				<h3 id="role-setting">
					{ __( 'Role Setting', 'vk-block-patterns' ) }
				</h3>
				<p>
					{__( 'User permission to register patterns in VK Block Patterns', 'vk-block-patterns' )} [ <a href={patternPostTypeUrl}>VK Block Patterns</a> ]
				</p>
				<SelectControl
					value={ vkpOption.role }
					onChange={ ( newValue ) => {
						updateOptionValue( { ...vkpOption, role: newValue } );
					} }
					options={ [
						{
							label: __(
								'Contributor or higher',
								'vk-block-patterns'
							),
							value: 'contributor',
						},
						{
							label: __(
								'Author or higher',
								'vk-block-patterns'
							),
							value: 'author',
						},
						{
							label: __(
								'Editor or higher',
								'vk-block-patterns'
							),
							value: 'editor',
						},
						{
							label: __(
								'Administrator only',
								'vk-block-patterns'
							),
							value: 'administrator',
						},
					] }
				/>
				<h3 id="default-patterns-setting">
					{ __( 'Default Patterns Setting', 'vk-block-patterns' ) }
				</h3>
				<ToggleControl
					label={ __(
						'Disable WordPress Core Patterns',
						'vk-block-patterns'
					) }
					checked={ vkpOption.disableCorePattern }
					onChange={ ( newValue ) => {
						updateOptionValue( {
							...vkpOption,
							disableCorePattern: newValue,
						} );
					} }
				/>
				<ToggleControl
					label={ __(
						'Disable Patterns of This Plugin',
						'vk-block-patterns'
					) }
					checked={ vkpOption.disablePluginPattern }
					onChange={ ( newValue ) => {
						updateOptionValue( {
							...vkpOption,
							disablePluginPattern: newValue,
						} );
					} }
				/>
				</section>
				{ lang === 'ja_JP' && (
					<>
						<h3 id="pattern-library-setting">
							{ __( 'VK Pattern Library Setting', 'vk-block-patterns' ) }
						</h3>

						<section>
							<h4>
								{ __( 'VWS account linkage', 'vk-block-patterns' ) }
							</h4>
							{ /* 日本語向けの案内なのと翻訳挟んでのリンク処理が難しいので日本語ママ */}
							<p><a href="https://vws.vektor-inc.co.jp/product/lightning-g3-pro-pack?ref=vkbp-admin" target="_blank">Lightning G3 Pro Pack</a> のライセンスをお持ちのユーザーは、<a href="https://vws.vektor-inc.co.jp/my-account" target="_blank">アカウント</a>のメールアドレスを登録してください。<br />
							VK Pattern Library でお気に入りに登録したパターンをエディター上で直接呼び出す事ができます。</p>
							<TextControl
								type="email"
								className="vws-mail-address"
								label={ __(
									'VWS Account email address',
									'vk-block-patterns'
								) }
								value={ vkpOption.VWSMail }
								onChange={ ( newValue ) => {
									updateOptionValue( {
										...vkpOption,
										VWSMail: newValue,
									} );
								} }
							/>
							<p>[ <a href="https://patterns.vektor-inc.co.jp/about/about-favorite/" target="_blank">{ __( 'Click here for more information on Favorites', 'vk-block-patterns' ) }</a> ]</p>
						</section>

						<section>
							<h4>
								{ __( 'Editor Setting', 'vk-block-patterns' ) }
							</h4>
							<ToggleControl
								label={ __(
									'Show VK Pattern Library link in editor toolbar',
									'vk-block-patterns'
								) }
								checked={ vkpOption.showPatternsLink }
								onChange={ ( newValue ) => {
									updateOptionValue( {
										...vkpOption,
										showPatternsLink: newValue,
									} );
								} }
							/>
						</section>
					</>
				) }
				<Button
					isPrimary
					onClick={ onClickUpdate }
					isBusy={ isLoading }
				>
					{__( 'Save setting', 'vk-block-patterns' ) }
				</Button>
				{ isLoading && <Spinner /> }
				{ isSaveSuccess === false &&
					__( 'Failed to save.', 'vk-block-patterns' ) }
			</div>
		</>
	);
};
render( <Admin />, document.getElementById( 'vk_block_patterns_admin' ) );
