<?php
/*** Add meta boxes on admin page for Sponsor/Advertising Images ***/
function golf_match_advertising() {
	global $post;
	$sponsor_stored_meta = get_post_meta( $post->ID );
	if (!empty($sponsor_stored_meta)) {
		$sponsor_stored_meta = unserialize($sponsor_stored_meta['sponsor-image'][0]);
	}
?>
	<p><strong><?php _e( 'A consistent image size across all holes is recommended. All images will be automatically resized to fit the screen.', 'golfdeputy' ); ?></strong></p>
	<div id="sponsors">
    	<?php for($j = 0; $j < 18; $j++) { ?>
        <p>
            <label for="sponsor-image" class="sponsor-title"><?php _e( 'Hole', 'golfdeputy' ); ?> #<?php echo $j+1;?> <?php _e( 'Sponsor', 'golfdeputy' ); ?></label>
            <input type="hidden" class="imageinput" name="sponsor-image[]" id="sponsorimage<?php echo $j;?>" value="<?php if ( isset ( $sponsor_stored_meta) ) echo $sponsor_stored_meta[$j]; ?>" />
            <input type="button" class="button sponsor-image-button" id="sponsorimage<?php echo $j;?>" value="<?php _e( 'Choose or Upload an Image', 'golfdeputy' ); ?>" />
            <input type="button" class="button sponsor-image-button-remove" id="sponsorimage<?php echo $j;?>" value="<?php _e( 'Remove Image', 'golfdeputy' ); ?>" />
			<br><img src="<?php echo $sponsor_stored_meta[$j]?>" id="sponsorimage<?php echo $j;?>">
        </p>
        <?php } ?>
    </div>

<?php
}

/*** Loads the image management javascript for sponsor images ***/
function prfx_image_enqueue() {
	wp_enqueue_media();

	wp_register_script( 'meta-box-image', plugin_dir_url( __FILE__ ) . '../meta-box-image.js', array( 'jquery' ) );
	wp_localize_script( 'meta-box-image', 'meta_image',
		array(
			'title' => __( __( "Choose or Upload an Image", "golfdeputy" ), 'prfx-textdomain' ),
			'button' => __( __( "Use This Image", "golfdeputy" ), 'prfx-textdomain' ),
		)
	);
	wp_enqueue_script( 'meta-box-image' );
}
add_action( 'admin_enqueue_scripts', 'prfx_image_enqueue' );

?>