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
import { __ } from '@wordpress/i18n';

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
		{
			label: __( 'Unspecified', 'vk-block-patterns' ),
			value: 'unspecified',
		},
		{ label: __( 'Auto add', 'vk-block-patterns' ), value: 'add' },
		{
			label: __( 'Show in Candidate', 'vk-block-patterns' ),
			value: 'show',
		},
	];

	return (
		<PluginDocumentSettingPanel
			name="vbp-init-pattern"
			title={ __(
				'Initial pattern setting',
				'vk-block-patterns'
			) }
			className="vbp-init-pattern-panel"
		>
			<p style={ { fontSize: '12px', color: '#757575' } }>
				{ __(
					'You can set this pattern as the default pattern for a specific post type.',
					'vk-block-patterns'
				) }
			</p>

			<SelectControl
				label={ __( 'Target Post Type.', 'vk-block-patterns' ) }
				value={ savedPostType }
				options={ postTypeOptions }
				onChange={ ( value ) =>
					updateMeta( 'vbp-init-post-type', value )
				}
			/>

			<SelectControl
				label={ __(
					'How to Add Patterns.',
					'vk-block-patterns'
				) }
				value={ effectiveAddMethod }
				options={ addMethodOptions }
				onChange={ ( value ) =>
					updateMeta( 'vbp-init-pattern-add-method', value )
				}
			/>

			<p style={ { fontSize: '12px', color: '#757575' } }>
				{ __(
					'If there are multiple patterns with "Auto Add" selected for one post type, only the oldest pattern will be inserted.',
					'vk-block-patterns'
				) }
			</p>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( 'vbp-init-pattern-panel', {
	render: VbpInitPatternPanel,
} );
