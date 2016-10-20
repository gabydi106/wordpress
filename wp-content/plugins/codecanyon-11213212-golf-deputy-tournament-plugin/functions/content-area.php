<?php 
include_once("scoring.php");

// match SESSION variables to matchup table; display matchups
if (isset($_POST['currenthole'])) {	
	$_SESSION['currenthole']= $_POST['currenthole'];
	gd_save_matchup_meta();
}

//Sponsorship ad, display if it exists
$sponsor_stored_meta = get_post_meta( $post->ID );
$sponsor_stored_meta = unserialize($sponsor_stored_meta['sponsor-image'][0]);

// Get course information for current round/hole
$matchupid = $_SESSION['matchups'][0]; // we only need the first matchup id to figure out what round it is
$roundimport = gd_get_tournament_info( $query=array('tournament_id'=>$matchupid, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'matchup_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );
$currentround = $roundimport[0]->round_id;
$courseid = $result[0]->course;
$courseid = unserialize($courseid);
$courseid = $courseid[$currentround-1]; // serialized array is a zero array, hence the -1
$course = gd_get_tournament_info( $query=array('tournament_id'=>$courseid, 'table'=>$wpdb->golf_deputy_courses, 'idtomatch'=>'course_id', 'allowedfields'=>get_course_table_columns(), 'orderby'=>'course_id', 'number'=>-1, 'order'=>'ASC') );
?>

<script> // flips the PHP array to JS, so that when someone changes holes, the hole info on screen updates; necessary for handicap calculations
    var course = new Array();
    course = <?php echo json_encode($course[0]); ?>;
    var sponsors = new Array();
    sponsors = <?php echo json_encode($sponsor_stored_meta); ?>;
    //console.log(sponsors);
</script>

<?php
if (empty($_SESSION['currenthole'])) {
	if (!empty($sponsor_stored_meta[0])) { ?>
    	<img src="<?php echo $sponsor_stored_meta[0]?>" alt="Hole #1 Sponsor" title="Hole #1 Sponsor" class="sponsorimage">
    <?php } ?>
<?php } else {
	if (!empty($sponsor_stored_meta[$_SESSION['currenthole']])) { ?>
		<img src="<?php echo $sponsor_stored_meta[$_SESSION['currenthole']]?>" alt="Hole #<?php echo $_SESSION['currenthole']+1; ?> Sponsor" title="Hole #<?php echo $_SESSION['currenthole']+1; ?> Sponsor" class="sponsorimage">
    <?php } ?>
<?php } ?>

<form method="POST" action="" id="holescore">
<label for="holenumber"><?php _e( 'Score for Hole #', 'golfdeputy' ); ?></label>
<select id="holenumber" name="currenthole">
    <?php for($j = 1; $j <= 18; $j++) { ?>
        <option value="<?php echo $j;?>"><?php echo $j;?></option> 
    <?php } ?>
</select><br>

<?php
if (!empty($_SESSION['currenthole'])){
	$thishole = $_SESSION['currenthole'] + 1;
} else {
	$thishole = 1;
}
if ($thishole == 19) { $thishole = 1;}
$measurement = $course[0]->measurement;
$thisholeyards = 'holeyardage' . $thishole;
$thisholepar = 'holepar' . $thishole;
$thisholehandicap = 'holehandicap' . $thishole;
$thisholestableford = 'stablefordpoints' . $thishole;
?>
<p id="holeinfo"><?php _e( 'Hole', 'golfdeputy' ); ?> <span id="holeinfo-number"><?php echo $thishole;?></span> | 
<span id="holeinfo-measurement"><?php echo $course[0]->$thisholeyards;?></span> <?php if (!empty($measurement)) { if ($measurement == "Metres") { _e( 'metres', 'golfdeputy' ); } else { _e( 'yards', 'golfdeputy' ); } } else { _e( 'yards', 'golfdeputy' );} ?> | 
Par <span id="holeinfo-par"><?php echo $course[0]->$thisholepar;?></span>
<?php if ($course[0]->handicaplabel != "None") {
	if ($course[0]->handicaplabel == "Handicap") {
		echo " | " . __( 'Handicap', 'golfdeputy' ) . " <span id='strokeindex'>" . $course[0]->$thisholehandicap . "</span>";
	} else {
		echo " | " . __( 'Stroke Index', 'golfdeputy' ) . " <span id='strokeindex'>" . $course[0]->$thisholehandicap . "</span>";
	}
};?>
</p>

<?php
// sets the "score for hole #" dropdown to currenthole + 1
if (!empty($_SESSION['currenthole'])) { ?>
	<script type="text/javascript">
		var currenthole = <?php echo $_SESSION['currenthole'] + 1; ?>;
		if (currenthole == 19) {
			currenthole = 1;
		} else {
			jQuery("#holenumber").val('<?php echo $_SESSION['currenthole'] + 1; ?>');
		}
	</script>
<?php } ?>

<div id="matchupstoscore">
	<?php
    if (!empty($_SESSION['matchups'])) {
		foreach ($_SESSION['matchups'] as $key => $matchupid) {
			$roundimport = gd_get_tournament_info( $query=array('tournament_id'=>$matchupid, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'matchup_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );
			if ($roundimport[0]->closed == 1) {
				if (($scoringsystem == 0) || ($scoringsystem == 2)) { // stroke or stableford scoring ?>
                    <div class="matchupsave">
                        <span class="players"><?php echo stripslashes($roundimport[0]->player1);?></span>
                                    
                        <p class="closedmatchup">
                            <?php _e( 'This player has completed this round:', 'golfdeputy' ); ?> <strong><?php echo $roundimport[0]->currentscore; ?></strong>.<br><br>
                            <input type="checkbox" name="<?php echo $roundimport[0]->matchup_id ?>" class="reopen" value="Reopen matchup" id="reopen<?php echo $roundimport[0]->matchup_id ?>"><span class="small"><label for="reopen<?php echo $roundimport[0]->matchup_id ?>"><?php _e( 'Reopen Round (selected hole will be cleared)', 'golfdeputy' ); ?></label></span>
                        </p>
                    </div>
            	<?php } else { ?>
                    <div class="matchupsave">
                        <span class="players"><?php echo stripslashes($roundimport[0]->player1);?> <span class="versus"><?php _e( 'vs', 'golfdeputy' ); ?></span> <?php echo stripslashes($roundimport[0]->player2); ?></span>
                                    
                        <p class="closedmatchup">
                            <?php _e( 'This matchup has completed:', 'golfdeputy' ); ?> <strong><?php echo $roundimport[0]->currentscore; ?></strong>.<br><br>
                            <input type="checkbox" name="<?php echo $roundimport[0]->matchup_id ?>" class="reopen" value="Reopen matchup" id="reopen<?php echo $roundimport[0]->matchup_id ?>"><span class="small"><label for="reopen<?php echo $roundimport[0]->matchup_id ?>"><?php _e( 'Reopen Matchup (selected hole will be cleared)', 'golfdeputy' ); ?></label></span>
                        </p>
                    </div>
            	<?php } ?>
		<?php } else {
        	if (($scoringsystem == 0) || ($scoringsystem == 2)) { // stroke or stableford scoring ?>
                <div class="matchupsave">
                    <span class="players"><?php echo stripslashes($roundimport[0]->player1);?> <?php if ($course[0]->handicaplabel != "None") { ?>| <em><?php _e( 'Handicap', 'golfdeputy' ); ?>: <span class="handicap-value"><?php echo $roundimport[0]->handicap;?></span></em><?php } ?></span>
                    
                    <div class="stroke-quantity">
                    	<?php if ($course[0]->handicaplabel != "None") {?> <p class="grossscore"><?php _e( 'Gross Score', 'golfdeputy' ); ?></p> <?php } ?>
                        <div class="stroke-minus"><a class="stroke-btn" data-multi="-1"></a></div>
                        <div class="stroke-input">
                            <input type="text" name="<?php echo $roundimport[0]->matchup_id ?>[]" class="quantity-input" value="<?php echo $course[0]->$thisholepar;?>" />
                        </div>
                        <div class="stroke-plus"><a class="stroke-btn" data-multi="1"></a></div>
                        <?php if ($course[0]->handicaplabel != "None") {?>
                        	<p class="netscore"><?php _e( 'Net Score', 'golfdeputy' ); ?>: <input readonly type="text" name="<?php echo $roundimport[0]->matchup_id ?>[]" class="netscorevalue" value="<?php echo $course[0]->$thisholepar;?>" /></p>
                        <?php } ?>

                        <?php if ($scoringsystem == 2) { // add "no score" option for stableford scoring ?>
							<input type="button" class="noscore" value="<?php _e( 'No Score', 'golfdeputy' ); ?>">
							<input type="hidden" class="stablefordpoints" name="<?php echo $roundimport[0]->matchup_id ?>[]" value=0>
                        <?php } ?>
                    </div>
                    
                </div>
            <?php } else { // match play scoring ?>
				<div class="matchupsave">
                    <span class="players"><?php echo stripslashes($roundimport[0]->player1);?> <span class="versus"><?php _e( 'vs', 'golfdeputy' ); ?></span> <?php echo stripslashes($roundimport[0]->player2); ?></span>
                    <input type="radio" value="1" name="<?php echo $roundimport[0]->matchup_id ?>" id="team1<?php echo $roundimport[0]->matchup_id ?>"><label for="team1<?php echo $roundimport[0]->matchup_id ?>"><?php echo stripslashes($roundimport[0]->player1); ?> <?php if (!empty($team1name)) { ?><span class="teamname">(<?php echo stripslashes($team1name); ?>)</span><?php } ?> <?php _e( 'win', 'golfdeputy' ); ?></label><br>
                    <input type="radio" value="2" name="<?php echo $roundimport[0]->matchup_id ?>" id="team2<?php echo $roundimport[0]->matchup_id ?>"><label for="team2<?php echo $roundimport[0]->matchup_id ?>"><?php echo stripslashes($roundimport[0]->player2); ?> <?php if (!empty($team2name)) { ?><span class="teamname">(<?php echo stripslashes($team2name); ?>)</span><?php } ?> <?php _e( 'win', 'golfdeputy' ); ?></label><br>
                    <input type="radio" value="-1" name="<?php echo $roundimport[0]->matchup_id ?>" id="halved<?php echo $roundimport[0]->matchup_id ?>"><label for="halved<?php echo $roundimport[0]->matchup_id ?>"><?php _e( 'Halved', 'golfdeputy' ); ?></label>
                    <input type="radio" value="0" name="<?php echo $roundimport[0]->matchup_id ?>" id="clear<?php echo $roundimport[0]->matchup_id ?>"><label for="clear<?php echo $roundimport[0]->matchup_id ?>"><?php _e( 'Clear hole', 'golfdeputy' ); ?></label>
                </div>
			<?php }
            }
		} ?>
		
		
		<br>
		<input type="submit" value="Save" id="savehole" autofocus>
		</form>
		
		<form method="POST" action="" id="resetplayers">
			<input type="hidden" name="reset" value="stopscoring">
			<input type="submit" value="Stop Scoring For These Players" id="stopscoring">
		</form>

	<?php } else { ?>
		<p><?php _e( 'Nothing selected. Try again.', 'golfdeputy' ); ?></p>
        
        <form method="POST" action="" id="resetplayers">
			<input type="hidden" name="reset" value="stopscoring">
			<input type="submit" value="Go Back" id="stopscoring">
		</form>
        
	<?php } ?>
</div>
<div id="leaderboard-wrapper">
<h2>Leaderboard</h2>
<?php echo do_shortcode( '[golf-deputy-leaderboard tournament_id="' . $roundimport[0]->tournament_id . '" show_round="' . $roundimport[0]->round_id . '"]' );?>
</div>

<script>

	jQuery(".stroke-btn").on("click", function () {
		var $button = jQuery(this);
		var $input = $button.closest('.stroke-quantity').find("input.quantity-input");
		
		$input.val(function(i, value) {
			var score = +value + (1 * +$button.data('multi'));
			if (score < 2) {
				score = 1;
			}
			return score;
		});

		handicapcalc($input, $button);
		stablefordcalcs();

	});

	jQuery(document).ready(function() {
    	handicapreset();
    	stablefordcalcs();
	});

	function handicapreset() {
		jQuery( ".stroke-btn" ).each(function( index ) {
    		var $button = jQuery(this);
    		var $input = $button.closest('.stroke-quantity').find("input.quantity-input");
    
			handicapcalc($input, $button);
		});
	}


	function handicapcalc($input, $button) {  // Handicap / Net Score Calculation

		if (jQuery('.handicap-value').length) {
			var $handicap = $button.parent().parent().siblings('.players').find('.handicap-value').html();
			var $netscore = $button.parent().siblings('.netscore').children('.netscorevalue');
		}

		$handicap = Number($handicap);
		var $stroke = Number($input.val());
		var $strokeindex = Number(jQuery("#strokeindex").html());
		//alert($strokeindex);

		// Negative handicaps
		if ($handicap >= 0) {
			if ($strokeindex <= $handicap) {
				$net = $stroke - 1;

				if ($handicap > 18) {
					if ($strokeindex <= ($handicap - 18)) {
						$net = $net - 1;
					}
				}

				if ($handicap > 36) {
					if ($strokeindex <= ($handicap - 36)) {
						$net = $net - 1;
					}
				}

				jQuery($netscore).val($net);
			} else { // if Stroke Index is greater than handicap, return same value as gross to net
				jQuery($netscore).val($stroke);
			}
		}

		// Positive Handicaps (max -18 handicap)
		if ($handicap < 0) {
			if ($strokeindex >= (19 + $handicap)) {
				$net = $stroke + 1;

				jQuery($netscore).val($net);
			} else { // if Stroke Index is greater than handicap, return same value as gross to net
				jQuery($netscore).val($stroke);
			}
		}
	}

	jQuery("#holenumber").on("change", function () {
		jQuery("#holeinfo-number").html(this.value);
		holeyardage = "holeyardage" + this.value;
		holepar = "holepar" + this.value;
		holehandicap = "holehandicap" + this.value;

		jQuery("#holeinfo-measurement").html(course[holeyardage]);
		jQuery("#holeinfo-par").html(course[holepar]);
		jQuery("#strokeindex").html(course[holehandicap]);
		jQuery('.sponsorimage').attr('src',sponsors[this.value-1]);
		//reset scores to par + run handicap net values

		handicapreset();
	});

	// Stableford Scoring Calcs
	function stablefordcalcs() {
		var par = Number(jQuery("#holeinfo-par").html());
		jQuery( ".stablefordpoints" ).each(function( index ) {
			var net = Number(jQuery(this).siblings('.netscore').children('.netscorevalue').val());
			if (net != 0) {
				if ((net - 2) == par) {
					jQuery(this).val('0'); // double bogey

				} else if ((net - 1) == par) {
					jQuery(this).val('1'); // single bogey

				} else if (net == par) {
					jQuery(this).val('2'); // par

				} else if ((net + 1) == par) {
					jQuery(this).val('3'); // birdie

				} else if ((net + 2) == par) {
					jQuery(this).val('4'); // eagle

				} else if ((net + 3) == par) {
					jQuery(this).val('5'); // albatross

				} else if ((net + 4) == par) {
					jQuery(this).val('6'); // condor... yes, it's called a CONDOR. Who knew, right?!

				} else if ((net + 5) == par) {
					jQuery(this).val('7'); // double-condor... because at some point, this naming just gets ludicrous... or Ludacris, if you prefer.

				} else {
					jQuery(this).val('0'); // larger than a double bogey
				}
			} else {
				jQuery(this).val('0');
			}
		});
	}

	jQuery(".noscore").on("click", function () { // "No Score" button for Stableford only; resets gross and net to 0
		jQuery(this).siblings(".netscore").find(".netscorevalue").val(0);
		jQuery(this).siblings(".stroke-input").find("input.quantity-input").val(0);
		stablefordcalcs();
	});
</script>

