<?php

 // ------------------------------------------------------------------
 // Copyright epicplugins limited. www.epicplugins.com www.epicthemes.com
 // ------------------------------------------------------------------
 // Latest working version.
 //




/** NO CUSTOMIZER PLZ **/

add_action( 'wp_before_admin_bar_render', 'pluginhunt_before_admin_bar_render' ); 

function pluginhunt_before_admin_bar_render()
{
    global $wp_admin_bar;

    $wp_admin_bar->remove_menu('customize');
}

/**
 * Remove Admin Menu Link to Theme Customizer
 */
add_action( 'admin_menu', function () {
    global $submenu;

    if ( isset( $submenu[ 'themes.php' ] ) ) {
        foreach ( $submenu[ 'themes.php' ] as $index => $menu_item ) {
            if ( in_array( 'Customize', $menu_item ) ) {
                unset( $submenu[ 'themes.php' ][ $index ] );
            }
        }
    }
});
/********************************************************************************************************
*                                         GLOBALS ETC
*******************************************************************************************************/

global $ph_version;
$ph_version = '8.0';  //latest release

global $pluginhunt_slugs;
$pluginhunt_slugs['home']           = "pluginhunt-home";
$pluginhunt_slugs['waiting']        = "pluginhunt-waiting";
$pluginhunt_slugs['settings']       = "pluginhunt-settings";
$pluginhunt_slugs['tools']          = "pluginhunt-tools";
$pluginhunt_slugs['addons']          = "pluginhunt-addons";
$pluginhunt_slugs['guides']          = "pluginhunt-guides";
$pluginhunt_slugs['api']            = "pluginhunt-api";


require_once dirname( __FILE__ ) . '/inc/invite-list.php';
require_once dirname( __FILE__ ) . '/inc/options-framework.php';
require_once dirname( __FILE__ ) . '/inc/scripts-styles.php';
require_once dirname( __FILE__ ) . '/inc/MailChimp.php';

require_once dirname( __FILE__ ) . '/inc/pluginhunt.generalFuncs.php';

// customiser options
// require_once dirname( __FILE__ ) . '/includes/plugin-hunt-options.php';


define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
define( 'PLUGINHUNT_IMAGES', get_template_directory_uri() . '/images/' );
define( 'PLUGINHUNT_PATH', get_template_directory_uri() );

add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );

add_action('admin_menu', 'pluginhunt_admin_menu'); 
add_action('init', 'ajaxcomments_load_js');

#} AJAX actions 

add_action('wp_ajax_nopriv_ph_create_collection','ph_create_collection');
add_action( 'wp_ajax_ph_create_collection', 'ph_create_collection' );
add_action('wp_ajax_nopriv_ph_delete_collection','ph_delete_collection');
add_action( 'wp_ajax_ph_delete_collection', 'ph_delete_collection' );
add_action('wp_ajax_nopriv_ph_remove_from_collection','ph_remove_from_collection');
add_action( 'wp_ajax_ph_remove_from_collection', 'ph_remove_from_collection' );
add_action('wp_ajax_ph_mailchimpsubscribe','ph_mailchimpsubscribe');
add_action('wp_ajax_nopriv_ph_mailchimpsubscribe','ph_mailchimpsubscribe');

define( 'PLUGINHUNT_URL', get_template_directory_uri() );


function featured_collections($query) {
  if(!is_admin() && !is_page()){
      if ( is_post_type_archive('collections') ) {
        $query->set('meta_key', 'featured-product');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'DESC');
        $query->set('posts_per_page', '-1');
      }
  }
}
add_action( 'pre_get_posts', 'featured_collections' );






add_action( 'wp_ajax_ph_simple_discuss', 'ph_simple_discuss' );
function ph_simple_discuss(){
#} new AJAX for simple discussions
    check_ajax_referer( 'eh_security_key_1517', 'nonce' );
    $uid = get_current_user_id();
     // process form data
    write_log("discussion post success");
    $title         = $_POST['title'];
    $content       = $_POST['content'];

    $discat         = (int)$_POST['discat'];

    write_log('discussion cat passed ' + $discat);

    $ptype          = 'discussions';
    $status         = 'publish';
    $title          = sanitize_post_field( 'post_title', $title,'','db' );

    $post = array(
    'post_content'   => $content, 
    'post_title'     => $title, 
    'post_status'    => $status,
    'post_type'      => $ptype,
    'post_author'    => $uid,
     ); 
    $wid = wp_insert_post( $post);
    wp_set_object_terms( $wid, $discat, 'discussion_category', true );
    update_post_meta($wid, 'epicredvote', 0);
    update_post_meta($wid, 'epicredrank',0);

     $m['message'] = 'Post submitted';
     $m['title'] = $title;
     echo json_encode($m);
     die();
}


#}After theme switch
add_action('after_switch_theme', 'pluginhunt_theme_switch');
function pluginhunt_theme_switch(){
  pluginhunt_welcome_wizard();
  flush_rewrite_rules();
  ph_create_tables();
}

function pluginhunt_welcome_wizard() {
  $loc = PLUGINHUNT_URL .'/welcome-to-pluginhunt/';
  wp_safe_redirect( $loc );
}
function ph_create_tables(){
   global $wpdb;
   $table_name = $wpdb->prefix . "ph_follows";
      
   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    follower mediumint(9) NOT NULL,
    followed mediumint(9) NOT NULL,
    UNIQUE KEY id (id)
      );";

     require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
     dbDelta($sql);
}
add_action( 'after_setup_theme', 'ph_theme_support' );
function ph_theme_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support('html5', array('search-form'));
    add_theme_support('post-thumbnails');
    add_theme_support( 'automatic-feed-links' );
}

$optionsfile = locate_template( 'options.php' );
load_template( $optionsfile );
function optionsframework_custom_scripts() { ?>
  <script type="text/javascript">
  jQuery(document).ready(function() {
    
    jQuery('#mailchimp_showhidden').click(function() {
    });                  

    jQuery("#epic-form-submit-button" ).click(function() {
        console.log('button clicked to submit');
        jQuery( "#epic_options_form" ).submit();
    });

    jQuery('.nav-tab-epic').click(function(e){

      e.preventDefault();
      var ph_settings_tab = jQuery(this).data('tab');

      jQuery('.group').removeClass('active').addClass('hide');
      jQuery('#'+ ph_settings_tab).removeClass('hide').addClass('active');

      if(jQuery(this).hasClass('active')){
        return false;
      }else{
        jQuery('.nav-tab-epic').removeClass('active');
        jQuery(this).addClass('active');
      }
    });

    if (jQuery('#example_showhidden:checked').val() !== undefined) {
      jQuery('#section-example_text_hidden').show();
    }

  });
  </script>
<?php }



remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'ph_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'ph_theme_wrapper_end', 10);

function ph_theme_wrapper_start() {
  echo '<div class="container"><div class="col-md-12">';
}

function ph_theme_wrapper_end() {
  echo '</div></div>';
}

/* 
LETS MAKE ACCESSING VOTING + SCORING A BIT EASIER
*/

function ph_output_voting($ID){
    global $wp_query,$post,$wpdb, $current_user,$query_string;
    wp_get_current_user();
    $wpdb->ph_votes   = $wpdb->prefix . 'epicred';

      $postvote = get_post_meta($ID, 'epicredvote' ,true);
      if($postvote == NULL){
        $postvote = 0;
      }
      
      $fid = $current_user->ID;
  
      $query = "SELECT epicred_option FROM $wpdb->ph_votes WHERE epicred_ip = $fid AND epicred_id = $ID";
      $al = $wpdb->get_var($query);
      $c = "none";
      
      if($al == NULL){
        $al = 0;
        $redclassu = 'up';
        $redclassd = 'down';
        $redscore = "unvoted";
        $c = "none norm";
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
      ob_start();
      ?>

      <div class = "reddit-voting <?php echo $c; ?> reddit-voting-<?php echo $ID; ?>">
          <div class="arrow fa fa-caret-up  fa-2x arrow-up-<?php echo $ID;?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>" data-red-id = "<?php echo $ID;?>"></div>
          <div class="score score-<?php echo $ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
      </div>

  <?php 
    return ob_get_clean();
}


/**
 * https://core.trac.wordpress.org/ticket/34737
 */
add_action('init', function() {
 
    $endpoints = array(
        '#https?://www\.facebook\.com/video.php.*#i'          => 'https://www.facebook.com/plugins/video/oembed.json/',
        '#https?://www\.facebook\.com/.*/videos/.*#i'         => 'https://www.facebook.com/plugins/video/oembed.json/',
        '#https?://www\.facebook\.com/.*/posts/.*#i'          => 'https://www.facebook.com/plugins/post/oembed.json/',
        '#https?://www\.facebook\.com/.*/activity/.*#i'       => 'https://www.facebook.com/plugins/post/oembed.json/',
        '#https?://www\.facebook\.com/photo(s/|.php).*#i'     => 'https://www.facebook.com/plugins/post/oembed.json/',
        '#https?://www\.facebook\.com/permalink.php.*#i'      => 'https://www.facebook.com/plugins/post/oembed.json/',
        '#https?://www\.facebook\.com/media/.*#i'             => 'https://www.facebook.com/plugins/post/oembed.json/',
        '#https?://www\.facebook\.com/questions/.*#i'         => 'https://www.facebook.com/plugins/post/oembed.json/',
        '#https?://www\.facebook\.com/notes/.*#i'             => 'https://www.facebook.com/plugins/post/oembed.json/'
    );
 
    foreach($endpoints as $pattern => $endpoint) {
        wp_oembed_add_provider( $pattern, $endpoint, true );
    }
});



function phsearchfilter($query) {
  if ($query->is_search && !is_admin() ) {
    $query->set('post_type',array('post','product','discussion'));
  }
  return $query;
}
add_filter('pre_get_posts','phsearchfilter');

function pluginhunt_admin_menu() {
    global $pluginhunt_slugs,$pluginhunt_menu, $envato_auto_affiliate_slugs; #} Req
    $pluginhunt_menu          = add_menu_page( 'Plugin Hunt', 'Plugin Hunt' , 'manage_options', $pluginhunt_slugs['home'], 'pluginhunt_home', get_template_directory_uri() . '/images/ph-icon.png','2.1');
    $pluginhunt_waiting       = add_submenu_page( $pluginhunt_slugs['home'], 'Waiting List', 'Waiting List', 'manage_options', $pluginhunt_slugs['waiting'] , 'pluginhunt_render_list_page' );



    do_action('ph_menu_extend');
    add_action( "load-$pluginhunt_waiting", 'ph_add_admin_scripts' );
    add_action( "load-$pluginhunt_menu", 'ph_add_admin_scripts' );
}
function ph_add_admin_scripts() {
  wp_enqueue_style('boot-admin', get_template_directory_uri() . '/css/bootstrap.css' );
  wp_register_script( 'pluginhunt-admin', get_template_directory_uri() . '/js/pluginhunt-admin.js', array( 'jquery' ) ); 
  wp_enqueue_script( 'pluginhunt-admin' );
}

function pluginhunt_feedCache(){
  return 86400; 
}


function pluginhunt_guides(){
#} Retrieves updated news.
?>
<style>
body{
  background:white;
}
.theme-guides li{
  margin: 10px;
  background: white;
  padding: 10px;
  font-size: 15px;
  border: 2px solid #ddd;
}
.intro{
  background:white;
  color: black;
  padding: 10px;
}
.intro p{
  font-size: 16px;
}
</style>
<?php
      global $pluginhunt_slugs;
                include_once(ABSPATH . WPINC . '/feed.php');
                add_filter( 'wp_feed_cache_transient_lifetime' , 'pluginhunt_feedCache' );
                $rss = fetch_feed($pluginhunt_slugs['guide_url']);
                remove_filter( 'wp_feed_cache_transient_lifetime' , 'pluginhunt_feedCache' );
                
                if (!is_wp_error( $rss ) ) {
          
                    $maxitems = $rss->get_item_quantity(5); 
                    $rss_items = $rss->get_items(0, $maxitems); 
          
        } ?>
        <div class='intro'>
          <h1>Welcome to our Plugin Hunt Theme Guides</h1>
          <p>
            We are putting together detailed guides all the time covering common areas that people are using the Plugin Hunt Theme for. Follow these guides. Make sure you get the most out of your Plugin Hunt Theme.
          </p>
          </div>
                <ul class='theme-guides'>
                    <?php 
          if ($maxitems == 0) 
            echo '<li>No News (is this good news?)</li>';
                    else 
            foreach ( $rss_items as $item ) : ?>
                    <li>
                        <a href='<?php echo esc_url( $item->get_permalink() ); ?>' target = '_blank'
                        title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>'>
                        <?php echo  $item->get_title() ; ?></a><br/>
                        <?php echo  $item->get_description() ; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                
                <?php
  
}


function pluginhunt_addons(){
  ?>
  <style>
    .ph-addon .ph-img img{
        width:350px;
        float:left;
        margin-right:15px;
    }
    .ph-addon-list .phclear
        clear:both;
    }
    .ph-des ul{
      list-style: disc !important;
    }
    .ph-addon-list .ph-title{
      font-size:15px;
      font-weight:900;
      margin-bottom:5px;
    }
    .ph-addon-list{
      margin-top:20px;
      background:white;
      box-shadow:0px 0px 1px 0px rgba(0,0,0,0.75);
      padding:10px;
      width:96%;
    }
    .ph-addon-list h3{
      margin-top:0px;
    }
    .ph-addon-list .ph-addon{
        border-bottom:1px solid #eee;
        padding-bottom:10px;
    }
    .ph-img img{
      border: 1px solid #ddd;
    }
    .ph-addon{
      float:left;
      margin-bottom:20px;
    }
    .ph-title{
      font-weight:900;
      font-size:20px;
      margin-bottom:10px;
      margin-top:10px;
    }
    hr{
      color:black;
    }
    .intro p{
      background: white;
      padding: 20px;
      margin-bottom:10px;
      font-size: 18px;
    }
    .intro p strong{
      color: red;
      font-weight:900;
    }
  </style>

  <div class='container'>
    <div class='row'>
      <h1><?php _e('Plugin Hunt Theme Official Add ons'); ?></h1>

      <div class='intro'>
      <p>Don't forget. You can get a 50% discount off any of the add ons by using the Coupon Code <strong>pluginhunt30</strong> at checkout.</p>
      </div>

        <div class='ph-addon'>
            <div class='ph-img'>
                <img src='<?php echo PLUGINHUNT_IMAGES .'/onboard-me-add-on.png'; ?>'/>
            </div>
            <div class='ph-des'>
            <div class='ph-title'><?php _e('Onboard Me - $19.99', 'pluginhunt'); ?></div>
              Onboard Me is a Plugin Hunt Theme Add On to help get your website visitors used to your website
              <ul class='ph-listing-list' style='list-style:disc;margin-left:380px'>
                <li>Give your users a tour</li>
                <li>Add steps to the tour. Linked to a class or ID of your choice</li>
                <li>Increase sign ups</li>
                <li>Increase retention</li>
              </ul>  
            </div>
            <div class='ph-buy'>
                <a href='https://epicplugins.com/product/notify-me-plugin/' target='_blank' class='button-primary'>Buy Now</a>
            </div>
        </div>


        <hr/>
        <div class='phclear'></div>
        <div class='ph-addon'>
            <div class='ph-img'>
                <img src='<?php echo PLUGINHUNT_IMAGES .'/notify-me-add-on.png'; ?>'/>
            </div>
            <div class='ph-des'>
            <div class='ph-title'><?php _e('Notify Me - $29.99', 'pluginhunt'); ?></div>
              Notify Me adds a notification system to the Plugin Hunt Theme. Using this add on you and your website users can be notifed when:
              <ul class='ph-listing-list' style='list-style:disc;margin-left:380px'>
                <li>Your product is upvoted</li>
                <li>Someone comments on your product</li>
                <li>You are followed</li>
                <li>Someone you follow posts a new product</li>
              </ul>  
            </div>
            <div class='ph-buy'>
                <a href='https://epicplugins.com/product/notify-me-plugin/' target='_blank' class='button-primary'>Buy Now</a>
            </div>
        </div>


        <hr/>
        <br/>
        <div class='phclear'></div>


        <div class='ph-addon'>
            <div class='ph-img'>
                <img src='<?php echo PLUGINHUNT_IMAGES .'/popular-this-add-on.png'; ?>'/>
            </div>
            <div class='ph-des'>
            <div class='ph-title'><?php _e('Popular This - $20', 'pluginhunt'); ?></div>
              The 'Popular This..' plugin adds an additional post listing above the first day of posts to show you which posts have been popular over the 
              last [period]. Keep your old posts a bit fresher with this add on. The period can be set to be the following:
              <ul class='ph-listing-list' style='list-style:disc;margin-left:380px'>
                <li>Last week</li>
                <li>Last month</li>
                <li>Last year</li>
                <li>All time</li>
              </ul>  
            </div>
            <div class='ph-buy'>
                <a href='https://epicplugins.com/checkout-2/?add-to-cart=17412' target='_blank' class='button-primary'>Buy Now</a>
            </div>
        </div>
        <div class='phclear'></div>
    </div>
  </div>


  <?php
}
function outputmedia_pop(){ 

  ob_start();

    ?>
    <div class="ph_popover_media v-bottom-center">
  <div class="add-product-link-popover">
    <div class="popover--header">
      <h3 class="popover--header--title"><?php _e('Add product media','pluginhunt'); ?></h3>
      <div class="popover--header--description">
        <?php _e('Enter the URL of an image or YouTube video:','pluginhunt'); ?>
      </div>
    </div>
    <div class='img-prev'></div>
    <div class='popoer--footer'>
    <form id='popover--simple-form--input' class='popover--simple-form'>
    <input class="form--input popover--simple-form--input" name="media_url" placeholder="http://" type="text" id="media_url">
    <div class="urlerror"> </div>
    <div class="popover--simple-form--actions">
      <input type="submit" name="commit" value="<?php _e('Add media','pluginhunt');?>" class="button v-green" data-disable-with="Saving...">
    </div>
  </form>
  </div>

  </div>

  </div>
  <?php 

  return ob_get_clean();
}
function outputmedia_pop_sin(){ 
  ob_start();
    ?>
    <div class="ph_popover_media v-bottom-center media_sin">
  <div class="add-product-link-popover">
    <div class="popover--header">
      <h3 class="popover--header--title"><?php _e('Add product media','pluginhunt'); ?></h3>
      <div class="popover--header--description">
        <?php _e('Enter the URL of an image or YouTube video:','pluginhunt'); ?>
      </div>
    </div>
    <div class='img-prev'></div>
    <div class='popoer--footer'>
    <form id='popover--simple-form--input' class='popover--simple-form'>
    <input class="form--input popover--simple-form--input" name="media_url" placeholder="http://" type="text" id="media_url">
    <div class="urlerror"> </div>
    <div class="popover--simple-form--actions">
      <input type="submit" name="commit" value="<?php _e('Add media','pluginhunt');?>" class="button v-green" data-disable-with="Saving...">
    </div>
  </form>
  </div>

  </div>

  </div>
  <?php 
  return ob_get_clean();
}
function pluginhunt_home(){
  global $ph_version;
  ?>
  <style>
    .home{
      text-align:center;
    }
    .home img{
      width: 100px;
      margin-top: 30px;
      margin-bottom: -15px;
    }
    .version{
      background-color: #f2f2f2;
      font-size: 16px;
      position: absolute;
      padding: 5px;
      border-radius: 35%;
      margin-left: 5px;
      margin-top: -6px;
    }
    .main{
      text-align:center;
    }
    .main p{
      margin-top:10px;
      font-size:16px;
    }
    .btn{
      margin-top:10px;
    }
    .button-hunt{
      margin-top:50px !important;
    }
    .logo{
      text-align:center;
    }
    .logo img{
      width:70px;
      margin-top:20px;
    }
    </style>
          <div class="logo"><img src="<?php echo get_template_directory_uri() . "/images/plugin-hunt.png"; ?>"/></div>
  <?php
  // home page for plugin hunt system admin management. 
  echo "<div class='home'><h1>Plugin Hunt WordPress Theme <span class='version'>" . $ph_version . "</span></h1></div>";
  ?>
  <div class='main'>
      <p><?php _e("Welcome to the Plugin Hunt WordPress Theme. This theme gives you out of the box functionailty to run a website just like Product Hunt","pluginhunt"); ?></p>
      <p><?php _e("Using the theme is super simple, but if you get stuck the documentation is below","pluginhunt"); ?>
      <?php $docs = "https://epicthemes.com/forums/forum/plugin-hunt-theme/"; ?>
      <br/>

  </div>

  <?php  
}

#} filter the code for the media uploader
add_filter('media_upload_default_tab', 'ph_switch_tab');
function ph_switch_tab($tab){
  if(!is_admin()){
    return 'type';
  }
}
function phthumbnail($postID){
  $phfi = get_post_meta($postID,'phfeaturedimage',true);
     ob_start();
    if ( has_post_thumbnail() ) {   ?>
        <div class='ph-list-thumbnail'>
        <?php the_post_thumbnail('small'); ?>
        </div>
    <?php }elseif($phfi != ''){ ?>    
      <div class='ph-list-thumbnail'>
          <img src="<?php echo $phfi;?>" class="attachment-small size-small wp-post-image">
      </div>
    <?php }
    return ob_get_clean();
}
function phthumbnaildiscuss($postID){
  $phfi = get_post_meta($postID,'phfeaturedimage',true);
     ob_start();
    if ( has_post_thumbnail() ) {   ?>
        <div class='ph-list-thumbnail'>
        <?php the_post_thumbnail('thumbnail'); ?>
        </div>
    <?php }elseif($phfi != ''){ ?>    
      <div class='ph-list-thumbnail'>
          <img src="<?php echo $phfi;?>" class="attachment-small size-small wp-post-image">
      </div>
    <?php }
    return ob_get_clean();
}
function phgetPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return '0';
    }
    return $count;
}
function phsetPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function pluginhunt_add_to_collection_layout_2($pid){
  if(!ph_in_user_collection($pid)) { ?>
    <div class='ph-collect ph-action' data-pid ='<?php echo $pid;?>'>
      <span class="collect-button--icon" data-pid ='<?php echo $pid;?>'>
      <svg width="12" height="11" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg">
      <path d="M13 10V8.99c0-.54-.448-.99-1-.99-.556 0-1 .444-1 .99V10H9.99c-.54 0-.99.448-.99 1 0 .556.444 1 .99 1H11v1.01c0 .54.448.99 1 .99.556 0 1-.444 1-.99V12h1.01c.54 0 .99-.448.99-1 0-.556-.444-1-.99-1H13zM0 1c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 2 0 1.556 0 1zm0 5c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 7 0 6.556 0 6zm0 5c0-.552.453-1 .997-1h6.006c.55 0 .997.444.997 1 0 .552-.453 1-.997 1H.997C.447 12 0 11.556 0 11z" fill="#C8C0B1" fill-rule="evenodd"></path>
      </svg>
      <span class='ph-save'> <?php _e('Save','pluginhunt'); ?> </span>
  <?php }else{ ?>
    <div class='ph-collect ph-collect-in in ph-action' data-pid ='<?php echo $pid;?>'>
      <span class="collect-button--icon in" data-pid ='<?php echo $pid;?>'>
      <svg width="12" height="11" viewBox="0 0 17 14" xmlns="http://www.w3.org/2000/svg">
      <path d="M11.036 10.864L9.62 9.45c-.392-.394-1.022-.39-1.413 0-.393.393-.39 1.023 0 1.414l2.122 2.12c.193.198.45.295.703.295.256 0 .51-.1.706-.295l4.246-4.246c.385-.385.39-1.02-.002-1.413-.393-.393-1.022-.39-1.412-.002l-3.537 3.538zM0 1c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h4.5c.552 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.552 0-1-.444-1-1z" fill="#DC5425" fill-rule="evenodd"></path>
      </svg>
      <span class='ph-save ph-saved'> <?php _e('Save','pluginhunt'); ?> </span>
  <?php } ?>
  </div>

<?php }


// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


function ph_avatar($ID){
    $ava = get_user_meta($ID,'pluginhunt_profile_picture',true);
    if($ava != ''){
      $url = esc_url($ava);
    }else{
      $url = esc_url(get_avatar_url($ID));
    }
    return $url;
}


#} New code for adding 'user tagline'...

add_action( 'show_user_profile', 'ph_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'ph_extra_user_profile_fields' );
function ph_extra_user_profile_fields( $user ) {
  ?>
  <h3><?php _e("Your tagline", "pluginhunt"); ?></h3>
  <table class="form-table">
    <tr>
      <td>
        <input type="text" name="ph_utag" id="ph_utag" class="regular-text" 
            value="<?php echo esc_attr( get_the_author_meta( 'ph_utag', $user->ID ) ); ?>" maxlength="100" /><br />
        <span class="description"><?php _e("Enter your tagline. This will be shown next to comments. Keep it brief."); ?></span>
    </td>
    </tr>
  </table>
  <?php
}

add_action( 'personal_options_update', 'ph_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'ph_save_extra_user_profile_fields' );
function ph_save_extra_user_profile_fields( $user_id ) {
  $saved = false;
  if ( current_user_can( 'edit_user', $user_id ) ) {
    update_user_meta( $user_id, 'ph_utag', $_POST['ph_utag'] );
    $saved = true;
  }
  return true;
}

/*
add_action( 'wp_ajax_hackers_vote_sub', 'hackers_vote_sub' );
function hackers_vote_sub(){

  write_log("testing this function...");

  $res['messaage'] = 'We got here.. thank you...';
  echo json_encode($res);
  die();  
}
*/


add_action( 'wp_ajax_hackers_vote', 'hackers_vote' );
function hackers_vote(){
  // check_ajax_referer('ph_security_key_95525', 'security');  //nonce sir...
  global $wp_query,$wpdb;

  $wpdb->myo_ip   = $wpdb->prefix . 'epicred';
  $id             = (int)$_POST['id'];  //the post we are voting on...
  $v              =  $_POST['vote'];
  $uid            = get_current_user_id();

  $query = $wpdb->prepare("SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = %d AND epicred_id = %d", $uid,$id);
  $al = $wpdb->get_var($query);
      if($al == NULL){
          $q = $wpdb->prepare("INSERT INTO $wpdb->myo_ip (epicred_id, epicred_option, epicred_ip) VALUES (%d, 1, %d)",$id,$uid);
          $wpdb->query($q);
          $votes = get_post_meta($id, 'epicredvote' ,true);
          if($votes == ''){
            update_post_meta($id,'epicredvote', 1);
          }else{
            $v = $votes + 1;
            update_post_meta($id,'epicredvote', $v);  
          }
      //notification core
      do_action('epic_notify_me_vote', $uid, $id);          
      }else{
        if($v == 'u'){
          $q = $wpdb->prepare("UPDATE $wpdb->myo_ip SET epicred_option = 1 WHERE epicred_id = %d AND epicred_ip = %d",$id,$uid);
          $wpdb->query($q);
          $votes = get_post_meta($id, 'epicredvote' ,true) + 1;
          update_post_meta($id,'epicredvote', $votes);
        }else if($v == 'd'){
          $q = $wpdb->prepare("UPDATE $wpdb->myo_ip SET epicred_option = 0 WHERE epicred_id = %d AND epicred_ip = %d",$id,$uid);
          $wpdb->query($q);
          $votes = get_post_meta($id, 'epicredvote' ,true) - 1;
          update_post_meta($id,'epicredvote', $votes);
        }
      }
  $r['m'] = 'success';
  echo  json_encode($r);
  die();  
}

/****************************
* Slider ShortCode          *
****************************/
function ph_slider(){
	$shortCode = of_get_option('ph_slider_shortcode');
	write_log($shortCode);
	$html = '<div id="ph_slider_div" class="ph_slider">' . do_shortcode($shortCode) . '</div>';
	
	return $html;
}

/****************************
* MAIL CHIMP AJAX API Stuff *
****************************/

  use DrewM\MailChimp\MailChimp;
  function ph_mailchimpsubscribe(){
  	check_ajax_referer( 'ph_security_key_95525', 'checksum' );
  	$version = explode('.',phpversion());
  	$minVersion = 5 * 1000 + 3 * 100 + 0;
  	if($version){
  		$versionNumber = $version[0] * 1000 + $version[1] * 100 + $version[2] * 1; 
  	} else {
  		$versionNumber = 0;
  	}
  	if($versionNumber < $minVersion){
  	$response['msg'] = "Unable to subscribe user. PHP Version: " . phpversion() . " | minVersion: " . $minversion;
  		write_log("You are running version: " . phpversion() . ".  You are required to be on version: 5.3 or higher to use subscription.");
  		echo json_encode($response);
  		die();
  	}
  	
  	$mailChimpAPIKey = of_get_option("mailchimp_apikey_hidden");
  	$mailChimpListID = of_get_option("mailchimp_listid_hidden");
  	$uid = get_current_user_id();

  	write_log('API:'.$mailChimpAPIKey.'| ListID:'. $mailChimpListID);
  	
  	$email = $_POST["email"];
  	write_log('email:'.$email);
  	
  	if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
  		$response['msg'] = "Email is not in correct format";
  		echo json_encode($response);
  		die();
  	}
  	
  	$MailChimp = new MailChimp($mailChimpAPIKey);
  	if($MailChimp == null || empty($mailChimpAPIKey) || empty($mailChimpListID)){
  		$response['msg'] = "Subscribe failed";
  		echo json_encode($response);
  		die();
  	}
  	
  	$result = $MailChimp->post("lists/".$mailChimpListID."/members", array(
  				'email_address' => $email,
  				'status'        => 'subscribed',
  			));
  	if(!$MailChimp->getLastError()){
  		$response['msg'] = "Success: You have been successfully subscribed";
  		//If the UID is set then update their metadata to add in that they've subscribed.
  		//Otherwise set a cookie
  		if($uid > 0){
  			update_user_meta($uid,'ph_mailchimp_subscribed','1');
  		}else {
  			setcookie('ph_mailchimp_subscribed','1',365*DAYS_IN_SECONDS,COOKIEPATH,COOKIE_DOMAIN);
  		}
  	}else{
  		$response['msg'] = "Subscribe Failed.";
  	}
  	$response['errors'] = $MailChimp->getLastError();
  	$response['response'] = $MailChimp->getLastResponse();
  	$response['request'] = $MailChimp->getLastRequest();
  	echo json_encode($response);
  	die();
  }

  function UserSubscribed(){
  	$uid = get_current_user_id();
  	$subscribed = 0;
  	if($uid == 0 && isset($_COOKIE['ph_mailchimp_subscribed'])){
  		$subscribed =  $_COOKIE['ph_mailchimp_subscribed'];
  	} elseif($uid > 0){
  		$subscribed = get_user_meta($uid,'ph_mailchimp_subscribed',true);
  	}
  	$result = (isset($subscribed) && $subscribed == 1);
  	return $result;
  }


add_action( 'init', 'ph_add_excerpts_to_pages' );
function ph_add_excerpts_to_pages(){
    add_post_type_support( 'page', 'excerpt' );
}


add_filter( 'body_class', 'no_ph_invited' );
function no_ph_invited( $classes ) {
  if(is_user_logged_in()){ 
      if(current_user_can( 'edit_posts' )){ 
        $classes[] = 'ph_invited monkey_dust';
      }else{
        $classes[] = 'no_ph_invited';
      }
  }else{
    $classes[] = 'no_ph_invited';
    $classes[] = 'ph_logged_out';
  }

  if(of_get_option('ph_layout_style') == 'index-2' && !is_single() && !is_page()){
       $classes[] = 'ph-layout-2-body';
  }

  if(of_get_option('ph_layout_style') == 'index-3'){
    $classes[] = 'index-3';
  }

  if(of_get_option('ph_layout_style') == 'index-4'){
    $classes[] = 'index-4';
  }


  if(of_get_option('ph_layout_style') == 'index-2' || of_get_option('ph_layout_style') == 'index-4' || of_get_option('ph_layout_style') == 'index-3'){
      $classes[] = 'ph-layout-2-single';
  }else{
      $classes[] = 'ph-layout-1-body';
  }

  if(is_search()){
    $classes[] = 'woocommerce';
  }


  return $classes;
}

add_filter('body_class','ph_mobile');
function ph_mobile($classes){
  if(wp_is_mobile()){
    $classes[] = 'ph_mobile';
  }
  return $classes;
}

add_filter('body_class','ph_grid_layout');
function ph_grid_layout( $classes ){
  if( of_get_option('ph_grid_on') == 1 && !is_post_type_archive('discussions')) { 
    $classes[] = 'grid_layout';
  }else{
    $classes[] = 'nogrid';
  }
  return $classes;
}
function ajaxcomments_load_js() {
  wp_enqueue_script('ajaxcomments', get_template_directory_uri() . "/js/ajaxcomments.js", array('jquery'));
}
function ajaxify_comments_jaya($comment_ID, $comment_status) {

    global $post;

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    //If AJAX Request Then
    switch ($comment_status) {
    case '0':
    //notify moderator of unapproved comment
    wp_notify_moderator($comment_ID);
    case '1': //Approved comment
    // echo "success";
    $commentdata = get_comment($comment_ID, ARRAY_A);
    //print_r( $commentdata);


    if($commentdata['comment_parent'] == 0){
    $output = '<article class="comment byuser comment-author-mike bypostauthor even depth-2" id="comment-' . $commentdata['comment_ID'] . '" itemprop="comment" itemscope="" itemtype="http://schema.org/Comment"><div class="ph-comment-row">
            <figure class="gravatar">'.get_avatar($commentdata['comment_author_email'],30).'</figure>
            <div class="comment-meta post-meta" role="complementary">
            <span class="comment-author">'.$commentdata['comment_author'].'</span><span class="ph-comment-info"> -' . get_the_author_meta('description',$commentdata['user_id']) .'</span></div>
            <div class="comment-content post-content" itemprop="text">
              <p>'.$commentdata['comment_content'].'</p>
              <div class="ph-comment-meta">
                <div class="pull-left">
                  <span class="ph-m reply-comment" data-cid="'.$commentdata['comment_ID'].'" data-un="'.$commentdata['comment_author'].'"><i class="fa fa-mail-reply" data-cid="398"></i> Reply</span>
                  <span class="ph-m share-comment"><i class="fa  fa-share-square-o"></i><a class="comment-tweet-link" href="https://twitter.com/intent/tweet?text=" title="share">share</a></span>
                </div>
                <div style="clear:both"></div>
              </div>
            </div>
          </div>
          </article>';

    echo $output;

    }
    else{
    //if a reply
    $output = '<article class="comment byuser comment-author-mike bypostauthor even depth-2" id="comment-' . $commentdata['comment_ID'] . '" itemprop="comment" itemscope="" itemtype="http://schema.org/Comment"><div class="ph-comment-row">
            <figure class="gravatar">'.get_avatar($commentdata['comment_author_email'],30).'</figure>
            <div class="comment-meta post-meta" role="complementary">
            <span class="comment-author">'.$commentdata['comment_author'].'</span><span class="ph-comment-info"> -' . get_the_author_meta('description',$commentdata['user_id']) .'</span></div>
            <div class="comment-content post-content" itemprop="text">
              <p>'.$commentdata['comment_content'].'</p>
              <div class="ph-comment-meta">
                <div class="pull-left">
                  <span class="ph-m reply-comment" data-cid="'.$commentdata['comment_ID'].'" data-un="'.$commentdata['comment_author'].'"><i class="fa fa-mail-reply" data-cid="398"></i> Reply</span>
                  <span class="ph-m share-comment"><i class="fa  fa-share-square-o"></i><a class="comment-tweet-link" href="https://twitter.com/intent/tweet?text=" title="share">share</a></span>
                </div>
                <div style="clear:both"></div>
              </div>
            </div>
          </div>
          </article>';
    echo $output;
    }

    /*
    <span class="ph-m upvote-comment" data-cid="'.$commentdata['comment_ID'].'"><span class="ph-up-adj"><i class="fa fa-sort-up"></i></span> upvote </span>
    */

    $post = get_post($commentdata['comment_post_ID']);
    wp_notify_postauthor($comment_ID);
    break;
    default:
    echo "error";
    }
    exit;
    }
}
add_action('comment_post', 'ajaxify_comments_jaya', 25, 2);

/** COMMENTS WALKER */
class ph_comment_walker extends Walker_Comment {
    var $tree_type = 'comment';
    var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );
 
    // constructor – wrapper for the comments list
    function __construct() { ?>

      <section class="comments-list">

    <?php }

    // start_lvl – wrapper for child comments list
    function start_lvl( &$output, $depth = 0, $args = array() ) {
      $GLOBALS['comment_depth'] = $depth + 2; ?>
      
      <section class="child-comments comments-list">

    <?php }
  
    // end_lvl – closing wrapper for child comments list
    function end_lvl( &$output, $depth = 0, $args = array() ) {
      $GLOBALS['comment_depth'] = $depth + 2; ?>

      </section>

    <?php }

    // start_el – HTML for comment template
    function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
      $depth++;
      $GLOBALS['comment_depth'] = $depth;
      $GLOBALS['comment'] = $comment;
      $parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' ); 
  
      if ( 'article' == $args['style'] ) {
        $tag = 'article';
        $add_below = 'comment';
      } else {
        $tag = 'article';
        $add_below = 'comment';
      } ?>

      <article <?php comment_class(empty( $args['has_children'] ) ? '' :'parent') ?> id="comment-<?php comment_ID() ?>" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
        <div class='ph-comment-row'>
        <figure class="gravatar"><?php echo get_avatar( $comment, 30 ); ?></figure>
        <div class="comment-meta post-meta" role="complementary">
          <?php 
          $em =  get_comment_author_email();
          $the_user = get_user_by('email', $em);
          $the_user->ID;
          $username = $the_user->user_nicename;
          $text = wp_trim_words($comment->comment_content,'5');
          $replyuri = home_url('/author/');
          $content = preg_replace('/\B\@([a-zA-Z0-9_]{1,20})/', '<a class="at" href="'.$replyuri.'$1">$0</a>', $comment->comment_content);
          $title = get_the_title($comment->comment_post_ID);
          $perma = get_comment_link($comment);
      //    $perma = get_permalink($comment->comment_post_ID);
          $share = urlencode($username . "'s thoughts on ") . $title . " " . $text;


          ?>
          <span class="comment-author"><?php comment_author(); ?></span><span class='ph-comment-info'> - <?php echo get_the_author_meta('description',$the_user->ID); ?></span>

          <?php // edit_comment_link('<p class="comment-meta-item">Edit this comment</p>','',''); ?>
          <?php if ($comment->comment_approved == '0') : ?>
          <p class="comment-meta-item"><?php _e('Your comment is awaiting moderation.','pluginhunt'); ?></p>
          <?php endif; ?>
        </div>
        <div class="comment-content post-content" itemprop="text">
          <?php echo $content ?>
          <div class='ph-comment-meta'>
            <div class='pull-left'>
              <!--
              <span class='ph-m upvote-comment' data-cid='<?php comment_ID();?>'><span class='ph-up-adj'><i class='fa fa-sort-up'></i></span> upvote </span>
            -->
              <span class='ph-m reply-comment' data-cid='<?php comment_ID(); ?>' data-un='<?php echo $username ; ?>'><i class='fa fa-mail-reply' data-cid='<?php comment_ID(); ?>'></i><?php _e(' Reply','pluginhunt'); ?></span>
              <span class='ph-m share-comment'><i class='fa  fa-twitter'></i><a class='comment-tweet-link' href='https://twitter.com/intent/tweet?text=<?php echo $share; ?>&amp;url=<?php echo urlencode( $perma ); ?>' title=share><?php _e('tweet','pluginhunt');?></a></span>
            </div>
            <div class='pull-right'>
              <span class="ph-m comment-meta-item ph-time-comment" datetime="<?php comment_date('Y-m-d') ?>T<?php comment_time('H:iP') ?>" itemprop="datePublished"><?php printf( _x( '%s ago', '%s = human-readable time difference', 'pluginhunt' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></time>
            </div>
            <div style='clear:both'></div>
          </div>
        </div>

      </div>

    <?php }

    // end_el – closing HTML for comment template
    function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>

      </article>

    <?php }

    // destructor – closing wrapper for the comments list
    function __destruct() { ?>

      </section>
    
    <?php }
}

#} Version 3.6 comes with collections. Managed as custom posts.
function ph_collections_init() {
  $labels = array(
    'name'               => _x( 'Collections', 'post type general name', 'pluginhunt' ),
    'singular_name'      => _x( 'Collection', 'post type singular name', 'pluginhunt' ),
    'menu_name'          => _x( 'Collections', 'admin menu', 'pluginhunt' ),
    'name_admin_bar'     => _x( 'Collection', 'add new on admin bar', 'pluginhunt' ),
    'add_new'            => _x( 'Add New', 'blog', 'pluginhunt' ),
    'add_new_item'       => __( 'Add New Collection', 'pluginhunt' ),
    'new_item'           => __( 'New Collection', 'pluginhunt' ),
    'edit_item'          => __( 'Edit Collection', 'pluginhunt' ),
    'view_item'          => __( 'View Collection', 'pluginhunt' ),
    'all_items'          => __( 'All Collections', 'pluginhunt' ),
    'search_items'       => __( 'Search Collection', 'pluginhunt' ),
    'parent_item_colon'  => __( 'Parent Collection:',  'pluginhunt' ),
    'not_found'          => __( 'No collections found.', 'pluginhunt' ),
    'not_found_in_trash' => __( 'No collections found in Trash.', 'pluginhunt' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'collections' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
    'menu_icon'          => 'dashicons-feedback'
  );

  register_post_type( 'collections', $args );
}
add_action( 'init', 'ph_collections_init' );
function ph_create_collection(){
  //get the current users ID (just to be safe)
  $uid    =   get_current_user_id();
  if($uid == 0){
    die();
  }
  $name   =   $_POST['name'];
  $prod   =   $_POST['prod'];   //product ID for the product
  $pname  =   get_the_title( $prod );
  
  $ptype = 'collections';
  $status = 'publish';

    $post = array(
    'post_title'     => $name, 
    'post_status'    => $status,
    'post_type'      => $ptype,
    'post_author'    => $uid
  );  

    $wid = wp_insert_post( $post, $wp_error );

    add_post_meta($wid,'ph_collected_posts',$prod); //add the product to this users collection (easy for the first product in a new collection).

    $user_collected       = get_user_meta($uid,'ph_collected_posts',true);
    $user_collected_array = explode(",",$user_collected);

    //this time check if the user has collected this anywhere (useful for marking the index)
    if(!in_array($prod,$user_collected_array)){
      array_push($user_collected_array, $prod);
    }else{
      write_log('in the array');
    }

    $user_collected_new = implode(",",$user_collected_array); // implode back to a collection csv meta.
    update_user_meta($uid,'ph_collected_posts',$user_collected_new);

    $response['user'] = $uid;
    $response['name'] = $name;
    $response['collection'] = $wid;
    $response['html'] = '<div class="collections-html">"'.$pname.'" has been added to your collection <a href="'.get_permalink($wid).'">"'.$name.'"</a></div>';

    echo json_encode($response);
    die();
}
function ph_in_user_collection($id){
  $uid = get_current_user_id();
  $uc = get_user_meta($uid,'ph_collected_posts',true);
  $uca = explode(',',$uc);
  if(in_array($id,$uca)){
    return true;
  }else{
    return false;
  }
}
function ph_delete_collection(){
  global $wpdb;
  $uid    =   get_current_user_id();
  $cid   =   (int)$_POST['cid'];   

  if($uid == 0){
    die();
  }

  delete_post_meta($cid,'ph_collected_posts'); //delete the meta.
  $query = $wpdb->prepare("DELETE FROM $wpdb->posts WHERE ID = %d AND post_author = %d AND post_type ='collections'", $cid, $uid);
  $wpdb->query($query);

  //check the user array, loop through existing collections create an array with unique elements and re-save the user meta
  $query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_author = %d AND post_type ='collections'", $uid);
  $collections = $wpdb->get_results($query);

  //total collection array 
  $total_collected = array();
  foreach($collections as $collection){
    $collected = get_post_meta($collection->ID,'ph_collected_posts',true);
    $collected_array = explode(',',$collected);
    $total_collected = array_merge($collected_array,$total_collected);
  } 

  //make array unique and save down into user meta
  $user_collected_array = array_unique($total_collected);
  $user_collected_new = implode(",",$user_collected_array); // implode back to a collection csv meta.
  update_user_meta($uid,'ph_collected_posts',$user_collected_new);

  $response['total'] = $total_collected;
  $response['collected'] = $user_collected_array;
  $response['html'] = 'Completed';
  echo json_encode($response);
  die();
}
add_filter( 'wp_title', 'ph_hack_wp_title_for_home' );
function ph_hack_wp_title_for_home( $title ){
  if( empty( $title ) && ( is_home() || is_front_page() ) ) {
    return get_bloginfo( 'title' ) . ' | ' . get_bloginfo( 'description' );
  }
  return $title;
}
function ph_remove_from_collection(){
  global $wpdb;
  $uid    =   get_current_user_id();
  if($uid == 0){
    die();  //die if not logged in. 
  }
  $pid   =   (int)$_POST['pid'];
  $cid   =   (int)$_POST['cid'];   
  $collected            = get_post_meta($cid,'ph_collected_posts',true);
  $user_collected       = get_user_meta($uid,'ph_collected_posts',true);

  $collected_array      = explode(",",$collected);
  $user_collected_array = explode(",",$user_collected);

  if(($key = array_search($pid, $collected_array)) !== false) {
    unset($collected_array[$key]);    //removes the post from the collected_array();
  }
  $collected_new = implode(",",$collected_array); // implode back to a collection csv meta.
  update_post_meta($cid,'ph_collected_posts',$collected_new);

  //the user array updated from collections
  //check the user array, loop through existing collections create an array with unique elements and re-save the user meta
  $query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_author = %d AND post_type ='collections'", $uid);
  $collections = $wpdb->get_results($query);
  $total_collected = array();
  
  foreach($collections as $collection){
    $collected = get_post_meta($collection->ID,'ph_collected_posts',true);
    $collected_array = explode(',',$collected);
    $total_collected = array_merge($collected_array,$total_collected);
  } 


  //make array unique and save down into user meta
  $user_collected_array = array_unique($total_collected);
  $user_collected_new = implode(",",$user_collected_array); // implode back to a collection csv meta.
  update_user_meta($uid,'ph_collected_posts',$user_collected_new);

  $pname  =   get_the_title($pid);
  $cname  =   get_the_title($cid);
  add_post_meta($wid,'ph_collected_posts',$prod); //add the product to this users collection (easy for the first product in a new collection).
  
  $response['uid'] = $uid;
  $response['collections'] = $collections;
  $response['collected_old'] = $collected;
  $response['collected_new'] = $collected_new;
  $response['collected_total'] = $user_collected_new;

  $response['html'] = '<div class="collections-html">"'.$pname.'" was removed from your collection <a href="'.get_permalink($cid).'">"'.$cname.'"</a></div>';
  echo json_encode($response);
  die();
}

//AJAX function which creates the collection header image as featured...
add_action('wp_ajax_nopriv_ph_update_collection_bgimg','ph_update_collection_bgimg');
add_action( 'wp_ajax_ph_update_collection_bgimg', 'ph_update_collection_bgimg' );
function ph_update_collection_bgimg(){
    $aid      = (int)$_POST['aid'];
    $cid      = (int)$_POST['cid'];

    $uid      = get_current_user_id();
    $post_tmp = get_post($cid);
    $author_id = $post_tmp->post_author;

    if($uid == $author_id){
    set_post_thumbnail($cid, $aid);
    $response['msg']     = 'all OK for the title';
    }else{
      $response['msg'] = 'nope';
      wp_mail('mike@epicplugins.com','someone tried to change image who was not collection owner');
    }

    echo json_encode($response);
    die();
}

//AJAX function which adds media to the post
add_action('wp_ajax_nopriv_ph_newmedia','ph_newmedia');
add_action( 'wp_ajax_ph_newmedia', 'ph_newmedia' );
function ph_newmedia(){
    $pid      = (int)$_POST['pid'];
    $vid      = sanitize_text_field($_POST['vid'] );
    $src      = sanitize_text_field( $_POST['src'] );
    $img      = esc_url($_POST['img']);

    $media = get_post_meta($pid,'phmedia',true);
    $media_array = json_decode($media); 
    if($media_array == ''){
      $media_array = array();
    }
    $item['url']    = esc_url($img);
    $item['source'] = $src;
    $item['id']     = $vid;
    $media_array[] = $item;
    $media = json_encode($media_array);
    $uid      = get_current_user_id();
    $post_tmp = get_post($pid);
    $author_id = $post_tmp->post_author;

    if($uid == $author_id){
      update_post_meta($pid,'phmedia',$media);
      $response['msg']     = 'all OK for the title';
      $response['item'] = $item;
      $response['newmedia'] = $media_array;
      $response['src'] = $src;
    }else{
      $response['msg'] = 'nope';
      wp_mail('mike@epicplugins.com','someone tried to add media to the wrong post');
    }

    echo json_encode($response);
    die();
}




function ph_rsscontent(){
    //if the current user can manage options we are good to go...
    $url = 'http://affirmationpod.libsyn.com/rss';
    $xml = simplexml_load_file($url,'SimpleXMLElement', LIBXML_NOCDATA);
    $i=0;
    foreach($xml->channel->item as $item){
          
          if(!ph_post_exists($item->title)){
            $ph_demo_post = array(
              'post_content'   => (string)$item->description, 
              'post_title'     => (string)$item->title, 
              'post_status'    => 'publish',
              'post_type'      => 'post',
              'post_author'    => 1,
             ); 
            $wid = wp_insert_post( $ph_demo_post, $wp_error );
            $link = esc_url((string)$item->link);
            update_post_meta($wid,'podcast_link', $link);
          }
    }
}
add_shortcode('ph_rsscontent','ph_rsscontent');


function ph_post_exists($title, $content = '', $date = '') {
    global $wpdb;
 
    $post_title = wp_unslash( sanitize_post_field( 'post_title', $title, 0, 'db' ) );
    $post_content = wp_unslash( sanitize_post_field( 'post_content', $content, 0, 'db' ) );
    $post_date = wp_unslash( sanitize_post_field( 'post_date', $date, 0, 'db' ) );
 
    $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
    $args = array();
 
    if ( !empty ( $date ) ) {
        $query .= ' AND post_date = %s';
        $args[] = $post_date;
    }
 
    if ( !empty ( $title ) ) {
        $query .= ' AND post_title = %s';
        $args[] = $post_title;
    }
 
    if ( !empty ( $content ) ) {
        $query .= ' AND post_content = %s';
        $args[] = $post_content;
    }
 
    if ( !empty ( $args ) )
        return (int) $wpdb->get_var( $wpdb->prepare($query, $args) );
 
    return 0;
}

//AJAX function for installing demo content
add_action('wp_ajax_nopriv_ph_democontent','ph_democontent');
add_action( 'wp_ajax_ph_democontent', 'ph_democontent' );
function ph_democontent(){
  check_ajax_referer( 'phdc-ajax-nonce', 'security' );
  if ( current_user_can( 'manage_options' ) ) {
    //if the current user can manage options we are good to go...
    $url = 'http://pluginhunt.com/feed/';
    $xml = simplexml_load_file($url,'SimpleXMLElement', LIBXML_NOCDATA);
    $i=0;
    foreach($xml->channel->item as $item){
          $item->title;
          $ph_demo_post = array(
            'post_content'   => (string)$item->description, 
            'post_title'     => (string)$item->title, 
            'post_status'    => 'publish',
            'post_type'      => 'post',
            'post_author'    => 1,
           ); 
          $wid = wp_insert_post( $ph_demo_post, $wp_error );
          if($i < 5){
            $col[] = $wid;
          }
          $ph_fi = (string)$item->featured;
          update_post_meta($wid,'phfeaturedimage',$ph_fi);   //takes the featured image from the modified RSS.
          $i++;
    }
    $ph_demo_collection = array(
        'post_title' => 'Collection',
        'post_content' => 'The best collection',
        'post_status' => 'publish',
        'post_type' => 'collections',
        'post_author' => 1,
      );
    $wid = wp_insert_post($ph_demo_collection);
    update_post_meta( $wid, 'featured-product', '1');
    $col_csv = implode(',',$col);
    update_post_meta($wid,'ph_collected_posts',$col_csv);
    update_user_meta(1,'ph_collected_posts',$col_csv);

    $r['message'] = 'success';
    $r['success'] = 1;
    echo json_encode($r);
    die();
  }else{
    $r['message'] = 'Unathorised to do this...';
    $r['success'] = 1;
    echo json_encode($r);
    die();
  }
}

add_action('wp_ajax_nopriv_ph_layout_init','ph_layout_init');
add_action( 'wp_ajax_ph_layout_init', 'ph_layout_init' );
function ph_layout_init(){
 //   check_ajax_referer( 'phos-ajax-nonce', 'security' );
    $layout = "index-" . (int)$_POST['layout'];
    of_set_option('ph_layout_style', $layout);

    //check some of the other settings and set them if not already set...
    $logo = of_get_option('main_logo');
    if($logo == ''){
      of_set_option('main_logo','http://pluginhunt.com/wp-content/uploads/2015/01/pluginhunt-e1418168687747.png');
    }
    $logo = of_get_option('white_logo');
    if($logo == ''){
      of_set_option('white_logo','http://pluginhunt.com/wp-content/uploads/2015/01/pluginhunt-e1418168687747.png');
    }
    $r['m'] = 'success';
    echo json_encode($r);
    die();
}

remove_all_actions( 'do_feed_rss2' );
add_action( 'do_feed_rss2', 'pluginhunt_feed_rss', 10, 1 );

function pluginhunt_feed_rss( $for_comments ) {
    $rss_template = get_template_directory() . '/feeds/feed-rss2.php';
    if( file_exists( $rss_template ) )
        load_template( $rss_template );
    else
        do_feed_rss2( $for_comments ); // Call default function
}

//AJAX function which creates the collection
add_action('wp_ajax_nopriv_ph_update_collection_title','ph_update_collection_title');
add_action( 'wp_ajax_ph_update_collection_title', 'ph_update_collection_title' );
function ph_update_collection_title(){
    $slug         = sanitize_text_field($_POST['title']);
    $post_ID      = (int)$_POST['cid'];
    $post_status  = 'publish';
    $post_type    = 'collections';
    $post_parent  = 0;

    $cid = get_current_user_id();
    if($cid == 0){
      die(); //die if logged out (user = 0)
    }

    $new_slug     = wp_unique_post_slug( $slug, $post_ID, $post_status, $post_type, $post_parent );
    write_log('new post slug ' . $new_slug);
    $my_post = array('ID'=> $post_ID,'post_name' => $new_slug,'post_title' => $slug);
    $w = wp_update_post( $my_post);
    $response['msg']     = 'all OK for the title';
    $response['newslug'] = $new_slug;
    $response['title'] = $slug;
    echo json_encode($response);
    die();
}

//AJAX function which creates the collection
add_action('wp_ajax_nopriv_ph_update_collection_desc','ph_update_collection_desc');
add_action( 'wp_ajax_ph_update_collection_desc', 'ph_update_collection_desc' );
function ph_update_collection_desc(){
    $cid = get_current_user_id();
    if($cid == 0){
      die(); //die if logged out (user = 0)
    }
    $slug         = sanitize_text_field($_POST['title']);
    $post_ID      = (int)$_POST['cid'];
    $my_post = array('ID'=> $post_ID, 'post_content' => $slug);
    wp_update_post( $my_post);
    $response['msg'] = 'all OK';
    $response['title'] = $slug;
    $response['ID'] = $post_ID;
    echo json_encode($response);
    die();
}

//MAILCHIMP WIDGET

add_action('widgets_init',
  create_function('', 'return register_widget("MailChimp_Widget");')
);
class MailChimp_Widget extends WP_Widget {
  function __construct() {
    parent::__construct(
      'ph_mailchimp', // Base ID
      __( 'MailChimp Widget', 'pluginhunt' ), // Name
      array( 'description' => __( 'Show the Plugin Hunt Theme Mail Chimp Widget', 'pluginhunt' ), ) // Args
    );
  }
  public function widget( $args, $instance ) {
    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }
       
      $IsSubscribed = UserSubscribed();
    if( of_get_option('mailchimp_showhidden') == 1 && !$IsSubscribed) { ?>
    <div class='mailchimp-sub-widget'>
      <?php 
        $action = of_get_option('mailchimp_action_hidden');
        echo ph_mailchimp($action);
      ?>
    </div>
    <?php } 


    echo $args['after_widget'];
  }
  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Categories', 'pluginhunt' );
  }
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
  }
} 



// Discussion widget for discussions sidebar  5.3+
add_action('widgets_init',
  create_function('', 'return register_widget("Discussion_Widget");')
);
class Discussion_Widget extends WP_Widget {
  function __construct() {
    parent::__construct(
      'ph_discuss', // Base ID
      __( 'Discussion Categories', 'pluginhunt' ), // Name
      array( 'description' => __( 'Discussions Widget', 'pluginhunt' ), ) // Args
    );
  }
  public function widget( $args, $instance ) {
    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }
       
    $args = array( 
    'taxonomy' => 'discussion_category',
    'title_li' => ''
    );

    // We wrap it in unordered list 
    echo '<ul>'; 
    echo wp_list_categories($args);
    echo '</ul></div>';

  }
  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Categories', 'pluginhunt' );
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <?php 
  }
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
  }
} 

add_action('widgets_init',
  create_function('', 'return register_widget("FeaturedCollections_Widget");')
);
class FeaturedCollections_Widget extends WP_Widget {
  function __construct() {
    parent::__construct(
      'ph_feat', // Base ID
      __( 'Featured Collections', 'pluginhunt' ), // Name
      array( 'description' => __( 'Featured Collections', 'pluginhunt' ), ) // Args
    );
  }
  public function widget( $args, $instance ) {
    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }

  global $wpdb;

  $results = $wpdb->get_results("SELECT * from $wpdb->posts WHERE post_type = 'collections' and post_status = 'publish' LIMIT 20");
      ?>
      <div class='collection-splash'>
        <div class='main-content'>
          <div class="rel-collect">
          <?php
              $j=0;

            foreach($results as $r){            
                if(get_post_meta( $r->ID, 'featured-product',true) == 1){
                    $thumb_id = get_post_thumbnail_id($r->ID);
                    if($thumb_id == ''){
                      $thumb_url = 'http://placehold.it/300x240';
                    }else{
                      $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'medium', true);
                      $thumb_url = $thumb_url_array[0];
                    }
                    setup_postdata($r);
                    $link = get_permalink($r->ID);
                    $t = get_the_title($r->ID);
                    ?>
                    <div class='rel-tile'>
                        <a class='collect-link' rel="external" href="<?php echo $link; ?>">
                        <img src ="<?php echo $thumb_url; ?>"/>
                          <div class='col-wrap'>
                            <div class='collect-overlay-new'><?php echo $t; ?></div>
                            <div class='button'><?php _e('View','pluginhunt'); ?></div> 
                          </div>
                        </a>
                    </div>
                    <?php
                }  
          } ?>
          </div></div></div></div>
          <?php

  }
  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Categories', 'pluginhunt' );
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <?php 
  }
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
  }
} 

//AJAX function which creates the collection
add_action('wp_ajax_nopriv_ph_add_collection','ph_add_collection');
add_action( 'wp_ajax_ph_add_collection', 'ph_add_collection' );
function ph_add_collection(){
  $uid    =   get_current_user_id();
  $pid   =   (int)$_POST['pid'];
  $cid   =   (int)$_POST['cid'];  

  if($uid == 0){
    die(); //die if user logged out. 
  }

  $collected            = get_post_meta($cid,'ph_collected_posts',true);
  $user_collected       = get_user_meta($uid,'ph_collected_posts',true);
  $collected_array      = explode(",",$collected);
  $user_collected_array = explode(",",$user_collected);
  if(!in_array($pid,$collected_array)){
    array_push($collected_array, $pid);
  }else{
    write_log('in the array');
  }
  if(!in_array($pid,$user_collected_array)){
    array_push($user_collected_array, $pid);
  }else{
    write_log('in the array');
  }
  $collected_new = implode(",",$collected_array); // implode back to a collection csv meta.
  update_post_meta($cid,'ph_collected_posts',$collected_new);
  $user_collected_new = implode(",",$user_collected_array); // implode back to a collection csv meta.
  update_user_meta($uid,'ph_collected_posts',$user_collected_new);
  $pname  =   get_the_title($pid);
  $cname  =   get_the_title($cid);
  add_post_meta($wid,'ph_collected_posts',$prod); //add the product to this users collection (easy for the first product in a new collection).
  $response['collected_old'] = $collected;
  $response['collected_new'] = $collected_new;
  $response['html'] = '<div class="collections-html">"'.$pname.'" has been added to your collection <a href="'.get_permalink($cid).'">"'.$cname.'"</a></div>';
  echo json_encode($response);
  die();
}

add_action('wp_ajax_nopriv_ph_list_collections','ph_list_collections');
add_action( 'wp_ajax_ph_list_collections', 'ph_list_collections' );
function ph_list_collections(){
     global $wpdb;
    $user = get_current_user_id();

    $post = (int)$_POST['pid'];
    $collectionsHTML = '<ul class="collections-popover--collections popover--scrollable-list">';
    $query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_author = %d AND post_type ='collections'", $user);
    $collections = $wpdb->get_results($query);
    foreach($collections as $collection){
      $collected = get_post_meta($collection->ID,'ph_collected_posts',true);
      $collected_array = explode(',',$collected);
      $in = in_array($post,$collected_array);   //is this post in the collection, if so, show the collected icon
      $cid = $collection->ID;
      if($in){
        $collectionsHTML .='<li><span class="collections-popover--collection ph-remove-from-collection popover--scrollable-list--element" href="#" data-cid="'.$collection->ID.'" data-pid="'.$post.'"><span>' . $collection->post_title . '</span><span class="collections-popover--collection--icon v-collected">';
        $collectionsHTML .= ph_in_collection($post,$cid);
      }else{
        $collectionsHTML .='<li><span class="collections-popover--collection ph-add-to-collection popover--scrollable-list--element" href="#" data-cid="'.$collection->ID.'" data-pid="'.$post.'"><span>' . $collection->post_title . '</span><span class="collections-popover--collection--icon v-collect">';
        $collectionsHTML .= ph_out_collection($post,$cid);
      }
      $collectionsHTML .=  '</span></span></li>';
    }
    $collectionsHTML .= '  </ul>';
    $response['html'] = $collectionsHTML;
    $response['message'] = 'this has reached here';
    echo json_encode($response);
    die();  // for when we do this AJAX wise...
}


function ph_in_collection($post,$cid){
   ob_start();
  ?>
  <span class="in-collection collections-popover--collection--icon v-collected" data-post = <?php echo $post; ?> data-collect='<?php echo $cid; ?>'>
  <svg width="17" height="14" viewBox="0 0 17 14" xmlns="http://www.w3.org/2000/svg">
      <path d="M11.036 10.864L9.62 9.45c-.392-.394-1.022-.39-1.413 0-.393.393-.39 1.023 0 1.414l2.122 2.12c.193.198.45.295.703.295.256 0 .51-.1.706-.295l4.246-4.246c.385-.385.39-1.02-.002-1.413-.393-.393-1.022-.39-1.412-.002l-3.537 3.538zM0 1c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h4.5c.552 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.552 0-1-.444-1-1z" fill="#DC5425" fill-rule="evenodd"></path>
  </svg>
  </span>
  <?php
  return ob_get_clean();
}
function ph_out_collection($post,$cid){
   ob_start();
  ?>
  <span class="out-collection collections-popover--collection--icon v-collect" data-post = <?php echo $post; ?> data-collect='<?php echo $cid; ?>'>
    <svg width="15" height="14" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg">
      <path d="M13 10V8.99c0-.54-.448-.99-1-.99-.556 0-1 .444-1 .99V10H9.99c-.54 0-.99.448-.99 1 0 .556.444 1 .99 1H11v1.01c0 .54.448.99 1 .99.556 0 1-.444 1-.99V12h1.01c.54 0 .99-.448.99-1 0-.556-.444-1-.99-1H13zM0 1c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 2 0 1.556 0 1zm0 5c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 7 0 6.556 0 6zm0 5c0-.552.453-1 .997-1h6.006c.55 0 .997.444.997 1 0 .552-.453 1-.997 1H.997C.447 12 0 11.556 0 11z" fill="#C8C0B1" fill-rule="evenodd"></path>
    </svg>
  </span>
  <?php
  return ob_get_clean();
}

#} Version 3.3 comes with a built in blog
function ph_blog_init() {
  $labels = array(
    'name'               => _x( 'Blogs', 'post type general name', 'pluginhunt' ),
    'singular_name'      => _x( 'Blog', 'post type singular name', 'pluginhunt' ),
    'menu_name'          => _x( 'Blog', 'admin menu', 'pluginhunt' ),
    'name_admin_bar'     => _x( 'Blog', 'add new on admin bar', 'pluginhunt' ),
    'add_new'            => _x( 'Add New', 'blog', 'pluginhunt' ),
    'add_new_item'       => __( 'Add New Blog', 'pluginhunt' ),
    'new_item'           => __( 'New Blog', 'pluginhunt' ),
    'edit_item'          => __( 'Edit Blog', 'pluginhunt' ),
    'view_item'          => __( 'View Blog', 'pluginhunt' ),
    'all_items'          => __( 'All Blogs', 'pluginhunt' ),
    'search_items'       => __( 'Search Blog', 'pluginhunt' ),
    'parent_item_colon'  => __( 'Parent Blog:',  'pluginhunt' ),
    'not_found'          => __( 'No blogs found.', 'pluginhunt' ),
    'not_found_in_trash' => __( 'No blogs found in Trash.', 'pluginhunt' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'blog' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    'menu_icon'          => 'dashicons-edit'
  );

  register_post_type( 'blog', $args );
}
add_action( 'init', 'ph_blog_init' );


/* New Features for v3.4 - firming up the login page */
function ph_login_logo() { ?>
<?php
  wp_enqueue_style('ph_font_a', get_template_directory_uri() . '/css/font-awesome.min.css' );
 }
add_action( 'login_enqueue_scripts', 'ph_login_logo' );


function ph_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'ph_login_logo_url' );

function ph_login_logo_url_title() {
    return 'Plugin Hunt';
}
add_filter( 'login_headertitle', 'ph_login_logo_url_title' );

function ph_custom_login_message() {
 $site_description = get_bloginfo( 'description' );
$message = "<h3 class='ph-info'>". $site_description ."</h3>";
return  $message;
}
// add_filter('login_message', 'ph_custom_login_message');









function pluginhunt_settings() {
    if (isset($_POST["update_settings"])) {
      global $wpdb;
      $querystr = "SELECT ID from $wpdb->posts WHERE post_status = 'publish'";
      $pageposts = $wpdb->get_results($querystr, OBJECT);
      foreach($pageposts as $ppost){
          if(get_post_meta($ppost->ID,'epicredvote',true)==''){
            update_post_meta($ppost->ID,'epicredvote',0);
            echo "Post updated " . $ppost->ID;
          }
      }
     ?>
        <div id="message" style="padding:10px" class="updated"><?php _e('History Updated', 'pluginhunt'); ?></div>
   <?php }
    ?>
    <div class="wrap">
        <h2><?php _e("Admin Tools", "pluginhunt"); ?></h2>
 
        <form method="POST" action="">
            <label for="num_elements">
            <?php _e('Update post records to ensure all posts are displayed','pluginhunt'); ?>
              <i><?php _e('Only needed if upgrading from an earlier version','pluginhunt'); ?></i>
            </label> 
            <input type="hidden" name="update_settings" value="Y" />
            <p>
            <input type="submit" value="<?php _e('Update History','pluginhunt'); ?>" class="button-primary"/>
            </p>
        </form>
    </div>
    <?php
}

#} Version 1.6 fixing the post meta for votes and ranking if not set
function epic_vote_updated( $post_id ) {

  // If this is just a revision, don't update.
  if ( wp_is_post_revision( $post_id ) )
    return;
  $votes = get_post_meta($post_id,'epicredvote',true);
  if($votes == ''){
    update_post_meta($post_id,'epicredvote',0);
  }
}
add_action( 'save_post', 'epic_vote_updated' );


//second page only will use this function...
function pluginhunt_findPrevious($y, $m, $d, $dateList){
        $ph_grouping = of_get_option('ph_post_group','ph-group-day');

        if($ph_grouping == 'ph-group-day'){
          foreach ($dateList as $key=>$object){
              if ($object->year == $y && $object->month == $m && $object->dayofmonth == $d){
                  $propose = $key + 1;
                  if ($propose > count($dateList)-1 ){
                      $propose = NULL;
                  }
                  return $propose;
              }
          }
        }else{
           foreach ($dateList as $key=>$object){
              if ($object->year == $y && $object->month == $m){
                  $propose = $key + 1;
                  if ($propose > count($dateList)-1 ){
                      $propose = NULL;
                  }
                  return $propose;
              }
          }         
        }
        return NULL;
}

function epic_s($array, $y,$m,$d) {
    $array = array_reverse($array);
    $search = new DateTime( "$y-$m-$d" );
    foreach($array as $k=>$v) {
        $current = new DateTime( "{$v->year}-{$v->month}-{$v->dayofmonth}" );
        if ( $current < $search )
          return $k-1;
    }
    // otherwise return false, you didn't find any correct date
    return -1;
}

function ph_banner_slider($ptype){
  global $post;
  ob_start();
  ?>
    <div class='ph-slide-wrap'>
    <div class='ph-slider'>
      <?php
        //get a random 4 posts
        $args = array(
           'posts_per_page' => 4,
           'post_type' => 'post',
        );
      query_posts($args);
      if (have_posts()) : while (have_posts()) : the_post();
          $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
        $out =  get_post_meta($post->ID, 'outbound', true);
        $n = parse_url($out);

        if($image[0] == ''){
          $image[0] = get_post_meta($post->ID,'phfeaturedimage',true);
        }
        echo "<div class='slide phsliderimg' style='background:linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ),url(".$image[0].") no-repeat 0 0;width:590px;height:266px'>";
        echo '<div class="out" data-ph-url="'. get_permalink( $post->ID ) . '"></div>';
      //  echo "<img src='" . $image[0] . "'/>";
        echo "<div class='slider-post-meta'><div class='slider-title'>". $post->post_title ."</div>";
        echo ph_output_voting($post->ID);
        echo "<div class='sub-title'>" . wp_trim_words($post->post_content, 15, '...') . "</div>";
        echo "</div>";
        echo "<div class='phoverlay2 hide'></div>";
        echo "</div>";
         endwhile;
      endif;
      ?>
    </div>
    <div class='ph-slider-nav'>
      <i class="fa fa-chevron-left"></i>
      <i class="fa fa-chevron-right"></i>
    </div>
    <div class='phoverlay'></div>
  </div>

  <?php
  wp_reset_query();
  return ob_get_clean();  
}



// Bootstrap pagination function
function ph_wp_bs_pagination($pages = '', $range = 4){  

     $showitems = ($range * 2) + 1;  

 

     global $paged;

     if(empty($paged)) $paged = 1;

 

     if($pages == '')

     {

         global $wp_query; 

     $pages = $wp_query->max_num_pages;

         if(!$pages)

         {

             $pages = 1;

         }

     }   

 

     if(1 != $pages)

     {

        echo '<div class="text-center">'; 
        echo '<nav><ul class="pagination"><li class="disabled hidden-xs"><span><span aria-hidden="true">Page '.$paged.' of '.$pages.'</span></span></li>';

         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."' aria-label='First'>&laquo;<span class='hidden-xs'> First</span></a></li>";

         if($paged > 1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."' aria-label='Previous'>&lsaquo;<span class='hidden-xs'> Previous</span></a></li>";

 

         for ($i=1; $i <= $pages; $i++)

         {

             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))

             {

                 echo ($paged == $i)? "<li class=\"active\"><span>".$i." <span class=\"sr-only\">(current)</span></span>

        </li>":"<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>";

             }

         }

 

         if ($paged < $pages && $showitems < $pages) echo "<li><a href=\"".get_pagenum_link($paged + 1)."\"  aria-label='Next'><span class='hidden-xs'>Next </span>&rsaquo;</a></li>";  

         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."' aria-label='Last'><span class='hidden-xs'>Last </span>&raquo;</a></li>";

         echo "</ul></nav>";
         echo "</div>";
     }
}

function ph_widgets_init() {

  register_sidebar( array(
    'name'          => 'Right sidebar',
    'id'            => 'sidebar-1',
    'before_widget' => '<div>',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="rounded">',
    'after_title'   => '</h2>',
  ) );

  register_sidebar( array(
    'name'          => 'Left sidebar',
    'id'            => 'ph-home-sidebar',
    'before_widget' => '<div>',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="rounded">',
    'after_title'   => '</h2>',
  ) );

  register_sidebar( array(
    'name'          => 'Woo sidebar',
    'id'            => 'ph-woo-sidebar',
    'before_widget' => '<div>',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="rounded">',
    'after_title'   => '</h2>',
  ) );

  register_sidebar( array(
    'name'          => 'Discussions Left Sidebar',
    'id'            => 'ph-discussions-sidebar',
    'before_widget' => '<div>',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="rounded">',
    'after_title'   => '</h2>',
  ) );
}
add_action( 'widgets_init', 'ph_widgets_init' );


#} Maker Meta Box
function ph_maker_box_markup(){
///enter a CSV list of makers (website usernames)   
}
 
function add_ph_maker_meta_box(){
  add_meta_box("ph-maker-meta-box", "Maker", "ph_maker_box_markup", "post", "side", "high", null);
}
 
// add_action("add_meta_boxes", "add_ph_maker_meta_box");  post poned to v3.7




#} Sticy Posts
/**
 * Adds a meta box to the post editing screen
 */
function ph_featured_meta() {
    add_meta_box( 'ph_meta', __( 'Featured', 'pluginhunt' ), 'ph_meta_callback', 'collections', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'ph_featured_meta' );
 
/**
 * Outputs the content of the meta box
 */
 
function ph_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ph_nonce' );
    $ph_stored_meta = get_post_meta( $post->ID );
    ?>
 
 <p>
    <span class="ph-row-title"><?php _e( 'Check if this is a featured collection: ', 'pluginhunt' )?></span>
    <div class="ph-row-content">
        <label for="featured-checkbox">
            <input type="checkbox" name="featured-product" id="featured-product" value="1" <?php if ( isset ( $ph_stored_meta['featured-product'] ) ) checked( $ph_stored_meta['featured-product'][0], '1' ); ?> />
            <?php _e( 'Featured', 'pluginhunt' )?>
        </label>
 
    </div>
</p>   
 
    <?php
}
 
/**
 * Saves the custom meta input
 */
function ph_meta_save( $post_id ) {
 
    // Checks save status - overcome autosave, etc.
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'ph_nonce' ] ) && wp_verify_nonce( $_POST[ 'ph_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
// Checks for input and saves - save checked as yes and unchecked at no
if( isset( $_POST[ 'featured-product' ] ) ) {
    update_post_meta( $post_id, 'featured-product', '1' );
} else {
    update_post_meta( $post_id, 'featured-product', '0' );
}
 
}
add_action( 'save_post', 'ph_meta_save' );



load_theme_textdomain('pluginhunt', get_template_directory() . '/languages');
add_action('after_setup_theme', 'pluginhunt_setup');
function pluginhunt_setup(){
    load_theme_textdomain('pluginhunt', get_template_directory() . '/languages');
}




add_action( 'after_setup_theme', 'register_my_menu' );
function register_my_menu() {
  register_nav_menu( 'primary', 'Drop Down Menu' );
  register_nav_menu( 'main','Main Header Menu');
  register_nav_menu('home', 'Home Page Menu');
  register_nav_menu('footer1', 'Footer Menu 1');
  register_nav_menu('footer2', 'Footer Menu 2');
  register_nav_menu('footer3', 'Footer Menu 3');
  register_nav_menu('footer4', 'Footer Menu 4');
  register_nav_menu('footerm', 'Footer Mobile');  
  register_nav_menu('mobiled', 'Dropdown Mobile');  

}

function pluginhunt_enqueuelogin_style() {
  wp_enqueue_style( 'core', get_template_directory_uri() .  '/login.css', false ); 
}


add_action( 'login_enqueue_scripts', 'pluginhunt_enqueuelogin_style', 10 );
class Bootstrap_Walker extends Walker_Nav_Menu{            
  function start_lvl(&$output, $depth = 0, $args = array()){
    $tabs = str_repeat("\t", $depth); 
    if ($depth == 0 || $depth == 1) { 
        $output .= "\n{$tabs}<ul class=\"dropdown-menu\">\n"; 
    } else { 
        $output .= "\n{$tabs}<ul>\n"; 
    } 
    return;
  }        
  function end_lvl(&$output, $depth = 0, $args = array()){
    if ($depth == 0) {
        $output .= '<!--.dropdown-->'; 
    } 
    $tabs = str_repeat("\t", $depth); 
    $output .= "\n{$tabs}</ul>\n"; 
    return; 
  }       
  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)  {    
    global $wp_query; 
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : ''; 
    $class_names = $value = ''; 
    $classes = empty( $item->classes ) ? array() : (array) $item->classes; 

    /* If this item has a dropdown menu, add the 'dropdown' class for Bootstrap */ 
    if ($item->hasChildren) { 
        $classes[] = 'dropdown'; 
        // level-1 menus also need the 'dropdown-submenu' class 
        if($depth == 1) { 
            $classes[] = 'dropdown-submenu'; 
        } 
    } 

    /* This is the stock Wordpress code that builds the <li> with all of its attributes */ 
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ); 
    $class_names = ' class="' . esc_attr( $class_names ) . '"'; 
    $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';             
    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : ''; 
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : ''; 
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : ''; 
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : ''; 
    #WHFIX 24/03/2015: 
    # This would work if it was an OBJECT, but it's an array... 
    $item_output = ''; if (isset($args) && is_object($args)) $item_output = $args->before; 
    # Fixed: 
    #$item_output = ''; if (isset($args)) $item_output = $args['before']; 

                 
    /* If this item has a dropdown menu, make clicking on this link toggle it */ 
    if ($item->hasChildren && $depth == 0) { 
        $item_output .= '<a'. $attributes .' class="dropdown-toggle" data-toggle="dropdown">'; 
    } else { 
        $item_output .= '<a'. $attributes .'>'; 
    } 
     
    #WHFIX 24/03/2015: 
    # This would work if it was an OBJECT, but it's an array... 
    if (isset($args) && is_object($args)) $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after; 
    # Fixed: 
    # if (isset($args)) $item_output .= $args['link_before'] . apply_filters( 'the_title', $item->title, $item->ID ) . $args['link_after']; 

    /* Output the actual caret for the user to click on to toggle the menu */             
    if ($item->hasChildren && $depth == 0) { 
        $item_output .= '<b class="caret"></b></a>'; 
    } else { 
        $item_output .= '</a>'; 
    } 

    #WHFIX 24/03/2015: 
    # This would work if it was an OBJECT, but it's an array... 
    if (isset($args) && is_object($args)) $item_output .= $args->after; 
    # Fixed:
    #if (isset($args)) $item_output .= $args['after']; 

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args ); 
    return; 
  }        
  function end_el (&$output, $item, $depth = 0, $args = array()){
    $output .= '</li>'; 
    return;
  } 
  function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) { 
    $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]); 
    return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output); 
  }         
} 

class Bootstrap_Walker_MF extends Walker_Nav_Menu{            
  function start_lvl(&$output, $depth = 0, $args = array()){
    $tabs = str_repeat("\t", $depth); 
    if ($depth == 0 || $depth == 1) { 
        $output .= "\n{$tabs}<ul class=\"dropdown-menu\">\n"; 
    } else { 
        $output .= "\n{$tabs}<ul>\n"; 
    } 
    return;
  }        
  function end_lvl(&$output, $depth = 0, $args = array()){
    if ($depth == 0) {
        $output .= '<!--.dropdown-->'; 
    } 
    $tabs = str_repeat("\t", $depth); 
    $output .= "\n{$tabs}</ul>\n"; 
    return; 
  }       
  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)  {    
    global $wp_query; 
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : ''; 
    $class_names = $value = ''; 
    $classes = empty( $item->classes ) ? array() : (array) $item->classes; 

    /* If this item has a dropdown menu, add the 'dropdown' class for Bootstrap */ 
    if ($item->hasChildren) { 
        $classes[] = 'dropdown'; 
        // level-1 menus also need the 'dropdown-submenu' class 
        if($depth == 1) { 
            $classes[] = 'dropdown-submenu'; 
        } 
    } 

    /* This is the stock Wordpress code that builds the <li> with all of its attributes */ 
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ); 
    $class_names = ' class="' . esc_attr( $class_names ) . '"'; 
    $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';             
    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : ''; 
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : ''; 
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : ''; 
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : ''; 
    #WHFIX 24/03/2015: 
    # This would work if it was an OBJECT, but it's an array... 
    $item_output = ''; if (isset($args)) $item_output = $args->before; 
    # Fixed: 
    #$item_output = ''; if (isset($args)) $item_output = $args['before']; 
        
    /* If this item has a dropdown menu, make clicking on this link toggle it */ 
    if ($item->hasChildren && $depth == 0) { 
        $item_output .= '<a'. $attributes .' class="dropdown-toggle" data-toggle="dropdown">'; 
    } else { 
        $item_output .= '<a'. $attributes .'>'; 
    } 
     
    #WHFIX 24/03/2015: 
    # This would work if it was an OBJECT, but it's an array... 
    if (isset($args)) $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after; 
    # Fixed: 
    # if (isset($args)) $item_output .= $args['link_before'] . apply_filters( 'the_title', $item->title, $item->ID ) . $args['link_after']; 

    /* Output the actual caret for the user to click on to toggle the menu */             
    if ($item->hasChildren && $depth == 0) { 
        $item_output .= '<b class="caret"></b></a>'; 
    } else { 
        $item_output .= '</a>'; 
    } 

    #WHFIX 24/03/2015: 
    # This would work if it was an OBJECT, but it's an array... 
    if (isset($args)) $item_output .= $args->after; 
    # Fixed:
    #if (isset($args)) $item_output .= $args['after']; 

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args ); 
    return; 
  }        
  function end_el (&$output, $item, $depth = 0, $args = array()){
    $output .= '</li>'; 
    return;
  } 
  function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) { 
    $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]); 
    return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output); 
  }        
} 

function epic_posts_nav() {
  global $wp_query;

  /** Stop execution if there's only 1 page */
  if( $wp_query->max_num_pages <= 1 )
    return;

  $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
  $max   = intval( $wp_query->max_num_pages );

  /** Add current page to the array */
  if ( $paged >= 1 )
    $links[] = $paged;

  /** Add the pages around the current page to the array */
  if ( $paged >= 3 ) {
    $links[] = $paged - 1;
    $links[] = $paged - 2;
  }

  if ( ( $paged + 2 ) <= $max ) {
    $links[] = $paged + 2;
    $links[] = $paged + 1;
  }

  echo '<div class="navigation" id="nav-below"><ul>' . "\n";

  /** Previous Post Link */
  if ( get_previous_posts_link() )
    printf( '<li>%s</li>' . "\n", get_previous_posts_link() );

  /** Link to first page, plus ellipses if necessary */
  if ( ! in_array( 1, $links ) ) {
    $class = 1 == $paged ? ' class="active"' : '';

    printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

    if ( ! in_array( 2, $links ) )
      echo '<li>...</li>';
  }

  /** Link to current page, plus 2 pages in either direction if necessary */
  sort( $links );
  foreach ( (array) $links as $link ) {
    $class = $paged == $link ? ' class="active"' : '';
    printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
  }

  /** Link to last page, plus ellipses if necessary */
  if ( ! in_array( $max, $links ) ) {
    if ( ! in_array( $max - 1, $links ) )
      echo '<li>...</li>' . "\n";

    $class = $paged == $max ? ' class="active"' : '';
    printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
  }

  /** Next Post Link */
  if ( get_next_posts_link() )
    printf( '<li>%s</li>' . "\n", get_next_posts_link() );

  echo '</ul></div>' . "\n";
}

add_action('wp_ajax_nopriv_ph_access_request','ph_access_request');
add_action( 'wp_ajax_ph_access_request', 'ph_access_request' );
function ph_access_request(){
  $uid = $_POST['uid'];
  update_user_meta($uid,'ph_access_request', 1);  
  //email admin to let them know about a new access request viewable from the
  //manage invites screen *added in v3.7*
  $admin_email = get_option( 'admin_email' );
  $subject = __('Access Request','pluginhunt');
  $message = __('Someone has requested access to post content','pluginhunt');
  wp_mail( $admin_email, $subject, $message );
  die();
}

add_action( 'wp_ajax_nopriv_ph_upgrade_user', 'ph_upgrade_user' );
add_action( 'wp_ajax_ph_upgrade_user', 'ph_upgrade_user' );
function ph_upgrade_user(){
  $uid = (int)$_POST['uid'];
  $to = (int)$_POST['level'];
  update_user_meta($uid,'ph_access_request', 0);   //remove from waiting list
  $wp_user_object = new WP_User($uid);
  if($to == '1'){
    $wp_user_object->set_role('author');
  }else if($to == '2'){
   $wp_user_object->set_role('contributor'); 
  }

  $msg['s'] = 'success';
  echo json_encode($msg);

  die();
}

function phtoColor($n) {
    $n = crc32($n);
    $n &= 0xffffffff;
    return("#".substr("000000".dechex($n),-6));
}

function ph_upvotes($ID){
  global $wpdb;
  $wpdb->epic   = $wpdb->prefix . 'epicred';
  $query = $wpdb->prepare("SELECT epicred_ip FROM $wpdb->epic WHERE epicred_id = %d LIMIT 5", $ID);
  $upvotes = $wpdb->get_results($query);
  $u = count($upvotes);
  if($u == 0){
    $uc = 'hide';
  }
  ob_start();
  ?>
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
      <a class="drop-target drop-theme-arrows-bounce"><?php echo get_avatar($upvote->epicred_ip, 30, $args); ?></a>
       <div class="ph-content pop-ava-v">
        <?php echo get_avatar($upvote->epicred_ip, 30, $args); ?>
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
  <?php
  return ob_get_clean();
}

add_action( 'wp_ajax_nopriv_ph_newpost', 'ph_newpost' );
add_action( 'wp_ajax_ph_newpost', 'ph_newpost' );
function ph_newpost(){

  //die if current user cannot edit posts
  if(!current_user_can( 'edit_posts' )){
      die();
  }

  //sanitise stuff
  $title          = $_POST['name'];
  $title          = sanitize_post_field( 'post_title', $title,'','db' );

  $slug           = sanitize_title($_POST['name']);
  $url            = esc_url($_POST['url']);

  $desc           = $_POST['tag'];


  $media          = json_encode($_POST['media']);
  $cat            = (int)$_POST['cat'];


  $type           = (int)$_POST['prodtype'];



  $avail          = (int)$_POST['avail'];
  $prod           = $_POST['prod'];
  $discat         = (int)$_POST['discat'];

  $slug     = wp_unique_post_slug( $slug, '','publish','post','');

  $image = $_POST['media'][0]['url'];     //image has uploaded to this point...
  if($_POST['media'][0]['source'] == 'yt'){
    $image = $_POST['media'][0]['image'];
    $islug = $_POST['media'][0]['id'];
  }else{
    $islug = '';
  }

  $current_user   = wp_get_current_user();
  $uid            = $current_user->ID;

  if(current_user_can( 'publish_posts' )){
    $status = 'publish';
  }else{
    $status = 'pending';
  }

  if($prod == 'post'){
    $ptype = 'post';
    $post = array(
    'post_content'   => $desc, 
    'post_title'     => $title, 
    'post_status'    => $status,
    'post_type'      => $ptype,
    'post_author'    => $uid,
    'post_name'      => $slug,
    'post_category'  => array($cat)
     ); 
     $wid = wp_insert_post( $post, $wp_error );


     wp_set_object_terms( $wid, $type, 'type', true );
     
     wp_set_object_terms( $wid, $avail, 'post_availibility', true );
     
     update_post_meta($wid, 'outbound', $url);
  }

  if($prod == 'woo'){
    //product stuff
    $price   = $_POST['price'];
    $reserve = $_POST['resprice'];
    $condition = (int)$_POST['condition'];
    if($condition == '1'){
      $condition = 'new';
    }else{
      $condition == 'used';
    }

    $start   = date('Y-m-d 00:00');   //start time today @ midnight
    $end     = date('Y-m-d 00:00', strtotime('+1 Week')); //end time in a week..  (can flex if you want)

    $title          = $_POST['name'];
    $title          = sanitize_post_field( 'post_title', $title,'','db' );

    $slug     = wp_unique_post_slug( $slug, '','publish','product','');
    $ptype = 'product';
    $post = array(
    'post_content'   => $desc, 
    'post_title'     => $title, 
    'post_status'    => $status,
    'post_type'      => $ptype,
    'post_author'    => $uid,
    'post_name'      => $slug,
    'post_category'  => array($cat)
     ); 
     $wid = wp_insert_post( $post, $wp_error );



     
     if($type == '1'){
       //buy now
      update_post_meta($wid,'_regular_price', number_format($price));
      wp_set_object_terms( $wid, 'simple_product', 'product_type' );
      update_post_meta($wid, '_price', number_format($price));   // price
      update_post_meta($wid,'_stock_status', 'instock');
      update_post_meta($wid,'_stock', "1"); 
      update_post_meta($wid,'_visibility', 'visible');
     }else{
       //auction
      update_post_meta($wid,'_regular_price', number_format($price));   // price
      update_post_meta($wid, '_price', number_format($price));   // price
      update_post_meta($wid, '_auction_reserved_price', number_format($reserve));  // reserve price

      update_post_meta($wid,'_auction_item_condition', $condition);          // condition
      update_post_meta($wid,'_auction_type','normal');  //auction type
      update_post_meta($wid,'_auction_dates_from',$start);  //start date
      update_post_meta($wid,'_auction_dates_to',$end);  //end date
      update_post_meta($wid,'_stock', "1"); 
      update_post_meta($wid,'_manage_stock','yes');
      update_post_meta($wid,'_sold_individually','yes');

      update_post_meta($wid,'_stock_status', 'instock');
      update_post_meta($wid,'_visibility', 'visible');



      wp_set_object_terms( $wid, 'auction', 'product_type' );
     }



  }

  if($prod == 'discussion'){
    $ptype = 'discussions';
    $slug     = wp_unique_post_slug( $slug, '','discussion','post','');
     $post = array(
    'post_content'   => $desc, 
    'post_title'     => $title, 
    'post_status'    => $status,
    'post_type'      => $ptype,
    'post_author'    => $uid,
    'post_name'      => $slug,
     ); 
     $wid = wp_insert_post( $post, $wp_error );
     wp_set_object_terms( $wid, $discat, 'discussion_category', true );

  }


    
    update_post_meta($wid,'phmedia',$media);
    update_post_meta($wid, 'epicredvote', 0);
    update_post_meta($wid, 'epicredrank',0);

    //set featured image to be from the $featured variable if the image is already uploaded to the media library skip the upload part
    
      if($image){      
            

            //extra code to upload the image and set it as the featured image

            #} if image is an external image then upload and set..  
            if($_POST['media'][0]['source'] == 'ei'){
              $upload_dir = wp_upload_dir();
              $image_data = file_get_contents($image);
              $filename = basename($image);
              if($islug!=''){
                $ext = explode(".",$filename);  
                $filename = $slug . "." . $ext[1]; 
              }
              if(wp_mkdir_p($upload_dir['path']))
                  $file = $upload_dir['path'] . '/' . $filename;
              else
                $file = $upload_dir['basedir'] . '/' . $filename;
                if($file != $image){
                  file_put_contents($file, $image_data);
                }else{
                  $file == $image;
                }     
                $wp_filetype = wp_check_filetype($filename, null );
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => sanitize_file_name($filename),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
                $attach_id = wp_insert_attachment( $attachment, $file, $wid);
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
                wp_update_attachment_metadata( $attach_id, $attach_data );           
                set_post_thumbnail($wid, $attach_id ); 
                update_post_meta($wid, 'epic_externalURL', $image );
              }elseif($_POST['media'][0]['source'] == 'med'){
                #} image is already in the media library
                $file = $_POST['media'][0]['url'];
                $filename = basename($file);

                $wp_filetype = wp_check_filetype($file, null );
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' =>     sanitize_file_name($filename),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
                $attach_id = wp_insert_attachment( $attachment, $file, $wid);

              }
              
          } 
    
  update_post_meta($wid,'_thumbnail_id', $attach_id);
  //store in post meta the $image
  update_post_meta($wid, 'phfeaturedimage',$image);

  //fire the notifications action
  $cid = get_current_user_id();
  do_action('notifyme_new_post', $wid, $cid);


  $response['success'] = 'post added';
  $response['slug'] = $slug;
  $response['perma'] = get_post_permalink($wid);
  $response['prod'] = $prod;
  echo json_encode($response); 
  die();
}

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'ph_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'ph_post_meta_boxes_setup' );

/* Meta box setup function. */
function ph_post_meta_boxes_setup() {
  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'ph_add_post_meta_boxes' );
  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'ph_save_post_class_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function ph_add_post_meta_boxes() {

  add_meta_box(
    'ph-post-class',      // Unique ID
    esc_html__( 'External Link' ),    // Title
    'ph_post_class_meta_box',   // Callback function
    'post',         // Admin page (or post type)
    'normal',         // Context
    'low'         // Priority
  );
}

/* Display the post meta box. */
function ph_post_class_meta_box( $object, $box ) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'ph_post_class_nonce' ); ?>
  <p>
  <label for="ph-post-class"><?php _e( "External link that this post title redirects to",'pluginhunt' ); ?></label>
  <br />
  <input class="widefat" type="text" name="ph-post-class" id="ph-post-class" value="<?php echo esc_attr( get_post_meta( $object->ID, 'outbound', true ) ); ?>" size="30" />
  </p>
  <?php 
}


function ph_save_post_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['ph_post_class_nonce'] ) || !wp_verify_nonce( $_POST['ph_post_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = $_POST['ph-post-class'];

  /* Get the meta key. */
  $meta_key = 'outbound';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}

function get_day_name($timestamp) {
    $date = date('d/m/Y', $timestamp);
    if($date == date('d/m/Y')) {
      $day_name = 'Today';
    } else if($date == date('d/m/Y',now() - (24 * 60 * 60))) {
      $day_name = 'Yesterday';
    }
    return $date;
}
add_filter('posts_orderby', 'posts_orderby');
function posts_orderby($orderby_for_query) {
      global $wpdb;
      $prefix = $wpdb->prefix;
      $orderby_for_query = "LEFT(" . $prefix . "posts.post_date, 10) DESC, " . $orderby_for_query;
      return $orderby_for_query;
} 


//ajax function for flagging posts.
add_action( 'wp_ajax_nopriv_epicred_ajax_flag', 'epicred_ajax_flag' );
add_action( 'wp_ajax_epicred_ajax_flag', 'epicred_ajax_flag' );
function epicred_ajax_flag(){
  //email admin if someone wants it removed....

  $current_user = wp_get_current_user();
  $n = $current_user->display_name;
  $em = get_bloginfo('admin_email');
  $bn = get_bloginfo('name');
  $pid  = (int)$_POST['post'];
  $mes = $_POST['mail'];
  $t = get_the_title( $pid );

  $hu = home_url();
  $perma = $_POST['perma'];
  $link = $hu . "/posts/" . $perma;

  $subject = __('Someone has flagged', 'pluginhunt') . " " . $t;
  $on = '<a href="'. $hu .'" class="brand">'. $bn .'</a>';
  $message = __('This is a note to let you know someone has flagged: ', 'pluginhunt') . $link . __(' as inappropriate. The reason is:  ', 'pluginhunt') . $mes;
  $headers = 'From:' . $n . " via " . $bn . "\r\n";

  wp_mail($em, $subject, $message, $headers );
  $response['pid'] = $pid;
  $response['em'] = $em;
  $response['t'] = $t;
  echo  json_encode($response);
  die();
}

add_filter( 'wp_mail_from', 'ph_wp_mail_from' );
function ph_wp_mail_from( $original_email_address ) {
  $f = of_get_option('ph_from_email');
  if($f == ''){
    return $original_email_address;
  }else{
    return $f;    
  }
}

add_filter( 'wp_mail_from_name', 'ph_wp_mail_from_name' );
function ph_wp_mail_from_name( $original_email_address ) {
  $n = get_bloginfo( 'name' );
  return $n;
}

//ajax function for emailing post to the email submitted...
add_action( 'wp_ajax_nopriv_epicred_ajax_mail', 'epicred_ajax_mail' );
add_action( 'wp_ajax_epicred_ajax_mail', 'epicred_ajax_mail' );
function epicred_ajax_mail(){

  $current_user = wp_get_current_user();
  $n = $current_user->display_name;

  $em   = $_POST['mail'];
  $pid  = (int)$_POST['post'];
  $t = get_the_title( $pid );
  $bn = get_bloginfo('name');
  $hu = home_url();
  $perma = $_POST['perma'];
  $link = $hu . "/posts/" . $perma;

  $subject = __('Check out', 'pluginhunt') . " " . $t;
  $on = '<a href="'. $hu .'" class="brand">'. $bn .'</a>';
  $message = __('I thought you might be interested in ', 'pluginhunt') . " " . $t . __(' on ', 'pluginhunt') . $bn .": ". $link;
  $headers = 'From:' . $n . " via " . $bn . "\r\n";

  wp_mail($em, $subject, $message, $headers );
  
  $response['pid'] = $pid;
  $response['em'] = $em;
  $response['perma'] = $perma;
  $response['sub'] = $subject;
  $response['msg'] = $message;

  echo  json_encode($response);

  die();
}

add_action( 'wp_ajax_nopriv_ph_follows', 'ph_follows' );
add_action( 'wp_ajax_ph_follows', 'ph_follows' );
function ph_follows(){
    global $wpdb;

    $followed      = (int)$_POST['followed']; 
    $crud          = (int)$_POST['crud'];
    $follower =      (int)$_POST['follower'];
    $ph_follows = $wpdb->prefix . "ph_follows";

    if($crud == 1){  //we are following

      $inDB = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $ph_follows WHERE follower = %d AND followed = %d", array($follower, $followed)) );
      

      if(!$inDB){
          $wpdb->insert( $ph_follows, array( 'follower' => $follower, 'followed' => $followed ),  array( '%d', '%d' ) );
           do_action('epic_notify_me_follow', $follower, $followed);
        }

    }else{   //we are unfollowing
        $wpdb->delete( $ph_follows, array( 'follower' => $follower,'followed' => $followed ),  array( '%d','%d' ) );
    }


    die();
}

add_action( 'wp_ajax_nopriv_epicred_ajax', 'epicred_ajax' );
add_action( 'wp_ajax_epicred_ajax', 'epicred_ajax' );
function epicred_ajax(){
  global $withcomments, $wp_query,$post,$wpdb, $current_user,$query_string;

  $wpdb->epic   = $wpdb->prefix . 'epicred';
  $withcomments = 1;

  $postid      = (int)$_POST['p'];   //prefix...

  $post = get_post($postid); 
  $args = array(
  'status' => 'approve',
  'post_id' => $postid, 
  );
  $comments = get_comments($args);
  
  $commarr  = array();
  $i = 0;
  foreach($comments as $comment){

    $ava = ph_get_avatar_url(get_avatar( $comment->user_id , 60 ));


    $commarr[] = array(
      'author'      =>   "$comment->comment_author" , 
      'id'          =>   "$comment->comment_ID", 
      'content'     =>   "$comment->comment_content", 
      'ava'         =>   "$ava", 
      'authorpage'  =>   "$comment->comment_author_url",
      'parent'      =>   "$comment->comment_parent"
      );
    $i++;

      }

  $query = $wpdb->prepare("SELECT epicred_ip FROM $wpdb->epic WHERE epicred_id = %d", $postid);
  $upvotes = $wpdb->get_results($query);
  foreach($upvotes as $upvote){
    $ava = ph_get_avatar_url(get_avatar( $upvote->epicred_ip , 60 ));
    $href = get_author_posts_url( $upvote->epicred_ip );
    $upv = get_userdata( $upvote->epicred_ip );

    $ups[] = array(
       'user'      => "$upv->display_name",
       'ava'       => "$ava",
       'hr'      => "$href",
      );
  }

  ob_start();
  $args = array();
  comment_form( $args, $postid );
  $commhtml = ob_get_contents();
  ob_end_clean();

  $response['title'] = $post->post_title;
  $response['content'] = $post->post_content;
  $response['comments'] = $commarr;
  $response['commentshtml'] = $commhtml;
  $response['upvotes'] = $ups;  
  echo json_encode($response);
  exit;
}

add_filter('comment_post_redirect', 'redirect_after_comment');
function redirect_after_comment($location, $comment = ""){
  $parent = $_POST['comment_post_ID'];
  return $_SERVER["HTTP_REFERER"] . "?comm=" . $parent;
}
function ph_get_avatar_url($get_avatar){
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}

function epicred_ajax2() {
  $id = ( isset( $_POST['p'] ) ) ? (int) $_POST['p'] : false;
  query_posts( array( 'p' => $id ) );
  if ( have_posts() ) {
    while( have_posts() ) {
      the_post();
      the_title();
      the_content();
      comments_template( '', true );
    }
  }
  else {
    print '<p>No posts</p>';
  }
  exit;
}
add_action( 'wp_ajax_epicred_ajax2', 'epicred_ajax2' );
add_action( 'wp_ajax_nopriv_epicred_ajax2', 'epicred_ajax2' );


function epic_query_vars_filter( $vars ){
  $vars[] = "latest";
  return $vars;
}
add_filter( 'query_vars', 'epic_query_vars_filter' );


add_filter('get_avatar','change_avatar_css');
function change_avatar_css($class) {
  $class = str_replace("class='avatar", "class='author_gravatar alignright_icon img-rounded", $class) ;
  return $class;
}

function ph_mailchimp($action){
  global $no_ph_mc, $sub_menu_ph62;
    if($sub_menu_ph62){
        $pheaderclass = 'extra-margin-top';
    }else{
        $pheaderclass = 'no-extra-margin';
    }

  $msg = of_get_option('ph_email_capture');
  $output = '
  <div class="newsletter-box well">
      <p>' . $msg  . '</p>

      <div style="display:none"><input name="utf8" type="hidden" value=""></div>
        <input class="inputfield" id="mce-EMAIL" name="EMAIL" placeholder="'. __('Your email','phtheme') .'" type="email">
        <input name="subscribe" class="subscribeButton" id="subscribeButton" type="submit" value="'. __('Subscribe','phtheme') .'">

  </div>';
  return $output;
}


function ph_the_excerpt($post_id) {
  global $post;  
  $save_post = $post;
  $post = get_post($post_id);
  $output = get_the_excerpt();
  $post = $save_post;
  return $output;
}


/** media uploader  **/
function ph_add_media_upload_scripts() {
    if ( is_admin() ) {
         return;
       }
    wp_enqueue_media();
}
add_action('wp_enqueue_scripts', 'ph_add_media_upload_scripts');


function ph_remove_medialibrary_tab($strings) {
        unset($strings["mediaLibraryTitle"]);
        return $strings;
}
// add_filter('media_view_strings','ph_remove_medialibrary_tab');

function ph_delete_attachment(){
  //delete attachement
  $aid = (int)$_POST['aid'];
  wp_delete_attachment( $aid );
  die();
}

// lets use the jQuery form upload instead of media manager
add_action('wp_print_scripts','ph_include_jquery_form_plugin');
function ph_include_jquery_form_plugin(){
   //     wp_enqueue_script( 'jquery-form',array('jquery'),false,true ); 
}




//hook the Ajax call
//for logged-in users
add_action('wp_ajax_my_upload_action', 'ph_my_ajax_upload');
//for none logged-in users
add_action('wp_ajax_nopriv_my_upload_action', 'ph_my_ajax_upload');

function ph_my_ajax_upload(){
  //simple Security check
  check_ajax_referer('upload_thumb');

  //get POST data
  //    $post_id = $_POST['post_id'];

  //require the needed files
  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');
  //then loop over the files that were sent and store them using  media_handle_upload();
  if ($_FILES) {
      foreach ($_FILES as $file => $array) {
          if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
              echo "upload error : " . $_FILES[$file]['error'];
              die();
          }
          $attach_id = media_handle_upload( $file, 0 );  //for new posts do not pass post id. duplicate this for the single post add media link
      }   
  }
  //and if you want to set that image as Post  then use:
  //  update_post_meta($post_id,'_thumbnail_id',$attach_id);
  $r['url'] = wp_get_attachment_url( $attach_id );

  echo json_encode($r);
  die();
} 



add_action('init', 'theme__init');
function theme__init(){}

add_action( 'wp_ajax_nopriv_epicred_vote_t', 'epicred_vote_t' );
add_action( 'wp_ajax_epicred_vote_t', 'epicred_vote_t' );

function epicred_vote_t(){
  global $wpdb, $current_user;
  
    wp_get_current_user();
  
  $wpdb->myo_ip   = $wpdb->prefix . 'epicred';
    
  $option = (int)$_POST['option'];
  $current = (int)$_POST['current'];
  
  $fid = $current_user->ID;
  $postid = (int)$_POST['poll'];  

  $query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $postid";
  
  $al = $wpdb->get_var($query);
    
  
  if($al == NULL){
    $query = "INSERT INTO $wpdb->myo_ip ( epicred_id , epicred_ip, epicred_option) VALUES ( $postid, $fid, $option)";
    $wpdb->query($query);
  }else{
    $query = "UPDATE $wpdb->myo_ip SET epicred_option = $option WHERE epicred_ip = $fid AND epicred_id = $postid";
    $wpdb->query($query);
  }
  
    $vote = get_post_meta($postid,'epicredvote',true);
  
    if($option == 1){
      if($al != 1){
        $vote = $vote+1;
      }
    }
     
    if($option == -1){
      if($al != -1){
        $vote = $vote-1;
      } 
    }
    update_post_meta($postid,'epicredvote',$vote);

    $response['poll'] = $postid;
    $response['vote'] = $vote;
    
    echo json_encode($response);
  
  // IMPORTANT: don't forget to "exit"
  exit;
}



add_action( 'wp_ajax_nopriv_epicred_submit', 'epicred_submit' );
add_action( 'wp_ajax_epicred_submit', 'epicred_submit' );
function epicred_submit(){

  $title      = $_POST['title'];
  $content    = $_POST['content'];
  $cat        = $_POST['cat'];
  $posttype   = $_POST['submit_type'];
  $details    = $_POST['new_post_details'];
  $status = 'pending';   
  $author = $current_user->ID;
  $taxonomy = 'category';
    $my_post = array(
      'post_title' => $title,
      'post_status' => $status,
      'post_type' => $posttype,
      'post_author' => $author,
      'post_content' => $details,
       );
    $post_id = wp_insert_post( $my_post );
    if($posttype == 'post'){
      $content = esc_url($content);
      update_post_meta($post_id,'outbound', $content);
    }
    update_post_meta( $post_id, 'details', $details);
    update_post_meta( $post_id, 'epicredvote', 0); 
    update_post_meta( $post_id, 'epicredrank', 0);    
    wp_set_post_terms( $post_id, $cat, $taxonomy);
    $permalink = get_permalink( $post_id );
    $current_user = wp_get_current_user();
    update_user_meta($current_user->ID, 'ehacklast', time());
    $response['stat'] = $status;
    $response['perma'] = $permalink;
    echo json_encode($response);
  exit;
}


function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function epic_nofollow($content){
    return preg_replace_callback('/<a[^>]+/', 'epic_nofollow_callback', $content);
}

function epic_nofollow_callback($matches) {
    $link = $matches[0];
    $site_link = home_url();
    if (strpos($link, 'rel') === false) {
        $link = preg_replace("%(href=\S(?!$site_link))%i", 'rel="nofollow" $1', $link);
    } elseif (preg_match("%href=\S(?!$site_link)%i", $link)) {
        $link = preg_replace('/rel=\S(?!nofollow)\S*/i', 'rel="nofollow"', $link);
    }
    return $link;
}
function ph_clear_br($content){
    return str_replace("<br />","<br clear='none'/>", $content);
}
add_filter('the_content', 'ph_clear_br');

function remove_http($url) {
   $disallowed = array('http://', 'https://');
   foreach($disallowed as $d) {
      if(strpos($url, $d) === 0) {
         return str_replace($d, '', $url);
      }
   }
   $url = rtrim($url," /");
   $url = ltrim($url);
   return $url;
}


/***********************Parent Theme**************/
if(function_exists('wp_get_theme')){
    $theme_data = wp_get_theme(get_option('template'));
    $theme_version = $theme_data->Version;  
} else {
    $theme_data = wp_get_theme();
    $theme_version = $theme_data['Version'];
}    
$theme_base = get_option('template');
/**************************************************/


function pluginHuntTheme_get_avatarurl($authorid){
    if (isset($authorid) && !empty($authorid)) {
      $get_avatar = get_avatar($authorid);
      preg_match("/src='(.*?)'/i", $get_avatar, $matches);
      return $matches[1];
    }

    return '';
}
if (!function_exists('write_log')) {
    function write_log ( $log )  {
   
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
      
    }
}
function ph_youtube($url) {
    $pattern = 
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )            # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
        ;
    $result = preg_match($pattern, $url, $matches);
    if (false !== $result) {
        return $matches[1];
    }
    return false;
}
function ph_custom_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'featured_image' => 'Image',
        'title' => 'Title',
        'comments' => '<span class="vers"><div title="Comments" class="comment-grey-bubble"></div></span>',
        'date' => 'Date'
     );
    return $columns;
}
function ph_custom_columns_data( $column, $post_id ) {
    switch ( $column ) {
    case 'featured_image':
        $phi = the_post_thumbnail('thumbnail');
        if($phi == ''){
          $phi ="<img src='".get_post_meta($post_id,'phfeaturedimage',true)."' width=150 />";
          echo $phi;
        }else{
          echo the_post_thumbnail( 'thumbnail' );  
        }
        break;
    }
}

add_action( 'wp_ajax_nopriv_ph_fetch', 'ph_fetch' );
add_action( 'wp_ajax_ph_fetch', 'ph_fetch' );
function ph_fetch(){
  $url = $_POST['url'];

  require_once dirname( __FILE__ ) . '/includes/OpenGraph.php';
  $graph = OpenGraph::fetch($url);
  $response['phsrc'] = $graph->image;
  $response['phtitle'] = $graph->title;
  $response['phdesc'] = $graph->description;

  if(ph_image($url)){
    //we have an image URL
    $response['phsrc'] = $url; // set to URL
  }

  echo json_encode($response);

  die();
}
add_shortcode('phfetch','ph_fetch');
add_action( 'wp_ajax_nopriv_ph_newpost_child', 'ph_newpost_child' );
add_action( 'wp_ajax_ph_newpost_child', 'ph_newpost_child' );
function ph_newpost_child(){
  
  $title          = $_POST['title'];
  $url            = $_POST['url'];
  $desc           = $_POST['desc'];
  $image          = $_POST['feat'];

  $current_user   = wp_get_current_user();
  $uid            = $current_user->ID;
  $type = get_option('wpedditnewpost', true);
  if($type == 'published'){
    $status = 'publish';
  }else{
    $status = 'pending';
  }
  $ptype = 'post';

  $post = array(
    'post_content'   => $desc, 
    'post_title'     => $title, 
    'post_status'    => $status,
    'post_type'      => $ptype,
    'post_author'    => $uid
  );  

    $wid = wp_insert_post( $post, $wp_error );

    update_post_meta($wid, 'outbound', $url);

    update_post_meta($wid, 'epicredvote', 0);
    update_post_meta($wid, 'epicredrank',0);
    //set the featured image from the URL

  if($image){
  
    //extra code to upload the image and set it as the featured image
  $upload_dir = wp_upload_dir();
  $image_data = file_get_contents($image);
  $filename = basename($image);
  if(wp_mkdir_p($upload_dir['path']))
      $file = $upload_dir['path'] . '/' . $filename;
  else
      $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);
    
    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $wid );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    wp_update_attachment_metadata( $attach_id, $attach_data );
  
    set_post_thumbnail($wid, $attach_id ); 
    update_post_meta($wid, 'epic_externalURL', $image );
    
  }


    //control the pace of posting...
    $current_user = wp_get_current_user();
    update_user_meta($current_user->ID, 'ehacklast', time());

  $response = 'post added';
  echo $response; 
  exit;
}
function ph_image($url){
  $url_headers=get_headers($url, 1);
  if(isset($url_headers['Content-Type'])){
    $type=strtolower($url_headers['Content-Type']);
    $valid_image_type=array();
    $valid_image_type['image/png']='';
    $valid_image_type['image/jpg']='';
    $valid_image_type['image/jpeg']='';
    $valid_image_type['image/jpe']='';
    $valid_image_type['image/gif']='';
    $valid_image_type['image/tif']='';
    $valid_image_type['image/tiff']='';
    $valid_image_type['image/svg']='';
    $valid_image_type['image/ico']='';
    $valid_image_type['image/icon']='';
    $valid_image_type['image/x-icon']='';
      if(isset($valid_image_type[$type])){
          return true;
      }
    }
}

/********************************************************************************************************
*
*                                         DISCUSSIONS SECTION OF THE WEBSITE
*
*******************************************************************************************************/
function ph_post_chooser(){
    ob_start();

    //just discussions
    if(of_get_option('ph_enable_discussions') == 1 && of_get_option('ph_enable_marketplace') == 0){
    ?>
    <div class="post-submission--category-toggle">
        <div class="post-submission--category-toggle-categories post-submit-just-discussion" >
            <button class="m-active v-category-product"><?php _e('Hunt it','pluginhunt');?></button>
            <button class="v-category-discussion"><?php _e('Discuss it','pluginhunt');?></button>
        </div>
    </div>
    <?php
    }

    //just woo
    else if(of_get_option('ph_enable_marketplace') == 1 && of_get_option('ph_enable_discussions') == 0){
    ?>
    <div class="post-submission--category-toggle">
      <div class="post-submission--category-toggle-categories post-submit-just-woo" >
          <button class="m-active v-category-product"><?php _e('Hunt it','pluginhunt');?></button>
          <button class="v-category-woo"><?php _e('Sell it','pluginhunt');?></button>
      </div>
    </div>
    <?php
    }
    //woo and discussions
    else if(of_get_option('ph_enable_marketplace') == 1 && of_get_option('ph_enable_discussions') == 1){
    ?>
    <div class="post-submission--category-toggle">
        <div class="post-submission--category-toggle-categories" >
            <button class="m-active v-category-product"><?php _e('Hunt it','pluginhunt');?></button>
            <button class="v-category-discussion"><?php _e('Discuss it','pluginhunt');?></button>
            <button class="v-category-woo"><?php _e('Sell it','pluginhunt');?></button>
        </div>
    </div>
    <?php
    }else{
    //just hunt 
    }
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}
add_action('ph_post_type_choice','ph_post_chooser');
function ph_excerpt_more( $more ) {
    return sprintf( '<div class="phmore"><a class="read-more" href="%1$s">%2$s</a></div>',
        get_permalink( get_the_ID() ),
        __( 'Read More', 'pluginhunt' )
    );
}
add_filter( 'excerpt_more', 'ph_excerpt_more' );
function ph_form(){
  ob_start();
  ?>

  <div class='discussion-category hide' id='discussion-category'>
     <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-tags"></i>
            </span>
            <span><?php _e('Category','pluginhunt');?></span>
          </label>
          <div class="form--field">
              <?php wp_dropdown_categories( 'show_count=0&hierarchical=1&taxonomy=discussion_category&hide_empty=0&id=discussioncat&name=discussioncat' ); ?>
        </div>
      </div>
  </div>

  <?php
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}
add_action('ph_form_extras','ph_form');
function ph_dis_content(){
  ob_start();
  ?>
  <div class='discussion-category hide' id='discussion-content'>
         <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-file-text-o"></i>
            </span>
            <span><?php _e('Discussion','pluginhunt');?></span>
          </label>
          <div class="form--field">
              <textarea name="description-discuss" id="tagline-dis"><?php _e("What do you want to discuss ","pluginhunt");?></textarea>
        </div>
      </div>
  </div>
  <?php
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}
function ph_product_link(){
  ob_start();
  ?>

      <div class="post-submission--form-row post-submission--form-row-url post-category">
        <label class="form--label" for="url">
          <span class="form--label-icon">
            <svg width="16" height="8" viewBox="0 0 16 8" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 6c-.86 0-1.783-.936-2.694-2 .91-1.064 1.833-2 2.694-2 1.103 0 2 .897 2 2s-.897 2-2 2zM4 6c-1.103 0-2-.897-2-2s.897-2 2-2c.86 0 1.783.936 2.694 2C5.784 5.064 4.86 6 4 6zm8-6c-1.606 0-2.85 1.137-4 2.453C6.85 1.137 5.606 0 4 0 1.794 0 0 1.794 0 4s1.794 4 4 4c1.606 0 2.85-1.137 4-2.453C9.15 6.863 10.394 8 12 8c2.206 0 4-1.794 4-4s-1.794-4-4-4z" fill="#BBB" fill-rule="evenodd"></path>
            </svg>
          </span>
          <span><?php _e('Link','pluginhunt');?></span></label>
          <div class="post-submission--form-field-group">
            <div class="form--field" data-reactid=".4.2.1.1.$url">
              <input class="form--input" name="url" placeholder="http://www..." type="text" id="url" value="" data-reactid=".4.2.1.1.$url.0">
            </div>
          </div>
        </div>

  <?php
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}
function ph_discuss_title(){
  ob_start();
  ?>
  <div class="post-submission--form-row post-submission--form-row-name discussion-category hide">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <svg width="14" height="15" viewBox="0 0 14 15" xmlns="http://www.w3.org/2000/svg">
                <title></title>
                <path d="M0 0v4h1.077s.768-2.005 1.927-2H6.06L6 12c.038 2.042-3 2-3 2v1h8v-1s-3.014.024-3-2l.023-10h2.975c2.057.003 1.925 2 1.925 2H14V0H0z" fill="#BBB" fill-rule="evenodd"></path>
              </svg>
            </span>
            <span><?php _e('Title','pluginhunt');?></span>
          </label>
          <div class="form--field">
          <input class="form--input" maxlength="60" name="title" placeholder="<?php _e("Discussion title","pluginhunt"); ?>" type="text" id="title" value="">
        </div>
      </div>
  <?php
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}
function ph_product_title(){
  ob_start();
  ?>
  <div class="post-submission--form-row post-submission--form-row-name post-category">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <svg width="14" height="15" viewBox="0 0 14 15" xmlns="http://www.w3.org/2000/svg">
                <title></title>
                <path d="M0 0v4h1.077s.768-2.005 1.927-2H6.06L6 12c.038 2.042-3 2-3 2v1h8v-1s-3.014.024-3-2l.023-10h2.975c2.057.003 1.925 2 1.925 2H14V0H0z" fill="#BBB" fill-rule="evenodd"></path>
              </svg>
            </span>
            <span><?php _e('Name','pluginhunt');?></span>
          </label>
          <div class="form--field">
          <input class="form--input" maxlength="60" name="name" placeholder="<?php _e("Enter the product’s name","pluginhunt"); ?>" type="text" id="name" value="">
        </div>
      </div>
  <?php
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}
function woo_product_price_single(){
  ob_start();
  ?>
  <div class='woo-category woo-single-product-price type hide' id='post-category-type'>
         <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-money"></i>
            </span>
            <span><?php _e('Price','pluginhunt');?></span>
          </label>
          <div class="form--field">
          <input class="form--input" required name="price" placeholder="<?php _e("Enter the product’s price","pluginhunt"); ?>" type="number" step="any" min="0" id="price" value="">
        </div>
      </div>
  </div>
  <?php
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;    
}
function woo_product_price_reserve(){
  ob_start();
  ?>
  <div class='woo-category woo-reserve-product-price type hide' id='post-category-type'>
         <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-money"></i>
            </span>
            <span><?php _e('Reserve price','pluginhunt');?></span>
          </label>
          <div class="form--field">
          <input class="form--input" maxlength="60" name="resprice" placeholder="<?php _e("Enter the product’s reserve price","pluginhunt"); ?>" type="number" step="any" min="0" id="resprice" value="">
        </div>
      </div>
  </div>
  <?php
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;    
}
function woo_product_title(){
  ob_start();
  ?>
  <div class="post-submission--form-row post-submission--form-row-name woo-category hide">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <svg width="14" height="15" viewBox="0 0 14 15" xmlns="http://www.w3.org/2000/svg">
                <title></title>
                <path d="M0 0v4h1.077s.768-2.005 1.927-2H6.06L6 12c.038 2.042-3 2-3 2v1h8v-1s-3.014.024-3-2l.023-10h2.975c2.057.003 1.925 2 1.925 2H14V0H0z" fill="#BBB" fill-rule="evenodd"></path>
              </svg>
            </span>
            <span><?php _e('Name','pluginhunt');?></span>
          </label>
          <div class="form--field">
          <input class="form--input" maxlength="60" name="name" placeholder="<?php _e("Enter the product’s name","pluginhunt"); ?>" type="text" id="wooname" value="">
        </div>
      </div>
  <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
function ph_woo_form(){
  if(current_user_can('publish_products')){ 
    if(function_exists('woo_simple_auction_required')){
      echo ph_woo_product_type();
    }
    echo woo_product_title();
    if(function_exists('woo_simple_auction_required')){
      echo woo_product_condition();
      echo woo_product_price_reserve();
    }
    echo woo_product_price_single();
    echo ph_woo_content();
  }
}
function woo_product_condition(){
  ob_start();
  ?>
  <div class='woo-category woo-item-condition hide type' id='post-category-type'>
         <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-question"></i>
            </span>
            <span><?php _e('Condition','pluginhunt');?></span>
          </label>
          <div class="form--field">
              <select id ='woo-product_condition'>
                <option value = '1'><?php _e('New','pluginhunt'); ?></option>
                <option value = '2'><?php _e('Used','pluginhunt'); ?></option>
              </select>
         </div>
      </div>
  </div>
  <?php 
    $content = ob_get_contents();
    ob_end_clean();
    return $content;  
}
function ph_woo_product_type(){
  ob_start();
  ?>
  <div class='woo-category hide type' id='post-category-type'>
         <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-product-hunt"></i>
            </span>
            <span><?php _e('Type','pluginhunt');?></span>
          </label>
          <div class="form--field">
              <select id ='woo-product-type'>
                <option value = '1'><?php _e('Buy Now','pluginhunt'); ?></option>
                <option value = '2'><?php _e('Auction','pluginhunt'); ?></option>
              </select>
         </div>
      </div>
  </div>
  <?php 
    $content = ob_get_contents();
    ob_end_clean();
    return $content;  
}
function ph_product_excerpt(){
  ob_start();
  ?>

        <div class="post-submission--form-row post-submission--form-row-tagline post-category">
          <label class="form--label" for="tagline">
          <span class="form--label-icon">
            <svg width="16" height="6" viewBox="0 0 16 6" xmlns="http://www.w3.org/2000/svg"><title>Slice 1</title><path d="M0 0h11v2H0V0zm0 4h16v2H0V4z" fill="#BBB" fill-rule="evenodd"></path>
            </svg>
          </span>
          <span><?php _e('Tagline','pluginhunt');?></span></label><div class="form--field">
          <input class="form--input" maxlength="100" name="tagline" placeholder="<?php _e("Describe the product briefly","pluginhunt");?>" type="text" id="tagline" value="">
        </div>
      </div>

  <?php
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}
function ph_product_content(){
  ob_start();
  ?>
  <div class='post-category type' id='post-category-type'>
         <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-file-text-o"></i>
            </span>
            <span><?php _e('Description','pluginhunt');?></span>
          </label>
          <div class="form--field">
              <textarea name="tagline" id="tagline-full"><?php _e("Describe the product","pluginhunt");?></textarea>
        </div>
      </div>
  </div>
  <?php
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}
function ph_woo_content(){
  ob_start();
  ?>
  <div class='woo-category type hide' id='post-category-type'>
         <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-file-text-o"></i>
            </span>
            <span><?php _e('Description','pluginhunt');?></span>
          </label>
          <div class="form--field">
              <textarea name="tagline" id="tagline-woo"><?php _e("Describe the product","pluginhunt");?></textarea>
        </div>
      </div>
  </div>
  <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
function ph_type(){
  ob_start();
  ?>
  <div class='post-category type' id='post-category-type'>
         <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-product-hunt"></i>
            </span>
            <span><?php _e('Type','pluginhunt');?></span>
          </label>
          <div class="form--field">
              <?php wp_dropdown_categories( 'show_count=0&hierarchical=1&taxonomy=type&hide_empty=0&id=producttype&name=producttype' ); ?>
        </div>
      </div>
  </div>
  <?php 
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}
function ph_category(){
  ob_start();
  ?>
  <div class='post-category cat' id='post-category-cat'>
          <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-tags"></i>
            </span>
            <span><?php _e('Category','pluginhunt');?></span>
          </label>
          <div class="form--field">
              <?php wp_dropdown_categories( 'show_count=0&hierarchical=1&exclude=1&hide_empty=0' ); ?>
        </div>
      </div>
  </div>
  <?php
  $content = ob_get_contents();
  ob_end_clean();
  echo $content;
}
function ph_availability(){
  ob_start();
  ?>
  <div class='post-category avail' id='post-category-avail'>
          <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <i class="fa fa-calendar-check-o"></i>
            </span>
            <span><?php _e('Availibility','pluginhunt');?></span>
          </label>
          <div class="form--field" data-reactid=".4.2.0.$name">
              <?php wp_dropdown_categories( 'show_count=0&hierarchical=1&taxonomy=post_availibility&hide_empty=0&id=postavailability&name=postavailibility' ); ?>
        </div>
      </div>
  </div>
  <?php 
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}
function ph_discussions_init() {
  $labels = array(
  'name'               => _x( 'Discussions', 'post type general name', 'pluginhunt' ),
  'singular_name'      => _x( 'Discussion', 'post type singular name', 'pluginhunt' ),
  'menu_name'          => _x( 'Discussions', 'admin menu', 'pluginhunt' ),
  'name_admin_bar'     => _x( 'Discussion', 'add new on admin bar', 'pluginhunt' ),
  'add_new'            => _x( 'Add New', 'blog', 'pluginhunt' ),
  'add_new_item'       => __( 'Add New Discussion', 'pluginhunt' ),
  'new_item'           => __( 'New Discussion', 'pluginhunt'),
  'edit_item'          => __( 'Edit Discussion', 'pluginhunt' ),
  'view_item'          => __( 'View Discussion', 'pluginhunt' ),
  'all_items'          => __( 'All Discussions', 'pluginhunt' ),
  'search_items'       => __( 'Search discussions', 'pluginhunt' ),
  'parent_item_colon'  => __( 'Parent Discussion:',  'pluginhunt' ),
  'not_found'          => __( 'No Discussions found.', 'pluginhunt' ),
  'not_found_in_trash' => __( 'No Discussions found in Trash.', 'pluginhunt')
  );

  $args = array(
  'labels'             => $labels,
  'public'             => true,
  'publicly_queryable' => true,
  'show_ui'            => true,
  'show_in_menu'       => true,
  'query_var'          => true,
  'rewrite'            => array( 'slug' => 'discuss' ),
  'capability_type'    => 'page',
  'map_meta_cap'     => true, 
  'has_archive'        => true,
  'hierarchical'       => false,
  'menu_position'      => null,
  'supports'           => array( 'title','editor','author','comments','custom-fields','thumbnail' ),
  'menu_icon'          => 'dashicons-groups',
  );

  if(of_get_option('ph_enable_discussions') == 1){
  register_post_type( 'discussions', $args );
  }

  #} extra taxonomies...
  #} Posts (Type + Availability)

  $labels = array(
            'name' => _x( 'Type', 'taxonomy general name', 'pluginhunt' ),
            'singular_name' => _x( 'Type', 'taxonomy singular name', 'pluginhunt' ),
            'search_items' =>  __( 'Search Types', 'pluginhunt' ),
            'all_items' => __( 'All Types', 'pluginhunt' ),
            'parent_item' => __( 'Parent Type', 'pluginhunt' ),
            'parent_item_colon' => __( 'Parent Type:', 'pluginhunt' ),
            'edit_item' => __( 'Edit Type', 'pluginhunt' ),
            'update_item' => __( 'Update Type', 'pluginhunt' ),
            'add_new_item' => __( 'Add New Type', 'pluginhunt' ),
            'new_item_name' => __( 'New Type', 'pluginhunt' ),
        );

  if(of_get_option('ph_type_drop') == 1){
  register_taxonomy('type',array('post'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'type'),
                    ));
  }

  $labels = array(
            'name' => _x( 'Availability', 'taxonomy general name', 'pluginhunt' ),
            'singular_name' => _x( 'Availibility', 'taxonomy singular name', 'pluginhunt' ),
            'search_items' =>  __( 'Search Availibilities', 'pluginhunt' ),
            'all_items' => __( 'All Availibilities', 'pluginhunt' ),
            'parent_item' => __( 'Parent Availibility', 'pluginhunt' ),
            'parent_item_colon' => __( 'Parent Availibility:', 'pluginhunt' ),
            'edit_item' => __( 'Edit Availibility', 'pluginhunt' ),
            'update_item' => __( 'Update Availibility', 'pluginhunt' ),
            'add_new_item' => __( 'Add New Availibility', 'pluginhunt' ),
            'new_item_name' => __( 'New Availibility', 'pluginhunt' ),
        );
  if(of_get_option('ph_type_avail') == 1){
  register_taxonomy('post_availibility',array('post'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'post-availibility'),
                    ));
  }

  #} Discussions
  $labels = array(
            'name' => _x( 'Discussion Categories', 'taxonomy general name', 'pluginhunt' ),
            'singular_name' => _x( 'Discussion Category', 'taxonomy singular name', 'pluginhunt' ),
            'search_items' =>  __( 'Search Discussion Categories', 'pluginhunt' ),
            'all_items' => __( 'All Discussion Categories', 'pluginhunt' ),
            'parent_item' => __( 'Parent Discussion Category', 'pluginhunt' ),
            'parent_item_colon' => __( 'Parent Discussion Category:', 'pluginhunt' ),
            'edit_item' => __( 'Edit Discussion Category', 'pluginhunt' ),
            'update_item' => __( 'Update Discussion Category', 'pluginhunt' ),
            'add_new_item' => __( 'Add New Discussion Category', 'pluginhunt' ),
            'new_item_name' => __( 'New Discussion Category Name', 'pluginhunt' ),
        );

  register_taxonomy('discussion_category',array('discussions'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'discussion-category'),
                    ));

  #} Initialize New Taxonomy Labels
  $labels = array(
            'name' => _x( 'Discussion Tags', 'taxonomy general name', 'pluginhunt' ),
            'singular_name' => _x( 'Discussion Tag', 'taxonomy singular name', 'pluginhunt' ),
            'search_items' =>  __( 'Search Discussion Tags', 'pluginhunt' ),
            'all_items' => __( 'All Discussion Tags', 'pluginhunt' ),
            'parent_item' => __( 'Parent Discussion Tag', 'pluginhunt' ),
            'parent_item_colon' => __( 'Parent Discussion Tag:', 'pluginhunt' ),
            'edit_item' => __( 'Edit Discussion Tag', 'pluginhunt' ),
            'update_item' => __( 'Update Discussion Tag', 'pluginhunt' ),
            'add_new_item' => __( 'Add New Discussion Tag', 'pluginhunt' ),
            'new_item_name' => __( 'New Mash Discussion Name', 'pluginhunt' ),
        );
  #} Custom taxonomy for Project Tags
  if(of_get_option('ph_enable_discussions') == 1){
  register_taxonomy('discussion_tags',array('discussions'), array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'discussion-tags'),
                    ));
  }
}
add_action( 'init', 'ph_discussions_init' );


// add_filter( 'http_request_host_is_external', 'epic_http_request_host_is_external', 10, 3 );
function epic_http_request_host_is_external( $is_external, $host, $url ) {
  return true;
}



	#} Initial Vars
	global $epicred_db_version;
	$epicred_db_version             	   = "1.0";
	$epicred_version           		       = "2.5";
	$epicred_activation                    = '';


	#} Urls
  global $epicred_urls;

	#} Page slugs
  global $epicred_slugs;
  $epicred_slugs['config']           = "epicred-plugin-config";
  $epicred_slugs['settings']         = "epicred-plugin-settings";

	#} Install function
	function epicred__install(){

    #} Default Options

    add_option('epicred_ip','no','','yes');
	add_option('wpedditnewpost','pending','','yes');



	epicred_install();
	add_option('wpedditshared','no','','yes'); 
		
	$current_user = wp_get_current_user();    //email the current user rather than admin info more likely to reach a human email 
	$userEmail = $current_user->user_email;
	$userName =  $current_user->user_firstname;
	$LastName =  $current_user->user_lastname;
	$plugin = 'WPeddit';
			
	if(get_option('wpedditshared') == 'no'){    //only send them an install email once
			wpeddit_sendReg($userEmail,$userName,$plugin);
		    update_option('wpedditshared','yes'); 
	}  
	
	
 
	}
	
	
	global $epicred_db_version;
	$epicred_db_version = "1.0";

   function epicred_install() {
   global $wpdb;
   global $epicred_db_version;

   $table_name = $wpdb->prefix . "epicred";
      
   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  epicred_id mediumint(9) NOT NULL,
	  epicred_option mediumint(9) NOT NULL,
	  epicred_ip text NOT NULL,
	  UNIQUE KEY id (id)
	    );";

	   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	   dbDelta($sql);

    $table_name = $wpdb->prefix . "epicred_comment";
      
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      epicred_id mediumint(9) NOT NULL,
      epicred_option mediumint(9) NOT NULL,
      epicred_ip text NOT NULL,
      UNIQUE KEY id (id)
        );";

       dbDelta($sql);
	 
	   add_option("epicred_db_version", $epicred_db_version);
	   


    }


#} Initialisation - enqueueing scripts/styles
function epicred__init(){
}

#} Add le admin menu
function epicred__admin_menu() {

}


#}Settings
function epicred_pages_settings() {

}


function epicred_html_settings(){
}





#} Save options changes
function epicred_html_save_settings(){
    
    global $wpdb;  #} Req
    
    $myoConfig = array();
    $myoConfig['epicred_ip'] 		=       $_POST['epicred_ip'];
    $myoConfig['trans_id'] 			=       $_POST['epicred_trans_id'];
    $myoConfig['pending']			=		$_POST['pending']; 	

    
    #} Save down
    update_option("epicred_ip", $myoConfig['epicred_ip']);
    update_option("epicred_trans_id", $myoConfig['trans_id']);
	update_option("wpedditnewpost", $myoConfig['pending']);


    #} Msg
    epicred_html_msg(0,"Saved options");
    
    #} Run standard
    epicred_html_settings();
    
}






function epicred_checkForMessages(){
    
    global $epicred_urls;

    # First deal with legit purchases
    if (isset($_GET['legit'])){
        
        # Update
        update_option('epicred_myo_firstLoadMsg',1);
        
        #} Set this here
        $flFlag = 1;
        
    } else $flFlag = get_option('epicred_myo_firstLoadMsg');
    
    
    
    if (empty($flFlag)) {
        
        epicred_html_msg(2,'<div class="sgThanks">
            <h3>Thank you for installing WPeddit</h3>
            <p>This license entitles you to use the WPeddit on a single WordPress install.</br>
            </p>
                        
            <p>Its Easy to get started, you can work it out for yourself below or read the <a href="'.$epicred_urls['docs'].'" target="_blank">WPeddit Support Manual</a>.<br />To keep up to date with WPeddit follow us on <a href="http://codecanyon.net/user/mikemayhem3030/follow/" target="_blank">CodeCanyon</a></p>
        
            <div class="sgButtons">
                <a class="buttonG" href="?page=epicred-plugin-config&legit=true">I have a License</a>
                <a class="buttonBad" href="http://codecanyon.net/item/pics-mash-image-rating-tool/3256459">I need a License</a>
            </div>
                    
            <div class="clear"></div>
        </div>');
        
    }
    
}

#} Options page
function epicred_menu() {
  
}

#} Retrieves updated news.
function wpeddit_retrieveNews(){

	
}

function wpeddit_feed_cache( $seconds )
			{
			  // change the default feed cache recreation period to 2 hours
			  return 7200;
			}



add_action( 'wp_ajax_nopriv_epicred_vote', 'epicred_vote' );
add_action( 'wp_ajax_epicred_vote', 'epicred_vote' );

function epicred_vote(){
	global $wpdb, $current_user;
	
    wp_get_current_user();
	
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';
		
    $option = (int)$_POST['option'];
	$current = (int)$_POST['current'];
	
	//if we are locked via IP set the fid variable to be the IP address, otherwise log the member ID
	if(get_option('epicred_ip') == 'yes'){
		$fid = "'" . $_SERVER['REMOTE_ADDR'] . "'";	
	}else{
		$fid = $current_user->ID;
	}

	
	$postid = (int)$_POST['poll'];	


	
	$query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $postid";
	
	$al = $wpdb->get_var($query);
    
	
	if($al == NULL){
		$query = "INSERT INTO $wpdb->myo_ip ( epicred_id , epicred_ip, epicred_option) VALUES ( $postid, $fid, $option)";
		$wpdb->query($query);
	}else{
		$query = "UPDATE $wpdb->myo_ip SET epicred_option = $option WHERE epicred_ip = $fid AND epicred_id = $postid";
		$wpdb->query($query);
	}
	
    $vote = get_post_meta($postid,'epicredvote',true);
	
		if($option == 1){
			if($al != 1){
				if($al == -1){
				$vote = $vote+2;	
				}else{
				$vote = $vote+1;
				}
			}
		}
		
		
		if($option == -1){
			
			if($al != -1){
				if($al == 1){
					$vote = $vote-2;
				}else{
				$vote = $vote-1;
				}	
			}	
		}
		update_post_meta($postid,'epicredvote',$vote);

	
		$response['poll'] = $postid;
		$response['vote'] = $vote;
    
    echo json_encode($response);
  
	// IMPORTANT: don't forget to "exit"
	exit;
}


function wpeddit_post_ranking($post_id){
	
	$x = get_post_meta($post_id, 'epicredvote', true );
	if($x == ""){
		$x = 0;
	}
	
	$ts = get_the_time("U",$post_id);
	
	if($x > 0){
		$y = 1;
	}elseif($x<0){
		$y = -1;
	}else{
		$y = 0;
	}
	
	$absx = abs($x);
	if($absx >= 1){
		$z = $absx;
	}else{
		$z = 1;
	}
	
	
	$rating = log10($z) + (($y * $ts)/45000);
	
	update_post_meta($post_id,'epicredrank',$rating);
	
	return $rating;
	
}  




function epicred_header(){


}


#} Outputs HTML message
function epicred_html_msg($flag,$msg,$includeExclaim=false){
    
    if ($includeExclaim){ $msg = '<div id="sgExclaim">!</div>'.$msg.''; }
    
    if ($flag == -1){
        echo '<div class="sgfail wrap">'.$msg.'</div>';
    }
    if ($flag == 0){ ?>
        <div id="message" class="updated fade below-h2"><p><strong>Settings saved!</strong></p></div>
    <?php }
    if ($flag == 1){
        echo '<div class="sgwarn wrap">'.$msg.'</div>';
    }
    if ($flag == 2){
        echo '<div class="sginfo wrap">'.$msg.'</div>';
    }
    if ($flag == 666){ ?>
        <div id="message" class="updated fade below-h2"><p><strong><?php echo $msg; ?>!</strong></p></div>
    <?php }
}




//new code for autoupdating and regCheck
#} Send registration info to my server
function wpeddit_sendReg($e='',$na='',$pl=''){
}


function wpeddit_hot($posts){
	global $wp_query,$post,$wpdb, $current_user,$query_string;
	wp_reset_query();
	
    $args = array(
        'meta_key' => 'epicredrank',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'posts_per_page' => $posts
    );
	
	query_posts($args);
	
	if ( have_posts() ) : ?>
 		<ul>	
		<?php while ( have_posts() ) : the_post(); ?> 
		<li><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
		<?php endwhile; ?>
		</ul>
	<?php else: ?> 

	<?php endif; 
}


function epic_reddit_index($args){
	global $wp_query,$post,$wpdb, $current_user,$query_string;
    wp_get_current_user();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

    //need to create our own query_posts for the hot and controversial
	if($args == 'hot'){
		
	if(!$wp_query) {
    global $wp_query;
    }
    
	$cat = get_query_var('cat');
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'meta_key' => 'epicredrank',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'paged' => $paged,
        'cat' => $cat
    );

    query_posts( array_merge( $args , $wp_query->query ) );
		
	}else{
	wp_reset_query(); 
	$cat = get_query_var('cat');
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
   
        'paged' => $paged,
        'cat' => $cat,
        
    );

    query_posts( $query_string );

	}
    
	if ( have_posts() ) : ?>
 			
			<?php while ( have_posts() ) : the_post(); ?> 
				
			<?php if(is_page()){
				
			}else{
			
			 $postvote = get_post_meta($post->ID, 'epicredvote' ,true);

			wpeddit_post_ranking($post->ID);

			if($postvote == NULL){
				$postvote = 0;
			}
			
			//again if IP locked set the fid variable to be the IP address.
	if(get_option('epicred_ip') == 'yes'){
		$fid = "'" . $_SERVER['REMOTE_ADDR'] . "'";	
	}else{
		$fid = $current_user->ID;
	}
			
			$query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $post->ID";
			$al = $wpdb->get_var($query);
			if($al == NULL){
				$al = 0;
			}
			if($al == 1){
				$redclassu = 'upmod';
				$redclassd = 'down';
				$redscore = 'likes';
			}elseif($al == -1){
				$redclassd = 'downmod';
				$redclassu = 'up';
				$redscore = "dislikes";
			}else{
				$redclassu = "up";
				$redclassd = "down";
				$redscore = "unvoted";
			}
			
			 ?>
			
			<div class = 'row' style = 'margin-bottom:20px'>
			
			
			<?php if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
			<script>var loggedin = 'false';</script>
			<?php }else{  ?>
			<script>var loggedin = 'true';</script>
			<?php } ?>
			
			<?php if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
			<a href="#myModal" data-toggle="modal">
			
			<?php } ?>
			
			<div class = 'span3'>

			<div class = 'reddit-voting'>
				<ul class="unstyled">
			<?php  if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
					<div class="arrow2 <?php echo $redclassu;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "up" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
					<div class="score2 <?php echo $redscore;?> score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
					<div class="arrow2 <?php echo $redclassd;?> arrow-down-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "down" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
					<?php }else{ ?>
					<div class="arrow <?php echo $redclassu;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "up" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
					<div class="score <?php echo $redscore;?> score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
					<div class="arrow <?php echo $redclassd;?> arrow-down-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "down" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>	
					<?php }  ?>
				</ul>
			</div>	
			<?php  if(!is_user_logged_in() && get_option('epicred_ip') == 'no') { ?>
			</a>
			<?php } ?>

			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
			<?php if ( has_post_thumbnail() ) { ?>
				<div class = 'reddit-image pull-left' style = 'width:180px'>
					<img src = "<?php echo $image[0]; ?>" width = "180px" class="img-rounded">
				</div>
			<?php }else{ ?>
				<div class = 'reddit-image pull-left' style = 'width:180px'>
					<img src = "<?php echo get_post_meta( $post->ID, 'wpedditimage', true ); ?>" width = "180px" class="img-rounded">
				</div>
			<?php } ?>
			
			</div>
			
			<div class = 'span5'>
				<div class = 'reddit-post pull-left'>
				<p class = 'title'><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
				<span class = 'tagline'>submitted <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?> by <?php the_author_posts_link(); ?> in <?php the_category(' '); ?></span> 
					
					<?php if(!is_single()){ ?>
					<p style = "text-align:justify">
					<?php the_excerpt(); ?> 
					</p>
					<?php }else{ ?>
					<?php the_content(); ?> 
					<?php } ?>
									<a href="<?php comments_link(); ?>">
				    <?php comments_number( 'no comments', 'one comment', '% comments' ); ?>. 
				</a>
				</div>
			

			</div>
			
			<div style="clear:both"></div>
			
				<div class = 'span8 pull-right'>
					<?php comments_template(); ?>
				</div>
			
			</div>
			
			<?php } ?>
			
			<?php endwhile; ?>

			<?php else: ?> 
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p> 
			<?php endif; ?>
	
	
            <?php echo get_next_posts_link('More Posts'); ?>
	
			
			<?php wp_reset_query(); ?>
			
<?php			}



add_filter( 'manage_edit-post_columns', 'wpeddit_post_columns' ) ;

function wpeddit_post_columns( $columns ) {

    $new_columns = array(

		'rating' => __('Ranking', 'WPeddit'),

    );
	
	return array_merge($columns, $new_columns);

}

add_action( 'manage_post_posts_custom_column', 'wpeddit_post_columnsw', 10, 2 );

function wpeddit_post_columnsw( $column, $post_id ) {
    global $post;

    switch( $column ) {
        
        
        case 'rating' :

            /* Get the post meta. */
            echo number_format((double)get_post_meta( $post_id, 'epicredvote', true ),0);

            break;

        /* Just break out of the switch statement for everything else. */
default:
            break;
    }
}

add_filter( 'manage_edit-post_sortable_columns', 'wpeddit_sortable_columns' );

function wpeddit_sortable_columns( $columns ) {

    $columns['rating'] = 'rating';

   
    return $columns;
}


/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'wpeddit_post_load' );

function wpeddit_post_load() {
    add_filter( 'request', 'wpeddit_sort_post' );
}

/* Sorts the pics. */
function wpeddit_sort_post( $vars ) {

    /* Check if we're viewing the 'picsmash' post type. */
    if ( isset( $vars['post_type'] ) && 'post' == $vars['post_type'] ) {

        /* Check if 'orderby' is set to 'rating'. */
        if ( isset( $vars['orderby'] ) && 'rating' == $vars['orderby'] ) {

            /* Merge the query vars with our custom variables. */
            $vars = array_merge(
                $vars,
                array(
                    'meta_key' => 'epicredvote',
                    'orderby' => 'meta_value_num'
                )
            );
        }
        

    }

    return $vars;
}


function wpeddit_comment_ranking($comment_id){
    $ups        =   get_comment_meta($comment_id,'wpeddit_comment_up',true);
    $downs      =   get_comment_meta($comment_id,'wpeddit_comment_down',true);
    $n = $ups + $downs;
    if($n == 0){
        return 0;
    }else{
    $z = 1.0;
    $phat = $ups / $n;
    $rating = sqrt($phat+$z*$z/(2*$n)-$z*(($phat*(1-$phat)+$z*$z/(4*$n))/$n))/(1+$z*$z/$n);
    }   
    update_comment_meta($comment_id,'wpeddit_comment_rank',$rating);
    return $rating;
}

add_action( 'wp_ajax_nopriv_epicred_vote_comment', 'epicred_vote_comment' );
add_action( 'wp_ajax_epicred_vote_comment', 'epicred_vote_comment' );

function epicred_vote_comment(){
    global $wpdb, $current_user;
    
    wp_get_current_user();
    
    $wpdb->myo_ip   = $wpdb->prefix . 'epicred_comment';
        
    $option = (int)$_POST['option'];
    $current = (int)$_POST['current'];
    $postid = (int)$_POST['poll'];  
        
    //if we are locked via IP set the fid variable to be the IP address, otherwise log the member ID
    if(get_option('epicred_ip') == 'yes'){
        $ipAddr = isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) ? $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
        $fid = "'" . $ipAddr . "'"; 
    }else{
        $fid = $current_user->ID;
    }
    
    $query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $postid";
    
    $al = $wpdb->get_var($query);
    
    
    if($al == NULL){
        $query = "INSERT INTO $wpdb->myo_ip ( epicred_id , epicred_ip, epicred_option) VALUES ( $postid, $fid, $option)";
        $wpdb->query($query);
    }else{
        $query = "UPDATE $wpdb->myo_ip SET epicred_option = $option WHERE epicred_ip = $fid AND epicred_id = $postid";
        $wpdb->query($query);
    }
    
    $ups        =   get_comment_meta($postid,'wpeddit_comment_up',true);
    $downs      =   get_comment_meta($postid,'wpeddit_comment_down',true);
    $vote       =   get_comment_meta($postid,'wpeddit_comment_votes',true);
    
        if($option == 1){
            if($al != 1){
                if($al == -1){
                $vote = $vote+2;    
                $downs = $downs - 1;
                $ups = $ups + 1;
                }else{
                $vote = $vote+1;
                $ups = $ups+1;
                }
            }
        }
        
        
        if($option == -1){
            
            if($al != -1){
                if($al == 1){
                    $vote = $vote-2;
                    $ups = $ups -1;
                    $downs = $downs + 1;
                }else{
                $vote = $vote-1;
                $downs = $downs + 1;
                }   
            }   
        }
        update_comment_meta($postid,'wpeddit_comment_votes',$vote);
        update_comment_meta($postid,'wpeddit_comment_up',$ups);
        update_comment_meta($postid,'wpeddit_comment_down',$downs);

    
        $response['poll'] = $postid;
        $response['vote'] = $vote;
    
    echo json_encode($response);
  
    // IMPORTANT: don't forget to "exit"
    exit;
}








?>