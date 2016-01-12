<div class="wrap">
<div id="dashboard-header">Dashboard</div>
<div id="welcome-panel"  class="welcome-panel">
<a class="welcome-panel-close" href="<?php echo esc_url( admin_url( '?welcome=0' ) ); ?>"><?php _e( 'Dismiss' ); ?></a>
	<div class="welcome-panel-content">
	<h3><?php _e( 'Welcome to Choosable Path!' ); ?></h3>
	<div class="welcome-panel-column-container">
	<div class="welcome-panel-column">
    <h4><?php _e( 'First Steps' ); ?></h4>
		<a class="button button-primary button-medium hide-if-customize" href="<?php echo admin_url( 'index.php?page=wpifw_options' ); ?>"><?php _e( 'Set story options' ); ?></a>
		<?php 
		if ( wpifw_utils::wpifw_isAdmin() ) { ?>
       		<a class="button button-primary button-medium hide-if-customize" href="<?php echo admin_url( 'user-new.php' ); ?>"><?php _e( 'Add co-authors' ); ?></a>
		<?php } 
		?>

	</div>
	<div class="welcome-panel-column">
		<h4><?php _e( 'Next Steps' ); ?></h4>
		<ul>
			<?php 
			$count_scenes = wp_count_posts('scene');
			if ( $count_scenes->publish == 0 ) {
			?>
            	<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Write your first scene' ) . '</a>', admin_url( 'post-new.php?post_type=scene' ) ); ?></li>
			<?php } else { ?>
            	<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Write a scene' ) . '</a>', admin_url( 'post-new.php?post_type=scene' ) ); ?></li>
            <?php } ?>
            <li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add a cover image' ) . '</a>', admin_url( 'index.php?page=wpifw_options' ) ); ?></li>
			<li><?php printf( '<a href="%s" class="welcome-icon welcome-view-site">' . __( 'Read your story' ) . '</a>', home_url( '/' ) ); ?></li>
		</ul>
	</div>
	<div class="welcome-panel-column welcome-panel-last">
		<h4><?php _e( 'More Actions' ); ?></h4>
		<ul>
			<?php
			$allow_fight = get_option( 'allow_fight' );
			$allow_classes = get_option( 'allow_classes' );
				
			if ( $allow_fight == 'on' ) { ?> 
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Create a character' ) . '</a>', __( 'post-new.php?post_type=characters' ) ); ?></li>
            <?php } 
			if ( $allow_classes == 'on' ) { ?>
           	 <li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add a character class' ) . '</a>', __( 'post-new.php?post_type=character_defaults' ) ); ?></li>
			<?php } ?>
			<li><?php printf( '<a href="%s" class="welcome-icon welcome-learn-more">' . __( 'Learn more about Choosable Path' ) . '</a>', __( 'http://choosablepath.net/resources/support/' ) ); ?></li>

            
		</ul>
	</div>
	</div>
	</div>

</div>
</div>