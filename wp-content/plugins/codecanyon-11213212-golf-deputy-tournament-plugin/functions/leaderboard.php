<?php
// Leaderboard used at bottom of every Score page; also used with [golf-deputy-leaderboard] shortcode
global $wpdb;
global $post; ?>

<?php
$showround = $tournamentid['show_round'];
$tournamentid = $tournamentid['tournament_id'];

// shows only the current round on the "input matchup" area; still shows entire tournament for "Watch Leaderboard" or admin-area-provided shortcode
if (!empty($showround)) { ?>
	<style>#leaderboard .golfround {display: none;} #leaderboard #round<?php echo $showround ?> {display: block;}</style>
<?php }

//get tournament info
$result = gd_get_tournament_info( $query=array('tournament_id'=>$tournamentid) );

if ($result[0]->scoringsystem == 0) {
	include(dirname( __FILE__ ) . '/leaderboard-stroke.php');
} else if ($result[0]->scoringsystem == 1) {
	include(dirname( __FILE__ ) . '/leaderboard-match.php');
} else {
	include(dirname( __FILE__ ) . '/leaderboard-stableford.php');
}

?>



<script>
	// Refresh/reload the page on button click	
	function refreshpage() {
		location.reload(true);	
	}
	
</script>