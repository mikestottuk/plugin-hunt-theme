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

<div class="container-main-head container">


	<div class='ph-search'>
      <?php get_search_form(); ?>
    </div>

    </div>


<div class='container'>

<div class='row'>


	<div class='col-md-8 maincontent toppad'>

<?php
	global $wp_query,$post,$wpdb, $current_user,$query_string;
    wp_get_current_user();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

	$args = get_query_var('latest');

	$posttype = get_query_var('post_type');


    
	if ( have_posts() ) : ?>
 			
			<?php while ( have_posts() ) : the_post();

			$day = get_the_date('j');
			$date = get_the_date('l');
			
			$stamp = get_the_date('U');
			  if ($stamp >= strtotime("today"))
	        		$date = __("Today",'ph_theme');
	    		else if ($stamp >= strtotime("yesterday"))
	        		 $date = __('Yesterday','ph_theme');


			  if ($day != $day_check) {
			    if ($day_check != '') {
			    }
			    echo "<div class='timing'><span class='day'>". $date ."</span><span class='date'>" . get_the_date() . "</span></div>";
			    $day_check = $day;
			  }

			 ?> 
				
			<?php if(is_page()){
				
			}else{
			
			$postvote = get_post_meta($post->ID, 'epicredvote' ,true);
			wpeddit_post_ranking($post->ID);

			if($postvote == NULL){
				$postvote = 0;
			}
			
			$fid = $current_user->ID;
	
			$query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $post->ID";
			$al = $wpdb->get_var($query);
			if($al == NULL){
				$al = 0;
			}
			if($al == 1){
				$redclassu = 'upmod';
				$redclassd = 'down';
				$redscore = 'likes';
				$voted = 'yesvote';
			}elseif($al == -1){
				$redclassd = 'downmod';
				$redclassu = 'up';
				$redscore = "dislikes";
				$voted = "yesvote";
			}else{
				$redclassu = "up";
				$redclassd = "down";
				$redscore = "unvoted";
				$voted = "novote";
			}
			
			 ?>
			
			<div class = 'row hunt-row' style = 'margin-bottom:20px'>
				<div class = 'reddit-voting'>
					<ul class="unstyled">

						<div class="arrow fa fa-caret-up  fa-2x <?php echo $redclassu;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "up" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
						<div class="score <?php echo $redscore;?> score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
		
					</ul>
				</div>	


			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
			<?php if ( has_post_thumbnail() ) { ?>
			
			<?php }else{ ?>
				
			<?php } ?>
			

						<div class = 'reddit-post pull-left' id='reddit-post-<?php echo $post->ID;?>' data-rajax = '<?php echo $post->ID; ?>' data-rups = '<?php echo $postvote;?>'>
							<div class='post-meta-hunt'>
							<div class='author-ava'>
								<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo get_the_author_meta('user_nicename'); ?>">  <?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?></span>
							</div>
							<div class='comment-icon'>
									<i class="fa fa-comment"></i> <span='hunt-comm-count'><?php $comments = get_comments('post_id=' . $post->ID); echo count($comments); ?></span>
							</div>
						</div>
						<?php if($post->post_type == 'post'){
							$out =  get_post_meta($post->ID, 'outbound', true);
							$n = parse_url($out);
						 ?>
						<a class='title' href="<?php echo esc_url($out); ?>" title="<?php the_title_attribute(); ?>" target="_blank" rel="nofollow"><?php the_title(); ?></a>
						<?php }else{ ?>
						<a class='title'href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php the_title(); ?></a>
						<?php } ?>				
							 <span class='description-single'><?php the_content(); ?></span>
						</div>

	


			
				<div style="clear:both"></div>
			
				<div class = 'span8'>
					<?php comments_template(); ?>
				</div>
			
			</div>
			
			<?php } ?>
			
			<?php endwhile; ?>

			<?php else: ?> 
				<p><?php _e('Sorry, no posts matched your criteria.', 'ph_theme'); ?></p> 
			<?php endif; ?>
	
		    <div class='next-posts-link'>
            <?php echo get_next_posts_link('More Posts','ph_theme'); ?>
			</div>
			
			<?php wp_reset_query(); ?>


<?php get_footer(); ?>
