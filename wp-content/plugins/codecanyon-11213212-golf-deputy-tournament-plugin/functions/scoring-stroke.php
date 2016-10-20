<?php
//saves hole result to matchup table or reopens the matchup by clearing the last hole
foreach ($save_hole as $key2 => $data) {
	
	if ($data != "Reopen matchup") {

		if (count($data) > 1) { // if the course uses a handicap system, it will post as an array
			$holemeta['hole'.$_SESSION['currenthole']] = $data[1]; // scores NET as actual score
			$holemeta['holegross'.$_SESSION['currenthole']] = $data[0]; // scores GROSS in gross score
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

//strips erroneous values from query, uses only hole<x> columns to calculate scores; second if statement removed holegross<x> from equation
foreach ( $roundimport[0] as $hole => $score) {
	if("hole" == substr($hole,0,4)){
		if("holeg" != substr($hole,0,5)){
			if (!empty($score)) {
				$holesplayed++;
				$totalscore = $score + $totalscore;
				
				$par = "holepar" . substr($hole,4,5);
				$totalpar = $course[0]->$par + $totalpar;
			}
		}
	}
}
$currentscore = $totalscore - $totalpar;

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