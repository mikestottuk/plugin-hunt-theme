<?php
get_header("mobile"); 

	global $wp_query,$post,$wpdb, $current_user,$query_string,$post;
    wp_get_current_user();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';
    
	$cid = get_current_user_id();

    $collected = get_post_meta($post->ID, 'ph_collected_posts' ,true);
	$c_array = explode(",",$collected);
	$col = ltrim ($collected, ',');
	if($cid != $post->post_author){
		$no = '-no hide';
	}
	$author = get_user_by( 'id', $post->post_author );
	$email = $author->user_email; 
	$site = $author->user_url;
	$size = 30;

?>


<div class="ph-user-message">
	<i class="fa fa-bell faa-ring animated"></i> 
	<span class='ph-user-message-text'></span>
	<span class='ph-user-close'>x</span>
</div>

<div id='phsf' class='col-mob-pad'>
	<div class='row'>

<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
<?php
    $author_id = $post->post_author;
    if($cid == $author_id){ ?>
<div class="collection-detail" ><header class="collection-detail--header v-brown" style="background-image: url(<?php echo $image[0]; ?>);">
<div class="collection-detail--header--background-uploader" data-pid="<?php echo $post->ID; ?>"><span class="collection-detail--header--background-uploader--upload<?php echo $no; ?>"><svg width="27" height="21" viewBox="0 0 27 21" xmlns="http://www.w3.org/2000/svg"><path d="M12.495 2.687c-.53 0-.932-.25-1.184-.926-.224-.61-.47-1.185-1.286-1.185h-4.2c-.65 0-1.06.526-1.27 1.188-.194.617-.64.927-1.167.927h-.65C1.223 2.686 0 3.93 0 5.46v12.257c0 1.532 1.224 2.774 2.735 2.774h21.53c1.51 0 2.735-1.24 2.735-2.77V5.46c0-1.53-1.224-2.773-2.735-2.773h-11.77zm.697 15.46c-3.567 0-6.456-2.883-6.456-6.443 0-3.56 2.89-6.444 6.456-6.444 3.566 0 6.457 2.885 6.457 6.444 0 3.56-2.894 6.444-6.46 6.444zm9.844-10.544c-.65 0-1.174-.525-1.174-1.17 0-.65.525-1.173 1.174-1.173.65 0 1.174.524 1.174 1.172 0 .646-.525 1.17-1.174 1.17zm-5.735 4.1c0 2.265-1.84 4.1-4.106 4.1s-4.11-1.835-4.11-4.1c0-2.265 1.842-4.1 4.11-4.1 2.27 0 4.11 1.835 4.11 4.1" fill="#FFF" fill-rule="evenodd"></path></svg></span><input accept="image/gif, image/jpeg, image/png" name="collection_background_image" type="file"></div>
<div class="collection-detail--header--shadow">
	<div class="collection-detail--header--heading">
	<h1>
		<span>
			<span class='collection-title' id='collection-title'><?php the_title(); ?></span>
			<input class='hide edit-title-input' id='edit-title' maxlength="80" name="etitle" type="text" value="<?php the_title(); ?>" onkeypress="return editTitle(event)" data-cid=<?php echo $post->ID;?>/>
			<span class="editable-text--button<?php echo $no; ?> edit-title" data-cid=<?php echo $post->ID;?>>
				<svg width="19" height="19" viewBox="0 0 19 19" xmlns="http://www.w3.org/2000/svg">
					<g fill="#B5B5B5" fill-rule="evenodd">
						<path d="M15.6111111,8.40952381 L15.6111111,15.0761905 C15.6111111,15.7800481 15.037191,16.3539683 14.3333333,16.3539683 L3.66666667,16.3539683 C2.9625373,16.3539683 2.38888889,15.7802208 2.38888889,15.0761905 L2.38888889,4.40952381 C2.38888889,3.70522174 2.9623646,3.13174603 3.66666667,3.13174603 L10.3333333,3.13174603 C10.854809,3.13174603 11.2777778,2.7087773 11.2777778,2.18730159 C11.2777778,1.66545755 10.8548853,1.24285714 10.3333333,1.24285714 L3.66666667,1.24285714 C1.9176354,1.24285714 0.5,2.66049255 0.5,4.40952381 L0.5,15.0761905 C0.5,16.8252217 1.9176354,18.2428571 3.66666667,18.2428571 L14.3333333,18.2428571 C16.0820608,18.2428571 17.5,16.8250811 17.5,15.0761905 L17.5,8.40952381 C17.5,7.88767977 17.0771075,7.46507937 16.5555556,7.46507937 C16.0336354,7.46507937 15.6111111,7.88760366 15.6111111,8.40952381 L15.6111111,8.40952381 Z M16.6111111,8.40952381 C16.6111111,8.43988841 16.5859202,8.46507937 16.5555556,8.46507937 C16.5249774,8.46507937 16.5,8.44011923 16.5,8.40952381 L16.5,15.0761905 C16.5,16.2727706 15.5298018,17.2428571 14.3333333,17.2428571 L3.66666667,17.2428571 C2.46992015,17.2428571 1.5,16.272937 1.5,15.0761905 L1.5,4.40952381 C1.5,3.2127773 2.46992015,2.24285714 3.66666667,2.24285714 L10.3333333,2.24285714 C10.3027552,2.24285714 10.2777778,2.21789701 10.2777778,2.18730159 C10.2777778,2.15649255 10.3025243,2.13174603 10.3333333,2.13174603 L3.66666667,2.13174603 C2.41007985,2.13174603 1.38888889,3.15293699 1.38888889,4.40952381 L1.38888889,15.0761905 C1.38888889,16.332467 2.41021392,17.3539683 3.66666667,17.3539683 L14.3333333,17.3539683 C15.5894757,17.3539683 16.6111111,16.3323329 16.6111111,15.0761905 L16.6111111,8.40952381 L16.6111111,8.40952381 Z"></path>
						<path d="M16.70122,0 C16.3097077,0 15.9188703,0.149175518 15.6205109,0.447189053 L9.22854018,6.8393175 L9.22854018,9 L11.3892835,9 L17.7812542,2.60787156 C18.3776355,2.01116948 18.3776355,1.04389112 17.7812542,0.447189053 C17.4828947,0.149175518 17.0920574,0 16.70122,0 Z"></path>
					</g>
				</svg>
			</span>
		</span>
	</h1>
	<h2>
		<span>
			<?php 
			if($post->post_content == ''){
				$pc = __('Describe the collection briefly','pluginhunt');
			}else{
				$pc = $post->post_content;
			}
			?>
			<span class='collection-content'><?php echo $pc; ?></span>
			<input class='hide edit-content-input' id='edit-content' maxlength="80" name="econtent" type="text" value="<?php echo $pc; ?>" onkeypress="return editDesc(event)" data-cid=<?php echo $post->ID;?>/>
			<span class="editable-text--button<?php echo $no; ?> edit-content" data-cid=<?php echo $post->ID;?>>
				<svg width="19" height="19" viewBox="0 0 19 19" xmlns="http://www.w3.org/2000/svg">
					<g fill="#B5B5B5" fill-rule="evenodd">
						<path d="M15.6111111,8.40952381 L15.6111111,15.0761905 C15.6111111,15.7800481 15.037191,16.3539683 14.3333333,16.3539683 L3.66666667,16.3539683 C2.9625373,16.3539683 2.38888889,15.7802208 2.38888889,15.0761905 L2.38888889,4.40952381 C2.38888889,3.70522174 2.9623646,3.13174603 3.66666667,3.13174603 L10.3333333,3.13174603 C10.854809,3.13174603 11.2777778,2.7087773 11.2777778,2.18730159 C11.2777778,1.66545755 10.8548853,1.24285714 10.3333333,1.24285714 L3.66666667,1.24285714 C1.9176354,1.24285714 0.5,2.66049255 0.5,4.40952381 L0.5,15.0761905 C0.5,16.8252217 1.9176354,18.2428571 3.66666667,18.2428571 L14.3333333,18.2428571 C16.0820608,18.2428571 17.5,16.8250811 17.5,15.0761905 L17.5,8.40952381 C17.5,7.88767977 17.0771075,7.46507937 16.5555556,7.46507937 C16.0336354,7.46507937 15.6111111,7.88760366 15.6111111,8.40952381 L15.6111111,8.40952381 Z M16.6111111,8.40952381 C16.6111111,8.43988841 16.5859202,8.46507937 16.5555556,8.46507937 C16.5249774,8.46507937 16.5,8.44011923 16.5,8.40952381 L16.5,15.0761905 C16.5,16.2727706 15.5298018,17.2428571 14.3333333,17.2428571 L3.66666667,17.2428571 C2.46992015,17.2428571 1.5,16.272937 1.5,15.0761905 L1.5,4.40952381 C1.5,3.2127773 2.46992015,2.24285714 3.66666667,2.24285714 L10.3333333,2.24285714 C10.3027552,2.24285714 10.2777778,2.21789701 10.2777778,2.18730159 C10.2777778,2.15649255 10.3025243,2.13174603 10.3333333,2.13174603 L3.66666667,2.13174603 C2.41007985,2.13174603 1.38888889,3.15293699 1.38888889,4.40952381 L1.38888889,15.0761905 C1.38888889,16.332467 2.41021392,17.3539683 3.66666667,17.3539683 L14.3333333,17.3539683 C15.5894757,17.3539683 16.6111111,16.3323329 16.6111111,15.0761905 L16.6111111,8.40952381 L16.6111111,8.40952381 Z"></path>
						<path d="M16.70122,0 C16.3097077,0 15.9188703,0.149175518 15.6205109,0.447189053 L9.22854018,6.8393175 L9.22854018,9 L11.3892835,9 L17.7812542,2.60787156 C18.3776355,2.01116948 18.3776355,1.04389112 17.7812542,0.447189053 C17.4828947,0.149175518 17.0920574,0 16.70122,0 Z"></path>
					</g>
				</svg>
			</span>
		</span>
	</h2>
	<div class="collection-detail--header--curator">
		<span class="user-image" style="width:100%;text-align:center">
			<?php echo get_avatar( $post->post_author, 30 ); ?>
			<span>by  <?php echo $author->nickname;?></span>
		</span>
		</div>
		<a class="collection-detail--header--delete-button<?php echo $no; ?>" data-cid=<?php echo $post->ID;?>>
			<span><i class="fa fa-trash-o"></i><?php _e('Delete this collection','pluginhunt');?></span></a></div></div>
</header>
</div>
<?php }else{ ?>
<div class="collection-detail" ><header class="collection-detail--header v-brown" style="background-image: url(<?php echo $image[0]; ?>);">
<div class="collection-detail--header--shadow">
	<div class="collection-detail--header--heading">
	<h1>
		<span class='collection-title' id='collection-title'><?php the_title(); ?></span>
	</h1>
	<h2>
		<span>
			<?php 
			if($post->post_content == ''){
				$pc = __('Describe the collection briefly','pluginhunt');
			}else{
				$pc = $post->post_content;
			}
			?>
			<span class='collection-content'><?php echo $pc; ?></span>
		</span>
	</h2>
	<div class="collection-detail--header--curator">
		<span class="user-image">
			<?php echo get_avatar( $post->post_author, 30 ); ?>
		</span>
			<span><?php _e('by','pluginhunt');?>  <?php echo $author->nickname;?></span>
		</div>
		</div></div>
</header>
</div>



<?php } ?>

<div class="collection-detail--subnav" data-reactid=".3.1"><div class="collection-detail--subnav--container" ><div class="collection-detail--share-buttons"><a class="collection-detail--share-buttons--item v-twitter share" href="<?php echo get_permalink();?>" title="<?php the_title(); ?>" data-action="twitter"><span data-reactid=".3.1.0.3.0.0"><!--?xml version="1.0" encoding="UTF-8" standalone="no"?-->
<svg width="16px" height="13px" viewBox="0 0 16 13" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
  <path d="M15.999,1.5367041 C15.4105184,1.79765391 14.7775382,1.97411998 14.1135589,2.05360469 C14.7910377,1.64718285 15.3115215,1.00430648 15.5570138,0.237953855 C14.9225336,0.613881561 14.2200556,0.887328975 13.472579,1.03430071 C12.8735977,0.39642338 12.0206243,-0.002 11.0766538,-0.002 C9.26371048,-0.002 7.7942564,1.46721746 7.7942564,3.27986887 C7.7942564,3.53731936 7.82325549,3.7877712 7.87925374,4.02772505 C5.15133899,3.89075139 2.73241458,2.58400269 1.11346517,0.598384541 C0.830974001,1.08329129 0.668979063,1.64668295 0.668979063,2.2485672 C0.668979063,3.3873482 1.24846095,4.39165507 2.12943342,4.98054182 C1.59145024,4.96354509 1.08546605,4.81607345 0.642479891,4.57012075 C0.641979907,4.58361815 0.641979907,4.59761546 0.641979907,4.61161277 C0.641979907,6.20180696 1.77344455,7.52805191 3.27489763,7.82949394 C2.99940624,7.90447952 2.7094153,7.94447183 2.40992466,7.94447183 C2.19843127,7.94447183 1.99293769,7.92397577 1.79244395,7.88548318 C2.20993091,9.18923246 3.42239302,10.13805 4.85884813,10.1645449 C3.73538324,11.0448756 2.31992747,11.5692748 0.781975532,11.5692748 C0.516983813,11.5692748 0.255991969,11.5537777 -0.001,11.5232836 C1.45145461,12.4546045 3.17690069,12.998 5.03084275,12.998 C11.0686541,12.998 14.3700509,7.99696174 14.3700509,3.65979581 C14.3700509,3.51732321 14.367051,3.37585041 14.3605512,3.23537743 C15.0020312,2.77246645 15.5585138,2.19457758 15.9985,1.5367041 L15.999,1.5367041 Z" id="twitter" fill="#000000"></path>
</svg>
</span></a><a class="collection-detail--share-buttons--item v-facebook share" href="<?php echo get_permalink();?>" title="<?php the_title(); ?>" data-action="facebook"><span><svg width="8" height="13" viewBox="0 0 8 14" xmlns="http://www.w3.org/2000/svg"><path d="M7.2 2.323H5.923c-1.046 0-1.278.464-1.278 1.16V5.11h2.44l-.35 2.438h-2.09v6.387H2.09V7.548H0V5.11h2.09V3.252C2.09 1.162 3.368 0 5.342 0c.93 0 1.742.116 1.858.116v2.207z" fill="#FFF" fill-rule="evenodd"></path></svg></span></a></div></div></div>

<?php
$uid = get_current_user_id();
$ph = get_user_meta($uid,'ph_collected_posts',true);
?>


<div class='ph-layout-2 ph-list'>
<div class='container-fluid'>
	<div class='post-wrapper'>



	<div class='col-md-12 ws'>


	<a id="ph-log-social-new" href="#animatedModal" style="display:none">.</a>

<div class='clear'></div>
		<div class='options discuss-switch'><span class='active popular d-s'><?php _e('popular','pluginhunt'); ?></span><span class='newest d-s hide'><?php _e('newest','pluginhunt'); ?></span></div>
		<div class='maincontent hunt-list'>

<?php
	global $wp_query,$post,$wpdb, $current_user,$query_string;
    wp_get_current_user();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

	#} check that our paged variable is being passed
	 $paged = ($_GET["page"]) ? $_GET["page"] : 0;


	#} first query for 20 posts and get the post_date of the last post
	if($paged == 0){

		#} build our first query of posts minimum 20 + the full day in which the 20th post is taken
		$querystr = "
		    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
	        MONTH(post_date) AS `month`,
	        DAYOFMONTH(post_date) AS `dayofmonth`
		    FROM $wpdb->posts, $wpdb->postmeta
		    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
		    AND $wpdb->posts.ID IN ( $col )
		    AND $wpdb->postmeta.meta_key = 'epicredvote' 
		    AND $wpdb->posts.post_status = 'publish' 
		    AND $wpdb->posts.post_type = 'post'
		    AND post_date >= '$tf'
		    GROUP BY ID
		    ORDER BY $wpdb->postmeta.meta_value+0 DESC
		 ";
		 $pageposts = $wpdb->get_results($querystr, OBJECT);
	}

    $hide = 1;
    $day_check = '';
    $num_posts = 0;


if ($pageposts): ?>
 <?php global $post; ?>
 <?php foreach ($pageposts as $post): ?>
 <?php setup_postdata($post); 

			$num_posts++;
			
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

			if($num_posts > 10 && $paged > 1){
				$blob = 'hidepost hidepost-' . $d . '-'. $m. '-' . $y;
			}else{
				$blob = '';
			}		
			 ?>
			
			<div class = 'row hunt-row <?php echo $blob;?>'>
		
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
							$phfi = get_post_meta($post->ID,'phfeaturedimage',true);
			?>
			
			<?php if ( has_post_thumbnail() ) {   ?>
						<div class = 'reddit-post pull-left' id='reddit-post-<?php echo $post->ID;?>' data-ph-url="<?php  echo get_permalink( $post->ID ); ?>" data-slug='<?php echo $post->post_name; ?>' data-id='<?php echo $post->ID; ?>' data-url = '<?php echo $url; ?>' data-auth = '<?php echo $auth; ?>' data-rajax = '<?php echo $post->ID; ?>' data-rups = '<?php echo $postvote;?>' data-pname='<?php echo $pname;?>' data-profurl="<?php echo $profileUrl; ?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>">
							<div class='ph-list-thumbnail'>
							<?php the_post_thumbnail('small'); ?>
							</div>
			<?php }elseif($phfi != ''){ ?>
						<div class = 'reddit-post pull-left' id='reddit-post-<?php echo $post->ID;?>' data-ph-url="<?php  echo get_permalink( $post->ID ); ?>" data-slug='<?php echo $post->post_name; ?>' data-id='<?php echo $post->ID; ?>' data-url = '<?php echo $url; ?>' data-auth = '<?php echo $auth; ?>' data-rajax = '<?php echo $post->ID; ?>' data-rups = '<?php echo $postvote;?>' data-pname='<?php echo $pname;?>' data-profurl="<?php echo $profileUrl; ?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>">			
							<div class='ph-list-thumbnail'>
								<img src="<?php echo $phfi;?>" class="attachment-small size-small wp-post-image">
							</div>
			<?php }else{ ?>
						<div class = 'reddit-post pull-left' id='reddit-post-<?php echo $post->ID;?>' data-ph-url="<?php  echo get_permalink( $post->ID ); ?>" data-slug='<?php echo $post->post_name; ?>' data-id='<?php echo $post->ID; ?>' data-url = '<?php echo $url; ?>' data-auth = '<?php echo $auth; ?>' data-rajax = '<?php echo $post->ID; ?>' data-rups = '<?php echo $postvote;?>' data-pname='<?php echo $pname;?>' data-profurl="<?php echo $profileUrl; ?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>">
			<?php } ?>
						

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
							 <div class='description'>
							 	<?php
							 	if( of_get_option('ph_grid_on') == 1) { 
									echo wp_trim_words( get_the_excerpt(), 10); 
								}else{
									echo wp_trim_words( get_the_excerpt(), 20); 
								}
							 	?>

						<div class='low-blow'>
							<div class = "reddit-voting <?php echo $c; ?> reddit-voting-<?php echo $post->ID; ?>">
									<div class="arrow fa fa-caret-up  fa-2x arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
									<div class="score score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
							</div>
							<div class='comment-icon'>
									<span><svg width="12" height="11" viewBox="0 0 19 17" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="b"><stop stop-color="#CCC" offset="0%"></stop><stop stop-color="#BABABA" offset="100%"></stop></linearGradient><filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="a"><feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset><feGaussianBlur stdDeviation=".5" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur><feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.070000001 0" in="shadowBlurOuter1" result="shadowMatrixOuter1"></feColorMatrix><feMerge><feMergeNode in="shadowMatrixOuter1"></feMergeNode><feMergeNode in="SourceGraphic"></feMergeNode></feMerge></filter></defs><path d="M1.753 18.06C.66 16.996 0 15.623 0 14.125 0 10.742 3.358 8 7.5 8c4.142 0 7.5 2.742 7.5 6.125s-3.358 6.125-7.5 6.125c-1.03 0-2.01-.17-2.904-.476-1.46.845-4.318 1.962-4.318 1.962s.955-2.298 1.475-3.676z" transform="translate(2 -7)" filter="url(#a)" stroke="url(#b)" fill="#bbb" fill-rule="evenodd"></path></svg></span>
									 <span class='hunt-comm-count'><?php $comments = get_comments('post_id=' . $post->ID); echo count($comments); ?></span>
							</div>
							<div class='post-meta-hunt'>
								<!-- external URL button -->


								<?php if(!ph_in_user_collection($post->ID)) { ?>
								<div class='ph-external ph-action'>
									<span class="post-item--action v-icon"><a href="<?php echo esc_url($out); ?>" target="_blank" data-reactid=".3.0.0:$1.0.2.0.$31121.0.0.3.0.0"><span data-reactid=".3.0.0:$1.0.2.0.$31121.0.0.3.0.0.0"><svg width="12" height="11" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg"><g fill="#BBB" fill-rule="evenodd"><path d="M0 3h16v2H0z"></path><rect width="16" height="2" rx="2"></rect><rect y="12" width="16" height="2" rx="2"></rect><rect width="2" height="14" rx="2"></rect><rect x="14" width="2" height="14" rx="2"></rect><path d="M9.355 6.355C9.16 6.16 9.215 6 9.49 6h3.26c.138 0 .25.115.25.25v3.26c0 .27-.152.338-.355.135l-3.29-3.29z"></path></g></svg></span></a></span>
								</div>
								<div class='ph-collect ph-action' data-pid ='<?php echo $post->ID;?>'>
									<span class="collect-button--icon" data-pid ='<?php echo $post->ID;?>'>
										<svg width="12" height="11" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg">
											<path d="M13 10V8.99c0-.54-.448-.99-1-.99-.556 0-1 .444-1 .99V10H9.99c-.54 0-.99.448-.99 1 0 .556.444 1 .99 1H11v1.01c0 .54.448.99 1 .99.556 0 1-.444 1-.99V12h1.01c.54 0 .99-.448.99-1 0-.556-.444-1-.99-1H13zM0 1c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 2 0 1.556 0 1zm0 5c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 7 0 6.556 0 6zm0 5c0-.552.453-1 .997-1h6.006c.55 0 .997.444.997 1 0 .552-.453 1-.997 1H.997C.447 12 0 11.556 0 11z" fill="#C8C0B1" fill-rule="evenodd"></path>
										</svg>
										<span class='ph-save'> <?php _e('Save','pluginhunt'); ?> </span>
										<?php }else{ ?>
								<div class='ph-external ph-external-in ph-action'>
									<span class="post-item--action v-icon"><a href="<?php echo esc_url($out); ?>" target="_blank" data-reactid=".3.0.0:$1.0.2.0.$31121.0.0.3.0.0"><span data-reactid=".3.0.0:$1.0.2.0.$31121.0.0.3.0.0.0"><svg width="12" height="11" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg"><g fill="#BBB" fill-rule="evenodd"><path d="M0 3h16v2H0z"></path><rect width="16" height="2" rx="2"></rect><rect y="12" width="16" height="2" rx="2"></rect><rect width="2" height="14" rx="2"></rect><rect x="14" width="2" height="14" rx="2"></rect><path d="M9.355 6.355C9.16 6.16 9.215 6 9.49 6h3.26c.138 0 .25.115.25.25v3.26c0 .27-.152.338-.355.135l-3.29-3.29z"></path></g></svg></span></a></span>
								</div>
									<div class='ph-collect ph-collect-in in ph-action' data-pid ='<?php echo $post->ID;?>'>
										<span class="collect-button--icon in" data-pid ='<?php echo $post->ID;?>'>
											<svg width="12" height="11" viewBox="0 0 17 14" xmlns="http://www.w3.org/2000/svg">
										      <path d="M11.036 10.864L9.62 9.45c-.392-.394-1.022-.39-1.413 0-.393.393-.39 1.023 0 1.414l2.122 2.12c.193.198.45.295.703.295.256 0 .51-.1.706-.295l4.246-4.246c.385-.385.39-1.02-.002-1.413-.393-.393-1.022-.39-1.412-.002l-3.537 3.538zM0 1c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h4.5c.552 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.552 0-1-.444-1-1z" fill="#DC5425" fill-rule="evenodd"></path>
										  	</svg>
										  	<span class='ph-save ph-saved'> <?php _e('Save','pluginhunt'); ?> </span>
									  	<?php } ?>
									  		
										</span>
										</div>
								</div>
								<div style="clear:both"></div>
							</div>			
							</div>
						</div>
					</div>
				</div>
			</div>
 <?php endforeach; ?>
 <?php else : ?>



 <?php endif; ?>

 			<?php if($num_posts > 10 && $paged >1){ 
 			$more = $num_posts - 10;
 			echo "<div class='unhide show-hidden-posts'><span class='showmore' data-d=$d data-m=$m data-y=$y><i class='hp fa fa-chevron-down'></i> ";
 			$text = sprintf( _n( 'Show 1 more ' . get_theme_mod('ph_keyword_s', 'plugin'), 'Show %s more ' . get_theme_mod('ph_keyword_p', 'plugins'), $more, 'ph_theme' ), $more );
			echo $text;
 			echo "</span></div>";

 			 } ?>


			

		</div>

	</div>




</div>
		</div>



	</div>


			
			<?php wp_reset_query(); ?>



</div>

</div>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>


<?php get_footer("mobile"); ?>
