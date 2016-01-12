<?php
/*
 * Plugin Name: WP IF Writer
 * Plugin URI: www.chooseablepath.net/if-writer
 * Description: Interactive Fiction Writer for WordPress
 * Version: 0.0.1
 * Author: Peter Gordon
 * Author URI: www.chooseablepath.net
 * Text Domain: wp_if_text
 * Domain Path: /locale/
 * License: GPL2
 */ 
   
/*
 * License:
 * ==============================================================================
 *
 * Copyright 2015 Peter Gordon  (email : hello@petergordon.net)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * 
 * Requirement
 * ==============================================================================
 * This plugin requires WordPress >= x.x beta and tested with PHP Interpreter >= x.x
 *
 */


/*
 * Version:	0.0.1
 * @var:	
 *
 */
 
//	Define constant
define('WP_IF_WRITER_VERSION', '0.0.1');

//	If not activated, pass contents of the output buffer to the DB
//	immediately after plugin activation is attempted. The hook does not 
//	fire if a plugin is silently activated (such as during an update).
if(get_option('wpifw_activated') != 1){
	add_action('activated_plugin','wpifw_save_activation_error');
	function wp_if_save_activation_error() {
		update_option('wpifw_plugin_act_error',  ob_get_contents());
	}
}

//	Check for non-existence of debug constant. If true:
//	require Class and register plugin activation and 
//	deactivation hook functions. Run install actions.
if(! defined('WP_IF_WRITER_VERSION_ONLY_MODE')){
	require_once('libs/wpifw_Class.php');
	register_activation_hook( WP_PLUGIN_DIR . '/wp-if-writer/wpifw.php', 'wpifwriter::installPlugin');
	register_deactivation_hook( WP_PLUGIN_DIR . '/wp-if-writer/wpifw.php', 'wpifwriter::uninstallPlugin');
	wpifwriter::install_actions();
}
?>