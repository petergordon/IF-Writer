<?php
/**
 * Register Character Custom Post Type
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 *
 */ 
function wpifw_cpt_character() {

	$labels = array(
		'name'                => _x( 'Characters', 'Post Type General Name', 'wp_if_text' ),
		'singular_name'       => _x( 'Character', 'Post Type Singular Name', 'wp_if_text' ),
		'menu_name'           => __( 'Characters', 'wp_if_text' ),
		'parent_item_colon'   => __( 'Parent Characters:', 'wp_if_text' ),
		'all_items'           => __( 'All Characters', 'wp_if_text' ),
		'view_item'           => __( 'View Character', 'wp_if_text' ),
		'add_new_item'        => __( 'Add New Character', 'wp_if_text' ),
		'add_new'             => __( 'Add New', 'wp_if_text' ),
		'edit_item'           => __( 'Edit Character', 'wp_if_text' ),
		'update_item'         => __( 'Update Character', 'wp_if_text' ),
		'search_items'        => __( 'Search Characters', 'wp_if_text' ),
		'not_found'           => __( 'Not found', 'wp_if_text' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'wp_if_text' ),
	);
	$capabilities = array(
		'edit_post'           => 'edit_character',
		'read_post'           => 'read_character',
		'delete_post'         => 'delete_character',
		'edit_posts'          => 'edit_characters',
		'edit_others_posts'   => 'edit_others_characters',
		'publish_posts'       => 'publish_characters',
		'read_private_posts'  => 'read_private_characters',
	);	
	$args = array(
		'label'               => __( 'characters', 'wp_if_text' ),
		'description'         => __( 'Characters', 'wp_if_text' ),
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
		'menu_icon'           => 'dashicons-id-alt',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capabilities'        => $capabilities,
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	);
	register_post_type( 'characters', $args );
}
// Hook into the 'init' action
add_action( 'init', 'wpifw_cpt_character', 0 );
?>