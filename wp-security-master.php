<?php
/*
Plugin Name: WP Security Master
Plugin URI: https://vuongnguyen.net/
Description: 🔐 <strong>Features: </strong> <br> ✔️ Securing your WordPress by deliberately disabling all functions in dashboard. <br> ✔️ Re-activate the site by providing the hashed password. It secures your site by providing another layer of authorization.
Author: <strong>Vuong Nguyen</strong>
Version: 1.0.2
Author URI: https://vuongnguyen.net
License: GPLv2 or later
*/

// Exit if accessed directly
if (!defined('ABSPATH' )){
	exit();	
} 

## Guardian
		###############################################################
		###############################################################
		###############################################################
if(!function_exists('wp_security_master_guardian')||!function_exists('wp_security_master_activate')||!function_exists('wp_security_master_menu')||!function_exists('wp_security_master_configure')||!function_exists('wp_security_master_changestate')||!function_exists('wp_security_master_configure_current')||!function_exists('wp_security_master_hiding')||!function_exists('wp_security_master_remove_menu')||!function_exists('wp_security_master_deactivate')){		
}else{
	throw new Exception("Cannot activate the plugin as there is a conflict of function names", 1);
}

function wp_security_master_guardian(){
	/*Loading the pre-defined core functions*/
	if(!function_exists('wp_get_current_user')) {
	    include(ABSPATH . "wp-includes/pluggable.php"); 
	}
	if(current_user_can('administrator') === false){
		throw new Exception("Error Processing Request. The current user is not granted to access", 1);
	}

	return null;
}


if(!function_exists('wp_get_current_user')) {
	    include(ABSPATH . "wp-includes/pluggable.php"); 
	}

## Activating the plugin
		###############################################################
		###############################################################
		###############################################################
function wp_security_master_activate() {
	wp_security_master_guardian();
	$wp_security_master_version = '1.0.1';
	/*Adding to the Option table*/
	if(get_option('wp_security_master') !== false){
		update_option('wp_security_master', $wp_security_master_version);
	}else{
		add_option('wp_security_master', $wp_security_master_version);	
	}

	if(get_option('wp_security_master_activate_state') != null){
		update_option('wp_security_master_activate_state', false);
	}else{
		add_option('wp_security_master_activate_state', false);	
	}
	
		
}
register_activation_hook( __FILE__, 'wp_security_master_activate');


# Adding a new menu
		##############################################################
		##############################################################
		##############################################################
function wp_security_master_menu() 
{
    add_menu_page (
            'Master Security Setting', 
            'WP Security Master', 
            'manage_options', 
            'security_master_setting', 
            'wp_security_master_configure', /*callback to handle data when clicking on the menu*/
            'dashicons-lock' //*Link to the image*/ https://developer.wordpress.org/resource/dashicons/#lock
            // plugins_url('/public/images/shield_16.png', /*Link to the image*/
            
    );
}
 
add_action('admin_menu', 'wp_security_master_menu');

# Configurate when clicking on the setting menu
		##############################################################
		##############################################################
		##############################################################
function wp_security_master_configure() {
	/*Guardian if current user is admin*/
	wp_security_master_guardian();
	
	/*Guardian with nonce*/
	$nonce = wp_create_nonce("wp_security_master_something_not_to_be_predicted_ABCXYZabcxyz");
	if(!empty($_REQUEST['wps_nonce'])){
		$wps_nonce = htmlentities($_REQUEST['wps_nonce']);
		$wps_nonce = filter_var($wps_nonce, FILTER_SANITIZE_STRING);
		$x = wp_verify_nonce($wps_nonce, 'wp_security_master_something_not_to_be_predicted_ABCXYZabcxyz');
		if($x != true){
			?>
			<script>alert("Your session is expired. Kindly re-configure with new setting!")</script>;

			<script>window.location.href = "<?= admin_url();?>";</script>
			<?php
		}
			
	}

	/*If clicking on the "Cancel" button*/
	if(!empty($_POST['submit_cancel'])){		
		?>
		 <script>window.location.href = "<?= admin_url();?>";</script>
		<?php
	}

	/*Current state*/
	if (wp_security_master_configure_current() > time()) {

		$getseconds = wp_security_master_configure_current();
		
		$gettime = date("Y-m-d H:i:s", $getseconds);

		$getseconds = $getseconds - time(); // Getting the amount of seconds to pass to javascript

		$current_time = date("Y-m-d H:i:s", time());

		// $timetotrigger = "{$year} years {$getdate['mon']} months {$getdate['mday']} days {$getdate['hours']} hours {$getdate['minutes']} minutes {$getdate['seconds']} seconds left to disable dashboard!";

		$timetotrigger = "Disable dashboard at {$gettime}, current is {$current_time}!";
	}else{
		$timetotrigger = "Expired. Reconfigure to disable the dashboard automatically!";
	}

	/*Uncheck if changing the passcode*/
	$state = get_option('wp_security_master_activate_state');

	function wp_security_master_changestate(){
		
		$changestate = ( isset( $_POST['wps_check'] ) ? true : false);

		return $changestate;
	}

	/*Configure*/
	$error = array();
	if (!empty($_POST['submit_savechange'])){
        $password 			= trim($_POST['wps_password']);        
        $password_confirm 	= trim($_POST['wps_password_confirm']);
        
        $wp_security_master_time_value 	= htmlentities(strip_tags(trim($_POST['wp_security_master_time_value'])));
        if(preg_match("/[A-Za-z]+/", $wp_security_master_time_value) == true){
        	$error[] = "The amount of time should only be digit!";	
        }
        /*Not neccessary*/
		$wp_security_master_time_value 	= (float)filter_var($wp_security_master_time_value, FILTER_SANITIZE_NUMBER_FLOAT);

		/*if uncheck to change password*/
        if(wp_security_master_changestate() !== true){
        	if($password !== $password_confirm){
        	$error[] = "The passcode does not match!";        	
	        }        
	        if(strlen($password) < 8){
	        	$error[] = "The passcode should have more than 8 characters!";	
	        }	
        }        
        
        if($wp_security_master_time_value <= 0){
        	$error[] = "The amount of time should be larger than 0!";	
        }

        if(count($error) < 1){
        	$wp_security_master_key		= password_hash($password, PASSWORD_DEFAULT);

        	/*If check ticket is uncheck*/
        	if(wp_security_master_changestate() !== true){
        		if(get_option('wp_security_master_key') !== false){
					update_option('wp_security_master_key', $wp_security_master_key);
				}else{
					add_option('wp_security_master_key', $wp_security_master_key);	
				}
        	}
	       
			/*Current time */
			$wp_security_master_time 	= time();
			if(get_option('wp_security_master_time') !== false){
				update_option('wp_security_master_time', $wp_security_master_time);
			}else{
				add_option('wp_security_master_time', $wp_security_master_time);	
			}
			
			/*hour many time_unit*/
			if(get_option('wp_security_master_time_value') !== false){
				update_option('wp_security_master_time_value', $wp_security_master_time_value);
			}else{
				add_option('wp_security_master_time_value', $wp_security_master_time_value);	
			}
			
			/*minutes --> hours --> days---> months--> years*/
			$wp_security_master_time_unit 		= htmlentities(strip_tags($_POST['wp_security_master_time_unit']));
			if($wp_security_master_time_unit === 'minutes'){
				$wp_security_master_time_unit = 60;
			}elseif ($wp_security_master_time_unit == 'hours') {
				$wp_security_master_time_unit = 60*60;
			}elseif ($wp_security_master_time_unit == 'days') {
				$wp_security_master_time_unit = 60*60*24;
			}elseif ($wp_security_master_time_unit == 'month') {
				$wp_security_master_time_unit = 60*60*24*30;
			}else{
				$wp_security_master_time_unit = 60*60*24*30*12;
			}			
			if(get_option('wp_security_master_time_unit') !== false){
				update_option('wp_security_master_time_unit', $wp_security_master_time_unit);
			}else{
				add_option('wp_security_master_time_unit', $wp_security_master_time_unit);	
			}

			update_option('wp_security_master_activate_state', true);

			//echo "<script>alert('Your configuration is saved.')</script>";
			
			?>
			<script>window.location.href = "<?= admin_url();?>";</script>
			<?php
	        
        }
    }

	require('includes/views/mainmenu.php');
}

#Get the current configuration 
		##############################################################
		##############################################################
		##############################################################
function wp_security_master_configure_current(){
	$wp_security_master_time 		= get_option("wp_security_master_time");
	$wp_security_master_time_unit 	= get_option("wp_security_master_time_unit");
	$wp_security_master_time_value 	= get_option("wp_security_master_time_value");
	if($wp_security_master_time !== null && $wp_security_master_time_unit != null && $wp_security_master_time_value != null){
		$amount 						= (float)$wp_security_master_time_unit * (float)$wp_security_master_time_value;
		$trigger 						= (float)$wp_security_master_time + (float)$amount;		
		return $trigger;	
	}
	
}

#Re-authenticate to open
		##############################################################
		##############################################################
		##############################################################
function wp_security_master_hiding() {
	/*Guardian with nonce*/
	$wps_nonce_request = wp_create_nonce("wp_security_master_something_ABCXYZabcxyz_is_not_to_be_predicted_");
	if(!empty($_REQUEST['wps_nonce_request'])){
		$wps_nonce_request = htmlentities($_REQUEST['wps_nonce_request']);
		$wps_nonce_request = filter_var($wps_nonce_request, FILTER_SANITIZE_STRING);
		$x = wp_verify_nonce($wps_nonce_request, 'wp_security_master_something_ABCXYZabcxyz_is_not_to_be_predicted_');
		if($x != true){
			?>
			<script>alert("Your session is expired. Kindly re-authenticate!")</script>;

			<script>window.location.href = "<?= admin_url();?>";</script>
			<?php
		}
			
	}
	/*If clicking on the Cancel button*/
	if(!empty($_POST['cancel_wsm'])){
		/*
		Header https://stackoverflow.com/questions/8028957/how-to-fix-headers-already-sent-error-in-php
		wp_safe_redirect( site_url() ) or header() do not work because headers have been sent. header_remove() does not work too.
		*/
		echo('<script>window.location.href = "home";</script>');
	}
	
	/*If submitting*/
	if (!empty($_POST['submit_wsm'])){
        $password = $_POST['password'];
        $hash = get_option("wp_security_master_key");
        $verify = password_verify($password, $hash);
        if($verify === true){
        	$time = time() + 3600;
        	update_option('wp_security_master_time', $time);
        	
        }else{
        	echo "<span style='padding-left: 5%'>The passcode is incorrect!</span>";
        }

    }
   
	$flag 			= wp_security_master_configure_current();
	$config_flag 	= get_option('wp_security_master_activate_state');

    if($flag < time() && $config_flag == true){    
	?>
		<!-- FORM -->
		<div id = 'hashform' style="padding-left: 5%; padding-top: 0%!important">
		<p>The system is temporarily locked. </p>
		<form method="POST" action="">
		<input type="hidden" name="wps_nonce_request" value="<?= $wps_nonce_request?>">
		<input type="password" name="password" size="25">
		<input type="submit" name="submit_wsm" id="submit" class="button button-primary" value="🔐 Authenticate"  />
		<input type="submit" name="cancel_wsm" id="cancel" class="button button-delete" value="Cancel"  />
		</form>
		<?php
		
		echo "<br>";
		die('Please provide your passcode when activating WP Security Master to proceed.');
		echo "</div>";

	}/*if verify statement*/
	
}
add_action('wp_before_admin_bar_render', 'wp_security_master_hiding' );

#Automatically hiding dashboard;
		##############################################################
		##############################################################
		##############################################################
$flag 			= wp_security_master_configure_current();
$config_flag 	= get_option('wp_security_master_activate_state');
if($flag < time() && $config_flag == true){
	/*This function will be called to hide the menu when activating the plugin*/
	function wp_security_master_remove_menu()
	{
	    remove_menu_page( 'index.php' );                  //Dashboard
		remove_menu_page( 'jetpack' );                    //Jetpack* 
		remove_menu_page( 'edit.php' );                   //Posts
		remove_menu_page( 'upload.php' );                 //Media
		remove_menu_page( 'edit.php?post_type=page' );    //Pages
		remove_menu_page( 'edit-comments.php' );          //Comments
		remove_menu_page( 'themes.php' );                 //Appearance
		remove_menu_page( 'plugins.php' );                //Plugins
		remove_menu_page( 'users.php' );                  //Users
		remove_menu_page( 'tools.php' );                  //Tools
		remove_menu_page( 'options-general.php' );        //Settings
		remove_menu_page( 'profile.php' );
		    
	}
	add_action('admin_menu', 'wp_security_master_remove_menu');

}

## Deactivating the plugin
		###############################################################
		###############################################################
		###############################################################
function wp_security_master_deactivate() {	
	/*Updating the value to in the Option table*/
	if(get_option('wp_security_master') !== false){
		update_option('wp_security_master', null);
	}
	if(get_option('wp_security_master_key') !== false){
		update_option('wp_security_master_key', null);
	}

	if(get_option('wp_security_master_time') !== false){
		update_option('wp_security_master_time', null);
	}

	if(get_option('wp_security_master_time_value') !== false){
		update_option('wp_security_master_time_value', null);
	}

	if(get_option('wp_security_master_time_unit') !== false){
		update_option('wp_security_master_time_unit', null);
	}

	if(get_option('wp_security_master_activate_state') !== false){
		update_option('wp_security_master_activate_state', null);
	}
	
}
register_deactivation_hook( __FILE__, 'wp_security_master_deactivate');



# Update for version 1.0.1 May 2019
		###############################################################
		###############################################################
		###############################################################

/*Update 1.0.2 - Hiding the countdown timer till the configuration is made*/

if($config_flag == true){

	add_action( 'admin_bar_menu', 'toolbar_link_lock_', 999 );

	function toolbar_link_lock_( $wp_admin_bar ) {
		$args = array(
			'id'    => 'my_page_lock',
			'title' => 'Lock in : <span style="color: yellow; font-weight:bold" id="timetolock"></span><span id = "secondwordid"> second</span> ',
			'href'  => '#',
			'meta'  => array( 'class' => 'my-toolbar-page-link' )
		);
		$wp_admin_bar->add_node( $args );
	}


	function wp_security_master_toolbar_item($wp_admin_bar){
	    $wp_admin_bar->add_node(array("id"=>"parent_node_1", "title"=> '<span id = "extendid" style="color: yellow; font-weight:bold">Extend</span> <span style="color: #eeba30;" id = "ajaxtime"></span>', "href"=>"#"));

	    //first group. provide parent node id if you are grouping child nodes using this group.
	    $wp_admin_bar->add_group(array("id"=>"group_1", "parent"=>"parent_node_1"));
	    //second group.
	    //$wp_admin_bar->add_group(array("id"=>"group_2", "parent"=>"parent_node_1"));

	    //when we want to put a node inside a group then make its parent array element assigned to group id.
	    $wp_admin_bar->add_node(array("id"=>"child_node_1", "title"=>"<div data-value = '0' onclick = 'getValue(this)'>Lock now</div>", "href"=>"#", "parent"=>"group_1"));
	    $wp_admin_bar->add_node(array("id"=>"child_node_2", "title"=>"<div data-value = '0.5' onclick = 'getValue(this)'>30 minutes</div>", "href"=>"#", "parent"=>"group_1"));
	    $wp_admin_bar->add_node(array("id"=>"child_node_3", "title"=>"<div data-value = '1' onclick = 'getValue(this)'>1 hour</div>", "href"=>"#", "parent"=>"group_1"));
	    $wp_admin_bar->add_node(array("id"=>"child_node_4", "title"=>"<div data-value = '3' onclick = 'getValue(this)'>3 hours</div>", "href"=>"#", "parent"=>"group_1"));
	    $wp_admin_bar->add_node(array("id"=>"child_node_5", "title"=>"<div data-value = '24' onclick = 'getValue(this)'>24 hours</div>", "href"=>"#", "parent"=>"group_1"));

	}

	add_action("admin_bar_menu", "wp_security_master_toolbar_item", 1000);

}



/*
* Register the third-part scripts
* Note: wp_register_script() and wp_enqueue_script() are used to call the custome js file. script_id_security_master is the ID of the js file only. They need to * be wrapped in a function and then add_action using HOOK admin_enqueue_scripts
* @see: https://wordpress.stackexchange.com/questions/137104/wp-enqueue-script-was-called-incorrectly
*/
function annotate_script_customize() {
	wp_register_script("script_id_security_master", plugin_dir_url(__FILE__).'includes/js/custom.js');	

	/*Pass the data to Ajax call in includes/js/custom.js, using the ID script_id_security_master*/

	/*object_annotate is the js object in the ajax call*/
	wp_localize_script('script_id_security_master', 'object_annotate', array(
		'url' 		=> admin_url('admin-ajax.php'),
		'ajax_nonce' => wp_create_nonce('wp_security_master_somethingelse_ABCXYZ000111'),		
	));

	wp_enqueue_script('script_id_security_master');
  
}

/**
* Noted" 3 "endpoints" for wp_enqueue_script() which are wp_enqueue_scripts (PLURAL, có "s") for the frontend, login_enqueue_scripts for the login screen, 
* admin_enqueue_scripts for * the  admin dashboard
*/
add_action( 'admin_enqueue_scripts', 'annotate_script_customize' );


/*wp_ajax_X, X is the function handle the Ajax call, here is getMessage()
* getMessageAction is the callback in the jQuery
*/
add_action('wp_ajax_getMessageAction', 'getMessageCallBack');

function getMessageCallBack(){
	
	/*Check the nonce_data and verify the nonce_data to make sur the AJAX call is valid*/
    check_ajax_referer( 'wp_security_master_somethingelse_ABCXYZ000111', 'nonce_data' );

    $hour_update_unit 				= (isset($_POST['hourupdate'])) ? esc_attr($_POST['hourupdate']) : '';    

    $wp_security_master_time_value 	=  $hour_update_unit;

    /*Update new hour value*/		
	if(get_option('wp_security_master_time_value') !== false){
		update_option('wp_security_master_time_value', $wp_security_master_time_value);
	}else{
		add_option('wp_security_master_time_value', $wp_security_master_time_value);	
	}

    /*Update the new time for the clock when AJAX called, then pass to javascript*/
    /*Current time */
	$wp_security_master_time 	= time();
	if(get_option('wp_security_master_time') !== false){
		update_option('wp_security_master_time', $wp_security_master_time);
	}else{
		add_option('wp_security_master_time', $wp_security_master_time);	
	}

	/*Update the unit*/
	$wp_security_master_time_unit = 60*60;
	if(get_option('wp_security_master_time_unit') !== false){
				update_option('wp_security_master_time_unit', $wp_security_master_time_unit);
		}else{
				add_option('wp_security_master_time_unit', $wp_security_master_time_unit);	
	}
	
  
	wp_die();

}

/*Getting the current second to pass to the JavaScript => custome.js*/
if (wp_security_master_configure_current() > time()) {

	$getsecond_clock = wp_security_master_configure_current();
			
	$getsecond_clock = $getsecond_clock - time();

	add_action('admin_footer', 'my_admin_footer_function_security_master');

	function my_admin_footer_function_security_master() {

		global $getsecond_clock;

		/*Passing from php and then to js via a hidden span*/
		echo "<div id = 'getsecond_clock' style = 'display:none'>$getsecond_clock</div>";

	}
}

add_action('admin_footer', 'my_admin_add_security_master_js');

function my_admin_add_security_master_js() {

$scriptfooter = <<<JS
<script>

window.onload = function load(){

  /*Get the time value from php*/
  var second_from_php = document.getElementById("getsecond_clock");

  seconds = second_from_php.innerText;

  
  var el = document.getElementById("time");

  var elhai = document.getElementById("timetolock");

  function incrementSeconds() {
    
    if(elhai == null){
      return;
    }

      seconds -= 1;
      if (seconds >= 0) {
        
       if(el !== null){
        el.innerText = seconds + " seconds left!";  
       }
        
        elhai.innerText = seconds;

      }else{
        
        if(el !== null){
        el.innerText = " It is locked now!";
       }  
        elhai.innerText = " Locked!!";
      }
      
  }

  setInterval(incrementSeconds, 1000);

};

	</script>
JS;

echo $scriptfooter;
}


?>
