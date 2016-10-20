<?php
/*
Plugin Name: Golf Deputy
Description: A golf tournament plugin, allowing users to input live scores on the front-end and track entire tournaments.
Version: 1.4
Author: Take The Leap Designs
Plugin URI: http://golfdeputy.com
Text Domain: golfdeputy
Domain Path: /languages
Artwork design: parts of logos and vector imagery designed by freepik.com
*/

/*  Cookies used:
	$_SESSION variable to store login/PIN data. Set in WP admin area.
	Expires: 12 hours. This can be adjusted in the functions/single-tournament.php file, in the first main loop.
*/

// Translations
function my_plugin_load_plugin_textdomain() {
    load_plugin_textdomain( 'golfdeputy', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'my_plugin_load_plugin_textdomain' );

include(dirname( __FILE__ ) . '/functions/api.php');
register_activation_hook( __FILE__, 'golf_deputy_install' );

/*** Registers Custom Post Types (tournament, golf course) ***/
include(dirname( __FILE__ ) . '/functions/admin/register-cpt.php');

/*** The Settings page and menu items ***/
include(dirname( __FILE__ ) . '/functions/admin/settings.php');

/*** All the front-end, client-facing styles, functions, etc ***/
include(dirname( __FILE__ ) . '/functions/front-end.php');

/*** All the admin functions for a tournament: information, match vs stroke play, etc. ***/
include(dirname( __FILE__ ) . '/functions/admin/tournament-information.php');

/*** All the admin functions for creating a golf course ***/
include(dirname( __FILE__ ) . '/functions/admin/golf-course.php');

/*** All the admin functions for creating a golfer profile ***/
include(dirname( __FILE__ ) . '/functions/admin/golfer.php');

/*** All the admin functions for advertising and sponsors ***/
include(dirname( __FILE__ ) . '/functions/admin/advertising.php');

/*** Saves all of the data from the admin area to custom tables, post meta data, etc ***/
include(dirname( __FILE__ ) . '/functions/admin/save-data.php');

/*** Allows import of CSV files; sample CSV found in plugin DIR ***/
include(dirname( __FILE__ ) . '/functions/admin/import-csv.php');


/*** Add Links to Plugins Page ***/
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'golfdeputy_plugin_action_links' );

function golfdeputy_plugin_action_links( $links ) {
   $links[] = '<a href="'. get_admin_url(null, 'edit.php?post_type=tournament&page=golf_deputy') .'">' . __('Settings','menu-settings') . '</a>';
   $links[] = '<a href="http://golfdeputy.com/help/" target="_blank">Help</a>';
   return $links;
}

?>