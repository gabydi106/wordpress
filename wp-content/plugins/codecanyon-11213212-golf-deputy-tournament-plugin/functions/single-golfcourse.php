<?php
/**
 * The template for displaying a golf course
 */

get_header();?>

	<div id="post-<?php the_ID(); ?>" class="container-wrap content-container pagecontainer">
    	<div class="container hentry">
    
                <header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header><!-- .entry-header -->
                
                <div class="entry-content single-golfcourse">
					<?php
                    while ( have_posts() ) : the_post();
                        
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

                        // The HTML for the input page
                        ?>
                            
                        <div id="courseinfo">
                            <label><strong><?php _e( 'Address:', 'golfdeputy' ); ?></strong></label> <span><?php if (!empty($courseaddress)) {echo $courseaddress;} ?></span><br>
                            <label><strong><?php _e( 'City:', 'golfdeputy' ); ?></strong></label> <span><?php if (!empty($coursecity)) {echo $coursecity;} ?></span><br>
                            <label><strong><?php _e( 'State/Province:', 'golfdeputy' ); ?></strong></label> <span><?php if (!empty($coursestate)) {echo $coursestate;} ?></span><br>
                            <label><strong><?php _e( 'Phone:', 'golfdeputy' ); ?></strong></label> <span><?php if (!empty($coursephone)) {echo $coursephone;} ?></span><br><br>
                            
                            <div>
                                <label><strong><?php _e( 'Measurement Unit:', 'golfdeputy' ); ?></strong></label> <span><?php if (!empty($measurement)) {echo $measurement;} ?></span>
                            </div>

                            <div id="handicap-wrapper">
                                <label><strong><?php _e( 'Handicap Label:', 'golfdeputy' ); ?></strong></label> <span><?php if (!empty($handicaplabel)) {echo $handicaplabel;} ?></span>
                            </div>
                            <br>
                        </div>
                        
                        <?php 
                        // query, which returns the basic tournament info
                        if (!empty($courseid)) {
                            $result = gd_get_tournament_info( $query=array('tournament_id'=>$courseid, 'table'=>$wpdb->golf_deputy_courses, 'idtomatch'=>'course_id', 'allowedfields'=>get_course_table_columns(), 'orderby'=>'course_id', 'number'=>-1, 'order'=>'ASC') );
                        }
                        
                        if (!empty($result)) {
                            $teename = $result[0]->teename;
                            $measurement = $result[0]->measurement;
                            $handicaplabel = $result[0]->handicaplabel;
                        }
                        
                        // The HTML for the input page
                        ?>
                        <label class="teename"><strong><?php _e( 'Tee Name:', 'golfdeputy' ); ?></strong></label>&nbsp;<span><?php if (!empty($teename)) {echo $teename;} ?></span><br>
                        
                        <div id="teeinputs">
                            <?php for($j = 1; $j <= 18; $j++) { ?>
                                <?php
                                $yards = 'holeyardage' . $j;
                                $par = 'holepar' . $j;
                                $handicap = 'holehandicap' . $j;
                                if (($j == 1) || $j == 10) { ?>
                                    <div class="hole labels">
                                        <span><strong>Hole</strong></span><br>
                                        <span><?php if (!empty($result)) {echo $result[0]->measurement;} ?></span><br> 
                                        <span><?php _e( 'Par', 'golfdeputy' ); ?></span><br>
                                        <span>
                                            <?php if (!empty($handicaplabel)) { 
                                                if ($handicaplabel == "Handicap") {
                                                    echo "HCP";
                                                } else if ($handicaplabel == "Stroke Index") {
                                                    echo "SI";
                                                }
                                                else {
                                                    // set to "None"
                                                } 
                                            } ?>
                                        </span>
                                    </div>
                                <?php } ?>

                                <div class="hole">
                                    <span><strong><?php echo $j;?></strong></span><br>
                                    <span><?php if (!empty($result)) {echo $result[0]->$yards;} ?></span><br> 
                                    <span><?php if (!empty($result)) {echo $result[0]->$par;} ?></span><br>
                                    <span><?php if ($handicaplabel != "None") {echo $result[0]->$handicap;} ?></span>
                                </div>
                            <?php } ?>
                        </div>

                        <?php
                    
                    // End loop.
                    endwhile;
                    ?>
                </div> <!-- entry content -->
	</div>
</div>


<?php get_footer(); ?>


