<?php
/**
 * Register character defaults Custom Post Type
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 *
 */ 
function wpifw_character_defaults() {

	$labels = array(
		'name'                => _x( 'Character Classes', 'Post Type General Name', 'wp_if_text' ),
		'singular_name'       => _x( 'Character Class', 'Post Type Singular Name', 'wp_if_text' ),
		'menu_name'           => __( 'Classes', 'wp_if_text' ),
		'parent_item_colon'   => __( 'Parent Character Classes:', 'wp_if_text' ),
		'all_items'           => __( 'All Character Classes', 'wp_if_text' ),
		'view_item'           => __( 'View Character Class', 'wp_if_text' ),
		'add_new_item'        => __( 'Add New Character Class', 'wp_if_text' ),
		'add_new'             => __( 'Add New', 'wp_if_text' ),
		'edit_item'           => __( 'Edit Character Class', 'wp_if_text' ),
		'update_item'         => __( 'Update Character Class', 'wp_if_text' ),
		'search_items'        => __( 'Search Character Classes', 'wp_if_text' ),
		'not_found'           => __( 'Not found', 'wp_if_text' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'wp_if_text' ),
	);
	$capabilities = array(
		'edit_post'           => 'edit_charDefault',
		'read_post'           => 'read_charDefault',
		'delete_post'         => 'delete_charDefault',
		'edit_posts'          => 'edit_charDefaults',
		'edit_others_posts'   => 'edit_others_charDefaults',
		'publish_posts'       => 'publish_charDefaults',
		'read_private_posts'  => 'read_private_charDefaults',
	);
	$args = array(
		'label'               => __( 'character_defaults', 'wp_if_text' ),
		'description'         => __( 'Character Defaults', 'wp_if_text' ),
		'labels'              => $labels,
		'supports'            => array( ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_icon'           => 'dashicons-id',
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capabilities'        => $capabilities,
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	);
	register_post_type( 'character_defaults', $args );
}
// Hook into the 'init' action
add_action( 'init', 'wpifw_character_defaults', 0 );