<?php
//saves hole result to matchup table or reopens the matchup by clearing the last hole
foreach ($save_hole as $key2 => $data) {
	if ($data != "Reopen matchup") {
		$holemeta['hole'.$_SESSION['currenthole']] = $data;
		gd_update_tournament_info($wpdb->golf_deputy_matchups, 'matchup', $key2, 'matchup_id', $holemeta);
				
	} else {
		$holemeta['hole'.$_SESSION['currenthole']] = 0;
		$holemeta['closed'] = 0;
		$holemeta['teamwinner'] = 0;
		gd_update_tournament_info($wpdb->golf_deputy_matchups, 'matchup', $key2, 'matchup_id', $holemeta);
		$_SESSION['currenthole'] = $_SESSION['currenthole'] - 1;
		
		$result = gd_get_tournament_info( $query=array('tournament_id'=>$roundimport[0]->tournament_id) );
		
		$team1currentscore = $result[0]->team1score;
		$team2currentscore = $result[0]->team2score;
		
		//remove points from reopened match
		if ($roundimport[0]->teamwinner == 1) { // was Team1 that won
			$newscore['team1score'] =  $team1currentscore - $roundimport[0]->win_points;
			gd_update_tournament_info($wpdb->golf_deputy_tournaments, 'tournament', $roundimport[0]->tournament_id, 'tournament_id', $newscore);
			
		} elseif ($roundimport[0]->teamwinner == 2) { // was Team2 that won
			$newscore['team2score'] = $team2currentscore - $roundimport[0]->win_points;
			gd_update_tournament_info($wpdb->golf_deputy_tournaments, 'tournament', $roundimport[0]->tournament_id, 'tournament_id', $newscore);
			
		} elseif ($roundimport[0]->teamwinner == -1) { // was halved
			$halfpoints = $roundimport[0]->win_points / 2;
			$newscore['team1score'] = $team1currentscore - $halfpoints;
			$newscore['team2score'] = $team2currentscore - $halfpoints;
			gd_update_tournament_info($wpdb->golf_deputy_tournaments, 'tournament', $roundimport[0]->tournament_id, 'tournament_id', $newscore);
		}
		$reopen++; // adds "reopen" flag so we can clear $_POST, so that we don't duplicate the removal of points on page reload
		break;
		// ******* remove points from teamscore ********
	}
}

// read data again after hole meta saved
$roundimport = gd_get_tournament_info( $query=array('tournament_id'=>$matchupid, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'matchup_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );

//resets the vars for each loop
$holesplayed = 0;
$team1score = 0;
$team2score = 0;
$closed = 0;
$teamwinner = 0;
$player1 = $roundimport[0]->player1;
$player2 = $roundimport[0]->player2;

//strips erroneous values from query, uses only hole<x> columns to calculate scores
foreach ( $roundimport[0] as $hole => $score) {
	if("hole" == substr($hole,0,4)){
		
		if (!empty($score)) {
			$holesplayed++;
		}
		
		if ($score == -1) { // push
			// do nothing, it's a push
		} elseif ($score == 1) { //team1
			$team1score = $team1score + 1;
		} elseif ($score == 2) { //team2
			$team2score = $team2score + 1;
		} else { // cleared or zero
			//do nothing: hole scored doesn't exist or it was cleared
		}
	}
}

$result = gd_get_tournament_info( $query=array('tournament_id'=>$roundimport[0]->tournament_id) );

$team1currentscore = $result[0]->team1score;
$team2currentscore = $result[0]->team2score;

// cycles through possible score strings and closes match if necessary
if ($team1score > $team2score) {
	$lead = $team1score - $team2score;
	$currentscore = $player1 . __( ' up ', 'golfdeputy' ) . $lead . __( ' thru ', 'golfdeputy' ) . $holesplayed;
} elseif ($team2score > $team1score) {
	$lead = $team2score - $team1score;
	$currentscore = $player2 . __( ' up ', 'golfdeputy' ) . $lead . __( ' thru ', 'golfdeputy' ) . $holesplayed;
} else {
	$currentscore = __( 'All Square', 'golfdeputy' ) . __( ' thru ', 'golfdeputy' ) . $holesplayed;
}

if ($holesplayed == 18) {
	if ($team1score > $team2score) {
		$currentscore = $player1 . __( ' wins ', 'golfdeputy' ) . $lead . __( ' up ', 'golfdeputy' );
		$teamwinner = 1;
		gd_save_teamscore ($teamwinner, $roundimport[0]->win_points, $roundimport[0]->closed, $roundimport[0]->tournament_id, $team1currentscore, $team2currentscore);
		$closed = 1;
	} elseif ($team2score > $team1score) {
		$currentscore = $player2 . __( ' wins ', 'golfdeputy' ) . $lead . __( ' up ', 'golfdeputy' );
		$teamwinner = 2;
		gd_save_teamscore ($teamwinner, $roundimport[0]->win_points, $roundimport[0]->closed, $roundimport[0]->tournament_id, $team1currentscore, $team2currentscore);
		$closed = 1;
	} else {
		$currentscore = __( 'Halved', 'golfdeputy' );
		$teamwinner = -1;
		gd_save_teamscore ($teamwinner, $roundimport[0]->win_points, $roundimport[0]->closed, $roundimport[0]->tournament_id, $team1currentscore, $team2currentscore);
		$closed = 1;
	}
	// close matchup, submit final score, add points to tournament_meta_score
} elseif ($lead > (18 - $holesplayed)) {
	if ($team1score > $team2score) {
		$currentscore = $player1 . __( ' wins ', 'golfdeputy' ) . $lead . __( ' and ', 'golfdeputy' ) . (18 - $holesplayed);
		$teamwinner = 1;
		gd_save_teamscore ($teamwinner, $roundimport[0]->win_points, $roundimport[0]->closed, $roundimport[0]->tournament_id, $team1currentscore, $team2currentscore);
		$closed = 1;
	} elseif ($team2score > $team1score) {
		$currentscore = $player2 . __( ' wins ', 'golfdeputy' ) . $lead . __( ' and ', 'golfdeputy' ) . (18 - $holesplayed);
		$teamwinner = 2;
		gd_save_teamscore ($teamwinner, $roundimport[0]->win_points, $roundimport[0]->closed, $roundimport[0]->tournament_id, $team1currentscore, $team2currentscore);
		$closed = 1;
	}
}
?>