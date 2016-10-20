<?php
/**
 * The template for displaying a tournament
 */

get_header();?>

	<div id="post-<?php the_ID(); ?>" class="container-wrap content-container pagecontainer">
    	<div class="container hentry">
    
                <header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header><!-- .entry-header -->
                
                <div class="entry-content">
					<?php
                    global $post;
                    while ( have_posts() ) : the_post();
                                        
                    // Check for session timeout, else initialize time
                    if (isset($_SESSION['timeout'])) {	
                        if ($_SESSION['timeout'] + 12 * 60 * 60 < time()){ // 12 hour session variable (12 x 60 min x 60 seconds)
                            session_destroy();
                        }
                    }
                    else {
                        $_SESSION['pass']="";
                        $_SESSION['timeout']=time();
                    }
                
                    if (isset($_POST["pass"])) {	
                        $_SESSION['pass']=hash('sha256',$_POST['pass']);
                        $_SESSION['matchups']= $_POST['matchupid'];
                        $_SESSION['team1name'] = $_POST['team1name'];
                        $_SESSION['team2name'] = $_POST['team2name'];
                    }
                
                    // Get tournament info and Check Login Data                   
                    $tournamentid = get_post_meta($post->ID, 'tournament_id', TRUE);
                
                    // query, which returns the basic tournament info
                    global $wpdb;
                    $result = gd_get_tournament_info( $query=array('tournament_id'=>$tournamentid) );
                    //query, which returns matchups data
                    $roundimport = gd_get_tournament_info( $query=array('tournament_id'=>$tournamentid, 'table'=>$wpdb->golf_deputy_matchups, 'idtomatch'=>'tournament_id', 'allowedfields'=>get_matchup_table_columns(), 'orderby'=>'matchup_id', 'number'=>-1, 'order'=>'ASC') );
                
                    // Noncename for plugin
                    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( 'golf-deputy' ) . '" />';
                    
                    // Get the location data if its already been entered
                    $pin = $result[0]->pin;
                    $team1name = $result[0]->team1name;
                    $team2name = $result[0]->team2name;
                    $totalrounds = $result[0]->rounds;
					$scoringsystem = $result[0]->scoringsystem;

                    // Get round names
                    $roundnames = unserialize($result[0]->roundnames);
                    
                    // hash the password
                    $pass = hash('sha256',$pin);
                    
                    //if the user clicks "Stop Scoring For These Players", it will kill the PHP session and return them to the PIN screen
                    if (isset($_POST['reset'])) {	
                        $_SESSION['pass'] = '';
                        session_destroy();
					} else if (isset($_POST['leaderboard'])) { // Watch the Leaderboard
						echo do_shortcode( '[golf-deputy-leaderboard tournament_id="' . $roundimport[0]->tournament_id . '"]' );?>
						<input action="action" type="button" value="<?php _e( 'Go Back', 'golfdeputy' ); ?>" onclick="history.go(-1);" id="backbutton" />
					<?php }
					
					if ((!isset($_POST['matchupcard'])) && (!isset($_POST['leaderboard'])) ) {
                        // stores player PINs in array
                        $playerpins = array();
                        foreach($roundimport as $key => $matchup) {
                            $playerpins[] = hash('sha256',$matchup->playerpin);
                        }
                        $sessionpass = $_SESSION['pass'];
                        //print_r($roundimport);
						if($sessionpass == $pass) { // check for Tournament PIN
							include_once("content-area.php"); // Load scoring form
						} else if (!empty($sessionpass)) {
                            if (in_array($sessionpass, $playerpins) ) { // check if the PIN entered is in the array as a whole
                                $findpin = array_search($sessionpass,$playerpins);
                                if (in_array ($roundimport[$findpin]->matchup_id, $_SESSION['matchups'])) { // if the selected player/matchup equals the PIN input, load scoring form
                                    unset($_SESSION['matchups']);
                                    $_SESSION['matchups'][0] = $roundimport[$findpin]->matchup_id; // remove all other matchups selected, use only the PIN one
                                    include_once("content-area.php"); // Load scoring form
                                } else {
                                    showloginform();
                                }
                            } else {
                                showloginform();
                            }
                        } else {
							// Show login form. Request for username and password
                            showloginform();
						}	
					}
                    // End loop.
                    endwhile;
                    ?>
                </div> <!-- entry content -->
	</div>
</div>

<?php function showloginform() {
    global $totalrounds;
    global $roundnames;
    global $team1name;
    global $team2name;
    global $scoringsystem;
    global $roundimport;
    global $totalrounds;

?>

<br>
<form method="POST" action="">
    <input type="hidden" name="leaderboard" value="true">
    <input type="submit" name="submit" value="<?php _e( 'Watch The Leaderboard', 'golfdeputy' ); ?>" id="watchleaderboard">
</form>

<div style="float: left; clear: both; width: 100%;">
    <span style="float: left; margin: 14px 0; width: 5%;"><strong><?php _e( 'OR', 'golfdeputy' ); ?></strong></span><hr style="width: 90%; float: left; margin: 20px 2%;">
</div>

<form method="POST" action="" id="matchupselect">
    <input type="hidden" value="<?php echo stripslashes($team1name); ?>" name="team1name" >
    <input type="hidden" value="<?php echo stripslashes($team2name); ?>" name="team2name" >
    <?php if ((!empty($roundimport[0]->player1)) || (!empty($roundimport[0]->player2))) {
        if (($scoringsystem == 0) || ($scoringsystem == 2)) { ?>
            <p><?php _e( 'Select the player(s) to score for:', 'golfdeputy' ); ?></p>

            <select name="round" id="roundselect">
                <?php for($j = 1; $j <= $totalrounds; $j++) { ?>
                    <option value="<?php echo $j;?>"><?php echo $roundnames[$j-1]; ?></option> 
                <?php } ?>
            </select>
            <?php for($j = 1; $j <= $totalrounds; $j++) { ?>
        
            <div class="golfround" id="round<?php echo $j;?>">
                    <?php 
                    foreach($roundimport as $key => $matchup) {
                        if ($j == $matchup->round_id) {?>
                            <div class="multi-field" id="menu-items">
                                <input type="checkbox" class="matchup_id" name="matchupid[]" value="<?php echo $matchup->matchup_id ?>">
                                <?php echo stripslashes($matchup->player1);?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    
            </div> <!-- /golfround -->    
            <?php } ?>                           
        <?php } else { ?>
            <p><?php _e( 'Select the matchup(s) to score for:', 'golfdeputy' ); ?></p>

            <select name="round" id="roundselect">
                <?php for($j = 1; $j <= $totalrounds; $j++) { ?>
                    <option value="<?php echo $j;?>"><?php echo $roundnames[$j-1]; ?></option> 
                <?php } ?>
            </select>

            <?php for($j = 1; $j <= $totalrounds; $j++) { ?>
        
            <div class="golfround" id="round<?php echo $j;?>">
                    <?php 
                    foreach($roundimport as $key => $matchup) {
                        if ($j == $matchup->round_id) {?>
                            <div class="multi-field" id="menu-items">
                                <input type="checkbox" class="matchup_id" name="matchupid[]" value="<?php echo $matchup->matchup_id ?>">
                                <?php if (!empty($team1name)) { ?><span class="teamname"><?php echo stripslashes($team1name); ?>: </span><?php } ?><?php echo stripslashes($matchup->player1);?> <span class="versus"><?php _e( 'vs', 'golfdeputy' ); ?></span> <?php if (!empty($team1name)) { ?><span class="teamname"><?php echo stripslashes($team2name); ?>: </span><?php } ?><?php echo stripslashes($matchup->player2);?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    
            </div> <!-- /golfround -->    
            <?php } ?>
        <?php } ?>
            <?php if(!empty($_SESSION['pass'])) {
                echo "<p id='pinerror'>". __( 'PIN is incorrect.', 'golfdeputy' ) . "</p>";
            } ?>
                <?php _e( 'Tournament or Player PIN:', 'golfdeputy' ); ?> <input type="password" name="pass" id="pass"><br/>
                <input type="submit" name="submit" value="<?php _e( 'Score for Selected Players', 'golfdeputy' ); ?>" id="submit">
            </form>

            <?php $options = get_option( 'golf_deputy_settings' );

            // Social Share Buttons; set in "Settings" area
            if ($options['golf_deputy_social_shares'] == "show") { ?>

                <hr style="width: 100%; float: left; margin: 20px 0;">

                <div id="social-shares">
                    <p>Share:</p>
                    <?php $url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; ?>
                    <a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url); ?>" data-link="<?php echo($url); ?>" class="facebook-share-button"></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo($url); ?>&e=<?php echo urlencode($url); ?>" class="twitter-share-button" target="_blank"></a>
                </div>

            <?php } ?>

        <?php } else { ?>
            <p><?php _e( 'No players added yet.', 'golfdeputy' ); ?></p><br>
        <?php }
} ?>
    
    
    
<script>
	jQuery( "#roundselect" ).change(function() {
		jQuery(".golfround input").prop("checked", false);
		var selected = jQuery(this).val();
		jQuery(".golfround").hide("fast");
		var show = "#round" + selected;
		jQuery(show).show("fast");
	});
</script>

<?php get_footer(); ?>


