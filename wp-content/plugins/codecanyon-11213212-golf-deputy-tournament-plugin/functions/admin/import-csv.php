<?php  
golf_deputy_register_tables();
global $wpdb;

if ($_FILES[csv][size] > 0) { 


    //get the csv file 
    $file = $_FILES[csv][tmp_name]; 
	$tournamentid = $_POST['tournamentid'];
    $handle = fopen($file,"r");
	
	$events_meta['rounds'] = 0;
     
    //loop through the csv file and insert into database 
    while ($data = fgetcsv($handle,1000,",","'")) {
		if ($data[0] != 'round_id') { 
			$matchup_data['round_id'] = (int)$data[0];
			
			// check if this round is higher than last row's import (if it is, update tournament information)
			$newround = (int)$data[0];
			
			if ($newround > $events_meta['rounds']) {
				$events_meta['rounds'] = $newround;
				echo $events_meta['rounds'];
				gd_update_tournament_info($wpdb->golf_deputy_tournaments, 'tournament', $tournamentid, 'tournament_id', $events_meta);
			}
			
			$matchup_data['win_points'] = (int)$data[1];
			$matchup_data['player1'] = $data[2];
			$matchup_data['player2'] = $data[3];
			$matchup_data['handicap'] = $data[4];
			$matchup_data['tournament_id'] = (int)$tournamentid;
			
			$result = gd_insert_tournament_info($wpdb->golf_deputy_matchups, 'matchup', $matchup_data);
        }
	}; 
	
	
    // 
	
	if (!$result) {
		function golf_match_error_notice() {
			$class = "error";
			$message = _e( 'Error: ', 'golfdeputy' ) . mysql_error();
				echo"<div class=\"$class\"> <p>$message</p></div>"; 
		}
		add_action( 'admin_notices', 'golf_match_error_notice' ); 
	} else {
		function golf_match_admin_notice() {
			?>
			<div class="updated">
				<p><?php _e( 'CSV imported.', 'golfdeputy' ); ?></p>
			</div>
			<?php
		}
		add_action( 'admin_notices', 'golf_match_admin_notice' );
	}

	

}
?>