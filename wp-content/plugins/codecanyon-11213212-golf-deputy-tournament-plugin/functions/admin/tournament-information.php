<?php
/*** Creates meta boxes for Tournament Information ***/
function golf_match_info() {
	global $post;
	global $wpdb;
	$tournamentid = get_post_meta($post->ID, 'tournament_id', TRUE);
	
	// query, which returns the basic tournament info
	if (!empty($tournamentid)) {
		$result = gd_get_tournament_info( $query=array('tournament_id'=>$tournamentid) );
	}
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( 'golf-deputy' ) . '" />';
	
	// Get the location data if its already been entered
	if (!empty($result)) {
		$pin = $result[0]->pin;
		$team1name = $result[0]->team1name;
		$team2name = $result[0]->team2name;
		global $totalrounds;
		$totalrounds = $result[0]->rounds;
	}
		
	// The HTML	for the input page
	?>
    <form id="tournamentinfo">
        <label><?php _e( 'Tournament PIN: ', 'golfdeputy' ); ?></label><input type="text" name="_pin" value="<?php if (!empty($pin)) {echo $pin;} ?>" id="pin" /><br>
        <label><?php _e( 'Player PIN: ', 'golfdeputy' ); ?></label><button type="button" id="generateplayerpins" class="button" /><?php _e( 'Generate Player PINs', 'golfdeputy' ); ?></button><br>
        <br>
        
        <label><?php _e( 'Scoring System: ', 'golfdeputy' ); ?></label>
        <select name="_scoringsystem" id="scoringsystem">
        	<option value="0"><?php _e( 'Strokes', 'golfdeputy' ); ?></option>
            <option value="1" <?php if (!empty($result)) {if ($result[0]->scoringsystem == 1) { echo "selected"; }}?>><?php _e( 'Match Play', 'golfdeputy' ); ?></option>
            <option value="2" <?php if (!empty($result)) {if ($result[0]->scoringsystem == 2) { echo "selected"; }}?>><?php _e( 'Stableford', 'golfdeputy' ); ?></option>
        </select>
        <br>
        <?php if (!empty($result)) {
				if (($result[0]->scoringsystem == 0) || ($result[0]->scoringsystem == 2)) { ?>
        	<style>
				#scorestroke {display: block;}
				#scorematch {display: none;}
			</style>
        <?php } 
		} else { ?>
        	<style>
				#scorestroke {display: none;}
				#scorematch {display: block;}
			</style>
        <?php 	}
		?>
        <div id="scorestroke">
            <label><?php _e( 'Scoring Type:', 'golfdeputy' ); ?></label>
            <select name="_scoringtype">
                <option value="0"><?php _e( 'Individual', 'golfdeputy' ); ?></option>
                <option value="1" <?php if (!empty($result)) {if ($result[0]->scoringtype == 1) { echo "selected"; }}?>><?php _e( 'Team (e.g. Scramble)', 'golfdeputy' ); ?></option>
            </select><br>        
        </div>
    
        <div id="scorematch">
        	<div>
                <label><?php _e( 'Scoring Type:', 'golfdeputy' ); ?></label>
                <select name="_numberofteams" id="numberofteams">
                    <option value="0" <?php if (!empty($result)) {if ($result[0]->numberofteams == 0) { echo "selected"; }}?>><?php _e( 'Individual (0 teams)', 'golfdeputy' ); ?></option>
                    <option value="2" <?php if (!empty($result)) {if ($result[0]->numberofteams != 0) { echo "selected"; }}?>><?php _e( 'Team (2 teams)', 'golfdeputy' ); ?></option>
                </select><br>
            </div>
            <?php if (!empty($result)) {
					if ($result[0]->numberofteams == 0) { ?>
        	<style>
				#teamnames {display: none;}
			</style>
        <?php } else { ?>
        	<style>
				#teamnames {display: block;}
			</style>
        <?php 	}
			} else { ?>
				<style>
					#teamnames {display: none;}
				</style>
			<?php } ?>
            <div id="teamnames">
                <label><?php _e( 'Team 1 Name: ', 'golfdeputy' ); ?></label><input type="text" name="_team1name" value="<?php if (!empty($team1name)) {echo stripslashes($team1name);} ?>" /><br>
                <label><?php _e( 'Team 2 Name: ', 'golfdeputy' ); ?></label><input type="text" name="_team2name" value="<?php if (!empty($team2name)) {echo stripslashes($team2name);} ?>" /><br>
            </div>
        </div>
        <br>
        
        <?php if (!empty($tournamentid)) { ?>
        	<label><?php _e( 'Leaderboard Shortcode: ', 'golfdeputy' ); ?></label><input type="text" disabled value="[golf-deputy-leaderboard tournament_id=<?php echo $tournamentid; ?>]" /><br>
        <?php } else { ?>
        	<label><?php _e( 'Leaderboard Shortcode: ', 'golfdeputy' ); ?></label><input type="text" disabled value="<?php _e( 'Save A Draft or Publish first', 'golfdeputy' ); ?>" /><br>
        <?php } ?>
	</form>

<?php
}

/*** Creates meta boxes for the tournament; if statements define stroke vs match play meta boxes ***/
function golf_match_teams() {
	global $post;
	global $wpdb;
	global $totalrounds;
	
	$tournamentid = get_post_meta($post->ID, 'tournament_id', TRUE);

	
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( 'golf-deputy' ) . '" />';
	
	if (!empty($tournamentid)) {
    	//Get tournament information
    	$result = gd_get_tournament_info( $query=array('tournament_id'=>$tournamentid) );
        if (!empty($result)) {
            $coursename = $result[0]->course;
            $roundnames = $result[0]->roundnames;
        }

    	// Get the match data if its already been entered
    	$roundimport = gd_get_tournament_info( $query=array('tournament_id'=>$tournamentid, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'tournament_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );
	}

    // Import Courses
    $course = gd_get_tournament_info( $query=array('tournament_id'=>'*', 'table'=>$wpdb->golf_deputy_courses, 'idtomatch'=>'all', 'allowedfields'=>get_course_table_columns(), 'orderby'=>'course_id', 'number'=>-1, 'order'=>'ASC') );

	
	if (!empty($result)) {
		if (($result[0]->scoringsystem == 0) || ($result[0]->scoringsystem == 2)) { //Stroke or Stableford Play ?>
			<style>
				.strokemeta {display: block;}
				.matchmeta {display: none;}
			</style>
		<?php } else { ?>
			<style>
				.strokemeta {display: none;}
				.matchmeta {display: block;}
			</style>
		<?php }
	} else { ?>
    	<style>
			.strokemeta {display: block;}
			.matchmeta {display: none;}
		</style>
    <?php }
	
	$round1players = 0;
	
	// Import CSV file ?>
	<?php if (!empty($_GET[success])) { echo "<b>Your file has been imported.</b><br><br>"; } //generic success notice ?> 
	
    <h4 class="roundtitle"><?php _e( 'Import Player List (CSV)', 'golfdeputy' ); ?></h4>
	<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
	  <?php _e( 'Choose your file: ', 'golfdeputy' ); ?><br /> 
	  <input name="csv" type="file" id="csv" /> 
      <input type="hidden" name="tournamentid" value="<?php echo $tournamentid;?>" />
	  <?php if ($tournamentid) { ?>
      	<input type="submit" name="Submit" value="Import" class="button" /> 
      <?php } else { ?>
		<input type="submit" name="Submit" value="Import" disabled="disabled" class="button" /><p style="font-size:10px"><strong><?php _e( 'Save the tournament (as Draft or Publish) to enable CSV import', 'golfdeputy' ); ?></strong></p>
      <?php } ?>
	</form> 
    
    <hr style="width: 100%; float: left; margin: 20px 0;">
	
	<?php
    // The HTML	for the input page	
	if (!empty($totalrounds)) {
		// get first round players to link additional rounds to
		foreach($roundimport as $key => $matchup) {
			if (1 == $matchup->round_id) {
				$round1names[$key][0] = $matchup->matchup_id;
				$round1names[$key][1] = $matchup->player1;
				$round1players++;
			}
		}
		
        $coursename = unserialize($coursename);
        $roundnames = unserialize($roundnames);

		for($j = 1; $j <= $totalrounds; $j++) { ?>
                <div class="golfround">
                	<div class="strokemeta"> <!-- Stroke and Stableford Meta Data -->
                        <?php if (!empty($roundnames[$j-1])) { // serialized array is a zero array, hence the -1 ?>
                            <h4 class="roundtitle"><input type="text" name="_roundnames[]" value="<?php echo $roundnames[$j-1]; ?>"></h4> 
                        <?php } else { ?>
                            <h4 class="roundtitle"><input type="text" name="_roundnames[]" value="Round <?php echo $j; ?>"></h4>
                        <?php } ?>

                        <label style="margin-right: 20px;"><strong><?php _e( 'Golf Course', 'golfdeputy' ); ?></strong></label>
                        <select name="_coursename[]">
                            <?php if (empty($course)) { ?>
                                <option value="0"><?php _e( 'No courses created.', 'golfdeputy' ); ?></option>
                            <?php } else {  ?>
                                <option value="0"><?php _e( 'Select A Course', 'golfdeputy' ); ?></option>
                                <?php foreach ($course as $key => $courseinfo) { ?>
                                    <option value="<?php echo $courseinfo->course_id;?>" <?php if (!empty($coursename[$j-1])) {if ($courseinfo->course_id == $coursename[$j-1]) { echo "selected"; }}?>><?php echo stripslashes($courseinfo->coursename) . ", " . stripslashes($courseinfo->teename);?> <?php _e( 'Tee', 'golfdeputy' ); ?></option>
                                <?php } 
                            } ?>
                        </select>

                        <?php if ($j > 1 ) { ?>
                            <p><span style="width: 30%; float: left; margin-right: 5.3%;"><strong><?php _e( 'Players', 'golfdeputy' ); ?></strong></span>
                            <span style="width: 75px; float: left; margin-right: 5.3%;"><strong><?php _e( 'Handicap', 'golfdeputy' ); ?></strong></span>
                            <span style="width: 75px; float: left; margin-right: 5.3%;"><strong><?php _e( 'Player PIN', 'golfdeputy' ); ?></strong></span>
                            <span><strong><?php _e( 'Same As Round 1 Player', 'golfdeputy' ); ?></strong></span></p>
                        <?php } else { ?>
                        	<p><span style="width: 30%; float: left; margin-right: 5.3%;"><strong><?php _e( 'Players', 'golfdeputy' ); ?></strong></span>
                            <span style="width: 75px; float: left; margin-right: 5.3%;"><strong><?php _e( 'Handicap', 'golfdeputy' ); ?></strong></span>
                            <span style="width: 75px; float: left; margin-right: 5.3%;"><strong><?php _e( 'Player PIN', 'golfdeputy' ); ?></strong></span></p>
                        <?php } ?>
                        <div class="multi-field-wrapper">
                            <div class="multi-fields">                    
                        <?php 
                            foreach($roundimport as $key => $matchup) {
                                if ($j == $matchup->round_id) {?>
                                    <div class="multi-field" id="menu-items">
                                        <input type="text" name="_round[<?php echo $j; ?>][_player1][]" id="team1" style="width: 30%; margin-right: 5%;" value="<?php echo stripslashes($matchup->player1);?>">
                                        <input type="number" max="54" name="_round[<?php echo $j; ?>][_handicap][]" id="handicap" style="width: 75px; margin-right: 5%;" value="<?php echo $matchup->handicap;?>">
                                        <input type="text" name="_round[<?php echo $j; ?>][_playerpin][]" class="playerpin" style="width: 75px; margin-right: 5%;" value="<?php echo $matchup->playerpin;?>">
                                        <input type="hidden" name="_round[<?php echo $j; ?>][_matchup_id][]" id="matchup_id" value="<?php echo $matchup->matchup_id ?>" >
                                        
                                        <?php if ($j > 1 ) { // if not first round, option to link to first round player ?>
                                            <select type="text" name="_round[<?php echo $j; ?>][_linkedto][]" id="team1" style="padding-right: 1%; margin-right: 5%;" value="<?php echo $matchup->linkedto;?>">
                                                <?php for ($k = 0; $k < $round1players; $k++) { ?>
                                                	<option value="<?php echo $round1names[$k][0]; ?>" <?php if ($round1names[$k][0] == $matchup->linkedto) { echo "selected"; } ?>><?php echo $round1names[$k][1];?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                        
                                        <button type="button" class="remove-field button"><?php _e( 'Remove', 'golfdeputy' ); ?></button>
                                    </div>
                                <?php } ?>
                            <?php }	?>
                            </div> <!-- /multi-fields --> 
                            <button type="button" class="add-field button" style="margin-top: 20px;"><?php _e( 'Add Player', 'golfdeputy' ); ?></button>               
                        </div> <!-- /multi-field-wrapper-->
                    </div>

                    <div class="matchmeta"> <!-- Match Meta Data -->
                        <?php if (!empty($roundnames[$j-1])) { ?>
                            <h4 class="roundtitle"><input type="text" name="_roundnames[]" value="<?php echo $roundnames[$j-1]; ?>"></h4>
                        <?php } else { ?>
                            <h4 class="roundtitle"><input type="text" name="_roundnames[]" value="Round <?php echo $j; ?>"></h4>
                        <?php } ?>

                        <label style="margin-right: 20px;"><strong><?php _e( 'Golf Course', 'golfdeputy' ); ?></strong></label>
                        <select name="_coursename[]">
                            <?php if (empty($course)) { ?>
                                <option value="0"><?php _e( 'No courses created.', 'golfdeputy' ); ?></option>
                            <?php } else {  ?>
                                <option value="0"><?php _e( 'Select A Course', 'golfdeputy' ); ?></option>
                                <?php foreach ($course as $key => $courseinfo) { ?>
                                    <option value="<?php echo $courseinfo->course_id;?>" <?php if (!empty($coursename[$j-1])) {if ($courseinfo->course_id == $coursename[$j-1]) { echo "selected"; }}?>><?php echo stripslashes($courseinfo->coursename) . ", " . stripslashes($courseinfo->teename);?> <?php _e( 'Tee', 'golfdeputy' );?></option>
                                <?php } 
                            } ?>
                        </select>

                        <p id="teamplayerstitle"><span style="width: 36.5%; float: left; margin-right: 5.3%;"><strong><?php _e( 'Team 1 Players', 'golfdeputy' ); ?></strong></span>
                        <span span style="width: 30%; float: left;"><strong><?php _e( 'Team 2 Players', 'golfdeputy' ); ?></strong></span>
                        <span style="width: 75px; float: left;"><strong><?php _e( 'Match PIN', 'golfdeputy' ); ?></strong></span></p>
                        <div class="multi-field-wrapper">
                            <div class="multi-fields">                    
                        <?php 
                            foreach($roundimport as $key => $matchup) {
                                if ($j == $matchup->round_id) {?>
                                    <div class="multi-field" id="menu-items">
                                        <input type="text" name="_round[<?php echo $j; ?>][_player1][]" id="team1" style="width: 30%; margin-right: 5%;" value="<?php echo stripslashes($matchup->player1);?>">
                                        <span class="versus" style="width: 5%; margin-right: 5%;"><?php _e( 'vs', 'golfdeputy' ); ?></span>
                                        <input type="text" name="_round[<?php echo $j; ?>][_player2][]" style="width: 30%;" value="<?php echo stripslashes($matchup->player2);?>">
                                        <input type="hidden" name="_round[<?php echo $j; ?>][_matchup_id][]" id="matchup_id" value="<?php echo $matchup->matchup_id ?>" >
                                        <input type="text" name="_round[<?php echo $j; ?>][_playerpin][]" class="playerpin" style="width: 75px; margin-right: 5%;" value="<?php echo $matchup->playerpin;?>">
                                        <button type="button" class="remove-field button"><?php _e( 'Remove', 'golfdeputy' ); ?></button>
                                    </div>
                                    <?php $matchupwin = $matchup->win_points; ?>
                                <?php } ?>
                            <?php }	?>
                            </div> <!-- /multi-fields --> 
                            <button type="button" class="add-field button" style="margin-top: 20px;"><?php _e( 'Add Players', 'golfdeputy' ); ?></button>               
                        </div> <!-- /multi-field-wrapper-->
                        <br><label class="winpoints"><?php _e( 'Win Points', 'golfdeputy' ); ?> &nbsp;</label><input type="text" name="_round[<?php echo $j; ?>][_winpoints]" value="<?php echo $matchupwin; ?>" style="width: 50px;" /><br>
                    </div>
                    <hr style="width: 100%; float: left; margin: 20px 0;">
                </div> <!-- /golfround -->
		<?php } // end for loop
	} else { //if $totalrounds is empty ?>
        <div class="golfround">
        	<div class="strokemeta">
                <h4 class="roundtitle"><input type="text" name="_roundnames[]" value="Round 1"></h4>
                
                <label style="margin-right: 20px;"><strong><?php _e( 'Golf Course', 'golfdeputy' ); ?></strong></label>
                <select name="_coursename[]">
                    <?php if (empty($course)) { ?>
                        <option value="0"><?php _e( 'No courses created.', 'golfdeputy' ); ?></option>
                    <?php } else {  ?>
                        <option value="0"><?php _e( 'Select A Course', 'golfdeputy' ); ?></option>
                        <?php foreach ($course as $key => $courseinfo) { ?>
                            <option value="<?php echo $courseinfo->course_id;?>" <?php if (!empty($coursename[$j-1])) {if ($courseinfo->course_id == $coursename[$j-1]) { echo "selected"; }}?>><?php echo stripslashes($courseinfo->coursename) . ", " . stripslashes($courseinfo->teename);?> <?php _e( 'Tee', 'golfdeputy' ); ?></option>
                        <?php } 
                    } ?>
                </select>

                <p><span style="width: 30%; float: left; margin-right: 5.3%;"><strong><?php _e( 'Players', 'golfdeputy' ); ?></strong></span>
                <span style="width: 75px; float: left; margin-right: 5.3%;"><strong><?php _e( 'Handicap', 'golfdeputy' ); ?></strong></span>
                <span style="width: 75px; float: left;"><strong><?php _e( 'Player PIN', 'golfdeputy' ); ?></strong></span></p>
                <div class="multi-field-wrapper">
                    <div class="multi-fields">                    
                        <div class="multi-field" id="menu-items">
                            <input type="text" name="_round[1][_player1][]" id="team1" style="width: 30%; margin-right: 5%;" value="">
                            <input type="number" name="_round[1][_handicap][]" id="handicap" style="width: 75px; margin-right: 5%;" value="">
                            <input type="hidden" name="_round[1][_matchup_id][]" id="matchup_id" value="" >
                            <input type="text" name="_round[1][_playerpin][]" class="playerpin" style="width: 75px; margin-right: 5%;" value="">
                            <button type="button" class="remove-field button"><?php _e( 'Remove', 'golfdeputy' ); ?></button>
                        </div>
                    </div> <!-- /multi-fields --> 
                    <button type="button" class="add-field button" style="margin-top: 20px;"><?php _e( 'Add Player', 'golfdeputy' ); ?></button>               
                </div> <!-- /multi-field-wrapper-->
                <hr style="width: 100%; float: left; margin: 20px 0;">
			</div>
            
            <div class="matchmeta">
            	<h4 class="roundtitle"><input type="text" name="_roundnames[]" value="<?php _e( 'Round 1', 'golfdeputy' ); ?>"></h4>

                <label style="margin-right: 20px;"><strong><?php _e( 'Golf Course', 'golfdeputy' ); ?></strong></label>
                <select name="_coursename[]">
                    <?php if (empty($course)) { ?>
                        <option value="0"><?php _e( 'No courses created.', 'golfdeputy' ); ?></option>
                    <?php } else {  ?>
                        <option value="0"><?php _e( 'Select A Course', 'golfdeputy' ); ?></option>
                        <?php foreach ($course as $key => $courseinfo) { ?>
                            <option value="<?php echo $courseinfo->course_id;?>" <?php if (!empty($coursename[$j-1])) {if ($courseinfo->course_id == $coursename[$j-1]) { echo "selected"; }}?>><?php echo $courseinfo->coursename . ", " . $courseinfo->teename;?> <?php _e( 'Tee', 'golfdeputy' ); ?></option>
                        <?php } 
                    } ?>
                </select>

                <p><span style="width: 36.5%; float: left; margin-right: 5.3%;"><strong><?php _e( 'Team 1 Players', 'golfdeputy' ); ?></strong></span>
                <span style="width: 30%; float: left;"><strong><?php _e( 'Team 2 Players', 'golfdeputy' ); ?></strong></span>
                <span style="width: 75px; float: left;"><strong><?php _e( 'Match PIN', 'golfdeputy' ); ?></strong></span></p>
                <div class="multi-field-wrapper">
                    <div class="multi-fields">                    
                        <div class="multi-field" id="menu-items">
                            <input type="text" name="_round[1][_player1][]" id="team1" style="width: 30%; margin-right: 5%;" value="">
                            <span class="versus" style="width: 5%; margin-right: 5%;"><?php _e( 'vs', 'golfdeputy' ); ?></span>
                            <input type="text" name="_round[1][_player2][]" style="width: 30%;" value="">
                            <input type="hidden" name="_round[1][_matchup_id][]" id="matchup_id" value="" >
                            <input type="text" name="_round[1][_playerpin][]" class="playerpin" style="width: 75px; margin-right: 5%;" value="">
                            <button type="button" class="remove-field button"><?php _e( 'Remove', 'golfdeputy' ); ?></button>
                        </div>
                    </div> <!-- /multi-fields --> 
                    <button type="button" class="add-field button" style="margin-top: 20px;"><?php _e( 'Add Players', 'golfdeputy' ); ?></button>               
                </div> <!-- /multi-field-wrapper-->
                <br><label class="winpoints"><?php _e( 'Win Points', 'golfdeputy' ); ?> &nbsp;</label><input type="text" name="_round[1][_winpoints]" value="" style="width: 50px;" /><br>
            </div>
			            
        </div> <!-- /golfround -->
    <?php } ?>

    
    <button type="button" class="add-round button" style="margin-top: 20px;"><?php _e( 'Add Round', 'golfdeputy' ); ?></button>
    <button type="button" class="remove-round button" style="margin-top: 20px;"><?php _e( 'Remove Round', 'golfdeputy' ); ?></button>
    
    <?php if (!empty($result)) {
		if (($result[0]->scoringsystem == 0) || ($result[0]->scoringsystem == 2)) { //Stroke or Stableford Play ?>
			<script>
				jQuery(".strokemeta :input").attr("disabled", false);
				jQuery(".matchmeta :input").attr("disabled", true);
                jQuery(".roundtitle").attr("disabled", false);
			</script>
		<?php } else { ?>
			<script>
				jQuery(".strokemeta :input").attr("disabled", true);
				jQuery(".matchmeta :input").attr("disabled", false);
                jQuery(".roundtitle").attr("disabled", false);
			</script>
		<?php }
	} else { ?>
		<script>
			jQuery(".strokemeta :input").attr("disabled", false);
			jQuery(".matchmeta :input").attr("disabled", true);
            jQuery(".roundtitle").attr("disabled", false);
		</script>
	<?php } ?>
    
    
	<script>
		//Change input fields on Scoring System Change
		jQuery( "#scoringsystem" ).change(function() {
		  if ((jQuery( this ).val() == 0) || (jQuery( this ).val() == 2)) { // Stroke or Stableford
			 jQuery("#scorestroke").css('display', 'block');
			 jQuery("#scorematch").css('display', 'none');
			 jQuery(".strokemeta").css('display', 'block');
			 jQuery(".matchmeta").css('display', 'none');
			 
			 jQuery(".strokemeta :input").attr("disabled", false);
			 jQuery(".matchmeta :input").attr("disabled", true);
		  } else {
			 jQuery("#scorestroke").css('display', 'none');
			 jQuery("#scorematch").css('display', 'block');
			 jQuery(".strokemeta").css('display', 'none');
			 jQuery(".matchmeta").css('display', 'block');
			 
			 jQuery(".strokemeta :input").attr("disabled", true);
			 jQuery(".matchmeta :input").attr("disabled", false);
		  }
		});
		
		jQuery( "#numberofteams" ).change(function() {
		  if (jQuery( this ).val() == 0) {
			 jQuery("#teamnames").css('display', 'none');
		  } else {
			 jQuery("#teamnames").css('display', 'block');
		  }
		});
		
		// Add/remove Rounds
		jQuery('#golf_match_teams').each(function() {
			jQuery(".add-round", jQuery(this)).click(function(e) {
				jQuery('.golfround:last').clone(true, true).insertAfter('#golf_match_teams .inside .golfround:last').find('#matchup_id').val('');
				
				var previous = parseInt(jQuery('.golfround:last #team1').attr('name').substr(7,2));
				var next = previous + 1;
				jQuery('.golfround:last').find('input').each(function(i) {
					this.name= this.name.replace('[' + previous + ']', '['+ next+']');
				});
				jQuery('.golfround:last').find('select').each(function(i) {
					this.name= this.name.replace('[' + previous + ']', '['+ next+']');
				});
				
                var nextround = "<?php _e( 'Round', 'golfdeputy' ); ?>" + " " + next;
				jQuery('.golfround:last .roundtitle input').val(nextround);
            });
						
            jQuery('.remove-round').click(function() {
                if (jQuery('#golf_match_teams .inside .golfround').length > 1)
                    jQuery('#golf_match_teams .inside .golfround:last').remove();
            });
        });
		
		// Add/remove Players
		jQuery('.multi-field-wrapper').each(function() {
			var $wrapper = jQuery('.multi-fields', this);
			jQuery(".add-field", jQuery(this)).click(function(e) {
				var $wrapper = jQuery(this).parent().find('.multi-fields');
				jQuery('.multi-field:last-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('');
			});
			jQuery('.multi-field .remove-field', $wrapper).click(function() {
				if (jQuery('.multi-field', $wrapper).length > 1) {
					var matchupid = jQuery(this).parent().find('#matchup_id').val();
					var url = "<?php echo plugins_url('api.php', __FILE__);?>";
					jQuery.ajax({
						url: url,
						type: 'post',
						data: { "callFunc": matchupid},
						success: function(response) {alert(response); }
					});
					
					jQuery(this).parent('.multi-field').remove();
            		return false;
				}
			});
		});

        // Generate random Player PINS (4 digits) in each .playerpin field
		jQuery( "#generateplayerpins" ).click(function() {
            jQuery('.playerpin').each(function() {
                newpin = Math.floor(Math.random() * 8999) + 1000;
                jQuery(this).val(newpin);
            });

        });

    </script>
    
<?php
}
?>