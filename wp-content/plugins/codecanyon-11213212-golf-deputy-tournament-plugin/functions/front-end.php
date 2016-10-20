<?php
/*** Register front-end styles ***/
function golf_deputy_main_style() {
	wp_register_style( 'GolfDeputyMainStyles', plugins_url('../css/golf-deputy.css', __FILE__) );
	wp_enqueue_style( 'GolfDeputyMainStyles' );

	$options = get_option( 'golf_deputy_settings' );
	if (!empty($options['golf_deputy_custom_css'])) {
		$golf_deputy_custom_css = $options['golf_deputy_custom_css'];
		wp_add_inline_style( 'GolfDeputyMainStyles', $golf_deputy_custom_css );
	}
}
add_action( 'wp_enqueue_scripts', 'golf_deputy_main_style' );

/*** [leaderboard] shortcode with tournament_id input value ***/
function leaderboard_shortcode( $tournamentid ) {
	ob_start();
	include_once dirname( __FILE__ ) . '/leaderboard.php';
    $output = ob_get_clean();
    return $output;
}
add_shortcode( 'golf-deputy-leaderboard', 'leaderboard_shortcode' );


/*** Load custom template for the tournament post type, for single templates (acrhive uses the theme default) ***/
function gd_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'tournament') {
          $single_template = dirname( __FILE__ ) . '/single-tournament.php';
     }

     if ($post->post_type == 'golfcourse') {
          $single_template = dirname( __FILE__ ) . '/single-golfcourse.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'gd_custom_post_type_template' );



/*** Add the Meta Boxes functions ***/
add_action( 'add_meta_boxes', 'add_golf_match_metaboxes' );

function add_golf_match_metaboxes() {
	// Tournament Metaboxes, Tournament Page
	add_meta_box('golf_match_info', __( "Tournament Information", "golfdeputy" ), 'golf_match_info', 'tournament', 'normal', 'default');
	add_meta_box('golf_match_teams', __( "Rounds and Player Information", "golfdeputy" ), 'golf_match_teams', 'tournament', 'normal', 'default');
	add_meta_box('golf_match_advertising', __( "Sponsors / Advertising", "golfdeputy" ), 'golf_match_advertising', 'tournament', 'normal', 'default');
	
	// Golf Course Metaboxes, Golf Course Page
	add_meta_box('golf_course_info', __( "Course Information", "golfdeputy" ), 'golf_course_info', 'golfcourse', 'normal', 'default');
	add_meta_box('golf_course_holes', __( "Hole Information", "golfdeputy" ), 'golf_course_holes', 'golfcourse', 'normal', 'default');

	// Golfer Metaboxes, Golfer Page
	add_meta_box('golfer_info', __( "Golfer Information", "golfdeputy" ), 'golfer_info', 'golfer', 'normal', 'default');
	add_meta_box('golfer_tournaments', __( "Tournaments Played", "golfdeputy" ), 'golfer_tournaments', 'golfer', 'normal', 'default');	
}


/*** Create PHP $_SESSION ***/
add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
	session_cache_limiter('public'); // allows back buttons to work, notably in Firefox
	if(!session_id()) {
		session_start();
	}
}

function myEndSession() {
	session_destroy ();
}	

/*** Changes default title text on tournament and golf course ***/
function change_default_title_text( $title ){
     $screen = get_current_screen();
 
     if  ( 'tournament' == $screen->post_type ) {
          $title = __( "Enter tournament name", "golfdeputy" );
     } else if ('golfcourse' == $screen->post_type) {
		  $title = __( "Enter course name", "golfdeputy" );
	 } else if ('golfer' == $screen->post_type) {
		  $title = __( "Enter golfer name", "golfdeputy" );
	 }
     return $title;
}
 
add_filter( 'enter_title_here', 'change_default_title_text' );

?>