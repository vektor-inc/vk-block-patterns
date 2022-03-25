/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';
import { render } from '@wordpress/element';
import { subscribe } from '@wordpress/data';
import { getLocaleData } from '@wordpress/i18n';
import { ExternalLink } from '@wordpress/components';

import './style.scss';
/*globals vkpOptions */

const PatternsLink = () => {
	return (
		<ExternalLink href="https://patterns.vektor-inc.co.jp/">
			VK パターンライブラリ
		</ExternalLink>
	);
};

domReady( () => {
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

		if ( ! headerToolbar.querySelector( '.vk-patterns-header-toolbar' ) ) {
			render( <PatternsLink />, patternsLinkArea );
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
