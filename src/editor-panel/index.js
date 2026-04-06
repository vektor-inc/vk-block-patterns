/**
 * VK Block Patterns - Block Editor Panel
 * ブロックエディタ用サイドバーパネル
 *
 * Replaces the legacy add_meta_box() with PluginDocumentSettingPanel
 * so that WordPress 7.0 RTC (Real-Time Collaboration) is not blocked.
 * レガシーなadd_meta_box()をPluginDocumentSettingPanelに置き換え、
 * WordPress 7.0 RTC（リアルタイム共同編集）がブロックされないようにする。
 */

import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';

// Use translated strings from PHP via wp_localize_script.
// PHPからwp_localize_scriptで渡された翻訳済み文字列を使用する。
const i18n = window.vbpEditor?.i18n || {};

/**
 * Initial Pattern Setting panel component for the block editor sidebar.
 * ブロックエディタサイドバー用の初期パターン設定パネルコンポーネント。
 *
 * @return {JSX.Element|null} The panel element or null if not vk-block-patterns post type.
 */
const VbpInitPatternPanel = () => {
	const postType = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostType(),
		[]
	);

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	// Only show panel for vk-block-patterns post type.
	// vk-block-patterns 投稿タイプでのみパネルを表示する。
	if ( postType !== 'vk-block-patterns' ) {
		return null;
	}

	const savedPostType = meta?.[ 'vbp-init-post-type' ] ?? '';
	const savedAddMethod = meta?.[ 'vbp-init-pattern-add-method' ] ?? '';

	// Backward compatibility: if post type is set but add method is empty, default to 'show'.
	// 後方互換: 投稿タイプのみ保存されていて追加方法が空の場合、自動的に show にする。
	let effectiveAddMethod = 'unspecified';
	if ( savedPostType && ! savedAddMethod ) {
		effectiveAddMethod = 'show';
	} else if ( savedAddMethod ) {
		effectiveAddMethod = savedAddMethod;
	}

	const updateMeta = ( key, value ) => {
		setMeta( { ...meta, [ key ]: value } );
	};

	// Build post type options from data passed via wp_localize_script.
	// wp_localize_script で渡されたデータから投稿タイプの選択肢を作成する。
	const postTypeOptions =
		window.vbpEditor?.postTypes
			? Object.entries( window.vbpEditor.postTypes ).map(
					( [ name, data ] ) => ( {
						label: data.label || name,
						value: name,
					} )
			  )
			: [];

	const addMethodOptions = [
		{ label: i18n.unspecified || 'Unspecified', value: 'unspecified' },
		{ label: i18n.autoAdd || 'Auto add', value: 'add' },
		{ label: i18n.showInCandidate || 'Show in Candidate', value: 'show' },
	];

	return (
		<PluginDocumentSettingPanel
			name="vbp-init-pattern"
			title={ i18n.panelTitle || 'Initial pattern setting' }
			className="vbp-init-pattern-panel"
		>
			<p style={ { fontSize: '12px', color: '#757575' } }>
				{ i18n.description || '' }
			</p>

			<SelectControl
				label={ i18n.targetPostType || 'Target Post Type.' }
				value={ savedPostType }
				options={ postTypeOptions }
				onChange={ ( value ) =>
					updateMeta( 'vbp-init-post-type', value )
				}
			/>

			<SelectControl
				label={ i18n.howToAddPatterns || 'How to Add Patterns.' }
				value={ effectiveAddMethod }
				options={ addMethodOptions }
				onChange={ ( value ) =>
					updateMeta( 'vbp-init-pattern-add-method', value )
				}
			/>

			<p style={ { fontSize: '12px', color: '#757575' } }>
				{ i18n.multiplePatterns || '' }
			</p>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( 'vbp-init-pattern-panel', {
	render: VbpInitPatternPanel,
} );
