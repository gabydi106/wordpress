<?php
// Save the Matchup Inputs
function gd_save_matchup_meta() {
	global $wpdb;

	// if matchup not closed, save submitted Hole Score data, then calculate score, then write to DB
	foreach ($_SESSION['matchups'] as $key => $matchupid) {
		//reset arrays for each matchup
		$save_hole="";
		$matches="";
		
		$roundimport = gd_get_tournament_info( $query=array('tournament_id'=>$matchupid, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'matchup_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );	
						
		//sanitizes data by comparing the input from $_POST to selected matchupids from $_SESSION
		$matches[$matchupid] = "";
		$save_hole = array_intersect_key($_POST, $matches);
		
		//include the proper scoring system, based on the tournament settings
		$tournamentid = $roundimport[0]->tournament_id;
		$result = gd_get_tournament_info( $query=array('tournament_id'=>$tournamentid) );
		if ($result[0]->scoringsystem == 0) {
			include(dirname( __FILE__ ) . '/scoring-stroke.php');
		} else if ($result[0]->scoringsystem == 2) {
			include(dirname( __FILE__ ) . '/scoring-stableford.php');
		} else {
			include(dirname( __FILE__ ) . '/scoring-match.php');
		}
		
		//Updates current matchupid with currentscore
		$current_score['currentscore'] = $currentscore;
		$current_score['closed'] = $closed;
		if (!empty($teamwinner)) {
			$current_score['teamwinner'] = $teamwinner;
		}
		$current_score['thru'] = $holesplayed;
		if (!is_null($overallscore)) {
			$current_score['overall'] = $overallscore;
		}
		gd_update_tournament_info($wpdb->golf_deputy_matchups, 'matchup', $matchupid, 'matchup_id', $current_score);
		
		$roundimport = gd_get_tournament_info( $query=array('tournament_id'=>$matchupid, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'matchup_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );
		
	}

	if ($reopen > 0) { // checks "reopen" flag so we can clear $_POST, so that we don't duplicate the removal of points on page reload
		$actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("Location: $actual_link");
	}
}

// add points to tournament_meta_score
function gd_save_teamscore ($teamwinner, $winpoints, $closed, $tournamentid, $team1currentscore, $team2currentscore) {
	global $wpdb;
	
	
	$result = gd_get_tournament_info( $query=array('tournament_id'=>$tournamentid) );
	
	$team1currentscore = $result[0]->team1score;
	$team2currentscore = $result[0]->team2score;
	
	if ($closed == 0) {
		if ($teamwinner == 1) {
			$scorefor = 'team' . $teamwinner . 'score';
			$totalscore = $team1currentscore + $winpoints;
			$events_meta[$scorefor] = $totalscore;
		} elseif ($teamwinner == 2) {
			$scorefor = 'team' . $teamwinner . 'score';
			$totalscore = $team2currentscore + $winpoints;
			$events_meta[$scorefor] = $totalscore;
		} elseif ($teamwinner == -1) { // match was halved
			$halfscore1 = $team1currentscore + ($winpoints / 2);
			$halfscore2 = $team2currentscore  + ($winpoints / 2);
			$events_meta['team1score'] = $halfscore1;
			$events_meta['team2score'] = $halfscore2;
		}
				
		gd_update_tournament_info($wpdb->golf_deputy_tournaments, 'tournament', $tournamentid, 'tournament_id', $events_meta);
	} else {
		// do nothing, this matchup is closed
	}
}


?>