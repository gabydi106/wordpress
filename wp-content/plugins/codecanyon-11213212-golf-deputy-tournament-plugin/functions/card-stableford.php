<?php
global $course;
$h = 1;
// retrieves and displays score for each hole
foreach ( $matchup as $hole => $score) {	
	if("hole" == substr($hole,0,4)){ //strips erroneous values from query, uses only hole<x> columns to calculate scores; second if statement removed holegross<x> from equation
		if("holeg" != substr($hole,0,5)){
			if (($h == 1) || ($h == 10)) { ?>
				<div class="holecard">
					<div class="holenumber header"><strong><?php _e( 'Hole', 'golfdeputy' ); ?></strong></div>
					<div class="holepar header"><strong><?php _e( 'Par', 'golfdeputy' ); ?></strong></div>
					<?php if ($course[0]->handicaplabel != "None") { ?>
						<div class="holescore header"><strong><?php _e( 'Gross', 'golfdeputy' ); ?></strong></div>
						<div class="holescore header"><strong><?php _e( 'Net', 'golfdeputy' ); ?></strong></div>
						<div class="holescore header"><strong><?php _e( 'Points', 'golfdeputy' ); ?></strong></div>
					<?php } else { ?>
						<div class="holescore header"><strong><?php _e( 'Score', 'golfdeputy' ); ?></strong></div>
					<?php } ?>
				</div>
			<?php } ?>
			<div class="holecard">
				<div class="holenumber"><strong><?php echo $h; ?></strong></div>
				<?php
				$par = 'holepar' . $h;
				$gross = 'holegross' . $h;
				?>
				<div class="holepar"><?php echo $course[0]->$par; ?></div>
				<?php if ($course[0]->handicaplabel != "None") { // if course uses a handicap, display gross and net; else, just display net ?>
					<div class="holescore">
						<?php // check if eagle, birdie, par, bogey, double bogey, add class
						if (!empty($score)) {
							echo $matchup->$gross;
						} else {
							echo "-";
						}?>
					</div>
					<div class="holescore">
						<?php // check if eagle, birdie, par, bogey, double bogey, add class
						if (!empty($score)) {
							if ($course[0]->$par-2 == $score) {
								echo "<span class='eagle'>" . $score . "</span>";
							}
							elseif ($course[0]->$par-1 == $score) {
								echo "<span class='birdie'>" . $score . "</span>";
							}
							elseif ($score == $course[0]->$par) {
								echo $score;
							}
							elseif ($course[0]->$par+1 == $score) {
								echo "<span class='bogey'>" . $score . "</span>";
							}
							else {
								echo "<span class='doublebogey'>" . $score . "</span>";
							}
						} else {
							echo "-";
						}?>
					</div>
					<div class="holescore">
						<?php // check if eagle, birdie, par, bogey, double bogey, add class
							$points = "stablefordpoints" . $h;
							echo $matchup -> $points;
						?>
					</div>
				<?php } else { ?>
				<div class="holescore">
					<?php // check if eagle, birdie, par, bogey, double bogey, add class
					if (!empty($score)) {
						if ($course[0]->$par-2 == $score) {
							echo "<span class='eagle'>" . $score . "</span>";
						}
						elseif ($course[0]->$par-1 == $score) {
							echo "<span class='birdie'>" . $score . "</span>";
						}
						elseif ($score == $course[0]->$par) {
							echo $score;
						}
						elseif ($course[0]->$par+1 == $score) {
							echo "<span class='bogey'>" . $score . "</span>";
						}
						else {
							echo "<span class='doublebogey'>" . $score . "</span>";
						}
					} else {
						echo "-";
					}?>
				</div>
				<?php } ?>
			</div>
			<?php
			$h++;
		}
	}
}
?>