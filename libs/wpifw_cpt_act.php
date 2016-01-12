<?php
/**
 * Register Custom Taxonomy
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 *
 */ 
function wpifw_acts() {

	$labels = array(
		'name'                       => _x( 'Acts', 'Taxonomy General Name', 'wp_if_text' ),
		'singular_name'              => _x( 'Act', 'Taxonomy Singular Name', 'wp_if_text' ),
		'menu_name'                  => __( 'Acts', 'wp_if_text' ),
		'all_items'                  => __( 'All Acts', 'wp_if_text' ),
		'parent_item'                => __( 'Parent Act', 'wp_if_text' ),
		'parent_item_colon'          => __( 'Parent Act:', 'wp_if_text' ),
		'new_item_name'              => __( 'New Act Name', 'wp_if_text' ),
		'add_new_item'               => __( 'Add New Act', 'wp_if_text' ),
		'edit_item'                  => __( 'Edit Act', 'wp_if_text' ),
		'update_item'                => __( 'Update Act', 'wp_if_text' ),
		'separate_items_with_commas' => __( 'Separate Acts with commas', 'wp_if_text' ),
		'search_items'               => __( 'Search Acts', 'wp_if_text' ),
		'add_or_remove_items'        => __( 'Add or remove Acts', 'wp_if_text' ),
		'choose_from_most_used'      => __( 'Choose from the most used Acts', 'wp_if_text' ),
		'not_found'                  => __( 'Not Found', 'wp_if_text' ),
	);
	$capabilities = array(
		'manage_terms' => 'read',//or some other capability your clients don't have
        'edit_terms' => 'read',
        'delete_terms' => 'read',
        'assign_terms' => 'read',
		//'manage_terms' 			=> 'manage_acts',
		//'edit_terms' 				=> 'edit_acts',
		//'delete_terms' 			=> 'delete_acts',
		//'assign_terms' 			=> 'assign_acts',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'capability_type'   		 => 'post',
		'capabilities'				 => $capabilities,
	);
	register_taxonomy( 'act', array( 'scene', 'characters' ), $args );
}
// Hook into the 'init' action
add_action( 'init', 'wpifw_acts', 0 );
?>