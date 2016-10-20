<?php

/*** Save the metabox data from the admin page ***/
function gd_save_tournament_meta($post_id, $post) {
	global $wpdb;

	
	if ( ( !isset( $_POST['eventmeta_noncename']) ) || (!wp_verify_nonce( $_POST['eventmeta_noncename'], 'golf-deputy' ))) {
		return $post->ID;
	}

	if ( !current_user_can( 'edit_post', $post->ID )) {
		return $post->ID;
	}

	// avoid diplicate call for save_post
	if( ! ( wp_is_post_revision( $post_id) || wp_is_post_autosave( $post_id ) ) ) {
	
		// Checks if it's a tournament, golfcourse or golfer that we're trying to save
		if ( 'tournament' == get_post_type() ) {
			
			$fullround = $_POST['_round'];
			
			$events_meta['pin'] = $_POST['_pin'];
			$events_meta['course'] = serialize($_POST['_coursename']);
			$events_meta['name'] = $_POST['post_title'];
			
			$events_meta['scoringsystem'] = $_POST['_scoringsystem'];
			$events_meta['scoringtype'] = $_POST['_scoringtype'];
			$events_meta['numberofteams'] = $_POST['_numberofteams'];
			
			$events_meta['team1name'] = $_POST['_team1name'];
			$events_meta['team2name'] = $_POST['_team2name'];
			$events_meta['roundnames'] = serialize($_POST['_roundnames']);
			$events_meta['rounds'] = count($fullround);


			// saves all of the info to our custom tables via the API
			if(get_post_meta($post->ID, 'tournament_id', TRUE)) { // If the tournament already exists/we're updating it
				gd_update_tournament_info($wpdb->golf_deputy_tournaments, 'tournament', get_post_meta($post->ID, 'tournament_id', TRUE), 'tournament_id', $events_meta);
				$tournamentid = get_post_meta($post->ID, 'tournament_id', TRUE);
				if(!empty($fullround)) {
					$i = 1;
					$j = 0;
					foreach($fullround as $key => $round) {
							if ($round['_winpoints'] != null) {
								$matchup_data['win_points'] = $round['_winpoints'];
							} else {
								$matchup_data['win_points'] = 0;
							}
							$matchup_data['round_id'] = $key;
							$length = max(count($round['_player1']), count($round['_player2']));
								for ($i = 0; $i < $length; $i++) {
									$matchup_data['player1'] = $round['_player1'][$i];
									$matchup_data['player2'] = $round['_player2'][$i];
									$matchup_data['handicap'] = $round['_handicap'][$i];
									$matchup_data['playerpin'] = $round['_playerpin'][$i];

									// null checks and fixes
									if ($matchup_data['player1'] == null) {
										$matchup_data['player1'] = '';
									}
									if ($matchup_data['player2'] == null) {
										$matchup_data['player2'] = '';
									}

									$matchup_data['linkedto'] = $round['_linkedto'][$i];
									$matchup_data['tournament_id'] = $tournamentid;
									$matchupid = $round['_matchup_id'][$i];
									$result = gd_update_tournament_info($wpdb->golf_deputy_matchups, 'matchup', $matchupid, 'matchup_id', $matchup_data);
									if (empty($result)) {
										gd_insert_tournament_info($wpdb->golf_deputy_matchups, 'matchup', $matchup_data);
									}
								}
						$i++;
					}
				}	
		
			} else { // If the tournament field doesn't have a value / if this is a new tournament
				$tournamentid = gd_insert_tournament_info($wpdb->golf_deputy_tournaments, 'tournament', $events_meta);
				add_post_meta($post->ID, 'tournament_id', $tournamentid);
				
				if(!empty($fullround)) {
					$i = 1;
					$j = 0;
					foreach($fullround as $key => $round) {
							if ($round['_winpoints'] != null) {
								$matchup_data['win_points'] = $round['_winpoints'];
							} else {
								$matchup_data['win_points'] = 0;
							}
							$matchup_data['round_id'] = $key;
							$length = max(count($round['_player1']), count($round['_player2']));
							for ($i = 0; $i < $length; $i++) {
								$matchup_data['player1'] = $round['_player1'][$i];
								$matchup_data['player2'] = $round['_player2'][$i];
								$matchup_data['handicap'] = $round['_handicap'][$i];

								// null checks and fixes
								if ($matchup_data['player1'] == null) {
									$matchup_data['player1'] = '';
								}
								if ($matchup_data['player2'] == null) {
									$matchup_data['player2'] = '';
								}

								$matchup_data['linkedto'] = $round['_linkedto'][$i];
								$matchup_data['tournament_id'] = $tournamentid;
								gd_insert_tournament_info($wpdb->golf_deputy_matchups, 'matchup', $matchup_data);
							}
					}
				}
			}
			
			// Saves Sponsor images as Post Meta attached to post
			if( isset( $_POST[ 'sponsor-image' ] ) ) {
				update_post_meta( $post_id, 'sponsor-image', $_POST[ 'sponsor-image' ] );
			}
		} elseif ( 'golfcourse' == get_post_type() ) {
			
			$yardage = $_POST['yardage'];
			$par = $_POST['par'];
			$handicap = $_POST['handicap'];
			
			$events_meta['coursename'] = $_POST['post_title'];
			$events_meta['courseaddress'] = $_POST['_courseaddress'];
			$events_meta['coursecity'] = $_POST['_coursecity'];
			$events_meta['coursestate'] = $_POST['_coursestate'];
			$events_meta['coursephone'] = $_POST['_coursephone'];
			$events_meta['measurement'] = $_POST['_measurement'];
			$events_meta['handicaplabel'] = $_POST['_handicaplabel'];
			$events_meta['teename'] = $_POST['_teename'];
			
			foreach($yardage as $key => $yards) {
				$events_meta[$key] = $yards;
			}
			
			foreach($par as $key => $holepar) {
				$events_meta[$key] = $holepar;
			}

			foreach($handicap as $key => $holehandicap) {
				$events_meta[$key] = $holehandicap;
			}
			
			if(get_post_meta($post->ID, 'course_id', TRUE)) { // If the course already exists/we're updating it
				gd_update_tournament_info($wpdb->golf_deputy_courses, 'course', get_post_meta($post->ID, 'course_id', TRUE), 'course_id', $events_meta);
				update_post_meta($post->ID, 'teename', $events_meta['teename']);
			} else { // If the course_id field doesn't have a value / if this is a new course
				$tournamentid = gd_insert_tournament_info($wpdb->golf_deputy_courses, 'course', $events_meta);
				add_post_meta($post->ID, 'course_id', $tournamentid);
				add_post_meta($post->ID, 'teename', $events_meta['teename']);
			}

		} elseif ( 'golfer' == get_post_type() ) {
			
			$events_meta['golfername'] = $_POST['post_title'];
			$events_meta['handicap'] = $_POST['_golferhandicap'];
			$events_meta['bio'] = htmlspecialchars($_POST['_golferbio']);
			
			if(get_post_meta($post->ID, 'golfer_id', TRUE)) { // If the golfer already exists/we're updating it
				gd_update_tournament_info($wpdb->golf_deputy_golfers, 'golfer', get_post_meta($post->ID, 'golfer_id', TRUE), 'golfer_id', $events_meta);
			} else { // If the golfer_id field doesn't have a value / if this is a new golfer
				$tournamentid = gd_insert_tournament_info($wpdb->golf_deputy_golfers, 'golfer', $events_meta);
				add_post_meta($post->ID, 'golfer_id', $tournamentid);
			}

			// Saves golfer image as Post Meta attached to post
			if( isset( $_POST[ 'sponsor-image' ] ) ) {
				update_post_meta( $post_id, 'sponsor-image', $_POST[ 'sponsor-image' ] );
			}

		}
	}
}
add_action('save_post', 'gd_save_tournament_meta', 1, 2); // save the custom fields

?>