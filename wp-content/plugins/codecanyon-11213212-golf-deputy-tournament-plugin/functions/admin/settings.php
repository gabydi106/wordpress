<?php 
/*** Add the Settings Page ***/
add_action('admin_menu', 'golf_deputy_settings');
add_action( 'admin_init', 'golf_deputy_settings_init' );

function golf_deputy_settings() {
	add_submenu_page('edit.php?post_type=tournament', __( 'All Courses', 'golfdeputy' ), __( 'All Courses', 'golfdeputy' ), 'manage_options', 'edit.php?post_type=golfcourse');
	add_submenu_page('edit.php?post_type=tournament', __( 'Add New Course', 'golfdeputy' ), __( 'Add New Course', 'golfdeputy' ), 'manage_options', 'post-new.php?post_type=golfcourse');
	//add_submenu_page('edit.php?post_type=tournament', __( 'All Golfers', 'golfdeputy' ), __( 'All Golfers', 'golfdeputy' ), 'manage_options', 'edit.php?post_type=golfer');
	//add_submenu_page('edit.php?post_type=tournament', __( 'Add New Golfer', 'golfdeputy' ), __( 'Add New Golfer', 'golfdeputy' ), 'manage_options', 'post-new.php?post_type=golfer');
    add_submenu_page('edit.php?post_type=tournament', __('Settings','golfdeputy'), __('Settings','golfdeputy'), 'manage_options', 'golf_deputy', 'golf_deputy_options_page');
}


function golf_deputy_settings_init(  ) { 
	register_setting( 'pluginPage', 'golf_deputy_settings' );

	add_settings_section(
		'golf_deputy_pluginPage_section', 
		__( '', 'golfdeputy' ), 
		'golf_deputy_settings_section_callback', 
		'pluginPage'
	);
	
	add_settings_section(
		'golf_deputy_matchoptions_section', 
		__( 'Match Play Leaderboard Settings', 'golfdeputy' ), 
		'', 
		'pluginPage'
	);
	
	add_settings_field( 
		'golf_deputy_team_colour_1', 
		__( 'Team 1 Colour', 'golfdeputy' ), 
		'golf_deputy_team_colour_1_render', 
		'pluginPage', 
		'golf_deputy_matchoptions_section' 
	);

	add_settings_field( 
		'golf_deputy_team_colour_2', 
		__( 'Team 2 Colour', 'golfdeputy' ), 
		'golf_deputy_team_colour_2_render', 
		'pluginPage', 
		'golf_deputy_matchoptions_section' 
	);
	
	add_settings_section(
		'golf_deputy_strokeoptions_section', 
		__( 'Stroke Play Leaderboard Settings', 'golfdeputy' ),
		'', 
		'pluginPage'
	);
	
	add_settings_field( 
		'golf_deputy_belowpar_colour', 
		__( 'Below Par Colour', 'golfdeputy' ), 
		'golf_deputy_belowpar_colour_render', 
		'pluginPage', 
		'golf_deputy_strokeoptions_section' 
	);
	
	add_settings_section(
		'golf_deputy_generaloptions_section', 
		__( 'General Settings', 'golfdeputy' ),
		'', 
		'pluginPage'
	);

	add_settings_field( 
		'golf_deputy_social_shares', 
		__( 'Social Shares', 'golfdeputy' ), 
		'golf_deputy_social_shares_render', 
		'pluginPage', 
		'golf_deputy_generaloptions_section' 
	);

	add_settings_field( 
		'golf_deputy_custom_css', 
		__( 'Custom CSS', 'golfdeputy' ), 
		'golf_deputy_custom_css_render', 
		'pluginPage', 
		'golf_deputy_generaloptions_section' 
	);
}

function golf_deputy_team_colour_1_render(  ) { ?>
	<?php $options = get_option( 'golf_deputy_settings' );?>
	<select name='golf_deputy_settings[golf_deputy_team_colour_1]'>
		<option value='' <?php selected( $options['golf_deputy_team_colour_1'], '' ); ?>>None</option>
        <option value='#950000' <?php selected( $options['golf_deputy_team_colour_1'], '#950000' ); ?>>Red</option>
		<option value='#000095' <?php selected( $options['golf_deputy_team_colour_1'], '#000095' ); ?>>Blue</option>
        <option value='#009500' <?php selected( $options['golf_deputy_team_colour_1'], '#009500' ); ?>>Green</option>
        <option value='#cdcd00' <?php selected( $options['golf_deputy_team_colour_1'], '#cdcd00' ); ?>>Yellow</option>
        <option value='#850095' <?php selected( $options['golf_deputy_team_colour_1'], '#850095' ); ?>>Purple</option>
        <option value='#e800bf' <?php selected( $options['golf_deputy_team_colour_1'], '#e800bf' ); ?>>Pink</option>
        <option value='#cd9100' <?php selected( $options['golf_deputy_team_colour_1'], '#cd9100' ); ?>>Orange</option>
	</select>
<?php
}

function golf_deputy_team_colour_2_render(  ) { 
	$options = get_option( 'golf_deputy_settings' );
	?>
	<select name='golf_deputy_settings[golf_deputy_team_colour_2]'>
		<option value='' <?php selected( $options['golf_deputy_team_colour_2'], '' ); ?>>None</option>
        <option value='#950000' <?php selected( $options['golf_deputy_team_colour_2'], '#950000' ); ?>>Red</option>
		<option value='#000095' <?php selected( $options['golf_deputy_team_colour_2'], '#000095' ); ?>>Blue</option>
        <option value='#009500' <?php selected( $options['golf_deputy_team_colour_2'], '#009500' ); ?>>Green</option>
        <option value='#cdcd00' <?php selected( $options['golf_deputy_team_colour_2'], '#cdcd00' ); ?>>Yellow</option>
        <option value='#850095' <?php selected( $options['golf_deputy_team_colour_2'], '#850095' ); ?>>Purple</option>
        <option value='#e800bf' <?php selected( $options['golf_deputy_team_colour_2'], '#e800bf' ); ?>>Pink</option>
        <option value='#cd9100' <?php selected( $options['golf_deputy_team_colour_2'], '#cd9100' ); ?>>Orange</option>
	</select>
	<br><br><br>
<?php
}

function golf_deputy_social_shares_render(  ) { ?>
	<?php $options = get_option( 'golf_deputy_settings' );?>
	<select name='golf_deputy_settings[golf_deputy_social_shares]'>
		<option value='show' <?php selected( $options['golf_deputy_social_shares'], 'show' ); ?>>Show</option>
        <option value='hide' <?php selected( $options['golf_deputy_social_shares'], 'hide' ); ?>>Hide</option>
	</select>
	<br><br><br>
<?php
}

function golf_deputy_belowpar_colour_render(  ) { ?>
	<?php $options = get_option( 'golf_deputy_settings' );?>
	<select name='golf_deputy_settings[golf_deputy_belowpar_colour]'>
		<option value='' <?php selected( $options['golf_deputy_belowpar_colour'], '' ); ?>>None</option>
        <option value='#FF0000' <?php selected( $options['golf_deputy_belowpar_colour'], '#FF0000' ); ?>>Red</option>
		<option value='#0000FF' <?php selected( $options['golf_deputy_belowpar_colour'], '#0000FF' ); ?>>Blue</option>
        <option value='#00FF00' <?php selected( $options['golf_deputy_belowpar_colour'], '#00FF00' ); ?>>Green</option>
        <option value='#FFFF00' <?php selected( $options['golf_deputy_belowpar_colour'], '#FFFF00' ); ?>>Yellow</option>
        <option value='#990099' <?php selected( $options['golf_deputy_belowpar_colour'], '#990099' ); ?>>Purple</option>
        <option value='#FF00FF' <?php selected( $options['golf_deputy_belowpar_colour'], '#FF00FF' ); ?>>Pink</option>
        <option value='#FF6600' <?php selected( $options['golf_deputy_belowpar_colour'], '#FF6600' ); ?>>Orange</option>
	</select>
	<br><br><br>
<?php
}


function golf_deputy_custom_css_render() { ?>
	<?php $options = get_option( 'golf_deputy_settings' );?>
	<textarea rows="10" cols="40" name='golf_deputy_settings[golf_deputy_custom_css]'><?php echo esc_textarea($options['golf_deputy_custom_css']); ?></textarea>
	<?php
}

function golf_deputy_settings_section_callback() { 
	_e( 'The options for Golf Deputy.', 'golfdeputy' );
}

function golf_deputy_options_page() { 
	?>
	<form action='options.php' method='post'>
		<h1><?php _e( 'Golf Deputy Settings', 'golfdeputy' ); ?></h1>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
	</form>
	<?php
}


function golf_deputy_admin_style() {
	wp_register_style( 'GolfDeputyStyles', plugins_url('../../css/admin-styles.css', __FILE__) );
	wp_enqueue_style( 'GolfDeputyStyles' );
}
add_action( 'admin_enqueue_scripts', 'golf_deputy_admin_style' );
?>