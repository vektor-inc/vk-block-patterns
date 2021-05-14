<?php
/**
 * Role Option
 *
 * @package VK Block Patterns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$role_array = array(
	array(
		'label' => __( 'Contributor or higher', 'vk-block-patterns' ),
		'value' => 'contributor',
	),
	array(
		'label' => __( 'Author or higher', 'vk-block-patterns' ),
		'value' => 'author',
	),
	array(
		'label' => __( 'Editor or higher', 'vk-block-patterns' ),
		'value' => 'editor',
	),
	array(
		'label' => __( 'Administrator only', 'vk-block-patterns' ),
		'value' => 'administrator',
	),
);
?>
<section>
	<h3 id="role-setting"><?php echo __( 'Role Setting', 'vk-block-patterns' ); ?></h3>
	<?php $vbp_options = vbp_get_options(); ?>
	<select name="vk_block_patterns_options[role]">
		<?php foreach ( $role_array as $role ) : ?>
			<option value="<?php echo $role['value']; ?>" <?php selected( $vbp_options['role'], $role['value'] ); ?>>
				<?php echo $role['label']; ?>
			</option>
		<?php endforeach; ?>
	</select>
<?php submit_button(); ?>
</section>
