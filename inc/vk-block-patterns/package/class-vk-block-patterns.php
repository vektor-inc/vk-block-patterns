<?php
/**
 * VK Block Patterns
 *
 * @package VK Block Patterns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'VK_Block_Patterns' ) ) {

	/**
	 * VK Block Patterns
	 */
	class VK_Block_Patterns {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'init', array( __CLASS__, 'automatic_insert_block_patterns' ), 9 );
			add_action( 'init', array( __CLASS__, 'register_block_patterns' ), 8 );
			add_action( 'init', array( __CLASS__, 'register_post_type' ), 7 );
			add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		}

		/**
		 * Register Post Type for Block Patterns
		 */
		public static function register_post_type() {
			register_post_type(
				'vk-block-patterns',
				array(
					'label'        => _x( 'VK Block Patterns', 'Post Type Menu', 'vk-block-patterns' ),
					'public'       => false,
					'show_ui'      => true,
					'show_in_menu' => true,
					'capabilities' => array(
						'edit_posts' => 'create_vk_block_patterns',
					),
					'map_meta_cap' => true,
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
		 * Role Setting
		 */
		public static function admin_init() {

			global $wp_roles;
			$vbp_options = vbp_get_options();

			if ( isset( $vbp_options['role'] ) && 'contributor' === $vbp_options['role'] ) {
				$wp_roles->add_cap( 'administrator', 'create_vk_block_patterns' );
				$wp_roles->add_cap( 'editor', 'create_vk_block_patterns' );
				$wp_roles->add_cap( 'author', 'create_vk_block_patterns' );
				$wp_roles->add_cap( 'contributor', 'create_vk_block_patterns' );
			} elseif ( isset( $vbp_options['role'] ) && 'author' === $vbp_options['role'] ) {
				$wp_roles->add_cap( 'administrator', 'create_vk_block_patterns' );
				$wp_roles->add_cap( 'editor', 'create_vk_block_patterns' );
				$wp_roles->add_cap( 'author', 'create_vk_block_patterns' );
				$wp_roles->remove_cap( 'contributor', 'create_vk_block_patterns' );
			} elseif ( isset( $vbp_options['role'] ) && 'editor' === $vbp_options['role'] ) {
				$wp_roles->add_cap( 'administrator', 'create_vk_block_patterns' );
				$wp_roles->add_cap( 'editor', 'create_vk_block_patterns' );
				$wp_roles->remove_cap( 'author', 'create_vk_block_patterns' );
				$wp_roles->remove_cap( 'contributor', 'create_vk_block_patterns' );
			} else {
				$wp_roles->add_cap( 'administrator', 'create_vk_block_patterns' );
				$wp_roles->remove_cap( 'editor', 'create_vk_block_patterns' );
				$wp_roles->remove_cap( 'author', 'create_vk_block_patterns' );
				$wp_roles->remove_cap( 'contributor', 'create_vk_block_patterns' );
			}
		}

		/**
		 * Register Block Patterns
		 */
		public static function register_block_patterns() {

			global $vbp_prefix;

			// New sub query.
			$the_query = new \WP_Query(
				array(
					'post_type'      => 'vk-block-patterns',
					'post_status'    => 'publish',
					'no_found_rows'  => true,
					'posts_per_page' => -1,
				)
			);

			// Sub loop.
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$post_data = get_post();
				$terms     = get_the_terms( get_the_ID(), 'vk-block-patterns-category' );

				if ( ! empty( $terms ) ) {
					$pattern_categories = array();
					foreach ( $terms as $term ) {
						// Register Block Pattern Category.
						register_block_pattern_category(
							'vk-block-pattern-' . $term->term_id,
							array(
								'label' => $term->name,
							)
						);
						$pattern_categories[] = 'vk-block-pattern-' . $term->term_id;
					}
					register_block_pattern(
						'vk-block-patterns/pattern-' . esc_attr( get_the_ID() ),
						array(
							'title'      => esc_html( get_the_title() ),
							'content'    => $post_data->post_content,
							'categories' => $pattern_categories,
						)
					);
				} else {
					// Register Block Pattern Category.
					register_block_pattern_category(
						'vk-block-patterns',
						array(
							'label' => $vbp_prefix . 'Block Patterns',
						)
					);
					register_block_pattern(
						'vk-block-patterns/pattern-' . esc_attr( get_the_ID() ),
						array(
							'title'      => esc_html( get_the_title() ),
							'content'    => $post_data->post_content,
							'categories' => array( 'vk-block-patterns' ),
						)
					);
				}
			}

			wp_reset_postdata();
		}

		/**
		 * Automatic　Insert Block Patterns
		 */
		public static function automatic_insert_block_patterns() {

			$the_query = new \WP_Query(
				array(
					'post_type'      => 'vk-block-patterns',
					'post_status'    => 'publish',
					'no_found_rows'  => true,
					'posts_per_page' => -1,
				)
			);

			// Sub loop.
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$post_data                     = get_post();
				$registered_pattern_add_method = get_post_meta( get_the_ID(), 'vbp-init-pattern-add-method', true );
				$registered_post_type          = get_post_meta( get_the_ID(), 'vbp-init-post-type', true );

				if ( $registered_post_type && empty( $registered_pattern_add_method ) ) {
					$registered_pattern_add_method = 'show';
				}

				// 新規投稿時の自動挿入の場合.
				// For automatic insertion on new post.
				if ( 'add' === $registered_pattern_add_method && $registered_post_type ) {

					$post_type_object = get_post_type_object( $registered_post_type );

					// Cope with failer of get_post_type_object().
					// * For example, if the post type is later deleted.
					if ( ! empty( $post_type_object ) ) {
						// Insert Block Pattern.
						$post_type_object->template = array(
							array(
								'core/pattern',
								array(
									'slug' => 'vk-block-patterns/pattern-' . esc_attr( get_the_ID() ),
								),
							),
						);
					}
				}

				// 新規投稿時の候補の表示.
				// Show suggestion when new post.
				if ( 'show' === $registered_pattern_add_method && $registered_post_type ) {
					register_block_pattern(
						// 通常のパターン登録と同じ title の場合、通常のパターン挿入候補に出てこなくなってしまうので -show を付けている.
						'vk-block-patterns/pattern-' . esc_attr( get_the_ID() ) . '-show',
						array(
							'title'      => esc_html( get_the_title() ),
							'content'    => $post_data->post_content,
							'blockTypes' => array( 'core/post-content' ),
							'postTypes'  => array( $registered_post_type ),
						)
					);
				}
			}

			wp_reset_postdata();
		}
	}
	new VK_Block_Patterns();
}
