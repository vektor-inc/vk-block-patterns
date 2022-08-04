import { __, getLocaleData } from '@wordpress/i18n';
import { render, useState } from '@wordpress/element';
import {
	ToggleControl,
	Button,
	SelectControl,
	TextControl,
	Spinner,
	CheckboxControl,
} from '@wordpress/components';
import api from '@wordpress/api';
/*globals vkpOptions */

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

	return (
		<>
			<div>
				<h3 id="role-setting">
					{ __( 'Role Setting', 'vk-block-patterns' ) }
				</h3>
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
				<h3 id="built-in-patterns-setting">
					{ __( 'Build in Patterns Setting', 'vk-block-patterns' ) }
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
				{ lang === 'ja_JP' && (
					<>
						<h3 id="editor-setting">
							{ __( 'Editor Setting', 'vk-block-patterns' ) }
						</h3>
						<ToggleControl
							label={ __(
								'Display a link to the VK Pattern Library on the toolbar',
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
						<h3 id="pattern-library-setting">
							{ __( 'VK Pattern Library Setting', 'vk-block-patterns' ) }
						</h3>
						<TextControl
							type="email"
							label={ __(
								'User Mail Adress on Vektor WordPress Solutions',
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
