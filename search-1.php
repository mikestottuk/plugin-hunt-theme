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

<div class="container-main-head container  side-collapse-container ph-layout-1">
	<a id="ph-log-social-new" href="#animatedModal" style="display:none">.</a>

<?php
if( of_get_option('ph_slider_shortcode') != '') { 
echo ph_slider();
}else{
	echo "<div class='noslider'></div>";
}

$IsSubscribed = UserSubscribed();

if( get_theme_mod( 'use_mailchimp' ) != '' && !$IsSubscribed) { 
$action = get_theme_mod( 'phmc_url' );
echo ph_mailchimp($action);
}else{
	echo "<div class='nomc'></div>";
}


 ?>

    </div>


<div class='result'></div>

<div class='container postlist'>
	<div class='maincontent'>
<?php
$mySearch = new WP_Query("s=$s & showposts=-1");
$NumResults = $mySearch->post_count;
if($NumResults == 1){
	$r = __('result','ph_theme');
}else{
	$r = __('results','ph_theme');
}


?>
		<h1 class='search-heading'>

<?php
	echo __('Results for ','ph_theme') . '<span class="search-query">'. $s . '</span><span class="nr"> ' . $NumResults . ' ' . $r . '</span></h1>';  



	global $wp_query,$post,$wpdb, $current_user,$query_string;
    wp_get_current_user();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

	$args = get_query_var('latest');

	$posttype = get_query_var('post_type');


    $day_check = '';
	if ( have_posts() ) : ?>
 			
			<?php while ( have_posts() ) : the_post();

			$num_posts++;

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
				$redclassu = 'up';
				$redscore = "unvoted";
				$c = "";
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

			if($num_posts > 10 && $paged > 1){
				$blob = 'hidepost hidepost-' . $d . '-'. $m. '-' . $y;
			}else{
				$blob = '';
			}

			if( get_theme_mod( 'pluginhunt_post_layout' ) == 'option2'){ 
				$span = 'col-md-3';
			}
			
			 ?>
			
			<div class = 'row hunt-row <?php echo $blob;?>' style = 'margin-bottom:20px'>
				<div class = "reddit-voting <?php echo $c; ?> reddit-voting-<?php echo $post->ID; ?>">
					<ul class="unstyled">

						<div class="arrow fa fa-caret-up  fa-2x arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
						<div class="score score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
		
					</ul>
				</div>	
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

								if(wp_is_mobile()){
									$mob = '-mob';
								}

							$out =  get_post_meta($post->ID, 'outbound', true);
							$n = parse_url($out);
			?>
			
			<?php if ( has_post_thumbnail() ) { //#WHFIX 24/03/2015: added data-attr to end  ?>
						<div class = 'reddit-post<?php echo $mob; ?> pull-left' id='reddit-post-<?php echo $post->ID;?>' data-ph-url="<?php  echo get_permalink( $post->ID ); ?>" data-slug='<?php echo $post->post_name; ?>' data-id='<?php echo $post->ID; ?>' data-url = '<?php echo $url; ?>' data-auth = '<?php echo $auth; ?>' data-rajax = '<?php echo $post->ID; ?>' data-rups = '<?php echo $postvote;?>' data-pname='<?php echo $pname;?>' data-profurl="<?php echo $profileUrl; ?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>">
							<?php the_post_thumbnail('small'); ?>
			<?php }else{ //#WHFIX 24/03/2015: added data-attr to end ?>
						<div class = 'reddit-post<?php echo $mob; ?> pull-left' id='reddit-post-<?php echo $post->ID;?>' data-ph-url="<?php  echo get_permalink( $post->ID ); ?>" data-slug='<?php echo $post->post_name; ?>' data-id='<?php echo $post->ID; ?>' data-url = '<?php echo $url; ?>' data-auth = '<?php echo $auth; ?>' data-rajax = '<?php echo $post->ID; ?>' data-rups = '<?php echo $postvote;?>' data-pname='<?php echo $pname;?>' data-profurl="<?php echo $profileUrl; ?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>">
			<?php } ?>
							<div class='post-meta-hunt'>
							<!-- external URL button -->
							<div class='ph-external ph-action'>
								<span class="post-item--action v-icon"><a href="<?php echo esc_url($out); ?>" target="_blank" data-reactid=".3.0.0:$1.0.2.0.$31121.0.0.3.0.0"><span data-reactid=".3.0.0:$1.0.2.0.$31121.0.0.3.0.0.0"><svg width="16" height="14" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg"><g fill="#BBB" fill-rule="evenodd"><path d="M0 3h16v2H0z"></path><rect width="16" height="2" rx="2"></rect><rect y="12" width="16" height="2" rx="2"></rect><rect width="2" height="14" rx="2"></rect><rect x="14" width="2" height="14" rx="2"></rect><path d="M9.355 6.355C9.16 6.16 9.215 6 9.49 6h3.26c.138 0 .25.115.25.25v3.26c0 .27-.152.338-.355.135l-3.29-3.29z"></path></g></svg></span></a></span>
							</div>

								<?php if(!ph_in_user_collection($post->ID)) { ?>
							<div class='ph-collect ph-action' data-pid ='<?php echo $post->ID;?>'>
							<span class="collect-button--icon" data-pid ='<?php echo $post->ID;?>'>
								<svg width="15" height="14" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg">
									<path d="M13 10V8.99c0-.54-.448-.99-1-.99-.556 0-1 .444-1 .99V10H9.99c-.54 0-.99.448-.99 1 0 .556.444 1 .99 1H11v1.01c0 .54.448.99 1 .99.556 0 1-.444 1-.99V12h1.01c.54 0 .99-.448.99-1 0-.556-.444-1-.99-1H13zM0 1c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 2 0 1.556 0 1zm0 5c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 7 0 6.556 0 6zm0 5c0-.552.453-1 .997-1h6.006c.55 0 .997.444.997 1 0 .552-.453 1-.997 1H.997C.447 12 0 11.556 0 11z" fill="#C8C0B1" fill-rule="evenodd"></path>
								</svg>
								<?php }else{ ?>
							<div class='ph-collect in ph-action' data-pid ='<?php echo $post->ID;?>'>
							<span class="collect-button--icon in" data-pid ='<?php echo $post->ID;?>'>
								<svg width="17" height="14" viewBox="0 0 17 14" xmlns="http://www.w3.org/2000/svg">
							      <path d="M11.036 10.864L9.62 9.45c-.392-.394-1.022-.39-1.413 0-.393.393-.39 1.023 0 1.414l2.122 2.12c.193.198.45.295.703.295.256 0 .51-.1.706-.295l4.246-4.246c.385-.385.39-1.02-.002-1.413-.393-.393-1.022-.39-1.412-.002l-3.537 3.538zM0 1c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h4.5c.552 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.552 0-1-.444-1-1z" fill="#DC5425" fill-rule="evenodd"></path>
							  	</svg>
							  	<?php } ?>
							</span>
							</div>

							<div class='author-ava'>
								<?php
								 if($plugina == ''){  ?>
									<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo get_the_author_meta('user_nicename'); ?>">             <?php 
                            $args = array( 'class' => 'img-rounded');
                            echo get_avatar($post->post_author, 40); ?></span>
                            <?php }else{ 
									$pluginava = get_post_meta($post->ID,'pluginavatar',true);
									?>
									<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo $plugina; ?>"><img class='img-rounded' src="<?php echo $pluginava;?>" height = "40px" width="40px"/></span>
								<?php } ?>
							</div>


							<div class='comment-icon'>
									<span><svg width="19" height="17" viewBox="0 0 19 17" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="b"><stop stop-color="#CCC" offset="0%"></stop><stop stop-color="#BABABA" offset="100%"></stop></linearGradient><filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="a"><feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset><feGaussianBlur stdDeviation=".5" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur><feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.070000001 0" in="shadowBlurOuter1" result="shadowMatrixOuter1"></feColorMatrix><feMerge><feMergeNode in="shadowMatrixOuter1"></feMergeNode><feMergeNode in="SourceGraphic"></feMergeNode></feMerge></filter></defs><path d="M1.753 18.06C.66 16.996 0 15.623 0 14.125 0 10.742 3.358 8 7.5 8c4.142 0 7.5 2.742 7.5 6.125s-3.358 6.125-7.5 6.125c-1.03 0-2.01-.17-2.904-.476-1.46.845-4.318 1.962-4.318 1.962s.955-2.298 1.475-3.676z" transform="translate(2 -7)" filter="url(#a)" stroke="url(#b)" fill="#FFF" fill-rule="evenodd"></path></svg></span>
									 <span class='hunt-comm-count'><?php $comments = get_comments('post_id=' . $post->ID); echo count($comments); ?></span>
							</div>


						</div>
						<?php if($post->post_type == 'post'){
						 if($out ==''){?>
						<a class='title title-<?php echo $post->ID;?>' href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>						 	
					 	<?php }else if(wp_is_mobile()){?>
						<a class='title title-<?php echo $post->ID;?>' href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
						<?php }else{ ?>
						<a class='title title-<?php echo $post->ID;?>' href="<?php echo esc_url($out); ?>" title="<?php the_title_attribute(); ?>" target="_blank" rel="nofollow"><?php the_title(); ?></a>
						<?php	}
						}else{ ?>
						<a class='title title-<?php echo $post->ID;?>'href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php the_title(); ?></a>
						<?php } ?>				
							 <span class='description'>
							 	<?php 
							 	if( get_theme_mod( 'pluginhunt_post_content' ) == 'option2'){ 
							 		the_content(); 
							 	}else{
									echo wp_trim_words( get_the_excerpt(), 30); 
							 	}
							 	?>
							 </span>
						</div>

				<div style="clear:both"></div>
			
			</div>
			
			<?php } 


			?>

			
			<?php endwhile; ?>
<?php else : ?>

 <div id="epic_page_end_2"><?php _e("Nothing found","ph_theme"); ?></div>


 <?php endif; ?>

 			<?php if($num_posts > 10 && $paged >1){ 
 			$more = $num_posts - 10;
 			echo "<div class='unhide show-hidden-posts'><span class='showmore' data-d=$d data-m=$m data-y=$y><i class='hp fa fa-chevron-down'></i> ";
 			$text = sprintf( _n( 'Show 1 more ' . get_theme_mod('ph_keyword_s', 'plugin'), 'Show %s more ' . get_theme_mod('ph_keyword_p', 'plugins'), $more, 'ph_theme' ), $more );
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
