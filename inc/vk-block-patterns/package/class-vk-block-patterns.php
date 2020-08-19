<?php
/**
 * VK Block Patterns
 *
 * @package VK Block Patterns
 */

if ( ! class_exists( 'VK_Block_Patterns' ) ) {

	/**
	 * VK Block Patterns
	 */
	class VK_Block_Patterns {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'init', array( __CLASS__, 'register_block_patterns' ), 20 );
			add_action( 'init', array( __CLASS__, 'register_post_type' ), 11 );
		}

		/**
		 * Register Post Type for Block Patterns
		 */
		public static function register_post_type() {

			global $vbp_prefix;

			register_post_type(
				'vk-block-patterns',
				array(
					'label'        => $vbp_prefix . __( 'Block Patterns', 'vk-block-patterns' ),
					'public'       => false,
					'show_ui'      => true,
					'show_in_menu' => true,
					'has_archive'  => false,
					'menu_icon'    => 'dashicons-screenoptions',
					'show_in_rest' => true,
					'supports'     => array( 'title', 'editor' ),
				)
			);

			register_taxonomy(
				'vk-block-patterns-category',
				'vk-block-patterns',
				array(
					'label'             => __( 'Category', 'vk-block-patterns' ),
					'public'            => false,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_rest'      => true,
					'hierarchical'      => true,
					'sort'              => true,
				)
			);
		}

		/**
		 * Register Block Patterns
		 */
		public static function register_block_patterns() {

			if ( ! is_admin() ) {
				return;
			}

			// Register Block Pattern Category.
			register_block_pattern_category(
				'vk-block-patterns',
				array(
					'label' => $vbp_prefix . 'Block Patterns',
				)
			);

			// New sub query.
			$the_query = new \WP_Query(
				array(
					'post_type'      => 'vk-block-patterns',
					'no_found_rows'  => true,
					'posts_per_page' => -1,
				)
			);

			// Sub loop.
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$parts = get_post();
				$terms = get_the_terms( get_the_ID(), 'vk-block-patterns-category' );

				// Register Block Pattern Category.
				register_block_pattern_category(
					'vk-block-pattern-' . $terms[0]->term_id,
					array(
						'label' => $terms[0]->name,
					)
				);

				register_block_pattern(
					'vk-block-patterns/pattern-' . esc_attr( get_the_ID() ),
					array(
						'title'      => esc_html( get_the_title() ),
						'content'    => $parts->post_content,
						'categories' => array( 'vk-block-pattern-' . $terms[0]->term_id ),
					)
				);
			}

			wp_reset_postdata();

		}
	}
	new VK_Block_Patterns();
}
