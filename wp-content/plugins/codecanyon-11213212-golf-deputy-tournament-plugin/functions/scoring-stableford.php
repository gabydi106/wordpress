<?php
//saves hole result to matchup table or reopens the matchup by clearing the last hole
foreach ($save_hole as $key2 => $data) {
	if ($data != "Reopen matchup") {

		if (count($data) > 1) { // if the course uses a handicap system, it will post as an array
			$holemeta['holegross'.$_SESSION['currenthole']] = $data[0]; // scores GROSS in gross score
			$holemeta['hole'.$_SESSION['currenthole']] = $data[1]; // scores NET as actual score
			$holemeta['stablefordpoints'.$_SESSION['currenthole']] = $data[2]; // scores POINTS in points table column
		} else {
			$holemeta['hole'.$_SESSION['currenthole']] = $data[0];
		}

		gd_update_tournament_info($wpdb->golf_deputy_matchups, 'matchup', $key2, 'matchup_id', $holemeta);
	} else {
		$holemeta['hole'.$_SESSION['currenthole']] = 0;
		$holemeta['closed'] = 0;
		gd_update_tournament_info($wpdb->golf_deputy_matchups, 'matchup', $key2, 'matchup_id', $holemeta);
		if ($removed <= 0) {
			$_SESSION['currenthole'] = $_SESSION['currenthole'] - 1;
		}
		$removed++; // makes sure the 'currenthole' is only removed once
	}
}
//die();
// read data again after hole meta saved
$roundimport = gd_get_tournament_info( $query=array('tournament_id'=>$matchupid, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'matchup_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );
$currentround = $roundimport[0]->round_id;
$courseid = $result[0]->course;
$courseid = unserialize($courseid);
$courseid = $courseid[$currentround-1]; // serialized array is a zero array, hence the -1
$course = gd_get_tournament_info( $query=array('tournament_id'=>$courseid, 'table'=>$wpdb->golf_deputy_courses, 'idtomatch'=>'course_id', 'allowedfields'=>get_course_table_columns(), 'orderby'=>'course_id', 'number'=>-1, 'order'=>'ASC') );

//resets the vars for each loop
$holesplayed = 0;
$closed = 0;
$totalscore = 0;
$totalpar = 0;
$previousscore = 0;
$overallscore = 0;
$currentscore = 0;

for ($x = 1; $x <= 18; $x++) { // loops through all 18 holes, adds up stablefordpoints fields for a total
	$stableford = "stablefordpoints" . $x;
    $currentscore = $currentscore + $roundimport[0]->$stableford;
}

// because we cannot determine number of holes played by scores entered (Stableford allows for "No Score"), we have to use the current session hole #
$holesplayed = $_SESSION['currenthole'];

// check if this matchup is > Round 1; if it is, add previous rounds to current score
$linkedto = $roundimport[0]->linkedto;
if (!empty($linkedto)) {
	//gets first round; the "linkedto"
	$previousround1 = gd_get_tournament_info( $query=array('tournament_id'=>$linkedto, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'matchup_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );
	$previousscore = $previousround1[0]->currentscore;
		
	//gets all others rounds linked to it
	$previousround2 = gd_get_tournament_info( $query=array('tournament_id'=>$linkedto, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'linkedto', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );
	
	foreach ($previousround2 as $key => $scores) {
		if (!empty($previousround2[$key]->currentscore)) {
			if ($previousround2[$key]->matchup_id != $roundimport[0]->matchup_id) { // if this is not the current round
				$previousscore = $previousscore + $previousround2[$key]->currentscore;
			}
		}
	}
	
	$overallscore = intval($previousscore) + intval($currentscore);
	
} else {
	$overallscore = intval($currentscore);
}

// close matchup, submit final score, add points to tournament_meta_score
if ($holesplayed == 18) {
		$teamwinner = 0;
		$closed = 1;
}
?>