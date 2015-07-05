<?php

/**
* Plugin Name: Geolib
* Plugin URI: http://geotonics.com/#geolib
* Description: A PHP mini-framework
* Version: 1.0
* Author: Peter Pitchford
* Author URI: http://geolib.com/
**/

include "geolib.php";



/**
* Add geoLib options page to Settings section of Wordpress admin
*/
function geoform_register_settings() {
	add_option( 'geolib_dbout', '0');
	register_setting( 'default', 'geolib_dbout' ); 
	add_option( 'geolib_debugging', null);
	register_setting( 'default', 'geolib_debugging' ); 
} 

add_action( 'admin_init', 'geoform_register_settings' );
 
function geoform_register_options_page() {
	add_options_page('Geolib Options', 'Geolib', 'manage_options', 'geolib-options', 'geoform_options_page');
}
add_action('admin_menu', 'geoform_register_options_page');
 
 
function geoform_options_page() {
	?>
<div class="wrap">
	
	<h2>GeoLib Options</h2>
	<form method="post" action="options.php"> 
        <?php settings_fields( 'default' ); ?>
		<?php echo h3("Debug Settings").
		    p(
		        geoCheckbox("geolib_debugging","Turn Debugging on",get_option('geolib_debugging'))
		    )
		    //.geoRadios("geolib_dbout",array("Add to debugging array","Echo debug","Return debug"),get_option('geolib_dbout'),"plainlist")
		    ;
		 submit_button(); 
		 echo GeoDebug::vars();
		 ?>
	</form>
</div>
<?php
}


function display_geoDebugVars() {
    echo geoDebug::vars();
}

add_action( 'wp_footer', 'display_geoDebugVars' );


set_geolib_debug_session();
/**
*  Create debug session variables for Geolib debudding from Wordpress options
*/
function set_geolib_debug_session(){ 
   // if(current_user_can( 'manage_options' )){
        Geo::setSession("isDebugSession",get_option('geolib_debugging')); 
        Geo::setSession("geoDbOut",get_option('geolib_dbout'));
   
}

