<?php
/**
 * Scripts and Styles
 *
 * @package   Theme Framework
 * @author    Mike Stott <mike@epicplugins.com>
 * @license   GPL-2.0+
 * @link      http://epicplugins.com
 * @copyright 2010-2015 Epic Plugins
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//front end scripts and styles 
function ph_frontend_scripts(){
  wp_enqueue_script("jquery");  
    /*  Styles */

    if(wp_is_mobile()){
      wp_enqueue_style('wpthememob', get_template_directory_uri() . '/mobile-style.css' );   //lets think about re-working this one...
    }

    wp_enqueue_style('eStoreboot', get_template_directory_uri() . '/css/bootstrap.css' ); 
    wp_enqueue_style('ph_font_a', get_template_directory_uri() . '/css/font-awesome.min.css' );
    wp_enqueue_style('ph_tip', get_template_directory_uri() . '/css/drop-theme-arrows-bounce.css' );   
    wp_enqueue_style('ph_main', get_template_directory_uri() . '/style.css' );   
    wp_enqueue_style('ph_animate_css', get_template_directory_uri() . '/css/animate.min.css' ); 
    wp_enqueue_style('ph_slick', get_template_directory_uri() . '/slick/slick.css' ); 
    wp_enqueue_style('ph_slick_theme', get_template_directory_uri() . '/slick/slick-theme.css' ); 

    /* Scripts */
    wp_register_script( 'ph_drop_js', get_template_directory_uri() . '/js/drop.min.js', array( 'jquery' ), '5.0.3', true ); 
 //   wp_enqueue_script( 'ph_drop_js' );
    wp_register_script( 'ph_main', get_template_directory_uri() . '/js/main.js', array( 'jquery' ) ,'5.0.3', true );
    wp_enqueue_script( 'ph_main' );
    wp_register_script( 'ph_modernizr', get_template_directory_uri() . '/js/modernizr.js', array( 'jquery' ) , '5.0.3', true ); 
    wp_enqueue_script( 'ph_modernizr' );
    wp_register_script( 'ph-bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ) , '5.0.3', true ); 
    wp_enqueue_script( 'ph-bootstrap' );
    wp_register_script( 'phmobmenu', get_template_directory_uri() . '/js/phmobmenu.js', array( 'jquery' ) , '5.0.3', true );
    wp_enqueue_script( 'phmobmenu' );
    wp_register_script( 'ph_animate_js', get_template_directory_uri() . '/js/animatedModal.min.js', array( 'jquery' ) , '5.0.3', true ); 
    wp_enqueue_script( 'ph_animate_js' );

    wp_register_script( 'ph_fitvid', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ) , '5.0.3', true ); 
    wp_enqueue_script( 'ph_fitvid' );

    wp_register_script('ph-tiny',get_template_directory_uri() . '/js/tinymce.min.js', array( 'jquery' ) , '5.0.3', true );
    wp_enqueue_script('ph-tiny');

    wp_register_script('ph-slick',get_template_directory_uri() . '/slick/slick.min.js', array( 'jquery' ) , '5.0.3', true );
    wp_enqueue_script('ph-slick');

    global $ph_version;

    //scripts with variables
    $fid = get_current_user_id();
    $type = get_option('wpedditnewpost', true);
    if($type == 'published'){
      $msg = get_theme_mod( 'phsuccess_setting', '<div class="new-post-header">POST SUBMITTED</div><div class="new-post-subheader">Your post is now live </div>' );
    }else{
      $msg= get_theme_mod( 'phpending_setting', '<div class="new-post-header">POST SUBMITTED</div><div class="new-post-subheader">Your post will be reviewed by our team and if suitable make it onto the homepage </div>' );
    }
    $home = home_url();
    $lm =  of_get_option('ph_load_more'); 
    $atc = __('Add to Collection','pluginhunt');
	$nonce = wp_create_nonce('ph_security_key_95525');

    $hunting = of_get_option('ph_hunting_string');

    wp_localize_script( 'ph-bootstrap', 'HuntAjax', array('ph_tweet' => of_get_option('ph_tweet_at') ,  'atc' => $atc, 'epic_more' => $lm, 'epichome' => $home, 'logged' => $fid ,'ajaxurl' => admin_url( 'admin-ajax.php' ), 'ajax_nonce' => $nonce ) );
    wp_localize_script( 'ph-bootstrap', 'PHnew', array('success' => $msg, 'ph_hunting_string' => $hunting ));
   
    wp_register_script( 'ph-hunt-main', get_template_directory_uri() . '/js/epictheme.min.js', array( 'jquery' ) , $ph_version, true ); 
    wp_enqueue_script( 'ph-hunt-main');

    $mail       = of_get_option('ph_mc_msg');
    $hc         = of_get_option('ph_newpost_header');
    $dc         = of_get_option('ph_discuss_header');
    $wc         = of_get_option('ph_woo_header');
    $fly        = of_get_option('ph_post_flyout','one');
    $sub        = of_get_option('ph_post_submit_option','one');
    $sub_url    = of_get_option('ph_post_submit_page');

    $args = array(
            'ph_mc_msg'        =>  $mail,
            'ph_post_hc'       =>  $hc,
            'ph_dis_hc'        =>  $dc,
            'ph_woo_hc'        =>  $wc,
            'ph_post_fly'      =>  $fly,
            'ph_post_sub'      =>  $sub,
            'ph_post_sub_url'  => $sub_url
    );


    wp_localize_script('ph-hunt-main','phe',$args);


    global $epicred_slugs, $epicred_taxonomy; #} Req
    
    #} Admin & Public
    wp_enqueue_script("jquery");
    wp_enqueue_script( 'jquery-form',array('jquery')); 
    wp_enqueue_script('epicred-ajax', get_template_directory_uri() .'/js/epicred.js',array('jquery'));
    wp_localize_script( 'epicred-ajax', 'EpicAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
	if(!is_admin()){
    wp_enqueue_style('epicred-css', get_template_directory_uri() . '/css/epicred.css' );
	}
    
    #} Admin only
    if (is_admin()) {
    wp_enqueue_style('myo-polling-admin-css', get_template_directory_uri() . '/css/epicadmin.css' );
    }


}
add_action( 'wp_enqueue_scripts', 'ph_frontend_scripts' );


//back end scripts and styles
function ph_backend_scripts(){}
add_action( 'admin_enqueue_scripts', 'ph_backend_scripts' );