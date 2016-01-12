<?php
// Include custom post types registration that
// runs after WordPress has finished loading but
// before any headers are sent.
require_once('wpifw_cpt_characterDefaults.php');
require_once('wpifw_cpt_character.php');
require_once('wpifw_cpt_scene.php');
require_once('wpifw_cpt_act.php');
require_once('wpifw_cpt_MetaClass.php');
require_once('wpifw_utils.php');

/**
 * wpifw Class
 *
 * A Class that hooks functions on to specific 
 * actions, creates plugin specific roles on 
 * activation and removes them on deactivation.
 *
 * @package    wpifw
 * @subpackage
 * @author     Peter Gordon <hello@petergordon.net>
 * @version    0.0.1
 * @since      0.0.1
 */
class wpifwriter {
	
	/**
	 * Updates activated switch, runs  
	 * install function.
	 *
	 * @param  none
	 */
	public static function installPlugin() {
		self::runInstall();
		// set variable to check for on activation
		update_option('wpifw_activated', 1);
	}
	
	/**
	 * Removes custom role and removes custom
	 * capabilities added to administrator role
	 *
	 * @param  none
	 */
	public static function uninstallPlugin() {
		
		remove_role( 'if_editor' );
		
		remove_role( 'if_admin' );
		
		$role = get_role( 'administrator' );
		
		// Character capabilities here
		$role->remove_cap( 'edit_character' );
		$role->remove_cap( 'read_character' );
		$role->remove_cap( 'delete_character' );
		$role->remove_cap( 'edit_characters' );
		$role->remove_cap( 'edit_others_characters' );
		$role->remove_cap( 'publish_characters' );
		$role->remove_cap( 'read_private_characters' );
	
		// Scene capabilities here
		$role->remove_cap( 'edit_scene' );
		$role->remove_cap( 'read_scene' );
		$role->remove_cap( 'delete_scene' );
		$role->remove_cap( 'edit_scenes' );
		$role->remove_cap( 'edit_others_scenes' );
		$role->remove_cap( 'publish_scenes' );
		$role->remove_cap( 'read_private_scenes' );
		
		// Act capabilities here
		$role->remove_cap( 'manage_acts' );
		$role->remove_cap( 'edit_acts' );
		$role->remove_cap( 'delete_acts' );
		$role->remove_cap( 'assign_acts' );
		
		// Character Class capabilities here	
		$role->remove_cap( 'edit_charDefault' );
		$role->remove_cap( 'read_charDefault' );
		$role->remove_cap( 'delete_charDefault' );
		$role->remove_cap( 'edit_charDefaults' );
		$role->remove_cap( 'edit_others_charDefaults' );
		$role->remove_cap( 'publish_charDefaults' );
		$role->remove_cap( 'read_private_charDefaults' );
		
		// set variable to check on deactivation
		update_option('wpifw_activated', 0);
	
	}

	/**
	 * Updates the WordPress plugin version, removes the
	 * previously created role and recreates it. Adds custom
	 * capabilities to both the new role and administrator.
	 *
	 * @param  none
	 */	
	public static function runInstall(){
		update_option('wpifw_version', WP_IF_WRITER_VERSION);
		
		remove_role( 'if_editor' );
		add_role( 'if_editor', 'IF Editor', array( 
			
			// Character capabilities here
			'edit_character' => true,
			'read_character' => true,
			'delete_character' => true,
			'edit_characters' => true,
			'edit_others_characters' => true,
			'publish_characters' => true,
			'read_private_characters' => true,
			
			// Scene capabilities here
			'edit_scene' => true,
			'read_scene' => true,
			'delete_scene' => true,
			'edit_scenes' => true,			
			'edit_others_scenes' => true,
			'delete_others_scenes' => true,
			'publish_scenes' => true,
			'read_private_scenes' => true,
			
			// Act capabilities here
			//'manage_terms' => false,
			//'edit_terms' => false,
			//'delete_terms' => false,
			//'assign_terms' => true,
			
			// Character Class capabilities here	
			'edit_charDefault' => true,
			'read_charDefault' => true,
			'delete_charDefault' => true,
			'edit_charDefaults' => true,
			'edit_others_charDefaults' => true,
			'publish_charDefaults' => true,
			'read_private_charDefaults' => true,

			// Standard capabilities here
			'manage_categories' => true,
			'read' => true,
			'upload_files' => true,
			'delete_posts' => true,
			'delete_published_posts' => true,
			
		 ) );
		 
		remove_role( 'if_admin' );
		add_role( 'if_admin', 'IF Admin', array( 
			
			// Character capabilities here
			'edit_character' => true,
			'read_character' => true,
			'delete_character' => true,
			'edit_characters' => true,
			'edit_others_characters' => true,
			'publish_characters' => true,
			'read_private_characters' => true,
			
			// Scene capabilities here
			'edit_scene' => true,
			'read_scene' => true,
			'delete_scene' => true,
			'edit_scenes' => true,			
			'edit_others_scenes' => true,
			'delete_others_scenes' => true,
			'publish_scenes' => true,
			'read_private_scenes' => true,
			
			// Act capabilities here
			//'manage_terms' => false,
			//'edit_terms' => false,
			//'delete_terms' => false,
			//'assign_terms' => true,
			
			// Character Class capabilities here	
			'edit_charDefault' => true,
			'read_charDefault' => true,
			'delete_charDefault' => true,
			'edit_charDefaults' => true,
			'edit_others_charDefaults' => true,
			'publish_charDefaults' => true,
			'read_private_charDefaults' => true,

			// Standard capabilities here
			'list_users' => true,
			'create_users' => true,
			'remove_users' => true,
			'delete_users' => true,
			'add_users' => true,
			'promote_users' => true,
			'edit_users' => true,
			'manage_categories' => true,
			'read' => true,
			'upload_files' => true,
			'delete_posts' => true,
			'delete_published_posts' => true,
			
		 ) );
		 
		$role = get_role( 'administrator' );
		
		// Character capabilities here
		$role->add_cap( 'edit_character' );
		$role->add_cap( 'read_character' );
		$role->add_cap( 'delete_character' );
		$role->add_cap( 'edit_characters' );
		$role->add_cap( 'edit_others_characters' );
		$role->add_cap( 'publish_characters' );
		$role->add_cap( 'read_private_characters' );
	
		// Scene capabilities here
		$role->add_cap( 'edit_scene' );
		$role->add_cap( 'read_scene' );
		$role->add_cap( 'delete_scene' );
		$role->add_cap( 'edit_scenes' );
		$role->add_cap( 'edit_others_scenes' );
		$role->add_cap( 'publish_scenes' );
		$role->add_cap( 'read_private_scenes' );
		
		// Act capabilities here
		//$role->add_cap( 'manage_acts' );
		//$role->add_cap( 'edit_acts' );
		//$role->add_cap( 'delete_acts' );
		//$role->add_cap( 'assign_acts' );
		
		// Character Class capabilities here
		$role->add_cap( 'edit_charDefault' );
		$role->add_cap( 'read_charDefault' );
		$role->add_cap( 'delete_charDefault' );
		$role->add_cap( 'edit_charDefaults' );
		$role->add_cap( 'edit_others_charDefaults' );
		$role->add_cap( 'publish_charDefaults' );
		$role->add_cap( 'read_private_charDefaults' );
		
		// Standard capabilities here
		$role->add_cap( 'manage_categories' );
	
	}
	
	/**
	 * Checks version and adds actions to admin_init triggered
	 * before any other hook when a user accesses the admin area.
	 *
	 * @param  none
	 */	
	public static function install_actions() {
		//	Check whether an upgrade is necessary
		$versionInOptions = get_option('wpifw_version', false);
		if( (! $versionInOptions) || version_compare( WP_IF_WRITER_VERSION, $versionInOptions, '>') ) {
			//Either there is no version in options or the version in options is greater and we need to run the upgrade
			self::runInstall();
		}

		/**
		 * Update scene options
		 * @link : http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
		 */ 		
		add_action( 'admin_init', 'wpifw_utils::wpifw_update_opening_scene' );
		
		/**
		 * Update contextual help
		 * @link : 
		 */ 	
		add_action('admin_head', 'wpifw_utils::wpifw_add_contextual_help', 999);
		
		/**
		 * Redirect user based on their role
		 * http://wordpress.stackexchange.com/questions/33344/redirect-admin-user-in-dashboard
		 */
		add_action( 'login_redirect', 'wpifw_utils::wpifw_redirect_IFEditor' );
		

		/**
		 * Configure dashboard.
		 * @link : http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
		 */ 
		add_action( 'admin_init', 'wpifw_utils::wpifw_configure_dashboard', 1 );
		
		/**
		 * Add custom meta boxes.
		 * @link : 
		 */ 	
		add_action('admin_menu','wpifw_utils::wpifw_add_cpt_meta');

		/** 
		 * Register function using the admin_menu action hook. 
		 * @link : http://codex.wordpress.org/Adding_Administration_Menus
		 */ 
		add_action( 'admin_menu', 'wpifw_utils::wpifw_admin_menu' );

		/** 
		 * Register function to remove IF Editor admin menus
		 * @link : http://codex.wordpress.org/Adding_Administration_Menus
		 */ 
		add_action( 'admin_init', 'wpifw_utils::wpifw_remove_editor_menus' );
		
		/**
		 * Remove WP logo node.
		 * @link : http://codex.wordpress.org/Function_Reference/remove_node
		 */ 			
		add_action( 'admin_bar_menu', 'wpifw_utils::wpifw_remove_wp_logo', 999 );
		
		/**
		 * Add dashboard widget.
		 * @link : http://codex.wordpress.org/Dashboard_Widgets_API
		 */ 		
		add_action( 'wp_dashboard_setup', 'wpifw_utils::wpifw_add_dashboard_widgets' );
		
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		add_action( 'welcome_panel', 'wpifw_utils::wpifw_welcome_panel' );

		add_action( 'wpmu_new_blog', 'wpifw_utils::wpifw_new_user_meta', 10, 2 );
		
		add_action( 'admin_enqueue_scripts', 'wpifw_utils::wpifw_pointer_load', 1000 );
		
		add_action('publish_scene', 'wpifw_utils::wpifw_scene_published');	
		
		add_filter( 'pre_get_shortlink', '__return_empty_string' );
		
		add_filter('get_sample_permalink_html', '__return_empty_string', '',4);
		
		add_filter('editable_roles', 'wpifw_utils::wpifw_if_admin_editable_roles');
		
		add_filter( 'wpifw_admin_pointers-dashboard_page_wpifw_options', 'wpifw_utils::wpifw_register_pointer_testing' );
		
		add_action('admin_notices', 'wpifw_utils::wpifw_custom_dashboard'); 
		
		/**
		 * Add support for upload of SVG files.
		 * @link : https://wordpress.org/support/topic/svg-upload-not-allowed
		 */ 
		add_filter('upload_mimes', 'custom_upload_mimes');

			function custom_upload_mimes ( $existing_mimes=array() ) {
			
				// add the file extension to the array
			
				$existing_mimes['svg'] = 'mime/type';
			
					// call the modified list of extensions
			
				return $existing_mimes;
			
			}
	
	}
}
?>