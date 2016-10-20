/*
 * Attaches the image uploader to the input field for sponsorship images in the admin area
 */
jQuery(document).ready(function($){
 
    // Instantiates the variable that holds the media library frame.
    var meta_image_frame;
 
    // Add image
    $('.sponsor-image-button').click(function(e){
		 
		// Prevents the default action from occuring.
		e.preventDefault();
		
		var id = '#' + $(this).attr('id');
		var imgclass = 'img#' + $(this).attr('id');

		// Sets up the media library frame
		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
			title: meta_image.title,
			button: { text:  meta_image.button },
			library: { type: 'image' }
		});
 
		// Runs when an image is selected.
		meta_image_frame.on('select', function(){
 
			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
 
			// Sends the attachment URL to our custom image input field.
			$(id).val(media_attachment.url);
			$(imgclass).attr("src", media_attachment.url);
			$(imgclass).css("display", 'block');
		});
 
		// Opens the media library frame.
		meta_image_frame.open();
    });
	
	// Removes image
	$('.sponsor-image-button-remove').click(function(e){
 
		// Prevents the default action from occuring.
		e.preventDefault();
		
		var id = '#' + $(this).attr('id');
		var imgclass = 'img#' + $(this).attr('id');
		
		// Removes the values from the fields
		$(id).val('');
		$(imgclass).attr("src", '');
		$(imgclass).css("display", 'none');
    });
	
});