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
function geolib_register_settings() {
	add_option( geo_user_option("db_out"), '0');
	register_setting( 'default', geo_user_option("db_out") ); 
	add_option( geo_user_option('debugging'), null);
	register_setting( 'default', geo_user_option('debugging') );
	add_option( geo_option('debug_ip_address'), null);
	register_setting( 'default', geo_option('debug_ip_address') );  
	set_geolib_debug_session();
} 

add_action( 'admin_init', 'geolib_register_settings' );
add_action( 'init', 'set_geolib_debug_session' );
 
function geolib_register_options_page() {
	add_options_page('Geolib Options', 'Geolib', 'manage_options', 'geolib-options', 'geolib_options_page');
}
add_action('admin_menu', 'geolib_register_options_page');
function geo_option($name){
    return "geolib_".$name;
}

function geo_user_option($name){
    return "geolib_".$name."_".get_current_user_id();
}
 
function geolib_options_page() {
	?>
<div class="wrap">
	
	<h2>GeoLib Options</h2>
	<form method="post" action="options.php"> 
        <?php settings_fields( 'default' ); ?>
		<?php 
		echo h3("Debug Settings").
		    p(
		        geoCheckbox(geo_user_option('debugging'),"Turn Debugging on (for this user)",get_option(geo_user_option('debugging')))
		    ).
		    p(
		        geoCheckbox(geo_option('debug_ip_address'),"Turn Debugging on (for this user's ip address)",get_option(geo_option('debug_ip_address')), null,$_SERVER['REMOTE_ADDR'])
		    )
		    //.geoRadios("geolib_dbout",array("Add to debugging array","Echo debug","Return debug"),get_option(geo_user_option("db_out")),"plainlist")
		    ;
		 submit_button(); 
		 echo GeoDebug::vars();
		 ?>
	</form>
</div>
<?php
}

function display_geoDebugVars (){
    if(is_admin()){
        $style='margin:0 16px 16px 180px';
    } else {
        global $template;
        geoDb($template,'template');
        $style="";
    }
    
    echo geoDebug::vars(null,null,$style);
}

add_action( 'wp_footer', 'display_geoDebugVars' );
add_action( 'admin_footer', 'display_geoDebugVars' );

/**
*  Create debug session variables for Geolib debudding from Wordpress options
*/
function set_geolib_debug_session(){ 
	$ipOption=get_option(geo_option('debug_ip_address'));
	
	if($ipOption==$_SERVER['REMOTE_ADDR']){
    	GeoDebug::debug(true);
    } else {
    	 GeoDebug::debug(get_option(geo_user_option('debugging'))); 
    }
	
    //GeoDebug::debug(get_option(geo_user_option('debugging'))); 
    //Geo::setSession("geoDbOut",get_option(geo_user_option("db_out")));
}

