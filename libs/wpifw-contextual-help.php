<!--<div id="screen" style="position: fixed; z-index: 10000; color: #fff;"><?php /*?><?php echo $screen->id; ?><?php */?></div>-->



<?php 

if ( $screen->id == 'dashboard' ) {
	
    $id = 'dashboardid';
    $title = 'Dashboard';
    $help = '<p>' . __( 'Dashboard help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );
	
	$screen->set_help_sidebar(
		__('<h4>External Links</h4><a href="http://choosablepath.net/resources/support/dashboard/">The dashboard</a>')
	);
		
} else if ( $screen->id == 'dashboard_page_wpifw_options' ) {
	
    $id = 'dashboardOptionsid';
    $title = 'Options';
    $help = '<p>' . __( '<h4>Options</h4><p>This is the elp</p>' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );	
	
	$id = 'openingsceneid';
    $title = 'Opening Scene';
    $help = '<p>' . __( '<h4>Setting the Opening Scene</h4>' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );	
	
	if ( wpifw_utils::wpifw_isAdmin() ) { 
	
		$id = 'allowfightid';
		$title = 'Allow Characters';
		$help = '<p>' . __( '<h4>Creating Characters</h4>' ) . '</p>';
		
		$screen->add_help_tab( array( 
		   'id' => $id,            //unique id for the tab
		   'title' => $title,      //unique visible title for the tab
		   'content' => $help  //actual help text
		) );
		
		$id = 'allowclassesid';
		$title = 'Allow Classes';
		$help = '<p>' . __( '<h4>Creating Character Classes</h4>' ) . '</p>';
		
		$screen->add_help_tab( array( 
		   'id' => $id,            //unique id for the tab
		   'title' => $title,      //unique visible title for the tab
		   'content' => $help  //actual help text
		) );
		
		$id = 'allowluckid';
		$title = 'Allow Luck';
		$help = '<p>' . __( '<h4>Incorporating Chance</h4>' ) . '</p>';
		
		$screen->add_help_tab( array( 
		   'id' => $id,            //unique id for the tab
		   'title' => $title,      //unique visible title for the tab
		   'content' => $help  //actual help text
		) );
		
		$id = 'allowpasssphraseid';
		$title = 'Allow Passphrases';
		$help = '<p>' . __( '<h4>Using Passphrases</h4>' ) . '</p>';
		
		$screen->add_help_tab( array( 
		   'id' => $id,            //unique id for the tab
		   'title' => $title,      //unique visible title for the tab
		   'content' => $help  //actual help text
		) );
	
	}
	
	$id = 'coverimageid';
    $title = 'Cover image';
    $help = '<p>' . __( '<h4>Setting a Cover Image</h4>' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );
	
	$screen->set_help_sidebar(
		__('<h4>External Links</h4><a href="http://choosablepath.net/resources/support/options/">Site options</a>')
	);			

} else if ( $screen->id == 'scene' ) {
	
    $id = 'editsceneid';
    $title = 'Editing Scenes';
    $help = '<p>' . __( 'Edit scene help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );
	
	$id = 'scenetitleid';
    $title = 'Setting a Title';
    $help = '<p>' . __( 'Scene Title help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );
	
	$id = 'scenecontentid';
    $title = 'Adding Content';
    $help = '<p>' . __( 'Adding Scene content help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );
	
	$id = 'sceneactionsid';
    $title = 'Defining Actions';
    $help = '<p>' . __( 'Defining Scene Actions' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );
	
	$id = 'chooseactionid';
    $title = '&#149; Multiple Choice';
    $help = '<p>' . __( 'Multiple choice help' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );
	
	$allow_fight = get_option( 'allow_fight' );
	$allow_luck = get_option( 'allow_luck' );
	$allow_passphrase = get_option( 'allow_passphrase' );
	
	if ( $allow_fight == 'on' ) {
		
		$id = 'combatactionid';
		$title = '&#149; Combat';
		$help = '<p>' . __( 'Fight help' ) . '</p>';
		
		$screen->add_help_tab( array( 
		   'id' => $id,            //unique id for the tab
		   'title' => $title,      //unique visible title for the tab
		   'content' => $help  //actual help text
		) );
	
	}

	if ( $allow_luck == 'on' ) {
	
		$id = 'luckactionid';
		$title = '&#149; Luck';
		$help = '<p>' . __( 'luck help' ) . '</p>';
		
		$screen->add_help_tab( array( 
		   'id' => $id,            //unique id for the tab
		   'title' => $title,      //unique visible title for the tab
		   'content' => $help  //actual help text
		) );
	
	}
	
	if ( $allow_passphrase == 'on' ) {
	
		$id = 'passphraseactionid';
		$title = '&#149; Passphrase';
		$help = '<p>' . __( 'Passphrase help' ) . '</p>';
		
		$screen->add_help_tab( array( 
		   'id' => $id,            //unique id for the tab
		   'title' => $title,      //unique visible title for the tab
		   'content' => $help  //actual help text
		) );
	
	}
	

} else if ( $screen->id == 'edit-scene' ) {
	
    $id = 'editsceneid';
    $title = 'Edit Scene';
    $help = '<p>' . __( 'Edit scene help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );	
	
} else if ( $screen->id == 'edit-act' ) {
	
    $id = 'editactid';
    $title = 'Edit Act';
    $help = '<p>' . __( 'Edit act help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );		
	
} else if ( $screen->id == 'toplevel_page_wpifw_navigator' ) {
	
    $id = 'navigator';
    $title = 'Navigator';
    $help = '<p>' . __( 'Navigator help.' ) . '</p>';	
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );	

} else if ( $screen->id == 'upload' ) {
	
    $id = 'uploadid';
    $title = 'Media Library';
    $help = '<p>' . __( 'media library help.' ) . '</p>';

	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );		
	
} else if ( $screen->id == 'media' ) {
	
    $id = 'mediaid';
    $title = 'Upload new media';
    $help = '<p>' . __( 'Upload new media help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );	
	
} else if ( $screen->id == 'users' ) {
	
    $id = 'usersid';
    $title = 'Users';
    $help = '<p>' . __( 'Users help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );	
	
} else if ( $screen->id == 'user' ) {
	
    $id = 'userid';
    $title = 'User';
    $help = '<p>' . __( 'Add User help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );		
	
} else if ( $screen->id == 'profile' ) {
	
    $id = 'profileid';
    $title = 'Profile';
    $help = '<p>' . __( 'profile help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );
	
} else if ( $screen->id == 'edit-characters' ) {
	
    $id = 'listcharacters';
    $title = 'Characters';
    $help = '<p>' . __( 'Character help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );	
	
} else if ( $screen->id == 'characters' ) {
	
    $id = 'characterid';
    $title = 'Adding Characters';
    $help = '<p>' . __( 'Add character help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );
	
} else if ( $screen->id == 'edit-character_defaults' ) {
	
    $id = 'classid';
    $title = 'Character classes';
    $help = '<p>' . __( 'List classes help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );
	
} else if ( $screen->id == 'character_defaults' ) {
	
    $id = 'characterdefid';
    $title = 'Adding classes';
    $help = '<p>' . __( 'Adding classes help.' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );									
	
} else {
	
    $id = 'default';
    $title = 'Helpful Links';
    $help = '<p>' . __( 'Urls to pages' ) . '</p>';
	
	$screen->add_help_tab( array( 
	   'id' => $id,            //unique id for the tab
	   'title' => $title,      //unique visible title for the tab
	   'content' => $help  //actual help text
	) );	
    
}

?>