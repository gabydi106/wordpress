<?php
/*** Register Custom Post Types 'tournament' and 'golfcourse' for Golf Deputy ***/
add_action( 'init', 'golf_match_init' );
function golf_match_init() {
	$labels = array(
		'name'               => _x( __( 'Tournaments', 'golfdeputy' ), __( 'Tournaments', 'golfdeputy' ), 'golfdeputy' ),
		'singular_name'      => _x( __( 'Tournament', 'golfdeputy' ), __( 'Tournament', 'golfdeputy' ), 'golfdeputy' ),
		'menu_name'          => _x( 'Golf Deputy', 'Golf Deputy', 'golfdeputy' ),
		'name_admin_bar'     => _x( __( 'Tournament', 'golfdeputy' ), __( 'Tournament', 'golfdeputy' ), 'golfdeputy' ),
		'add_new'            => _x( __( 'Add New Tournament', 'golfdeputy' ), 'tournament', 'golfdeputy' ),
		'add_new_item'       => __( __( 'Add New Tournament', 'golfdeputy' ), 'golfdeputy' ),
		'new_item'           => __( __( 'New Tournament', 'golfdeputy' ), 'golfdeputy' ),
		'edit_item'          => __( __( 'Edit Tournament', 'golfdeputy' ), 'golfdeputy' ),
		'view_item'          => __( __( 'View Tournament', 'golfdeputy' ), 'golfdeputy' ),
		'all_items'          => __( __( 'All Tournaments', 'golfdeputy' ), 'golfdeputy' ),
		'search_items'       => __( __( 'Search Tournaments', 'golfdeputy' ), 'golfdeputy' ),
		'parent_item_colon'  => __( __( 'Parent Tournament:', 'golfdeputy' ), 'golfdeputy' ),
		'not_found'          => __( __( 'No tournament found.', 'golfdeputy' ), 'golfdeputy' ),
		'not_found_in_trash' => __( __( 'No tournament found in Trash.', 'golfdeputy' ), 'golfdeputy' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true, // menu items after the main are are controlled in settings.php
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'tournament', 'with_front' => TRUE ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'thumbnail', 'revisions')
	);

	register_post_type( 'tournament', $args );
	
	
	$labels2 = array(
		'name'               => _x( __( 'Golf Courses', 'golfdeputy' ), __( 'Golf Courses', 'golfdeputy' ), 'golfdeputy' ),
		'singular_name'      => _x( __( 'Golf Course', 'golfdeputy' ), __( 'Golf Course', 'golfdeputy' ), 'golfdeputy' ),
		'name_admin_bar'     => _x( __( 'Golf Course', 'golfdeputy' ), __( 'Golf Course', 'golfdeputy' ), 'golfdeputy' ),
		'add_new'            => _x( __( 'Add New Course', 'golfdeputy' ), 'golfcourse', 'golfdeputy' ),
		'add_new_item'       => __( __( 'Add New Course', 'golfdeputy' ), 'golfdeputy' ),
		'new_item'           => __( __( 'New Course', 'golfdeputy' ), 'golfdeputy' ),
		'edit_item'          => __( __( 'Edit Course', 'golfdeputy' ), 'golfdeputy' ),
		'view_item'          => __( __( 'View Course', 'golfdeputy' ), 'golfdeputy' ),
		'all_items'          => __( __( 'All Courses', 'golfdeputy' ), 'golfdeputy' ),
		'search_items'       => __( __( 'Search Courses', 'golfdeputy' ), 'golfdeputy' ),
		'parent_item_colon'  => __( __( 'Parent Course:', 'golfdeputy' ), 'golfdeputy' ),
		'not_found'          => __( __( 'No courses found.', 'golfdeputy' ), 'golfdeputy' ),
		'not_found_in_trash' => __( __( 'No courses found in Trash.', 'golfdeputy' ), 'golfdeputy' )
	);

	$args2 = array(
		'labels'             => $labels2,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => false, // menu items after the main are are controlled in settings.php
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'golfcourse', 'with_front' => TRUE ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'supports'           => array( 'title', 'thumbnail' ),
	);

	register_post_type( 'golfcourse', $args2 );


	$labels3 = array(
		'name'               => _x( __( 'Golfers', 'golfdeputy' ), __( 'Golfers', 'golfdeputy' ), 'golfdeputy' ),
		'singular_name'      => _x( __( 'Golfer', 'golfdeputy' ), __( 'Golfer', 'golfdeputy' ), 'golfdeputy' ),
		'name_admin_bar'     => _x( __( 'Golfer', 'golfdeputy' ), __( 'Golfer', 'golfdeputy' ), 'golfdeputy' ),
		'add_new'            => _x( __( 'Add New Golfer', 'golfdeputy' ), 'golfer', 'golfdeputy' ),
		'add_new_item'       => __( __( 'Add New Golfer', 'golfdeputy' ), 'golfdeputy' ),
		'new_item'           => __( __( 'New Golfer', 'golfdeputy' ), 'golfdeputy' ),
		'edit_item'          => __( __( 'Edit Golfer', 'golfdeputy' ), 'golfdeputy' ),
		'view_item'          => __( __( 'View Golfer', 'golfdeputy' ), 'golfdeputy' ),
		'all_items'          => __( __( 'All Golfers', 'golfdeputy' ), 'golfdeputy' ),
		'search_items'       => __( __( 'Search Golfers', 'golfdeputy' ), 'golfdeputy' ),
		'parent_item_colon'  => __( __( 'Parent Golfer:', 'golfdeputy' ), 'golfdeputy' ),
		'not_found'          => __( __( 'No golfers found.', 'golfdeputy' ), 'golfdeputy' ),
		'not_found_in_trash' => __( __( 'No golfers found in Trash.', 'golfdeputy' ), 'golfdeputy' )
	);

	$args3 = array(
		'labels'             => $labels3,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => false, // menu items after the main are are controlled in settings.php
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'golfer', 'with_front' => TRUE ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'supports'           => array( 'title', 'revisions' ),
	);

	register_post_type( 'golfer', $args3 );
	
}

/*** Add Tee Name to Golf Course listing on Admin Screen ***/
add_filter('manage_edit-golfcourse_columns', 'add_new_course_columns');
function add_new_course_columns($gallery_columns) {
    $new_columns['cb'] = '<input type="checkbox" />';
     
    $new_columns['title'] = _x(__( 'Course Name', 'golfdeputy' ), 'column name');
	$new_columns['teename'] = _x(__( 'Tee Name', 'golfdeputy' ), 'teename'); 
    $new_columns['date'] = _x(__( 'Date', 'golfdeputy' ), 'column name');
 
    return $new_columns;
}

// Add to admin_init function
add_action('manage_golfcourse_posts_custom_column', 'manage_golfcourse_columns', 10, 2);
 
function manage_golfcourse_columns($column_name, $id) {
    global $wpdb;
	global $post;
    switch ($column_name) {
    case 'teename':
		echo get_post_meta( $id, 'teename', true);
        break;
    default:
        break;
    } // end switch
} 

// Removes tournament or course from custom tables when removed from wp_posts
add_action( 'before_delete_post', 'golf_deputy_custom_table_delete', 10 );
function golf_deputy_custom_table_delete( $post_id ) {
    global $wpdb;
	global $post;
	$posttype = get_post_type( $post_id );
	if ($posttype == 'tournament') {
		$ids = get_post_meta( $post_id, 'tournament_id', true);
		$sql = $wpdb->prepare("DELETE from {$wpdb->golf_deputy_tournaments} WHERE tournament_id = %d", $ids);
	} else {
		$ids = get_post_meta( $post_id, 'course_id', true);
		$sql = $wpdb->prepare("DELETE from {$wpdb->golf_deputy_courses} WHERE course_id = %d", $ids);
	}
    if( !$wpdb->query( $sql ) )
         return false;
		 
    return true;
} 
?>