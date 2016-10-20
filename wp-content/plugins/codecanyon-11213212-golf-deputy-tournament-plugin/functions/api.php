<?php
/*** Create initial SQL tables for Golf Deputy ***/
global $golf_deputy_version;
$golf_deputy_version = '1.4';
add_action( 'init', 'golf_deputy_register_tables', 1 );

function golf_deputy_install() {
	global $wpdb;
	global $golf_deputy_version;
	
	golf_deputy_register_tables();

	$charset_collate = $wpdb->get_charset_collate();
	
	$sql = "CREATE TABLE {$wpdb->golf_deputy_tournaments} (
		tournament_id mediumint(9) NOT NULL AUTO_INCREMENT,
		name text NOT NULL,
		course text NOT NULL,
		team1name tinytext NOT NULL,
		team2name tinytext NOT NULL,
		pin tinytext NOT NULL,
		scoringsystem tinyint(1) DEFAULT 0,
		scoringtype tinyint(1) DEFAULT 0,
		numberofteams tinyint(1) DEFAULT 2,
		rounds smallint(3) NOT NULL,
		roundnames text NOT NULL,
		team1score decimal(11,1) DEFAULT 0,
		team2score decimal(11,1) DEFAULT 0,
		UNIQUE KEY tournament_id (tournament_id)
	) $charset_collate;";
	// note that advertising images for tournaments are stored in post_meta of the tournament post, specifically in advertising.php
	
	$sql2 = "CREATE TABLE {$wpdb->golf_deputy_matchups} (
		matchup_id mediumint(9) NOT NULL AUTO_INCREMENT,
		tournament_id mediumint(9) NOT NULL,
		round_id mediumint(9) NOT NULL,
		win_points mediumint(9) NOT NULL,
		player1 tinytext NOT NULL,
		player2 tinytext NOT NULL,
		playerpin tinytext NOT NULL,
		handicap smallint(2),
		hole1 tinyint DEFAULT 0,
		hole2 tinyint DEFAULT 0,
		hole3 tinyint DEFAULT 0,
		hole4 tinyint DEFAULT 0,
		hole5 tinyint DEFAULT 0,
		hole6 tinyint DEFAULT 0,
		hole7 tinyint DEFAULT 0,
		hole8 tinyint DEFAULT 0,
		hole9 tinyint DEFAULT 0,
		hole10 tinyint DEFAULT 0,
		hole11 tinyint DEFAULT 0,
		hole12 tinyint DEFAULT 0,
		hole13 tinyint DEFAULT 0,
		hole14 tinyint DEFAULT 0,
		hole15 tinyint DEFAULT 0,
		hole16 tinyint DEFAULT 0,
		hole17 tinyint DEFAULT 0,
		hole18 tinyint DEFAULT 0,
		holegross1 tinyint DEFAULT 0,
		holegross2 tinyint DEFAULT 0,
		holegross3 tinyint DEFAULT 0,
		holegross4 tinyint DEFAULT 0,
		holegross5 tinyint DEFAULT 0,
		holegross6 tinyint DEFAULT 0,
		holegross7 tinyint DEFAULT 0,
		holegross8 tinyint DEFAULT 0,
		holegross9 tinyint DEFAULT 0,
		holegross10 tinyint DEFAULT 0,
		holegross11 tinyint DEFAULT 0,
		holegross12 tinyint DEFAULT 0,
		holegross13 tinyint DEFAULT 0,
		holegross14 tinyint DEFAULT 0,
		holegross15 tinyint DEFAULT 0,
		holegross16 tinyint DEFAULT 0,
		holegross17 tinyint DEFAULT 0,
		holegross18 tinyint DEFAULT 0,
		stablefordpoints1 tinyint DEFAULT 0,
		stablefordpoints2 tinyint DEFAULT 0,
		stablefordpoints3 tinyint DEFAULT 0,
		stablefordpoints4 tinyint DEFAULT 0,
		stablefordpoints5 tinyint DEFAULT 0,
		stablefordpoints6 tinyint DEFAULT 0,
		stablefordpoints7 tinyint DEFAULT 0,
		stablefordpoints8 tinyint DEFAULT 0,
		stablefordpoints9 tinyint DEFAULT 0,
		stablefordpoints10 tinyint DEFAULT 0,
		stablefordpoints11 tinyint DEFAULT 0,
		stablefordpoints12 tinyint DEFAULT 0,
		stablefordpoints13 tinyint DEFAULT 0,
		stablefordpoints14 tinyint DEFAULT 0,
		stablefordpoints15 tinyint DEFAULT 0,
		stablefordpoints16 tinyint DEFAULT 0,
		stablefordpoints17 tinyint DEFAULT 0,
		stablefordpoints18 tinyint DEFAULT 0,
		currentscore tinytext,
		overall mediumint(3),
		closed tinyint(1) DEFAULT 0,
		teamwinner tinyint(1) DEFAULT 0,
		thru tinytext NOT NULL,	
		linkedto mediumint(9) DEFAULT 0,			
		UNIQUE KEY matchup_id (matchup_id)
	) $charset_collate;";
	
	$sql3 = "CREATE TABLE {$wpdb->golf_deputy_courses} (
		course_id mediumint(9) NOT NULL AUTO_INCREMENT,
		coursename text NOT NULL,
		courseaddress text NOT NULL,
		coursecity text NOT NULL,
		coursestate text NOT NULL,
		coursephone text NOT NULL,
		measurement text NOT NULL,
		teename text NOT NULL,
		holeyardage1 smallint DEFAULT 0,
		holeyardage2 smallint DEFAULT 0,
		holeyardage3 smallint DEFAULT 0,
		holeyardage4 smallint DEFAULT 0,
		holeyardage5 smallint DEFAULT 0,
		holeyardage6 smallint DEFAULT 0,
		holeyardage7 smallint DEFAULT 0,
		holeyardage8 smallint DEFAULT 0,
		holeyardage9 smallint DEFAULT 0,
		holeyardage10 smallint DEFAULT 0,
		holeyardage11 smallint DEFAULT 0,
		holeyardage12 smallint DEFAULT 0,
		holeyardage13 smallint DEFAULT 0,
		holeyardage14 smallint DEFAULT 0,
		holeyardage15 smallint DEFAULT 0,
		holeyardage16 smallint DEFAULT 0,
		holeyardage17 smallint DEFAULT 0,
		holeyardage18 smallint DEFAULT 0,
		holepar1 tinyint DEFAULT 0,
		holepar2 tinyint DEFAULT 0,
		holepar3 tinyint DEFAULT 0,
		holepar4 tinyint DEFAULT 0,
		holepar5 tinyint DEFAULT 0,
		holepar6 tinyint DEFAULT 0,
		holepar7 tinyint DEFAULT 0,
		holepar8 tinyint DEFAULT 0,
		holepar9 tinyint DEFAULT 0,
		holepar10 tinyint DEFAULT 0,
		holepar11 tinyint DEFAULT 0,
		holepar12 tinyint DEFAULT 0,
		holepar13 tinyint DEFAULT 0,
		holepar14 tinyint DEFAULT 0,
		holepar15 tinyint DEFAULT 0,
		holepar16 tinyint DEFAULT 0,
		holepar17 tinyint DEFAULT 0,
		holepar18 tinyint DEFAULT 0,
		handicaplabel text NOT NULL,
		holehandicap1 smallint DEFAULT 0,
		holehandicap2 smallint DEFAULT 0,
		holehandicap3 smallint DEFAULT 0,
		holehandicap4 smallint DEFAULT 0,
		holehandicap5 smallint DEFAULT 0,
		holehandicap6 smallint DEFAULT 0,
		holehandicap7 smallint DEFAULT 0,
		holehandicap8 smallint DEFAULT 0,
		holehandicap9 smallint DEFAULT 0,
		holehandicap10 smallint DEFAULT 0,
		holehandicap11 smallint DEFAULT 0,
		holehandicap12 smallint DEFAULT 0,
		holehandicap13 smallint DEFAULT 0,
		holehandicap14 smallint DEFAULT 0,
		holehandicap15 smallint DEFAULT 0,
		holehandicap16 smallint DEFAULT 0,
		holehandicap17 smallint DEFAULT 0,
		holehandicap18 smallint DEFAULT 0,
		UNIQUE KEY course_id (course_id)
	) $charset_collate;";

	$sql4 = "CREATE TABLE {$wpdb->golf_deputy_golfers} (
		golfer_id mediumint(9) NOT NULL AUTO_INCREMENT,
		golfername text NOT NULL,
		handicap smallint(2),
		bio mediumtext NOT NULL,
		UNIQUE KEY golfer_id (golfer_id)
	) $charset_collate;";
	// note that golfer images are stored in post_meta of the golfer post, specifically in golfer.php

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	dbDelta( $sql2 );
	dbDelta( $sql3 );
	dbDelta( $sql4 );

	add_option( 'golf_deputy_version', $golf_deputy_version );
}

function golf_deputy_register_tables() {
	global $wpdb;
	$wpdb->golf_deputy_tournaments = "{$wpdb->prefix}golf_deputy_tournaments";
	$wpdb->golf_deputy_matchups = "{$wpdb->prefix}golf_deputy_matchups";
	$wpdb->golf_deputy_courses = "{$wpdb->prefix}golf_deputy_courses";
	$wpdb->golf_deputy_golfers = "{$wpdb->prefix}golf_deputy_golfers";	
}

function golf_deputy_upgradecheck() {
	global $wpdb;
	global $golf_deputy_version;
	$current_version = $golf_deputy_version;
	$installed_version = get_option('golf_deputy_version');

	if( !$installed_version ){
       //No installed version - we'll assume its just been freshly installed
       add_option( 'golf_deputy_version', $golf_deputy_version );
 
   	} elseif( $installed_version != $current_version ){

   		$sql11 = "CREATE TABLE {$wpdb->golf_deputy_tournaments} (
			tournament_id mediumint(9) NOT NULL AUTO_INCREMENT,
			name text NOT NULL,
			course text NOT NULL,
			team1name tinytext NOT NULL,
			team2name tinytext NOT NULL,
			pin tinytext NOT NULL,
			scoringsystem tinyint(1) DEFAULT 0,
			scoringtype tinyint(1) DEFAULT 0,
			numberofteams tinyint(1) DEFAULT 2,
			rounds smallint(3) NOT NULL,
			roundnames text NOT NULL,
			team1score decimal(11,1) DEFAULT 0,
			team2score decimal(11,1) DEFAULT 0,
			UNIQUE KEY tournament_id (tournament_id)
		) $charset_collate;";

		$sql12 = "CREATE TABLE {$wpdb->golf_deputy_matchups} (
			matchup_id mediumint(9) NOT NULL AUTO_INCREMENT,
			tournament_id mediumint(9) NOT NULL,
			round_id mediumint(9) NOT NULL,
			win_points mediumint(9) NOT NULL,
			player1 tinytext NOT NULL,
			player2 tinytext NOT NULL,
			playerpin tinytext NOT NULL,
			handicap smallint(2),
			hole1 tinyint DEFAULT 0,
			hole2 tinyint DEFAULT 0,
			hole3 tinyint DEFAULT 0,
			hole4 tinyint DEFAULT 0,
			hole5 tinyint DEFAULT 0,
			hole6 tinyint DEFAULT 0,
			hole7 tinyint DEFAULT 0,
			hole8 tinyint DEFAULT 0,
			hole9 tinyint DEFAULT 0,
			hole10 tinyint DEFAULT 0,
			hole11 tinyint DEFAULT 0,
			hole12 tinyint DEFAULT 0,
			hole13 tinyint DEFAULT 0,
			hole14 tinyint DEFAULT 0,
			hole15 tinyint DEFAULT 0,
			hole16 tinyint DEFAULT 0,
			hole17 tinyint DEFAULT 0,
			hole18 tinyint DEFAULT 0,
			holegross1 tinyint DEFAULT 0,
			holegross2 tinyint DEFAULT 0,
			holegross3 tinyint DEFAULT 0,
			holegross4 tinyint DEFAULT 0,
			holegross5 tinyint DEFAULT 0,
			holegross6 tinyint DEFAULT 0,
			holegross7 tinyint DEFAULT 0,
			holegross8 tinyint DEFAULT 0,
			holegross9 tinyint DEFAULT 0,
			holegross10 tinyint DEFAULT 0,
			holegross11 tinyint DEFAULT 0,
			holegross12 tinyint DEFAULT 0,
			holegross13 tinyint DEFAULT 0,
			holegross14 tinyint DEFAULT 0,
			holegross15 tinyint DEFAULT 0,
			holegross16 tinyint DEFAULT 0,
			holegross17 tinyint DEFAULT 0,
			holegross18 tinyint DEFAULT 0,
			stablefordpoints1 tinyint DEFAULT 0,
			stablefordpoints2 tinyint DEFAULT 0,
			stablefordpoints3 tinyint DEFAULT 0,
			stablefordpoints4 tinyint DEFAULT 0,
			stablefordpoints5 tinyint DEFAULT 0,
			stablefordpoints6 tinyint DEFAULT 0,
			stablefordpoints7 tinyint DEFAULT 0,
			stablefordpoints8 tinyint DEFAULT 0,
			stablefordpoints9 tinyint DEFAULT 0,
			stablefordpoints10 tinyint DEFAULT 0,
			stablefordpoints11 tinyint DEFAULT 0,
			stablefordpoints12 tinyint DEFAULT 0,
			stablefordpoints13 tinyint DEFAULT 0,
			stablefordpoints14 tinyint DEFAULT 0,
			stablefordpoints15 tinyint DEFAULT 0,
			stablefordpoints16 tinyint DEFAULT 0,
			stablefordpoints17 tinyint DEFAULT 0,
			stablefordpoints18 tinyint DEFAULT 0,
			currentscore tinytext,
			overall mediumint(3),
			closed tinyint(1) DEFAULT 0,
			teamwinner tinyint(1) DEFAULT 0,
			thru tinytext NOT NULL,	
			linkedto mediumint(9) DEFAULT 0,			
			UNIQUE KEY matchup_id (matchup_id)
		) $charset_collate;";

		$sql13 = "CREATE TABLE {$wpdb->golf_deputy_courses} (
			course_id mediumint(9) NOT NULL AUTO_INCREMENT,
			coursename text NOT NULL,
			courseaddress text NOT NULL,
			coursecity text NOT NULL,
			coursestate text NOT NULL,
			coursephone text NOT NULL,
			measurement text NOT NULL,
			teename text NOT NULL,
			holeyardage1 smallint DEFAULT 0,
			holeyardage2 smallint DEFAULT 0,
			holeyardage3 smallint DEFAULT 0,
			holeyardage4 smallint DEFAULT 0,
			holeyardage5 smallint DEFAULT 0,
			holeyardage6 smallint DEFAULT 0,
			holeyardage7 smallint DEFAULT 0,
			holeyardage8 smallint DEFAULT 0,
			holeyardage9 smallint DEFAULT 0,
			holeyardage10 smallint DEFAULT 0,
			holeyardage11 smallint DEFAULT 0,
			holeyardage12 smallint DEFAULT 0,
			holeyardage13 smallint DEFAULT 0,
			holeyardage14 smallint DEFAULT 0,
			holeyardage15 smallint DEFAULT 0,
			holeyardage16 smallint DEFAULT 0,
			holeyardage17 smallint DEFAULT 0,
			holeyardage18 smallint DEFAULT 0,
			holepar1 tinyint DEFAULT 0,
			holepar2 tinyint DEFAULT 0,
			holepar3 tinyint DEFAULT 0,
			holepar4 tinyint DEFAULT 0,
			holepar5 tinyint DEFAULT 0,
			holepar6 tinyint DEFAULT 0,
			holepar7 tinyint DEFAULT 0,
			holepar8 tinyint DEFAULT 0,
			holepar9 tinyint DEFAULT 0,
			holepar10 tinyint DEFAULT 0,
			holepar11 tinyint DEFAULT 0,
			holepar12 tinyint DEFAULT 0,
			holepar13 tinyint DEFAULT 0,
			holepar14 tinyint DEFAULT 0,
			holepar15 tinyint DEFAULT 0,
			holepar16 tinyint DEFAULT 0,
			holepar17 tinyint DEFAULT 0,
			holepar18 tinyint DEFAULT 0,
			handicaplabel text NOT NULL,
			holehandicap1 smallint DEFAULT 0,
			holehandicap2 smallint DEFAULT 0,
			holehandicap3 smallint DEFAULT 0,
			holehandicap4 smallint DEFAULT 0,
			holehandicap5 smallint DEFAULT 0,
			holehandicap6 smallint DEFAULT 0,
			holehandicap7 smallint DEFAULT 0,
			holehandicap8 smallint DEFAULT 0,
			holehandicap9 smallint DEFAULT 0,
			holehandicap10 smallint DEFAULT 0,
			holehandicap11 smallint DEFAULT 0,
			holehandicap12 smallint DEFAULT 0,
			holehandicap13 smallint DEFAULT 0,
			holehandicap14 smallint DEFAULT 0,
			holehandicap15 smallint DEFAULT 0,
			holehandicap16 smallint DEFAULT 0,
			holehandicap17 smallint DEFAULT 0,
			holehandicap18 smallint DEFAULT 0,
			UNIQUE KEY course_id (course_id)
		) $charset_collate;";

		$sql14 = "CREATE TABLE {$wpdb->golf_deputy_golfers} (
			golfer_id mediumint(9) NOT NULL AUTO_INCREMENT,
			golfername text NOT NULL,
			handicap smallint(2),
			bio mediumtext NOT NULL,
			UNIQUE KEY golfer_id (golfer_id)
		) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql11 );
		dbDelta( $sql12 );
		dbDelta( $sql13 );
		dbDelta( $sql14 );
 
	    //Database is now up to date: update installed version to latest version
	    update_option( 'golf_deputy_version', $golf_deputy_version );
   }
}
add_action('admin_init', 'golf_deputy_upgradecheck');


//*** Sanitize data, create API ***//
function get_tournament_table_columns(){
    return array(
        'tournament_id' => '%d',
		'name' => '%s',
		'course' => '%s',
		'team1name' => '%s',
		'team2name' => '%s',
		'pin' => '%s',
		'scoringsystem' => '%d',
		'scoringtype' => '%d',
		'numberofteams' => '%d',
		'rounds' => '%d',
		'roundnames' => '%s',
		'team1score' => '%f',
		'team2score' => '%f',
    );
}

function get_matchup_table_columns(){
    return array(
        'matchup_id' => '%d',
		'tournament_id' => '%d',
		'round_id' => '%d',
		'win_points' => '%d',
		'player1' => '%s',
		'player2' => '%s',
		'playerpin' => '%s',
		'handicap' => '%d',
		'hole1' => '%d',
		'hole2' => '%d',
		'hole3' => '%d',
		'hole4' => '%d',
		'hole5' => '%d',
		'hole6' => '%d',
		'hole7' => '%d',
		'hole8' => '%d',
		'hole9' => '%d',
		'hole10'  => '%d',
		'hole11'  => '%d',
		'hole12'  => '%d',
		'hole13'  => '%d',
		'hole14'  => '%d',
		'hole15'  => '%d',
		'hole16'  => '%d',
		'hole17'  => '%d',
		'hole18'  => '%d',
		'holegross1' => '%d',
		'holegross2' => '%d',
		'holegross3' => '%d',
		'holegross4' => '%d',
		'holegross5' => '%d',
		'holegross6' => '%d',
		'holegross7' => '%d',
		'holegross8' => '%d',
		'holegross9' => '%d',
		'holegross10'  => '%d',
		'holegross11'  => '%d',
		'holegross12'  => '%d',
		'holegross13'  => '%d',
		'holegross14'  => '%d',
		'holegross15'  => '%d',
		'holegross16'  => '%d',
		'holegross17'  => '%d',
		'holegross18'  => '%d',
		'stablefordpoints1' => '%d',
		'stablefordpoints2' => '%d',
		'stablefordpoints3' => '%d',
		'stablefordpoints4' => '%d',
		'stablefordpoints5' => '%d',
		'stablefordpoints6' => '%d',
		'stablefordpoints7' => '%d',
		'stablefordpoints8' => '%d',
		'stablefordpoints9' => '%d',
		'stablefordpoints10'  => '%d',
		'stablefordpoints11'  => '%d',
		'stablefordpoints12'  => '%d',
		'stablefordpoints13'  => '%d',
		'stablefordpoints14'  => '%d',
		'stablefordpoints15'  => '%d',
		'stablefordpoints16'  => '%d',
		'stablefordpoints17'  => '%d',
		'stablefordpoints18'  => '%d',
		'currentscore' => '%s',
		'overall' => '%d',
		'closed' => '%s',
		'teamwinner' => '%d',
		'thru' => '%s',
		'linkedto' => '%d',
    );
}

function get_course_table_columns(){
    return array(
        'course_id' => '%d',
		'coursename' => '%s',
		'courseaddress' => '%s',
		'coursecity' => '%s',
		'coursestate' => '%s',
		'coursephone' => '%s',
		'measurement' => '%s',
		'teename' => '%s',
		'holeyardage1' => '%d',
		'holeyardage2' => '%d',
		'holeyardage3' => '%d',
		'holeyardage4' => '%d',
		'holeyardage5' => '%d',
		'holeyardage6' => '%d',
		'holeyardage7' => '%d',
		'holeyardage8' => '%d',
		'holeyardage9' => '%d',
		'holeyardage10' => '%d',
		'holeyardage11' => '%d',
		'holeyardage12' => '%d',
		'holeyardage13' => '%d',
		'holeyardage14' => '%d',
		'holeyardage15' => '%d',
		'holeyardage16' => '%d',
		'holeyardage17' => '%d',
		'holeyardage18' => '%d',
		'holepar1' => '%d',
		'holepar2' => '%d',
		'holepar3' => '%d',
		'holepar4' => '%d',
		'holepar5' => '%d',
		'holepar6' => '%d',
		'holepar7' => '%d',
		'holepar8' => '%d',
		'holepar9' => '%d',
		'holepar10' => '%d',
		'holepar11' => '%d',
		'holepar12' => '%d',
		'holepar13' => '%d',
		'holepar14' => '%d',
		'holepar15' => '%d',
		'holepar16' => '%d',
		'holepar17' => '%d',
		'holepar18' => '%d',
		'handicaplabel' => '%s',
		'holehandicap1' => '%d',
		'holehandicap2' => '%d',
		'holehandicap3' => '%d',
		'holehandicap4' => '%d',
		'holehandicap5' => '%d',
		'holehandicap6' => '%d',
		'holehandicap7' => '%d',
		'holehandicap8' => '%d',
		'holehandicap9' => '%d',
		'holehandicap10' => '%d',
		'holehandicap11' => '%d',
		'holehandicap12' => '%d',
		'holehandicap13' => '%d',
		'holehandicap14' => '%d',
		'holehandicap15' => '%d',
		'holehandicap16' => '%d',
		'holehandicap17' => '%d',
		'holehandicap18' => '%d',
    );
}

function get_golfer_table_columns(){
    return array(
        'golfer_id' => '%d',
		'golfername' => '%s',
		'handicap' => '%d',
		'bio' => '%s',
    );
}

function column_format_array($formats) {
	//Initialise column format array
    if ($formats == 'tournament') {
		$column_formats = get_tournament_table_columns();
	} elseif ($formats == 'matchup') {
		$column_formats = get_matchup_table_columns();
	} elseif ($formats == 'course') {
		$column_formats = get_course_table_columns();
	} elseif ($formats == 'golfer') {
		$column_formats = get_golfer_table_columns();
	}
	return $column_formats;
}

/**
 * Inserts tournament information into the database
 *
 *@param $table string The table to be inserted into
 *@param $formats string A string to define which array of whitelist columns to use: tournament, matchup or course; all others fail
 *@param $data array An array of key => value pairs to be inserted
 *@return int The log ID of the created activity log. Or WP_Error or false on failure.
*/
function gd_insert_tournament_info( $table, $formats, $data=array() ){
    global $wpdb;
	
    $column_formats = column_format_array($formats);
 
    //Force fields to lower case
    $data = array_change_key_case ( $data );
 
    //White list columns
    $data = array_intersect_key($data, $column_formats);
 
    //Reorder $column_formats to match the order of columns given in $data
    $data_keys = array_keys($data);
    $column_formats = array_merge(array_flip($data_keys), $column_formats);

    $wpdb->insert($table, $data, $column_formats);
    
    return $wpdb->insert_id;
}

/**
 * Updates an exiting tournament with supplied data
 *
 *@param $table string The table to be updated
 *@param $formats string A string to define wihich array list to use: tournament or matchup
 *@param $id int ID of the column to be updated
 *@param $column string Table column to be updated
 *@param $data array An array of column=>value pairs to be updated
 *@return bool Whether the log was successfully updated.
*/
function gd_update_tournament_info( $table, $formats, $id, $column, $data=array() ){
    global $wpdb;
	
	//Log ID must be positive integer
    $id = absint($id);     
    if( empty($id) )
         return false;

   $column_formats = column_format_array($formats);
	 
    //Force fields to lower case
    $data = array_change_key_case ( $data );
 
    //White list columns
    $data = array_intersect_key($data, $column_formats);
 
    //Reorder $column_formats to match the order of columns given in $data
    $data_keys = array_keys($data);
    $column_formats = array_merge(array_flip($data_keys), $column_formats);
 
    if ( false === $wpdb->update($table, $data, array($column=>$id), $column_formats) ) {
         return false;
    }
 
    return true;
}

/**
 * Retrieves tournament information from the database matching $query.
 * $query is an array which can contain the following keys:
 *
 * 'table' - the table name to query. Default: $wpdb->golf_deputy_tournaments, the tournament information table
 * 'allowedfields' - an array of table column formats. Default: get_tournament_table_columns()
 * 'fields' - an array of columns to include in returned roles. Or 'count' to count rows. Default: empty (all fields).
 * 'orderby' - any acceptable table field. Default: id, the primary key in $wpdb->golf_deputy_tournaments.
 * 'order' - asc or desc
 * 'tournament_id' - integer to match in idtomatch field
 * 'idtomatch' - string of table field name to match. Default: 'id'.
 * 'number' - number of results to return. Default: 1. -1 returns all.
 *
 *@param $query Query array
 *@return array Array of matching logs. False on error.
*/
function gd_get_tournament_info( $query=array() ){
 
     global $wpdb;

     $defaults = array(
       'table'=>$wpdb->golf_deputy_tournaments,'allowedfields'=>get_tournament_table_columns(),'fields'=>array(),'orderby'=>'tournament_id','order'=>'desc', 'tournament_id'=>false,'idtomatch'=>'tournament_id','number'=>1,'offset'=>0
     );
 
    $query = wp_parse_args($query, $defaults);
 
    extract($query);
 
    if( is_array($fields) ){
        $fields = array_map('strtolower',$fields);
		// Sanitize by white listing
		$fields = array_intersect($fields, $allowedfields);
    } else {
        $fields = strtolower($fields);
    }
 
    // Return only selected fields. Empty is interpreted as all
    if( empty($fields) ){
        $select_sql = "SELECT * FROM {$table}";
    }elseif( 'count' == $fields ) {
        $select_sql = "SELECT COUNT(*) FROM {$table}";
    }else{
        $select_sql = "SELECT ".implode(',',$fields)." FROM {$table}";
    }
 
     // Done for the purposes of 'gd_tournament_clauses'
     $join_sql='';
	
	if ($idtomatch != "all") {
    	$where_sql = "WHERE $idtomatch = $tournament_id";
	} else {
		$where_sql = "";
	}
 
    if( !empty($id) )
       $where_sql .=  $wpdb->prepare(' AND ' . $idtomatch .'=%d', $id);
 
    // Whitelist order
    $order = strtoupper($order);
    $order = ( 'ASC' == $order ? 'ASC' : 'DESC' );
 
    $order_sql = "ORDER BY $orderby $order";
 
    // SQL limit
    $offset = absint($offset);
    if( $number == -1 ){
         $limit_sql = "";
    }else{
         $number = absint($number);
         $limit_sql = "LIMIT $offset, $number";
    }
 
    // filter and join
    $pieces = array( 'select_sql', 'join_sql', 'where_sql', 'order_sql', 'limit_sql' );
    $clauses = apply_filters( 'gd_tournament_clauses', compact( $pieces ), $query );
    foreach ( $pieces as $piece )
          $newpiece = isset( $clauses[ $piece ] ) ? $clauses[ $piece ] : '';
 	
	
    $sql = "$select_sql $where_sql $order_sql $limit_sql";
 
    if( 'count' == $fields ){
        return $wpdb->get_var($sql);
    }

    $logs = $wpdb->get_results($sql);
 
    $logs = apply_filters('gd_get_tournament_info', $logs, $query);
    return $logs;
 }
 
/**
 * Deletes a matchup from the database
 *
 *@param $id int ID of the tournament/matchup to be deleted
 *@return bool Whether the log was successfully deleted.
*/
function gd_delete_tournament_info( $id ){
    golf_deputy_register_tables();
	global $wpdb;    	
 
    // $id must be positive integer
    $id = absint($id); 
 
    if( empty($id) )
         return false;
 
    do_action('gd_delete_tournament_info',$id);
    $sql = $wpdb->prepare("DELETE from {$wpdb->golf_deputy_matchups} WHERE matchup_id = %d", $id);

    if( !$wpdb->query( $sql ) )
         return false;
 
    do_action('gd_delete_tournament_info_log',$id);
 
    return true;
}

// allows AJAX call to PHP function for delete matchup
if (isset($_POST['callFunc'])) {
	echo gd_delete_tournament_info($_POST['callFunc']);
}

?>
