<?php
/**
 * The category template
 *
 */

get_header(); 
?>

<div class="container-main-head container  side-collapse-container ph-cat-1">
	<a id="ph-log-social-new" href="#animatedModal" style="display:none">.</a>

<?php
if( of_get_option('ph_slider_shortcode') != '') { 
echo ph_slider();
}else{
	echo "<div class='noslider'></div>";
}

$IsSubscribed = UserSubscribed();

if( of_get_option('mailchimp_showhidden') == 1 && !$IsSubscribed) { 
$action = of_get_option('mailchimp_action_hidden');
echo ph_mailchimp($action);
}else{
	echo "<div class='nomc'></div>";
}

if( of_get_option('ph_sticky_on') == 1) {
	#} new code for the sticky post flydown. Running from the websites featured posts, drawn randomly
	$querystr = "
		SELECT 	* 
		FROM 	$wpdb->postmeta
		WHERE 	meta_key = 'featured-product' 
		AND     meta_value = '1' ORDER BY RAND() LIMIT 1
	";

	$pageposts = $wpdb->get_results($querystr, OBJECT);
	foreach($pageposts as $ppost){
		$ID = $ppost->post_id;
		$title = get_the_title( $ID ); 
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $ID ), 'single-post-thumbnail' );
		$t = $thumb[0];
		$desc = get_post($ID)->post_content;
		$out = get_post_meta($ID,'outbound',true);
	$output = "
	<div class='ph-sticky' id='phf'>
		<span class='icon-x'><i class='fa fa-times icon-xy'></i></span>
		<div class='row hunt-row-fp'>
		<a class='title' href='$out' target='_blank' rel='nofollow'>$title</a>
		<div class='img-featured'><img class='phsi' src='$t'/></div>
		<span class='description'>$desc</span>
	</div>
	</div>"; 	
 }

 echo $output;
}


 ?>

    </div>


<div class='result'></div>

<div class='container postlist ph-layout-1'>
	<div class='maincontent'>
<?php

	global $wp_query,$post,$wpdb, $current_user,$query_string;
    wp_get_current_user();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

	$args = get_query_var('latest');

	$posttype = get_query_var('post_type');


    $day_check = '';
	if ( have_posts() ) : ?>
 			
			<?php while ( have_posts() ) : the_post();
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

			 <?php

			endwhile; ?>
<?php else : ?>

 <div id="epic_page_end_2"><?php _e("Nothing found","pluginhunt"); ?></div>


 <?php endif; ?>

 			<?php if($num_posts > 10 && $paged >1){ 
 			$more = $num_posts - 10;
 			echo "<div class='unhide show-hidden-posts'><span class='showmore' data-d=$d data-m=$m data-y=$y><i class='hp fa fa-chevron-down'></i> ";
 			$text = sprintf( _n( 'Show 1 more ' . of_get_option('ph_hunt_single'), 'Show %s more ' . of_get_option('ph_hunt_plural'), $more, 'pluginhunt' ), $more );
			echo $text;
 			echo "</span></div>";

 			 } ?>



			<div class='container'>
			<div id='navigation'>
			    <div class='next-posts-link'>
					<a href="<?php echo esc_url( home_url( '/' ) )?>?day=<?php echo $d;?>&month=<?php echo $m;?>&year=<?php echo $y;?>">next</a>
				</div>
			</div>
			</div>

			<div id="results"></div>
			<div id = "error"></div>


			<div class='hide'>
				<?php wp_link_pages(); ?>
			</div>

			

		</div>

	</div>
			
			<?php wp_reset_query(); ?>






<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

<?php get_footer(); ?>