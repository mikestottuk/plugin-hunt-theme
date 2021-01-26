<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns:fb="http://ogp.me/ns/fb#">
  <head>
    <meta charset="utf-8">
    <title><?php wp_title(''); ?></title> 
    <meta name="description" content="404 not found">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">

    <meta name="twitter:widgets:csp" content="on">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
 
  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };
 
  return t;
}(document, "script", "twitter-wjs"));</script>

<?php  		
global $post;
#}code used for throttling of submitted content...
$current_user = wp_get_current_user();

if ( ! isset( $content_width ) ) $content_width = 900;

wp_head();   
?>

  <script>
    <?php 
      $ph_ga = of_get_option('ph_ga');
      echo $ph_ga;
    ?>
    </script>
</head>
<div id="fb-root"></div>
 <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '427029257462972',
          xfbml      : true,
          version    : 'v2.7'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
</script>

<body <?php 
  if (!isset($class)) $class = ''; 
  body_class( $class ); ?> style='background: <?php echo of_get_option('ph_main_color'); ?>;'>


