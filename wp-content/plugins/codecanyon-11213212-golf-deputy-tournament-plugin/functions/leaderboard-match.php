<?php
//query, which returns matchups data
$roundimport = gd_get_tournament_info( $query=array('tournament_id'=>$tournamentid, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'tournament_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );

$options=get_option('golf_deputy_settings');

if (!empty($options['golf_deputy_team_colour_1']) || (!empty($options['golf_deputy_team_colour_2']))) { ?>
	<style>
    <?php if (!empty($options['golf_deputy_team_colour_1'])) {
        echo ".team1colour .flag { border-color: " . $options['golf_deputy_team_colour_1'] . $options['golf_deputy_team_colour_1'] . $options['golf_deputy_team_colour_1'] . " transparent; }"; // flags
    }
    if (!empty($options['golf_deputy_team_colour_2'])) {
        echo ".team2colour .flag { border-color: " . $options['golf_deputy_team_colour_2'] . " transparent " . $options['golf_deputy_team_colour_2'] . $options['golf_deputy_team_colour_2'] . "; }"; // flags
    } ?>
    </style>
<?php }

// Get the location data if its already been entered
$team1name = $result[0]->team1name;
$team2name = $result[0]->team2name;
$totalrounds = $result[0]->rounds;
$name = $result[0]->name;
$team1totalscore = $result[0]->team1score;
$team2totalscore = $result[0]->team2score;

// converts 0.5 to "1/2" fraction
if(abs($team1totalscore) - (int)(abs($team1totalscore)) == 0.5) {
    $team1totalscore = strval((int)(abs($team1totalscore))) . "&frac12;";
} else {
    $team1totalscore = abs($team1totalscore);
}
if(abs($team2totalscore) - (int)(abs($team2totalscore)) == 0.5) {
    $team2totalscore = strval((int)(abs($team2totalscore))) . "&frac12;";
} else {
    $team2totalscore = abs($team2totalscore);
}

$numberofteams = $result[0]->numberofteams;
$roundnames = unserialize($result[0]->roundnames);

//Sponsorship ad, display if it exists
$sponsor_stored_meta = get_post_meta( $post->ID );
$sponsor_stored_meta = unserialize($sponsor_stored_meta['sponsor-image'][0]);

?>

<div id="leaderboard">
    <span onclick="refreshpage()" href="">
        <div id="refresh">
            <?php if (!empty($_POST['leaderboard'])) {
				echo '<div id="refreshtime">' . __( "Auto-refresh in", "golfdeputy" ) . ' <span id="timecountdown">120</span> ' . __( "seconds", "golfdeputy" ). '.</div>';
				echo "<!-- auto-refresh page every 2 minutes -->
						<script>
							jQuery(window).ready( function() {
								var time = 120;
								setInterval( function() {
									time--;
									jQuery('#timecountdown').html(time);
									if (time === 0) {
										location.reload(true);
									}    
								}, 1000 );
							});
						</script>";
			} else {
				echo 'Refresh';
			}?>
        </div>
    </span>
    
    <?php if (!empty($_POST['leaderboard'])) { // if "Watch The Leaderboard" is clicked, show a random sponsor ad here
    $random = rand(0,17);

        if (!empty($sponsor_stored_meta[$random])) {
    ?>
            <span style="text-align: center; width: 100%; float: left;">
                <img src="<?php echo $sponsor_stored_meta[$random]?>" alt="Hole #<?php echo $random+1; ?> Sponsor" title="Hole #<?php echo $random+1; ?> Sponsor" class="sponsorimage">
            </span>
    <?php
        }
    } ?>

    <div id="leaderboardheader" id="menu-items">
        <div id="totalscores">
            <span class="team1colour"><?php echo stripslashes($team1name); ?><div class="flag"><span><?php echo $team1totalscore; ?></span></div></span>
            <span class="team2colour"><div class="flag"><span><?php echo $team2totalscore; ?></span></div><?php echo stripslashes($team2name); ?></span>
        </div>
    </div>
	<?php for($j = 1; $j <= $totalrounds; $j++) { ?>
        
        <div class="golfround" id="round<?php echo $j;?>">
            <h4 class="card"><?php echo $roundnames[$j-1]; ?></h4>
            <?php 
            foreach($roundimport as $key => $matchup) {
    			$team1score = 0;
    			$team2score = 0;
    			// gets who is winning for every match
    			foreach ( $matchup as $hole => $score) {
    				if("hole" == substr($hole,0,4)){
    					
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

    			
                if ($j == $matchup->round_id) {?>
                    <div class="multi-field" id="menu-items">
                        <div class="players">
                        	<span class="playername"><?php echo stripslashes($matchup->player1);?></span>

                            <?php // give class to the current leader, nothing if All Square
                            if ($team1score > $team2score) { ?>
                                <div class="currentscore team1colour">
                                    <div class="flag"></div>
                                    <?php echo $matchup->currentscore;?>
                                </div>
                            <?php } elseif ($team2score > $team1score) { ?>
                                <div class="currentscore team2colour">
                                    <?php echo $matchup->currentscore;?>
                                    <div class="flag"></div>
                                </div>
                            <?php } elseif ($team2score == $team1score) { ?>
                                <?php if ($matchup->currentscore != null) { ?>
                                    <div class="currentscore">
                                        <?php echo $matchup->currentscore;?>
                                    </div>
                                <?php } else { ?>
                                    <div class="currentscore">
                                        -
                                    </div>
                                <?php } ?>
                            <?php } ?>

    						<span class="playername alignright"><?php echo stripslashes($matchup->player2);?></span>
                        </div>

                        <div class="scorecard">
                            <?php
                            // get course information to show on card
                            global $course;
                            $currentround = $matchup->round_id;
                            $courseid = $result[0]->course;
                            $courseid = unserialize($courseid);
                            $courseid = $courseid[$currentround-1]; // serialized array is a zero array, hence the -1
                            $course = gd_get_tournament_info( $query=array('tournament_id'=>$courseid, 'table'=>$wpdb->golf_deputy_courses, 'idtomatch'=>'course_id', 'allowedfields'=>get_course_table_columns(), 'orderby'=>'course_id', 'number'=>-1, 'order'=>'ASC') );
                            include(dirname( __FILE__ ) . '/card-match.php'); ?>
                        </div>

                    </div>
                <?php }
            	} ?>

        </div> <!-- /golfround -->
    <?php } ?>

</div>

<script>
    jQuery('.multi-field').click(function(){
        if (jQuery(this).hasClass('active')) {
            jQuery(this).removeClass('active');
        } else {
            jQuery(this).addClass('active');
        }
        jQuery(this).children('.scorecard').toggle();
    });
</script>