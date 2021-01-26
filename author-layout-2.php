<?php
/**
 * The author template
*
*/

get_header();
	
	$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
	$email = $author->user_email; 
	$site = $author->user_url;
	$size = 100;

global $wp_query,$post,$wpdb, $current_user,$query_string;
      
    wp_get_current_user();
	  $wpdb->myo_ip   = $wpdb->prefix . 'epicred';
    $ph_follows = $wpdb->prefix . "ph_follows";
// grab the queries we want for the new tabbed display, being:- 
    $fid = $current_user->ID;
    $aid = $author->ID;

    $q1 = "SELECT epicred_id FROM $wpdb->myo_ip JOIN $wpdb->posts ON $wpdb->myo_ip.epicred_id = $wpdb->posts.ID WHERE epicred_ip = $aid AND $wpdb->posts.post_status = 'publish'";
    $upvoted = $wpdb->get_results($q1, OBJECT);
    $cu =  count($upvoted);
    // #2 submitted 
    $q2 = "SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_author = $aid AND $wpdb->posts.post_type = 'post' AND $wpdb->posts.post_status = 'publish'";
    $submitted = $wpdb->get_results($q2, OBJECT);
    $cs =  count($submitted);
    // #3 'made' (UX to be added - suggest a maker - how to handle since not just twitter login...)
    // #4 collections (UX to be added)
    $q4 = "SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_author = $aid AND $wpdb->posts.post_type = 'collections' AND $wpdb->posts.post_status = 'publish'";
    $collected = $wpdb->get_results($q4, OBJECT);
    $cl =  count($collected);
    #} discussions
    if(of_get_option('ph_enable_discussions') == 1){
    $q7 = "SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_author = $aid AND $wpdb->posts.post_type = 'discussions' AND $wpdb->posts.post_status = 'publish'";
    $discussed = $wpdb->get_results($q7, OBJECT);
    $cd =  count($discussed);
    }

    // #5 followers (UX to be added)
    $q5 = "SELECT follower FROM $ph_follows WHERE followed = $aid";
    $followers = $wpdb->get_results($q5, OBJECT);
    $cfr =  count($followers);
   // #6 following (UX to be added)
    $q6 = "SELECT followed FROM $ph_follows WHERE follower = $aid";
    $followed = $wpdb->get_results($q6, OBJECT);
    $cfd =  count($followed);

    global $sub_menu_ph62;
    if($sub_menu_ph62){
        $pheaderclass = 'extra-margin-top';
    }else{
        $pheaderclass = 'no-extra-margin';
    }

?>

<header class="page-header <?php echo $pheaderclass;?>">
      <div class="container">
        <div class="page-header--avatar">
            <!-- <a class="user-image--badge v-maker v-big" href="#">M</a>-->
            <?php echo get_avatar( $aid ); ?>
        </div>


        <div class="page-header--info" data-component="Emoji">
          <h1 class="page-header--title"><?php echo $author->nickname;?><span class="page-header--id">#<?php echo $aid; ?></span></h1>
          <h2 class="page-header--subtitle"><?php echo $author->description; ?></h2>
          <div class="page-header--links">
            <?php
            $tw = get_user_meta( $aid, 'twitter', true );
            if($tw){ 
            ?>
            <a class="page-header--links--link" target="_blank" href="http://twitter.com/<?php echo $tw; ?>">@<?php echo $tw; ?></a>
            <?php }?>
              <a class="page-header--links--link" target="_blank" href="<?php echo $site;?>"><?php echo $site;?></a>
          </div>
        </div>

        <div class="page-header--buttons">
          <?php 
          if($fid != $aid){
          $inDB = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $ph_follows WHERE follower = %d AND followed = %d", array($fid, $aid)) );
          if($inDB){ ?>
            <a class="button v-red ph-follow" data-crud = "0" data-follower="<?php echo $fid;?>" data-follow="<?php echo $aid;?>"  href="#"><?php _e("Unfollow","ph_theme");?></a>
          <?php }else{ ?>
            <a class="button v-green ph-follow" data-crud = "1"  data-follower="<?php echo $fid;?>" data-follow="<?php echo $aid;?>"  href="#"><?php _e("Follow","ph_theme");?></a>
          <?php } }?>
        </div>
        <?php ?>

        <nav class="page-header--navigation">
  <ul>
    <li class="page-header--navigation--tab m-active" id = 'ah-upvoted'>
      <a href="#"><strong><?php echo $cu; ?></strong> <?php _e('Upvoted','pluginhunt');?></a>
    </li>
      <li class="page-header--navigation--tab" id = 'ah-submitted'>
        <a href="#"><strong><?php echo $cs; ?></strong><?php _e('Submitted','pluginhunt');?></a>
      </li>
<!-- MAKER to come in the future
    <li class="page-header--navigation--tab" id = 'ah-made'>
      <a href="#"><strong>3</strong> Made</a>
    </li>
-->
      <li class="page-header--navigation--tab" id = 'ah-collections'>
        <a href="#"><strong><?php echo $cl; ?></strong><?php  _e('Collections','pluginhunt');?></a>
      </li>

      <?php if(of_get_option('ph_enable_discussions') == 1){ ?>
      <li class="page-header--navigation--tab" id = 'ah-discussions'>
        <a href="#"><strong><?php echo $cd; ?></strong><?php _e('Discussions','pluginhunt');?></a>
      </li>
      <?php } ?>

    <li class="page-header--navigation--tab" id = 'ah-followers'>
      <a href="#"><strong><?php echo $cfr; ?></strong><?php _e('Followers','pluginhunt');?></a>
    </li>
    <li class="page-header--navigation--tab" id = 'ah-following'>
      <a href="#"><strong><?php echo $cfd; ?></strong><?php _e('Following','pluginhunt');?></a>
    </li>
  </ul>
</nav>

      </div>
    </header>

<div class='container ph-tab-container'>

<div class='ph-layout-2 ph-list'>

  <div class='col-md-12 white-splash'>

  <div class='ph-tabbed active' id ='ah-upvoted-tab'>
<?php global $post; ?>
<?php $num_posts = 0; ?>
 <?php foreach ($upvoted as $ID): ?>
 <?php 

      $post = get_post($ID->epicred_id);

      $num_posts++;
      $day = get_the_date('j');
      $date = get_the_date('l');
      
      $stamp = get_the_date('U');
        if ($stamp >= strtotime("today"))
              $date = __("Today",'ph_theme');
          else if ($stamp >= strtotime("yesterday"))
               $date = __('Yesterday','ph_theme');


      
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
                    //echo wp_trim_words( $post->post_content, 10); 
                    the_excerpt();
                  }else{
                    //echo wp_trim_words( $post->post_content, 20); 
                    the_excerpt();
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
                <div class='ph-external ph-action'>
                  <span class="post-item--action v-icon"><a href="<?php echo esc_url($out); ?>" target="_blank" data-reactid=".3.0.0:$1.0.2.0.$31121.0.0.3.0.0"><span data-reactid=".3.0.0:$1.0.2.0.$31121.0.0.3.0.0.0"><svg width="12" height="11" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg"><g fill="#BBB" fill-rule="evenodd"><path d="M0 3h16v2H0z"></path><rect width="16" height="2" rx="2"></rect><rect y="12" width="16" height="2" rx="2"></rect><rect width="2" height="14" rx="2"></rect><rect x="14" width="2" height="14" rx="2"></rect><path d="M9.355 6.355C9.16 6.16 9.215 6 9.49 6h3.26c.138 0 .25.115.25.25v3.26c0 .27-.152.338-.355.135l-3.29-3.29z"></path></g></svg></span></a></span>
                </div>

                <?php if(!ph_in_user_collection($post->ID)) { ?>
                <div class='ph-collect ph-action' data-pid ='<?php echo $post->ID;?>'>
                  <span class="collect-button--icon" data-pid ='<?php echo $post->ID;?>'>
                    <svg width="12" height="11" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg">
                      <path d="M13 10V8.99c0-.54-.448-.99-1-.99-.556 0-1 .444-1 .99V10H9.99c-.54 0-.99.448-.99 1 0 .556.444 1 .99 1H11v1.01c0 .54.448.99 1 .99.556 0 1-.444 1-.99V12h1.01c.54 0 .99-.448.99-1 0-.556-.444-1-.99-1H13zM0 1c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 2 0 1.556 0 1zm0 5c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 7 0 6.556 0 6zm0 5c0-.552.453-1 .997-1h6.006c.55 0 .997.444.997 1 0 .552-.453 1-.997 1H.997C.447 12 0 11.556 0 11z" fill="#C8C0B1" fill-rule="evenodd"></path>
                    </svg>
                    <?php }else{ ?>
                  <div class='ph-collect in ph-action' data-pid ='<?php echo $post->ID;?>'>
                    <span class="collect-button--icon in" data-pid ='<?php echo $post->ID;?>'>
                      <svg width="12" height="11" viewBox="0 0 17 14" xmlns="http://www.w3.org/2000/svg">
                          <path d="M11.036 10.864L9.62 9.45c-.392-.394-1.022-.39-1.413 0-.393.393-.39 1.023 0 1.414l2.122 2.12c.193.198.45.295.703.295.256 0 .51-.1.706-.295l4.246-4.246c.385-.385.39-1.02-.002-1.413-.393-.393-1.022-.39-1.412-.002l-3.537 3.538zM0 1c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h4.5c.552 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.552 0-1-.444-1-1z" fill="#DC5425" fill-rule="evenodd"></path>
                        </svg>
                      <?php } ?>
                        <span class='ph-save'><?php _e('Save','pluginhunt'); ?></span>
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

  </div>
  <div class='ph-tabbed' id ='ah-submitted-tab'>
<?php global $post; ?>
<?php $num_posts = 0; ?>
 <?php foreach ($submitted as $ID): ?>
 <?php 

      $post = get_post($ID->ID);

      $num_posts++;
      $day = get_the_date('j');
      $date = get_the_date('l');
      
      $stamp = get_the_date('U');
        if ($stamp >= strtotime("today"))
              $date = __("Today",'ph_theme');
          else if ($stamp >= strtotime("yesterday"))
               $date = __('Yesterday','ph_theme');


      
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
                  echo wp_trim_words( $post->post_content , 10); 
                }else{
                  echo wp_trim_words( $post->post_content , 20); 
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
                <div class='ph-external ph-action'>
                  <span class="post-item--action v-icon"><a href="<?php echo esc_url($out); ?>" target="_blank" data-reactid=".3.0.0:$1.0.2.0.$31121.0.0.3.0.0"><span data-reactid=".3.0.0:$1.0.2.0.$31121.0.0.3.0.0.0"><svg width="12" height="11" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg"><g fill="#BBB" fill-rule="evenodd"><path d="M0 3h16v2H0z"></path><rect width="16" height="2" rx="2"></rect><rect y="12" width="16" height="2" rx="2"></rect><rect width="2" height="14" rx="2"></rect><rect x="14" width="2" height="14" rx="2"></rect><path d="M9.355 6.355C9.16 6.16 9.215 6 9.49 6h3.26c.138 0 .25.115.25.25v3.26c0 .27-.152.338-.355.135l-3.29-3.29z"></path></g></svg></span></a></span>
                </div>

                <?php if(!ph_in_user_collection($post->ID)) { ?>
                <div class='ph-collect ph-action' data-pid ='<?php echo $post->ID;?>'>
                  <span class="collect-button--icon" data-pid ='<?php echo $post->ID;?>'>
                    <svg width="12" height="11" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg">
                      <path d="M13 10V8.99c0-.54-.448-.99-1-.99-.556 0-1 .444-1 .99V10H9.99c-.54 0-.99.448-.99 1 0 .556.444 1 .99 1H11v1.01c0 .54.448.99 1 .99.556 0 1-.444 1-.99V12h1.01c.54 0 .99-.448.99-1 0-.556-.444-1-.99-1H13zM0 1c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 2 0 1.556 0 1zm0 5c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 7 0 6.556 0 6zm0 5c0-.552.453-1 .997-1h6.006c.55 0 .997.444.997 1 0 .552-.453 1-.997 1H.997C.447 12 0 11.556 0 11z" fill="#C8C0B1" fill-rule="evenodd"></path>
                    </svg>
                    <?php }else{ ?>
                  <div class='ph-collect in ph-action' data-pid ='<?php echo $post->ID;?>'>
                    <span class="collect-button--icon in" data-pid ='<?php echo $post->ID;?>'>
                      <svg width="12" height="11" viewBox="0 0 17 14" xmlns="http://www.w3.org/2000/svg">
                          <path d="M11.036 10.864L9.62 9.45c-.392-.394-1.022-.39-1.413 0-.393.393-.39 1.023 0 1.414l2.122 2.12c.193.198.45.295.703.295.256 0 .51-.1.706-.295l4.246-4.246c.385-.385.39-1.02-.002-1.413-.393-.393-1.022-.39-1.412-.002l-3.537 3.538zM0 1c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h4.5c.552 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.552 0-1-.444-1-1z" fill="#DC5425" fill-rule="evenodd"></path>
                        </svg>
                      <?php } ?>
                        <span class='ph-save'><?php _e('Save','pluginhunt'); ?></span>
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
  </div>

  <div class='ph-tabbed' id='ah-collections-tab'>
    <?php
      foreach($collected as $c){
        $post = get_post($c->ID);
         get_template_part( 'content', 'collections' );
      }
    ?>

  </div>

<?php if(of_get_option('ph_enable_discussions') == 1){ ?>
    <div class='ph-tabbed' id='ah-discussions-tab'>
    <?php
      foreach($discussed as $d){
        $post = get_post($d->ID);
         get_template_part( 'content', 'discussions' );
      }
    ?>
  </div>
  <?php } ?>

  <div class='ph-tabbed' id ='ah-followers-tab'>
    <?php 
       $fw = 0;
       foreach($followers as $f){ 

         if($fw==0){
          echo "<div class='row author-row'>";
         }

        echo "<div class='col-md-4 author-list'>";
          echo "<span class='person'>";
          echo  '<a href="';
          echo get_author_posts_url( $f->follower );
          echo '">';
          echo get_avatar( $f->follower, 40 ); 
            echo "<div class='title'>";
              echo get_author_name( $f->follower );
            echo "</div></a></span>";
           $fw++;
          echo "</div>";

          if($fw == 3){
            $fw=0; 
            echo "</div>";
          }
          

        }
     
    ?>
  </div>
  </div>
  <div class='ph-tabbed' id ='ah-following-tab'>
    <?php 
       $fr = 0;
       foreach($followed as $g){ 

         if($fr==0){
          echo "<div class='row author-row'>";
         }

        echo "<div class='col-md-4 author-list'>";
          echo "<span class='person'>";
          echo  '<a href="';
          echo get_author_posts_url( $g->followed );
          echo '">';
          echo get_avatar( $g->followed, 40 ); 
            echo "<div class='title'>";
              echo get_author_name( $g->followed );
            echo "</div></span>";
           $fr++;
          echo "</div>";

          if($fr == 3){
            $fr=0; 
            echo "</div>";
          }

        }
        echo "</div>";
    ?>

  </div>
</div>

</div>

			
			<?php wp_reset_query(); ?>


<?php get_footer(); ?>
