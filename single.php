<?php
get_header(); 

	global $wp_query,$post,$wpdb, $current_user,$query_string;
    wp_get_current_user();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';
    
			
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
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
			$out =  get_post_meta($post->ID, 'outbound', true);
			$n = parse_url($out);

			if($image[0] == ''){
				$image[0] = get_post_meta($post->ID,'phfeaturedimage',true);
			}

			if($image[0] == ''){
				//fallback image (for video mode)...
				$image[0] = get_post_meta($post->ID,'ph_fallback',true);
			}

    global $sub_menu_ph62;
    if($sub_menu_ph62){
        $pheaderclass = 'extra-margin-top';
    }else{
        $pheaderclass = 'no-extra-margin';
    }

			?>

<div class="ph-user-message">
	<i class="fa fa-bell faa-ring animated"></i> 
	<span class='ph-user-message-text'></span>
	<span class='ph-user-close'>x</span>
</div>

<?php 
if (  post_password_required() ) {
?>
<div class='password-protected'>
	<div id='phsf' class='password-protected'>
		<?php the_content(); ?>
	</div>
</div>
<?php
}else{ ?> 
<div class='single-post-ph-wrapper'>
<div id='phsf'>
	<div class='row <?php echo $pheaderclass;?>'>
			 <div class='post-header' style="background-image: url('<?php echo $image[0]; ?>')">
			 	<div class='post-header-shadow'>
				 	<div class='container-title'>
				 		<span class='post-title-single'><a class='title-link title-link-html-<?php echo $post->ID;?>' href="<?php echo esc_url($out); ?>" title="<?php the_title_attribute(); ?>" target="_blank" rel="nofollow"><?php the_title(); ?></a></span>
				 		<div class='post-description-short'><?php // echo wp_trim_words( $post->post_content , 35, '...' ); ?></div>
				 		
				 		<div class='reddit-wrapper'>
						 	<div class = 'reddit-voting <?php echo $c; ?> reddit-voting-<?php echo $post->ID; ?>'>
								<ul class="unstyled">
									<div class="arrow fa fa-caret-up  fa-2x <?php echo $c;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "up" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
									<div class="score score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
								</ul>
							</div>	
							<a class='get-it' href="<?php echo esc_url($out); ?>" title="<?php the_title_attribute(); ?>" target="_blank" rel="nofollow"><?php echo of_get_option('ph_get_it', 'Get it'); ?></a>
				 		</div>
				 		<div class="post-detail--header--buttons">
				 			<?php
							$categories = get_the_category($post->ID);
							foreach($categories as $category) {
								$cat_id = $category->cat_ID;
								$cat_name = $category->cat_name;
								if ($cat_id != 1) {
									echo '<a class="post-detail--header--button" href="' . esc_url( get_category_link( $category->term_id ) ) . '">'.$cat_name.'</a>';
								}
							}
				 			?>				 			
				 			<a class="ph-collect-single post-detail--header--button" href="#" title="Save to your collections" data-pid ='<?php echo $post->ID;?>'>
				 				<span>
				 					<span><svg class='single-collect' width="15" height="14" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg"><path d="M13 10V8.99c0-.54-.448-.99-1-.99-.556 0-1 .444-1 .99V10H9.99c-.54 0-.99.448-.99 1 0 .556.444 1 .99 1H11v1.01c0 .54.448.99 1 .99.556 0 1-.444 1-.99V12h1.01c.54 0 .99-.448.99-1 0-.556-.444-1-.99-1H13zM0 1c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 2 0 1.556 0 1zm0 5c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 7 0 6.556 0 6zm0 5c0-.552.453-1 .997-1h6.006c.55 0 .997.444.997 1 0 .552-.453 1-.997 1H.997C.447 12 0 11.556 0 11z" fill="#C8C0B1" fill-rule="evenodd"></path></svg></span>
				 					<span class='save-collect'><?php _e('save','pluginhunt');?></span></span></a></div>


                    <div class="post-hunted-by col-md-4">
                        <div class="aside-hunt">
                            <section class="post-detail--footer--meta">
                                <a class="user-image post-detail--footer--meta--user-image" href="#">
                                    <span class="user-image">
                                        <div class="user-image--badge v-hunter">H</div>
                                        <?php 
                                        global $post;  
                                        $aid=$post->post_author;
                                        $pluginava = ph_avatar($post->post_author);
                                        $pname = get_the_author_meta('user_nicename',$post->post_author);
                                        $auth = 'yes';
                                        $profileUrl = get_author_posts_url($post->post_author); 
                                        $args = array( 'class' => 'img-rounded');
                                        ?>
                                      <div class="who-by profile-drop">
                                        <a class="drop-theme-arrows-bounce-dark" href="<?php echo $profileUrl; ?>"><?php echo get_avatar($aid, 40); ?></a>
                                         <div class="ph-content pop-ava">
               								<?php echo get_avatar($aid, 40); ?>
                              
                                        </div>
                                      </div>
                                    </span>
                                </a>
                                <a class="post-detail--footer--meta--time" href="<?php echo get_post_permalink($post->ID); ?>">
                                    <span><?php _e('Posted','pluginhunt');?></span>
                                    <span> </span>
                                    <time><?php printf( _x( '%s ago', '%s = human-readable time difference', 'pluginhunt' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></time>
                                </a>

						      <span class='ph_em ph_ft hide'>
						          <div class="who-by-e flag-drop votes-inner">
						            <span class="drop-target drop-theme-arrows-bounce">        
						            <i class="fa fa-flag"></i>
						            </span>

						            <div class="ph-content pop-ava-v">
						                <div class='user-info-e ph-flag'>
						                  <div class='user-desc-em'>
						                      <span class='ph_title_em'><?php _e("Flag","pluginhunt");?> </span><span class='ph_red' id ="ph_red_title_flag"><?php echo $post->post_title; ?></span><span class='ph_title_em'></span>
						                  </div>
						        
						                  <textarea name="flag" id="body-flag-ph" class="textarea tooltip-field tooltip-textarea textarea-flag" placeholder="<?php _e('Why should this be removed ?','pluginhunt'); ?>"></textarea><br>
						                  <span class='ph-flag-done'><strong><?php _e("Thank you ", "pluginhunt"); ?></strong><?php _e(" we have received your feedback", "pluginhunt"); ?></span>
						                  <div class='view-profile flag-post-ph'>
						                    <button class='btn btn-cancel primary ph_vp ph_cancel'><?php _e("Cancel","pluginhunt"); ?></button>
						                    <button class='btn btn-success primary ph_vp ph_vp_flag' data-perma ="<?php echo $perma; ?>" data-id ="<?php echo $phid->ID; ?>"><?php _e("Send","pluginhunt"); ?></button>
						                  </div>

						           
						              </div>
						            </div>
						          </div>
						      </span>


                            </section>
                        </div>
                    </div>
                </div>


				 	</div>
			 	</div>



			 </div>

		<div class = "row post-detail">
			 <div class='full-width-ph'>
			 	<section class="col-md-8 ph-lhs">


					
					<div class='section section-media'>

						<div class='ph-media-items'>
						<h3 class='h3'><?php _e('media','pluginhunt'); ?></h3>
						<div class="carousel--controls">
					    <?php
					    $media = get_post_meta($post->ID,'phmedia',true);

					     if(current_user_can( 'upload_files' ) && $media !='' && $current_user->ID  == $post->post_author && !wp_is_mobile()){ ?> 
							<a class="carousel--controls--button v-add" title="Add content" data-pid="<?php echo $post->ID; ?>">
								<span>
									<svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
										<path d="M5 5V1.002C5 .456 5.448 0 6 0c.556 0 1 .45 1 1.002V5h3.998C11.544 5 12 5.448 12 6c0 .556-.45 1-1.002 1H7v3.998C7 11.544 6.552 12 6 12c-.556 0-1-.45-1-1.002V7H1.002C.456 7 0 6.552 0 6c0-.556.45-1 1.002-1H5z" fill="#948B88" fill-rule="evenodd"></path>
									</svg>
								</span>
							</a>

						<?php
							echo outputmedia_pop();
						?>


					    <?php } ?>

					    <?php if($media !== ''){ ?>
							<a class="carousel--controls--button v-prev m-disabled">
								<span>
									<svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
										<path d="M.256 5.498c0-.255.098-.51.292-.703L4.795.548C5.18.163 5.817.16 6.207.55c.393.393.392 1.023.002 1.412L2.67 5.5l3.54 3.538c.384.384.388 1.02-.003 1.412-.393.393-1.023.39-1.412.002L.548 6.205c-.192-.192-.29-.447-.29-.702z" fill="#948B88" fill-rule="evenodd"></path>
									</svg>
								</span>
							</a>
							<a class="carousel--controls--button v-next">
								<span>
									<svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
										<path d="M6.744 5.502c0 .255-.098.51-.292.703l-4.247 4.247c-.385.385-1.022.388-1.412-.002C.4 10.057.4 9.427.79 9.038L4.33 5.5.79 1.962C.407 1.578.403.942.794.55 1.186.157 1.816.16 2.204.548l4.248 4.247c.192.192.29.447.29.702z" fill="#948B88" fill-rule="evenodd"></path>
									</svg>
								</span>
							</a>
						</div>
						<?php
						$media_array = json_decode($media);
						if($media_array !=''){
							echo "<div class='ph-slick'>";
							foreach($media_array as $m){
								if($m->source == 'yt'){
									echo "<div><a href='$m->url' class='phlb' data-yturl='$m->url' data-ytid='$m->id'><img data-yturl='$m->url' data-ytid='$m->id' src='http://img.youtube.com/vi/$m->id/0.jpg' height='210px'/></a></div>";
								}else{
									echo "<div><a href='$m->url' class='phlb'><img src='$m->url' height='210px'/></a></div>";
								}
							}
							echo "</div>";
						}?>
						<?php } ?>
					</div>
					<?php if(current_user_can( 'upload_files' ) && $current_user->ID  == $post->post_author ){ ?>
					<?php if($media==''){ ?>
						<div class='pm'>
							<div class="post-detail placeholder media-placeholder"><span><?php _e('No media yet. Be the first one to','pluginhunt'); ?>&nbsp;</span><a class='postmedia' data-pid='<?php echo $post->ID;?>' href="#"><?php _e('add media on this product','pluginhunt'); ?></a><?php echo outputmedia_pop_sin();  ?><span>.</span></div>
						
						</div>
						<?php } ?>

					<?php }else{  ?>
						<div class='pm'>
							<div class="post-detail placeholder media-placeholder"><span><?php _e('No media yet.','pluginhunt'); ?>&nbsp;</span> </div>
						
						</div>

					<?php } ?>
					</div>

					<?php
						$pod = get_post_meta($post->ID,'podcast_link',true);
						if($pod != ''){
					?>
					<div class='section podcast'>
					<h3 class='h3'><?php _e('Listen','pluginhunt');?></h3>
						<?php
							echo do_shortcode('[audio src="'.$pod.'"]');
						?>
					</div>
					<?php
						}
					?>


					<?php if( of_get_option('ph_detail_on') == 1){ ?>
					<div class='section section-details'>
						<h3 class='h3'><?php _e('details','pluginhunt');?></h3>
						<?php echo apply_filters('the_content',$post->post_content); ?>
					</div>
					<?php } ?>

			

					<div class='section section-discussion'>

					<?php if(current_user_can( 'subscriber' )){  }else if(is_user_logged_in()){ ?>
						<h3 class='h3'><?php _e('discussion','pluginhunt');?></h3>

	                <div class="<?php echo $c;?> post-detail--footer-3">
	                    <main class="">
	                    	
	                        <div class="post-detail--footer--comments-form-toggle">
	                        	<form action="<?php echo home_url(); ?>/wp-comments-post.php" method="post" id="phcommentform">
	                            
	                        

	                            <textarea class="post-detail--footer--comments-form-toggle--link comment-post" rows="1" name="comment" id="comment"></textarea>
	                        	
	                        	<div class='comment-actions'><span class='comment-cancel'><?php _e('Cancel','pluginhunt'); ?></span> <span class='comment-submit'><?php _e('Submit','pluginhunt'); ?></span></div>
	                        	<input type="hidden" name="comment_post_ID" value="<?php echo $post->ID;?>" id="comment_post_ID">
	                        	<input type="hidden" name="comment_parent" id="comment_parent" value="0">
	                        	</form>
	                        </div>
	                      
	                    </main>
	                </div>	
					
					<?php } ?>

					<?php comments_template(); ?> 
		

					<?php if(!is_user_logged_in()){ ?>
					<div class='section can-comment'>
						<p class="post-detail placeholder"><span><?php _e('Commenting is limited to those invited by others in the community','pluginhunt');?></span><br/><a class='ph-login-link' href="#"><?php _e(' Login to continue','pluginhunt'); ?></a><span> <?php _e('or','pluginhunt'); ?> <a href="<?php echo esc_url(of_get_option('ph_faq')); ?>"><?php _e('learn more','pluginhunt'); ?></a>.</span></p>
					</div>
					<?php }

					if(current_user_can( 'subscriber' )){  ?>
					<div class='section can-comment'>
						<p class="post-detail placeholder"><span><?php _e('Commenting is limited to those invited by others in the community','pluginhunt');?></span><br/>
							<?php 
							$cid = get_current_user_id();
							$access = get_user_meta($cid, 'plugin_access_request',true);
							if($access == ''){ ?>
							<span class='ph-request-msg'>
							<a class='ph-request-access' data-uid ='<?php echo $cid; ?>' href="#"><?php _e('request access','pluginhunt'); ?></a>
						</span>
						<?php }else{ ?>
						<span><?php _e('You have been added to the waiting list.','pluginhunt');?><span class='emo'>&#x1f483;</span></span>
						<?php } ?>
							<br/><span><?php _e('Questions? check out our','pluginhunt'); ?><a href='<?php echo esc_url(get_theme_mod('phfaq')); ?>'> <?php _e('FAQ','pluginhunt');?></a>.</span></p>
					</div>						
				<?php } ?>

				</div>

					<div class='lhs-bottompad'></div>
                  
				</section>
				<?php 
				if(wp_is_mobile()){
					$c ='-mobile';
				}; ?>

				<div class='col-md-4 aside'>
					<div class='section sharer'>
						<h3 class='h3'><?php _e('share','pluginhunt'); ?></h3>
						<ul class='post-share'>
							<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="facebook"><li class='fb ph-s'><i class="fa fa-facebook"></i></li></a>
							<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="twitter"><li class='tw ph-s'><i class="fa fa-twitter"></i></li></a>
							<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="google"><li class='gp ph-s'><i class="fa fa-google-plus"></i></li></a>
							<li class='em ph-s ph-s-em' data-subject='<?php the_title(); ?>'' data-link=<?php echo get_permalink($post->ID); ?>'><i class="fa fa-envelope"></i></li>
						</ul>
					</div>

					<div style="clear:both"></div>

					<div class='section' style='display:none;'>
						<h3 class='h3'>0 makers</h3>
						<p class="post-detail placeholder v-white">
							<span>No maker yet.</span><br>
							<span>Be the first to&nbsp;</span>
							<a data-popover="click" data-popover-href="/posts/28453/maker_suggestions/new" href="#">suggest a maker</a>
						</p>
					</div>
                 
					<div class='section section-upv'>

					<?php
					$wpdb->epic   = $wpdb->prefix . 'epicred';
					$query = $wpdb->prepare("SELECT epicred_ip FROM $wpdb->epic WHERE epicred_id = %d", $post->ID);
					$upvotes = $wpdb->get_results($query);
					$u = count($upvotes);
					if($u == 0){
					  $uc = 'hide';
					}else{
						$uc = '';
					}
					?>
					  
					  <div class="title upvotes upvotes-modal <?php echo $uc; ?>">
					  	<h3 class='h3'><?php echo $u; ?> <?php _e("Upvotes",'pluginhunt'); ?></h3>
					  </div>

					  <div data-user-carousel="true" class="user-votes">
					  <?php
					  $ui = 0;
					  foreach($upvotes as $upvote){

					    $ava = ph_avatar($upvote->epicred_ip);
					    $href = get_author_posts_url( $upvote->epicred_ip );
					    $upv = get_userdata( $upvote->epicred_ip );
					    $args = array( 'class' => 'img-rounded');
        
					  ?>
					  <div class="who-by-v example votes-inner">
					    <a class="drop-target drop-theme-arrows-bounce" href="<?php echo $href; ?>"><?php echo get_avatar($upvote->epicred_ip, 40, $args); ?></a>
					     <div class="ph-content pop-ava-v">
					  		<?php echo get_avatar($upvote->epicred_ip, 40, $args); ?>
					        <div class='user-info'>
					          <span class='user-name'><?php echo $upv->display_name; ?></span>
					          <div class='user-desc'>
					              <?php echo $upv->description; ?>
					          </div>
					          <div class='view-profile'>
					            <div class='btn btn-success primary ph_vp'><a class='vp' href='<?php echo $href;?>'><?php _e("View Profile","pluginhunt"); ?></a></div>
					          </div>
					        </div>
					    </div>
					  </div>

					  <?php
					  $ui++;
					   }
					   ?>
					  </div>

					  <div style="clear:both"></div>

					</div>
					<?php
					$posttags = get_the_tags();
					if($posttags){ ?>
					<div class='section section-tags'>
						<h3 class='h3'><?php _e('tags','tags'); ?></h3>
						<?php the_tags( '<span class="phtag">', '</span>', '</span>' ); ?>
					</div>
					<?php } ?>

					<div class='section section-sim'>
						<h3 class='h3'><?php _e('related products','pluginhunt'); ?></h3>
						<p class="post-detail">
							<?php // similar products run from tags taxonomies 
						    $orig_post = $post;
						    $tags = wp_get_post_tags($post->ID);
						    if ($tags) {
						    $tag_ids = array();
						    foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
						    $args=array(
						    'tag__in' => $tag_ids,
						    'post__not_in' => array($post->ID),
						    'posts_per_page'=>4, // Number of related posts to display.
						    'ignore_sticky_posts'=>1
						    );
						     
						    $my_query = new wp_query( $args );
						 
						    while( $my_query->have_posts() ) {
						    $my_query->the_post();
						    ?>
						     
						    <div class="relatedthumb similar-product">
						        <a rel="external" href="<?php the_permalink()?>">
						          <?php the_post_thumbnail('thumbnail'); ?>
						            <div class='desc-wrap'>
							          <div class='heading'><?php the_title(); ?></div>
							          <div class='description'><?php echo wp_trim_words($post->post_content,10); ?></div>
						      	    </div>
						        </a>
						    </div>

	
						    <?php } 
						    }else{ ?>
						    <p class="post-detail placeholder v-white">
						    <?php
						    	echo "<span>";
						    	_e('No related products yet.','pluginhunt');
						    	echo "&nbsp;</span>";
						    }
						    $post = $orig_post;
						    wp_reset_query();
						    ?>
						</p>
					</div>
				</div>
			 </div>
		</div>

			<div class='row'>
                    <!-- aside footer for single page -->


    </div>
</div> <!-- ph-sing-flash -->

</div>
<?php } ?>		
<?php get_footer('new'); ?>