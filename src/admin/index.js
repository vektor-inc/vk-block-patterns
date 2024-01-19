import { __, _x, getLocaleData } from '@wordpress/i18n';
import { render, useState, useEffect } from '@wordpress/element';
import {
	ToggleControl,
	Button,
	SelectControl,
	TextControl,
	Spinner,
	Snackbar,
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
	const savePluginData =
		vkpOptions.savePluginData === '1' ? true : false;

	const [ vkpOption, setVkpOption ] = useState( {
		role: vkpOptions.role,
		showPatternsLink: defaultShowPatternsLink,
		VWSMail: vkpOptions.VWSMail,
		disableCorePattern: defaultDisableCorePattern,
		disablePluginPattern: defaultDisablePluginPattern,
		savePluginData: savePluginData
	} );
	const ajaxUrl  =  vkpOptions.ajaxUrl;
	const updateOptionValue = ( newValue ) => {
		setVkpOption( newValue );
	};

	const [ isLoading, setIsLoading ] = useState( false );
	const [ isSaveSuccess, setIsSaveSuccess ] = useState( '' );
	const [ isClearing, setIsClearing ] = useState( false );
	const [ isCleared, setIsCleared ] = useState( '' );
	const [ isReload, setIsReload ] = useState( false );

	// パターンのキャッシュをクリア
	const clearPatternsCache = () => {
		setIsClearing( true );

		// ajax を使う時の定型文的な...
		const req = new XMLHttpRequest();
		// ajax で POST して PHPにわたす
		req.open('POST', ajaxUrl, true);
		req.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
		// アクションフックのポイント（PHP側でキャッシュをクリアする処理が走る）
		req.send(`action=clear_patterns_cache&vbp_clear_patterns_cache_nonce=${vkpOptions.nonce}`);

		setIsClearing( false );
		setIsCleared( true );
	}

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
				if ( isReload === true ) {
					clearPatternsCache();
					location.reload();
				}
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
	const patternPostTypeUrl =
		vkpOptions.adminUrl + 'edit.php?post_type=vk-block-patterns';
	const template = vkpOptions.template;

	

	// snackbar更新する
	useEffect(() => {
		if (isSaveSuccess) {
			setTimeout(() => {
				setIsSaveSuccess();
			}, 3000);
		}
	}, [isSaveSuccess]);

	// snackbar更新する
	useEffect(() => {
		if (isCleared) {
			setTimeout(() => {
				setIsCleared();
			}, 3000);
		}
	}, [isCleared]);

	return (
		<>
			<div>
				<section>
					<h3 id="role-setting">
						{ __( 'Role Setting', 'vk-block-patterns' ) }
					</h3>
					<p>
						{ __(
							'User permission to register patterns in VK Block Patterns',
							'vk-block-patterns'
						) }{ ' ' }
						[ <a href={ patternPostTypeUrl }>VK Block Patterns</a> ]
					</p>
					<SelectControl
						value={ vkpOption.role }
						onChange={ ( newValue ) => {
							updateOptionValue( {
								...vkpOption,
								role: newValue,
							} );
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
				</section>
				<section>
					<h3 id="default-patterns-setting">
						{ __( 'Default Pattern Setting', 'vk-block-patterns' ) }
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
							'Disable default patterns of this plugin',
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
					{ lang === 'ja_JP' &&
						vkpOption.VWSMail !== '' &&
						template === 'x-t9' && (
							<>
								<ToggleControl
									label={ __(
										'Disable X-T9 patterns',
										'vk-block-patterns'
									) }
									checked={ vkpOption.disableXT9Pattern }
									onChange={ ( newValue ) => {
										updateOptionValue( {
											...vkpOption,
											disableXT9Pattern: newValue,
										} );
									} }
								/>
							</>
						) }
				</section>
				{ lang === 'ja_JP' && (
					<>
						<h3 id="pattern-library-setting">
							{ __(
								'VK Pattern Library Setting',
								'vk-block-patterns'
							) }
						</h3>

						<section>
							{ /* 日本語向けの案内 & 翻訳挟んでのリンク処理が難しい & 翻訳関数挟むと多国語の人が翻訳しようとする時に疑問に思われるので日本語ママ */ }
							<h4>アカウント連携</h4>
							<p>
								<a
									href="https://vws.vektor-inc.co.jp/vektor-passport?ref=vkbp-admin"
									target="_blank"
									rel="noreferrer"
								>
									Vektor Passport
								</a>{ ' ' }
								あるいは Lightning G3 Pro Pack のライセンスをお持ちのユーザーは{ ' ' }
								<a
									href="https://patterns.vektor-inc.co.jp/"
									target="_blank"
									rel="noreferrer"
								>
									VK Pattern Library
								</a>{ ' ' }
								でお気に入りに登録したパターンをエディター上で直接呼び出す事ができます。
							</p>
							<p>
								お気に入り登録・連携を利用するには VK Pattern
								Library
								のユーザーアカウントを発行する必要があります。
								<br />[{ ' ' }
								<a
									href="https://patterns.vektor-inc.co.jp/about/about-favorite/"
									target="_blank"
									rel="noreferrer"
								>
									{ __(
										'Click here for more information on Favorites',
										'vk-block-patterns'
									) }
								</a>{ ' ' }
								]
							</p>
							<TextControl
								type="email"
								className="vws-mail-address"
								label={
									'VK Pattern Library のアカウントのメールアドレス'
								}
								value={ vkpOption.VWSMail }
								onChange={ ( newValue ) => {
									updateOptionValue( {
										...vkpOption,
										VWSMail: newValue,
									} );
									setIsReload( true );
								} }
							/>
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

						<section>
							<h4>
								{ __( 'Uninstall Setting', 'vk-block-patterns' ) }
							</h4>
							<ToggleControl
								label={ __(
									'When Uninstall This Plugin, Save Data of This Plugin',
									'vk-block-patterns'
								) }
								checked={ vkpOption.savePluginData }
								onChange={ ( newValue ) => {
									updateOptionValue( {
										...vkpOption,
										savePluginData: newValue,
									} );
								} }
							/>
						</section>

						<section>
							<h3 id="cache-setting">
								{ __( 'Patterns data cache setting', 'vk-block-patterns' ) }
							</h3>
							<p>{ __( 'If the VK Pattern Library data is old, please try clearing the cache.', 'vk-block-patterns' ) }</p>
							<Button
								isSecondary
								onClick={ clearPatternsCache }
								isBusy={ isClearing }
							>
								{ __( 'Clear Cache', 'vk-block-patterns' ) }
							</Button>
							{ isClearing && <Spinner /> }
							{ isCleared === true && (
								<div>
									<Snackbar>
										{ __( 'Cache cleared', 'vk-block-patterns'  ) }{ ' ' }
									</Snackbar>
								</div>
							) }
						</section>
					</>
				) }
				<Button
					isPrimary
					onClick={ onClickUpdate }
					isBusy={ isLoading }
				>
					{ __( 'Save setting', 'vk-block-patterns' ) }
				</Button>
				{ isLoading && <Spinner /> }
				{ isSaveSuccess === true && (
					<div>
						<Snackbar>
							{ __( 'Save Success', 'vk-block-patterns'  ) }{ ' ' }
						</Snackbar>
					</div>
				) }
				{ isSaveSuccess === false && (
					<div>
						<Snackbar>
							{ __( 'Failed to save.', 'vk-block-patterns'  ) }{ ' ' }
						</Snackbar>
					</div>
				) }
			</div>
		</>
	);
};
render( <Admin />, document.getElementById( 'vk_block_patterns_admin' ) );
