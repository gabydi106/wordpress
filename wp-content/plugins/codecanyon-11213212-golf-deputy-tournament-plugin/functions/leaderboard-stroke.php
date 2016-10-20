<?php
//query, which returns matchups data
$roundimport = gd_get_tournament_info( $query=array('tournament_id'=>$tournamentid, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'tournament_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'-overall', 'number'=>-1, 'order'=>'DESC') );

$options=get_option('golf_deputy_settings');

if (!empty($options['golf_deputy_belowpar_colour']) || (!empty($options['golf_deputy_team_colour_2']))) { ?>
	<style>
    <?php if (!empty($options['golf_deputy_belowpar_colour'])) {
        echo "#leaderboard .belowpar { color: " . $options['golf_deputy_belowpar_colour'] . "; }";
    }?>
    </style>
<?php }

// Get the location data if its already been entered
$team1name = $result[0]->team1name;
$totalrounds = $result[0]->rounds;
$name = $result[0]->name;
$roundnames = unserialize($result[0]->roundnames);

//Sponsorship ad, display if it exists
$sponsor_stored_meta = get_post_meta( $post->ID );
$sponsor_stored_meta = unserialize($sponsor_stored_meta['sponsor-image'][0]);

?>

<div id="leaderboard" class="strokeboard">
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
										location.reload();
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

	<?php for($j = 1; $j <= $totalrounds; $j++) { ?>
    
        <div class="golfround" id="round<?php echo $j;?>">
        	<div class="headers">
                <h4 class="card"><?php echo $roundnames[$j-1]; ?></h4>
                <div class="players" style="padding-top: 5px;">
                    <span class="playername"><strong><?php echo stripslashes($team1name);?></strong></span>
                </div>
                <?php if ($totalrounds > 1) { ?><div class="currentscore"><?php _e( "Overall", "golfdeputy" ); ?></div><?php } ?>
                <div class="currentscore"><?php _e( "Thru", "golfdeputy" ); ?></div>
                <div class="currentscore"><?php _e( "Today", "golfdeputy" ); ?></div>
            </div>


                <?php 
                foreach($roundimport as $key => $matchup) {					
                    if ($j == $matchup->round_id) {?>
                        <div class="multi-field" id="menu-items" matchup="<?php echo $matchup->matchup_id;?>">

                            <div class="players">
                            	<span class="playername"><?php echo stripslashes($matchup->player1);?></span>
                            </div>
                                                      
                            <?php 
							$overallscore = $matchup->overall;
                            
                            // OVERALL SCORE
                            // give class to $overallcore that are below pars, nothing is even or above par
                            if ($totalrounds > 1) {
                                if ($overallscore < 0) { ?>
                                    <div class="currentscore belowpar"><?php echo $overallscore;?></div>
                                <?php } elseif ($overallscore > 0) {
                                	$overallscore = "+". $overallscore; ?>
                                    <div class="currentscore"><?php echo $overallscore;?></div>
                                <?php } elseif (is_null($overallscore)) { ?>
    								<div class="currentscore">-</div>
                                <?php } else {
                                	$overallscore = __( "E", "golfdeputy" ); ?>
                                	<div class="currentscore"><?php echo $overallscore;?></div>
                                <?php }
                            }
                            
							$currentscore = $matchup->currentscore;
                            
                            // THRU ?>
                            <div class="currentscore">
								<?php
                                if ($matchup->thru == 18) { 
									_e( "F", "golfdeputy" );
								} else {
									if (!empty($matchup->thru)) {
                                        echo $matchup->thru;
                                    } else {
                                        echo "-";
                                    }
								} ?>
                            </div>						
							
                            
                            <?php
                            // TODAY SCORE
                            // give class to $currentscore that are below pars, nothing is even or above par
                            if ($currentscore < 0) { ?>
                                <div class="currentscore belowpar"><?php echo $currentscore;?></div>
                            <?php } elseif ($currentscore > 0) {
                            	$currentscore = "+". $currentscore; ?>
                                <div class="currentscore"><?php echo $currentscore;?></div>
                            <?php } elseif (is_null($currentscore)) { ?>
								<div class="currentscore">-</div>
                            <?php } else {
								$currentscore = __( "E", "golfdeputy" ); ?>
                            	<div class="currentscore"><?php echo $currentscore;?></div>                            
                            	
                            <?php } ?>
                            
                            <div class="scorecard">
                                <?php
                                // get course information to show on card
                                global $course;
                                $currentround = $matchup->round_id;
                                $courseid = $result[0]->course;
                                $courseid = unserialize($courseid);
                                $courseid = $courseid[$currentround-1]; // serialized array is a zero array, hence the -1
                                $course = gd_get_tournament_info( $query=array('tournament_id'=>$courseid, 'table'=>$wpdb->golf_deputy_courses, 'idtomatch'=>'course_id', 'allowedfields'=>get_course_table_columns(), 'orderby'=>'course_id', 'number'=>-1, 'order'=>'ASC') );
                                include(dirname( __FILE__ ) . '/card-stroke.php'); ?>
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