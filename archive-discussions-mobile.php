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

get_header('mobile'); 


?>

<div class='ph-layout-2'>
	<div class='post-wrapper container-fluid'>

	<div class='col-md-12 mob-pad'>


	<a id="ph-log-social-new" href="#animatedModal" style="display:none">.</a>
		<div class='options discuss-switch'><span class='active popular d-s'><?php _e('popular','pluginhunt'); ?></span><span class='newest d-s hide'><?php _e('newest','pluginhunt'); ?></span></div>
		<div class='maincontent mc-d'>

<?php
	global $wp_query,$post,$wpdb, $current_user,$query_string;
    wp_get_current_user();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

	#} check that our paged variable is being passed
?>

<table class='table'>
	<thead class='discuss-header'>
		<th class='thirty'><?php _e('Vote','pluginhunt'); ?></th>
		<th class='thirty'><?php _e('Topic', 'pluginhunt'); ?></th>
		<th class= 'thirty'><?php _e('Category', 'pluginhunt');?></th>
	</thead>
	<tbody class='discuss-body'>
<?php
			// Start the loop.
			while ( have_posts() ) : the_post();
 			setup_postdata($post); 

			$day = get_the_date('j');
			$date = get_the_date('l');
			

			 ?> 
				
			<?php 
			
			$postvote = get_post_meta($post->ID, 'epicredvote' ,true);
			wpeddit_post_ranking($post->ID);

			if($postvote == NULL){
				$postvote = 0;
			}
			
			$fid = $current_user->ID;
	
			$query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $post->ID";
			$al = $wpdb->get_var($query);
			$c = "none";
			
			if($al == NULL){
				$al = 0;
				$redclassu = 'up';
				$redscore = "unvoted";
				$c = "none";
			}else if($al==1){
				$redclassu = 'upmod';
				$redclassd = 'down';
				$redscore = 'likes';
				$voted = 'yesvote';
				$c = 'blue';
			}else{
				$redclassu = 'upmod';
				$redclassd = 'down';
				$redscore = 'likes';
			}
		
			 ?>
			
			<tr>
				<td class='meta-vote thirty'><?php echo ph_output_voting($post->ID); ?></td> <!-- voting -->
				<td class='thirty'>
					<div class = 'row hunt-row hr-d'>
		
				<?php
								$plugina = get_post_meta($post->ID,'pluginauthor', true);
								if($plugina ==''){
									$pname = get_the_author_meta('user_nicename');
									$auth = 'yes';
								}else{
									$pname = $plugina;
									$auth = 'no';
								}
                    			$profileUrl = '#'; if (isset($post->ID)) $profileUrl = get_author_posts_url($post->post_author); 
								$url = home_url();

							$out =  get_post_meta($post->ID, 'outbound', true);
							$n = parse_url($out);
			?>
						   <div class = 'reddit-post pull-left' id='reddit-post-<?php echo $post->ID;?>' data-ph-url="<?php  echo get_permalink( $post->ID ); ?>" data-slug='<?php echo $post->post_name; ?>' data-id='<?php echo $post->ID; ?>' data-url = '<?php echo $url; ?>' data-auth = '<?php echo $auth; ?>' data-rajax = '<?php echo $post->ID; ?>' data-rups = '<?php echo $postvote;?>' data-pname='<?php echo $pname;?>' data-profurl="<?php echo $profileUrl; ?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>">			
												

			<div class='ph-content-detail'>
						<div class='author-ava hide'>
								<?php
								 if($plugina == ''){  ?>
									<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo get_the_author_meta('user_nicename'); ?>">             <?php 
                            		$args = array( 'class' => 'img-rounded');
                            		echo get_avatar($post->post_author, 40, $args); ?></span>
								<?php }else{ 
									$pluginava = get_post_meta($post->ID,'pluginavatar',true);
									?>
									<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo $plugina; ?>"><img class='img-rounded' src="<?php echo $pluginava;?>" height = "40px" width="40px"/></span>
								<?php } ?>
						</div>
						<?php
						//trim the title to 37 characters...
						$title = get_the_title($post->ID);
						if(strlen($title)>37){
						// $title = substr( $title, 0, 37) . "...";
						} ?>
						<?php // if ( wp_is_mobile() ) { echo "<a href='". get_permalink( $post->ID ) ."'>";  }  ?>
						<div class='post-info'>
						<span class='title'><?php echo $title; ?></span>	
						<?php // if ( wp_is_mobile() ) {  echo '</a>'; }  ?>		
				</div>  <!-- post info -->
			</div> <!-- ph content detail -->
		</div> <!-- reddit-post -->
	</div> <!-- hunt-row -->

	</td>
	<td class='meta thirty'><?php $terms = wp_get_post_terms($post->ID, 'discussion_category');
		foreach($terms as $term){
			echo "<div class='dt'><div class='dtl' style='background:" . phtoColor($term->term_id) . ";'></div><div class='dtn'>" . $term->name . "</div></div>";
		}
	 ?></td>  <!-- category -->
</tr>
			
			
 <?php endwhile; ?>


		</div>

	</div>

</tbody>
</table>




</div> <!-- end of col-md-8 -->

<?php
if (function_exists("ph_wp_bs_pagination"))
    {
         //wp_bs_pagination($the_query->max_num_pages);
         ph_wp_bs_pagination();
}
?>
			
			<?php wp_reset_query(); ?>



</div>


<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

<?php get_footer('mobile'); ?>
