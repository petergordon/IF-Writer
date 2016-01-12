<?php

class wpifw_utils {

	/**
	 * If Site Admin check 
	 * @link : http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
	 */ 
	public static function wpifw_isSiteAdmin() {
		
		if( current_user_can( 'manage_options' )) {
			return true;
		}
		
		return false;
	}
	/**
	 * If Admin check 
	 * @link : http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
	 */ 
	public static function wpifw_isAdmin() {
		
		if( current_user_can( 'create_users' )) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * IFEditor check
	 * @link : http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
	 */ 
	public static function wpifw_isIFEditor() {
		
		if( current_user_can( 'edit_scene' )) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Redirect IFEditors to daashboard.
	 * @link : http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
	 */ 
	public static function wpifw_redirect_IFEditor() {
 
		//if ( ! current_user_can( 'manage_options' ) ) {
			 $url = get_option('siteurl') . '/wp-admin/index.php';
		//}
	
		return $url;
	}
	
	/**
	 * Configure dashboard.
	 * @link : http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
	 */ 
	public static function wpifw_configure_dashboard() {

		if(! wpifw_utils::wpifw_isSiteAdmin()){
			
			remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); //Removes the 'incoming links' widget
			remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); //Removes the 'plugins' widget
			remove_meta_box('dashboard_primary', 'dashboard', 'normal'); //Removes the 'WordPress News' widget
			remove_meta_box('dashboard_secondary', 'dashboard', 'normal'); //Removes the secondary widget
			remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); //Removes the 'Quick Draft' widget
			remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); //Removes the 'Recent Drafts' widget
			remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); //Removes the 'Activity' widget
			remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //Removes the 'At a Glance' widget
			remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Removes the 'Activity' widget (since 3.8)
			
		}
	
	// End configure_dashboard();
	}
	
	public function wpifw_add_contextual_help () {
			
			$screen = get_current_screen();
			$screen->remove_help_tabs();
			 			
			include 'wpifw-contextual-help.php'; 

		}
	
	/**
	 * Add widget to the dashboard
	 * @link : http://codex.wordpress.org/Dashboard_Widgets_API
	 */ 	 
	public static function wpifw_add_dashboard_widgets() {
	
		wp_add_dashboard_widget(
			'wpifw_glance_widget', // Widget slug.
			'At a Glance', // Title.
			'wpifw_utils::wpifw_glance_widget_function' // Display function.
		);
		
		//add conditional if fight allowed
		//$allow_fight = get_option( 'allow_fight' );
		//check if fight option selected and remove widget if appropriate
		$count_classes 	= wp_count_posts('character_defaults');
		$count_characters = wp_count_posts('characters');
		$count_scenes = wp_count_posts('scene');
		
		if ( $count_scenes->publish != 0 || $count_characters->publish != 0 || $count_classes->publish != 0 ) {
			
			$allow_fight = get_option( 'allow_fight' );
			if ( $allow_fight == 'on' ) {
			
				wp_add_dashboard_widget(
					'wpifw_activity_widget', // Widget slug.
					'Recently created', // Title.
					'wpifw_utils::wpifw_activity_widget_function' // Display function.
				);
			
			}
			
		} else {


		}
		
	}

	/**
	 * Function to output the contents of the At a Glance Dashboard Widget.
	 * @link : http://codex.wordpress.org/Dashboard_Widgets_API
	 */ 
	public static function wpifw_glance_widget_function() {
	
		// Display scene and character count
		$count_classes 	= wp_count_posts('character_defaults');
		$count_characters = wp_count_posts('characters');
		$count_scenes = wp_count_posts('scene');
		$screen = get_current_screen();
		echo '<ul>';
		
		if ( $count_scenes->publish != 0 || $count_characters->publish != 0 || $count_classes->publish != 0) {

			$allow_fight = get_option( 'allow_fight' );
			$allow_classes = get_option( 'allow_classes' );
			//check if fight option selected and remove class and character if appropriate
			if ( $allow_fight == 'on' ) {
				if ( $allow_classes == 'on' ) {
					if (  $count_classes->publish == 1 ) { $classRef = 'Character Class'; } else {	$classRef = 'Character Classes'; }
					echo '<h4><li><a href="/wp-admin/edit.php?post_type=character_defaults">' . $count_classes->publish . ' ' . $classRef . '</a></li></h4>';
				}
				if (  $count_characters->publish == 1 ) { $characterRef = 'Character'; } else {	$characterRef = 'Characters'; }
				echo '<h4><li><a href="/wp-admin/edit.php?post_type=characters">' . $count_characters->publish . ' ' .  $characterRef . '</a></li></h4>';
			}			
			
		}
		
		if ( $count_scenes->publish == 0 ) {
			echo '<h4><strong>Outstanding tasks:</strong></h4><br />';
			printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Write your first scene' ) . '</a>', admin_url( 'post-new.php?post_type=scene' ) );

		} else {
		
			if (  $count_scenes->publish == 1 ) { $sceneRef = 'Scene'; } else {	$sceneRef = 'Scenes'; }
			echo '<h4><li><a href="/wp-admin/edit.php?post_type=scene">' . $count_scenes->publish . ' ' .  $sceneRef . '</a></li></h4><br /><br />';
	
		}
		echo '</ul>';
	}
	
	/**
	 * Function to output the contents of the Activity Dashboard Widget.
	 * @link : http://codex.wordpress.org/Dashboard_Widgets_API
	 */ 
	public static function wpifw_activity_widget_function() {
	
		// Display recently published Scenes.
		$count_classes 	= wp_count_posts('character_defaults');
		$count_characters = wp_count_posts('characters');
		$count_scenes = wp_count_posts('scene');
		
		if ( $count_scenes->publish != 0 ) {
			echo "<h4>Scenes:</h4>";
		}
		
		$args = array(
			'numberposts' => 3,
			'offset' => 0,
			'category' => 0,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'post_type' => 'scene',
			'post_status' => 'draft, publish, future, pending, private',
			'suppress_filters' => true );
	
		$recent_characters = wp_get_recent_posts( $args, ARRAY_A );
		echo '<ul>';
		foreach( $recent_characters as $recent ){
			echo '<li><a href="/wp-admin/post.php?post=' . $recent["ID"] . '&action=edit">' .   $recent["post_title"].'</a> </li> ';
		}
		echo '</ul>';


		// Display recently published classes.
		$allow_classes = get_option( 'allow_classes' );
		//check if fight option selected and remove widget if appropriate
		
		if ( $allow_classes == 'on' ) {
		
			if ( $count_classes->publish != 0 ) {
				echo "<h4>Classes:</h4>";
			}
			
			$args = array(
				'numberposts' => 3,
				'offset' => 0,
				'category' => 0,
				'orderby' => 'post_date',
				'order' => 'DESC',
				'post_type' => 'character_defaults',
				'post_status' => 'draft, publish, future, pending, private',
				'suppress_filters' => true );
		
			$recent_characters = wp_get_recent_posts( $args, ARRAY_A );
			echo '<ul>';
			foreach( $recent_characters as $recent ){
				echo '<li><a href="/wp-admin/post.php?post=' . $recent["ID"] . '&action=edit">' .   $recent["post_title"].'</a> </li> ';
			}
			echo '</ul>';
		}

		
		// Display recently published characters.
		$allow_fight = get_option( 'allow_fight' );
		//check if fight option selected and remove widget if appropriate
		
		if ( $allow_fight == 'on' ) {
		
			if ( $count_characters->publish != 0 ) {
				echo "<h4>Characters:</h4>";
			}
			
			$args = array(
				'numberposts' => 3,
				'offset' => 0,
				'category' => 0,
				'orderby' => 'post_date',
				'order' => 'DESC',
				'post_type' => 'characters',
				'post_status' => 'draft, publish, future, pending, private',
				'suppress_filters' => true );
		
			$recent_characters = wp_get_recent_posts( $args, ARRAY_A );
			echo '<ul>';
			foreach( $recent_characters as $recent ){
				echo '<li><a href="/wp-admin/post.php?post=' . $recent["ID"] . '&action=edit">' .   $recent["post_title"].'</a> </li> ';
			}
			echo '</ul>';
		}
		
	}

	/**
	 * Function to remove WP Admin logo node.
	 * @link : http://codex.wordpress.org/Dashboard_Widgets_API
	 */ 	
	public static function wpifw_remove_wp_logo( $wp_admin_bar ) {
		
		$allow_fight = get_option( 'allow_fight' );
		$allow_classes = get_option( 'allow_classes' );
		//check if fight option selected and remove widget if appropriate
		if ( $allow_classes != 'on' ) {
			$wp_admin_bar->remove_node( 'new-character_defaults' );
			if ( $allow_fight != 'on' ) {
				$wp_admin_bar->remove_node( 'new-characters' );
			}
		}
		
		if(! wpifw_utils::wpifw_isSiteAdmin()){
		
			$wp_admin_bar->remove_node( 'wp-logo' );
			$wp_admin_bar->remove_node( 'view' );
			$wp_admin_bar->remove_node( 'my-sites' );

		}
		
	}
	
	/**
	 * Function to check for scene and character editing and add the relevant meta boxes.
	 * @link : http://codex.wordpress.org/Function_Reference/add_meta_box#Examples
	 */ 	
	public static function wpifw_add_cpt_meta() {
		
		global $pagenow, $typenow;
		
		if (empty($typenow) && !empty($_GET['post'])) {
			
			$post = get_post($_GET['post']);
			$typenow = $post->post_type;
			
		 }	
		
		if( wpifw_utils::wpifw_isIFEditor()) {
			
			/**
			 * Load media files needed for Uploader
			 */
			function load_wp_media_files() {
			  wp_enqueue_media();
			}
			add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );
			
			$url = plugins_url();
			wp_enqueue_script('wpifw-media-uploader', $url . '/wp-if-writer/js/wpifw-media-uploader.js', '', '', true);
			
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			wp_enqueue_script( 'jquery-ui-droppable' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'iris' );

			if ( $pagenow == 'admin.php' ) { 
				wp_enqueue_style('wpifw-navigator-styles', $url . '/wp-if-writer/css/wpifw-scene-navigator.css');
				wp_enqueue_script('wpifw-navigator-scripts', $url . '/wp-if-writer/js/wpifw-scene-navigator.js', '', '', true);
				


				
				

			}
			
			if ( $pagenow == 'index.php' ) { 
				wp_enqueue_style('wpifw-dashboard', $url . '/wp-if-writer/css/wpifw-dashboard.css');
			}
			
			if (  wpifw_utils::wpifw_isAdmin() ) {
				wp_enqueue_script('wpifw-site-options', $url . '/wp-if-writer/js/wpifw-site-options.js', '', '', true);
				
				

			}	
					
			if ( $pagenow=='post-new.php' OR $pagenow=='post.php' ) { 
			
				//$url = plugins_url();
				wp_enqueue_script('wpifw', $url . '/wp-if-writer/js/wpifw-admin.js', '', '', true);
				
				if( $typenow=='scene' ) {
					wp_enqueue_style('wpifw-scene-styles', $url . '/wp-if-writer/css/wpifw-admin.css');
					wp_enqueue_script('wpifw-admin-tables', $url . '/wp-if-writer/js/wpifw-admin-scene.js', '', '', true);
					new wpifw_cpt_MetaClass;
					wpifw_cpt_MetaClass::wpifw_add_meta_box( 'scene' );
					
				} else if( $typenow=='characters' ) {
					
					//Enqueue scripts
					wp_enqueue_style('wpifw-characters-styles', $url . '/wp-if-writer/css/wpifw-character-options.css');
					wp_enqueue_script('wpifw-characters-scripts', $url . '/wp-if-writer/js/wpifw-character-options.js', '', '', true);
					//load Meta Box Class and instantiate
					new wpifw_cpt_MetaClass;
					wpifw_cpt_MetaClass::wpifw_add_meta_box( 'characters' );
					
				} else if( $typenow=='character_defaults' ) {
					
					//Enqueue scripts
					wp_enqueue_style('wpifw-default-styles', $url . '/wp-if-writer/css/wpifw-character-defaults-options.css');
					wp_enqueue_script('wpifw-default-scripts', $url . '/wp-if-writer/js/wpifw-character-defaults-options.js', '', '', true);
					//load Meta Box Class and instantiate
					new wpifw_cpt_MetaClass;
					wpifw_cpt_MetaClass::wpifw_add_meta_box( 'character_defaults' );
					
				} 
			}
		}
	}
	
	/**
	 * Function to list scene 'choice' options (excluding current) setting selected attribute on current selection.
	 * TODO: Filter for Act.
	 * @link : 
	 */ 		
	
	public static function wpifw_get_scenes_in_act( $destination ) { 
	
		//if (!isset($thisScene)) {
			$thisScene = $_GET['post']; 
			$exclude_ids = array( $thisScene );

			$args = array( 
				'post_type' => 'scene',
				'post__not_in' => $exclude_ids,
				'posts_per_page' => -1,
			);
			
			$loop = new WP_Query( $args );		
		//}
		
		echo '<option>Select...</option>';
		while ( $loop->have_posts() ) : $loop->the_post();
			
			$id = get_the_ID();
			echo '<option value="'. $id . '"';
			if ( $destination == $id ) 
				echo ' selected ';
			
			echo '>';
			the_title();
			echo '</option>';
		endwhile;
		
		wp_reset_query();
	
	}
	
	/**
	 * Function to list scene 'choice' options (excluding current) setting selected attribute on current selection.
	 * TODO: Filter for Act.
	 * @link : 
	 */ 		
	
	public static function wpifw_get_all_scenes( $opening_scene ) { 
	
		$args = array( 
			'post_type' => 'scene',
			'posts_per_page' => -1,
		);
	
		$loop = new WP_Query( $args );		

		echo '<option>Select...</option>';
		while ( $loop->have_posts() ) : $loop->the_post();
			
			$id = get_the_ID();
			echo '<option value="'. $id . '"';
			if ( $opening_scene == $id ) 
				echo ' selected ';
			
			echo '>';
			the_title();
			echo '</option>';
		endwhile;
		
		wp_reset_query();
	
	}
	
	/**
	 * Function that contains the menu-building code
	 * @link : http://codex.wordpress.org/Adding_Administration_Menus (Step 1)
	 */ 	
	public static function wpifw_admin_menu() {
		
		add_dashboard_page( 'Options', 'Options', 'edit_others_scenes', 'wpifw_options', 'wpifw_utils::wpifw_admin_options' );
		add_menu_page( 'Navigator', 'Navigator', 'edit_others_scenes', 'wpifw_navigator', 'wpifw_utils::wpifw_scene_navigator', 'dashicons-images-alt2', 3 );

	}
	
	/**
	 * Create the HTML output for the scene navigator (screen) displayed when the menu item is clicked.
	 * @link : http://codex.wordpress.org/Adding_Administration_Menus (Step 3)
	 */ 
	
	public static function wpifw_scene_navigator() {
		
		if ( !current_user_can( 'edit_others_scenes' ) )  {
			
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}?>
        <div class="wrap scene-navigator">
            <h1>Scene Navigator<?php echo $screen; ?></h1>
            <table id="navigator-filter-header">
            <tbody>
            <tr>
            <td>
            <div class="navigator-filter">
                <ul class="subsubsub" id="scene-sub">
                    <li><input type="radio" id="wpifw_opening_scene" name="wpifw_start_scene" value="openingScene" checked /><?php echo  _e( 'Opening', 'wpifw_text' ); ?></li>
                    <li><input type="radio" id="wpifw_most_recent" name="wpifw_start_scene" value="mostRecent" /><?php echo  _e( 'Most recent', 'wpifw_text' ); ?></li>
                    <li><input type="radio" id="wpifw_last_modified" name="wpifw_start_scene" value="lastModified" /><?php echo  _e( 'Last modified', 'wpifw_text' ); ?></li>
                    <li><select type="dropdown" id="wpifw_scene_navigator" name="wpifw_scene_navigator"><?php wpifw_utils::wpifw_get_scenes_in_act(); ?></select></li>
                </ul>
            </div>
            </td>
            </tr>
            </tbody>
            </table>
            <div id="navigator"><?php 
			
				$args = array(
					'numberposts' => 1,
					'offset' => 0,
					'category' => 0,
					'orderby' => 'post_date',
					'order' => 'DESC',
					'post_type' => 'scene',
					'post_status' => 'publish',
					'suppress_filters' => true );
			
				$recent_scene = wp_get_recent_posts( $args, ARRAY_A );
				foreach( $recent_scene as $recent ) {
					$most_recent_id = $recent["ID"] ;
				}
				
				$args = array(
					'numberposts' => 1,
					'offset' => 0,
					'category' => 0,
					'orderby' => 'modified',
					'order' => 'DESC',
					'post_type' => 'scene',
					'post_status' => 'publish',
					'suppress_filters' => true );
			
				$modified_scene = wp_get_recent_posts( $args, ARRAY_A );
				foreach( $modified_scene as $recent ) {
					$last_modified_id = $recent["ID"] ;
				}
				
				
				$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/wp-json/wp/v2/scene/";
				$opening_scene_id = get_option( 'opening_scene', 'Select...' );
				$opening_scene = $base_url . $opening_scene_id;
				$mostRecent = $base_url . $most_recent_id;
				$lastModified = $base_url . $last_modified_id;

				?></div>
                <script>					
					openingScene = <?php echo json_encode( $opening_scene, JSON_FORCE_OBJECT ) ?>;
					mostRecent = <?php echo json_encode( $mostRecent, JSON_FORCE_OBJECT ) ?>;
					lastModified = <?php echo json_encode( $lastModified, JSON_FORCE_OBJECT ) ?>;
					baseUrl = <?php echo json_encode( $base_url, JSON_FORCE_OBJECT ) ?>;
				</script>
                
		</div><?php 
                    
	}
	
	/**
	 * Create the HTML output for the page (screen) displayed when the menu item is clicked.
	 * @link : http://codex.wordpress.org/Adding_Administration_Menus (Step 3)
	 */ 	
	public static function wpifw_admin_options() {
		
		if ( !current_user_can( 'edit_others_scenes' ) )  {
			
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		} 
		
		?>
		<div class="wrap">
            <h2>Story Options</h2>
            
                    <?php settings_errors(); 
					
					$display_saved = get_option( 'opening_scene_updated' );
					$icon_color = get_option( 'icon_color' );
					
					if( wpifw_utils::wpifw_isAdmin() ) {
					
						$allow_fight = get_option( 'allow_fight' );
						$allow_classes = get_option( 'allow_classes' );
						$allow_luck = get_option( 'allow_luck' );
						$allow_passphrase = get_option( 'allow_passphrase' );
						
						
						
						$allow_preface = get_option( 'allow_preface' );
						$wpifw_preface_raw = get_option( 'wpifw_preface' );
						$wpifw_preface = stripslashes( $wpifw_preface_raw );
						
						
					
					}
					
					if ( $display_saved == 1) {
						
						echo '<div class="updated"><p>' ;
							esc_html_e( 'Changes', 'wpifw_text' ); 
						echo '<strong>';
							esc_html_e( ' saved', 'wpifw_text' ); 
						echo '</strong></p></div>';	
						update_option( 'opening_scene_updated', 0 );
					} 
                    
 ?>

                <form method="post">
                
                <table class="form-table">
                	<tbody>
                    	<tr>
                        	<th scope="row">
                            	<label for="opening_scene">Opening Scene</label>
                            </th>
                            <td>
                                <select id="opening_scene" name="opening_scene"> 
                                    
                                    <?php 
                                    $opening_scene = get_option( 'opening_scene' );
                                    wpifw_utils::wpifw_get_all_scenes( $opening_scene ); ?>
                                    
                                </select>
                            </td>
                        </tr>
                        
                        <?php  if( wpifw_utils::wpifw_isAdmin() ) { 
							$allow_fight = get_option( 'allow_fight' );
							$allow_luck = get_option( 'allow_luck' ); 
							$allow_passphrase = get_option( 'allow_passphrase' ); 
						?>
                        <tr>
                            <th scope="row">
                                <label for="allow_fight">Allow Characters</label>
                            </th>
                            <td>
							<?php 
                                echo '<input type="checkbox" id="allow_fight" name="allow_fight"'; 
									if ( $allow_fight == 'on' ) {
										echo ' checked';
									}					
                                echo ' ></input>';
								?>
                            </td>
                        </tr>
                        <tr id="classesRow">
                            <th scope="row">
                                <label for="allow_classes">Allow Classes</label>
                            </th>
                            <td>
							<?php 
                                echo '<input type="checkbox" id="allow_classes" name="allow_classes"'; 
									if ( $allow_classes == 'on' ) {
										echo ' checked';
									}					
                                echo ' ></input>';
								?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="allow_luck">Allow luck</label>
                            </th>
                            <td>
							<?php 
                                echo '<input type="checkbox" id="allow_luck" name="allow_luck"'; 
									if ( $allow_luck == 'on' ) {
										echo ' checked';
									}					
                                echo ' ></input>';
								?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="allow_passphrase">Allow Passphrases</label>
                            </th>
                            <td>
							<?php 
                                echo '<input type="checkbox" id="allow_passphrase" name="allow_passphrase"'; 
									if ( $allow_passphrase == 'on' ) {
										echo ' checked';
									}					
                                echo ' ></input>';
								?>
                            </td>
                        </tr>

                        <?php  }  ?>
                        <tr>
                        	<th scope="row">
                                <label for="image_url">Cover Image</label>
                            </th>
                            <td>    
                               <?php echo '<input type="hidden" name="cover_image_url" id="cover_image_url" class="regular-text" value="';
                               	$cover_image_url = get_option( 'cover_image_url' );
								echo $cover_image_url;
                                echo '">';
								?>
                                <?php echo '<input type="hidden" name="cover_image_thumbnail_url" id="cover_image_thumbnail_url" class="regular-text" value="';
                               	$cover_image_thumbnail_url = get_option( 'cover_image_thumbnail_url' );
								echo $cover_image_thumbnail_url;
                                echo '">';
								?>
                                <?php echo '<input type="hidden" name="cover_image_id" id="cover_image_id" class="regular-text" value="';
                               	$cover_image_id = get_option( 'cover_image_id' );
								echo $cover_image_id;
                                echo '">';
								?>
                                <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Select Image">
                                <input type="button" name="clear-upload-btn" id="clear-upload-btn" class="button-secondary" value="Remove Image">
							</td>
                         </tr>
                         <?php 
						 	$cover_image_thumbnail_url = get_option( 'cover_image_thumbnail_url' );
							if ( $cover_image_thumbnail_url != null ) {
								echo '<tr id="cover_image_thumbnail_row"><th scope="row">';
							} else {
								echo '<tr id="cover_image_thumbnail_row" style="display:none"><th scope="row">';
							}
                         ?>
                                
                            </th>
                            <td>    
							<?php 
								$cover_image_thumbnail_url = get_option( 'cover_image_thumbnail_url' );
								if ( $cover_image_thumbnail_url != null ) {
									echo '<img src="';
									echo $cover_image_thumbnail_url;
								} else {
									echo '<img style="display:none" src="';
								}
                                	echo '" id="cover_image_thumbnail">';
							?>
							</td>
                    	</tr>
                        <?php 
                        if ( $cover_image_thumbnail_url != null ) {
							echo '<tr id="cover-overlay-row" style="display:table-row">';
						} else {
							echo '<tr id="cover-overlay-row" style="display:none">';
						}
						?>
                        
                        	<th scope="row">
                                <label for="image_url">Cover Image SVG Overlay</label>
                            </th>
                            <td>    
                               <?php echo '<input type="hidden" name="cover_image_overlay_url" id="cover_image_overlay_url" class="regular-text" value="';
                               	$cover_image_overlay_url = get_option( 'cover_image_overlay_url' );
								echo $cover_image_overlay_url;
                                echo '">';
								?>
                                <?php echo '<input type="hidden" name="cover_image_overlay_id" id="cover_image_overlay_id" class="regular-text" value="';
                               	$cover_image_overlay_id = get_option( 'cover_image_overlay_id' );
								echo $cover_image_overlay_id;
                                echo '">';
								?>
                                <?php echo '<input type="text" name="cover_image_overlay_title" id="cover_image_overlay_title" class="regular-text" value="';
                               	$cover_image_overlay_title = get_option( 'cover_image_overlay_title' );
								echo $cover_image_overlay_title;
                                echo '">';
								?>
                                <input type="button" name="cover-overlay-upload-btn" id="cover-overlay-upload-btn" class="button-secondary" value="Select Image">
                                <input type="button" name="clear-cover-overlay-upload-btn" id="clear-cover-overlay-upload-btn" class="button-secondary" value="Remove Image">
							</td>
                         </tr> 
                         <tr>
                             <th scope="row">
                             	<label for="icon_color">Scroll Icon Colour</label>
                             </th>  
                             <td>
                             	<input type="text" id="icon_color" name="icon_color" value="<?php echo $icon_color; ?>" />
                             </td> 
                         </tr>
                    </tbody>
                </table>
                
                <br />
                <hr />
                
                
                <table class="form-table">
	                <tbody>
    		            <tr>
            			    <th scope="row">
                    			<label for="allow_fight">Use Custom Preface</label>
                			</th>
                			<td>
							<?php 
                                echo '<input type="checkbox" id="allow_preface" name="allow_preface"'; 
									if ( $allow_preface == 'on' ) {
										echo ' checked';
										$content = $wpifw_preface;
									} else {									
										$content = ''; 
									}
                                echo ' ></input>';
								?>
							</td>
						</tr>
					</tbody>
				</table>

				<?php 	
				
				if ( $allow_preface == 'on' ) {	?>
				
					<div id="preface-editor" style="display:block">
                
               <?php } else { ?>
               
               		<div id="preface-editor" style="display:none">
               
               <?php } 
			
					
					$editor_id = 'wpifw_preface';
	
					$settings = array( 
						'media_buttons' => false,
						'editor_height' => 400,
						'wpautop' => true
					 );
					wp_editor( $content, $editor_id, $settings );
				
					?>
				
				</div>

               <?php submit_button();  ?>

			</form>  
                  
		<?php }
		
	/**
	 * Function to update opening scene option options 
	 * @link : 
	 */ 		
		
		
		function wpifw_update_opening_scene() {
			
			if ( isset( $_POST['opening_scene'] ) ) {
				update_option( 'opening_scene', $_POST['opening_scene'] );
				//don't update if not admin
				
				if(wpifw_utils::wpifw_isAdmin()){
					update_option( 'allow_fight', $_POST['allow_fight'] );
					update_option( 'allow_classes', $_POST['allow_classes'] );
					update_option( 'allow_luck', $_POST['allow_luck'] );
					update_option( 'allow_passphrase', $_POST['allow_passphrase'] );
					update_option( 'allow_preface', $_POST['allow_preface'] );
					update_option( 'wpifw_preface', $_POST['wpifw_preface'] );
				}
				
				update_option( 'icon_color', $_POST['icon_color'] );
				
				update_option( 'cover_image_id', $_POST['cover_image_id'] );
				update_option( 'cover_image_url', $_POST['cover_image_url'] );
				update_option( 'cover_image_thumbnail_url', $_POST['cover_image_thumbnail_url'] );
				update_option( 'cover_image_overlay_url', $_POST['cover_image_overlay_url'] );
				update_option( 'cover_image_overlay_id', $_POST['cover_image_overlay_id'] );
				update_option( 'cover_image_overlay_title', $_POST['cover_image_overlay_title'] );
				update_option( 'opening_scene_updated', 1 );
		  	} // end if
			
		} // wpifw_update_opening_scene
		
	
	/**
	 * Remove items from admin menu for IF Editor.
	 * @link : http://codex.wordpress.org/Function_Reference/remove_menu_page
	 */ 		
	public static function wpifw_remove_editor_menus(){
  
		if ( !current_user_can( 'manage_options' )) {
			remove_menu_page( 'edit-tags.php?taxonomy=category' );//Posts
			remove_submenu_page( 'index.php', 'my-sites.php' );//Posts
		
			$allow_fight = get_option( 'allow_fight' );
			//check if fight option selected and remove class and character if appropriate
			if ( $allow_fight != 'on' ) {
				remove_menu_page( 'edit.php?post_type=character_defaults' );//Classes	
				remove_menu_page( 'edit.php?post_type=characters' );//Characters
			}
			$allow_classes = get_option( 'allow_classes' );
			if ( $allow_classes != 'on' ) {
				remove_menu_page( 'edit.php?post_type=character_defaults' );//Classes
			}
		}
		  
	}
	
	/**
	 * Generate dice roll dropdown.
	 * @link :
	 */ 		
	public static function wpifw_generate_dice_options( $selector_type, $roll ) {
		
		if ($roll == '') {
			$roll = '2d6';
		}
		
		$dice = explode('d', $roll);
  
		if ( $selector_type == 'quantity' ) {
		
			for ( $i = 1; $i < 11; $i++ ) {
				$options .= '<option ';
				if ( $dice[0] == $i ) {
					$options .= ' selected';
				}
				$options .= '>' . $i . '</option>';	
			}
			return $options;
		
		} else if ( $selector_type == 'type' ) {
			
			$options  = '<option '; if ( $dice[1] == 4  ) { $options .= ' selected'; } $options .= '>4</option>';
			$options .= '<option '; if ( $dice[1] == 6  ) { $options .= ' selected'; } $options .= '>6</option>';
			$options .= '<option '; if ( $dice[1] == 8  ) { $options .= ' selected'; } $options .= '>8</option>';
			$options .= '<option '; if ( $dice[1] == 12 ) { $options .= ' selected'; } $options .= '>12</option>';
			$options .= '<option '; if ( $dice[1] == 20 ) { $options .= ' selected'; } $options .= '>20</option>';
			
			return $options;
		}
		  
	}

	/**
	 * Generate class dropdown options.
	 * @link :
	 */ 		
	public static function wpifw_generate_class_options( $characterClass ) {
		
		if ( strpos($characterClass,'random-') === 0 ) {
			//$old = $characterClass;
			$characterClass = str_replace( "random-", "", $characterClass );
			$randomCharClass = 1;
		//if this is a random strip the prefix
		}
		
		$args = array(
			'post_type' => 'character_defaults',
		);
		$query = new WP_Query( $args );
		
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$charClass = get_the_title();
				$charClassId = get_the_ID();
				if ( $randomCharClass == 1 ) {
					echo '<option value="random-';
				} else {
					echo '<option value="';
				}
				_e( $charClassId, 'wpifw_text' );
				echo '" ';
				if ( $characterClass == $charClassId ) { echo 'selected'; };
				echo '>' . $charClass . '</option>';
			}
		} else {
			// no posts found
		}
		//$characterClass = $old;
		/* Restore original Post Data */
		wp_reset_postdata();
		
	}

	/**
	 * Generate get Combatants for FIGHT follow_on.
	 * @link : 
	 */ 
	public static function wpifw_get_combatants_in_act( $thisCombatant ) {
		
		$args = array(
			'post_type' => 'characters',
		);
		$query = new WP_Query( $args );
		
		echo '<option>Select...</option>';
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$charName = get_the_title();
				$charId = get_the_ID();
				echo '<option value="';
				_e( $charId, 'wpifw_text' );
				echo '" ';
					if ( $thisCombatant == $charId ) { echo 'selected'; };
				echo '>' . $charName . '</option>';
			}
			
		} 
		//$allow_classes = get_option( 'allow_classes' );
		//if ( $allow_classes == 'on' ) {
		$allow_classes = get_option( 'allow_classes' );
		$count_classes = wp_count_posts('character_defaults');
		if ( $allow_classes == 'on' && $count_classes->publish > 0 ) {	
			if ( strpos($thisCombatant,'random-') === 0 ) {
				echo '<option selected>Random...</option>';
			} else {
				echo '<option>Random...</option>';
			}
		}
	}

	/**
	 * Generate json pcDefaults.
	 * @link : http://codex.wordpress.org/Displaying_Posts_Using_a_Custom_Select_Query
	 */ 		
	public static function wpifw_generate_json_pcDefaults() {
		
		$args = array(
			'post_type' => 'character_defaults',
		);
		$query = new WP_Query( $args );

		$options = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$newoption = get_post_meta( get_the_ID(), '_character_defaults' );
				$strippedOption = $newoption[0];
				$strippedOption = (object)$strippedOption;
				array_push( $options, $strippedOption );
			}
		}

		echo json_encode($options);
		

	}
	
	public static function wpifw_new_user_meta( $blog_id, $user_id ) {
		
 		add_user_to_blog($blog_id, $user_id, 'if_admin' );
		
	}
	
	public static function wpifw_if_admin_editable_roles( $all_roles ) {
		
		if( !current_user_can( 'manage_network' )) {
		
			foreach ( $all_roles as $name => $role ) {
				  if (!isset($role['capabilities']['edit_scenes']) || isset($role['capabilities']['manage_options'])) {
					  unset($all_roles[$name]);
				 }
			}
		}
		
		return $all_roles;
		
	}
	
	/* 
	*
	*
	* POINTERS
	*
	*
	*/
	
	public static function wpifw_pointer_load( $hook_suffix ) {
 
    // Don't run on WP < 3.3
    if ( get_bloginfo( 'version' ) < '3.3' )
        return;
 
    $screen = get_current_screen();
    $screen_id = $screen->id;
 
    // Get pointers for this screen
    $pointers = apply_filters( 'wpifw_admin_pointers-' . $screen_id, array() );
 
    if ( ! $pointers || ! is_array( $pointers ) )
        return;
 
    // Get dismissed pointers
    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
    $valid_pointers =array();
 
    // Check pointers and remove dismissed ones.
    foreach ( $pointers as $pointer_id => $pointer ) {
 
        // Sanity check
        if ( in_array( $pointer_id, $dismissed ) || empty( $pointer )  || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) )
            continue;
 
        $pointer['pointer_id'] = $pointer_id;
 
        // Add the pointer to $valid_pointers array
        $valid_pointers['pointers'][] =  $pointer;
    }
 
    // No valid pointers? Stop here.
    if ( empty( $valid_pointers ) )
        return;
 
    // Add pointers style to queue.
    wp_enqueue_style( 'wp-pointer' );
 
    // Add pointers script to queue. Add custom script.
    wp_enqueue_script( 'wpifw-pointer', plugins_url( '../js/wpifw-pointer.js', __FILE__ ), array( 'wp-pointer' ) );
 
    // Add pointer options to script.
    wp_localize_script( 'wpifw-pointer', 'wpifwPointer', $valid_pointers );
}

	public static function wpifw_register_pointer_testing( $p ) {
		$p['xyz140'] = array(
			'target' => '#opening_scene',
			'options' => array(
				'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
					__( 'Title' ,'plugindomain'),
					__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.','plugindomain')
				),
				'position' => array( 'edge' => 'top', 'align' => 'left' )
			)
		);
		return $p;
	}
	
	// Custom Dashboard
	public static function wpifw_custom_dashboard() {
		$screen = get_current_screen();
		if( $screen->base == 'dashboard' ) {
			
			include 'wpifw-dashboard.php';
		}
	}
	
	public static function wpifw_scene_published() {
		
		$count_scenes = wp_count_posts('scene');
		
		if ( $count_scenes->publish = 1 ) {
			
			$args = array(
			'numberposts' => 1,
			'offset' => 0,
			'category' => 0,
			'orderby' => 'post_date',
			'order' => 'ASC',
			'post_type' => 'scene',
			'post_status' => 'publish',
			'suppress_filters' => true );
	
			$recent_characters = wp_get_recent_posts( $args, ARRAY_A );
			
			foreach( $recent_characters as $recent ) {
				update_option( 'opening_scene', $recent["ID"] );
			}
				
		} else {
			
			echo '<script type="text/javascript">alert("' . $count_scenes->publish . '");</script>';
			die();
		}
		
	}
	
// End Class
}
?>