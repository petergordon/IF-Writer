<?php
/**
 * Register Scene Custom Post Type
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 *
 */ 
function wpifw_cpt_scene() {

	$labels = array(
		'name'                => _x( 'Scenes', 'Post Type General Name', 'wpifw_text' ),
		'singular_name'       => _x( 'Scene', 'Post Type Singular Name', 'wpifw_text' ),
		'menu_name'           => __( 'Scenes', 'wpifw_text' ),
		'parent_item_colon'   => __( 'Parent Scene:', 'wpifw_text' ),
		'all_items'           => __( 'All Scenes', 'wpifw_text' ),
		'view_item'           => __( 'View Scene', 'wpifw_text' ),
		'add_new_item'        => __( 'Add New Scene', 'wpifw_text' ),
		'add_new'             => __( 'Add New', 'wpifw_text' ),
		'edit_item'           => __( 'Edit Scene', 'wpifw_text' ),
		'update_item'         => __( 'Update Scene', 'wpifw_text' ),
		'search_items'        => __( 'Search Scenes', 'wpifw_text' ),
		'not_found'           => __( 'Not found', 'wpifw_text' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'wpifw_text' ),
	);
	$capabilities = array(
		'edit_post'           => 'edit_scene',
		'read_post'           => 'read_scene',
		'delete_post'         => 'delete_scene',
		'edit_posts'          => 'edit_scenes',
		'edit_others_posts'   => 'edit_others_scenes',
		'publish_posts'       => 'publish_scenes',
		'read_private_posts'  => 'read_private_scenes',
	);
	$args = array(
		'label'               => __( 'scene', 'wpifw_text' ),
		'description'         => __( 'Scenes', 'wpifw_text' ),
		'labels'              => $labels,
		'supports'            => array( ),
		'taxonomies'          => array( 'act' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-aside',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capabilities'        => $capabilities,
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	);
	register_post_type( 'scene', $args );
}
// Hook into the 'init' action
add_action( 'init', 'wpifw_cpt_scene', 0 );
?>