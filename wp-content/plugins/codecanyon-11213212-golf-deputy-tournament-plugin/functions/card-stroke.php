<?php
global $course;
$h = 1;
$nineHoles = 0;
$fullGameScore = 0;

// retrieves and displays score for each hole
foreach ( $matchup as $hole => $score) {
	if("hole" == substr($hole,0,4)){ //strips erroneous values from query, uses only hole<x> columns to calculate scores; second if statement removed holegross<x> from equation
		if("holeg" != substr($hole,0,5)){
			 ?>
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
						
						<?php 
						$nineHoles += $score;
						$fullGameScore += $score;
						if($h==9) {  //front-9 ?>
							<div class="holescore"> 
								<strong><?php echo $nineHoles; //print front-9 ?> </strong>
							</div>
						<?php $nineHoles=0; //becomes back-9
						}
						if($h==18) {  ?>
							<div class="holescore"> 
								<strong><?php echo $nineHoles; //print back-9 ?> </strong> 
							</div>
							<div class="holescore"> 
								<strong><?php echo $fullGameScore; //print 18-holes ?> </strong>
							</div>
						<?php }?> 
						
					</div>
				<?php } else { //course does not use handicap, only display net score ?>
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