<?php
/*** Creates meta boxes for Golf Course Information ***/
function golf_course_info() {
	global $post;
	global $wpdb;
	$courseid = get_post_meta($post->ID, 'course_id', TRUE);
	
	// query, which returns the basic tournament info
	if (!empty($courseid)) {
		$result = gd_get_tournament_info( $query=array('tournament_id'=>$courseid, 'table'=>$wpdb->golf_deputy_courses, 'idtomatch'=>'course_id', 'allowedfields'=>get_course_table_columns(), 'orderby'=>'course_id', 'number'=>-1, 'order'=>'ASC') );
	}
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( 'golf-deputy' ) . '" />';
	
	// Get the course data if its already been entered
	if (!empty($result)) {
		$courseaddress = $result[0]->courseaddress;
		$coursecity = $result[0]->coursecity;
		$coursestate = $result[0]->coursestate;
		$coursephone = $result[0]->coursephone;
		$measurement = $result[0]->measurement;
		$handicaplabel = $result[0]->handicaplabel;
	}

	// The HTML	for the input page
	?>
        
    <form id="courseinfo">
        <label><?php _e( 'Address:', 'golfdeputy' ); ?></label><input type="text" name="_courseaddress" value="<?php if (!empty($courseaddress)) {echo stripslashes($courseaddress);} ?>" /><br>
        <label><?php _e( 'City:', 'golfdeputy' ); ?></label><input type="text" name="_coursecity" value="<?php if (!empty($coursecity)) {echo stripslashes($coursecity);} ?>" /><br>
        <label><?php _e( 'State/Province:', 'golfdeputy' ); ?></label><input type="text" name="_coursestate" value="<?php if (!empty($coursestate)) {echo stripslashes($coursestate);} ?>" /><br>
        <label><?php _e( 'Phone:', 'golfdeputy' ); ?></label><input type="text" name="_coursephone" value="<?php if (!empty($coursephone)) {echo stripslashes($coursephone);} ?>" /><br><br>
        
        <div>
	        <label><?php _e( 'Measurement Unit:', 'golfdeputy' ); ?></label>
	        <input type="radio" name="_measurement" value="<?php _e( 'Yards', 'golfdeputy' ); ?>" id="measurementyards" checked /><label for="measurementyards"><?php _e( 'Yards', 'golfdeputy' ); ?></label>
	        <input type="radio" name="_measurement" value="<?php _e( 'Metres', 'golfdeputy' ); ?>" id="measurementmetres" <?php if (!empty($measurement)) { if ($measurement == "Metres") {echo "checked";} } ?>/><label for="measurementmetres"><?php _e( 'Metres', 'golfdeputy' ); ?></label>
	    </div>

	    <div id="handicap-wrapper">
	        <label><?php _e( 'Handicap Label:', 'golfdeputy' ); ?></label>
	        <input type="radio" name="_handicaplabel" value="Handicap" id="handicaphandicap" checked /><label for="handicaphandicap"><?php _e( 'Handicap', 'golfdeputy' ); ?></label>
	        <input type="radio" name="_handicaplabel" value="Stroke Index" id="handicapstrokeindex" <?php if (!empty($handicaplabel)) { if ($handicaplabel == "Stroke Index") {echo "checked";} } ?>/><label for="handicapstrokeindex"><?php _e( 'Stroke Index', 'golfdeputy' ); ?></label>
	        <input type="radio" name="_handicaplabel" value="None" id="handicapnone" <?php if (!empty($handicaplabel)) { if ($handicaplabel == "None") {echo "checked";} } ?>/><label for="handicapnone"><?php _e( 'None', 'golfdeputy' ); ?></label>
	    </div>
        <br>
	</form>

<?php
}

function golf_course_holes() {
	global $post;
	global $wpdb;
	$courseid = get_post_meta($post->ID, 'course_id', TRUE);
	
	// query, which returns the basic tournament info
	if (!empty($courseid)) {
		$result = gd_get_tournament_info( $query=array('tournament_id'=>$courseid, 'table'=>$wpdb->golf_deputy_courses, 'idtomatch'=>'course_id', 'allowedfields'=>get_course_table_columns(), 'orderby'=>'course_id', 'number'=>-1, 'order'=>'ASC') );
	}
	
	if (!empty($result)) {
		$teename = $result[0]->teename;
		$measurement = $result[0]->measurement;
		$handicaplabel = $result[0]->handicaplabel;
	}
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( 'golf-deputy' ) . '" />';
	
	// The HTML	for the input page
	?>
    <label class="teename"><?php _e( 'Tee Name:', 'golfdeputy' ); ?></label>&nbsp;<input type="text" class="teename" name="_teename" value="<?php if (!empty($teename)) {echo stripslashes($teename);} ?>" /><br>
    
    <div id="teeinputs">
        <?php for($j = 1; $j <= 18; $j++) { ?>
            <?php
			$yards = 'holeyardage' . $j;
			$par = 'holepar' . $j;
			$handicap = 'holehandicap' . $j;
			?>
            <div class="hole">
                <strong><?php echo $j;?></strong><br>
                <label class="measurementlabels"><?php if (!empty($measurement)) { if ($measurement == "Metres") {_e( 'Metres', 'golfdeputy' );} else { _e( 'Yards', 'golfdeputy' );} } else { _e( 'Yards', 'golfdeputy' );} ?></label><br>
                <input type="text" class="hole-yards" name="yardage[holeyardage<?php echo $j; ?>]" value="<?php if (!empty($result)) {echo $result[0]->$yards;} ?>"><br> 
                <label>Par</label><br>
                <input type="text" class="hole-par" name="par[holepar<?php echo $j; ?>]" value="<?php if (!empty($result)) {echo $result[0]->$par;} ?>"> 
                <label class="handicaplabels">
                	<?php if (!empty($handicaplabel)) { 
                		if ($handicaplabel == "Handicap") {
                			_e( 'Handicap', 'golfdeputy' );
                		} else if ($handicaplabel == "Stroke Index") {
                			_e( 'Stroke Index', 'golfdeputy' );
                		}
                		else {
                			// set to "None"
                		} 
                	} else {
                		_e( 'Handicap', 'golfdeputy' );
                	} ?></label><br>
                <input type="text" class="hole-handicap" name="handicap[holehandicap<?php echo $j; ?>]" <?php if (!empty($handicaplabel)) { if ($handicaplabel == "None") { echo "style='display: none;'";} }; ?>  value="<?php if (!empty($result)) {echo $result[0]->$handicap;} ?>"> 
            </div>
        <?php } ?>
    </div>


	<script>
	// Change Measurement Labels
	jQuery("#measurementyards, #measurementmetres").change(function () {
		jQuery('.measurementlabels').each(function() {
			if(jQuery('#measurementyards').is(':checked')) {
				jQuery(this).html("<?php _e( 'Yards', 'golfdeputy' ); ?>");
			} else {
				jQuery(this).html("<?php _e( 'Metres', 'golfdeputy' ); ?>");
			}
			
		});
	});

	jQuery("#handicaphandicap, #handicapstrokeindex, #handicapnone").change(function () {
		jQuery('.handicaplabels').each(function() {
			if(jQuery('#handicapnone').is(':checked')) {
				jQuery(this).hide();
				jQuery(".hole-handicap").hide();
			} else if(jQuery('#handicaphandicap').is(':checked')) {
				jQuery(this).show();
				jQuery(".hole-handicap").show();
				jQuery(this).html("<?php _e( 'Handicap', 'golfdeputy' ); ?>");
			} else {
				jQuery(this).show();
				jQuery(".hole-handicap").show();
				jQuery(this).html("<?php _e( 'Stroke Index', 'golfdeputy' ); ?>");
			}
			
		});
	});
	</script>

<?php
}
?>