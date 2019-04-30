<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion: 
 *
 * @link       http://vuongnguyen.net
 * @since      1.0.0
 *
 * @package    WP Master Security
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if(get_option('wp_security_master') !== false){
		delete_option('wp_security_master');
	}
	if(get_option('wp_security_master_key') !== false){
		delete_option('wp_security_master_key');
	}

	if(get_option('wp_security_master_time') !== false){
		delete_option('wp_security_master_time');
	}

	if(get_option('wp_security_master_time_value') !== false){
		delete_option('wp_security_master_time_value');
	}

	if(get_option('wp_security_master_time_unit') !== false){
		delete_option('wp_security_master_time_unit');
	}

	if(get_option('wp_security_master_activate_state') !== false){
		delete_option('wp_security_master_activate_state');
	}
	
