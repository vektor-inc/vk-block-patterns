const mockSetMeta = jest.fn();
let mockPluginSettings;

jest.mock(
	'@wordpress/plugins',
	() => ( {
		registerPlugin: ( name, settings ) => {
			mockPluginSettings = settings;
		},
	} ),
	{ virtual: true }
);

jest.mock(
	'@wordpress/editor',
	() => ( {
		PluginDocumentSettingPanel: () => null,
	} ),
	{ virtual: true }
);

jest.mock(
	'@wordpress/components',
	() => ( {
		SelectControl: () => null,
	} ),
	{ virtual: true }
);

jest.mock(
	'@wordpress/data',
	() => ( {
		useSelect: ( mapSelect ) =>
			mapSelect( () => ( {
				getCurrentPostType: () => 'vk-block-patterns',
			} ) ),
	} ),
	{ virtual: true }
);

jest.mock(
	'@wordpress/core-data',
	() => ( {
		useEntityProp: () => [ {}, mockSetMeta ],
	} ),
	{ virtual: true }
);

jest.mock( '@wordpress/element', () => ( {
	useRef: ( value ) => ( { current: value } ),
} ) );

describe( 'initial pattern target post type', () => {
	beforeAll( () => {
		window.vbpEditor = {
			postTypes: {
				post: { label: 'Posts' },
				page: { label: 'Pages' },
			},
			i18n: {
				targetPostType: 'Target Post Type.',
				unspecified: '指定なし',
			},
		};
		require( './index' );
	} );

	afterAll( () => {
		delete window.vbpEditor;
	} );

	test( 'provides a matching unspecified option without changing empty meta', () => {
		const panel = mockPluginSettings.render();
		const targetPostTypeControl = panel.props.children.find(
			( child ) => child?.props.label === 'Target Post Type.'
		);

		expect( targetPostTypeControl.props.value ).toBe( '' );
		expect( targetPostTypeControl.props.options[ 0 ] ).toEqual( {
			label: '指定なし',
			value: '',
		} );
		expect( mockSetMeta ).not.toHaveBeenCalled();
	} );
} );
