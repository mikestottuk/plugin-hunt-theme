<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); 


	global $wp_query,$post,$wpdb, $current_user,$query_string, $product;;
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

?>

<div id='phsf'>
	<div class='row'>
			 <div class='post-header' style="background-image: url('<?php echo $image[0]; ?>')">
			 	<div class='post-header-shadow post-header-shadow-shop'>
				 	<div class='container-title'>
				 		<span class='post-title-single'><a class='title-link title-link-html-<?php echo $post->ID;?>' href="<?php echo esc_url($out); ?>" title="<?php the_title_attribute(); ?>" target="_blank" rel="nofollow"><?php the_title(); ?></a></span>
				 		<div class='post-description-short'><?php echo wp_trim_words( $post->post_content , 35, '...' ); ?></div>
				 		
				 		<div class='reddit-wrapper'>
						 	<div class = 'reddit-voting <?php echo $c; ?> reddit-voting-<?php echo $post->ID; ?>'>
								<ul class="unstyled">
									<div class="arrow fa fa-caret-up  fa-2x <?php echo $c;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "up" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
									<div class="score score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
								</ul>
							</div>	
									<?php 
									$sc = "[add_to_cart id=" .$post->ID ."]";
									echo do_shortcode($sc ); ?>
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
				 	</div>
			 	</div>
			 </div>


		<div class = "row post-detail">
			 <div class='full-width-ph'>
			 	<section class="col-md-7 ph-lhs">
					
					<div class='section section-media'>
						<?php
						$media = get_post_meta($post->ID,'phmedia',true);
						if($media == ''){
							$phs_c = 'hide';
						}else{
							$phs_c = '';
						}
						?>
						<div class='ph-media-items'>
						<h3 class='h3'><?php _e('media','pluginhunt'); ?></h3>
						<div class="carousel--controls <?php echo $phs_c; ?>">
					    <?php

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

					    <?php if(!wp_is_mobile()){ ?>
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

					<?php } ?>
					</div>
					<?php if( of_get_option('ph_detail_on') == 1){ ?>
					<div class='section section-details'>
						<h3 class='h3'><?php _e('details','pluginhunt');?></h3>
						<p class='details-content'>
						<?php echo $post->post_content; ?>
						</p>
					</div>
					<?php } ?>

					<?php 
					//woocommerce reviews / tabs
					$tabs = apply_filters( 'woocommerce_product_tabs', array() );

					if ( ! empty( $tabs ) ) : ?>
					<div class='woocommerce'>
						<div class="woocommerce-tabs wc-tabs-wrapper">
							<ul class="tabs wc-tabs">
								<?php foreach ( $tabs as $key => $tab ) : ?>
									<li class="<?php echo esc_attr( $key ); ?>_tab">
										<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
							<?php foreach ( $tabs as $key => $tab ) : ?>
								<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>">
									<?php call_user_func( $tab['callback'], $key, $tab ); ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>


					
	

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
							$access = get_user_meta($cid, 'ph_access_request',true);
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

					<div class='lhs-bottompad'></div>
                  
				</section>
				<?php 
				if(wp_is_mobile()){
					$c ='-mobile';
				}; ?>

				<div class='col-md-5 aside'>
					<div class='section sharer'>
						<h3 class='h3'><?php _e('share','pluginhunt'); ?></h3>
						<ul class='post-share'>
							<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="facebook"><li class='fb ph-s'><i class="fa fa-facebook"></i></li></a>
							<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="twitter"><li class='tw ph-s'><i class="fa fa-twitter"></i></li></a>
							<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="google"><li class='gp ph-s'><i class="fa fa-google-plus"></i></li></a>
							<li class='em ph-s'><i class="fa fa-envelope"></i></li>
						</ul>
					</div>

					<div style="clear:both"></div>

<!--  MAKER UX IN V3.7 - see epicplugins.com for more info 
					<div class='section'>
						<h3 class='h3'>0 makers</h3>
						<p class="post-detail placeholder v-white">
							<span>No maker yet.</span><br>
							<span>Be the first to&nbsp;</span>
							<a data-popover="click" data-popover-href="/posts/28453/maker_suggestions/new" href="#">suggest a maker</a>
						</p>
					</div>
-->
					<div class='section section-upv'>

					<?php
					$wpdb->epic   = $wpdb->prefix . 'epicred';
					$query = $wpdb->prepare("SELECT epicred_ip FROM $wpdb->epic WHERE epicred_id = %d", $post->ID);
					$upvotes = $wpdb->get_results($query);
					$u = count($upvotes);
					if($u == 0){
					  $uc = 'hide';
					}
					?>
					  
					  <div class="title upvotes upvotes-modal <?php echo $uc; ?>">
					  	<h3 class='h3'><?php echo $uc; ?><?php _e("Upvotes",'pluginhunt'); ?></h3>
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
					    <a class="drop-target drop-theme-arrows-bounce"><?php echo get_avatar($upvote->epicred_ip, 40, $args); ?></a>
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

					<div class='section section-tags'>
						<h3 class='h3'><?php _e('tags','tags'); ?></h3>
						<?php the_tags( '<span class="phtag">', '</span>', '</span>' ); ?>
					</div>

					<div class='section buy section-buy'>
						<h3 class='h3'><?php _e('buy','pluginhunt'); ?></h3>
								<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>
					</div>

		
				</div>
			 </div>
		</div>



		</div>



</div>

<?php get_footer( 'shop' ); ?>
