/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';
import { createRoot } from 'react-dom/client';
import { subscribe } from '@wordpress/data';
import { getLocaleData } from '@wordpress/i18n';
import { ToolbarButton } from '@wordpress/components';

import './style.scss';
/* globals vkpOptions */

const PatternsLink = () => {
	return (
		<ToolbarButton
			label="VK Pattern Library" // Accessibility label
			className="components-button is-secondary"
			onClick={ () => window.open( 'https://patterns.vektor-inc.co.jp/', '_blank' ) }
		>
			VK Pattern Library 
			<span className="dashicons dashicons-external"></span>
		</ToolbarButton>
	);
};

domReady( () => {
	// vkpOptions は wp_localize_script() で送られてきている
	const { showPatternsLink } = vkpOptions;
	if ( ! showPatternsLink ) {
		return;
	}

	// 言語設定を取得
	// console.log(getLocaleData());
	const lang = getLocaleData()[ '' ].lang;
	if ( lang !== 'ja_JP' ) {
		return;
	}

	const buttonRender = () => {
		const headerToolbar =
			document.querySelector( '.edit-post-header-toolbar' ) ||
			document.querySelector( '.edit-site-header__toolbar' );
		if ( ! headerToolbar ) {
			return;
		}

		const patternsLinkArea = document.createElement( 'div' );
		patternsLinkArea.classList.add( 'vk-patterns-header-toolbar' );

		// .vk-patterns-header-toolbar がまだ存在しなかったら
		if ( ! headerToolbar.querySelector( '.vk-patterns-header-toolbar' ) ) {
			// .vk-patterns-header-toolbar 内にリンク追加
			const root = createRoot(patternsLinkArea);
			root.render( <PatternsLink />);
			// ツールバーの子要素の最後にパターンへのリンクを追加
			headerToolbar.appendChild( patternsLinkArea );
		}
	};

	// 何かしらの変更があったら発火
	// https://developer.wordpress.org/block-editor/reference-guides/packages/packages-data/
	subscribe( () => {
		// console.log('subscribe');
		if ( ! document.querySelector( '.vk-patterns-header-toolbar' ) ) {
			buttonRender();
		}
	} );
} );
