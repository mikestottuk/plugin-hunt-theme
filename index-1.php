<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * Lets enhance this and move the epic_reddit_index($agrs) function into this file.
 */

get_header(); 


?>
<div class='ph-layout-1'>
	<div class="container-main-head container  side-collapse-container">
		<a id="ph-log-social-new" href="#animatedModal" style="display:none">.</a>
		<?php
		global $wp_query,$post,$wpdb, $current_user,$query_string;
	   	wp_get_current_user();

		if(isset($_GET["page"])){
			$paged = ($_GET["page"]) ? $_GET["page"] : 0;
		}

		pluginhunt_OutputSlider('post');
		pluginhunt_Globals();
		pluginhunt_OutputMailchimp();
		pluginhunt_StickyPosts();
		?>
		<div class='result'></div>
		<?php
		do_action('ph-popular-this');
		?>
		<div class='clear'></div>
		<div class='container postlist'>
		<div class='maincontent'>

		<?php
		
			$ph_grouping = of_get_option('ph_post_group','ph-group-day');
			pluginhunt_QueryPosts($paged, $ph_grouping);
		 if ($pageposts):
		 	global $post; 
		 	foreach ($pageposts as $post): 
		 		setup_postdata($post); 
				$num_posts++;
				pluginhunt_SetupLoop($ph_grouping);
				
				$postvote = get_post_meta($post->ID, 'epicredvote' ,true);
				wpeddit_post_ranking($post->ID);

				if($postvote == NULL){
					$postvote = 0;
				}
			
				$fid = $current_user->ID;
				pluginhunt_GetRankings($post->ID, $fid);


				if($num_posts > 10 && $paged > 1){
					$blob = 'hidepost hidepost-' . $d . '-'. $m. '-' . $y;
				}else{
					$blob = '';
				}			
			 get_template_part( 'template-parts/content' );
			 ?>
			
		</div>			
		 <?php endforeach; ?>
		 <?php else : ?>
			<div id="epic_page_end_2"><?php _e('No more','pluginhunt'); ?> <?php echo of_get_option('ph_hunt_plural');?>...</div>
		<?php endif; ?>

 			<?php if($num_posts > 10 && $paged >1){ 
 			$more = $num_posts - 10;
 			echo "<div class='unhide show-hidden-posts'><span class='showmore' data-d=$d data-m=$m data-y=$y><i class='hp fa fa-chevron-down'></i> ";
 			$text = sprintf( _n( 'Show 1 more ' . of_get_option('ph_hunt_single'), 'Show %s more ' . of_get_option('ph_hunt_plural'), $more, 'pluginhunt' ), $more );
			echo $text;
 			echo "</span></div>";
 			 } ?>
			<div class='container'>
				<?php pluginhunt_Navigation($ph_grouping); ?>				
			</div>
			<div id="results"></div>
			<div id = "error"></div>
			<div class='hide'>
				<?php wp_link_pages(); ?>
			</div>
		</div>
	</div>
			<?php wp_reset_query(); ?>
</div>



<?php get_footer(); ?>
