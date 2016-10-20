<?php
	global $course;
	$team1score = 0;
	$team2score = 0;
	$h = 1;
	// calculates who won for every hole, spits out each team total
	foreach ( $matchup as $hole => $score) {
		if("hole" == substr($hole,0,4)){
			if("holeg" != substr($hole,0,5)){
				if (($h == 1) || ($h == 10)) { ?>
					<div class="holecard header">
						<div class="holenumber header"><strong><?php _e( 'Hole', 'golfdeputy' ); ?></strong></div>
						<div class="holepar header"><strong><?php _e( 'Par', 'golfdeputy' ); ?></strong></div>
						<div class="holescore header"><strong>&nbsp;</strong></div>
					</div>
				<?php } ?>
				<div class="holecard">
					<div class="holenumber"><?php echo $h; ?></div>
					<?php $par = 'holepar' . $h; ?>
					<div class="holepar"><?php echo $course[0]->$par; ?></div>
					<div class="holescore">
						<?php if ($score == -1) { // push
							echo _e( 'Halved', 'golfdeputy' );
						} elseif ($score == 1) { //team1
							if (!empty($team1name)) {
								echo $team1name;
							} else {
								echo $matchup->player1;
							}
						} elseif ($score == 2) { //team2
						   	if (!empty($team2name)) {
								echo $team2name;
							} else {
								echo $matchup->player2;
							}
						} else {
							echo "-";
						}?>
					</div>
				</div>
				<?php $h++;
			}
		}
	}
 ?>