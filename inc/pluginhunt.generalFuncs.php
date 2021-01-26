<?php

function pluginhunt_QueryPosts($paged, $ph_grouping){
	global $wpdb, $pageposts,$d,$m,$y,$ph_grouping, $key;
	if($ph_grouping == 'ph-group-day'){
		if($paged == 0){
			$query = "SELECT post_date, YEAR(post_date) AS `year`, MONTH(post_date) AS `month`,
		        DAYOFMONTH(post_date) AS `dayofmonth` FROM $wpdb->posts WHERE post_status='publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 10";
			$first = $wpdb->get_results($query);
			$c =  count($first) - 1;
			$f =  $first[$c]->post_date;
			$tf = substr($f,0,10);
			$date = date('U');
			$d =  $first[$c]->dayofmonth;
			$m =  $first[$c]->month;
			$y =  $first[$c]->year;
			$key = '';
			#} extra check - does the day, month and year match for the first and last post from the 10. If so, then get all posts from that day, month and year
			
			if($d == $first[0]->dayofmonth && $m == $first[0]->month && $y == $first[0]->year){
			$querystr = "
			    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
		        MONTH(post_date) AS `month`,
		        DAYOFMONTH(post_date) AS `dayofmonth`
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = 'epicredvote' 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'post'
			    AND DAYOFMONTH(post_date) ='$d'
			    AND MONTH(post_date) = '$m'
			    AND YEAR(post_date) = '$y'
			    GROUP BY ID
			    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
			 ";
			}else{
			#} build our first query of posts minimum 20 + the full day in which the 20th post is taken
			$querystr = "
			    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
		        MONTH(post_date) AS `month`,
		        DAYOFMONTH(post_date) AS `dayofmonth`
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = 'epicredvote' 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'post'
			    AND post_date >= '$tf'
			    GROUP BY ID
			    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
			 ";
			}
			 $pageposts = $wpdb->get_results($querystr, OBJECT);
		}else if($paged == 1){
			$d = $_GET['day'];
			$m = $_GET['month'];
			$y = $_GET['year'];

	        $query = "SELECT YEAR(post_date) AS `year`,
	                  MONTH(post_date) AS `month`,
	                  DAYOFMONTH(post_date) AS `dayofmonth`,
	                  count(ID) as posts
	                  FROM $wpdb->posts
	                  WHERE post_type = 'post'
	                  AND post_status = 'publish'
	                  GROUP BY YEAR(post_date),
	                  MONTH(post_date),
	                  DAYOFMONTH(post_date)
	                  ORDER BY post_date DESC";
	 
	        $arcresults = $wpdb->get_results($query);    //this gets the posts grouped by year, month, dayofmonth



			$key = pluginhunt_findPrevious($y, $m, $d, $arcresults);

			echo "<div id='epic-key' class='hideme'>" . $key . "</div>";

			$d = $arcresults[$key]->dayofmonth;
			$m = $arcresults[$key]->month;
			$y = $arcresults[$key]->year;


			$querystr = "
			    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
		        MONTH(post_date) AS `month`,
		        DAYOFMONTH(post_date) AS `dayofmonth`
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = 'epicredvote' 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'post'
			    AND DAYOFMONTH(post_date) ='$d'
			    AND MONTH(post_date) = '$m'
			    AND YEAR(post_date) = '$y'
			    GROUP BY (ID)
			    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
			 ";


			 $pageposts = $wpdb->get_results($querystr, OBJECT);
		}else{
	        $query = "SELECT YEAR(post_date) AS `year`,
	                  MONTH(post_date) AS `month`,
	                  DAYOFMONTH(post_date) AS `dayofmonth`,
	                  count(ID) as posts
	                  FROM $wpdb->posts ".$join."
	                  WHERE post_type = 'post'
	                  AND post_status = 'publish'
	                  GROUP BY YEAR(post_date),
	                  MONTH(post_date),
	                  DAYOFMONTH(post_date)
	                  ORDER BY post_date DESC";
	 
	        $arcresults = $wpdb->get_results($query);  //this gets the posts grouped by year, month, dayofmonth
			$key = $_GET['key'];

			$d = $arcresults[$key]->dayofmonth;
			$m = $arcresults[$key]->month;
			$y = $arcresults[$key]->year;

			$querystr = "
			    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
		        MONTH(post_date) AS `month`,
		        DAYOFMONTH(post_date) AS `dayofmonth`
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = 'epicredvote' 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'post'
			    AND DAYOFMONTH(post_date) ='$d'
			    AND MONTH(post_date) = '$m'
			    AND YEAR(post_date) = '$y'
			    GROUP BY (ID)
			    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
			 ";
			 $pageposts = $wpdb->get_results($querystr, OBJECT);
		}
	}else{
		if($paged == 0){
			$query = "SELECT post_date, YEAR(post_date) AS `year`, MONTH(post_date) AS `month` FROM $wpdb->posts WHERE post_status='publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 10";
			$first = $wpdb->get_results($query);
			$c =  count($first) - 1;  //position in array....

			$position = count($first);
			
			$f =  $first[$c]->post_date;
			$tf = substr($f,0,10);
			$date = date('U');
			$m =  $first[$c]->month;
			$y =  $first[$c]->year;
			$key = '';
			#} extra check - does the month and year match for the first and last post from the 10. If so, then get all posts from that month and year
			
			if($m == $first[0]->month && $y == $first[0]->year){
			$querystr = "
			    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
		        MONTH(post_date) AS `month`
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = 'epicredvote' 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'post'
			    AND MONTH(post_date) = '$m'
			    AND YEAR(post_date) = '$y'
			    GROUP BY ID
			    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
			 ";
			}else{
			#} build our first query of posts minimum 20 + the full day in which the 20th post is taken
			$querystr = "
			    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
		        MONTH(post_date) AS `month`
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = 'epicredvote' 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'post'
			    AND post_date >= '$tf'
			    GROUP BY ID
			    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
			 ";
			}
			$pageposts = $wpdb->get_results($querystr, OBJECT);
		}else if($paged == 1){

			$m = (int)$_GET['month'];
			$y = (int)$_GET['dog'];

	
			$key = $paged;
			if(!isset($key)){
				$key = $paged;
			}

	        $query = "SELECT YEAR(post_date) AS `year`,
	                  MONTH(post_date) AS `month`,
	                  count(ID) as posts
	                  FROM $wpdb->posts
	                  WHERE post_type = 'post'
	                  AND post_status = 'publish'
	                  GROUP BY YEAR(post_date),
	                  MONTH(post_date)
	                  ORDER BY post_date DESC";

	        $d=1; //first day of month 
	        $arcresults = $wpdb->get_results($query);    //this gets the posts grouped by year, month, dayofmonth

			echo "<div id='epic-key' class='hideme'>" . $key . "</div>";


			$m = $arcresults[1]->month;
			$y = $arcresults[1]->year;

	
			$querystr = "
			    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
		        MONTH(post_date) AS `month`,
		        DAYOFMONTH(post_date) AS `dayofmonth`
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = 'epicredvote' 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'post'
			    AND MONTH(post_date) = '$m'
			    AND YEAR(post_date) = '$y'
			    GROUP BY (ID)
			    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
			 ";
			 $pageposts = $wpdb->get_results($querystr, OBJECT);
		}else{
	        $query = "SELECT YEAR(post_date) AS `year`,
	                  MONTH(post_date) AS `month`,
	                  count(ID) as posts
	                  FROM $wpdb->posts
	                  WHERE post_type = 'post'
	                  AND post_status = 'publish'
	                  GROUP BY YEAR(post_date),
	                  MONTH(post_date)
	                  ORDER BY post_date DESC";
	 
	        $arcresults = $wpdb->get_results($query);  //this gets the posts grouped by year, month, dayofmonth
			$key = $paged;

			$m = $arcresults[$key]->month;
			$y = $arcresults[$key]->year;

			$querystr = "
			    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
		        MONTH(post_date) AS `month`
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = 'epicredvote' 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'post'
			    AND MONTH(post_date) = '$m'
			    AND YEAR(post_date) = '$y'
			    GROUP BY (ID)
			    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
			 ";
			 $pageposts = $wpdb->get_results($querystr, OBJECT);
		}		   //grouping by month...
	}
}

function pluginhunt_SetupLoop($ph_grouping){
	global $hide, $day_check, $month_check, $num_posts, $fday, $ph_grouping;
	if($ph_grouping == 'ph-group-day'){
		$localDateTime = current_time('mysql');
		$DateTimeSplit = explode(' ',$localDateTime);
		$dateParts = explode('-',$DateTimeSplit[0]);
		$localDateMidnight = date("Y-m-d",mktime(0,0,0,$dateParts[1],$dateParts[2],$dateParts[0]));
		$localDateEpoch = strtotime($localDateMidnight);
		$yesterday = strtotime("-1 day", $localDateEpoch);
		$yesterdayEpoch = $localDateEpoch + $yesterday;
		
		$day = get_the_date('j');
		$month = get_the_date('m');
		$date = get_the_date('l');
		
		$stamp = get_the_date('U');
		
		if ($stamp >= $localDateEpoch)
        		$date = __("Today",'pluginhunt');
    		else if ($stamp >= $yesterdayEpoch)
        		 $date = __('Yesterday','pluginhunt');


		  if ($day != $day_check) {
			if($fday){
		  		echo "<div class='phday'>";
		  	}else{
		  		echo "<div style='clear:both'></div></div><div class='phday'>";
		  	}
		    echo "<div class='timing'><span class='day'>". $date ."</span><span class='date'>" . get_the_date() . "</span></div>";
		    $day_check = $day;
		    $fday = false;
		  }
	}else{
		
			$month = get_the_date('m');
			$year = get_the_date('y');

			if ($month != $month_check) {
				if($fday){
			  		echo "<div class='phday'>";
			  	}else{
			  		echo "<div style='clear:both'></div></div><div class='phday'>";
			  	}
				echo "<div class='timing'><span class='day'>" . get_the_date('F Y') . "</span></div>";
				$month_check = $month;
				$fday = false;
			}
	}
}

function pluginhunt_ExternalLink($post){ 
	$out =  get_post_meta($post->ID, 'outbound', true);
	?>
	<div class='ph-external ph-action'>
		<span class="post-item--action v-icon">
			<a href="<?php echo esc_url($out); ?>" target="_blank">
				<span>
					<svg width="16" height="14" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg">
						<g fill="#BBB" fill-rule="evenodd">
							<path d="M0 3h16v2H0z"></path>
							<rect width="16" height="2" rx="2"></rect>
							<rect y="12" width="16" height="2" rx="2"></rect>
							<rect width="2" height="14" rx="2"></rect>
							<rect x="14" width="2" height="14" rx="2"></rect>
							<path d="M9.355 6.355C9.16 6.16 9.215 6 9.49 6h3.26c.138 0 .25.115.25.25v3.26c0 .27-.152.338-.355.135l-3.29-3.29z"></path>
						</g>
					</svg>
				</span>
			</a>
		</span>
	</div>	
	<?php 
} 

function pluginhunt_CollectionOutput($post_id){
	if(!ph_in_user_collection($post_id)) { ?>
		<div class='ph-collect ph-action' data-pid ='<?php echo $post_id;?>'>
			<span class="collect-button--icon" data-pid ='<?php echo $post_id;?>'>
				<svg width="15" height="14" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg">
					<path d="M13 10V8.99c0-.54-.448-.99-1-.99-.556 0-1 .444-1 .99V10H9.99c-.54 0-.99.448-.99 1 0 .556.444 1 .99 1H11v1.01c0 .54.448.99 1 .99.556 0 1-.444 1-.99V12h1.01c.54 0 .99-.448.99-1 0-.556-.444-1-.99-1H13zM0 1c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 2 0 1.556 0 1zm0 5c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 7 0 6.556 0 6zm0 5c0-.552.453-1 .997-1h6.006c.55 0 .997.444.997 1 0 .552-.453 1-.997 1H.997C.447 12 0 11.556 0 11z" fill="#C8C0B1" fill-rule="evenodd"></path>
				</svg>
			</span>
		</div>
		<?php }else{ ?>
		<div class='ph-collect ph-collect-in in ph-action' data-pid ='<?php echo $post_id;?>'>
			<span class="collect-button--icon in" data-pid ='<?php echo $post_id;?>'>
				<svg width="17" height="14" viewBox="0 0 17 14" xmlns="http://www.w3.org/2000/svg">
			      <path d="M11.036 10.864L9.62 9.45c-.392-.394-1.022-.39-1.413 0-.393.393-.39 1.023 0 1.414l2.122 2.12c.193.198.45.295.703.295.256 0 .51-.1.706-.295l4.246-4.246c.385-.385.39-1.02-.002-1.413-.393-.393-1.022-.39-1.412-.002l-3.537 3.538zM0 1c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h4.5c.552 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.552 0-1-.444-1-1z" fill="#DC5425" fill-rule="evenodd"></path>
			  	</svg>
		  	</span>
		</div>
	  	<?php } 
}

function pluginhunt_GetRankings($post_id, $fid){		
	global $wpdb, $redscore, $redclassu, $redclassd, $voted, $c, $al;
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';
	$query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $post_id";
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
}

function pluginhunt_Navigation($ph_grouping){ 
	global $d,$m,$y,$ph_grouping;
	if($ph_grouping == 'ph-group-day'){ ?>
	<div id='navigation'>
	    <div class='next-posts-link'>
			<a href="<?php echo esc_url( home_url( '/' ) )?>?day=<?php echo $d;?>&month=<?php echo $m;?>&year=<?php echo $y;?>">next</a>
		</div>
	</div>
	<?php } else{ ?>
	<div id='navigation'>
	    <div class='next-posts-link'>
			<a href="<?php echo home_url( '/' ); ?>?day=<?php echo $d;?>&month=<?php echo $m;?>&dog=<?php echo $y; ?>&year=<?php echo $y;?>">next</a>
		</div>
	</div>
	<?php } 
}

function pluginhunt_FeaturedImage($post){
	$phfi = get_post_meta($post->ID,'phfeaturedimage',true);
	global $al, $url, $postvote, $redscore, $redclassu, $redclassd, $auth, $pname, $profileUrl;
	if ( has_post_thumbnail() ) {   ?>
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
					<div class='ph-list-thumbnail'>
						<?php $placeholderimage = of_get_option('placeholder_image'); ?>			
						<?php if ( $placeholderimage != '' ) { ?>
							<img src="<?php echo esc_url($placeholderimage); ?>" alt="<?php the_title(); ?>" class="wp-post-image"/>
						<?php }else{ ?>
							<img src="<?php bloginfo('template_directory'); ?>/images/placeholder1.jpg" alt="<?php the_title(); ?>" class="wp-post-image"/>
						<?php } ?>
					</div>
	<?php } 
}

function pluginhunt_AuthorMeta($post){
	?>
	<div class='author-ava'>
		<?php
		$plugina = get_post_meta($post->ID,'pluginauthor', true);
		 if($plugina == ''){  ?>
			<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo get_the_author_meta('user_nicename'); ?>">             <?php 
			$args = array( 'class' => 'img-rounded');
			echo get_avatar($post->post_author, 40); ?></span>
		<?php }else{ 
			$pluginava = get_post_meta($post->ID,'pluginavatar',true); ?>
			<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo $plugina; ?>"><img class='img-rounded' src="<?php echo $pluginava;?>" height = "40px" width="40px"/></span>
		<?php } ?>
	</div>
	<?php
}

function pluginhunt_StickyPosts(){
	global $wpdb, $sub_menu_ph62, $no_ph_mc;
    if($sub_menu_ph62 && $no_ph_mc){
        $pheaderclass = 'extra-margin-top';
    }else{
        $pheaderclass = 'no-extra-margin';
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
	 $output = '';
	 foreach($pageposts as $ppost){
	 	$ID = $ppost->post_id;
	    $title = get_the_title( $ID ); 
	    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $ID ), 'single-post-thumbnail' );
	    $t = $thumb[0];
	    $desc = get_post($ID)->post_content;
	    $out = get_the_permalink($ID);
	  $output = "
	<div class='ph-sticky ". $pheaderclass ."' id='phf'>
	    <span class='icon-x'><i class='fa fa-times icon-xy'></i></span>
	    <div class='row hunt-row-fp'>
	    <a class='title' href='$out'>$title</a>
	    <div class='img-featured'><img class='phsi' src='$t'/></div>
	    <span class='description'>$desc</span>
	  </div>
	</div>"; 	
	 }
	 echo $output;
	 wp_reset_query();
	}
}

function pluginhunt_commentList($post){ 
	?>
	<div class='comment-icon'>
		<span>
			<svg width="19" height="17" viewBox="0 0 19 17" xmlns="http://www.w3.org/2000/svg">
				<defs>
					<linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="b">
						<stop stop-color="#CCC" offset="0%"></stop>
						<stop stop-color="#BABABA" offset="100%"></stop>
					</linearGradient>
					<filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="a">
						<feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset>
						<feGaussianBlur stdDeviation=".5" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur>
						<feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.070000001 0" in="shadowBlurOuter1" result="shadowMatrixOuter1"></feColorMatrix>
						<feMerge>
							<feMergeNode in="shadowMatrixOuter1"></feMergeNode>
							<feMergeNode in="SourceGraphic"></feMergeNode>
						</feMerge>
					</filter>
				</defs>
				<path d="M1.753 18.06C.66 16.996 0 15.623 0 14.125 0 10.742 3.358 8 7.5 8c4.142 0 7.5 2.742 7.5 6.125s-3.358 6.125-7.5 6.125c-1.03 0-2.01-.17-2.904-.476-1.46.845-4.318 1.962-4.318 1.962s.955-2.298 1.475-3.676z" transform="translate(2 -7)" filter="url(#a)" stroke="url(#b)" fill="#FFF" fill-rule="evenodd"></path>
			</svg>
		</span>
		<span class='hunt-comm-count'><?php $comments = get_comments('post_id=' . $post->ID); echo count($comments); ?></span>
	</div>
	<?php
}

function pluginhunt_outputVoting($post){
	global $c, $al, $postvote, $redclassd;
	?>
	<div class = "reddit-voting <?php echo $c; ?> reddit-voting-<?php echo $post->ID; ?>">
		<ul class="unstyled">
			<div class="arrow fa fa-caret-up  fa-2x arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
			<div class="score score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
		</ul>
	</div>	
	<?php
}

function pluginhunt_PostContent($post){
	$title = get_the_title($post->ID);
	global $al;
	 ?>
	<span class='title'><?php echo $title; ?></span>		
	 <span class='description'>
	 	<?php
	 	if( of_get_option('ph_grid_on') == 1) { 
		//	echo wp_trim_words( get_the_excerpt(), 10); 
		}else{
		//	echo wp_trim_words( get_the_excerpt(), 30); 
		}

		the_excerpt();
	 	?>
	 </span>
	 <?php
}

function pluginhunt_PrettyPrint($array){
	echo '<pre>';
	var_dump($array);
	echo '</pre>';
}

function pluginhunt_Globals(){
	global $hide, $day_check, $month_check, $num_posts, $fday, $ph_grouping;
    $hide = 1;
    $day_check = '';
    $month_check = '';
    $num_posts = 0;
	$fday = true;
	$ph_grouping = of_get_option('ph_post_group','ph-group-day');
}

function pluginhunt_OutputSlider($ptype){
    global $sub_menu_ph62;
    if($sub_menu_ph62){
        if(is_user_logged_in()){
          $no_sli_height = '87px;';
        }else{
          $no_sli_height = '0px;';
        }
    }else{
        $no_sli_height = '20px;';
    }

	if( of_get_option('ph_show_slider') && !is_single() && !is_page()) { 
		echo ph_banner_slider('post');
	}else{
		echo "<div class='noslider' style='height:".$no_sli_height."'></div>";
	}
}

function pluginhunt_OutputMailchimp(){
	global $no_ph_mc;
	$IsSubscribed = UserSubscribed();
	if( of_get_option('mailchimp_showhidden') == 1 && !$IsSubscribed) { 
		$action = of_get_option('mailchimp_action_hidden');
		echo ph_mailchimp($action);
		$no_ph_mc = 0;
	}else{
		echo "<div class='nomc'></div>";
		$no_ph_mc = 1;
	}
}


?>