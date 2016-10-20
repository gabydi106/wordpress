<?php
/*** Creates meta boxes for Golfer Information ***/
function golfer_info() {
	global $post;
	global $wpdb;
	$golferid = get_post_meta($post->ID, 'golfer_id', TRUE);
	
	// query, which returns the basic tournament info
	if (!empty($golferid)) {
		$result = gd_get_tournament_info( $query=array('tournament_id'=>$golferid, 'table'=>$wpdb->golf_deputy_golfers, 'idtomatch'=>'golfer_id', 'allowedfields'=>get_golfer_table_columns(), 'orderby'=>'golfer_id', 'number'=>-1, 'order'=>'ASC') );
	}
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( 'golf-deputy' ) . '" />';
	
	// Get the golfer data if its already been entered
	if (!empty($result)) {
		$golferhandicap = $result[0]->handicap;
		$golferbio = $result[0]->bio;
	}

	$sponsor_stored_meta = get_post_meta( $post->ID );
	if (!empty($sponsor_stored_meta)) {
		$sponsor_stored_meta = $sponsor_stored_meta['sponsor-image'][0];
	}

?>
	<div id="sponsors">
        <p>
            <label for="sponsor-image" class="sponsor-title"><?php _e( 'Golfer Image', 'golfdeputy' ); ?></label>
            <input type="hidden" class="imageinput" name="sponsor-image" id="sponsorimage" value="<?php if ( isset ( $sponsor_stored_meta[0]) ) echo $sponsor_stored_meta[0]; ?>" />
            <input type="button" class="button sponsor-image-button" id="sponsorimage" value="<?php _e( 'Choose or Upload an Image', 'golfdeputy' ); ?>" />
            <input type="button" class="button sponsor-image-button-remove" id="sponsorimage" value="<?php _e( 'Remove Image', 'golfdeputy' ); ?>" />
			<br><img src="<?php echo $sponsor_stored_meta ?>" id="sponsorimage">
        </p>
    </div>
        
    <form id="golferinfo">
        <label><?php _e( 'Handicap', 'golfdeputy' ); ?></label><input type="number" max="54" name="_golferhandicap" value="<?php if (!empty($golferhandicap)) {echo $golferhandicap;} ?>" /><br>
        <label><?php _e( 'Biography / Additional Information', 'golfdeputy' ); ?></label><textarea rows="5" name="_golferbio"><?php if (!empty($golferbio)) {echo $golferbio;} ?></textarea><br>
	</form>

<?php
}

function golfer_tournaments() {
	global $post;
	global $wpdb;
	$golferid = get_post_meta($post->ID, 'golfer_id', TRUE);
	
	// query, which returns the basic tournament info
	if (!empty($golferid)) {
		$result = gd_get_tournament_info( $query=array('tournament_id'=>$golferid, 'table'=>$wpdb->golf_deputy_golfers, 'idtomatch'=>'golfer_id', 'allowedfields'=>get_golfer_table_columns(), 'orderby'=>'golfer_id', 'number'=>-1, 'order'=>'ASC') );
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

<?php
}
?>